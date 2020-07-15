<?php
return [
    'loader' => [
        // load default component
        'routing' => \greenweb\addon\routing\Routing::class,
        'request' => \greenweb\addon\request\Request::class,
        'permission' => \greenweb\addon\permission\permission::class,
        'admin' => \greenweb\addon\Admin\Admin::class,
        'user' => \greenweb\addon\User\User::class,
        'session' => \greenweb\addon\session\Session::class,
        'dateTime' => \greenweb\addon\formatter\DateTime::class,
        'setting' => \greenweb\addon\setting\Setting::class,
        'routingPath' => \greenweb\addon\routing\RoutingPath::class
    ],

    'permission' => [
        // permission list
        'showUser',
        'addUser',
    ],

    'AdminViewTemplatePath' => 'Resources/view/admin/',
    'ClientViewTemplatePath' => 'Resources/view/client/',
    'AdminControllerPath' => 'Http/Controller/',
    'ClientControllerPath' => 'Http/Controller/client/',
    'RoutePath' => 'Routes/',
    'RouteName' => 'routes.php',
    'ModelPath' => 'Models/',
    'MigrationPath' => 'migrations/',
    'LangPath' => 'lang/',
    'HeaderPath' => '/',

    'language' => 'fa',

    'settingConfig' => [
        'table' => 'setting',
        'code'  => 'cancel_request_hook'
    ]
];