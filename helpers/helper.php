<?php

use greenweb\addon\Addon;

if (! method_exists('storage_path')){

    function storage_path() {
        return Addon::$instance->config['BaseDir'].DIRECTORY_SEPARATOR.Addon::$instance->config['StoragePath'];
    }
}

if (! method_exists('config')){

    function config($file = null, $key = null) {
        $config = [];

        if ($file != null) {
            $config = require Addon::$instance->config['BaseDir'].DIRECTORY_SEPARATOR.
                Addon::$instance->config['ConfigPath'].
                DIRECTORY_SEPARATOR.$file.'.php';
        }

        if ($key != null) {
            $config = isset($config[$key]) ? $config[$key] : $config;

        }

        return $config;
    }
}