<?php
/**
 * SEOstats Example - Get Alexa Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/16
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
    $url = 'http://www.google.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        echo "Alexa metrics for " . $url . PHP_EOL;

        // Get the global Alexa Traffic Rank (last 3 months).
        echo "Global Rank:      " .
            Alexa::getGlobalRank() . PHP_EOL;

        // Get the country-specific Alexa Traffic Rank.
        echo "Country Rank:     ";
        $countryRank = Alexa::getCountryRank();
        if (is_array($countryRank)) {
            echo $countryRank['rank'] . ' (in ' .
                 $countryRank['country'] . ")" . PHP_EOL;
        }
        else {
            echo "{$countryRank}\r\n";
        }

        // Get Alexa's backlink count for the given domain.
        echo "Total Backlinks:  " .
            Alexa::getBacklinkCount() . PHP_EOL;

        // Get Alexa's page load time info for the given domain.
        echo "Page load time:   " .
            Alexa::getPageLoadTime() . PHP_EOL;
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
