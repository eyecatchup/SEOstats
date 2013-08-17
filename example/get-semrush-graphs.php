<?php
/**
 * SEOstats Example - Get SEMrush Metrics as Graphs
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

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
