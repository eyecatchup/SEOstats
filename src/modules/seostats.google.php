<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for Google data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/05/30
 */

class SEOstats_Google extends SEOstats implements services
{
    /**
     *  Gets the Google Pagerank
     *
     *  @param   string    $a      String, containing the query URL.
     *  @return  integer           Returns an integer value between 0 and 10.
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
     *  @param   string    $a      String, containing the query URL.
     *  @return  integer           Returns the total site-search result count.
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
     *  @param   string    $a      String, containing the query URL.
     *  @return  integer           Returns the total link-search result count.
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
     *  @param   string    $a      String, containing the search query.
     *  @return  integer           Returns the total search result count.
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
     *  Returns the Google Pagespeed Service results.
     *
     *  @param   string    $a      String, containing the query URL.
     *  @return  array             Returns an array, containing the Pagespeed analysis results.
     */
    public function getPagespeedAnalysis($a=NULL) {
        $b = (NULL !== $a) ? $a : parent::getUrl();
        $c = sprintf(services::GOOGLE_PAGESPEED_URL, $b);
        $r = HttpRequest::sendRequest($c);
        return json_decode($r);
    }

    /**
     *  Returns total score of a Pagespeed analysis.
     *
     *  @param   string    $a      String, containing the query URL.
     *  @return  integer           Returns an integer value between 0 and 100.
     */
    public function getPagespeedScore($a=NULL) {
        $b = (NULL !== $a) ? $a : parent::getUrl();
        $r = self::getPagespeedAnalysis($b);
        return intval($r->results->score);
    }
}

/* End of file seostats.google.php */
/* Location: ./src/modules/seostats.google.php */
