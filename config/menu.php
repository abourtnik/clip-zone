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
                    'new_tab' => true,
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
            'title' => 'Playlists',
            'icon' => 'rectangle-list',
            'route' => 'user.playlists.index',
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
            'icon' => 'bars-staggered',
            'route' => 'user.activity.index',
        ],
        [
            'title' => 'Reports',
            'icon' => 'flag',
            'route' => 'user.reports.index',
        ],
        [
            'title' => 'Invoices',
            'icon' => 'receipt',
            'route' => 'user.invoices.index',
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
        ],
        [
            'title' => 'Comments',
            'icon' => 'comment',
            'route' => 'admin.comments.index',
        ],
        [
            'title' => 'Reports',
            'icon' => 'flag',
            'route' => 'admin.reports.index',
        ],
        [
            'title' => 'Categories',
            'icon' => 'folder',
            'route' => 'admin.categories.index',
        ],
        [
            'title' => 'Exports',
            'icon' => 'file-export',
            'route' => 'admin.exports.index',
        ],
        [
            'title' => 'Plans',
            'icon' => 'toolbox',
            'route' => 'admin.plans.index',
        ],
        [
            'title' => 'Invoices',
            'icon' => 'receipt',
            'route' => 'admin.invoices.index',
        ],
        [
            'title' => 'Spam',
            'icon' => 'envelope',
            'route' => 'admin.spams.index',
        ],
        [
            'title' => 'Logs',
            'icon' => 'file-lines',
            'route' => 'admin.logs',
            'new_tab' => true,
        ],
        [
            'title' => 'Telescope',
            'icon' => 'cube',
            'route' => 'admin.telescope',
            'new_tab' => true,
        ]
    ]
];
