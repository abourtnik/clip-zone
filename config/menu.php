<?php

return [
    'front' => [
        'top' => [
            [
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
                    'route' => 'subscription.index',
                ],
            ],
            [
                [
                    'title' => 'History',
                    'icon' => 'history',
                    'route' => 'history.index',
                ],
                [
                    'title' => 'Your videos',
                    'icon' => 'video',
                    'route' => 'user.videos.index',
                ],
                [
                    'title' => 'Liked videos',
                    'icon' => 'thumbs-up',
                    'route' => 'pages.liked',
                ]
            ],
        ],
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
            [
                'title' => 'Activity',
                'icon' => 'rectangle-list',
                'route' => 'user.activity.index',
            ]
        ],
        'bottom' => [
            [
                'title' => 'My channel',
                'icon' => 'user-cog',
                'route' => 'user.edit',
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
