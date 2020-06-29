<?php


namespace greenweb\addon\controller;

use greenweb\addon\Addon;
use greenweb\addon\models\Permission;
use greenweb\addon\models\Role;
use Smarty;

class AdminController extends Controller
{
    private $customView;

    public function view($template, $params) {
        $uri = Addon::getTemplateUri($template);
        $params['showPerms'] = Role::hasFullAdminRole() ? true:false;
        $smarty = new Smarty();
        $smarty->assign($params);
        $smarty->caching = false;

        return $smarty->display($this->DirAdminView($uri));
    }

    private function DirAdminView($uri){
        return ($this->customView) ?
            dirname(__DIR__).'/templates/'.$uri.'.tpl':
            $this->getViewTemplate() .DIRECTORY_SEPARATOR.$uri.'.tpl';
    }

    public function permission() {
        $this->customView = true;

        return $this->view('permission',[
            'roles' => Role::allNotFullAdmin(),
            'link'  => $this->vars['modulelink'],
            'permissions' => $this->app->permission->permissions,
            'dirTemplate' => $this->getViewTemplate()
        ]);
    }

    public function addPermission(){
        $permission = new Permission();
        $permission->role_id = $_POST['role_id'];
        $permission->permissions = $_POST['perms'];
        $permission->save();

        http_response_code(200);
        echo  json_encode($permission);
        die();
    }

    private function getViewTemplate(): string{
        return Addon::ModuleDir() . DIRECTORY_SEPARATOR . Addon::ADMIN_VIEW;
    }
}