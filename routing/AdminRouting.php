<?php


namespace greenweb\addon\routing;

class AdminRouting extends Routing
{
    public function route($controller, $action, $vars, $customRoute)
    {
        return $this->routeArea($controller, $action, $vars, $customRoute);
    }
}