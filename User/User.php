<?php


namespace greenweb\addon\User;


use greenweb\addon\Addon;
use greenweb\addon\component\Component;
use greenweb\addon\models\User as UserModel;
use greenweb\addon\foundation\UserFoundation;
use greenweb\addon\exceptions\ComponentNotLoadedException;

class User extends UserFoundation
{
    public function current()
    {
        if (!$this->hasUser()) {
            return false;
        }

        return UserModel::find($_SESSION['uid']);
    }

    public function can($perm)
    {
        if (!$this->hasUser()) {
            return false;
        }

        $this->app->permission->has($perm, $this->current()->id);
    }

    public function __construct(Addon $app)
    {
        parent::__construct($app);

        if (!$this->app->permission) {
            throw new ComponentNotLoadedException('permission component not loaded');
        }
    }


    private function hasUser() {
        if (isset($_SESSION['uid'])) {
            return true;
        }

        return false;
    }
}