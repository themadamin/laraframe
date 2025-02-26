<?php

namespace Core;

use Exception;

class App
{
    protected static Container $container;

    /**
     * @param $container
     * @return void
     */
    public static function setContainer($container): void
    {
        static::$container = $container;
    }

    /**
     * @return Container
     */
    public static function container(): Container
    {
        return static::$container;
    }

    /**
     * @param $key
     * @param $resolver
     * @return void
     */
    public static function bind($key, $resolver): void
    {
        static::container()->bind($key, $resolver);
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public static function resolve($key): mixed
    {
        return static::container()->resolve($key);
    }

    /**
     * @param $key
     * @return mixed
     * @throws Exception
     */
    public static function get($key): mixed
    {
        return static::container()->get($key);
    }
}
