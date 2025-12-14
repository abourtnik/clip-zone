<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Resources\Playlist\PlaylistListResource;
use App\Http\Resources\User\UserShowResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController
{
    public function show(User $user): UserShowResource
    {
        return new UserShowResource(
            $user
                ->loadCount(['subscribers', 'videos', 'videos_views'])
                ->load([
                    'pinned_video',
                    'videos' => function ($q) {
                        $q->with('user')
                            ->active()
                            ->latest('published_at')
                            ->limit(8);
                    },
                    'playlists' => function ($q) {
                        $q->withCount('videos')
                            ->withWhereHas('videos', function($q) {
                                $q->active()
                                    ->with('user')
                                    ->limit(8);
                            })
                            ->active()
                            ->latest('updated_at')
                            ->limit(6);
                    },
                ])
                ->loadExists([
                    'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', Auth::id()),
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
                ->with('user')
                ->when($sort === 'latest', fn($query) => $query->latest('published_at'))
                ->when($sort === 'popular', fn($query) => $query->orderBy('views', 'DESC'))
                ->when($sort === 'oldest', fn($query) => $query->oldest('published_at'))
                ->cursorPaginate(24)
        );
    }

    public function playlists(User $user): ResourceCollection
    {
        return PlaylistListResource::collection(
            $user->playlists()
                ->active()
                ->withCount('videos')
                ->having('videos_count', '>', 0)
                ->with('videos')
                ->latest('updated_at')
                ->cursorPaginate(15)
        );
    }
}
