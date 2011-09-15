<?php
    /************************************************************************
     * PHP Class SEOstats 2.0.8.2
     *=======================================================================
     * PHP class to request a bunch of SEO data, such as Backlinkdetails,
     * Traffic Statistics, Pageauthority and much more.
     *=======================================================================
     * @package     class.seostats.2.0.8.2
     * @link        https://github.com/eyecatchup/SEOstats/
     * @link        https://bexton.net/SEOstats/
     * @updated     2011/09/06
     * @author      Stephan Schmitz <eyecatchup@gmail.com>
     * @copyright   2010-present, Stephan Schmitz
     * @license     GNU General Public License (GPL)
     * @link        http://www.gnu.org/copyleft/gpl.html
     *=======================================================================
     * @filename    ./class.seostats.php
     * @description SEOstats main class file that includes the child classes
     *              and the config and contains all public methods.
     *=======================================================================
     * @changelog
     * date         author              method: change(s)
     * 2011/09/06   Stephan Schmitz     Removed Majesticseo methods.
     * 2011/08/04   Stephan Schmitz     Added method Bing_Siteindex_Total()
     *                                  Added method Bing_Siteindex_Array()
     *                                  Added method Bing()
     *                                  Updated constant
     *                                              PAGERANK_CHECKSUM_API_URI
     *                                  Removed pre tags when output of the
     *                                  print_array() method is json
     *=======================================================================
     * Note: The above changelog is related to this file only. Each file of
     * the package has it's own changelog in the head section. For a general
     * changelog, please see the CHANGELOG file.
     *=======================================================================
     * Copyright (c) 2010-present, Stephan Schmitz
     * All rights reserved.
     *=======================================================================
     * Project Contributors (Alphabetically by first names):
     *
     * |Chris Alvares <mail@chrisalvares.com>
     * |-> contributed the BING child class
     *
     * |Florent Cima <florentcm@gmail.com>
     * |-> code fix for SEOstats_Majesticseo::report
     *
     * |http://code.google.com/u/@UxJTQFFRABFDXwZ%2F/
     * |-> code fix for SEOstats_Google::googleTotal2
     *=======================================================================
     * Redistribution and use in source and binary forms, with or without
     * modification, are permitted provided that the following conditions are
     * met:
     *
     *     * Redistributions of source code must retain the above copyright
     * notice, this list of conditions and the following disclaimer.
     *     * Neither the name of the Author nor the name of the Product
     * may be used to endorse or promote products derived from
     * this software without specific prior written permission.
     *
     * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
     * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
     * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
     * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
     * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
     * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
     * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
     * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
     * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
     * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
     * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     ***********************************************************************/

include_once('class.seostats.config.php');
include_once('modules.php');

class SEOstats
{
	const BUILD_NO 					= '2.0.8.2';
	const PAGERANK_CHECKSUM_API_URI = 'http://pagerank.bexton.net/?url=';

	/**
	 * Object URL
	 *
	 * @access		public
	 * @var			string
	 */
	public $url;

	/**
	 * Constructor
	 *
	 * Checks for valid URL syntax and server response.
	 *
	 * @access		public
	 * @param		string		$url		String, containing the initialized
	 *                                      object URL.
	 */
	public function __construct($url)
	{
		$url = str_replace(' ', '+', $url);
		$this->url = $url;
		$url_validation = $this->valid_url($this->url);
		if($url_validation == 'valid')
		{
			$valid_response_codes = array('200','301','302');
			$curl_result = $this->get_status_code($this->url);
			if(in_array($curl_result,$valid_response_codes))
			{
				$this->host 		= parse_url($this->url, PHP_URL_HOST);
				$this->protocol 	= parse_url($this->url, PHP_URL_SCHEME);
			}
			elseif($curl_result == '0')
			{
				$e = 'Invalid URL > '.$this->url.' returned no response for a HTTP HEAD request, at all. It seems like the Domain does not exist.';
				$this->errlogtxt($e);
				throw new SEOstatsException($e);
			}
			else
			{
				$e = 'Invalid Request > '.$this->url.' returned a '.$curl_result.' status code.';
				$this->errlogtxt($e);
				throw new SEOstatsException($e);
			}
		}
		else
		{
			$e = $url_validation;
			$this->errlogtxt($e);
			throw new SEOstatsException($e);
		}
	}

	function errlogtxt($errtxt)
	{
		$fp = fopen('errlog.txt','a+'); //ouvrir le fichier
		$newerr = date('Y-m-d\TH:i:sP') .' : ' . $errtxt."\r\n"; //creation du texte de l'erreur
		fwrite($fp,$newerr); //edition du fichier texte
		fclose($fp); //fermeture du fichier texte
		echo $newerr;
	}

	/**
	 * HTTP GET request with curl.
	 *
	 * @access		private
	 * @param		string		$url		String, containing the URL to
	 *                                      curl.
	 * @return		string					Returns string, containing the
	 *                                      curl result.
	 */
	public static function cURL($url)
	{
		$ch  = curl_init($url);
		curl_setopt($ch,CURLOPT_USERAGENT,
			'SEOstats '. SEOstats::BUILD_NO .' code.google.com/p/seostats/');
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
		curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch,CURLOPT_MAXREDIRS,2);
		if(strtolower(parse_url($url, PHP_URL_SCHEME)) == 'https')
		{
			curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
			curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,1);
		}
		$str = curl_exec($ch);
		curl_close($ch);

		return $str;
	}

	/**
	 * HTTP HEAD request with curl.
	 *
	 * @access		private
	 * @param		string		$url		String, containing the
	 *                                      initialized object URL.
	 * @return		intval					Returns a HTTP status code.
	 */
	private function get_status_code($url)
	{
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_USERAGENT,
            'SEOstats '. SEOstats::BUILD_NO .' code.google.com/p/seostats/');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_NOBODY,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
        $str = curl_exec($ch);
        $int = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);

        return intval($int);
    }

    /**
     * Validates the initialized object URL syntax.
     *
     * @access        private
     * @param         string        $url        String, containing the initialized object URL.
     * @return        string                    Returns string, containing the validation result.
     */
    private function valid_url($url)
    {
        $allowed_schemes = array('http','https');
        $host     = parse_url($url, PHP_URL_HOST);
        $scheme = parse_url($url, PHP_URL_SCHEME);

        if(!isset($url) || empty($url) || $url = '')
        {
            $e = 'Invalid Object > Requires an URL.';
        }
        else
        {
            if(!in_array(strtolower($scheme),$allowed_schemes))
            {
                $e = 'Invalid URL > SEOstats supports soley RFC compliant URL\'s with HTTP(/S) protocol.';
            }
            elseif(empty($host) || $host == '')
            {
                $e = 'Invalid URL > Hostname undefined (or invalid URL syntax).';
            }
            else
            {
                /**
                 *  Regex pattern found in and copied from the Nutch source
                 *  @url    {http://nutch.apache.org/}
                 *
                 *  Fyi: For the following reason, i decided to stay with preg_match.
                 *
                 *  Testing 10k URL's, returned an average execution time (in seconds, per URL) of:
                 *  if(!preg_match($pattern,$this->url))
                 *  0.000104904174805
                 *  if(!filter_var($this->url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED))
                 *  0.000140905380249
                 */
                $pattern  = '([A-Za-z][A-Za-z0-9+.-]{1,120}:[A-Za-z0-9/](([A-Za-z0-9$_.+!*,;/?:@&~=-])';
                $pattern .= '|%[A-Fa-f0-9]{2}){1,333}(#([a-zA-Z0-9][a-zA-Z0-9$_.+!*,;/?:@&~=%-]{0,1000}))?)';
                if(!preg_match($pattern,$this->url))
                {
                    $e = 'Invalid URL > Invalid URL syntax.';
                }
                else
                {
                    $e = 'valid';
                }
            }
        }
        return $e;
    }

    /**
     * Just a 'quicker' way to print an array of a method's  result.
     *
     * @param         string        $method        String, containing a method name.
     * @param         string        $output        Optional Parameter. If set to json,
     *                                             output will be a json encoded array.
     * @access        public
     * @return        string                       Prints an array within <pre> tags.
     */
    public function print_array($method,$output='json')
    {
        if($output=='json')
        {
            print_r (json_encode($this->$method()));
        }
        else
        {
            print '<pre>';
            print_r ($this->$method());
            print '</pre>';
        }
    }

    /**
     * @access        public
     * @return        integer   Returns the Google PageRank.
     */
    public function Google_Page_Rank()
    {
        return SEOstats_Google::Google_PR($this->host);
    }

    /**
     * @access        public
     * @return        integer   Returns the total amount of results for a Google 'site:'-search on the object URL.
     */
    public function Google_Siteindex_Total()
    {
        $q = urlencode('site:'.$this->host);
        return SEOstats_Google::googleTotal($q);
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of results for a Google 'site:'-search on the object URL.
     */
    public function Google_Siteindex_Total_API()
    {
        $q = urlencode('site:'.$this->host);
        return SEOstats_Google::googleTotal2($q);
    }

    /**
     * Limited to 1000 results, due to Google.
     *
     * @access        public
     * @return        array      Returns array, containing foreach 'site:'-search result the keys 'URL', 'Title' and 'Description'.
     */
    public function Google_Siteindex_Array()
    {
        $q = urlencode('site:'.$this->host);
        return SEOstats_Google::googleArray($q);
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of results for a Google 'link:'-search on the object URL.
     */
    public function Google_Backlinks_Total()
    {
        $q = urlencode('link:'.$this->host);
        return SEOstats_Google::googleTotal($q);
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of results for a Google 'site:'-search on the object URL.
     */
    public function Google_Backlinks_Total_API()
    {
        $q = urlencode('link:'.$this->host);
        return SEOstats_Google::googleTotal2($q);
    }

    /**
     * Limited to 1000 results, due to Google.
     *
     * @access        public
     * @return        array      Returns array, containing foreach 'link:'-search result the keys 'URL', 'Title' and 'Description'.
     */
    public function Google_Backlinks_Array()
    {
        $q = urlencode('link:'.$this->host);
        return SEOstats_Google::googleArray($q);
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of results for an exact match Google search on the object URL.
     */
    public function Google_Mentions_Total()
    {
        $q = urlencode('"'.$this->host.'" -site:'.$this->host.'');
        return SEOstats_Google::googleTotal($q);
    }

    /**
     * Limited to 1000 results, due to Google.
     *
     * @access        public
     * @return        array      Returns array, containing foreach exact match search result the keys 'URL', 'Title' and 'Description'.
     */
    public function Google_Mentions_Array()
    {
        $q = urlencode('"'.$this->host.'" -site:'.$this->host.'');
        return SEOstats_Google::googleArray($q);
    }


    /**
     * Returns Onsite Optimization Tips (for better page performance) by Google.
     *
     * @access    public
     * @returun    array        Returns array, containing the page analysis results.
     */
    public function Google_Performance_Analysis()
    {
        return SEOstats_Google::performanceAnalysis($this->host);
    }

    /**
     * Returns the Google Pagespeed Score.
     * Score is between 0 (worst) and 100 (best).
     *
     * @access    public
     * @returun    integer      Returns a number between 0 - 100.
     */
    public function Google_Pagespeed_Score()
    {
        return SEOstats_Google::pagespeedScore($this->host);
    }

    /**
     * @access        public
     * @return        integer   Returns the total amount of pages for the domain, indexed at Yahoo!.
     */
    public function Yahoo_Siteindex_Total()
    {
        return SEOstats_Yahoo::yahooSiteindexTotal($this->host);
    }

    /**
     * Limited to 100 results.
     *
     * @access        public
     * @return        integer    Returns array, containing the keys 'Title', 'URL' and 'Click URL'.
     */
    public function Yahoo_Siteindex_Array()
    {
        return SEOstats_Yahoo::yahooSiteindexArray($this->host);
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of backlinks to the domain, listed at Yahoo!.
     */
    public function Yahoo_Backlinks_Total()
    {
        return SEOstats_Yahoo::yahooBacklinksTotal($this->host);
    }

    /**
     * Limited to 100 results.
     *
     * @access        public
     * @return        integer    Returns array, containing the keys 'URL' and 'Anchortext'.
     */
    public function Yahoo_Backlinks_Array()
    {
        return SEOstats_Yahoo::yahooBacklinksArray($this->host);
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of pages for the domain, indexed at Yahoo!.
     */
    public function Bing_Siteindex_Total()
    {
        return SEOstats_Bing::bingSiteindexTotal($this->host);
    }


    /**
     * Limited to 50 results.
     *
     * @access        public
     * @return        integer    Returns array, containing the keys 'Title', 'URL' and 'Click URL'.
     */
    public function Bing_Siteindex_Array()
    {
        return SEOstats_Bing::bingSiteindexArray($this->host);
    }

    /**
     * @access        public
     * @return        array      Returns array, containing the keys 'URL Authority', 'URL mozRank', 'Domain Authority' and 'Domain mozRank'.
     */
    public function Seomoz_Domainauthority_Array()
    {
        return SEOstats_Seomoz::Seomoz_Authority($this->host);
    }

    /**
     * Limited to 25 links per source domain, due to using a free API key.
     *
     * @access        public
     * @link          http://apiwiki.seomoz.org/w/page/27002419/Glossary    Response Format, Term Conventions
     * @return        array      Returns multi-array, containing backlink details.
     */
    public function Seomoz_Linkdetails_Array()
    {
        return SEOstats_Seomoz::Seomoz_Links($this->host);
    }

    /**
     * @access        public
     * @return        integer    Returns the Alexa Global Rank.
     */
    public function Alexa_Global_Rank_Array()
    {
        return SEOstats_Alexa::extractSingle('div','id','rank','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        integer    Returns the Alexa Country Rank.
     */
    public function Alexa_Country_Rank()
    {
        return SEOstats_Alexa::extractSingle('div','class','data','1',$this->host,false);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about the total pageviews.
     */
    public function Alexa_Pageviews()
    {
        return SEOstats_Alexa::extractSingle('div','id','pageviews','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about pageviews per user.
     */
    public function Alexa_Pageviews_Per_User()
    {
        return SEOstats_Alexa::extractSingle('div','id','pageviews_per_user','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about the reach.
     */
    public function Alexa_Reach()
    {
        return SEOstats_Alexa::extractSingle('div','id','reach','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about the bounce rate.
     */
    public function Alexa_Bounce_Rate()
    {
        return SEOstats_Alexa::extractSingle('div','id','bounce','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about the avg time, users stay on the site.
     */
    public function Alexa_Time_On_Site()
    {
        return SEOstats_Alexa::extractSingle('div','id','time_on_site','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about visitors from searches.
     */
    public function Alexa_Search_Visits()
    {
        return SEOstats_Alexa::extractSingle('div','id','search','0',$this->host,true);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing the Visits by Country.
     */
    public function Alexa_Visits_By_Country()
    {
        return SEOstats_Alexa::Alexa_VBC($this->url);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing the Alexa Rank, sorted by Country.
     */
    public function Alexa_Rank_By_Country()
    {
        return SEOstats_Alexa::Alexa_RBC($this->url);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about keywords from search visits.
     */
    public function Alexa_Search_Visits_Keywords()
    {
        return SEOstats_Alexa::Alexa_SV_Keywords($this->url);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing data from Alexa about changes of incoming search terms.
     */
    public function Alexa_Search_Visits_Changes()
    {
        return SEOstats_Alexa::Alexa_SV_Changes($this->url);
    }

    /**
     * @access        public
     * @return        string     Returns string, containing the average load time of the URL from Alexa.
     */
    public function Alexa_Avg_Load_Time()
    {
        return SEOstats_Alexa::Alexa_Load_Time($this->url);
    }

    /**
     * @access        public
     * @param         integer    $type      Specifies the graph type. Valid values are 1 to 6.
     * @param         integer    $width     Specifies the graph width (in px).
     * @param         integer    $height    Specifies the graph height (in px).
     * @param         integer    $period    Specifies the displayed time period. Valid values are 1 to 12.
     * @return        string                Returns a string, containing the HTML code of an image, showing Alexa Statistics as Graph.
     */
    public function Alexa_Graph($type='1',$width='660',$height='330',$period='1')
    {
        switch($type)
        {
            case 1: $gtype = 't'; break;
            case 2: $gtype = 'p'; break;
            case 3: $gtype = 'u'; break;
            case 4: $gtype = 's'; break;
            case 5: $gtype = 'b'; break;
            case 6: $gtype = 'q'; break;
            default:break;
        }
        $graph  = '<img src="http://traffic.alexa.com/graph?&o=f&c=1&y='.$gtype.'&b=ffffff&n=666666';
        $graph .= '&w='.$width.'&h='.$height.'&r='.$period.'m&u='.$this->url;
        $graph .= '" width="'.$width.'" height="'.$height.'" alt="Alexa Statistics Graph for '.$this->url.'" />';

        return $graph;
    }

    /**
     * @access        public
     * @return        integer    Returns the total amount of Facebook pages mention the URL.
     */
    public function Facebook_Mentions_Total()
    {
        return SEOstats_Facebook::getFacebookShares($this->url);
    }

    /**
     * Limited to 1000 results.
     *
     * @access        public
     * @return        array      Returns array, containing detailed results about Facebook pages mention the URL.
     */
    public function Facebook_Mentions_Array()
    {
        $q = urlencode('site:facebook.com "'.$this->host.'"');
        return SEOstats_Google::googleArray($q);
    }

	/**
	 * Returns the internal ID, the Facebook Graph API registers to - and uses for identifying - a Domain.
	 *
	 * @access		  public
	 * @link		  http://developers.facebook.com/docs/reference/api/domain/
	 * @param   host  string  	 The URL to get the ID for.
	 * @return        integer    Returns 0, or the unique Domain-ID.
	 */
    public function Facebook_GraphApi_DomainId_ByHostname()
    {
        return SEOstats_Facebook::fbGraphApiIdByHost($this->host);
    }
	
    /**
     * @access        public
     * @return        integer    Returns the total amount of Twitter pages mention the URL.
     */
    public function Twitter_Mentions_Total()
    {
        return SEOstats_Twitter::getTweetCount($this->url);
    }

    /**
     * Limited to 1000 results.
     *
     * @access        public
     * @return        array      Returns array, containing detailed results about Twitter pages mention the URL.
     */
    public function Twitter_Mentions_Array()
    {
        $q = urlencode('site:twitter.com "'.$this->host.'"');
        return SEOstats_Google::googleArray($q);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all Google data.
     */
    public function Google()
    {
        $all = array(
            'GOOGLE' => array(
                'Google_Page_Rank'           => $this->Google_Page_Rank(),
                'Google_Siteindex_Total'     => $this->Google_Siteindex_Total(),
                'Google_Siteindex_Total_API' => $this->Google_Siteindex_Total_API(),
                'Google_Siteindex_Array'     => $this->Google_Siteindex_Array(),
                'Google_Backlinks_Total'     => $this->Google_Backlinks_Total(),
                'Google_Backlinks_Total_API' => $this->Google_Backlinks_Total_API(),
                'Google_Backlinks_Array'     => $this->Google_Backlinks_Array(),
                'Google_Mentions_Total'      => $this->Google_Mentions_Total(),
                'Google_Mentions_Array'      => $this->Google_Mentions_Array()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all Yahoo! data.
     */
    public function Yahoo()
    {
        $all = array(
            'YAHOO' => array(
                'Yahoo_Siteindex_Total' => $this->Yahoo_Siteindex_Total(),
                'Yahoo_Siteindex_Array' => $this->Yahoo_Siteindex_Array(),
                'Yahoo_Backlinks_Total' => $this->Yahoo_Backlinks_Total(),
                'Yahoo_Backlinks_Array' => $this->Yahoo_Backlinks_Array()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all Bing data.
     */
    public function Bing()
    {
        $all = array(
            'YAHOO' => array(
                'Bing_Siteindex_Total' => $this->Bing_Siteindex_Total(),
                'Bing_Siteindex_Array' => $this->Bing_Siteindex_Array()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all SEOmoz data.
     */
    public function Seomoz()
    {
        $all = array(
            'SEOMOZ' => array(
                'Seomoz_Domainauthority_Array' => $this->Seomoz_Domainauthority_Array(),
                'Seomoz_Linkdetails_Array'     => $this->Seomoz_Linkdetails_Array()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all Alexa data.
     */
    public function Alexa()
    {
        $all = array(
            'ALEXA' => array(
                'Alexa_Global_Rank_Array'      => $this->Alexa_Global_Rank_Array(),
                'Alexa_Country_Rank'           => $this->Alexa_Country_Rank(),
                'Alexa_Rank_By_Country'        => $this->Alexa_Rank_By_Country(),
                'Alexa_Visits_By_Country'      => $this->Alexa_Visits_By_Country(),
                'Alexa_Pageviews'              => $this->Alexa_Pageviews(),
                'Alexa_Pageviews_Per_User'     => $this->Alexa_Pageviews_Per_User(),
                'Alexa_Reach'                  => $this->Alexa_Reach(),
                'Alexa_Bounce_Rate'            => $this->Alexa_Bounce_Rate(),
                'Alexa_Time_On_Site'           => $this->Alexa_Time_On_Site(),
                'Alexa_Search_Visits'          => $this->Alexa_Search_Visits(),
                'Alexa_Search_Visits_Keywords' => $this->Alexa_Search_Visits_Keywords(),
                'Alexa_Search_Visits_Changes'  => $this->Alexa_Search_Visits_Changes(),
                'Alexa_Avg_Load_Time'          => $this->Alexa_Avg_Load_Time()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all Facebook data.
     */
    public function Facebook()
    {
        $all = array(
            'FACEBOOK' => array(
                'Facebook_Mentions_Total' => $this->Facebook_Mentions_Total(),
                'Facebook_Mentions_Array' => $this->Facebook_Mentions_Array()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * @access        public
     * @return        array      Returns multi-array, containing all Twitter data.
     */
    public function Twitter()
    {
        $all = array(
            'TWITTER' => array(
                'Twitter_Mentions_Total' => $this->Twitter_Mentions_Total(),
                'Twitter_Mentions_Array' => $this->Twitter_Mentions_Array()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
	 * @access		public
	 * @return		integer					Returns a global unique ID (useful for database integration)
	 */
	public function Guid()
	{

		return SEOstats_Guid::CreateGUID($this->varGUID);
	}

    /**
     * Method processing might take a few more seconds!
     *
     * @access        public
     * @return        array      Returns multi-array, containing all data but without Linkdetails.
     */
    public function All_Totals()
    {
        $all = array(
            'GOOGLE' => array(
                'Google_Page_Rank'                       => $this->Google_Page_Rank(),
                'Google_Siteindex_Total'                 => $this->Google_Siteindex_Total(),
                'Google_Siteindex_Total_API'             => $this->Google_Siteindex_Total_API(),
                'Google_Backlinks_Total'                 => $this->Google_Backlinks_Total(),
                'Google_Backlinks_Total_API'             => $this->Google_Backlinks_Total_API(),
                'Google_Mentions_Total'                  => $this->Google_Mentions_Total()
            ),
            'YAHOO' => array(
                'Yahoo_Siteindex_Total'                  => $this->Yahoo_Siteindex_Total(),
                'Yahoo_Backlinks_Total'                  => $this->Yahoo_Backlinks_Total()
            ),
            'BING' => array(
                'Bing_Siteindex_Total'                   => $this->Bing_Siteindex_Total()
            ),
            'SEOMOZ' => array(
                'Seomoz_Domainauthority_Array'           => $this->Seomoz_Domainauthority_Array()
            ),
            'ALEXA' => array(
                'Alexa_Global_Rank_Array'                => $this->Alexa_Global_Rank_Array(),
                'Alexa_Country_Rank'                     => $this->Alexa_Country_Rank(),
                'Alexa_Rank_By_Country'                  => $this->Alexa_Rank_By_Country(),
                'Alexa_Visits_By_Country'                => $this->Alexa_Visits_By_Country(),
                'Alexa_Pageviews'                        => $this->Alexa_Pageviews(),
                'Alexa_Pageviews_Per_User'               => $this->Alexa_Pageviews_Per_User(),
                'Alexa_Reach'                            => $this->Alexa_Reach(),
                'Alexa_Bounce_Rate'                      => $this->Alexa_Bounce_Rate(),
                'Alexa_Time_On_Site'                     => $this->Alexa_Time_On_Site(),
                'Alexa_Search_Visits'                    => $this->Alexa_Search_Visits(),
                'Alexa_Search_Visits_Keywords'           => $this->Alexa_Search_Visits_Keywords(),
                'Alexa_Search_Visits_Changes'            => $this->Alexa_Search_Visits_Changes(),
                'Alexa_Avg_Load_Time'                    => $this->Alexa_Avg_Load_Time()
            ),
            'FACEBOOK' => array(
                'Facebook_Mentions_Total'                => $this->Facebook_Mentions_Total()
            ),
            'TWITTER' => array(
                'Twitter_Mentions_Total'                 => $this->Twitter_Mentions_Total()
            )
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
     * Method processing might take a few more seconds!
     *
     * @access        public
     * @return        array      Returns multi-array, containing all data.
     */
    public function Everything()
    {
        $google    = $this->Google();
        $yahoo     = $this->Yahoo();
        $bing      = $this->Bing();
        $seomoz    = $this->Seomoz();
        $alexa     = $this->Alexa();
        $facebook  = $this->Facebook();
        $twitter   = $this->Twitter();

        $all = array(
            $google['DATA'],
            $yahoo['DATA'],
            $bing['DATA'],
            $seomoz['DATA'],
            $alexa['DATA'],
            $facebook['DATA'],
            $twitter['DATA']
        );
        return array('OBJECT' => $this->url, 'DATA' => $all);
    }

    /**
	 * Custom Arrays
	 *
	 * @access		public
	 * @return		array					Returns multi-array, containing custom data.
	 */
	public function Custom()
	{
		$all = 	array(
			'SEOMOZ' => array(
				'Seomoz_Domainauthority_Array'	=> $this->Seomoz_Domainauthority_Array()
			),
			'FACEBOOK' => array(
				'Facebook_Mentions_Total'	=> $this->Facebook_Mentions_Total()
			),
			'TWITTER' => array(
				'Twitter_Mentions_Total'	=> $this->Twitter_Mentions_Total()
			),
			'GUID' => array(
				'Global_Unique_ID'		=> $this->Guid()
			)
		);
		return array('OBJECT' => $this->url, 'DATA' => $all);
	}

	/**
	 * Social Arrays
	 *
	 * @access		public
	 * @return		array					Returns multi-array, containing social data.
	 */
	public function Social()
	{
		$all = 	array(
			'FACEBOOK' => array(
				'Facebook_Mentions_Total' 	=> $this->Facebook_Mentions_Total()
			),
			'TWITTER' => array(
				'Twitter_Mentions_Total'	=> $this->Twitter_Mentions_Total()
			),
			'GUID' => array(
				'Global_Unique_ID'		=> $this->Guid()
			)
		);
		return array('OBJECT' => $this->url, 'DATA' => $all);
	}
}
?>
