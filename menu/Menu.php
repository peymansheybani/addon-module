<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-22 2:03 PM
 **/

namespace greenweb\addon\menu;


use greenweb\addon\Addon;
use greenweb\addon\component\Component;

class Menu extends Component
{
    public $menu;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
    }

    public function setMenu(array $menu)
    {
        $this->menu = $menu;
    }

    public function getMenu($array, $isSub = false, $parent = '', $vars)
    {
        if ($isSub) {
            $this->app->tempMenu .= '<ul class="dropdown-menu">';
        }

        foreach ($array as $menu) {
            $submodule = isset($menu['is_module'])? $menu['is_module'].'/':'';
            $submodule = $parent.$submodule;
            $link = isset($menu[1]) ? $menu[1]:"#";
            $link = $vars['modulelink'].'&action='.$parent.$link;

            if (!$isSub) {
                $this->app->tempMenu .= '<div class="dropdown">';
            }

            if (!$isSub && isset($menu['submenu'])){
                $this->app->tempMenu .= '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">'.
                    $menu[0].'<span class="caret"></span></button>';
            }else if (!$isSub && !isset($menu['submenu'])){
                $this->app->tempMenu .= '<a href="'.$link.'" class="btn btn-default">'.
                    $menu[0].'</a>';
            }

            if (isset($menu['submenu'])) {
                if ($isSub) {
                    $this->app->tempMenu .= '<li class="dropdown-submenu">';
                    $this->app->tempMenu .= '<a class="test" tabindex="-1" href="#">'.$menu[0].' <span class="caret"></span></a>';
                }
                $this->getMenu($menu['submenu'], true, $submodule, $vars);
            }elseif ($parent !== ''){
                $this->app->tempMenu .= '<li><a tabindex="-1" href="'.$link.'">'.$menu[0].'</a></li>';
            }

            if (isset($menu['submenu']) && $isSub) {
                $this->app->tempMenu .= '</li>';
            }

            if (!$isSub){
                $this->app->tempMenu .= '</div>';
            }
        }

        if ($isSub) {
            $this->app->tempMenu .= '</ul>';
        }
    }

    public function setMenuList($subModule = null, $menu = [], $isRoot = null)
    {
        $menus = collect($this->app->modules)->map(function ($app, $key) use ($subModule) {
            $app = new $app();
            return $app->menu->setMenuList($key.'/', $this->menu, is_null($subModule));
        })->values()->toArray();

        if (!empty($menu) && empty($menus)) {
            $array = [
                $this->app->AppName ?? trim($subModule, '/'),
                'is_module' => $this->app->AppName ?? trim($subModule, '/'),
                'icon' => 'icon',
                'submenu' => $this->menu
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
                array_push($this->menu, $value);
            }

            return $this->menu;
        }

        if (empty($menu) && !is_null($subModule) && empty($menus)) {
            return [
                $this->app->AppName ?? trim($subModule, '/'),
                'is_module' => $this->app->AppName ?? trim($subModule, '/'),
                'icon' => 'icon',
                'submenu' => $this->menu
            ];
        }

        return $this->menu;
    }
}