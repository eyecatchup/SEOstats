# SEOstats: SEO metrics library for PHP

SEOstats is a powerful open source PHP library to request a bunch of SEO relevant metrics such as detailed backlink analyses, keyword and traffic statistics, website trends, page authority, the Google Pagerank, the Alexa Trafficrank and much more.

SEOstats offers over 50 different methods and gathers data from Google, Yahoo, Bing, SEOmoz, SEMRush, Sistrix, Alexa, Facebook, Twitter & more.

## Dependencies

SEOstats requires the PHP5-CURL, PHP5-JSON and PHP5-SOAP extensions.

## Installation

The recommended way to install SEOstats is [through composer](http://getcomposer.org). Just create a `composer.json` file and run the `php composer.phar install` command (Windows users use `composer install`) to install it:

    {
        "require": {
            "seostats/seostats": "dev-master"
        }
    }

Alternatively, you can download the [`SEOstats.zip`](https://github.com/eyecatchup/SEOstats/archive/master.zip) file and extract it.

## Usage

### TOC

* <a href='#brief-example-of-use'>Brief Example of Use</a>  
* <a href='#seostats-alexa-methods'>Alexa Methods</a>   
 * <a href='#alexa-traffic-metrics'>Alexa Traffic Metrics</a>   
 * <a href='#alexa-traffic-graphs'>Alexa Traffic Graphs</a>   
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

## SEOstats Alexa Methods

### Alexa Traffic Metrics
```php
<?php
  // Returns the global Alexa Page-Rank.
  print $SEOstats->Alexa()->getGlobalRank();

  // Returns a country-specific Alexa Page-Rank.
  print_r( $SEOstats->Alexa()->getCountryRank() );

  // The total amount of backlinks returned by Alexa.
  print $SEOstats->Alexa()->getBacklinkCount();

  // Returns pageload time information based on measurements by Alexa's crawler.
  print $SEOstats->Alexa()->getPageLoadTime();
```

### Alexa Traffic Graphs

```php
<?php
  // Returns HTML code for the 'daily traffic trend'-graph.
  print $SEOstats->Alexa()->getTrafficGraph(1);

  // Returns HTML code for the 'daily pageviews (percent)'-graph.
  print $SEOstats->Alexa()->getTrafficGraph(2);

  // Returns HTML code for the 'daily pageviews per user'-graph.
  print $SEOstats->Alexa()->getTrafficGraph(3);

  // Returns HTML code for the 'time on site (in minutes)'-graph.
  print $SEOstats->Alexa()->getTrafficGraph(4);
  
  // Returns HTML code for the 'bounce rate (percent)'-graph.
  print $SEOstats->Alexa()->getTrafficGraph(5);
  
  // Returns HTML code for the 'search visits'-graph, using specific graph dimensions of 320*240 px.
  print $SEOstats->Alexa()->getTrafficGraph(6, 0, 320, 240);
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
  print_r ( $SEOstats->Google()->getSerps('keyword') );

  // Returns an array of URLs and titles for the first 200 results for a Google site-search for $url.
  print_r ( $SEOstats->Google()->getSerps("site:$url", 200) );

  // Returns an array of URLs, titles and position in SERPS for occurrences of $url
  // within the first 1000 results for a Google web search for 'keyword'.
  print_r ( $SEOstats->Google()->getSerps('keyword', 1000, $url) );
```
<hr>

## SEOstats Open Site Explorer Methods

```php
<?php
  // Returns basic SEOmoz page metrics (Page-Authority, Domain Authority, Domain-Inlinks, total Inlinks).
  print_r ( $SEOstats->OpenSiteExplorer()->getPageMetrics() );
```
<hr>

## SEOstats SEMRush Methods

### SEMRush Domain Reports

```php
<?php
  // Returns an array containing the SEMRush main report (includes DomainRank, Traffic- & Ads-Data)
  print_r ( $SEOstats->SEMRush()->getDomainRank() );

  // Returns an array containing the domain rank history.
  print_r ( $SEOstats->SEMRush()->getDomainRankHistory() );

  // Returns an array containing data for competeing (auto-detected) websites.
  print_r ( $SEOstats->SEMRush()->getCompetitors() );

  // Returns an array containing data about organic search engine traffic, using explicitly SEMRush's german database.
  print_r ( $SEOstats->SEMRush()->getOrganicKeywords(0, 'de') );
```

### SEMRush Graphs

```php
<?php
  // Returns HTML code for the 'search engine traffic'-graph.
  print $SEOstats->SEMRush()->getDomainGraph(1);

  // Returns HTML code for the 'search engine traffic price'-graph.
  print $SEOstats->SEMRush()->getDomainGraph(2);

  // Returns HTML code for the 'number of adwords ads'-graph, using explicitly SEMRush's german database.
  print $SEOstats->SEMRush()->getDomainGraph(3, 0, 'de');

  // Returns HTML code for the 'adwords traffic'-graph, using explicitly SEMRush's german database and
  // specific graph dimensions of 320*240 px.
  print $SEOstats->SEMRush()->getDomainGraph(4, 0, 'de', 320, 240);

  // Returns HTML code for the 'adwords traffic price '-graph, using explicitly SEMRush's german database,
  // specific graph dimensions of 320*240 px and specific graph colors (black lines and red dots for data points).
  print $SEOstats->SEMRush()->getDomainGraph(5, 0, 'de', 320, 240, '000000', 'ff0000');
```
<hr>

## SEOstats Sistrix Methods

### Sistrix Visibility Index

```php
<?php
  // Returns the Sistrix visibility index
  // @link http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
  print $SEOstats->Sistrix()->getVisibilityIndex();
```
<hr>

## SEOstats Social Media Methods

### Google+ PlusOnes

```php
<?php
  // Returns integer PlusOne count
  print $SEOstats->Social()->getGoogleShares();
```

### Facebook Interactions

```php
<?php
  // Returns an array of total counts for overall Facebook interactions count, shares, likes, comments and clicks.
  print_r ( $SEOstats->Social()->getFacebookShares() );
```

### Twitter Mentions

```php
<?php
  // Returns integer tweet count for URL mentions
  print $SEOstats->Social()->getTwitterShares();
```

### Other Shares

```php
<?php
  // Returns the total count of URL shares via Delicious
  print $SEOstats->Social()->getDeliciousShares();
  
  // Returns array of top ten delicious tags for a URL
  print_r ( $SEOstats->Social()->getDeliciousTopTags() );
  
  // Returns the total count of URL shares via Digg
  print $SEOstats->Social()->getDiggShares();
  
  // Returns the total count of URL shares via LinkedIn
  print $SEOstats->Social()->getLinkedInShares();
  
  // Returns the total count of URL shares via Pinterest
  print $SEOstats->Social()->getPinterestShares();
  
  // Returns the total count of URL shares via StumbleUpon
  print $SEOstats->Social()->getStumbleUponShares();
  
  // Returns the total count of URL shares via VKontakte
  print $SEOstats->Social()->getVKontakteShares();
```
<hr>

## License

(c) 2010 - 2013, Stephan Schmitz eyecatchup@gmail.com   
License: MIT, http://eyecatchup.mit-license.org   
URL: https://github.com/eyecatchup/SEOstats   
