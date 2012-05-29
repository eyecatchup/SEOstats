<?php
require("../src/seostats.php");
try {
  $url = "http://www.nahklick.de/";
  $seostats = new SEOstats($url);
  
  print $seostats->Google()->getPageRank();
  print "<pre>";
  print_r ($seostats->Google()->getPagespeedAnalysis());
  print "</pre>";
  print $seostats->Google()->getPagespeedScore();
  print "<br>";
  print $seostats->Google()->getSiteindexTotal();
  print "<br>";
  print $seostats->Google()->getBacklinksTotal();  
  print "<br>";
  print $seostats->Google()->getSearchResultsTotal(); 
} 
catch (SEOstatsException $e) {
  die($e->getMessage());
}
