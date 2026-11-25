<?php

namespace App\Jobs;

use App\Helpers\WebhookUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class SimulateTranscriptionWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public string $transcriptId) {}

    public function handle(): void
    {
        Http::acceptJson()
            ->timeout(10)
            ->withHeaders([
                config('transcription.webhook_auth_header_name') => config('transcription.providers.fake.webhook_secret'),
            ])
            ->post(WebhookUrl::for('webhook.transcript'), [
                'transcript_id' => $this->transcriptId,
                'status'        => 'completed',
            ])
            ->throw();
    }
}
