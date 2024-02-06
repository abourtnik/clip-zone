<?php

namespace App\Models\Videos;

use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Database\Eloquent\Casts\Attribute;

class PlaylistVideo extends Video implements NextVideo
{
    private Video $video;
    private Playlist $playlist;

    public function __construct(Video $video, Playlist $playlist)
    {
       $this->video = $video;
       $this->playlist = $playlist;

       //parent::__construct($video->attributesToArray());
    }

    public function route(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->video->routeWithParams(['list' => $this->playlist->uuid]),
        );
    }
}
