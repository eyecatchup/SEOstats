<?php
/**
 * SEOstats Example - Get Google Pagespeed Analysis
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

try {
    $url = 'http://www.nahklick.de/';

    /**
     *  Get the Google Pagespeed Analysis metrics for the given URL.
     *  NOTE: Requires an API key to be set in \SEOstats\Config\ApiKeys.php
     */
    $pagespeed = \SEOstats\Services\Google::getPagespeedAnalysis($url);
    print_r($pagespeed);
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
