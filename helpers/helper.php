<?php

use greenweb\addon\Addon;

if (! method_exists('storage_path')){

    function storage_path() {
        return Addon::ModuleDir().DIRECTORY_SEPARATOR.'storage';
    }
}