<?php


namespace greenweb\addon\foundation;


use greenweb\addon\component\Component;

abstract class UserFoundation extends Component
{
    abstract public function current();
    abstract public function can($perm);
}