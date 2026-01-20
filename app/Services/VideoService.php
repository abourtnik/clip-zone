<?php

namespace App\Services;

use App\Data\NextVideoDTO;
use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class VideoService {

    public function getSuggestedVideos(Video $parentVideo): Collection
    {
        $user_videos = Video::whereNot('id', $parentVideo->id)
            ->where('user_id', $parentVideo->user->id)
            ->active()
            ->when($parentVideo->category, fn($q) => $q->orderByRaw("FIELD(category_id, ".$parentVideo->category->id.") DESC"))
            ->inRandomOrder()
            ->orderBy('views', 'desc')
            ->limit(3)
            ->get();

        if ($parentVideo->category) {
            $category_videos = Video::whereNotIn('id', [$parentVideo->id, ...$user_videos->pluck('id')])
                ->where('category_id', $parentVideo->category->id)
                ->active()
                ->inRandomOrder()
                ->orderBy('views', 'desc')
                ->limit(6 - $user_videos->count())
                ->get();
        } else {
            $category_videos = collect();
        }

        $random_videos = Video::whereNotIn('id', [$parentVideo->id, ...$user_videos->pluck('id'), ...$category_videos->pluck('id')])
            ->active()
            ->inRandomOrder()
            ->orderBy('views', 'desc')
            ->limit(9 - ($user_videos->count() + $category_videos->count()))
            ->get();

        return $user_videos->merge($category_videos)->merge($random_videos)->load('user');
    }

    public function getNextVideo (Collection $suggestedVideos, ?Playlist $playlist, ?Collection $playlistVideos, ?int $currentIndex) : NextVideoDTO|null {

        if ($playlist && ($currentIndex + 1 < $playlist->videos_count)) {

            /** @var Video|null $nextPlaylistVideo */
            $nextPlaylistVideo = $playlistVideos->skip($currentIndex + 1)->first(function (Video $video, int $index) {
                return $video->user->is(Auth::user()) || $video->is_public;
            });

            if ($nextPlaylistVideo) {
                return NextVideoDTO::fromPlaylist($nextPlaylistVideo, $playlist);
            }

            /** @var Video $video */
            $video = $suggestedVideos->first();

            return NextVideoDTO::fromSuggested($video);

        }

        if ($suggestedVideos->isEmpty()) {
            return null;
        }

        /** @var Video $video */
        $video = $suggestedVideos->first();

        return NextVideoDTO::fromSuggested($video);
    }
}
