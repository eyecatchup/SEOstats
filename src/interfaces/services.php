<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats provider list
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/05/30
 */

interface services
{
	const PROVIDER = '["alexa","bing","facebook","google","semrush","seomoz","twitter","yahoo"]';
	const GOOGLE_APISEARCH_URL = 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=%s&q=%s';
	const GOOGLE_PAGESPEED_URL = 'https://developers.google.com/_apps/pagespeed/run_pagespeed?url=%s&format=json';
}

/* End of file services.php */
/* Location: ./src/interfaces/services.php */
