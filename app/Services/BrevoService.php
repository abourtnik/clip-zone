<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BrevoService
{
    const string API_ENDPOINT = 'https://api.brevo.com/v3';

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function sendTransactionalSMS(string $number, string $content) : array|null
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

        return $response->json();
    }
}
