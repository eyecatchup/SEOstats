<?php
/**
 * SEOstats Example - Get Alexa Traffic Metrics' Graphs
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/04
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

use \SEOstats\Services\Alexa as Alexa;

try {
    $url = 'http://www.nahklick.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        /**
         *  Print HTML code for the 'daily traffic trend'-graph.
         */
        echo Alexa::getTrafficGraph(1);

        /**
         *  Print HTML code for the 'daily pageviews (percent)'-graph.
         */
        echo Alexa::getTrafficGraph(2);

        /**
         *  Print HTML code for the 'daily pageviews per user'-graph.
         */
        echo Alexa::getTrafficGraph(3);

        /**
         *  Print HTML code for the 'time on site (in minutes)'-graph.
         */
        echo Alexa::getTrafficGraph(4);

        /**
         *  Print HTML code for the 'bounce rate (percent)'-graph.
         */
        echo Alexa::getTrafficGraph(5);

        /**
         *  Print HTML code for the 'search visits'-graph, using
         *  specific graph dimensions of 320*240 px.
         */
        echo Alexa::getTrafficGraph(6, false, 320, 240);
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
