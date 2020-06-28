<?php


namespace greenweb\addon\Admin;


use greenweb\addon\Addon;
use greenweb\addon\models\Admin as AdminModel;
use greenweb\addon\foundation\UserFoundation;

class Admin extends UserFoundation
{
    public $app;

    public function __construct(Addon $app)
    {
        $this->app = $app;
    }

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