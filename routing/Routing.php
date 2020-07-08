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
    public $basePathController;

    private $methodCalled;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
        $this->setRoutes();
    }

    public function __call($method, $params)
    {
        return $this->getMethod(debug_backtrace()[1]['function'])
                ->setRouteData($params[0], $this->methodCalled)
                ->routeArea($params[1]);
    }

    public static function parsTemplateUrl($template)
    {
        return str_replace('.','/', $template);
    }


    protected function getSession()
    {
        $session = $_SESSION;

        if (isset($_SESSION['message'])) {
            unset($_SESSION['message']);
        }

        return $session;
    }

    protected function getLanguage()
    {
        $file = $this->app->config['BaseDir'] . DIRECTORY_SEPARATOR . $this->app->config['LangPath'] . $this->app->config['language'] . ".php";

        return require_once $file;
    }

    protected function routeArea($vars)
    {
        $isClient = $this->routeType == 'client';
        $this->initialData($vars, $isClient);
        $class = new $this->controller($this->app, $this->vars);

        return $class->{$this->method}();
    }

    protected function initialDataClient($isClient = false)
    {
        if ($isClient) {
            $this->vars['session'] = $this->getSession();
            $this->vars['lang'] = $this->getLanguage();
        }
    }


    private function setRoutes()
    {
        $routePath = $this->app->config['BaseDir'] . DIRECTORY_SEPARATOR .
            $this->app->config['RoutePath'] . 'routes.php';

        $this->routes = file_exists($routePath) ? require $routePath : [];

        return $this;
    }

    private function isMethod($action)
    {
        $this->method = (isset(explode('@', $action)[1])) ? explode('@', $action)[1] : $this->method;

        if ($this->checkMethod($this->controller)) {
            return true;
        }

        return false;
    }

    private function getMethod($method)
    {
        $this->methodCalled = (strpos($method,'client')) ? 'client' : 'admin';

        return $this;
    }

    private function checkMethod($controller)
    {
        $object = new $controller($this->app, []);

        return method_exists($object, $this->method);
    }

    private function checkRoute($action, $method)
    {
        $check = true;

        if (!$this->routes[$method][$action]) {
            $this->method = explode('/', $action)[1];

            if (!$this->checkMethod(Controller::class)) {
                throw new RouteNotFoundException("route {$action} not found");
            }
        }

        if (!$this->isController($this->routes[$method][$action]['controller'], $method)) {
            throw new ControllerNotFoundException("controller {$this->controller} not found");
        }

        if (!$this->isMethod($this->routes[$method][$action]['controller'])) {
            throw new MethodNotFoundException("method {$this->method} not found");
        }

        return $check;
    }

    private function initialData($vars, $isClient)
    {
        $this->vars = $vars;
        $this->initialDataClient($isClient);

        return $this;
    }

    private function isController($controller, $method)
    {
        $this->controller = $controller ?
            $this->app->config['ControllerNameSpace']."\\".explode('@', $controller)[0]:
            \greenweb\addon\controller\Controller::class;

        if ($method == 'client' && class_exists($this->controller)) {
            return true;
        }

        if ($method == 'admin' && class_exists($this->controller)) {
                return true;
        }

        return false;
    }

    private function setRouteData($action, string $method)
    {
        $this->routeType = $method;
        $action = str_replace('\\','/',$action);
        $this->checkRoute($action, $method);

        return $this;
    }
}