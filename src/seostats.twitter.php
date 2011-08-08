<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Twitter
	 *  @package	class.seostats
	 *  @updated	2011/04/29
	 *  @author		Florent Cima <florentcm@gmail.com>
	 *  @copyright	2010-present, Florent Cima/Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Twitter extends SEOstats {

	/**
	 * Returns the total amount of twitter mentions for a single page 
	 *
	 * @access		private
	 * @return		integer 				Returns the total of twitter mentions for a single page.
	 */		
	function getTweetCount($url) { 
		$url = urlencode($url); 
		$twitterEndpoint = "http://urls.api.twitter.com/1/urls/count.json?url=%s"; 
		$fileData = file_get_contents(sprintf($twitterEndpoint, $url)); $json = json_decode($fileData, true); 
		unset($fileData); // free memory 
		//print_r($json); 
		return $json['count']; 
	}
	
	/**
	 * Returns the total amount of twitter mentions for entire domain 
	 *
	 * @access		private
	 * @return		integer 				Returns the total of twitter mentions for the entire domain.
	 */		
	public static function backtweets($uri)
	{
		
		$tmp = SEOstats::cURL( 'http://backtweets.com/search?q='.$uri );
		$dom = new DOMDocument();
		@$dom->loadHTML($tmp);
		$xpath = new DOMXPath($dom);

		$p = $xpath->query('//div/ol/li');
		
		$r = $p->item(2)->textContent;
		$r = str_replace(',', '', $r);
		$r = str_replace('Results', '', $r);
		$r = str_replace('Result', '', $r);
		$r = trim($r);
		return ($r != '') ? $r : intval('0');
	}
	
}
?>