<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YoutubeService
{
    const API_ENDPOINT = 'https://www.googleapis.com/youtube/v3';

    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getComments(string $videoID) : array|null
    {
        $response = Http::get(self::API_ENDPOINT. '/commentThreads', [
            'key' => $this->apiKey,
            'part' => 'snippet,replies',
            'videoId' => $videoID,
            'order' => 'relevance',
            'maxResults' => 100
        ]);

        return $response->json();
    }

    public function getChannelInfo(string $channelId) : array|null
    {
        $response = Http::get(self::API_ENDPOINT. '/channels', [
            'key' => $this->apiKey,
            'part' => 'snippet,brandingSettings',
            'id' => $channelId,
        ]);

        return $response->json();
    }
}
