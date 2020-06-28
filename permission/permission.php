<?php


namespace greenweb\addon\permission;


use greenweb\addon\Addon;
use greenweb\addon\models\Permission as Perm;

class permission
{
    public $app;
    public $permissions;

    public function __construct(Addon $app)
    {
        $this->app = $app;
        $this->permissions = $this->app->config['permission'];
    }

    public function has($perm, $user_id = null)
    {
        return Perm::hasPerm($perm);
    }


}