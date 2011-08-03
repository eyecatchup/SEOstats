<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @package	class.seostats
	 *  @updated	2011/04/29
	 *  @author		Stephan Schmitz <eyecatchup@gmail.com>
	 *  @copyright	2010-present, Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 *
	 *  EXCEPTION
	 */

class SEOstatsException extends Exception {}	 
	 
if (!function_exists('curl_init'))
{
	throw new SEOstatsException('SEOstats needs the PHP CURL extension.');
}
if (!function_exists('json_decode'))
{
	throw new SEOstatsException('SEOstats needs the PHP JSON extension.');
}
?>