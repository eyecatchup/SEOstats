<?php
/**
 * SEOstats Example - Get Open-Site-Explorer Metrics
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

try {
    $url = 'http://www.nahklick.de/';

    // Get Open-Site-Explorer metrics for the given URL.
    $ose = \SEOstats\Services\OpenSiteExplorer::getPageMetrics($url);

    echo "Open-Site-Explorer metrics for " . $url . PHP_EOL;

    echo "Domain-Authority:     " .
        $ose['domainAuthority'] . PHP_EOL;    // String - e.g "71/100"

    echo "Page-Authority:       " .
        $ose['pageAuthority'] . PHP_EOL;      // String - eg "67/100"

    echo "Linking Root Domains: " .
        $ose['linkingRootDomains'] . PHP_EOL; // Integer - eg "512"

    echo "Total Inbound Links:  " .
        $ose['totalInboundLinks'];           // Integer - eg "7013"
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
