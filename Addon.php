<?php

namespace greenweb\addon;


use greenweb\addon\Admin\Admin;
use greenweb\addon\formatter\DateTime;
use greenweb\addon\permission\permission;
use greenweb\addon\request\Request;
use greenweb\addon\routing\Routing;
use greenweb\addon\session\Session;
use greenweb\addon\setting\Setting;
use greenweb\addon\User\User;

class Addon
{
    const CLIENT = 'client';
    const HTTP = 'Http';
    const VIEW = 'View';
    const ADMIN_VIEW = 'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'admin';
    const CLIENT_VIEW = 'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'client';

    /**
     * @var $this
     */
    public static $instance;
    public $config;

    /**
     * @var Routing
     */
    public $routing;
    /**
     * @var Request
     */
    public $request;
    public $routes;
    /**
     * @var permission
     */
    public $permission;

    /**
     * @var User
     */
    public static $user;
    /**
     * @var Admin
     */
    public static $admin;

    /**
     * @var Session
     */
    public $session;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * @var Setting
     */
    public $setting;

    public function __construct($config)
    {
        static::$instance = $this;
        $this->setConfig($config);
        $this->init();
    }

    public static function ModuleDir()
    {
        return dirname(dirname(dirname(__DIR__)));
    }

    public static function getTemplateUri($template)
    {
        return str_replace('.','/', $template);
    }

    private function init()
    {
        collect($this->config['loader'])->each(function ($config, $key){
                $this->{$key} = new $config($this);
        });
    }

    private function setConfig($config){
        $baseConfig = require 'config.php';
        $this->config = array_merge_recursive($baseConfig, $config);
    }
}