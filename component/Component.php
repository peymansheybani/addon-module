<?php
/**
 * AUTHOR : p.sheybani
 * CREATED AT : 2020-07-04 9:53 AM
 **/

namespace greenweb\addon\component;


use greenweb\addon\Addon;

class Component
{
    public $app;

    public function __construct(Addon $app)
    {
        $this->app = $app;
    }
}