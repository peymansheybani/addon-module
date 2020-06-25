<?php
return [
    'loader' => [
        'routing' => \greenweb\addon\routing\Routing::class,
        'request' => \greenweb\addon\request\Request::class,
        'permission' => \greenweb\addon\permission\permission::class,
    ],
    'base_route' => [
        'admin/permission'
    ]
];