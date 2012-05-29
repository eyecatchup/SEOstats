<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Yahoo
	 *  @package	class.seostats
	 *  @updated	2011/04/29
	 *  @author		Stephan Schmitz <eyecatchup@gmail.com>
	 *  @copyright	2010-present, Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Yahoo extends SEOstats {

	/**
	 * Returns the total amount of pages for a Domain indexed at Yahoo!
	 *
	 * @access		private
	 * @link 		http://developer.yahoo.com/search/siteexplorer/		Get your own application ID here
	 * @return		integer 				Returns the total amount of pages for a Domain indexed at Yahoo!
	 */		
	public static function yahooSiteindexTotal($uri)
	{
		$url  = 'http://search.yahooapis.com/SiteExplorerService/V1/pageData?appid=';
		$url .= YAHOO_APP_ID;
		$url .= '&results=1&output=json&query=http://'.urlencode($uri);
		$str  = SEOstats::cURL($url);

		$data = json_decode($str);

		return $data->ResultSet->totalResultsAvailable;   
	}

	/**
	 * Returns array, containing details about the pages indexed at Yahoo!
	 *
	 * @access		private
	 * @return		array 			Returns array, containing details about the pages indexed at Yahoo!
	 */		
	public static function yahooSiteindexArray($uri)
	{
		$tsv_url  = 'http://siteexplorer.search.yahoo.com/de/export;_ylt=?p='.
			urlencode($uri).'&bwmf=s&fr=sfp&fr2=seo-rd-se';

		$result = array();
		foreach ( file($tsv_url) as $line )
		{
			$tmp = explode("\t", $line);
			if ( isset($tmp[1]) && !empty($tmp[1]) && $tmp[1] != "URL" )
			{
				$result[] = array(
					'Title' => utf8_decode($tmp[0]),
					'URL' => $tmp[1]
				);
			}
		}
		return $result; 
	}
	
	/**
	 * Returns the total amount of Backlinks indexed at Yahoo!
	 *
	 * @access		private
	 * @link 		http://developer.yahoo.com/search/siteexplorer/		Get your own application ID here
	 * @return		integer 				Returns the total amount of backlinks indexed at Yahoo!
	 */		
	public static function yahooBacklinksTotal($uri)
	{
		$url  = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
		$url .= YAHOO_APP_ID;
		$url .= '&results=1&output=json&query=http://'.urlencode($uri);
		$str  = SEOstats::cURL($url);

		$data = json_decode($str);

		return $data->ResultSet->totalResultsAvailable;   
	}
	
	/**
	 * Returns an array containing details about the backlinks indexed at Yahoo!
	 *
	 * @access		private
	 * @return		array 			Returns an array containing details about the backlinks indexed at Yahoo!
	 */		
	public static function yahooBacklinksArray($uri)
	{
		$tsv_url  = 'http://siteexplorer.search.yahoo.com/de/export;_ylt=?p='.
			urlencode($uri).'&bwm=i&bwmf=s&fr=sfp&fr2=seo-rd-se';

		$result = array();
		foreach ( file($tsv_url) as $line )
		{
			$tmp = explode("\t", $line);
			if ( isset($tmp[1]) && !empty($tmp[1]) && $tmp[1] != "URL" )
			{
				$result[] = array(
					'URL' => $tmp[1],
					'Anchortext' => utf8_decode($tmp[0])
				);
			}
		}
		return $result;   
	}
}
?>
