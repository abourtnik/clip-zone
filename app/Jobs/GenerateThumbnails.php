<?php

namespace App\Jobs;

use App\Enums\ThumbnailStatus;
use App\Events\Thumbnail\ThumbnailGenerated;
use App\Events\Thumbnail\ThumbnailGeneratedError;
use App\Helpers\VideoMetadata;
use App\Models\Thumbnail;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateThumbnails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Video $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $duration = $this->video->getRawOriginal('duration');

        $path = Storage::disk('local')->path(Video::VIDEO_FOLDER . DIRECTORY_SEPARATOR . $this->video->file);

        $timecodes = [
            1,
            intval(round($duration / 2)),
            intval($duration - 1)
        ];

        $thumbnails = $this->video
            ->thumbnails()
            ->orderBy('id')
            ->get();

        foreach ($timecodes as $index => $time) {

            $thumbnail = $thumbnails->get($index);

            $fileName = VideoMetadata::extractImage($path, $time);

            // Check if thumbnail is successfully generated
            if ($fileName !== false) {
                $thumbnail->update([
                    'file' => $fileName,
                    'is_active' => $index === 0,
                    'status' =>  ThumbnailStatus::GENERATED
                ]);

                if (config('filesystems.default') === 's3') {
                    $this->uploadToS3($thumbnail);
                }

            } else {
                $thumbnail->update([
                    'status' =>  ThumbnailStatus::ERROR
                ]);
            }

            ThumbnailGenerated::dispatch($thumbnail);
        }

        // Remove video from local storage
        Storage::disk('local')->delete(Video::VIDEO_FOLDER . DIRECTORY_SEPARATOR . $this->video->file);

    }

    /**
     * Upload file from local to S3.
     *
     * @param Thumbnail $thumbnail
     *
     * @return void
     */
    private function uploadToS3 (Thumbnail $thumbnail) : void {

        $path = Video::THUMBNAIL_FOLDER . DIRECTORY_SEPARATOR . $thumbnail->file;

        $stream = Storage::disk('local')->readStream($path);

        Storage::disk('s3')->writeStream($path, $stream);

        Storage::disk('local')->delete($path);
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {
        ThumbnailGeneratedError::dispatch($this->video);
    }
}
