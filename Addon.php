<?php

namespace greenweb\addon;

use greenweb\addon\helpers\FileHelper;
use Respect\Validation\Validator;

class Addon
{
    const CONTROLLER = 'controller';
    const CLIENT = 'client';
    const HTTP = 'Http';
    const RESOURCES = 'Resources';
    const VIEW = 'View';
    const ADMIN_VIEW = 'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'admin';
    const CLIENT_VIEW = 'Resources'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'client';
    private $defaultRoute;
    private $controller;
    private $method;
    private $basePathController;
    private $vars;
    private $language;

    public $instance;

    public static function ModuleDir()
    {
        return dirname(dirname(dirname(__DIR__)));
    }

    public static function getTemplateUri($template)
    {
        return str_replace('.','/', $template);
    }
}