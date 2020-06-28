<?php


namespace greenweb\addon\foundation;



abstract class UserFoundation
{
    abstract public function current();
    abstract public function can($perm);

    public static function currentUser() {

    }
}