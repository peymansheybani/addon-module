<?php


namespace greenweb\addon\session;


use greenweb\addon\Addon;

class Session
{
    /**
     * @var Addon
     */
    private $app;

    public function __construct(Addon $app)
    {
        $this->app = $app;
    }

    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set($key, $value)
    {
        return $_SESSION[$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);

        return $_SESSION;
    }

    public function flush()
    {
        $_SESSION = [];
    }
}