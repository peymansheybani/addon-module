<?php


namespace greenweb\addon\request;


use greenweb\addon\component\Component;

class Request extends Component
{
    public static function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    public static function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
}