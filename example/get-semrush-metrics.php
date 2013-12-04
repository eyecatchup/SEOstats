<?php
/**
 * SEOstats Example - Get SEMrush DomainRank and Competitor Metrics
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

use \SEOstats\Services\SEMRush as SEMrush;

try {
    $url = 'http://www.nahklick.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        /**
         *  Get the current SEMrush DomainRank metrics for the given URL.
         */
        print_r(SEMrush::getDomainRank());

        /**
         *  Get historical SEMrush DomainRank metrics for the given URL.
         */
        //print_r(SEMrush::getDomainRankHistory());

        /**
         *  Get competing domains for the given URL
         *  and their basic SEMrush DomainRank metrics.
         */
        //print_r(SEMrush::getCompetitors());

        /**
         *  Get organic search engine traffic data for the given URL.
         */
        //print_r(SEMrush::getOrganicKeywords());

        /**
         *  Get organic search engine traffic metrics for the given URL,
         *  using explicitly SEMrush's data for google.de (german index).
         */
        //print_r(SEMrush::getOrganicKeywords(false, 'de'));

        /**
         *  Get an array containing explainations for the
         *  result keys of the DomainRank metric methods.
         */
        //print_r(SEMrush::getParams());
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
