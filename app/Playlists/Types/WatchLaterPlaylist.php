<?php

namespace App\Playlists\Types;

use App\Playlists\CustomPlaylist;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class WatchLaterPlaylist extends CustomPlaylist
{
    public static function getName(): string
    {
        return 'Watch Later';
    }

    public function getVideos(): Collection
    {
        return $this->playlist
            ->load(['videos' => fn(BelongsToMany $query) => $query->with('user')])
            ->videos;
    }

    public function getActions(): Collection
    {
        return collect([
            'Remove watched videos' => route('custom-playlist.clear-watched-videos')
        ]);
    }

    public function removeWatchedVideos(): int
    {
        $watchedVideoIds = Auth::user()
            ->views()
            ->pluck('video_id')
            ->toArray();

        return $this->playlist->videos()->detach($watchedVideoIds);
    }
}
