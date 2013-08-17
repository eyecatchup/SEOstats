<?php
/**
 * SEOstats Example - Get Google PageRank
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/16
 */

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

try {
    $url = 'http://www.nahklick.de/';

    // Get the Google PageRank for the given URL.
    $pagerank = \SEOstats\Services\Google::getPageRank($url);
    echo "The current Google PageRank for {$url} is {$pagerank}.";
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
