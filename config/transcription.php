<?php

return [
    'default' => env('TRANSCRIPTION_DRIVER', 'fake'),
    'webhook_auth_header_name' => 'X-Transcription-Webhook-Secret',
    'providers' => [
        'fake' => [
            'webhook_secret' => 'secret'
        ],
        'assemblyai' => [
            'api_key' => env('ASSEMBLYAI_API_KEY', ''),
            'webhook_secret' => env('ASSEMBLYAI_WEBHOOK_SECRET', ''),
        ],
    ]
];
