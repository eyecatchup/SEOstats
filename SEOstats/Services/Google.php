<?php
namespace SEOstats\Services;

/**
 * SEOstats extension for Google data.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/17
 */

use SEOstats\Common\SEOstatsException as E;
use SEOstats\SEOstats as SEOstats;
use SEOstats\Config as Config;
use SEOstats\Helper as Helper;

class Google extends SEOstats
{
    /**
     *  Gets the Google Pagerank
     *
     *  @param    string    $url    String, containing the query URL.
     *  @return   integer           Returns the Google PageRank.
     */
    public static function getPageRank($url = false)
    {
        // Composer autoloads classes out of the SEOstats namespace.
        // The custom autolader, however, does not. So we need to include it first.
        if(!class_exists('\GTB_PageRank')) {
            require_once realpath(__DIR__ . '/3rdparty/GTB_PageRank.php');
        }

        $gtb = new \GTB_PageRank(parent::getUrl($url));
        $result = $gtb->getPageRank();

        return $result != "" ? $result : static::noDataDefaultValue();
    }

    /**
     *  Returns the total amount of results for a Google 'site:'-search for the object URL.
     *
     *  @param    string    $url    String, containing the query URL.
     *  @return   integer           Returns the total site-search result count.
     */
    public static function getSiteindexTotal($url = false)
    {
        $url   = parent::getUrl($url);
        $query = urlencode("site:{$url}");

        return self::getSearchResultsTotal($query);
    }

    /**
     *  Returns the total amount of results for a Google 'link:'-search for the object URL.
     *
     *  @param    string    $url    String, containing the query URL.
     *  @return   integer           Returns the total link-search result count.
     */
    public static function getBacklinksTotal($url = false)
    {
        $url   = parent::getUrl($url);
        $query = urlencode("link:{$url}");

        return self::getSearchResultsTotal($query);
    }

    /**
     *  Returns total amount of results for any Google search,
     *  requesting the deprecated Websearch API.
     *
     *  @param    string    $url    String, containing the query URL.
     *  @return   integer           Returns the total search result count.
     */
    public static function getSearchResultsTotal($url = false)
    {
        $url = parent::getUrl($url);
        $url = sprintf(Config\Services::GOOGLE_APISEARCH_URL, 1, $url);

        $ret = static::_getPage($url);

        $obj = Helper\Json::decode($ret);
        return !isset($obj->responseData->cursor->estimatedResultCount)
               ? parent::noDataDefaultValue()
               : intval($obj->responseData->cursor->estimatedResultCount);
    }

    public static function getPagespeedAnalysis($url = false, $strategy = 'desktop')
    {
        if ('' == Config\ApiKeys::GOOGLE_SIMPLE_API_ACCESS_KEY) {
            throw new E('In order to use the PageSpeed API, you must obtain
                and set an API key first (see SEOstats\Config\ApiKeys.php).');
            exit(0);
        }

        $url = parent::getUrl($url);
        $url = sprintf(Config\Services::GOOGLE_PAGESPEED_URL,
            $url, $strategy, Config\ApiKeys::GOOGLE_SIMPLE_API_ACCESS_KEY);

        $ret = static::_getPage($url);

        return Helper\Json::decode($ret);
    }

    public static function getPagespeedScore($url = false, $strategy = 'desktop')
    {
        $url = parent::getUrl($url);
        $ret = self::getPagespeedAnalysis($url, $strategy);

        // Check if $ret->score exists for backwards compatibility with v1.
        if (!isset($ret->score)) {
            return !isset($ret->ruleGroups->SPEED->score) || !$ret->ruleGroups->SPEED->score ? parent::noDataDefaultValue() :
                intval($ret->ruleGroups->SPEED->score);
        }
        else {
            return $ret->score;
        }
    }

    /**
     * Returns array, containing detailed results for any Google search.
     *
     * @param     string    $query  String, containing the search query.
     * @param     string    $tld    String, containing the desired Google top level domain.
     * @return    array             Returns array, containing the keys 'URL', 'Title' and 'Description'.
     */
    public static function getSerps($query, $maxResults=100, $domain=false)
    {
        return Google\Search::getSerps($query, $maxResults, $domain);
    }
}
