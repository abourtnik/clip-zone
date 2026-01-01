<?php


namespace App\Enums;

use App\Enums\Traits\Listable;

enum PlaylistSort: int
{
    use Listable;

    case DATE_ADDED_NEWEST = 0;
    case DATE_ADDED_OLDEST = 1;
    case POPULAR = 2;
    case DATE_PUBLISHED_NEWEST = 3;
    case DATE_PUBLISHED_OLDEST = 4;
    case MANUAL = 5;

    public function getSortedColumn(): string
    {
        return match ($this) {
            self::DATE_ADDED_NEWEST, self::DATE_ADDED_OLDEST => 'playlist_has_videos.added_at',
            self::POPULAR => 'views',
            self::DATE_PUBLISHED_NEWEST, self::DATE_PUBLISHED_OLDEST => 'published_at',
            self::MANUAL => 'playlist_has_videos.position',
        };
    }

    public function getSortedDirection(): string
    {
        return match ($this) {
            self::DATE_ADDED_OLDEST, self::MANUAL, self::DATE_PUBLISHED_OLDEST => 'ASC',
            self::DATE_ADDED_NEWEST, self::DATE_PUBLISHED_NEWEST, self::POPULAR => 'DESC',
        };
    }
}
