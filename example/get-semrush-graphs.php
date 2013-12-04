<?php
/**
 * SEOstats Example - Get SEMrush Metrics as Graphs
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
// 1. Download this file: https://raw.github.com/eyecatchup/SEOstats/dev-253/SEOstats/Common/AutoLoader.php
// 2. Place it in ./SEOstats/Common/AutoLoader.php
// 3. Comment-in line 25 (remove hash char "#") and comment-out line 26 (prepend hash char "#")
//
// For further reference see: https://github.com/eyecatchup/SEOstats/issues/49

// Bootstrap the library / register autoloader
#require_once __DIR__ . DIRECTORY_SEPARATOR . 'SEOstats' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

use \SEOstats\Services\SEMRush as SEMrush;

try {
    $url = 'http://www.nahklick.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        /**
         *  Print HTML code for the 'search engine traffic'-graph.
         */
        echo SEMrush::getDomainGraph(1);

        /**
         *  Print HTML code for the 'search engine traffic price'-graph.
         */
        echo SEMrush::getDomainGraph(2);

        /**
         *  Print HTML code for the 'number of adwords ads'-graph,
         *  using explicitly SEMRush's data for google.de (german index).
         */
        echo SEMrush::getDomainGraph(3, false, 'de');

        /**
         *  Print HTML code for the 'adwords traffic'-graph, using
         *  explicitly SEMRush's data for google.de (german index)
         *  and specific graph dimensions of 320*240 px.
         */
        echo SEMrush::getDomainGraph(4, false, 'de', 320, 240);

        /**
         *  Print HTML code for the 'adwords traffic price'-graph,
         *  using explicitly SEMRush's data for google.de (german index),
         *  specific graph dimensions of 320*240 px and specific
         *  graph colors (black lines and red dots for data points).
         */
        echo SEMrush::getDomainGraph(5, false, 'de', 320, 240, '000000', 'ff0000');
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
