<?php


namespace greenweb\addon\controller;


use greenweb\addon\Addon;

class AdminController extends Controller
{
    public function view($template, $params)
    {
        $uri = Addon::getTemplateUri($template);

        $smarty = new \Smarty();
        $smarty->assign($params);
        $smarty->caching = false;

        return $smarty->display($this->DirAdminView($uri));
    }

    private function DirAdminView($uri)
    {
        return Addon::ModuleDir().DIRECTORY_SEPARATOR.Addon::ADMIN_VIEW.DIRECTORY_SEPARATOR.$uri.'.tpl';
    }
}