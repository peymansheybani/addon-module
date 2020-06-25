<?php


namespace greenweb\addon\routing;

class ClientRouting extends Routing
{
    public function route($controller, $action, $vars, $customRoute)
    {
        return $this->routeArea($controller, $action, $vars, $customRoute, true);
    }
}