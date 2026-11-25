<?php

namespace App\Integrations\Transcription;

use App\Helpers\WebhookUrl;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AssemblyAIProvider implements TranscriptionContract
{
    const string API_ENDPOINT = 'https://api.assemblyai.com';
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function uploadFile(string $localPath) : string
    {
        $response = Http::baseUrl(self::API_ENDPOINT)
            ->withHeaders(['Authorization' => $this->apiKey])
            ->withBody(fopen($localPath, 'r'), 'application/octet-stream')
            ->timeout(300)
            ->post('v2/upload')
            ->throw();

        return $response->json('upload_url');
    }

    public function createTranscript(string $audioUrl, array $options = []) : string
    {
        $response = Http::baseUrl(self::API_ENDPOINT)
            ->withHeaders(['Authorization' => $this->apiKey])
            ->timeout(300)
            ->post('v2/transcript', array_merge($options, [
                'audio_url' => $audioUrl,
                'language_detection' => true,
                'webhook_url' => WebhookUrl::for('webhook.transcript'),
                'webhook_auth_header_name' => config('transcription.webhook_auth_header_name'),
                'webhook_auth_header_value' => config('transcription.providers.assemblyai.webhook_secret')
            ]))
            ->throw();

        return $response->json('id');
    }

    public function getTranscript(string $transcriptId): array
    {
        return Http::baseUrl(self::API_ENDPOINT)
            ->withHeaders(['Authorization' => $this->apiKey])
            ->timeout(60)
            ->withUrlParameters(['transcript_id' => $transcriptId])
            ->get('v2/transcript/{transcript_id}')
            ->throw()
            ->json();
    }

    public function getSubtitles(string $transcriptId, string $format = 'vtt') : string
    {
        $response = Http::baseUrl(self::API_ENDPOINT)
            ->withHeaders(['Authorization' => $this->apiKey])
            ->timeout(300)
            ->withUrlParameters(['transcript_id' => $transcriptId, 'format' => $format])
            ->get('v2/transcript/{transcript_id}/{format}')
            ->throw();

        return $response->body();
    }

    public function shouldCreateDefaultSubtitle(User $user): bool
    {
        return !$user->is_admin;
    }
}
