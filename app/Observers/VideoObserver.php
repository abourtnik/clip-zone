<?php

namespace App\Observers;

use App\Enums\VideoStatus;
use App\Events\Video\VideoPublished;
use App\Helpers\File;
use App\Models\Video;

class VideoObserver
{
    /**
     * Handle the Video "updated" event.
     *
     * @param Video $video
     * @return void
     */
    public function updating(Video $video) : void
    {
        if ($video->status === VideoStatus::PUBLIC && is_null($video->getOriginal('publication_date'))) {
            VideoPublished::dispatch($video);
        }
    }

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

        // Remove video reports
        $video->reports()->delete();

        File::deleteIfExist($video->file, Video::VIDEO_FOLDER);
        File::deleteIfExist($video->thumbnail, Video::THUMBNAIL_FOLDER);
    }
}
