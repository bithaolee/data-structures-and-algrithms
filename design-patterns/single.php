<?php

class Single
{

    private static $instance = null;

    private function __construct()
    {

    }

    private function __clone()
    {

    }

    public static function instance()
    {
        if (!self::$instance instanceof Single) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}

$instance1 = Single::instance();
$instance2 = Single::instance();

var_dump($instance1 === $instance2);
