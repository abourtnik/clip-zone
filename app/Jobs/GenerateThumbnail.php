<?php

namespace App\Jobs;

use App\Enums\ThumbnailStatus;
use App\Events\Thumbnail\ThumbnailGenerated;
use App\Events\Thumbnail\ThumbnailGeneratedError;
use App\Helpers\VideoMetadata;
use App\Models\Thumbnail;
use App\Models\Video;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateThumbnail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    public int $timeout = 180; // 3 minutes

    public Thumbnail $thumbnail;

    public int $time;

    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Thumbnail $thumbnail, int $time)
    {
        $this->thumbnail = $thumbnail;
        $this->time = $time;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle() : void
    {
        $videoPath = Storage::disk('local')->path(Video::VIDEO_FOLDER . DIRECTORY_SEPARATOR . $this->thumbnail->video->file);

        $fileName = VideoMetadata::extractImage($videoPath, $this->time);

        // Check if thumbnail is successfully generated
        if ($fileName !== false) {
            $this->thumbnail->update([
                'file' => $fileName,
                'is_active' => $this->time === 1,
                'status' =>  ThumbnailStatus::GENERATED
            ]);

            if (config('filesystems.default') === 's3') {
                $this->uploadToS3($this->thumbnail);
            }

            ThumbnailGenerated::dispatch($this->thumbnail);

            return;
        }

        throw new \Exception('Thumbnail could not be generated');
    }


    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {
        $this->thumbnail->update(['status' => ThumbnailStatus::ERROR]);
        ThumbnailGeneratedError::dispatch($this->thumbnail);
    }

    private function uploadToS3 (Thumbnail $thumbnail) : void {

        $path = Video::THUMBNAIL_FOLDER . DIRECTORY_SEPARATOR . $thumbnail->file;

        $stream = Storage::disk('local')->readStream($path);

        Storage::disk('s3')->writeStream($path, $stream);

        Storage::disk('local')->delete($path);
    }
}
