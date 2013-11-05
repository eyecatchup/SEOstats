<?php
/**
 * SEOstats Example - Get Google Serps
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

// Bootstrap the library / register autoloader
require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use \SEOstats\Services\Google as Google;

try {
    /**
     *  Get an array containing URL and title for the first
     *  100 results for a Google web search for 'keyword'.
     */
    $serps = Google::getSerps('keyword');
    print_r($serps);

    /**
     *  Get an array containing URL and title for the first
     *  200 results for a Google web search for 'keyword'.
     */
    //$serps = Google::getSerps('keyword', 200);
    //print_r($serps);

    /**
     *  Get an array containing URL, title and position in Serps
     *  for each occurrence of 'http://www.domain.tld' within the
     *  first 1000 results for a Google web search for 'keyword'.
     */
    //$serps = Google::getSerps('keyword', 1000, 'http://www.domain.tld');
    //print_r($serps);
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
