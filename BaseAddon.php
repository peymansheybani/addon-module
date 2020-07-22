<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-22 10:34 AM
 **/

namespace greenweb\addon;


class BaseAddon
{
    /**
     * @var array
     */
    public $permissionArray;
    /**
     * @var array
     */
    public $settingConfig = [
        'table' => 'setting',
        'code'  => 'cancel_request_hook'
    ];

    public $AppName;
    public $AdminViewTemplatePath = 'Resources/view/admin/';
    public $ClientViewTemplatePath = 'Resources/view/client/';
    public $AdminControllerPath = 'Http/Controller/';
    public $ClientControllerPath = 'Http/Controller/client/';
    public $ControllerNameSpace;
    public $RoutePath = 'Routes/';
    public $RouteName = 'routes.php';
    public $ModelPath = 'Models/';
    public $MigrationPath = 'migrations/';
    public $MigrationNameSpace;
    public $LangPath = 'lang/';
    public $HeaderPath = '/';
    public $language = 'fa';
    public $BaseDir;
    public $modules;
}