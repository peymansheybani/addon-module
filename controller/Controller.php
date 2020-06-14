<?php


namespace greenweb\addon\controller;


class Controller
{
    public $vars;

    public function __construct($vars)
    {
        $this->vars = $vars;
    }
}