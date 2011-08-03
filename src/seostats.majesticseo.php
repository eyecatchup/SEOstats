<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Majesticseo
	 *  @package	class.seostats
	 *  @updated	2011/06/11
	 *  @author		Stephan Schmitz <eyecatchup@gmail.com>
	 *  @copyright	2010-present, Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Majesticseo extends SEOstats {

	/**
	 * Helper. Gets the Majesticseo's free report webpage.
	 *
	 * @access		private
	 * @return 		string 					String, containing the curl result of the the Majesticseo webpage.
	 */
	public static function report($uri, $i)
	{
		$tmp = SEOstats::cURL( 'http://www.majesticseo.com/reports/site-explorer/summary/'.
			str_replace('http://', '', $uri) );

		$dom = new DOMDocument();
		@$dom->loadHTML($tmp);
		$xpath = new DOMXPath($dom);

		$p = $xpath->query("//table//tr//td//p");

		if($i==1 || $i==3)
		{
			$r = trim($p->item($i)->textContent);
		} 
		else
		{
			switch($i)
			{
					case 4: $regex = ' Referring IP addresses'; break;
					case 5: $regex = ' are Class C subnets'; break;
					case 6: $regex = ' Indexed URLs'; break;
					default:break;
			}
			foreach ( $p as $paragraph )
			{
				if(preg_match('#'.$regex.'#i',$paragraph->textContent))
				{
					$r = str_replace($regex,'',$paragraph->textContent);
				}
			}
		}
		return ($r != '') ? $r : intval('0');
	}
}
?>
