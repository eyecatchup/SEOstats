<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats provider list
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/15
 */

interface services
{
	const PROVIDER = '["alexa","bing","google","semrush","seomoz","social","yahoo"]';
	
	// Url to get Google search total counts from
	const GOOGLE_APISEARCH_URL = 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=%s&q=%s';
	
	// Url to get the Pagespeed analysis from
	const GOOGLE_PAGESPEED_URL = 'https://developers.google.com/_apps/pagespeed/run_pagespeed?url=%s&format=json';
	
	// Url to get the Plus One count from
	const GOOGLE_PLUSONE_URL = 'https://plusone.google.com/u/0/_/+1/fastbutton?count=true&url=%s';
	
	// Url to get Facebook link stats from
	const FB_LINKSTATS_URL = 'https://api.facebook.com/method/fql.query?query=%s&format=json';
	
	// Url to get Twitter mentions from
	// @link https://dev.twitter.com/discussions/5653#comment-11514
	const TWEETCOUNT_URL = 'http://cdn.api.twitter.com/1/urls/count.json?url=%s';
}

/* End of file services.php */
/* Location: ./src/interfaces/services.php */