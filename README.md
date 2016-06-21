# SEOstats: SEO metrics library for PHP

[![License](https://poser.pugx.org/seostats/seostats/license "SEOstats is licensed under the M.I.T.")](https://github.com/eyecatchup/SEOstats) [![Scrutinizer Code Quality Rating](https://scrutinizer-ci.com/g/eyecatchup/SEOstats/badges/quality-score.png?b=master "Scrutinizer Code Quality Rating: Very Good!")](https://scrutinizer-ci.com/g/eyecatchup/SEOstats/ "'Very good' code quality, says Scrutinizer C.I.") [![Build Status](https://travis-ci.org/eyecatchup/SEOstats.svg?branch=master "Travis C.I. Build Status")](https://travis-ci.org/eyecatchup/SEOstats) [![Scrutinizer Test Coverage Report](https://scrutinizer-ci.com/g/eyecatchup/SEOstats/badges/coverage.png?b=master "Scrutinizer Test Coverage Report")](https://scrutinizer-ci.com/g/eyecatchup/SEOstats/code-structure/master?elementType=class&orderField=test_coverage&order=desc&changesExpanded=0 "Test coverage report by Scrutinizer C.I.") [![Latest Stable Version](https://poser.pugx.org/seostats/seostats/v/stable "Latest Stable Version")](https://packagist.org/packages/seostats/seostats) [![Latest Unstable Version](https://poser.pugx.org/seostats/seostats/v/unstable "Latest Unstable Version")](https://packagist.org/packages/seostats/seostats) [![Monthly Downloads](https://poser.pugx.org/seostats/seostats/d/monthly "That many people downloaded SEOstats from Github or Packagist this month.")](https://packagist.org/packages/seostats/seostats)

SEOstats is _the_ open source PHP library to get SEO-relevant website metrics. 

SEOstats is used to gather metrics such as detailed searchindex & backlink data, keyword & traffic statistics, website trends, page authority, social visibility, Google Pagerank, Alexa Trafficrank and more. 

It offers over 50 different methods to fetch data from sources like Alexa, Google, Mozscape (by Moz - f.k.a. Seomoz), SEMRush, Open-Site-Explorer, Sistrix, Facebook or Twitter.

A variety of *(private as well as enterprise)* SEO tools have been built using SEOstats.

## Dependencies

SEOstats requires PHP version 5.3 or greater and the PHP5-CURL and PHP5-JSON extensions.

## Installation

The recommended way to install SEOstats is [through composer](http://getcomposer.org).
To install SEOstats, just create the following `composer.json` file

    {
        "require": {
            "seostats/seostats": "dev-master"
        }
    }
and run the `php composer.phar install` (Windows: `composer install`) command in path of the `composer.json`.  

#### Step-by-step example:

If you haven't installed composer yet, here's the easiest way to do so:
```
# Download the composer installer and execute it with PHP:
user@host:~/> curl -sS https://getcomposer.org/installer | php

# Copy composer.phar to where your local executables live:
user@host:~/> mv /path/given/by/composer-installer/composer.phar /usr/local/bin/composer.phar

# Alternatively: For ease of use, you can add an alias to your bash profile:
# (Note, you need to re-login your terminal for the change to take effect.)
user@host:~/> echo 'alias composer="php /usr/local/bin/composer.phar"' >> ~/.profile
```
<hr>
If you have installed composer, follow these steps to install SEOstats:
```
# Create a new directory and cd into it:
user@host:~/> mkdir /path/to/seostats && cd /path/to/seostats

# Create the composer.json for SEOstats:
user@host:/path/to/seostats> echo '{"require":{"seostats/seostats":"dev-master"}}' > composer.json

# Run the install command:
user@host:/path/to/seostats> composer install
Loading composer repositories with package information
Installing dependencies (including require-dev)
  - Installing seostats/seostats (dev-master 4c192e4)
    Cloning 4c192e43256c95741cf85d23ea2a0d59a77b7a9a

Writing lock file
Generating autoload files

# You're done. For a quick start, you can now 
# copy the example files to the install directory:
user@host:/path/to/seostats> cp ./vendor/seostats/seostats/example/*.php  ./

# Your SEOstats install directory should look like this now:
user@host:/path/to/seostats> ls -1
composer.json
composer.lock
get-alexa-graphs.php
get-alexa-metrics.php
get-google-pagerank.php
get-google-pagespeed-analysis.php
get-google-serps.php
get-opensiteexplorer-metrics.php
get-semrush-graphs.php
get-semrush-metrics.php
get-sistrix-visibilityindex.php
get-social-metrics.php
vendor
```
<hr>
#### Use SEOstats without composer

If composer is no option for you, you can still just download the [`SEOstats.zip`](https://github.com/eyecatchup/SEOstats/archive/master.zip) file of the current master branch (version 2.5.2) and extract it. However, currently [there is an issues with autoloading](https://github.com/eyecatchup/SEOstats/issues/49) and you need to follow the instructions in the comments in the example files in order to use SEOstats (or download zip for the development version of SEOstats (2.5.3) [here](https://github.com/eyecatchup/SEOstats/archive/dev-253.zip)).

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
* <a href='#seostats-mozscape-methods'>Mozscape Methods</a>  
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
<em>Client API Keys (currently only required for Mozscape, Google's Pagespeed Service and Sistrix).</em>
</li>
<li>`./SEOstats/Config/DefaultSettings.php`<br>
<em>Some default settings for querying data (mainly locale related stuff).</em>
</li>
</ol>
<hr>

### Brief Example of Use
To use the SEOstats methods, you must include one of the Autoloader classes first (For composer installs: `./vendor/autoload.php`; for zip download: `./SEOstats/bootstrap.php`).

Now, you can create a new SEOstats instance an bind any URL to the instance for further use with any child class.

```php
<?php
// Depending on how you installed SEOstats
#require_once __DIR__ . DIRECTORY_SEPARATOR . 'SEOstats' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

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
// Depending on how you installed SEOstats
#require_once __DIR__ . DIRECTORY_SEPARATOR . 'SEOstats' . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

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

## SEOstats Mozscape Methods

```php
<?php
  // The normalized 10-point MozRank score of the URL. 
  print Mozscape::getMozRank();
  
  // The raw MozRank score of the URL.
  print Mozscape::getMozRankRaw();
  
  // The number of links (equity or nonequity or not, internal or external) to the URL.
  print Mozscape::getLinkCount();
  
  // The number of external equity links to the URL (http://apiwiki.moz.com/glossary#equity).
  print Mozscape::getEquityLinkCount();
  
  // A normalized 100-point score representing the likelihood
  // of the URL to rank well in search engine results.  
  print Mozscape::getPageAuthority();
  
  // A normalized 100-point score representing the likelihood
  // of the root domain of the URL to rank well in search engine results.
  print Mozscape::getDomainAuthority();
```
<hr>

## SEOstats Open Site Explorer (by MOZ) Methods

```php
<?php
  // Returns several metrics from Open Site Explorer (by MOZ)
  $ose = OpenSiteExplorer::getPageMetrics();

  // MOZ Domain-Authority Rank - Predicts this domain's ranking potential in the search engines 
  // based on an algorithmic combination of all link metrics.
  print "Domain-Authority:         " .
        $ose->domainAuthority->result . ' (' .      // Int - e.g 42
        $ose->domainAuthority->unit   . ') - ' .    // String - "/100"
        $ose->domainAuthority->descr  . PHP_EOL;    // String - Result value description

  // MOZ Page-Authority Rank - Predicts this page's ranking potential in the search engines 
  // based on an algorithmic combination of all link metrics.
  print "Page-Authority:           " .
        $ose->pageAuthority->result . ' (' .        // Int - e.g 48
        $ose->pageAuthority->unit   . ') - ' .      // String - "/100"
        $ose->pageAuthority->descr  . PHP_EOL;      // String - Result value description

  // Just-Discovered Inbound Links - Number of links to this page found over the past %n days, 
  // indexed within an hour of being shared on Twitter.
  print "Just-Discovered Links:    " .
        $ose->justDiscovered->result . ' (' .       // Int - e.g 140
        $ose->justDiscovered->unit   . ') - ' .     // String - e.g "32 days"
        $ose->justDiscovered->descr  . PHP_EOL;     // String - Result value description

  // Root-Domain Inbound Links - Number of unique root domains (e.g., *.example.com) 
  // containing at least one linking page to this URL.
  print "Linking Root Domains:     " .
        $ose->linkingRootDomains->result . ' (' .   // Int - e.g 210
        $ose->linkingRootDomains->unit   . ') - ' . // String - "Root Domains"
        $ose->linkingRootDomains->descr  . PHP_EOL; // String - Result value description

  // Total Links - All links to this page including internal, external, followed, and nofollowed.
  print "Total Links:              " .
        $ose->totalLinks->result . ' (' .           // Int - e.g 31571
        $ose->totalLinks->unit   . ') - ' .         // String - "Total Links"
        $ose->totalLinks->descr  . PHP_EOL;         // String - Result value description
```
<hr>

## SEOstats SEMRush Methods

### SEMRush Domain Reports

```php
<?php
  // Returns an array containing the SEMRush main report (includes DomainRank, Traffic- & Ads-Data)
  print_r ( SemRush::getDomainRank() );

  // Returns an array containing the domain rank history.
  print_r ( SemRush::getDomainRankHistory() );

  // Returns an array containing data for competeing (auto-detected) websites.
  print_r ( SemRush::getCompetitors() );

  // Returns an array containing data about organic search engine traffic, using explicitly SEMRush's german database.
  print_r ( SemRush::getOrganicKeywords(0, 'de') );
```

### SEMRush Graphs

```php
<?php
  // Returns HTML code for the 'search engine traffic'-graph.
  print SemRush::getDomainGraph(1);

  // Returns HTML code for the 'search engine traffic price'-graph.
  print SemRush::getDomainGraph(2);

  // Returns HTML code for the 'number of adwords ads'-graph, using explicitly SEMRush's german database.
  print SemRush::getDomainGraph(3, 0, 'de');

  // Returns HTML code for the 'adwords traffic'-graph, using explicitly SEMRush's german database and
  // specific graph dimensions of 320*240 px.
  print SemRush::getDomainGraph(4, 0, 'de', 320, 240);

  // Returns HTML code for the 'adwords traffic price '-graph, using explicitly SEMRush's german database,
  // specific graph dimensions of 320*240 px and specific graph colors (black lines and red dots for data points).
  print SemRush::getDomainGraph(5, 0, 'de', 320, 240, '000000', 'ff0000');
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

  // Returns shares, comments, clicks and reach for the given URL via Xing
  print_r( Social::getXingShares() );

  // Returns the total count of URL shares via Pinterest
  print Social::getPinterestShares();

  // Returns the total count of URL shares via StumbleUpon
  print Social::getStumbleUponShares();

  // Returns the total count of URL shares via VKontakte
  print Social::getVKontakteShares();
```
<hr>

## License

(c) 2010 - 2016, Stephan Schmitz eyecatchup@gmail.com   
License: MIT, http://eyecatchup.mit-license.org  
URL: https://eyecatchup.github.io  
