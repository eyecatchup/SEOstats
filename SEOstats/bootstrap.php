<?php
namespace SEOstats;

/**
 * Bootstrap the library.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/16
 */

// @link https://github.com/eyecatchup/SEOstats/pull/42#issuecomment-29747931
if (!ini_get('date.timezone') && function_exists('date_default_timezone_set')) {
    date_default_timezone_set('UTC');
}

// Exit if the PHP version is not equals or greater 5.3.0.
if (version_compare(PHP_VERSION, '5.3', '<')) {
    exit('SEOstats requires PHP version 5.3.0 or greater, but yours is ' . PHP_VERSION);
}

// Disabling Zend Garbage Collection to prevent segfaults with PHP5.4+
// @link https://bugs.php.net/bug.php?id=53976
if (version_compare(PHP_VERSION, '5.4', '>=') && gc_enabled()) {
    gc_disable();
}

/*
 *---------------------------------------------------------------
 *  Register custom PSR-0 Autoloader
 *---------------------------------------------------------------
 */

require_once realpath(__DIR__ . '/Common/AutoLoader.php');

$autoloader = new \SEOstats\Common\AutoLoader(__NAMESPACE__, dirname(__DIR__));

$autoloader->register();
