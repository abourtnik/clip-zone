<?php

namespace App\Observers;

use App\Enums\VideoStatus;
use App\Events\Video\VideoPublished;
use App\Helpers\File;
use App\Models\Subtitle;
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
        if ($video->status === VideoStatus::PUBLIC && is_null($video->getOriginal('published_at'))) {
            VideoPublished::dispatch($video);
        }
    }

    public function updated(Video $video) : void
    {
        if ($video->wasChanged('status')) {
            $this->reindex($video);
        }
    }

    /**
     * Handle the Video "deleting" event.
     *
     * @param Video $video
     * @return void
     */
    public function deleted(Video $video) : void
    {
        if ($video->user->is_admin) {

            File::deleteIfExist($video->file, Video::VIDEO_FOLDER);

            // Remove videos thumbnails
            foreach ($video->thumbnails as $thumbnail) {
                $thumbnail->delete();
                File::deleteIfExist($thumbnail->file, Video::THUMBNAIL_FOLDER);
            }

            // Remove audio file
            File::deleteIfExist($video->audio, Video::AUDIO_FOLDER);

            // Remove subtitles
            foreach ($video->subtitles as $subtitle) {
                $subtitle->delete();
                File::deleteIfExist($subtitle->file, Subtitle::FILE_FOLDER);
            }

            $video->forceDelete();
        }

        $this->reindex($video);
    }

    private function reindex(Video $video): void
    {
        $video->user->reindex();
    }
}
