<?php  if ( ! defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats Exception Class
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/05/13
 */

class SEOstatsException extends Exception {}

if (!function_exists('curl_init'))
{
    throw new SEOstatsException('SEOstats requires the PHP CURL extension.');
}
if (!function_exists('json_decode'))
{
    throw new SEOstatsException('SEOstats requires the PHP JSON extension.');
}

/* End of file seostats.exception.php */
/* Location: ./src/helper/seostats.exception.php */