<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Alexa
	 *  @package	class.seostats
	 *  @updated	2011/06/11
	 *  @author		Stephan Schmitz <eyecatchup@gmail.com>
	 *  @copyright	2010-present, Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Alexa extends SEOstats {

	/**
	 * Helper. Get the Alexa's free report webpage.
	 *
	 * @access		private
	 * @return 		string 					String, containing the curl result of the the Alexa webpage.
	 */
	private static function _alexa($uri)
	{
		$tmp = SEOstats::cURL('http://www.alexa.com/siteinfo/'.$uri);

		return $tmp;
	}
	
	/**
	 * Extracts a String off of HTML input.
	 *
	 * @access		private
	 *
	 * @param 		string 		$tag 		String, containing the html tag to select
	 * @param 		string 		$attr 		String, containing the attribute used on the WHERE condition
	 * @param 		string 		$value 		String, containing the value used on the WHERE condition
	 * @param 		string 		$site 		Input string
	 * @param 		integer 	$id 		Defines the position of the key, to be returned from the preg_match_all array.
	 *
	 * @return 		string/integer 			Returns an integer or a string.
	 */
	public static function extractSingle($tag,$attr,$value,$id,$uri,$alextract)
	{
		// external helper class
		include_once("ext/htmlsql.class.php");

		$wsql = new htmlsql();
		$wsql->connect('string', SEOstats_Alexa::_alexa($uri));
		$wsql->query('SELECT * FROM '.$tag.' WHERE $'.$attr.' == "'.$value.'"');
		$row = $wsql->fetch_array();
		
		if($alextract == true)
		{
			return SEOstats_Alexa::alextract($row[$id]['text']);
		}
		elseif($alextract == false)
		{
			return trim(strip_tags($row[$id]['text']));
		}
		elseif($alextract == 'none')
		{
			return $row[$id]['text'];
		}
	}
	
	/**
	 * Extracts an Array off of HTML input.
	 *
	 * @access		private
	 *
	 * @param 		string 		$tag 		String, containing the html tag to select
	 * @param 		string 		$attr 		String, containing the attribute used on the WHERE condition
	 * @param 		string 		$value 		String, containing the value used on the WHERE condition
	 * @param 		string 		$site 		Input string
	 *
	 * @return 		array 					Returns an array of results.
	 */
	public static function extractMulti($tag,$attr,$value,$uri)
	{
		// external helper classs
		include_once("ext/htmlsql.class.php");

		$wsql = new htmlsql();
		$wsql->connect('string', SEOstats_Alexa::_alexa($uri));
		$wsql->query('SELECT * FROM '.$tag.' WHERE $'.$attr.' == "'.$value.'"');

		$results = array ();
		foreach($wsql->fetch_array() as $row)
		{
			$results[] = $row['text'];
		}
		return $results;
	}
		
	/**
	 * Gets data off of curled Alexa webpages.
	 *
	 * @access		private
	 * @param 		string 		$str 		String, containing the html source code of Alexa's free result webpage.
	 * @return 		array 					Returns an array of Alexa results.
	 */
    public static function alextract($str)
	{
		$tmpArr = explode('</td>',$str);
		$replace = array("\r", "\r\n", "\n", "7 day", "Yesterday", "1 month", "3 month");
		$value1 = trim(str_replace($replace,'',strip_tags($tmpArr[3])));
		if ($value1 == '-' || $value1 == '' || empty($value1))
		{
			$value1 = 'No Data available.';
			$change1 = '';
		}
		else
		{
			$change1 = trim(strip_tags($tmpArr[4]));
		}
		$value3 = trim(str_replace($replace,'',strip_tags($tmpArr[6])));
		if ($value3 == '-' || $value3 == '' || empty($value3))
		{
			$value3 = 'No Data available.';
			$change3 = '';
		}
		else
		{
			$change3 = trim(strip_tags($tmpArr[7]));
		}
		
		$month1 = array('value' => $value1, 'change' => $change1);
		$month3 = array('value' => $value3, 'change' => $change3);
		$result = array('1 Month' => $month1,'3 Months' => $month3);
		
		return $result;
    }

	/**
	 * @access		public
	 * @return		array					Returns multi-array, containing the Visits by Country.
	 */	
    public static function Alexa_VBC($uri)
	{
		$str = SEOstats_Alexa::_alexa($uri);
		
		preg_match_all('#<img class="dynamic-icon" src="/images/flags/(.*?).png" alt="(.*?)"/>(.*?)</a>#',$str,$country);
		preg_match_all('#<p class="tc1" style="width:30%; text-align:right;">(.*?)</p>#',$str,$percent);
		$result = array();
		$countries = array();
		foreach($country[3] as $tmp)
		{
			$x = trim(str_replace('&nbsp;','',$tmp));
			if(!in_array($x,$countries))
			{
				$countries[] = $x;
			}
		}
		for($i=0;$i < sizeof($countries);$i++)
		{
			$result[] = array('Country' => trim($countries[$i]), 'Percent of Traffic' => $percent[1][$i]);
		}
		return $result;
    }
	
	/**
	 * @access		public
	 * @return		array					Returns multi-array, containing the Alexa Rank, sorted by Country.
	 */	
    public static function Alexa_RBC($uri)
	{
		$str = SEOstats_Alexa::_alexa($uri);
		
		preg_match_all('#&nbsp;(.*?)</a>#',$str,$country);
		preg_match_all('#<p class="tc1" style="width:40%; text-align:right;">(.*?)</p>#',$str,$rank);
		$result = array();
		for($i=0;$i < sizeof($country);$i++)
		{
			$result[] = array('Country' => trim($country[1][$i]), 'Rank' => trim($rank[1][$i]));
		}
		return $result;
    }
	
	/**
	 * @access		public
	 * @return		array					Returns multi-array, containing data from Alexa about keywords from search visits.
	 */	
    public static function Alexa_SV_Keywords($uri)
	{
		$tmp = SEOstats_Alexa::extractSingle('div','id','top-keywords-from-search','0',$uri,'none');
		$tmp = explode('style="color:#253759;">', $tmp);
		$tmp = explode('<tr>', $tmp[2]);
		$result = array();
		for ($i=1;$i<sizeof($tmp);$i++)
		{
			$temp = explode('</td>',$tmp[$i]);
			$result[] = array(
							'Keyword' => utf8_decode(trim(strip_tags($temp[1]))), 
							'Percent of Search Traffic' => trim(strip_tags($temp[2])));
		}
		return $result;
    }
	
	/**
	 * @access		public
	 * @return		array					Returns multi-array, containing data from Alexa about changes of incoming search terms.
	 */	
    public static function Alexa_SV_Changes($uri)
	{
		$tmp = SEOstats_Alexa::extractMulti('table','class','dataTable',$uri);
		$tmp_incr = explode('<tr>', $tmp[9]);
		$tmp_decl = explode('<tr>', $tmp[11]);
		
		for ($i=1;$i<sizeof($tmp_incr);$i++)
		{
			$temp_incr = explode('</td>',$tmp_incr[$i]);
			$result_incr[] =  array(
								'Keyword' => utf8_decode(trim(strip_tags($temp_incr[1]))),
								'Change in Percent' => trim(strip_tags($temp_incr[2])));
		}
		for ($i=1;$i<sizeof($tmp_decl);$i++)
		{
			$temp_decl = explode('</td>',$tmp_decl[$i]);
			$result_decl[] = array(
								'Keyword' => utf8_decode(trim(strip_tags($temp_decl[1]))),
								'Change in Percent' => trim(strip_tags($temp_decl[2])));
		}		
		if(empty($result_incr)) { $result_incr = 'No Data available.'; }
		if(empty($result_decl)) { $result_decl = 'No Data available.'; }
		$result = array('Increase' => $result_incr, 'Decline' => $result_decl);
		
		return $result;
    }

	/**
	 * @access		public
	 * @return		string					Returns string, containing the average load time of the URL from Alexa.
	 */		
	public static function Alexa_Load_Time($uri)
	{	
		$str = SEOstats_Alexa::_alexa($uri);	
		$html = new DOMDocument();
		@$html->loadHtml( $str );

		$xpath = new DOMXPath( $html );
		$p = $xpath->query( "//div[@class='speedAd']//div//p" );
		foreach($p as $match)
		{
			if(preg_match('/Seconds/si',$match->textContent))
			{
				return trim(strip_tags($match->textContent));
				exit();
			}
		}
		return 'No data available.';
	}
}
?>
