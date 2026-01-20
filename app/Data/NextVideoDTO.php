<?php

namespace App\Data;

use App\Models\Video;
use App\Models\Playlist;

class NextVideoDTO
{
    public function __construct(
        public Video $video,
        public string $title,
        public string $route,
        public string $type,
        public ?array $playlist = null,
    ) {}

    public static function fromSuggested(Video $video): self
    {
        return new self(
            video: $video,
            title: $video->title,
            route: $video->route,
            type: 'suggested',
        );
    }

    public static function fromPlaylist(Video $video, Playlist $playlist): self
    {
        return new self(
            video: $video,
            title: $video->title,
            route: $video->routeWithParams(['list' => $playlist->uuid]),
            type: 'playlist',
        );
    }
}
