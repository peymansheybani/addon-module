<?php


class Addon
{
    private $defaultRoute;

    public function __construct()
    {

    }

    public function routeAdminArea($action, $vars)
    {
        $action= $action ?: $this->defaultRoute;
        $action=explode('/',trim($action));
        $controller=$action[0].'Controller';
        $method=$action[1];
        $_baseDirController = dirname(__DIR__) .  DIRECTORY_SEPARATOR . "controller";

        return [
            'controller' => $controller,
            'method' => $method,
            'baseDirectoryController' => $_baseDirController
        ];
    }

    public function routeClientArea($action, $vars)
    {

    }
}