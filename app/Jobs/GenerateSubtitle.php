<?php

namespace App\Jobs;

use App\Events\Subtitles\SubtitleError;
use App\Helpers\VideoMetadata;
use App\Integrations\Transcription\TranscriptionContract;
use App\Models\Subtitle;
use App\Models\Video;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Throwable;

#[Tries(1)]
class GenerateSubtitle implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300; // 5 minutes

    public Video $video;
    public ?Subtitle $subtitle;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video, ?Subtitle $subtitle)
    {
        $this->onQueue('heavy');

        $this->video = $video;
        $this->subtitle = $subtitle;
    }

    /*
     * Execute the job.
     *
     * @return void
     */
    public function handle(TranscriptionContract $transcriptionService) : void
    {
        $audioFilePath = $this->extractAudio();

        if ($transcriptionService->shouldCreateDefaultSubtitle($this->video->user)) {
            $fileUrl = $transcriptionService->uploadFile(Storage::disk('local')->path($audioFilePath));
            $this->subtitle->update(['provider_id' => $transcriptionService->createTranscript($fileUrl)]);
        }

        if (config('filesystems.default') === 's3') {
            Storage::disk('local')->moveToDisk('s3', $audioFilePath, $audioFilePath);
        }
    }


    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {
        SubtitleError::dispatch($this->subtitle);
    }

    private function extractAudio () : string
    {
        $videoPath = Storage::disk('local')->path($this->video->path);

        $audioFile = VideoMetadata::extractAudio($videoPath);

        $this->video->update(['audio' => $audioFile]);

        return Video::AUDIO_FOLDER.DIRECTORY_SEPARATOR.$audioFile;
    }
}
