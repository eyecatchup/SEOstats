# SEOstats: SEO data library for PHP

SEOstats is a powerful open source tool to get a lot of SEO relevant data such as detailed backlink analyses, keyword and traffic statistics, website trends, page authority, the Google Pagerank, the Alexa Trafficrank and much more.

SEOstats offers over 50 different methods to request SEO relevant data for websites and gathers data from Google, Yahoo, Bing, SEOmoz, Alexa, Facebook and Twitter.

## Note

The dev branch code is a work in progress. To get the latest stable release, use the master branch https://github.com/eyecatchup/SEOstats/tree/master or the downloads tab https://github.com/eyecatchup/SEOstats/downloads.

## Usage

### TOC

* <a href='#brief-example-of-use'>Brief Example of Use</a>  
* <a href='#seostats-google-methods'>Google Methods</a>   
 * <a href='#google-toolbar-pagerank'>Toolbar Pagerank</a>   
 * <a href='#google-pagespeed-service'>Pagespeed Service</a>   
 * <a href='#google-websearch-index'>Websearch Index</a>   
 * <a href='#google-serp-details'>SERP Details</a>   
* <a href='#seostats-open-site-explorer-methods'>Open Site Explorer Methods</a>   
* <a href='#seostats-semrush-methods'>SEMRush Methods</a>   
 * <a href='#semrush-domain-reports'>Domain Reports</a>   
 * <a href='#semrush-graphs'>Graphs</a>   
* <a href='#seostats-sistrix-methods'>Sistrix Methods</a>  
 * <a href='#sistrix-visibility-index'>Visibility Index</a>   
 * <a href='#sistrix-openlinkgraph-metrics'>OpenLinkGraph Metrics</a>  
* <a href='#seostats-social-media-methods'>Social Media Methods</a>  

<hr>   
 
### Brief Example of Use
You have several methods to define the URL to request data for.
```php
<?php
try {
  $url1 = 'http://www.nahklick.de';
  $url2 = 'http://www.bing.com';
  $url3 = 'http://www.google.com';

  // Set a URL using the constructor function.
  $SEOstats = new SEOstats($url1);
  print $SEOstats->Google()->getPageRank(); // prints 4

  // Set a URL using the `setUrl` function (overwrites any previously set URL). Eg:
  $SEOstats = new SEOstats($url1);
  $SEOstats->setUrl($url2);
  print $SEOstats->Google()->getPageRank(); // prints 8

  // Set a URL using optional parameter calls (overwrites any previously set URL). Eg:
  $SEOstats = new SEOstats($url1);
  $SEOstats->setUrl($url2);
  print $SEOstats->Google()->getPageRank($url3); // prints 9
}
catch (SEOstatsException $e) {
  die($e->getMessage());
}
```
<hr>

## SEOstats Google Methods

### Google Toolbar PageRank

```php
<?php
  //  Returns the Google Toolbar PageRank.
  print $SEOstats->Google()->getPageRank();
```

### Google Pagespeed Service

```php
<?php
  // Returns an array, containing the resultset for a 'Google Pagespeed' analysis.
  print_r( $SEOstats->Google()->getPagespeedAnalysis() );

  // Returns the 'Google Pagespeed' analysis' total score.
  print $SEOstats->Google()->getPagespeedScore();
```

### Google Websearch Index

```php
<?php
  // Returns the total amount of results for a Google site-search for the object URL.
  print $SEOstats->Google()->getSiteindexTotal();

  // Returns the total amount of results for a Google link-search for the object URL.
  print $SEOstats->Google()->getBacklinksTotal();

  // Returns the total amount of results for a Google search.
  print $SEOstats->Google()->getSearchResultsTotal('keyword');
```

### Google SERP Details

```php
<?php
  // Returns an array of URLs and titles for the first 100 results for a Google web search for 'keyword'.
  print_r ( $seostats->Google()->getSerps('keyword') );

  // Returns an array of URLs and titles for the first 200 results for a Google site-search for $url.
  print_r ( $seostats->Google()->getSerps("site:$url", 200) );

  // Returns an array of URLs, titles and position in SERPS for occurrences of $url
  // within the first 1000 results for a Google web search for 'keyword'.
  print_r ( $seostats->Google()->getSerps('keyword', 1000, $url) );
```
<hr>

## SEOstats Open Site Explorer Methods

```php
<?php
  // Returns basic SEOmoz page metrics (Page-Authority, Domain Authority, Domain-Inlinks, total Inlinks).
  print_r ( $seostats->OpenSiteExplorer()->getPageMetrics() );
```
<hr>

## SEOstats SEMRush Methods

### SEMRush Domain Reports

```php
<?php
  // Returns an array containing the SEMRush main report (includes DomainRank, Traffic- & Ads-Data)
  print_r ( $seostats->SEMRush()->getDomainRank() );

  // Returns an array containing the domain rank history.
  print_r ( $seostats->SEMRush()->getRankHistory() );

  // Returns an array containing data for competeing (auto-detected) websites.
  print_r ( $seostats->SEMRush()->getCompetitors() );

  // Returns an array containing data about organic search engine traffic, using explicitly SEMRush's german database.
  print_r ( $seostats->SEMRush()->getOrganics(0, 'de') );
```

### SEMRush Graphs

```php
<?php
  // Returns HTML code for the 'search engine traffic'-graph.
  print $seostats->SEMRush()->getDomainGraph(1);

  // Returns HTML code for the 'search engine traffic price'-graph.
  print $seostats->SEMRush()->getDomainGraph(2);

  // Returns HTML code for the 'number of adwords ads'-graph, using explicitly SEMRush's german database.
  print $seostats->SEMRush()->getDomainGraph(3, 0, 'de');

  // Returns HTML code for the 'adwords traffic'-graph, using explicitly SEMRush's german database and
  // specific graph dimensions of 320*240 px.
  print $seostats->SEMRush()->getDomainGraph(4, 0, 'de', 320, 240);

  // Returns HTML code for the 'adwords traffic price '-graph, using explicitly SEMRush's german database,
  // specific graph dimensions of 320*240 px and specific graph colors (black lines and red dots for data points).
  print $seostats->SEMRush()->getDomainGraph(5, 0, 'de', 320, 240, '000000', 'ff0000');
```
<hr>

## SEOstats Sistrix Methods

### Sistrix Visibility Index

```php
<?php
  // Returns the Sistrix visibility index
  // @link http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
  print $seostats->Sistrix()->getVisibilityIndex();
```

### Sistrix OpenLinkGraph Metrics
```php
<?php
  // Returns the Sistrix OpenLinkGraph metrics summary
  print $seostats->Sistrix()->OpenLinkGraph()->getSummary();
```
<hr>

## SEOstats Social Media Methods

### Google+ PlusOnes

```php
<?php
  // Returns integer PlusOne count
  print $seostats->Social()->getGoogleShares();
```

### Facebook Interactions

```php
<?php
  // Returns an array of total counts for overall Facebook interactions count, shares, likes, comments and clicks.
  print_r ( $seostats->Social()->getFacebookShares() );
```

### Twitter Mentions

```php
<?php
  // Returns integer tweet count for URL mentions
  print $seostats->Social()->getTwitterShares();
```

### Other Shares

```php
<?php
  // Returns the total count of URL shares via Delicious
  print $seostats->Social()->getDeliciousShares();
  
  // Returns array of top ten delicious tags for a URL
  print_r ( $seostats->Social()->getDeliciousTopTags() );
  
  // Returns the total count of URL shares via Digg
  print $seostats->Social()->getDiggShares();
  
  // Returns the total count of URL shares via LinkedIn
  print $seostats->Social()->getLinkedInShares();
  
  // Returns the total count of URL shares via Pinterest
  print $seostats->Social()->getPinterestShares();
  
  // Returns the total count of URL shares via StumbleUpon
  print $seostats->Social()->getStumbleUponShares();
  
  // Returns the total count of URL shares via VKontakte
  print $seostats->Social()->getVKontakteShares();
```
<hr>

## License

(c) 2010 - 2012, Stephan Schmitz eyecatchup@gmail.com   
License: MIT, http://eyecatchup.mit-license.org   
URL: https://github.com/eyecatchup/SEOstats   
