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
 * @method string admin($action, $vars)
 * @method string client($action, $vars)
 */
class Routing extends Component
{
    public $vars;
    public $method;
    public $routes;
    public $language;
    public $routeType;
    public $controller;

    private $methodCalled;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
        $this->setRoutes();
    }

    public function __call($method, $params)
    {
        return $this->getMethod($method)
                ->setRouteData($params)
                ->routeArea();
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
        $file = $this->app->BaseDir . DIRECTORY_SEPARATOR . rtrim($this->app->LangPath,'/').'/' . $this->app->language . ".php";

        return require_once $file;
    }

    protected function routeArea()
    {
        $isClient = $this->routeType == 'client';
        $this->initialData($isClient);
        $class = new $this->controller($this->app, $this->vars);

        $data = $this->app->routingPath->getMethodParams($class, $this->method);

        return [
            'controller' => $this->controller,
            'method' => $this->method,
            'vars' => $this->vars,
            'data' => $data,
            'app' => $this->app
        ];
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
        $routePath = $this->app->BaseDir . DIRECTORY_SEPARATOR .
            rtrim($this->app->RoutePath, '/'). '/' . $this->app->RouteName;

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
        $this->methodCalled = $method == 'client' ? 'client':'admin';

        return $this;
    }

    private function checkMethod($controller)
    {
        $object = new $controller($this->app, []);

        return method_exists($object, $this->method);
    }

    private function checkRoute($action)
    {
        if ($routePath = $this->app->routingPath) {
            $action = $routePath->parsRoute($action);
        }

        if (!$this->routes[$action]) {
            $this->method = explode('/', $action)[1];

            if (!$this->checkMethod(Controller::class)) {
                throw new RouteNotFoundException("route {$action} not found");
            }
        }

        $controller = $this->routes[$action]['controller'] ?? $this->routes[$action][0];

        if (!$this->isController($controller)) {
            throw new ControllerNotFoundException("controller {$this->controller} not found");
        }

        if (!$this->isMethod($controller)) {
            throw new MethodNotFoundException("method {$this->method} not found");
        }

        return true;
    }

    private function initialData( $isClient)
    {
        $this->initialDataClient($isClient);

        return $this;
    }

    private function isController($controller)
    {
        $this->controller = $controller ?
            $this->app->ControllerNameSpace."\\".explode('@', $controller)[0]:
            \greenweb\addon\controller\Controller::class;

        if ($this->methodCalled == 'client' && class_exists($this->controller)) {
            return true;
        }

        if ($this->methodCalled == 'admin' && class_exists($this->controller)) {
                return true;
        }

        return false;
    }

    private function setRouteData($action)
    {
        $this->routeType = $this->methodCalled;
        $this->vars = $action[1];
        $action = str_replace('\\','/',$action[0]);
        $action = $this->getSubRoute($action);
        $this->checkRoute($action);

        return $this;
    }

    private function getSubRoute($action)
    {
        $arrayAction = explode('/', $action);
        $module = array_shift($arrayAction);

        if (isset($this->app->modules[$module])) {
            $this->vars['subModules'] .= $module . '/';
            $this->app = new $this->app->modules[$module]();
            $action = implode('/', $arrayAction);
            $this->routes = $this->app->routing->routes;
        }

        return $action;
    }
}