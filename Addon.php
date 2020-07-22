<?php

namespace greenweb\addon;


use greenweb\addon\menu\Menu;
use greenweb\addon\User\User;
use greenweb\addon\Admin\Admin;
use greenweb\addon\request\Request;
use greenweb\addon\routing\Routing;
use greenweb\addon\session\Session;
use greenweb\addon\setting\Setting;
use greenweb\addon\formatter\DateTime;
use greenweb\addon\component\Component;
use greenweb\addon\routing\RoutingPath;
use greenweb\addon\migrations\Migration;
use greenweb\addon\permission\permission;

/**
 * Class Addon
 * @package greenweb\addon
 *
 * @property Menu $menu
 * @property User $user
 * @property Admin $admin
 * @property Request $request
 * @property Routing $routing
 * @property Session $session
 * @property Setting $setting
 * @property DateTime $dateTime
 * @property Migration $migration
 * @property permission $permission
 * @property RoutingPath $routingPath
 *
 */

class Addon extends BaseAddon
{
    /**
     * @var Addon
     */
    public static $instance;
    public $routes;
    public $database;
    public $tempMenu;
    public $migration;

    private $vars;
    private $component;

    public function __construct()
    {
        self::$instance = $this;
        $this->setBaseDirectory()
             ->setMigration()
             ->setComponent()
             ->setDatabase()
             ->init();
    }

    public function run($data)
    {
        $this->vars = $data['vars'];

        if ($this->menu instanceof Component) {
            $this->menu->setMenuList();
            $this->menu->getMenu($this->menu->menu, false, '', $this->vars);
            $app = $data['app'];
            $app->tempMenu = $this->tempMenu;
        }

        $class = new $data['controller']($data['app'], $data['vars']);

        return $class->{$data['method']}(...$data['data']);
    }

    public function __get($name)
    {
        if (isset($this->component->{$name.'_component'})) {
            return $this->addComponent($name, new $this->component->{$name.'_component'}($this));
        }
    }

    public function addComponent($name, Component $component)
    {
        return $this->$name = new $component($this);
    }


    private function init()
    {
        collect(get_object_vars($this->component))->each(function ($component, $name) {
            $object = (strpos($name,'component') !== false)? new $component($this):'null';

            if(method_exists($object, 'boot')){
                $this->addComponent($name, $object);
            }
        });
    }

    private function setDatabase()
    {
        $this->database = require 'database.php';

        return $this;
    }

    private function setMigration()
    {
        $this->migration = new Migration($this);

        return $this;
    }

    private function setBaseDirectory()
    {
        $rc = new \ReflectionClass(get_class($this));
        $this->BaseDir = dirname($rc->getFileName());

        return $this;
    }

    private function setComponent()
    {
        $this->component = new Component($this);

        return $this;
    }
}