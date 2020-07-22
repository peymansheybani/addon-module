<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-04 9:53 AM
 **/

namespace greenweb\addon\component;


use greenweb\addon\Addon;
use greenweb\addon\menu\Menu;
use greenweb\addon\User\User;
use greenweb\addon\Admin\Admin;
use greenweb\addon\request\Request;
use greenweb\addon\routing\Routing;
use greenweb\addon\setting\Setting;
use greenweb\addon\session\Session;
use greenweb\addon\formatter\DateTime;
use greenweb\addon\routing\RoutingPath;
use greenweb\addon\permission\permission;

class Component
{
    public $app;

    /**
     * @var Routing
     */
    public $routing_component = Routing::class;
    /**
     * @var Request
     */
    public $request_component = Request::class;
    /**
     * @var permission
     */
    public $permission_component = permission::class;
    /**
     * @var Admin
     */
    public $admin_component = Admin::class;
    /**
     * @var User
     */
    public $user_component = User::class;
    /**
     * @var Session
     */
    public $session_component = Session::class;
    /**
     * @var DateTime
     */
    public $dateTime_component = DateTime::class;
    /**
     * @var Setting
     */
    public $setting_component = Setting::class;
    /**
     * @var RoutingPath
     */
    public $routingPath_component = RoutingPath::class;
    /**
     * @var Menu
     */
    public $menu_component = Menu::class;

    public function __construct(Addon $app)
    {
        $this->app = $app;
    }
}