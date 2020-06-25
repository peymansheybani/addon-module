<?php

namespace greenweb\addon;


class Addon
{
    const CLIENT = 'client';
    const HTTP = 'Http';
    const VIEW = 'View';
    const ADMIN_VIEW = 'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'admin';
    const CLIENT_VIEW = 'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'client';

    public static $instance;
    public $config;

    public $routing;
    public $request;
    public $routes;

    public function __construct($config)
    {
        static::$instance = $this;
        $this->setConfig($config);
        $this->init();
    }

    private function setConfig($config){
        $baseConfig = require 'config.php';
        $this->config = array_merge($baseConfig, $config);
    }

    public static function ModuleDir()
    {
        return dirname(dirname(dirname(__DIR__)));
    }

    public static function getTemplateUri($template)
    {
        return str_replace('.','/', $template);
    }

    public function boot()
    {

    }

    private function init()
    {
        collect($this->config['loader'])->each(function ($config, $key){
                $this->{$key} = new $config;
        });
    }

    public function getConfig()
    {
        return $this->config;
    }
}