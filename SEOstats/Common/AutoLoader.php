<?php
namespace SEOstats\Common;

/**
 * PSR-0 Autoloader
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/02/03
 */
class AutoLoader
{
    /**
     * @var string The namespace prefix for this instance.
     */
    protected $namespace = '';

    /**
     * @var string The filesystem prefix to use for this instance
     */
    protected $path = '';

    /**
     * Build the instance of the autoloader
     *
     * @param string $namespace The prefixed namespace this instance will load
     * @param string $path The filesystem path to the root of the namespace
     */
    public function __construct($namespace, $path)
    {
        $this->namespace = ltrim($namespace, '\\');
        $this->path      = rtrim($path, '/\\') . DIRECTORY_SEPARATOR;
    }

    /**
     * Try to load a class
     *
     * @param string $class The class name to load
     *
     * @return boolean If the loading was successful
     */
    public function load($className)
    {
        $class = ltrim($className, '\\');

        if (strpos($class, $this->namespace) !== 0) {
            return false;
        }

        $nsparts   = explode('\\', $class);
        $class     = array_pop($nsparts);
        $nsparts[] = '';
        $path      = $this->path . implode(DIRECTORY_SEPARATOR, $nsparts);
        $path     .= str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';

        if (!is_readable($path)) {
            return false;
        }

        require $path;

        return class_exists($className,false);
    }

    /**
     * Register the autoloader to PHP
     *
     * @return boolean The status of the registration
     */
    public function register()
    {
        return spl_autoload_register(array($this, 'load'));
    }

    /**
     * Unregister the autoloader to PHP
     *
     * @return boolean The status of the unregistration
     */
    public function unregister()
    {
        return spl_autoload_unregister(array($this, 'load'));
    }
}
