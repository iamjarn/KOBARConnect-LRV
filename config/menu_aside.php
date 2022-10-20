<?php
// Aside menu
return [

    'items' => [
        // // Dashboard

        // Custom
        [
            'section' => 'Tour',
        ],
        [
            'title' => 'Tour Place',
            'icon' => 'media/svg/icons/Shopping/Barcode-read.svg',
            'bullet' => 'dot',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'Tour Place',
                    'bullet' => 'dot',
                    'page' => 'tours'
                ],
                [
                    'title' => 'Category',
                    'bullet' => 'dot',
                    'page' => 'categories'
                ],
            ]
        ],

        // Layout
        [
            'section' => 'User Management',
        ],
        [
            'title' => 'User Management',
            'desc' => '',
            'icon' => 'media/svg/icons/Design/Bucket.svg',
            'bullet' => 'dot',
            'root' => true,
            'submenu' => [
                [
                    'title' => 'List User',
                    'page' => "users"
                ],
            ]
        ],

        // CRUD
        [
            'section' => 'General',
        ],
        [
            'title' => 'Content',
            'icon' => 'media/svg/icons/Design/PenAndRuller.svg',
            'root' => true,
            'page' => 'contents',
            'new-tab' => false
        ],
        [
            'title' => 'Transaction',
            'icon' => 'media/svg/icons/Design/PenAndRuller.svg',
            'root' => true,
            'page' => 'transactions',
            'new-tab' => false
        ],
        [
            'title' => 'Log Activity',
            'icon' => 'media/svg/icons/Design/PenAndRuller.svg',
            'root' => true,
            'page' => 'logs',
            'new-tab' => false
        ],
    ]

];
