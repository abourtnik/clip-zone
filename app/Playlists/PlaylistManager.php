<?php

namespace App\Playlists;

use App\Enums\CustomPlaylistType;
use App\Playlists\Types\WatchLaterPlaylist;
use App\Playlists\Types\LikedVideosPlaylist;

class PlaylistManager
{
    /**
     * @var array<string, class-string<CustomPlaylist>>
     */
    public static array $types = [
        CustomPlaylistType::WATCH_LATER->value => WatchLaterPlaylist::class,
        CustomPlaylistType::LIKED_VIDEOS->value => LikedVideosPlaylist::class,
    ];


    /**
     * Get a custom playlist instance
     */
    public static function get(CustomPlaylistType $type): CustomPlaylist
    {
        if (!isset(static::$types[$type->value])) {
            throw new \InvalidArgumentException("Unknown playlist type: {$type->value}");
        }

        $class = static::$types[$type->value];
        return new $class($type->value);
    }
}
