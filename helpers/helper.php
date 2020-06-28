<?php

use greenweb\addon\Addon;

if (! method_exists('storage_path')){

    function storage_path() {
        return Addon::ModuleDir().DIRECTORY_SEPARATOR.'storage';
    }
}

if (! method_exists('config')){

    function config($file = null, $key = null) {
        $config = [];

        if ($file != null) {
            $config = require Addon::ModuleDir().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.$file.'.php';
        }

        if ($key != null) {
            $config = isset($config[$key]) ? $config[$key] : $config;

        }

        return $config;
    }
}