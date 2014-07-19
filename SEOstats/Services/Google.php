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

    public static function getPagespeedAnalysis($url = false)
    {
        if ('' == Config\ApiKeys::GOOGLE_SIMPLE_API_ACCESS_KEY) {
            throw new E('In order to use the PageSpeed API, you must obtain
                and set an API key first (see SEOstats\Config\ApiKeys.php).');
            exit(0);
        }

        $url = parent::getUrl($url);
        $url = sprintf(Config\Services::GOOGLE_PAGESPEED_URL,
            $url, Config\ApiKeys::GOOGLE_SIMPLE_API_ACCESS_KEY);

        $ret = static::_getPage($url);

        return Helper\Json::decode($ret);
    }

    public static function getPagespeedScore($url = false)
    {
        $url = parent::getUrl($url);
        $ret = self::getPagespeedAnalysis($url);

        return !isset($ret->score) || !$ret->score ? parent::noDataDefaultValue() :
            intval($ret->score);
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
        $q = rawurlencode($query);
        $maxResults = ($maxResults/10)-1;
        $result = array ();
        $pages = 1;
        $delay = 0;

        for ($start=0; $start<$pages; $start++) {
            $ref = 0 == $start ? 'ncr' : sprintf('search?q=%s&hl=en&prmd=imvns&start=%s0&sa=N', $q, $start);
            $nextSerp =  0 == $start ? sprintf('search?q=%s&filter=0', $q) : sprintf('search?q=%s&filter=0&start=%s0', $q, $start);

            $curledSerp = utf8_decode( static::gCurl($nextSerp, $ref) );

            if (preg_match("#answer[=|/]86640#i", $curledSerp)) {
                print('Please read: https://support.google.com/websearch/answer/86640');
                exit();
            }


            $matches = array();
            preg_match_all('#<h3 class="?r"?>(.*?)</h3>#', $curledSerp, $matches);
            if (empty($matches[1])) {
                // No [@id="rso"]/li/h3 on currect page
                $pages -= 1;
            } else {

                static::getSerpsMatches($matches, $domain, $start * 10, $result);

                if ( preg_match('#id="?pnnext"?#', $curledSerp) ) {
                    // Found 'Next'-link on currect page
                    $pages += 1;
                    $delay += 200000;
                    usleep($delay);
                } else {
                    // No 'Next'-link on currect page
                    $pages -= 1;
                }
            }

            if ($start == $maxResults) {
                $pages -= 1;
            }
        } // for ($start=0; $start<$pages; $start++)
        return $result;
    }

    protected static function getSerpsMatches ($matches, $domain, $start, &$result)
    {
        $domainRexExp = $domain ? "#^(https?://)?[^/]*{$domain}#i" : false;

        $c = 0;

        foreach ($matches[1] as $link) {
            if ( !preg_match('#<a\s+[^>]*href=[\'"]?([^\'" ]+)[\'"]?[^>]*>(.*?)</a>#', $link, $match) ||
                  preg_match('#^https?://www.google.com/(?:intl/.+/)?webmasters#', $match[1]))
            {
                continue;
            }

            $c++;
            $resCnt = $start + $c;
            if (! $domainRexExp) {
                $result[$resCnt] = array(
                    'url' => $match[1],
                    'headline' => trim(strip_tags($match[2]))
                );
            } elseif (preg_match($domainRexExp, $match[1])) {
                $result[] = array(
                    'position' => $resCnt,
                    'url' => $match[1],
                    'headline' => trim(strip_tags($match[2]))
                );
            }
        } // foreach ($matches[1] as $link)

        return $result;
    }

    protected static function gCurl($path, $ref, $useCookie = Config\DefaultSettings::ALLOW_GOOGLE_COOKIES)
    {
        $url = sprintf('https://www.google.%s/', Config\DefaultSettings::GOOGLE_TLD);
        $referer = $ref == '' ? $url : $ref;
        $url .= $path;

        $ua = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36";
        if (isset($_SERVER["HTTP_USER_AGENT"]) && 0 < strlen($_SERVER["HTTP_USER_AGENT"])) {
            $ua = $_SERVER["HTTP_USER_AGENT"];
        }

        $header = array(
            'Host: www.google.' . Config\DefaultSettings::GOOGLE_TLD,
            'Connection: keep-alive',
            'Cache-Control: max-age=0',
            'User-Agent: ' . $ua,
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Referer: ' . $referer,
            'Accept-Language: ' . Config\DefaultSettings::HTTP_HEADER_ACCEPT_LANGUAGE,
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7'
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
        if ($useCookie == 1) {
            curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
            curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
        }
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return ($info['http_code']!=200) ? false : $result;
    }
}
