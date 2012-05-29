<?php
/**
 *  PHP class SEOstats
 *
 *  @class      SEOstats_Google
 *  @package    class.seostats
 *  @link       https://github.com/eyecatchup/SEOstats/
 *  @updated    2012/01/29
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @copyright  2010-present, Stephan Schmitz
 *  @license    GNU General Public License (GPL)
 *
 *  @filename   ./seostats.google.php
 *  @desc       Child class of SEOstats, extending the main class
 *              by methods for Google statistics.
 *
 *  @changelog
 *  date        author              method: change(s)
 *  2011/08/04  Stephan Schmitz     googleTotal2: Added if condition at return, to fix/avoid
 *                                  errors when estimatedResultCount is not set.
 *  2011/10/07  Stephan Schmitz     Google_PR: Updated the toolbar URL for Pagerank requests.
 *  2012/01/29  Stephan Schmitz     Google_PR: Implemented alternative checksum calculation.
 *                                  performanceAnalysis: Updated request URL.
 *                                  pageSpeedScore: Updated request URL.
 */

class SEOstats_Google extends SEOstats implements services 
{
    /**
     *  Gets the Google Pagerank
     *
     *  @param        string        $a      String, containing the query URL.
     *  @return       integer               Returns the Google PageRank.
     */
    public function getPageRank($a=NULL)
    {
		$b = (NULL !== $a) ? $a : parent::getUrl();
        require_once(SEOSTATSPATH ."3rdparty/GTB_PageRank.php");
		$r = new GTB_PageRank($b);
        return $r->getPageRank();
    }
	
    /**
	 *  Returns the total amount of results for a Google 'site:'-search for the object URL.
	 *
     *  @access        public
     *  @return        integer   Returns the total site-search result count.
     */
    public function getSiteindexTotal($a=NULL)
    {
		$b = (NULL !== $a) ? $a : parent::getUrl();
        $q = urlencode("site:$b");
        return self::getSearchResultsTotal($q);
    }
	
    /**
	 *  Returns the total amount of results for a Google 'link:'-search for the object URL.
	 *
     *  @access        public
     *  @return        integer   Returns the total link-search result count.
     */
    public function getBacklinksTotal($a=NULL)
    {
		$b = (NULL !== $a) ? $a : parent::getUrl();
        $q = urlencode("link:$b");
        return self::getSearchResultsTotal($q);
    }
	
    /**
     *  Returns total amount of results for any Google search,
     *  requesting the deprecated Websearch API.
     *
     *  @param        string        $a      String, containing the search query.
     *  @return       integer               Returns the total search result count.
     */
    public function getSearchResultsTotal($a=NULL)
    {
		$b = (NULL !== $a) ? $a : parent::getUrl();
        $c = sprintf(services::GOOGLE_APISEARCH_URL, 1, $b);
        $r = HttpRequest::sendRequest($c);
        $r = json_decode($r);

        return (! isset($r->responseData->cursor->estimatedResultCount) )
                ? '0'
                : intval($r->responseData->cursor->estimatedResultCount);
    }

    /**
     *  Returns total amount of results for any Google search,
     *  requesting the deprecated Websearch API.
     *
     *  @param        string        $a      String, containing the search query.
     *  @return       integer               Returns a total count.
     */	
    public function getPagespeedAnalysis($a=NULL) {
		$b = (NULL !== $a) ? $a : parent::getUrl();
        $c = sprintf(services::GOOGLE_PAGESPEED_URL, $b);
        $r = HttpRequest::sendRequest($c);
        return json_decode($r);
    }

    public function getPagespeedScore($a=NULL) {
		$b = (NULL !== $a) ? $a : parent::getUrl();
        $r = self::getPagespeedAnalysis($b);
        return intval($r->results->score);
    }

    /**
     * Returns total amount of results for any Google search.
     *
     * @access       private
     * @param        string        $query      String, containing the search query.
     * @param        string        $tld        String, containing the desired Google top level domain.
     * @return       integer                   Returns a total count.
     */
    public function googleTotal($query)
    {
        $url = 'http://www.google.'. GOOGLE_TLD .'/custom?num=1&q='.$query;
        $str = SEOstats::cURL($url);
        preg_match_all('#<b>(.*?)</b>#',$str,$matches);

        return (!empty($matches[1][2]))
                ? $matches[1][2]
                : '0';
    }

    /**
     * Returns array, containing detailed results for any Google search.
     *
     * @access       private
     * @param        string        $query      String, containing the search query.
     * @param        string        $tld        String, containing the desired Google top level domain.
     * @return       array                     Returns array, containing the keys 'URL', 'Title' and 'Description'.
     */
    public function googleArray($query)
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



}
?>
