<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionRequest;
use App\Http\Resources\InteractionsResource;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    private function perform (InteractionRequest $request, bool $status) : Response {

        list('model' => $model, 'id' => $id) = $request->only('model', 'id');

        $likeable = $model::findOrFail($id);

        $this->authorize('interact', $likeable);

        $interaction = $likeable->interactions()->whereRelation('user', 'id', Auth::user()->id)->first();

        if ($interaction) {

            $interaction->delete();

            if ($interaction->status != $status) {
                Auth::user()->interactions()->create([
                    'likeable_type' => get_class($likeable),
                    'likeable_id' => $likeable->id,
                    'status' => $status,
                ]);
            }
        }

        else {
            Auth::user()->interactions()->create([
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id,
                'status' => $status,
            ]);
        }

        return response()->noContent();

    }

    public function like(InteractionRequest $request): Response
    {
        return $this->perform($request, true);
    }

    public function dislike(InteractionRequest $request): Response
    {
        return $this->perform($request, false);
    }

    public function list (Video $video, Request $request) : ResourceCollection {

        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        return InteractionsResource::collection(
            $video
                ->interactions()
                ->with([
                    'user' => function ($query) {
                        $query->withExists([
                            'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', auth('sanctum')->user()->id)
                        ]);
                    },
                    'likeable'
                ])
                ->when($filter === 'up', fn($query) => $query->where('status', true))
                ->when($filter === 'down', fn($query) => $query->where('status', false))
                ->when($search, fn($query) => $query->whereRelation('user', 'username', 'LIKE',  '%'.$search.'%'))
                ->latest('perform_at')
                ->paginate(40)
                ->withQueryString()
        );
    }
}
