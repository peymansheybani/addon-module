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
    public $controller;
    public $method;
    public $basePathController;
    public $vars;
    public $language;
    public $routes;
    private $customRoute = false;
    /**
     * @var string
     */
    public $routeType;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
        $this->routes = require $this->app->config['BaseDir']. DIRECTORY_SEPARATOR .$app->config['RoutePath'].'routes.php';
    }

    public function __call($method, $params)
    {
        $method = $this->getMethod(debug_backtrace()[1]['function']);
        $this->routeType = $method;
        if($this->checkRoute($params[0], $method)){
            $this->customRoute = false;
        } else {
            $values = $this->checkBaseRoute($params[0], $method);
            $this->controller = $values[0];
            $this->method = $values[1];
            $this->customRoute = true;
        }

        $map = [
            'admin' => new AdminRouting($this->app),
            'client' => new ClientRouting($this->app)
        ];
        $route = $map[$method] ?? $map['admin'];

        return $route->route($this->controller, $this->method, $params[1], $this->customRoute);
    }

    public function clientController()
    {
        return Addon::ModuleDir(). DIRECTORY_SEPARATOR . $this->app->config['ClientControllerPath'];
    }

    public function adminController()
    {
        return $this->app->config['AdminControllerPath'];
    }

    protected function routeArea($controller, $action, $vars, $customRoute, $isClient = false)
    {
        $this->initialData($vars, $isClient)
            ->getBaseDirController($isClient);

        if (!$customRoute) {
            $controller = $this->app->config['ControllerNameSpace']."\\".$controller;
            $class = new $controller($this->app, $this->vars);
        }else{
            $class =  new Controller($this->app, $this->vars);
        }

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

    private function getBaseDirController($isClient = false){
        $this->basePathController = $isClient ?
            self::clientController() :
            self::adminController();

        return $this;
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
        return (strpos($method,'client')) ?
            'client' : 'admin';
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