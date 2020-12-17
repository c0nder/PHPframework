<?php

namespace App\Interfaces;

class Singleton
{
    protected static $instance = null;

    protected function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}