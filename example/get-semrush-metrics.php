<?php
/**
 * SEOstats Example - Get SEMrush DomainRank and Competitor Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2014/07/26
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

use \SEOstats\Services\SemRush;

try {
    $url = 'http://www.google.de/';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        /**
         *  Get the current SEMrush DomainRank metrics for the given URL.
         */
        print_r(SemRush::getDomainRank());

        /**
         *  Get historical SEMrush DomainRank metrics for the given URL.
         */
        //print_r(SemRush::getDomainRankHistory());

        /**
         *  Get competing domains for the given URL
         *  and their basic SEMrush DomainRank metrics.
         */
        //print_r(SemRush::getCompetitors());

        /**
         *  Get organic search engine traffic data for the given URL.
         */
        //print_r(SEMrush::getOrganicKeywords());

        /**
         *  Get organic search engine traffic metrics for the given URL,
         *  using explicitly SEMrush's data for google.de (german index).
         */
        //print_r(SemRush::getOrganicKeywords(false, 'de'));

        /**
         *  Get an array containing explainations for the
         *  result keys of the DomainRank metric methods.
         */
        //print_r(SemRush::getParams());
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
