<?php


namespace greenweb\addon\request;


class Request
{
    public static function get($key, $default)
    {
        return $_GET[$key] ?? $default;
    }

    public static function post($key, $default)
    {
        return $_POST[$key] ?? $default;
    }
}