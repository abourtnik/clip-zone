<?php

namespace App\Http\Controllers\Api;

use App\Events\UserSubscribed;
use App\Http\Resources\Playlist\PlaylistListResource;
use App\Http\Resources\User\UserListResource;
use App\Http\Resources\User\UserShowResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function show(User $user): UserShowResource
    {
        return new UserShowResource(
            $user
                ->loadCount(['subscribers', 'videos', 'videos_views'])
                ->load([
                    'pinned_video' => fn($q) => $q->withCount('views'),
                    'videos' => function ($q) {
                        $q->with('user')
                            ->withCount('views')
                            ->active()
                            ->latest('publication_date')
                            ->limit(8);
                    },
                    'playlists' => function ($q) {
                        $q->withCount('videos')
                            ->withWhereHas('videos', function($q) {
                                $q->active()
                                    ->withCount('views')
                                    ->with('user')
                                    ->limit(8);
                            })
                            ->active()
                            ->latest('updated_at')
                            ->limit(6);
                    },
                ])
                ->loadExists([
                    'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', auth('sanctum')->user()?->id),
                ])
        );
    }

    public function videos(User $user, Request $request): ResourceCollection
    {
        $sort = $request->get('sort', 'latest');
        $excludePinned = $request->exists('excludePinned');

        return VideoListResource::collection(
            $user->videos()
                ->active()
                ->when($excludePinned, fn($query) => $query->where('id', '!=', $user->pinned_video->id))
                ->withCount('views')
                ->with('user')
                ->when($sort === 'latest', fn($query) => $query->latest('publication_date'))
                ->when($sort === 'popular', fn($query) => $query->orderByRaw('views_count DESC'))
                ->when($sort === 'oldest', fn($query) => $query->oldest('publication_date'))
                ->paginate(24)
        );
    }

    public function playlists(User $user, Request $request): ResourceCollection
    {
        $video_id = $request->get('video_id');

        return PlaylistListResource::collection(
            $user->playlists()
                ->when(Auth::guest(), fn($query) => $query->active())
                ->withCount('videos')
                ->with('videos')
                ->when($video_id, function ($query) use ($video_id) {
                    $query->withExists([
                        'videos as has_video' => fn($q) => $q->where('video_id', $video_id)
                    ]);
                })
                ->paginate(15)
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
                ->withCount('views')
                ->latest('publication_date')
                ->paginate(15)
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
                ->paginate(15)
        );
    }

}
