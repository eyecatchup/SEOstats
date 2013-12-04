<?php
/**
 * SEOstats Example - Get Alexa Metrics
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

use \SEOstats\Services\Alexa as Alexa;

try {
    $url = 'http://www.google.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        echo "Alexa metrics for " $url . PHP_EOL;

        // Get the global Alexa Traffic Rank (last 3 months).
        echo "Global Rank (quarterly): " .
            Alexa::getGlobalRank() . PHP_EOL;

        // Get the global Traffic Rank for the last month.
        echo "Global Rank (monthly):   " .
            Alexa::getMonthlyRank() . PHP_EOL;

        // Get the global Traffic Rank for the last week.
        echo "Global Rank (weekly):    " .
            Alexa::getWeeklyRank() . PHP_EOL;

        // Get the global Traffic Rank for yesterday.
        echo "Global Rank (daily):     " .
            Alexa::getDailyRank() . PHP_EOL;

        // Get the country-specific Alexa Traffic Rank.
        echo "Country Rank:            ";
        $countryRank = Alexa::getCountryRank();
        if (is_array($countryRank)) {
            echo $countryRank['rank'] . ' (in ' .
                 $countryRank['country'] . ")" . PHP_EOL;
        }
        else {
            echo "{$countryRank}\r\n";
        }

        // Get Alexa's backlink count for the given domain.
        echo "Total Backlinks:         " .
            Alexa::getBacklinkCount() . PHP_EOL;

        // Get Alexa's page load time info for the given domain.
        echo "Page load time:          " .
            Alexa::getPageLoadTime() . PHP_EOL;
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
