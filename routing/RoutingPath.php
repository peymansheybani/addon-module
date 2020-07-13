<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-12 11:08 AM
 **/

namespace greenweb\addon\routing;


use ReflectionMethod;
use greenweb\addon\Addon;
use Illuminate\Database\Eloquent\Model;
use greenweb\addon\component\Component;
use greenweb\addon\exceptions\ComponentNotLoadedException;
use greenweb\addon\exceptions\PathParamsNotFoundException;

class RoutingPath extends Component
{
    /**
     * @var array
     */
    public $pathParams;
    /**
     * @var bool
     */
    public $hasParamPath = false;

    public function __construct(Addon $app)
    {
        parent::__construct($app);

        if (is_null($this->app->routing)) {
            throw new ComponentNotLoadedException('component routing not loaded');
        }
    }

    public function parsRoute($action)
    {
        $baseParams = [];
        $route = null;
        collect($this->app->routing->routes)->each(function ($value, $key) use ($action, &$baseParams, &$route) {
            preg_match_all('#\{(!)?(\w+)\}#', $key, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            if ($matches){
                $baseParams[$key] = $matches;
            }

            if ($key == $action) {
                $route = $key;
                return true;
            }
        });

        if (!is_null($route)) {
            return $route;
        }

        collect($baseParams)->each(function ($value, $key) use(&$route, $action){
            $actionCollect = collect(explode('/', $action));
            $keyCollect = collect(explode('/', $key));

            if ($actionCollect->count() === $keyCollect->count()) {
                $word = $key;
                $params = [];
                foreach ($value as $k => $v){
                    $params[] = $v[2][0];
                    $word = str_replace($v[0][0],null,$word);
                }

                $wordArray = explode('/', $word);
                $actionArray = $actionCollect->toArray();
                $check = true;
                $values = [];
                foreach ($wordArray as $ka => $val) {
                    if ($wordArray[$ka] !== "" && $wordArray[$ka] == $actionArray[$ka]) {
                        continue;
                    }

                    if ($wordArray[$ka] == "" && !is_null($actionArray[$ka])) {
                        $values[] = $actionArray[$ka];
                        continue;
                    }

                    $check = false;
                }

                $finalParams = [];
                foreach ($params as $i => $m) {
                    $finalParams[$m] = $values[$i];
                }

                if ($check) {
                    $this->pathParams = $finalParams;
                    $this->hasParamPath = true;
                    $route = $key;
                }
            }
        });

        if (!is_null($route)){
            return $route;
        }

        return false;
    }

    public function getMethodParams($class, $method)
    {
        $data = [];
        if ($this->app->routingPath->hasParamPath) {
            $reflection = new ReflectionMethod($class, $method);

            collect($reflection->getParameters())->each(function ($param, $key) use(&$data){
                $name = null;
                if ($param->getType()){
                    $name = $param->getType()->getName();
                }

                if(is_null($this->app->routingPath->pathParams[$param->getName()])) {
                    throw new PathParamsNotFoundException("{$param->getName()} not found in {$this->method} method");
                }

                $model = null;
                if (class_exists($name)) {
                    $model = new $name();
                }

                if ($model instanceof Model) {
                    $data[] = $model->find($this->app->routingPath->pathParams[$param->getName()]);
                }else {
                    $data[] = $this->app->routingPath->pathParams[$param->getName()];
                }
            });
        }

        return $data;
    }

}