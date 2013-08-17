<?php
/**
 * SEOstats Example - Get Open-Site-Explorer Metrics
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

    // Get Open-Site-Explorer metrics for the given URL.
    $ose = \SEOstats\Services\OpenSiteExplorer::getPageMetrics($url);

    echo "Open-Site-Explorer metrics for {$url}\r\n";

    echo "Domain-Authority:     " .
        $ose['domainAuthority'] . "\r\n";    // String - e.g "71/100"

    echo "Page-Authority:       " .
        $ose['pageAuthority'] . "\r\n";      // String - eg "67/100"

    echo "Linking Root Domains: " .
        $ose['linkingRootDomains'] . "\r\n"; // Integer - eg "512"

    echo "Total Inbound Links:  " .
        $ose['totalInboundLinks'];           // Integer - eg "7013"
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
