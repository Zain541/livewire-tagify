<?php

return [
    'frontend_library' => 'tailwind',

    'frontend_views' => [
        'tailwind' => 'livewire-tagify::livewire.tailwind',
        'bootstrap' => 'livewire-tagify::livewire.bootstrap',
        'none' => 'livewire-tagify::livewire.none',
    ],

    'colors' => [
        'lightblue' => '#add8e6',
        'lightgreen' => '#90ee90',
        'pink' => '#ffc0cb',
        'peachpuff' => '#ffdab9',
        'lightyellow' => '#9bfafa',
        'orange' => '#ffa500',
        'fuchsia' => '#ff00ff',
        'gold' => '#ffd700',
    ],
    'default_color' => 'lightgray',

    'max_tag_length' => 255,

    'permissions' => [
        'create' => true,
        'read' => true,
        'update' => true,
        'delete' => true,
        'change_color' => true,
    ],

    'permission_gates' => [
        'create' => null,
        'read' => null,
        'update' => null,
        'delete' => null,
        'change_color' => null,
    ],
];
