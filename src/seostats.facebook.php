<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Facebook
	 *  @package	class.seostats
	 *  @updated	2011/04/29
	 *  @author		Florent Cima <florentcm@gmail.com>
	 *  @copyright	2011-present, Florent Cima/Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Facebook extends SEOstats {

	/**
	 * Returns the total amount of pages for a Domain indexed at Yahoo!
	 *
	 * @access		private
	 * @link 		https://graph.facebook.com/
	 * @return		integer 				Returns the total amount of Facebook Shares for a single page
	 */		
	public static function getFacebookShares($q)
	{
		$url = 'https://graph.facebook.com/?ids=';
	
	    // Parameters
		$url .= urlencode($q);
		
		//Execution and result of Json in $str
		$str  = SEOstats::cURL($url);
		
		//Decode Json object
		$data = json_decode($str);
		
		//Return only number of facebook shares
		$r = $data->$q->shares; 
		return ($r != NULL) ? $r : intval('0');
	}
}
?>