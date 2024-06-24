<?php

$today = date("Y-m-d");

return [
    'version' => '2.0',
    'permissions' => [
        'User' => [
            'View user',
            'Create user',
            'Edit user',
            'Activate/deactivate user'
        ],
        'Project' => [
            'View project',
            'Create project',
            'Edit project',
            'Edit sales',
            'Activate/deactivate project'
        ],
        'Owner' => [
            'View owner',
            'Create owner',
            'Edit owner',
            'Activate/deactivate owner'
        ]
    ],
];
