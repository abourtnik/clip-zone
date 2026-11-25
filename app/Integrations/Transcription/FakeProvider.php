<?php

namespace App\Integrations\Transcription;

use App\Jobs\SimulateTranscriptionWebhookJob;
use App\Models\User;
use Illuminate\Support\Str;

class FakeProvider implements TranscriptionContract
{
    public function uploadFile(string $localPath): string
    {
        return 'fake://upload/'.basename($localPath);
    }

    public function createTranscript(string $audioUrl, array $options = []): string
    {
        $transcriptId = 'fake_'.Str::uuid();

        SimulateTranscriptionWebhookJob::dispatch($transcriptId)
            ->delay(now()->addSeconds(5));

        return $transcriptId;
    }

    public function getTranscript(string $transcriptId): array
    {
        return [
            'id'            => $transcriptId,
            'status'        => 'completed',
            'language_code' => 'en',
            'text'          => 'Fake transcript',
        ];
    }

    public function getSubtitles(string $transcriptId, string $format = 'vtt', ?int $charsPerCaption = null): string
    {
        return "WEBVTT\n\n00:00:00.000 --> 00:00:03.000\nFake subtitles\n";
    }

    public function shouldCreateDefaultSubtitle(User $user): bool
    {
        return true;
    }
}
