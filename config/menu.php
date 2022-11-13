<?php

return [
    'default' => [
        [
            'title' => 'Home',
            'icon' => 'house',
            'route' => 'pages.home',
        ],
        [
            'title' => 'Trend',
            'icon' => 'fire',
            'route' => 'pages.trend',
        ],
        [
            'title' => 'Subscriptions',
            'icon' => 'user-check',
            'route' => 'pages.subscriptions',
        ],
        [
            'title' => 'Library',
            'icon' => 'bookmark',
            'route' => 'pages.library',
        ],
        [
            'title' => 'History',
            'icon' => 'clock-rotate-left',
            'route' => 'pages.history',
        ],
        [
            'title' => 'Playlist',
            'icon' => 'clock',
            'route' => 'pages.playlist',
        ]
    ],
    'user' => [
        [
            'title' => 'Dashboard',
            'icon' => 'chart-line',
            'route' => 'user.index',
        ],
        [
            'title' => 'Videos',
            'icon' => 'video',
            'route' => 'user.videos.index',
        ]
    ],
    'admin' => [
        [
            'title' => 'Dashboard',
            'icon' => 'chart-line',
            'route' => 'admin.index',
        ],
        [
            'title' => 'Users',
            'icon' => 'users',
            'route' => 'admin.users.index',
        ],
        [
            'title' => 'Videos',
            'icon' => 'video',
            'route' => 'admin.videos.index',
        ]
    ]
];
