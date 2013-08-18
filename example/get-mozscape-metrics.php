<?php
/**
 * SEOstats Example - Get Mozscape Link Metrics
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/19
 */

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

use \SEOstats\Services\Mozscape as Mozscape;

try {
    $url = 'http://www.google.de/contact/impressum.html';

    // Create a new SEOstats instance.
    $seostats = new \SEOstats\SEOstats;

    // Bind the URL to the current SEOstats instance.
    if ($seostats->setUrl($url)) {

        // The normalized 10-point MozRank score of the URL.
        $mozrank = Mozscape::getMozRank();
        echo "The current (normalized) URL MozRank for {$url} is {$mozrank}/10.\r\n";

        // The raw MozRank score of the URL.
        $mozrankRaw = Mozscape::getMozRankRaw();
        echo "The current (raw) URL MozRank for {$url} is {$mozrankRaw}.\r\n";

        // The number of links (equity or nonequity or not, internal or external) to the URL.
        $alllinks = Mozscape::getLinkCount();
        echo "The current Mozscape URL link count for {$url} is {$alllinks}.\r\n";

        // The number of external equity links to the URL (http://apiwiki.moz.com/glossary#equity).
        $eqlinks = Mozscape::getEquityLinkCount();
        echo "The current Mozscape URL equity link count for {$url} is {$eqlinks}.\r\n";

        // A normalized 100-point score representing the likelihood
        // of the URL to rank well in search engine results.
        $pa = Mozscape::getPageAuthority();
        echo "The current Mozscape page authority for {$url} is {$pa}/100.\r\n";

        // A normalized 100-point score representing the likelihood
        // of the domain of the URL to rank well in search engine results.
        $da = Mozscape::getDomainAuthority();
        echo "The current Mozscape domain authority for {$seostats::getDomain()} is {$da}/100.\r\n";
    }
}
catch (\Exception $e) {
    echo 'Caught SEOstatsException: ' .  $e->getMessage();
}
