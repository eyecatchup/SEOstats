<?php
/**
 * SEOstats Example - Get SEMrush Metrics as Graphs
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2014/07/26
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

use \SEOstats\Services\SemRush;

try {
    $url = 'http://www.google.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        /**
         *  Print HTML code for the 'search engine traffic'-graph.
         */
        echo SemRush::getDomainGraph(1);

        /**
         *  Print HTML code for the 'search engine traffic price'-graph.
         */
        echo SemRush::getDomainGraph(2);

        /**
         *  Print HTML code for the 'number of adwords ads'-graph,
         *  using explicitly SEMRush's data for google.de (german index).
         */
        echo SemRush::getDomainGraph(3, false, 'de');

        /**
         *  Print HTML code for the 'adwords traffic'-graph, using
         *  explicitly SEMRush's data for google.de (german index)
         *  and specific graph dimensions of 320*240 px.
         */
        echo SemRush::getDomainGraph(4, false, 'de', 320, 240);

        /**
         *  Print HTML code for the 'adwords traffic price'-graph,
         *  using explicitly SEMRush's data for google.de (german index),
         *  specific graph dimensions of 320*240 px and specific
         *  graph colors (black lines and red dots for data points).
         */
        echo SemRush::getDomainGraph(5, false, 'de', 320, 240, '000000', 'ff0000');
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
