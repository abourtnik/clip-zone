<?php

namespace App\Http\Controllers;

use App\Http\Requests\Interaction\InteractionRequest;
use App\Http\Resources\InteractionsResource;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    private function perform (InteractionRequest $request, $status) : JsonResponse {

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

        return response()->json();

    }

    public function like(InteractionRequest $request): JsonResponse
    {
        return $this->perform($request, true);
    }

    public function dislike(InteractionRequest $request): JsonResponse
    {
        return $this->perform($request, false);
    }

    public function list (Request $request) : ResourceCollection {

        $filter = $request->get('filter', 'all');
        $search = $request->get('search');
        $video_id = $request->get('video_id');

        $video = Video::findOrFail($video_id)->loadCount('comments');

        return InteractionsResource::collection(
            $video->interactions()
                ->with([
                    'user' => function ($query) {
                        $query->withCount(['subscribers as is_subscribe_to_current_user' => function($query) {
                            $query->where('subscriber_id', Auth::id());
                        }]);
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
