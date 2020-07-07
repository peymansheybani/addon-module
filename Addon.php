<?php

namespace greenweb\addon;


use greenweb\addon\User\User;
use greenweb\addon\Admin\Admin;
use greenweb\addon\request\Request;
use greenweb\addon\routing\Routing;
use greenweb\addon\session\Session;
use greenweb\addon\setting\Setting;
use greenweb\addon\formatter\DateTime;
use greenweb\addon\component\Component;
use greenweb\addon\migrations\Migration;
use greenweb\addon\permission\permission;

/**
 * Class Addon
 * @package greenweb\addon
 *
 * @property User $user
 * @property Admin $admin
 * @property Request $request
 * @property Routing $routing
 * @property Session $session
 * @property Setting $setting
 * @property DateTime $dateTime
 * @property permission $permission
 *
 */

class Addon
{
    /**
     * @var $this
     */
    public static $instance;
    public $config;

    public $routes;
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
        $this->setMigration();
        $this->init();
    }

    public function __get($name)
    {
        if (isset($this->config['loader'][$name])) {
            return $this->addComponent($name, new $this->config['loader'][$name]($this));
        }
    }

    public static function ModuleDir()
    {
        return dirname(dirname(dirname(__DIR__)));
    }

    public static function getTemplateUri($template)
    {
        return str_replace('.','/', $template);
    }

    public function addComponent($name, Component $component)
    {
        return $this->$name = new $component($this);
    }

    public function hasComponent($component)
    {
        return isset($this->config['loader'][$component]);
    }

    private function setConfig($config)
    {
        $baseConfig = require 'config.php';
        $this->config = array_merge($baseConfig, $config);
    }

    private function setDatabase()
    {
        $this->database = require 'database.php';
    }

    private function setMigration()
    {
        $this->migration = new Migration($this);
    }

    private function init()
    {
        collect($this->config['loader'])->each(function ($component, $name) {
            $object = new $component($this);
            if(method_exists($object, 'boot')){
                $this->addComponent($name, $object);
            }
        });
    }
}