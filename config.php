<?php
return [
    'loader' => [
        // load default component
        'routing' => \greenweb\addon\routing\Routing::class,
        'request' => \greenweb\addon\request\Request::class,
        'permission' => \greenweb\addon\permission\permission::class,
//        'admin' => \greenweb\addon\Admin\Admin::class,
//        'user' => \greenweb\addon\User\User::class,
//        'session' => \greenweb\addon\session\Session::class,
//        'dateTime' => \greenweb\addon\formatter\DateTime::class,
        'setting' => \greenweb\addon\setting\Setting::class
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
    'ModelPath' => 'Models/',
    'MigrationPath' => 'migrations/',
    'LangPath' => 'lang/',

    'language' => 'fa',

    'settingConfig' => [
        'table' => 'setting',
        'code'  => 'cancel_request_hook'
    ]
];