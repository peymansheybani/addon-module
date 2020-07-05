<?php


namespace greenweb\addon\permission;


use greenweb\addon\Addon;
use greenweb\addon\component\Component;
use greenweb\addon\models\Permission as Perm;

class permission extends Component
{
    public $permissions;

    public function __construct(Addon $app)
    {
        parent::__construct($app);
        $this->permissions = $this->app->config['permission'];;
    }

    public function has($perm, $user_id = null)
    {
        return Perm::hasPerm($perm, $user_id);
    }
}