<?php

return [
    'free' => [
        'max_uploads' => 10,
        'max_file_size' => 2209715200, // 200 MB
        'max_videos_storage' => 5073741824 // 1 GB
    ],
    'premium' => [
        'max_uploads' => null,
        'max_file_size' => 2073741824, // 1 GB
        'max_videos_storage' => null
    ],
    'trial_period' => [
        'period' => 30, // days,
        'email_reminder' => 7 // days
    ]
];
