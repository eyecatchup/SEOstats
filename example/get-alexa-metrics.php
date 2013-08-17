<?php
/**
 * SEOstats Example - Get Alexa Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

use \SEOstats\Services\Alexa as Alexa;

try {
    $url = 'http://www.google.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        echo "Alexa metrics for {$url}\r\n";

        // Get the global Alexa Traffic Rank (last 3 months).
        echo "Global Rank (quarterly): " .
            Alexa::getGlobalRank() . "\r\n";

        // Get the global Traffic Rank for the last month.
        echo "Global Rank (monthly):   " .
            Alexa::getMonthlyRank() . "\r\n";

        // Get the global Traffic Rank for the last week.
        echo "Global Rank (weekly):    " .
            Alexa::getWeeklyRank() . "\r\n";

        // Get the global Traffic Rank for yesterday.
        echo "Global Rank (daily):     " .
            Alexa::getDailyRank() . "\r\n";

        // Get the country-specific Alexa Traffic Rank.
        echo "Country Rank:            ";
        $countryRank = Alexa::getCountryRank();
        if (is_array($countryRank)) {
            echo $countryRank['rank'] . ' (in ' .
                 $countryRank['country'] . ")\r\n";
        }
        else {
            echo "{$countryRank}\r\n";
        }

        // Get Alexa's backlink count for the given domain.
        echo "Total Backlinks:         " .
            Alexa::getBacklinkCount() . "\r\n";

        // Get Alexa's page load time info for the given domain.
        echo "Page load time:          " .
            Alexa::getPageLoadTime() . "\r\n";
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
