<?php


namespace greenweb\addon\routing;

class AdminRouting extends Routing
{
    public function route($action, $vars)
    {
        return $this->routeArea($action, $vars, true);
    }
}