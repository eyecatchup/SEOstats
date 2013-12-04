# SEOstats: SEO metrics library for PHP

SEOstats is a powerful open source PHP library to request a bunch of SEO relevant metrics such as detailed backlink analyses, keyword and traffic statistics, website trends, page authority, the Google Pagerank, the Alexa Trafficrank and much more.

SEOstats offers over 50 different methods and gathers data from Alexa, Google, SEMRush, Open-Site-Explorer (by SEOmoz), Sistrix, Facebook, Twitter & more.

## Dependencies

SEOstats requires the PHP5-CURL and PHP5-SOAP extensions.

## Installation

The recommended way to install SEOstats is [through composer](http://getcomposer.org).
To install SEOstats, just create the following `composer.json` file

    {
        "require": {
            "seostats/seostats": "dev-master"
        }
    }
and run the `php composer.phar install` (Windows: `composer install`) command in path of the `composer.json`.  

#### Command line example:

<img src="http://i.imgur.com/02TPudv.png">

#### Use SEOstats without composer

If composer is no option for you, you can still just download the [`SEOstats.zip`](https://github.com/eyecatchup/SEOstats/archive/master.zip) file and extract it. However, currently [there is an issues with autoloading](https://github.com/eyecatchup/SEOstats/issues/49) and you need to follow the instructions in the comments in the example files in order to use SEOstats (or download zip for the development version of SEOstats (2.5.3) [here](https://github.com/eyecatchup/SEOstats/archive/dev-253.zip)).

## Usage

### TOC

* <a href='#configuration'>Configuration</a>
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

### Configuration
There're two configuration files to note:
<ol>
<li>`./SEOstats/Config/ApiKeys.php`<br>
<em>Client API Keys (currently required for Google's Pagespeed Service only).</em>
</li>
<li>`./SEOstats/Config/DefaultSettings.php`<br>
<em>Some default settings for querying data (mainly locale related stuff).</em>
</li>
</ol>
<hr>

### Brief Example of Use
To use the SEOstats methods, you must include the Autoloader (`./SEOstats/bootstrap.php`) first.

Now, you can create a new SEOstats instance an bind any URL to the instance for further use with any child class.

```php
<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

use \SEOstats\Services as SEOstats;

try {
  $url = 'http://www.google.com/';

  // Create a new SEOstats instance.
  $seostats = new \SEOstats\SEOstats;

  // Bind the URL to the current SEOstats instance.
  if ($seostats->setUrl($url)) {

	echo SEOstats\Alexa::getGlobalRank();
	echo SEOstats\Google::getPageRank();
  }
}
catch (SEOstatsException $e) {
  die($e->getMessage());
}
```

Alternatively, you can call all methods statically passing the URL to the methods directly.

```php
<?php

require_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

try {
  $url = 'http://www.google.com/';

  // Get the Google Toolbar Pagerank for the given URL.
  echo \SEOstats\Services\Google::getPageRank($url);
}
catch (SEOstatsException $e) {
  die($e->getMessage());
}
```

More detailed examples can be found in the `./example` directory.
<hr>

## SEOstats Alexa Methods

### Alexa Traffic Metrics
```php
<?php
  // Returns the global Alexa Traffic Rank (last 3 months).
  print Alexa::getGlobalRank();

  // Returns the global Traffic Rank for the last month.
  print Alexa::getMonthlyRank();

  // Returns the global Traffic Rank for the last week.
  print Alexa::getWeeklyRank();

  // Returns the global Traffic Rank for yesterday.
  print Alexa::getDailyRank();

  // Returns the country-specific Alexa Traffic Rank.
  print_r( Alexa::getCountryRank() );

  // Returns Alexa's backlink count for the given domain.
  print Alexa::getBacklinkCount();

  // Returns Alexa's page load time info for the given domain.
  print Alexa::getPageLoadTime();
```

### Alexa Traffic Graphs

```php
<?php
  // Returns HTML code for the 'daily traffic trend'-graph.
  print Alexa::getTrafficGraph(1);

  // Returns HTML code for the 'daily pageviews (percent)'-graph.
  print Alexa::getTrafficGraph(2);

  // Returns HTML code for the 'daily pageviews per user'-graph.
  print Alexa::getTrafficGraph(3);

  // Returns HTML code for the 'time on site (in minutes)'-graph.
  print Alexa::getTrafficGraph(4);

  // Returns HTML code for the 'bounce rate (percent)'-graph.
  print Alexa::getTrafficGraph(5);

  // Returns HTML code for the 'search visits'-graph, using specific graph dimensions of 320*240 px.
  print Alexa::getTrafficGraph(6, 0, 320, 240);
```
<hr>

## SEOstats Google Methods

### Google Toolbar PageRank

```php
<?php
  //  Returns the Google PageRank for the given URL.
  print Google::getPageRank();
```

### Google Pagespeed Service

```php
<?php
  // Returns the Google Pagespeed analysis' metrics for the given URL.
  print_r( Google::getPagespeedAnalysis() );

  // Returns the Google Pagespeed analysis' total score.
  print Google::getPagespeedScore();
```

### Google Websearch Index

```php
<?php
  // Returns the total amount of results for a Google site-search for the object URL.
  print Google::getSiteindexTotal();

  // Returns the total amount of results for a Google link-search for the object URL.
  print Google::getBacklinksTotal();

  // Returns the total amount of results for a Google search for 'keyword'.
  print Google::getSearchResultsTotal('keyword');
```

### Google SERP Details

```php
<?php
  // Returns an array of URLs and titles for the first 100 results for a Google web search for 'keyword'.
  print_r ( Google::getSerps('keyword') );

  // Returns an array of URLs and titles for the first 200 results for a Google site-search for $url.
  print_r ( Google::getSerps("site:$url", 200) );

  // Returns an array of URLs, titles and position in SERPS for occurrences of $url
  // within the first 1000 results for a Google web search for 'keyword'.
  print_r ( Google::getSerps('keyword', 1000, $url) );
```
<hr>

## SEOstats Open Site Explorer Methods

```php
<?php
  // Returns basic SEOmoz page metrics (Page-Authority, Domain Authority, Domain-Inlinks, total Inlinks).
  print_r ( OpenSiteExplorer::getPageMetrics() );
```
<hr>

## SEOstats SEMRush Methods

### SEMRush Domain Reports

```php
<?php
  // Returns an array containing the SEMRush main report (includes DomainRank, Traffic- & Ads-Data)
  print_r ( SEMRush::getDomainRank() );

  // Returns an array containing the domain rank history.
  print_r ( SEMRush::getDomainRankHistory() );

  // Returns an array containing data for competeing (auto-detected) websites.
  print_r ( SEMRush::getCompetitors() );

  // Returns an array containing data about organic search engine traffic, using explicitly SEMRush's german database.
  print_r ( SEMRush::getOrganicKeywords(0, 'de') );
```

### SEMRush Graphs

```php
<?php
  // Returns HTML code for the 'search engine traffic'-graph.
  print SEMRush::getDomainGraph(1);

  // Returns HTML code for the 'search engine traffic price'-graph.
  print SEMRush::getDomainGraph(2);

  // Returns HTML code for the 'number of adwords ads'-graph, using explicitly SEMRush's german database.
  print SEMRush::getDomainGraph(3, 0, 'de');

  // Returns HTML code for the 'adwords traffic'-graph, using explicitly SEMRush's german database and
  // specific graph dimensions of 320*240 px.
  print SEMRush::getDomainGraph(4, 0, 'de', 320, 240);

  // Returns HTML code for the 'adwords traffic price '-graph, using explicitly SEMRush's german database,
  // specific graph dimensions of 320*240 px and specific graph colors (black lines and red dots for data points).
  print SEMRush::getDomainGraph(5, 0, 'de', 320, 240, '000000', 'ff0000');
```
<hr>

## SEOstats Sistrix Methods

### Sistrix Visibility Index

```php
<?php
  // Returns the Sistrix visibility index
  // @link http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
  print Sistrix::getVisibilityIndex();
```
<hr>

## SEOstats Social Media Methods

### Google+ PlusOnes

```php
<?php
  // Returns integer PlusOne count
  print Social::getGooglePlusShares();
```

### Facebook Interactions

```php
<?php
  // Returns an array of total counts for overall Facebook interactions count, shares, likes, comments and clicks.
  print_r ( Social::getFacebookShares() );
```

### Twitter Mentions

```php
<?php
  // Returns integer tweet count for URL mentions
  print Social::getTwitterShares();
```

### Other Shares

```php
<?php
  // Returns the total count of URL shares via Delicious
  print Social::getDeliciousShares();

  // Returns array of top ten delicious tags for a URL
  print_r ( Social::getDeliciousTopTags() );

  // Returns the total count of URL shares via Digg
  print Social::getDiggShares();

  // Returns the total count of URL shares via LinkedIn
  print Social::getLinkedInShares();

  // Returns the total count of URL shares via Pinterest
  print Social::getPinterestShares();

  // Returns the total count of URL shares via StumbleUpon
  print Social::getStumbleUponShares();

  // Returns the total count of URL shares via VKontakte
  print Social::getVKontakteShares();
```
<hr>

## License

(c) 2010 - 2013, Stephan Schmitz eyecatchup@gmail.com
License: MIT, http://eyecatchup.mit-license.org
URL: https://github.com/eyecatchup/SEOstats
