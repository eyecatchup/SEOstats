<?php
/**
 * SEOstats Example - Get Open-Site-Explorer (by MOZ) Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/17
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
    $url = 'http://www.nahklick.de/';

    // Get Open-Site-Explorer metrics for the given URL.
    $ose = \SEOstats\Services\OpenSiteExplorer::getPageMetrics($url);

    echo "Open-Site-Explorer metrics for " . $url . PHP_EOL;

    // MOZ Domain-Authority Rank - Predicts this domain's ranking potential in the search engines 
    // based on an algorithmic combination of all link metrics.
    echo "Domain-Authority:         " .
        $ose->domainAuthority->result . ' (' .      // Int - e.g 42
        $ose->domainAuthority->unit   . ') - ' .    // String - "/100"
        $ose->domainAuthority->descr  . PHP_EOL;    // String - Result value description

    // MOZ Page-Authority Rank - Predicts this page's ranking potential in the search engines 
    // based on an algorithmic combination of all link metrics.
    echo "Page-Authority:           " .
        $ose->pageAuthority->result . ' (' .        // Int - e.g 48
        $ose->pageAuthority->unit   . ') - ' .      // String - "/100"
        $ose->pageAuthority->descr  . PHP_EOL;      // String - Result value description

    // Just-Discovered Inbound Links - Number of links to this page found over the past %n days, 
    // indexed within an hour of being shared on Twitter.
    echo "Just-Discovered Links:    " .
        $ose->justDiscovered->result . ' (' .       // Int - e.g 140
        $ose->justDiscovered->unit   . ') - ' .     // String - e.g "32 days"
        $ose->justDiscovered->descr  . PHP_EOL;     // String - Result value description

    // Root-Domain Inbound Links - Number of unique root domains (e.g., *.example.com) 
    // containing at least one linking page to this URL.
    echo "Linking Root Domains:     " .
        $ose->linkingRootDomains->result . ' (' .   // Int - e.g 210
        $ose->linkingRootDomains->unit   . ') - ' . // String - "Root Domains"
        $ose->linkingRootDomains->descr  . PHP_EOL; // String - Result value description

    // Total Links - All links to this page including internal, external, followed, and nofollowed.
    echo "Total Links:              " .
        $ose->totalLinks->result . ' (' .           // Int - e.g 31571
        $ose->totalLinks->unit   . ') - ' .         // String - "Total Links"
        $ose->totalLinks->descr  . PHP_EOL;         // String - Result value description
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
