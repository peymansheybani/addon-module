<?php


namespace greenweb\addon\routing;


use greenweb\addon\Addon;
use greenweb\addon\component\Component;
use greenweb\addon\controller\Controller;
use greenweb\addon\exceptions\RouteNotFoundException;
use greenweb\addon\exceptions\MethodNotFoundException;
use greenweb\addon\exceptions\ControllerNotFoundException;

/**
 * Class Routing
 * @package greenweb\addon\routing
 *
 * @method string route($action, $vars)
 */
class Routing extends Component
{
    public $vars;
    public $method;
    public $routes;
    public $language;
    public $routeType;
    public $controller;

    private $routeClass;
    private $methodCalled;
    private $customRoute = false;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
        $this->setRoutes();
    }

    public function __call($method, $params)
    {
        return $this->getMethod(debug_backtrace()[1]['function'])
                ->setRouteData($params, $this->methodCalled)
                ->setRoutClass()
                ->routeClass->route($this->controller, $this->method, $params[1], $this->customRoute);
    }

    protected function routeArea($controller, $action, $vars, $customRoute, $isClient = false)
    {
        $this->initialData($vars, $isClient);

        $controller = (!$customRoute) ?
            $this->app->config['ControllerNameSpace']."\\".$controller :
            Controller::class;

        $class = new $controller($this->app, $this->vars);

        return $class->{$action}();
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
        $file = $this->app->config['BaseDir'] . DIRECTORY_SEPARATOR . $this->app->config['LangPath'] . $this->app->config['language'] . ".php";

        return require_once $file;
    }

    private function initialData($vars, $isClient)
    {
        $this->vars = $vars;
        $this->initialDataClient($isClient);

        return $this;
    }

    private function checkRoute($action, $method)
    {
        $check = true;

        if (!$this->routes[$method][$action]) {
            $check = false;
            $this->customRoute = true;
//            throw new RouteNotFoundException('route not found');
        }

        if (!$this->isController($this->routes[$method][$action]['controller'], $method)) {
            $check = false;
//            throw new ControllerNotFoundException('controller not found');
        }

        if (!$this->isMethod($this->routes[$method][$action]['controller'], $method)) {
            $check = false;
//            throw new MethodNotFoundException('method not found');
        }

        return $check;
    }

    private function isController($controller, $method)
    {
        $controller = explode('@', $controller)[0];
        $this->controller = $controller;

        $myController = $this->app->config['ControllerNameSpace']."\\".$this->controller;
        if ($method == 'client' && class_exists($myController)) {
            return true;
        }

        if ($method == 'admin' && class_exists($myController)) {
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
            $controller = $this->app->config['ControllerNameSpace']."\\".$controller;
        }

        if ($method == 'admin' && !$this->customRoute) {
            $controller = $this->app->config['ControllerNameSpace']."\\".$controller;
        }

        if (!$this->customRoute) {
            $object = new $controller($this->app, []);

            if (method_exists($object, $function)) {
                return true;
            }
        }

        return false;
    }

    private function getMethod($method) {
        $this->methodCalled = (strpos($method,'client')) ? 'client' : 'admin';

        return $this;
    }

    private function checkBaseRoute($action, $method) {
        $this->controller =  'Controller';

        $this->method = explode('/', $action)[1];
        $this->customRoute = true;

        return [
            $this->controller,
            $this->method
        ];
    }

    private function setRouteData($params, string $method)
    {
        $this->routeType = $method;

        if ($this->checkRoute($params[0], $method)) {
            $this->customRoute = false;
        } else {
            $values = $this->checkBaseRoute($params[0], $method);
            $this->controller = $values[0];
            $this->method = $values[1];
            $this->customRoute = true;
        }

        return $this;
    }

    private function setRoutClass()
    {
        $map = [
            'admin' => new AdminRouting($this->app),
            'client' => new ClientRouting($this->app)
        ];
        $this->routeClass = $map[$this->methodCalled] ?? $map['admin'];

        return $this;
    }

    private function setRoutes()
    {
        $this->routes = require $this->app->config['BaseDir'] . DIRECTORY_SEPARATOR .
            $this->app->config['RoutePath'] . 'routes.php';
    }
}