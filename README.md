# SEOstats: SEO data library for PHP

SEOstats is a powerful open source tool to get a lot of SEO relevant data such as detailed backlink analyses, keyword and traffic statistics, website trends, page authority, the Google Pagerank, the Alexa Trafficrank and much more. 

SEOstats offers over 50 different methods to request SEO relevant data for websites and gathers data from Google, Yahoo, Bing, SEMRush, SEOmoz, Alexa, Facebook and Twitter.

## Download

The latest stable version can be downloaded from the downloads tab, or using the following link:   
https://github.com/downloads/eyecatchup/SEOstats/SEOstats-2.1.0.zip

## Usage

### Example Usage
```php
<?php
try 
{
  $url = new SEOstats("http://www.domain.com/");
  print $url->Google_Page_Rank();
} 
catch (SEOstatsException $e) 
{
  die($e->getMessage());
}
```
