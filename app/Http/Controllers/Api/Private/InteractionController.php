<?php

namespace App\Http\Controllers\Api\Private;

use App\Events\Video\VideoInteracted;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionRequest;
use App\Http\Resources\InteractionsResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
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

        $interaction = $likeable->interactions()
            ->where('user_id', Auth::id())
            ->first();

        if ($interaction && $interaction->status === $status) {
            $interaction->delete();
            $this->dispatchEvent($likeable, Auth::user(), $interaction->status, null);
            return response()->noContent();
        }

        $likeable->interactions()->updateOrCreate(
            ['user_id' => Auth::id()],
            ['status' => $status, 'perform_at' => now()]
        );

        $this->dispatchEvent($likeable, Auth::user(), $interaction->status ?? null, $status);

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
                        $query
                            ->withExists([
                                'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', Auth::id())
                            ])
                            ->withTrashed();
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

    private function dispatchEvent(Model $likeable, User $user, ?bool $previousStatus, ?bool $currentStatus): void
    {
        if ($likeable instanceof Video) {
            VideoInteracted::dispatch(
                $likeable,
                $user,
                $previousStatus,
                $currentStatus
            );
        }
    }
}
