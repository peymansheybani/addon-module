<?php


namespace greenweb\addon\controller;


use greenweb\addon\Addon;

class Controller
{
    public $vars;
    public $app;
    public function __construct(Addon $app, $vars)
    {
        $this->app = $app;
        $this->vars = $vars;
    }
}