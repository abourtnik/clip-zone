<?php

namespace App\Observers;

use App\Helpers\Image;
use App\Models\Video;

class VideoObserver
{
    /**
     * Handle the Video "deleting" event.
     *
     * @param Video $video
     * @return void
     */
    public function deleting(Video $video) : void
    {
        // Remove video interactions
        $video->interactions()->delete();

        // Remove video comments interaction
        $video->comment_interactions()->delete();

        Image::deleteIf($video->file, 'videos');
        Image::deleteIf($video->thumbnail, 'thumbnails');
    }
}