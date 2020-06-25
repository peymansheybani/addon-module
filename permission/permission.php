<?php


namespace greenweb\addon\permission;


use greenweb\addon\Addon;

class permission
{
    private $permissions;

    public function __construct()
    {
        $permissions =  require Addon::ModuleDir().DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'routes.php';
        $this->permissions = array_keys($permissions['admin']);
    }


}