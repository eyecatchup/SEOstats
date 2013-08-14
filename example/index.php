<?php
// Set a timezone
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('date.timezone', 'Europe/Berlin');

// Bootstrap the library / register autoloader
require_once (__DIR__ . '\..') . '\SEOstats\bootstrap.php';

use \SEOstats\Services as SEOstats;

try {
	$uri = 'http://www.nahklick.de';
	
	// Create a new SEOstats instance
	$seostats = new \SEOstats\SEOstats;
	
	if ($seostats->setUrl($uri)) {
		#$alexa = $seostats->Alexa();
		#$google = $seostats->Google();
		#$opensiteexplorer = $seostats->OpenSiteExplorer();
		#$semrush = $seostats->SEMRush();
		#print '<pre>';
		#print_r($seostats->Google()->getSerps('SEOstats'));
		#print $alexa->getPageLoadTime();
		#print $alexa->getPageLoadTime();
		#print $google->getSearchResultsTotal('test');
		#print $google->getSearchResultsTotal('test');
		#print_r($semrush->getDomainRank());
		#print_r($semrush->getDomainRankHistory());
		#print_r($semrush->getCompetitors());
		#print_r($opensiteexplorer->getPageMetrics());
		#print '</pre>';
		#print $semrush->getDomainGraph();
		#print $semrush->getDomainGraph(2);
		#print $semrush->getDomainGraph(3);
		#print $semrush->getDomainGraph(4);
		#print $semrush->getDomainGraph(5);
		
		#print_r(SEOstats\Google::getPagespeedAnalysis());
		#print SEOstats\Google::getPagespeedScore();
		
		#/*
		print "= Alexa Metrics: \n";
		print "    Page Load Time:       " . SEOstats\Alexa::getPageLoadTime() . "\n";
		print "= Google Metrics: \n";
		print "    Page-Rank:            " . SEOstats\Google::getPageRank() . "\n";
		print "    Total Indexed Pages:  " . SEOstats\Google::getSiteindexTotal() . "\n";
		print "= Sistrix Metrics: \n";
		print "    Visibility-Index:     " . SEOstats\Sistrix::getVisibilityIndex() . "\n";
		print "= Open-Site-Explorer Metrics: \n";
		$ose = SEOstats\OpenSiteExplorer::getPageMetrics();
		print "    Domain-Authority:     " . $ose['domainAuthority'] . "\n";
		print "    Page-Authority:       " . $ose['pageAuthority'] . "\n";
		print "    Linking Root Domains: " . $ose['linkingRootDomains'] . "\n";
		print "    Total Inbound Links:  " . $ose['totalInboundLinks'];
		#*/
	}	
} 
catch (\Exception $e) {
	echo 'Caught SEOstatsException: ' .  $e->getMessage();
}