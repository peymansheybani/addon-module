<?php

namespace greenweb\addon;


use greenweb\addon\migrations\Migration;
use greenweb\addon\User\User;
use greenweb\addon\Admin\Admin;
use greenweb\addon\request\Request;
use greenweb\addon\routing\Routing;
use greenweb\addon\session\Session;
use greenweb\addon\setting\Setting;
use greenweb\addon\formatter\DateTime;
use greenweb\addon\permission\permission;

class Addon
{
    /**
     * @var $this
     */
    public static $instance;
    public $config;

    /**
     * @var Routing
     */
    public $routing;
    public $routes;
    /**
     * @var Request
     */
    public $request;
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
    /**
     * @var Migration
     */
    public $migration;
    /**
     * @var mixed
     */
    public $database;

    public function __construct($config)
    {
        static::$instance = $this;
        $this->setConfig($config);
        $this->setDatabase();
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

        $this->migration = new Migration($this);
    }

    private function setConfig($config){
        $baseConfig = require 'config.php';
        $this->config = array_merge_recursive($baseConfig, $config);
    }

    private function setDatabase() {
        $this->database = require 'database.php';
    }
}