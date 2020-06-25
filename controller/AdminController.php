<?php


namespace greenweb\addon\controller;

use greenweb\addon\Addon;
use greenweb\addon\models\Role;
use Smarty;

class AdminController extends Controller
{
    private $customView;

    public function view($template, $params)
    {
        $uri = Addon::getTemplateUri($template);
        $params['showPerms'] = Role::getRoleCurrentUser() ? true:false;
        $smarty = new Smarty();
        $smarty->assign($params);
        $smarty->caching = false;

        return $smarty->display($this->DirAdminView($uri));
    }

    private function DirAdminView($uri)
    {
        return ($this->customView) ?
            dirname(__DIR__).'/templates/'.$uri.'.tpl':
            $this->getViewTemplate() .DIRECTORY_SEPARATOR.$uri.'.tpl';
    }

    public function permission() {
        $this->customView = true;
        return $this->view('permission',[
            'test' => 'test',
            'dirTemplate' => $this->getViewTemplate()
        ]);
    }

    private function getViewTemplate(): string
    {
        return Addon::ModuleDir() . DIRECTORY_SEPARATOR . Addon::ADMIN_VIEW;
    }
}