<?php

namespace App\Jobs;

use App\Events\VideoUploaded;
use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class UploadToS3 implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 120;

    public string $path;
    public Video $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $path, Video $video)
    {
        $this->path = $path;
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        $stream = Storage::disk('local')->readStream($this->path);

        Storage::disk('s3')->writeStream($this->path, $stream);

        Storage::disk('local')->delete($this->path);

        $this->video->update([
            'uploaded_at' => now()
        ]);

        VideoUploaded::dispatch($this->video);
    }
}
