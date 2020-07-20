<?php

namespace greenweb\addon;


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
use is\support\models\TblDomainsAdditionalFields;

/**
 * Class Addon
 * @package greenweb\addon
 *
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

class Addon
{
    /**
     * @var $this
     */
    public static $instance;

    public $config;
    public $routes;
    public $database;
    public $migration;

    private $vars;
    private $tempMenu;

    public function __construct($config)
    {
        static::$instance = $this;
        $this->setConfig($config);
        $this->setDatabase();
        $this->setMigration();
        $this->init();
    }

    public function __get($name)
    {
        if (isset($this->config['loader'][$name])) {
            return $this->addComponent($name, new $this->config['loader'][$name]($this));
        }
    }

    public function addComponent($name, Component $component)
    {
        return $this->$name = new $component($this);
    }

    public function run($data)
    {
        $this->vars = $data['vars'];
        $this->setMenu();
        $this->getMenu($this->config['menu']);
        $data['config']['menu'] = $this->tempMenu;
        $data['config']['ModulesPath'] = $this->config['ModulesPath'];
        $this->config = $data['config'];
        $class = new $data['controller']($this, $data['vars']);

        return $class->{$data['method']}(...$data['data']);
    }

    private function setConfig($config)
    {
        $baseConfig = require 'config.php';
        $this->config = array_merge($baseConfig, $config);
    }

    private function setDatabase()
    {
        $this->database = require 'database.php';
    }

    private function setMigration()
    {
        $this->migration = new Migration($this);
    }

    private function init()
    {
        collect($this->config['loader'])->each(function ($component, $name) {
            $object = new $component($this);
            if(method_exists($object, 'boot')){
                $this->addComponent($name, $object);
            }
        });
    }

    public function setMenu($subModule = null, $menu = [], $isRoot = null)
    {
        $menus = collect($this->config['modules'])->map(function ($app, $key) use ($subModule) {
            $app = new $app();
            return $app->setMenu($key.'/', $this->config['menu'], is_null($subModule));
        })->values()->toArray();

        if (!empty($menu) && empty($menus)) {
            $array = [
                trim($subModule, '/'),
                'is_module' => trim($subModule, '/'),
                'icon' => 'icon',
                'submenu' => $this->config['menu']
            ];

            if ($isRoot) {
                return $array;
            }

            $menu[] = $array;

            return $menu;
        }

        if (!empty($menus) && !is_null($subModule)) {
            return $menus;
        }

        if (!empty($menus) && is_null($subModule)) {
            foreach ($menus as $key => $value) {
                array_push($this->config['menu'], $value);
            }

            return $this->config['menu'];
        }

        return $this->config['menu'];
    }

    public function getMenu($array, $isSub = false, $parent = '')
    {
        if ($isSub) {
            $this->tempMenu .= '<ul class="dropdown-menu">';
        }

        foreach ($array as $menu) {
            $submodule = isset($menu['is_module'])? $menu['is_module'].'/':'';
            $submodule = $parent.$submodule;
            $link = isset($menu[1]) ? $menu[1]:"#";
            $link = $this->vars['modulelink'].'&action='.$parent.$link;

            if (!$isSub) {
                $this->tempMenu .= '<div class="dropdown">';
            }

            if (!$isSub && isset($menu['submenu'])){
                $this->tempMenu .= '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">'.
                    $menu[0].'<span class="caret"></span></button>';
            }else if (!$isSub && !isset($menu['submenu'])){
                $this->tempMenu .= '<a href="'.$link.'" class="btn btn-default">'.
                    $menu[0].'</a>';
            }

            if (isset($menu['submenu'])) {
                if ($isSub) {
                    $this->tempMenu .= '<li class="dropdown-submenu">';
                    $this->tempMenu .= '<a class="test" tabindex="-1" href="#">'.$menu[0].' <span class="caret"></span></a>';
                }
                $this->getMenu2($menu['submenu'], true, $submodule);
            }else{
                $this->tempMenu .= '<li><a tabindex="-1" href="'.$link.'">'.$menu[0].'</a></li>';
            }

            if (isset($menu['submenu']) && $isSub) {
                $this->tempMenu .= '</li>';
            }

            if (!$isSub){
                $this->tempMenu .= '</div>';
            }
        }

        if ($isSub) {
            $this->tempMenu .= '</ul>';
        }
    }
}