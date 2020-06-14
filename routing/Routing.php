<?php


namespace greenweb\addon\routing;


use greenweb\addon\Addon;
use greenweb\addon\helpers\FileHelper;
use Respect\Validation\Validator;

class Routing
{
    const CONTROLLER = "controller";
    const CLIENT = "client";
    const HTTP = "Http";

    public $defaultRoute;
    public $controller;
    public $method;
    public $basePathController;
    public $vars;
    public $language;

    public $instance;

    public static function __callStatic($method, $params)
    {
        $map = [
            'client' => new AdminRouting(),
            'admin' => new ClientRouting()
        ];
        $route = $map[$method] ?? $map['admin'];

        return $route->route($params[0], $params[1]);
    }

    protected function routeArea($action, $vars, $isClient = false)
    {
        $this->initialRoutes($action, $vars)
            ->getBaseDirController($isClient);

        if (Validator::alnum()->validate($this->method) && Validator::alnum()->validate($this->controller)) {
            if ($selectedDir = FileHelper::findFileInPaths($this->controller . '.php', [$this->basePathController])) {
                require_once $selectedDir . DIRECTORY_SEPARATOR . $this->controller . '.php';
                $class = new $this->controller($this->vars);

                return $class->{$this->method}();
            }
        }

        return false;
    }

    protected function initialRoutes($action, $vars)
    {
        $this->vars = $vars;
        $action = $action ?: $this->defaultRoute;
        $action = explode('/', trim($action));
        $this->controller = ucfirst($action[0]) . 'Controller';
        $this->method = $action[1];

        return $this;
    }

    protected function getBaseDirController($isClient = false){
        $this->basePathController = $isClient ?
            Addon::ModuleDir() . self::clientController() :
            Addon::ModuleDir() . self::baseController();

        return $this;
    }

    protected function initialDataClient($isClient = false){
        $this->vars['session'] = $this->getSession();
        $this->vars['lang'] = $this->getSession();
    }

    protected function getSession() {
        $session = $_SESSION;

        if (isset($_SESSION['message'])) {
            unset($_SESSION['message']);
        }

        return $session;
    }

    protected function getLanguage() {
        $file = Addon::ModuleDir(). DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . $this->language . ".php";

        return require_once $file;
    }

    public static function clientController()
    {
        return self::baseController(). DIRECTORY_SEPARATOR. self::CLIENT;
    }

    public static function baseController()
    {
        return DIRECTORY_SEPARATOR. self::HTTP . DIRECTORY_SEPARATOR. self::CONTROLLER;
    }
}