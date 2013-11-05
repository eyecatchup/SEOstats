<?php
/**
 * SEOstats Example - Get SEMrush DomainRank and Competitor Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

// Bootstrap the library / register autoloader
require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

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
