<?php


use greenweb\addon\helpers\FileHelper;
use Respect\Validation\Validator;

class Addon
{
    const CONTROLLER = "controller";
    const CLIENT = "client";

    private $defaultRoute;
    private $controller;
    private $method;
    private $basePathController;
    private $vars;
    private $language;

    /**
     * @var $this
     */
    public $instance;

    public function __construct()
    {

    }

    public function routeAdminArea($action, $vars)
    {
        return $this->routeArea($action, $vars);
    }

    public function routeClientArea($action, $vars)
    {
        return $this->routeArea($action, $vars, true);
    }

    private function routeArea($action, $vars, $isClient = false)
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

    private function initialRoutes($action, $vars)
    {
        $this->vars = $vars;
        $action = $action ?: $this->defaultRoute;
        $action = explode('/', trim($action));
        $this->controller = ucfirst($action[0]) . 'Controller';
        $this->method = $action[1];

        return $this;
    }

    private function getBaseDirController($isClient = false){
        $this->basePathController = $isClient ?
            static::ModuleDir() . DIRECTORY_SEPARATOR. self::CONTROLLER. DIRECTORY_SEPARATOR. self::CLIENT :
            static::ModuleDir() . DIRECTORY_SEPARATOR. self::CONTROLLER;

        return $this;
    }

    private function initialDataClient($isClient = false){
        $this->vars['session'] = $this->getSession();
        $this->vars['lang'] = $this->getSession();
    }

    private function getSession() {
        $session = $_SESSION;

        if (isset($_SESSION['message'])) {
            unset($_SESSION['message']);
        }

        return $session;
    }

    private function getLanguage() {
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . "lang" . DIRECTORY_SEPARATOR . $this->language . ".php";

        return require_once $file;
    }

    public static function ModuleDir()
    {
        return dirname(dirname(dirname(__DIR__)));
    }

    public static function check1()
    {
        return "peyman sheybani";
    }
}