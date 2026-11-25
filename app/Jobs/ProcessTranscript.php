<?php

namespace App\Jobs;

use App\Enums\SubtitleStatus;
use App\Events\Subtitles\SubtitleError;
use App\Events\Subtitles\SubtitleGenerated;
use App\Integrations\Transcription\TranscriptionContract;
use App\Models\Subtitle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\Attributes\Tries;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

#[Tries(1)]
class ProcessTranscript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $subtitleId,
    ) {
        $this->onQueue('heavy');
    }

    public function handle(TranscriptionContract $transcriptionService): void
    {
        $subtitle = Subtitle::findOrFail($this->subtitleId);

        $transcript = $transcriptionService->getTranscript($subtitle->provider_id);

        $isEmpty = blank($transcript['text'] ?? null);

        $vttContent = $isEmpty
            ? "WEBVTT\n\n"
            : $transcriptionService->getSubtitles($subtitle->provider_id);

        $subtitleFilename = Str::ulid().'.vtt';

        Storage::put(Subtitle::FILE_FOLDER.DIRECTORY_SEPARATOR.$subtitleFilename, $vttContent);

        $language = $transcript['language_code'] ?? null;

        $subtitle->update([
            'status' => SubtitleStatus::GENERATED,
            'language' => $language,
            'file' => $subtitleFilename,
            'is_enabled' => !$isEmpty
        ]);

        SubtitleGenerated::dispatch($subtitle);
    }

    /**
     * Handle a job failure.
     *
     * @param Throwable  $exception
     * @return void
     */
    public function failed(Throwable $exception) : void
    {
        $subtitle = Subtitle::findOrFail($this->subtitleId);

        $subtitle->update(['status' => SubtitleStatus::ERROR]);

        SubtitleError::dispatch($subtitle->fresh());
    }
}
