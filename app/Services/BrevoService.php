<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BrevoService
{
    const string API_ENDPOINT = 'https://api.brevo.com/v3';

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendTransactionalSMS(string $number, string $content) : int
    {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'api-key' => $this->apiKey
        ])->post(self::API_ENDPOINT. '/transactionalSMS/send', [
            'sender' => config('app.name'),
            'recipient' => $number,
            'content' => $content,
            'type' => 'transactional',
        ]);

        if ($response->failed()) {
            Log::error('Failed to send SMS: ' . $response->body());
            //$response->throw();
        }

        return $response->status();
    }
}
