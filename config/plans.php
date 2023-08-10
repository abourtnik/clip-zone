<?php

return [
    'free' => [
        'max_uploads' => 5,
        'max_file_size' => 209715200, // 200 MB
        'max_videos_storage' => 2147483648 // 2 GB
    ],
    'premium' => [
        'max_uploads' => null,
        'max_file_size' => 1073741824, // 1 GB
        'max_videos_storage' => null
    ]
];
