<?php

namespace App\Jobs;

use App\Enums\VideoStatus;
use App\Events\Video\VideoError;
use App\Events\Video\VideoUploaded;
use App\Helpers\VideoMetadata;
use App\Models\Video;
use App\Services\FileMerger;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

class BuildFullFileFromChunks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1800; // 30 minutes

    public string $folder;
    public Video $video;
    public string $extension;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $folder, Video $video, string $extension)
    {
        $this->folder = $folder;
        $this->video = $video;
        $this->extension = $extension;
    }

    /*
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $files = Storage::disk('local')->files(Video::CHUNK_FOLDER.'/'.$this->folder);

        // Sort the chunk order
        natcasesort($files);

        $fileName = uniqid() . '.' .$this->extension;
        $path = Video::VIDEO_FOLDER . '/' . $fileName;

        $fileMerger = new FileMerger(Storage::disk('local')->path($path));

        // Append each chunk file
        foreach ($files as $file) {
            $fileMerger->appendFile(Storage::disk('local')->path($file));
            // Delete the chunk file
            Storage::disk('local')->delete($file);
        }

        $fileMerger->close();

        Storage::disk('local')->deleteDirectory(Video::CHUNK_FOLDER.'/'.$this->folder);

        $this->video->update([
            'original_mimetype' => Storage::disk('local')->mimeType($path)
        ]);

        // Convert video to mp4 and remove original
        $path = VideoMetadata::convert($path);

        $this->updateVideo($path);

        GenerateThumbnails::dispatch($this->video);

        if (config('filesystems.default') === 's3') {
            $this->uploadToS3($path);
        } else {
            $this->video->update([
                'uploaded_at' => now()
            ]);
        }

        VideoUploaded::dispatch($this->video);
    }

    /**
     * Create Video from uploaded file.
     *
     * @param string $path
     *
     */
    private function updateVideo (string $path): void {

        $filePath = Storage::disk('local')->path($path);

        $duration = VideoMetadata::getDuration($filePath);

        $this->video->update([
            'file' => pathinfo($path, PATHINFO_BASENAME),
            'duration' => round($duration),
            'size' => Storage::disk('local')->size($path),
            'is_short' => $this->isShort($duration, $filePath)
        ]);
    }

    /**
     * Upload file from local to S3.
     *
     * @param string $path
     *
     * @return void
     */
    private function uploadToS3 (string $path) : void {

        $stream = Storage::disk('local')->readStream($path);

        Storage::disk('s3')->writeStream($path, $stream);

        $this->video->update([
            'uploaded_at' => now()
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {
        $this->video->update(['status' => VideoStatus::FAILED]);
        VideoError::dispatch($this->video);
    }

    private function isShort(float $duration, string $path): bool
    {
        if ($duration > 60) {
            return false;
        }

        $videoDimensions = VideoMetadata::getDimensions($path);

        return $videoDimensions->getWidth() < $videoDimensions->getHeight();
    }
}
