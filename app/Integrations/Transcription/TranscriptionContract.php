<?php

namespace App\Integrations\Transcription;

use App\Models\User;

interface TranscriptionContract
{
    const string STATUS_ERROR = 'error';

    public function uploadFile(string $localPath): string;
    public function createTranscript(string $audioUrl, array $options = []): string;
    public function getTranscript(string $transcriptId): array;
    public function getSubtitles(string $transcriptId, string $format = 'vtt'): string;

    public function shouldCreateDefaultSubtitle(User $user): bool;
}
