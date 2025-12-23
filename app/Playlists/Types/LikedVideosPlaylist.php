<?php

namespace App\Playlists\Types;

use App\Models\Video;
use App\Playlists\CustomPlaylist;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LikedVideosPlaylist extends CustomPlaylist
{
    public static function getName(): string
    {
        return 'Liked Videos';
    }

    public function getVideos(): Collection
    {
        return Auth::user()
            ->interactions()
            ->whereHasMorph('likeable', [Video::class], fn($query) => $query->active())
            ->where('status', true)
            ->where('likeable_type', Video::class)
            ->with(['likeable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Video::class => ['user', 'thumbnails'],
                ]);
            }])
            ->latest('perform_at')
            ->get()
            ->pluck('likeable');
    }

    public function getActions(): Collection
    {
        return collect();
    }
}
