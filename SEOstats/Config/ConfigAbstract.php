<?php
namespace SEOstats\Config;

abstract class ConfigAbstract
{
    protected static $config = [];

    public static function configure($config)
    {
        // Update only existed
        foreach ($config as $key => $value) {
            static::set($key, $config[$key]);
        }
    }

    public static function set($name, $value)
    {
        if (isset(static::$config[$name])) {
            static::$config[$name] = $value;
        }
    }

    public static function get($name)
    {
        if (!isset(static::$config[$name])) {
            throw new \Exception("Value for $name does not exist");
        }
        return static::$config[$name];
    }
}
