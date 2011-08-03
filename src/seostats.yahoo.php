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
	 * @link 		http://developer.yahoo.com/search/siteexplorer/		Get your own application ID here
	 * @return		array 					Returns array, containing details about the pages indexed at Yahoo!
	 */		
	public static function yahooSiteindexArray($uri)
	{
		$url  = 'http://search.yahooapis.com/SiteExplorerService/V1/pageData?appid=';
		$url .= YAHOO_APP_ID;
		$url .= '&results=100&output=json&query=http://'.urlencode($uri);
		$str  = SEOstats::cURL($url);

		$data = json_decode($str);

		$result = array();
		for($i=0;$i<sizeof($data->ResultSet->Result);$i++)
		{
			$result[] =  array( 'Title' => utf8_decode($data->ResultSet->Result[$i]->Title),
								  'URL' => $data->ResultSet->Result[$i]->Url,
							'Click URL' => $data->ResultSet->Result[$i]->ClickUrl);
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
	 * @link 		http://developer.yahoo.com/search/siteexplorer/		Get your own application ID here
	 * @return		array 					Returns an array containing details about the backlinks indexed at Yahoo!
	 */		
	public static function yahooBacklinksArray($uri)
	{
		$url  = 'http://search.yahooapis.com/SiteExplorerService/V1/inlinkData?appid=';
		$url .= YAHOO_APP_ID;
		$url .= '&results=100&output=json&query=http://'.urlencode($uri);
		$str  = SEOstats::cURL($url);

		$data = json_decode($str);

		$result = array();
		for($i=0;$i<sizeof($data->ResultSet->Result);$i++)
		{
			$result[] = array('URL' => $data->ResultSet->Result[$i]->Url,
					   'Anchortext' => utf8_decode($data->ResultSet->Result[$i]->Title));
		}
		return $result;   
	}
}
?>
