<?php

namespace App\Models\Videos;

use App\Models\Video;
use Illuminate\Database\Eloquent\Casts\Attribute;

class SuggestedVideo extends Video implements NextVideo
{
    private Video $video;


    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function route(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->video->route,
        );
    }
}
