<?php


namespace greenweb\addon\session;


use Iterator;
use ArrayAccess;
use greenweb\addon\component\Component;
use Illuminate\Contracts\Support\Arrayable;

class Session extends Component implements ArrayAccess , Iterator, Arrayable
{
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

    public function offsetExists($offset)
    {
        return isset($_SESSION[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($_SESSION[$offset]) ? $_SESSION[$offset]:'';
    }

    public function offsetSet($offset, $value)
    {
        $_SESSION[$offset] = $value;

        return $this;
    }

    public function offsetUnset($offset)
    {
        unset($_SESSION[$offset]);

        return $this;
    }

    public function current()
    {
        return current($_SESSION);
    }

    public function next()
    {
        return next($_SESSION);
    }

    public function key()
    {
        return key($_SESSION);
    }

    public function valid()
    {
        return (key($_SESSION) !== NULL && key($_SESSION) !== FALSE);
    }

    public function rewind()
    {
        reset($_SESSION);
    }

    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $_SESSION);
    }
}