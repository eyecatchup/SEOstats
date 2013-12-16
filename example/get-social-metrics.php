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
         *  Get the total count of +1s for $url on Google+.
         */
        echo "Google+ Shares:          " .
            Social::getGooglePlusShares() . PHP_EOL;

        /**
         *  Get the total count of mentions of $url on Twitter.
         */
        echo "Twitter Shares:          " .
            Social::getTwitterShares() . PHP_EOL;

        /**
         *  Get interaction counts (shares, likes, comments, clicks) for $url on Facebook.
         */
        echo "Facebook Shares:         ";
            print_r(Social::getFacebookShares());

        /**
         *  Get the total count of shares for $url via VKontakte.
         */
        echo "VKontakte Shares:        " .
            Social::getVKontakteShares() . PHP_EOL;

        /**
         *  Get the total count of shares for $url via Pinterest.
         */
        echo "Pinterest Shares:        " .
            Social::getPinterestShares() . PHP_EOL;

        /**
         *  Get the total count of shares for $url via LinkedIn.
         */
        echo "LinkedIn Shares:         " .
            Social::getLinkedInShares() . PHP_EOL;

        /**
         *  Get interaction counts (shares, comments, clicks, reach) for host of $url on Xing.
         */
        echo "Xing Shares:             ";
            print_r(Social::getXingShares());

        /**
         *  Get the total count of shares for $url via Delicious.
         */
        echo "Delicious Shares:        " .
            Social::getDeliciousShares() . PHP_EOL;

        /**
         *  Get the Top10 tags for $url from Delicious.
         */
        echo "Xing Shares:             ";
            print_r(Social::getDeliciousTopTags());

        /**
         *  Get the total count of shares for $url via Digg.
         */
        echo "Digg Shares:             " .
            Social::getDiggShares() . PHP_EOL;

        /**
         *  Get the total count of shares for $url via StumpleUpon.
         */
        echo "StumpleUpon Shares:      " .
            Social::getStumbleUponShares() . PHP_EOL;
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
