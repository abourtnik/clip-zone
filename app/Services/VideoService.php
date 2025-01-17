<?php

namespace App\Services;

use App\Models\Playlist;
use App\Models\Video;
use App\Models\Videos\NextVideo;
use App\Models\Videos\PlaylistVideo;
use App\Models\Videos\SuggestedVideo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class VideoService {

    public function getSuggestedVideos(Video $parentVideo): Collection
    {
        $user_videos = Video::whereNot('id', $parentVideo->id)
            ->where('user_id', $parentVideo->user->id)
            ->active()
            ->with(['user'])
            ->withCount(['views'])
            ->when($parentVideo->category, fn($q) => $q->orderByRaw("FIELD(category_id, ".$parentVideo->category->id.") DESC"))
            ->inRandomOrder()
            ->orderBy('views_count', 'desc')
            ->limit(3)
            ->get();

        if ($parentVideo->category) {
            $category_videos = Video::whereNotIn('id', [$parentVideo->id, ...$user_videos->pluck('id')])
                ->where('category_id', $parentVideo->category->id)
                ->active()
                ->with(['user'])
                ->withCount(['views'])
                ->inRandomOrder()
                ->orderBy('views_count', 'desc')
                ->limit(6 - $user_videos->count())
                ->get();
        } else {
            $category_videos = collect();
        }

        $random_videos = Video::whereNotIn('id', [$parentVideo->id, ...$user_videos->pluck('id'), ...$category_videos->pluck('id')])
            ->active()
            ->with(['user'])
            ->withCount(['views'])
            ->inRandomOrder()
            ->orderBy('views_count', 'desc')
            ->limit(9 - ($user_videos->count() + $category_videos->count()))
            ->get();

        return $user_videos->merge($category_videos)->merge($random_videos);
    }

    public function getNextVideo (Collection $suggestedVideos, ?Playlist $playlist, ?int $currentIndex) : NextVideo | null {

        if ($playlist && ($currentIndex + 1 !== $playlist->videos_count)) {

            $nextPlaylistVideo = $playlist->videos->skip($currentIndex + 1)->first(function (Video $video, int $index) {
                return $video->user->is(Auth::user()) || $video->is_public;
            });

            if ($nextPlaylistVideo) {
                return new PlaylistVideo($nextPlaylistVideo, $playlist);
            }

            return new SuggestedVideo($suggestedVideos->first());

        }

        if ($suggestedVideos->isEmpty()) {
            return null;
        }

        return new SuggestedVideo($suggestedVideos->first());
    }
}
