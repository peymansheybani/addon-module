<?php


namespace greenweb\addon\Admin;


use greenweb\addon\foundation\UserFoundation;
use greenweb\addon\models\Admin as AdminModel;

class Admin extends UserFoundation
{
    public function can($perm)
    {
        if (!$this->hasAdmin()) {
            return false;
        }

        return $this->app->permission->has($perm, $this->Current()->id);
    }

    public function Current() {

        if (!$this->hasAdmin()) {
            return false;
        }

        return AdminModel::findOrFail($_SESSION['adminid']);
    }

    private function hasAdmin() {
        if (isset($_SESSION['adminid'])) {
            return true;
        }

        return false;
    }
}