<?php

return [
    'free' => [
        'max_uploads' => 20,
        'max_file_size' => 1_073_741_824, // 1 GB
        'max_videos_storage' => 10_737_418_240, // 10 GB
    ],
    'premium' => [
        'max_uploads' => null,
        'max_file_size' => 3_221_225_472, // 3 GB
        'max_videos_storage' => null
    ],
    'trial_period' => [
        'period' => 30, // days,
        'email_reminder' => 7 // days
    ]
];
