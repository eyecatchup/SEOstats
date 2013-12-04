<?php
namespace SEOstats;

/**
 * Bootstrap the library.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/04
 */

// See: https://github.com/eyecatchup/SEOstats/pull/42#issuecomment-29747931
if (!ini_get('date.timezone') && function_exists('date_default_timezone_set')) {
  date_default_timezone_set('UTC');
}

/*
 *---------------------------------------------------------------
 *  SEOSTATS BASE PATH
 *---------------------------------------------------------------
 *  For increased reliability, resolve os-specific system path.
 */

// Define the __DIR__ constant for PHP versions < 5.3.
// See: http://www.php.net/manual/en/language.constants.predefined.php#113233
(@__DIR__ == '__DIR__') && define('__DIR__', dirname(__FILE__));

$base_path = __DIR__;
if (realpath( $base_path ) !== false) {
    $base_path = realpath($base_path).'/';
}
$base_path = rtrim($base_path, '/').'/';
$base_path = str_replace('\\', '/', $base_path);

define('SEOSTATSPATH', $base_path);

/*
 *---------------------------------------------------------------
 *  Register autoloader
 *---------------------------------------------------------------
 */

require_once SEOSTATSPATH . '/Common/AutoLoader.php';

$autoloader = new \SEOstats\Common\AutoLoader(__NAMESPACE__, dirname(__DIR__));

$autoloader->register();
