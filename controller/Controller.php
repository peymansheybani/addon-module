<?php


namespace greenweb\addon\controller;


use Smarty;
use WHMCS\Exception;
use greenweb\addon\Addon;
use greenweb\addon\models\Role;
use greenweb\addon\models\Permission;
use greenweb\addon\exceptions\ComponentNotLoadedException;

class Controller
{
    const CLIENT = 'client';

    public $app;
    public $vars;

    private $customView;

    public function __construct(Addon $app, $vars)
    {
        $this->app = $app;
        $this->vars = $vars;
    }

    public function view($template, $params) {
        $uri = Addon::getTemplateUri($template);

        return ($this->app->routing->routeType == self::CLIENT) ?
                $this->renderClient($uri, $params):
                $this->renderAdmin($params, $uri);
    }

    public function permission() {
        if (!$this->app->permission) {
            throw new ComponentNotLoadedException('permission component not loaded');
        }

        $this->customView = true;

        return $this->view('permission',[
            'roles' => Role::allNotFullAdmin(),
            'link'  => $this->vars['modulelink'],
            'permissions' => $this->app->permission->permissions,
            'dirTemplate' => $this->getViewTemplate()
        ]);
    }

    public function addPermission(){
        http_response_code(200);
        echo  Permission::SavePermission($_POST);;

        die();
    }

    public function getPermission()
    {
        $permission = Permission::where('role_id', $this->app->request::post('role_id'))
            ->firstOrFail()
            ->permissions;

        http_response_code('200');
        echo json_encode($permission);

        die();
    }

    private function getViewTemplate(): string{
        return $this->app->config['BaseDir'].DIRECTORY_SEPARATOR.$this->app->config['AdminViewTemplatePath'];
    }

    private function renderClient($uri, $params)
    {
        return [
            'pagetitle' => 'test',
            'breadcrumb' => [$this->vars['modulelink'] => 'test'],
            'templatefile' => $this->app->config['ClientViewTemplatePath'] . $uri,
            'vars' => $params,
        ];
    }

    private function renderAdmin($params, $uri)
    {
        $params['showPerms'] = Role::hasFullAdminRole() ? true : false;
        $smarty = new Smarty();
        $smarty->assign($params);
        $smarty->caching = false;

        return $smarty->display($this->DirAdminView($uri));
    }

    private function DirAdminView($uri){
        return ($this->customView) ?
            dirname(__DIR__).'/templates/'.$uri.'.tpl':
            $this->getViewTemplate() .$uri.'.tpl';
    }
}