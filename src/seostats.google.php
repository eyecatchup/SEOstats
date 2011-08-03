<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Google
	 *  @package	class.seostats
	 *  @updated	2011/06/11
	 *  @author		Stephan Schmitz <eyecatchup@gmail.com>
	 *  @copyright	2010-present, Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Google extends SEOstats {

	/**
	 * Returns total amount of results for any Google search,
	 * requesting the deprecated Websearch API.
	 *
	 * @access		private
	 * @param		string		$query		String, containing the search query.
	 * @param		string		$tld		String, containing the desired Google top level domain.
	 * @return		integer					Returns a total count.
	 */
	public static function googleTotal2($query)
	{
		$url = 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=1&q='.$query;
		$str = SEOstats::cURL($url);
		$data= json_decode($str);
		
		return intval($data->responseData->cursor->estimatedResultCount);
	}

	/**
	 * Returns total amount of results for any Google search.
	 *
	 * @access		private
	 * @param		string		$query		String, containing the search query.
	 * @param		string		$tld		String, containing the desired Google top level domain.
	 * @return		integer					Returns a total count.
	 */
	public static function googleTotal($query)
	{
		$url = 'http://www.google.'. GOOGLE_TLD .'/custom?num=1&q='.$query;
		$str = SEOstats::cURL($url);
		preg_match_all('#<b>(.*?)</b>#',$str,$matches);
		$result = (!empty($matches[1][2])) ? $matches[1][2] : '0';
		
		return $result;
	}
	
	/**
	 * Returns array, containing detailed results for any Google search.
	 *
	 * @access		private
	 * @param		string		$query		String, containing the search query.
	 * @param		string		$tld		String, containing the desired Google top level domain.
	 * @return		array 					Returns array, containing the keys 'URL', 'Title' and 'Description'.
	 */
	public static function googleArray($query)
	{
		$result = array ();
		$pages = 1;
		$delay = 0;
		for($start=0;$start<$pages;$start++)
		{
			$url = 'http://www.google.'. GOOGLE_TLD .'/custom?q='.$query.'&filter=0'.
				   '&num=100'.(($start == 0) ? '' : '&start='.$start.'00');
			$str = SEOstats::cURL($url);			
			if (preg_match("#answer=86640#i", $str))
			{
				$e = 'Please read: http://www.google.com/support/websearch/' .
					 'bin/answer.py?&answer=86640&hl=en';
				throw new SEOstatsException($e);
			}
			else
			{
				$html = new DOMDocument();
				@$html->loadHtml( $str );

				$xpath = new DOMXPath( $html );
				$links = $xpath->query( "//div[@class='g']//a" );
				$descs = $xpath->query( "//td[@class='j']//div[@class='std']" );
				$i = 0;
				foreach ( $links as $link )
				{
					if(!preg_match('#cache#si',$link->textContent) && 
					   !preg_match('#similar#si',$link->textContent))
					{
						$result []= array(
							'url' => $link->getAttribute('href'), 
							'title' => utf8_decode($link->textContent), 
							'descr' => utf8_decode($descs->item($i)->textContent)
						);
						$i++; 
					}
				}					
				if ( preg_match('#<div id="nn"><\/div>#i', $str) ||
					 preg_match('#<div id=nn><\/div>#i', $str))
				{
					$pages += 1;
					$delay += 200000;
					usleep($delay);
				}
				else
				{
					$pages -= 1;
				}
			}
		}
		return $result;
	}
	
	public static function performanceAnalysis($uri)
	{
		$url  = 'http://pagespeed.googlelabs.com/run_pagespeed?url='.$uri.'&format=json';
		$str  = SEOstats::cURL($url);

		return json_decode($str);
	}

	public static function pageSpeedScore($uri)
	{
		$url  = 'http://pagespeed.googlelabs.com/run_pagespeed?url='.$uri.'&format=json';
		$str  = SEOstats::cURL($url);
		
		$data = json_decode($str);
		return intval($data->results->score);
	}
	
	/**
	 * Gets the 'GPR_awesomeHash' of the object URL.
	 *
	 * @access		private
	 * @param 		string 		$url 		String, containing the URL to hash.
	 * @return 		string 					Returns hash.
	 */
    public static function genhash ($url)
	{
		$hash = 'Mining PageRank is AGAINST GOOGLE\'S TERMS OF SERVICE. Yes, I\'m talking to you, scammer.';
		$c = 16909125;
		$length = strlen($url);
		$hashpieces = str_split($hash);
		$urlpieces = str_split($url);
		for ($d = 0; $d < $length; $d++)
		{
			$c = $c ^ (ord($hashpieces[$d]) ^ ord($urlpieces[$d]));
			$c = self::zerofill($c, 23) | $c << 9;
		}
		return '8' . self::hexencode($c);
	}

	/**
	 * @return 		integer
	 */
	public static function zerofill($a,$b)
	{
		$z = hexdec(80000000);
		if ($z & $a)
		{
			$a  = ($a>>1);
			$a &= (~$z);
			$a |= 0x40000000;
			$a  = ($a>>($b-1));
		}
		else
		{
			$a = ($a>>$b);
		}
		return $a;
	}

	/**
	 * @return		string
	 */
	public static function hexencode($str)
	{
		$out  = self::hex8(self::zerofill($str, 24));
		$out .= self::hex8(self::zerofill($str, 16) & 255);
		$out .= self::hex8(self::zerofill($str, 8 ) & 255);
		$out .= self::hex8($str & 255);

		return $out;
	}
	
	/**
	 * @return 		integer
	 */	
    public static function hex8 ($str)
	{
		$str = dechex($str);
		(strlen($str) == 1 ? $str = '0' . $str: null);

		return $str;
    }
	
	/**
	 * Gets the Google Pagerank
	 *
	 * @access		private
	 * @return 		integer					Returns the Google PageRank.
	 */
	public static function Google_PR($host)
	{
		$domain = 'http://'.$host;
		if(USE_PAGERANK_CHECKSUM_API == true)
		{
			$str  = SEOstats::cURL( SEOstats::PAGERANK_CHECKSUM_API_URI . $domain );
			$data = json_decode($str);

			$checksum = $data->CH;
		}
		else
		{
			$checksum = self::genhash($domain);
		}
		$googleurl  = 'http://toolbarqueries.google.com/search?features=Rank&sourceid=navclient-ff&client=navclient-auto-ff';
		$googleurl .= '&googleip=O;66.249.81.104;104&ch='.$checksum.'&q=info:'.urlencode($domain);
		$out = SEOstats::cURL($googleurl);
		
		$pagerank = trim(substr($out, 9));	
		if (!preg_match('/^[0-9]/',$pagerank))
		{
			$pagerank = 'Failed to generate a valid hash for PR check.';
		}
		return $pagerank;
	}
}
?>
