<?php
namespace NN\Util;

use NN\Logic\UserLogic;
use NN\Service\SearchService;

class IoC
{
    private static $_instance;
    private function __construct()
    {

    }

    public static function Init()
    {
        if (self::$_instance == null) {
            $container = new \League\Container\Container;
            $container->add('UserLogic', new UserLogic);
            $container->add('SearchService', new SearchService);
            self::$_instance = $container;
        }
    }

    public static function get($name)
    {
        return self::$_instance->get($name);
    }

    public static function set($name, $val)
    {
        self::$_instance->add($name, $val);
    }
}