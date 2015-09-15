<?php
namespace SEOstats\Config;

interface Configurable
{
    public static function configure($config);
    public static function get($value);
}
