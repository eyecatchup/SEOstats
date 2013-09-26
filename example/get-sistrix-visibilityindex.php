<?php
/**
 * SEOstats Example - Get Sitrix Visibility-Index
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/16
 */

// Bootstrap the library / register autoloader
require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

try {
    $url = 'http://www.nahklick.de/';

    // Get the Sitrix Visibility-Index for the given URL.
    $vi = \SEOstats\Services\Sistrix::getVisibilityIndex($url);
    echo "The current Sitrix Visibility-Index for {$url} is {$vi}.";
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
