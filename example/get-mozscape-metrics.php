<?php
/**
 * SEOstats Example - Get Mozscape Link Metrics
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

use \SEOstats\Services\Mozscape as Mozscape;

try {
    $url = 'http://www.nahklick.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        // The normalized 10-point MozRank score of the URL.
        $mozrank = Mozscape::getMozRank();
        echo "The current (normalized) URL MozRank for {$url} is {$mozrank}/10." . PHP_EOL;

        // The raw MozRank score of the URL.
        $mozrankRaw = Mozscape::getMozRankRaw();
        echo "The current (raw) URL MozRank for {$url} is {$mozrankRaw}." . PHP_EOL;

        // The number of links (equity or nonequity or not, internal or external) to the URL.
        $alllinks = Mozscape::getLinkCount();
        echo "The current Mozscape URL link count for {$url} is {$alllinks}." . PHP_EOL;

        // The number of external equity links to the URL (http://apiwiki.moz.com/glossary#equity).
        $eqlinks = Mozscape::getEquityLinkCount();
        echo "The current Mozscape URL equity link count for {$url} is {$eqlinks}." . PHP_EOL;

        // A normalized 100-point score representing the likelihood
        // of the URL to rank well in search engine results.
        $pa = Mozscape::getPageAuthority();
        echo "The current Mozscape page authority for {$url} is {$pa}/100." . PHP_EOL;

        // A normalized 100-point score representing the likelihood
        // of the domain of the URL to rank well in search engine results.
        $da = Mozscape::getDomainAuthority();
        echo "The current Mozscape domain authority for {$seostats::getDomain()} is {$da}/100." . PHP_EOL;
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
