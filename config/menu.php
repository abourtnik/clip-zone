<?php

return [
    'default' => [
        'top' => [
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
            ]
        ],
        'subscriptions' => true
    ],
    'user' => [
        'top' => [
            [
                'title' => 'Dashboard',
                'icon' => 'chart-line',
                'route' => 'user.index',
            ],
            [
                'title' => 'Videos',
                'icon' => 'video',
                'route' => 'user.videos.index',
            ],
            [
                'title' => 'Subscribers',
                'icon' => 'users',
                'route' => 'user.subscribers',
            ],
            [
                'title' => 'Comments',
                'icon' => 'comments',
                'route' => 'user.comments.index',
            ],

        ],
        'bottom' => [
            [
                'title' => 'My channel',
                'icon' => 'user-cog',
                'route' => 'user.profile',
            ]
        ]
    ],
    'admin' => [
        'top' => [
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
    ]
];
