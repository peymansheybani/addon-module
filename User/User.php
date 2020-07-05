<?php


namespace greenweb\addon\User;


use greenweb\addon\models\User as UserModel;
use greenweb\addon\foundation\UserFoundation;

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

    private function hasUser() {
        if (isset($_SESSION['uid'])) {
            return true;
        }

        return false;
    }
}