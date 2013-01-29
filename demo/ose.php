<?php 
ini_set('max_execution_time', 300);

require '../src/seostats.php';

try {
  $url = "http://www.nahklick.de";
  
  // create a new SEOstats object to request SEO metrics
  $seostats = new SEOstats($url);

  $openSiteExplorer = $seostats->OpenSiteExplorer()->getPageMetrics();
  
  #header('content-type: text/plain; charset=utf-8');
  var_dump($openSiteExplorer);
} 
catch (SEOstatsException $e) {
  die($e->getMessage());
}