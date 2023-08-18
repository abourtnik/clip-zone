<?php

return [
    'free' => [
        'max_uploads' => 10,
        'max_file_size' => 209715200, // 200 MB
        'max_videos_storage' => 1073741824 // 1 GB
    ],
    'premium' => [
        'max_uploads' => null,
        'max_file_size' => 1073741824, // 1 GB
        'max_videos_storage' => null
    ],
    'trial_period' => [
        'period' => 2, // days,
        'email_reminder' => 1 // days
    ]
];
