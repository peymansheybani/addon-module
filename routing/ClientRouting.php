<?php


namespace greenweb\addon\routing;

class ClientRouting extends Routing
{
    public function route($action, $vars)
    {
        return $this->routeArea($action, $vars);
    }
}