<?php
return [
    'loader' => [
        'routing' => \greenweb\addon\routing\Routing::class,
        'request' => \greenweb\addon\request\Request::class,
        'permission' => \greenweb\addon\permission\permission::class,
        'admin' => \greenweb\addon\Admin\Admin::class,
        'user' => \greenweb\addon\User\User::class
    ],
    'permission' => [
        'adminPermission',
        'addPermission',
    ]
];