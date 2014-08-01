<?php
/**
 * SEOstats Example - Get Sistrix Visibility-Index
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2014/07/31
 */

// NOTE: The given path to the autoload.php assumes that you installed SEOstats via composer 
// and copied this example file from ./vendor/seostats/seostats/example/example.php to ./example.php
//
// If you did NOT installed SEOstats via composer but instead downloaded the zip file from github.com, 
// you need to follow this steps:
//
// 1. Comment-in line 24 (remove hash char "#") and comment-out line 25 (prepend hash char "#")
// 2. Copy this example file (and the others) from ./example/example.php to ./example.php
//
// For further reference see: https://github.com/eyecatchup/SEOstats/issues/49

// Bootstrap the library / register autoloader
#require_once realpath(__DIR__ . '/SEOstats/bootstrap.php');
require_once realpath(__DIR__ . '/vendor/autoload.php');

try {
    $url = 'http://www.spiegel.de/';

    // Get the Sistrix Visibility-Index for the given URL.
    $vi = \SEOstats\Services\Sistrix::getVisibilityIndex($url);
    echo "The current Sistrix Visibility-Index for {$url} is {$vi}." . PHP_EOL;

    // Get the current available credits for the SISTRIX API (this API call does cost 0 credits currently).
    $credits = \SEOstats\Services\Sistrix::getApiCredits();
    echo "Currently your Sistrix API Key has {$credits} credits available." . PHP_EOL;

    // Get the Sistrix Visibility-Index for the given URL by using the SISTRIX API
    $vi = \SEOstats\Services\Sistrix::getVisibilityIndexByApi($url);
    echo "The current Sistrix Visibility-Index for {$url} is {$vi}." . PHP_EOL;
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
