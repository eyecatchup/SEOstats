<?php

include( "./src/seostats.php" );
$url = "http://t3n.de";

set_time_limit( 600 );

$oSeoStats = new seostats\SEOstats( $url );
$oAlexa = $oSeoStats->Alexa();
$oGoogle = $oSeoStats->Google();
$oOpenSiteExplorer = $oSeoStats->OpenSiteExplorer();
$oSemRush = $oSeoStats->SEMRush();
$oSistrix = $oSeoStats->Sistrix();
$oSocial = $oSeoStats->Social();

header("Content-Type: text/plain" );


print( "Alexa\r\n\r\n" );

print( "GlobalRank\r\n" );
print_r( $oAlexa->getGlobalRank() );

print( "\r\n" );

print( "CountryRank\r\n" );
print_r( $oAlexa->getCountryRank() );

print( "\r\n" );

print( "BacklinkCount\r\n" );
print_r( $oAlexa->getBacklinkCount() );

print( "\r\n" );

print( "PageLoadTime\r\n" );
print_r( $oAlexa->getPageLoadTime() );

/** Graphs coming soon **/

print( "\r\n\r\n" );

print( "Google\r\n\r\n" );

print( "PageRank\r\n" );
print_r( $oGoogle->getPageRank() );

print( "\r\n" );

print( "PagespeedAnalysis\r\n" );
print_r( $oGoogle->getPagespeedAnalysis() );

print( "\r\n" );

print( "PageSpeedScore\r\n" );
print_r( $oGoogle->getPagespeedScore() );

print( "\r\n" );

print( "SiteIndexTotal()\r\n" );
print_r( $oGoogle->getSiteIndexTotal() );

print( "\r\n" );

print( "BacklinksTotal\r\n" );
print_r( $oGoogle->getBacklinksTotal() );

print( "\r\n" );

print( "SearchResultsTotal\r\n" );
print_r( $oGoogle->getSearchResultsTotal("keyword") );

print( "\r\n" );

print( "Serps\r\n" );
print_r( $oGoogle->getSerps("keyword") );

print( "\r\n" );

print( "Serps(Site,200)\r\n" );
print_r( $oGoogle->getSerps("site:$url", 200) );

print( "\r\n" );

print( "Serps(Keyword,1000,url)\r\n" );
print_r( $oGoogle->getSerps("keyword", 100, $url) );

print( "\r\n\r\n" );

print( "OpenSiteExplorer\r\n\r\n" );

print( "PageMetrics\r\n" );
print_r( $oOpenSiteExplorer->getPageMetrics() );
print( "\r\n" );

print( "\r\n\r\n" );

print( "SEMRush\r\n\r\n" );

print( "DomainRank\r\n" );
print_r( $oSemRush->getDomainRank() );
print( "\r\n" );

print( "DomainRankHistory\r\n" );
print_r( $oSemRush->getDomainRankHistory() );
print( "\r\n" );

print( "Competitors\r\n" );
print_r( $oSemRush->getCompetitors() );
print( "\r\n" );

print( "OrganicKeywords(0,de)\r\n" );
print_r( $oSemRush->getOrganicKeywords(0, 'de') );

/** Graphs coming soon **/

print( "\r\n\r\n" );

print( "Sistrix\r\n\r\n" );

print( "VisibilityIndex\r\n" );
print_r( $oSistrix->getVisibilityIndex() );
print( "\r\n" );

print( "\r\n\r\n" );

print( "Social\r\n\r\n" );

print( "GooglePlusOnes\r\n" );
print_r( $oSocial->getGoogleShares() );
print( "\r\n" );

print( "Facebook Interactions\r\n" );
print_r( $oSocial->getFacebookShares() );
print( "\r\n" );

print( "Twitter Mentions\r\n" );
print_r( $oSocial->getTwitterShares() );
print( "\r\n" );

/** Other social shares later **/