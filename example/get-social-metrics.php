<?php
/**
 * SEOstats Example - Get Social Network Metrics
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

use \SEOstats\Services\Social as Social;

try {
    $url = 'http://www.google.com/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        echo "Social network metrics for " . $url . PHP_EOL;

        /**
         *  Get the total count of Google +1s for the given URL.
         */
        echo "Google+ Shares:          " .
            Social::getGooglePlusShares() . PHP_EOL;

        /**
         *  Get the total count of Twitter mentions for the given URL.
         */
        echo "Twitter Shares:          " .
            Social::getTwitterShares() . PHP_EOL;

        /**
         *  Get the total count of Facebook Shares for the given URL.
         */
        echo "Facebook Shares:         ";
            print_r(Social::getFacebookShares());

        /**
         *  Get the total count of VKontakte shares for the given URL.
         */
        echo "VKontakte Shares:        " .
            Social::getVKontakteShares() . PHP_EOL;

        /**
         *  Get the total count of Pinterest shares for the given URL.
         */
        echo "Pinterest Shares:        " .
            Social::getPinterestShares() . PHP_EOL;

        /**
         *  Get the total count of LinkedIn shares for the given URL.
         */
        echo "LinkedIn Shares:         " .
            Social::getLinkedInShares() . PHP_EOL;

        /**
         *  Get the total count of Delicious shares for the given URL.
         */
        echo "Delicious Shares:        " .
            Social::getDeliciousShares() . PHP_EOL;

        /**
         *  Get the total count of Digg shares for the given URL.
         */
        echo "Digg Shares:             " .
            Social::getDiggShares() . PHP_EOL;

        /**
         *  Get the total count of StumpleUpon shares for the given URL.
         */
        echo "StumpleUpon Shares:      " .
            Social::getStumbleUponShares() . PHP_EOL;
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
