<?php


namespace greenweb\addon\User;


use greenweb\addon\foundation\UserFoundation;
use greenweb\addon\Addon;
use greenweb\addon\models\User as UserModel;

class User extends UserFoundation
{
    public $app;

    public function __construct(Addon $app)
    {
        $this->app = $app;
    }

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

    private function hasUser() {
        if (isset($_SESSION['uid'])) {
            return true;
        }

        return false;
    }
}