<?php
/**
 * SEOstats Example - Get Social Network Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

use \SEOstats\Services\Social as Social;

try {
    $url = 'http://www.google.com/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        echo "Social network metrics for {$url}\r\n";

        /**
         *  Get the total count of Google +1s for the given URL.
         */
        echo "Google+ Shares:          " .
            Social::getGooglePlusShares() . "\r\n";

        /**
         *  Get the total count of Twitter mentions for the given URL.
         */
        echo "Twitter Shares:          " .
            Social::getTwitterShares() . "\r\n";

        /**
         *  Get the total count of Facebook Shares for the given URL.
         */
        echo "Facebook Shares:         ";
            print_r(Social::getFacebookShares());

        /**
         *  Get the total count of VKontakte shares for the given URL.
         */
        echo "VKontakte Shares:        " .
            Social::getVKontakteShares() . "\r\n";

        /**
         *  Get the total count of Pinterest shares for the given URL.
         */
        echo "Pinterest Shares:        " .
            Social::getPinterestShares() . "\r\n";

        /**
         *  Get the total count of LinkedIn shares for the given URL.
         */
        echo "LinkedIn Shares:         " .
            Social::getLinkedInShares() . "\r\n";

        /**
         *  Get the total count of Delicious shares for the given URL.
         */
        echo "Delicious Shares:        " .
            Social::getDeliciousShares() . "\r\n";

        /**
         *  Get the total count of Digg shares for the given URL.
         */
        echo "Digg Shares:             " .
            Social::getDiggShares() . "\r\n";

        /**
         *  Get the total count of StumpleUpon shares for the given URL.
         */
        echo "StumpleUpon Shares:      " .
            Social::getStumbleUponShares() . "\r\n";
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
