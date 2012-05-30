# SEOstats: SEO data library for PHP

SEOstats is a powerful open source tool to get a lot of SEO relevant data such as detailed backlink analyses, keyword and traffic statistics, website trends, page authority, the Google Pagerank, the Alexa Trafficrank and much more. 

SEOstats offers over 50 different methods to request SEO relevant data for websites and gathers data from Google, Yahoo, Bing, SEOmoz, Alexa, Facebook and Twitter.

## Usage

### Brief Example of Use
You have several methods to define the URL to request data for.
```php
<?php
try {
  $url = "http://www.nahklick.de";

  // Define object URL on init
  $SEOstats = new SEOstats($url);  
  print $SEOstats->Google->getPageRank();

  // Define object URL using the setter function
  $SEOstats = new SEOstats();
  $SEOstats->setUrl($url); 
  print $SEOstats->Google->getPageRank();

  // Define request URL on function call
  $SEOstats = new SEOstats();  
  print $SEOstats->Google->getPageRank($url);  
} 
catch (SEOstatsException $e) {
  die($e->getMessage());
}
```

### Google Stats

#### Google Toolbar PageRank
 
```php 
  //  Returns the Google Toolbar PageRank.
  print $SEOstats->Google->getPageRank();
  
  // Returns an array, containing the resultset for a 'Google Pagespeed' analysis. 
  print_r( $SEOstats->Google->getPagespeedAnalysis() );

  // Returns the 'Google Pagespeed' analysis' total score.
  print $SEOstats->Google->getPagespeedScore();  

  // Returns the total amount of results for a Google site-search for the object URL.
  print $seostats->Google()->getSiteindexTotal();
 
  // Returns the total amount of results for a Google link-search for the object URL.
  print $seostats->Google()->getBacklinksTotal();
  
  // Returns the total amount of results for a Google search.
  print $seostats->Google()->getSearchResultsTotal("keyword");
```

#### Google Pagespeed Service
 
```php   
  // Returns an array, containing the resultset for a 'Google Pagespeed' analysis. 
  print_r( $SEOstats->Google->getPagespeedAnalysis() );

  // Returns the 'Google Pagespeed' analysis' total score.
  print $SEOstats->Google->getPagespeedScore();  

  // Returns the total amount of results for a Google site-search for the object URL.
  print $seostats->Google()->getSiteindexTotal();
 
  // Returns the total amount of results for a Google link-search for the object URL.
  print $seostats->Google()->getBacklinksTotal();
  
  // Returns the total amount of results for a Google search.
  print $seostats->Google()->getSearchResultsTotal("keyword");
```

#### Google Websearch Index
 
```php   
  // Returns the total amount of results for a Google site-search for the object URL.
  print $seostats->Google()->getSiteindexTotal();
 
  // Returns the total amount of results for a Google link-search for the object URL.
  print $seostats->Google()->getBacklinksTotal();
  
  // Returns the total amount of results for a Google search.
  print $seostats->Google()->getSearchResultsTotal("keyword");
```