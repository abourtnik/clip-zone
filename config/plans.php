<?php

return [
    'free' => [
        'max_uploads' => 10,
        'max_file_size' => 524288000, // 500 MB
        'max_videos_storage' => 5368709120 // 5 GB
    ],
    'premium' => [
        'max_uploads' => null,
        'max_file_size' => 2147483648, // 2 GB
        'max_videos_storage' => null
    ],
    'trial_period' => [
        'period' => 30, // days,
        'email_reminder' => 7 // days
    ]
];
