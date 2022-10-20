<?php
// Header menu
return [

    'items' => [
        [
            'title' => 'Tour',
            'root' => true,
            'toggle' => 'click',
            'submenu' => [
                'type' => 'classic',
                'alignment' => 'left',
                'items' => [
                    [
                        'title' => 'Tour Place',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'tours'
                    ],
                    [
                        'title' => 'Category',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'categories'
                    ],
                    [
                        'title' => 'Event',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'events'
                    ],
                ]
            ]
        ],
        [
            'title' => 'User Management',
            'root' => true,
            'toggle' => 'click',
            'submenu' => [
                'type' => 'classic',
                'alignment' => 'left',
                'items' => [
                    [
                        'title' => 'List User',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'users'
                    ],
                ]
            ]
        ],
        [
            'title' => 'General',
            'root' => true,
            'toggle' => 'click',
            'submenu' => [
                'type' => 'classic',
                'alignment' => 'left',
                'items' => [
                    [
                        'title' => 'Content',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'contents'
                    ],
                    [
                        'title' => 'Calendar',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'calendars'
                    ],
                    [
                        'title' => 'Log Activity',
                        'icon' => 'media/svg/icons/Shopping/Box2.svg',
                        'page' => 'logs'
                    ],
                ]
            ]
        ],
    ]

];
