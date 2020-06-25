<?php


namespace greenweb\addon\routing;


use greenweb\addon\Addon;
use greenweb\addon\controller\AdminController;
use greenweb\addon\controller\ClientController;
use greenweb\addon\exceptions\ControllerNotFoundException;
use greenweb\addon\exceptions\MethodNotFoundException;
use greenweb\addon\exceptions\RouteNotFoundException;

class Routing
{
    const CONTROLLER = "controller";
    const CLIENT = "client";
    const HTTP = "Http";

    public $controller;
    public $method;
    public $basePathController;
    public $vars;
    public $language;

    private $customRoute = false;

    public function __construct()
    {

    }

    public static function __callStatic($method, $params)
    {
        $my = new static();
        $method = $my->getMethod(debug_backtrace()[1]['function']);

        if($my->checkRoute($params[0], $method)){
            $my->customRoute = false;
        } elseif (in_array($params[0], $my->getBaseRoute())) {
            $values = $my->checkBaseRoute($params[0], $method);
            $my->controller = $values[0];
            $my->method = $values[1];
            $my->customRoute = true;
        }

        $map = [
            'admin' => new AdminRouting(),
            'client' => new ClientRouting()
        ];
        $route = $map[$method] ?? $map['admin'];

        return $route->route($my->controller, $my->method, $params[1], $my->customRoute);
    }

    public static function clientController()
    {
        return self::baseController(). DIRECTORY_SEPARATOR. self::CLIENT;
    }

    public static function baseController()
    {
        return Addon::ModuleDir().DIRECTORY_SEPARATOR. self::HTTP . DIRECTORY_SEPARATOR. self::CONTROLLER;
    }

    protected function routeArea($controller, $action, $vars, $customRoute, $isClient = false)
    {
        $this->initialData($vars, $isClient)
            ->getBaseDirController($isClient);

        if (!$customRoute) {
            require_once $this->basePathController . DIRECTORY_SEPARATOR . $controller . '.php';
            $class = new $controller($this->vars);
        }else{
            $class =  new AdminController($this->vars);
        }


        return $class->{$action}();
    }

    protected function initialData($vars, $isClient)
    {
        $this->vars = $vars;
        $this->initialDataClient($isClient);

        return $this;
    }

    protected function getBaseDirController($isClient = false){
        $this->basePathController = $isClient ?
            self::clientController() :
            self::baseController();

        return $this;
    }

    protected function initialDataClient($isClient = false){
        if ($isClient) {
            $this->vars['session'] = $this->getSession();
            $this->vars['lang'] = $this->getLanguage();
        }
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

    private function checkRoute($action, $method)
    {
        $routes = require Addon::ModuleDir().DIRECTORY_SEPARATOR.'Routes'.DIRECTORY_SEPARATOR.'routes.php';

        $check = true;

        if (!$routes[$method][$action]) {
            $check = false;
            $this->customRoute = true;
//            throw new RouteNotFoundException('route not found');
        }

        if (!$this->isController($routes[$method][$action]['controller'], $method)) {
            $check = false;
//            throw new ControllerNotFoundException('controller not found');
        }

        if (!$this->isMethod($routes[$method][$action]['controller'], $method)) {
            $check = false;
//            throw new MethodNotFoundException('method not found');
        }

        return $check;
    }

    private function isController($controller, $method)
    {
        $controller = explode('@', $controller)[0];
        $this->controller = $controller;

        if ($method == 'client' && file_exists(self::clientController().DIRECTORY_SEPARATOR. $controller .'.php')) {
                return true;
        }

        if ($method == 'admin' && file_exists(self::baseController().DIRECTORY_SEPARATOR. $controller .'.php')) {
                return true;
        }

        return false;
    }

    private function isMethod($action, $method)
    {
        $controller = explode('@', $action)[0];
        $function = explode('@', $action)[1];
        $this->method = $function;

        if ($method == 'client' && !$this->customRoute) {
            require_once self::clientController().DIRECTORY_SEPARATOR. $controller .'.php';
        }

        if ($method == 'admin' && !$this->customRoute) {
            require_once self::baseController().DIRECTORY_SEPARATOR. $controller .'.php';
        }

        if (!$this->customRoute) {
            $object = new $controller([]);

            if (method_exists($object, $function)) {
                return true;
            }
        }

        return false;
    }

    private function getMethod() {
        return (strpos(debug_backtrace()[1]['function'], 'client')) ?
            'client' : 'admin';
    }

    private function getBaseRoute() {
        return (require __DIR__.'/../'.'config.php')['base_route'];
    }

    private function checkBaseRoute($action, $method) {
        $this->controller =  ($method === 'admin') ?
            'AdminController':
            'ClientController';

        $this->method = explode('/', $action)[1];
        $this->customRoute = true;

        return [
            $this->controller,
            $this->method
        ];
    }
}