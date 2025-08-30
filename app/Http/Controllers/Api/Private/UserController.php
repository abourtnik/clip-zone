<?php

namespace App\Http\Controllers\Api\Private;

use App\Events\UserSubscribed;
use App\Http\Resources\AccountResource;
use App\Http\Resources\Playlist\PlaylistListResource;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\Video\MyVideoListResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class UserController
{
    public function user (Request $request) : AccountResource{
        return new AccountResource($request->user());
    }

    public function videos(Request $request): ResourceCollection
    {
        return MyVideoListResource::collection(
            $request
                ->user()
                ->videos()
                ->withCount(['likes','comments'])
                ->latest('updated_at')
                ->latest('id')
                ->cursorPaginate(15)
        );
    }

    public function playlists(Request $request): ResourceCollection
    {
        $video_id = $request->get('video_id');

        return PlaylistListResource::collection(
            $request
                ->user()
                ->playlists()
                ->orderBy('is_deletable', 'ASC')
                ->latest('created_at')
                ->withCount('videos')
                ->with('videos')
                ->when($video_id, function ($query) use ($video_id) {
                    $query->withExists([
                        'videos as has_video' => fn($q) => $q->where('video_id', $video_id)
                    ]);
                })
                ->cursorPaginate(15)
        );
    }

    public function subscribe (Request $request, User $user) : Response {
        $subscription = $request->user()->subscriptions()->toggle($user);
        UserSubscribed::dispatchIf($subscription['attached'], $user, $request->user());
        return response()->noContent();
    }

    public function subscriptionsVideos(Request $request): ResourceCollection
    {
        return VideoListResource::collection(
            $request
                ->user()
                ->subscriptions_videos()
                ->active()
                ->with('user')
                ->latest('publication_date')
                ->cursorPaginate(15)
        );
    }

    public function subscriptionsChannels(Request $request): ResourceCollection
    {
        return UserListResource::collection(
            $request
                ->user()
                ->subscriptions()
                ->withExists([
                    'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', auth('sanctum')->user()?->id),
                ])
                ->withCount('subscribers')
                ->latest('subscribe_at')
                ->cursorPaginate(15)
        );
    }

    public function logout (Request $request): Response {

        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

}
