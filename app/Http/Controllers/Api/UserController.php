<?php

namespace App\Http\Controllers\Api;

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
            $user->loadCount(['subscribers', 'videos', 'videos_views'])
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
                ->paginate(24)
        );
    }

}
