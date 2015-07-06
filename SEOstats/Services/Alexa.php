<?php
namespace SEOstats\Services;

/**
 * SEOstats extension for Alexa data.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/17
 */

use SEOstats\SEOstats as SEOstats;
use SEOstats\Config as Config;
use SEOstats\Helper as Helper;

class Alexa extends SEOstats
{
    /**
     * Used for cache
     * @var DOMXPath
     */
    protected static $_xpath = false;

    protected static $_rankKeys = array(
        '1d' => 0,
        '7d' => 0,
        '1m' => 0,
        '3m' => 0,
    );

    /**
     * Get yesterday's rank
     * @return int
     */
    public static function getDailyRank($url = false)
    {
        self::setRankingKeys($url);
        if (0 == self::$_rankKeys['1d']) {
            return parent::noDataDefaultValue();
        }

        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[" . self::$_rankKeys['1d'] . "]/td[1]");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    /**
     * For backward compatibility
     * @deprecated
     */
    public static function getWeekRank($url = false) {
        return self::getWeeklyRank($url);
    }
    /**
     * Get the average rank over the last 7 days
     * @return int
     */
    public static function getWeeklyRank($url = false)
    {
        self::setRankingKeys($url);
        if (0 == self::$_rankKeys['7d']) {
            return parent::noDataDefaultValue();
        }

        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[" . self::$_rankKeys['7d'] . "]/td[1]");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    /**
     * For backward compatibility
     * @deprecated
     */
    public static function getMonthRank($url = false) {
        return self::getMonthlyRank($url);
    }
    /**
     * Get the average rank over the last month
     * @return int
     */
    public static function getMonthlyRank($url = false)
    {
        self::setRankingKeys($url);
        if (0 == self::$_rankKeys['1m']) {
            return parent::noDataDefaultValue();
        }

        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[" . self::$_rankKeys['1m'] . "]/td[1]");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    /**
     * For backward compatibility
     * @deprecated
     */
    public static function getQuarterRank($url = false) {
        return self::getGlobalRank($url);
    }
    /**
     * Get the average rank over the last 3 months
     * @return int
     */
    public static function getGlobalRank($url = false)
    {
        /*
        self::setRankingKeys($url);
        if (0 == self::$_rankKeys['3m']) {
            return parent::noDataDefaultValue();
        }
        */

        $xpath = self::_getXPath($url);

        $xpathQueryList = array(
            "//*[@id='traffic-rank-content']/div/span[2]/div[1]/span/span/div/strong",
            "//*[@id='traffic-rank-content']/div/span[2]/div[1]/span/span/div/strong/a"
        );

        return static::parseDomByXpathsToIntegerWithoutTags($xpath, $xpathQueryList);
    }

    /**
     * Get the average rank over the week
     * @return int
     */
    public static function setRankingKeys($url = false)
    {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr");

        if (5 == $nodes->length) {
            self::$_rankKeys = array(
                '1d' => 2,
                '7d' => 3,
                '1m' => 4,
                '3m' => 5,
            );
        }
        else if (4 == $nodes->length) {
            self::$_rankKeys = array(
                '1d' => 0,
                '7d' => 2,
                '1m' => 3,
                '3m' => 4,
            );
        }
        else if (3 == $nodes->length) {
            self::$_rankKeys = array(
                '1d' => 0,
                '7d' => 0,
                '1m' => 2,
                '3m' => 3,
            );
        }
        else if (2 == $nodes->length) {
            self::$_rankKeys = array(
                '1d' => 0,
                '7d' => 0,
                '1m' => 0,
                '3m' => 2,
            );
        }
    }

    public static function getCountryRank($url = false)
    {
        $xpath = self::_getXPath($url);
        $node1 = self::parseDomByXpaths($xpath, array(
            "//*[@id='traffic-rank-content']/div/span[2]/div[2]/span/span/h4/a",
            "//*[@id='traffic-rank-content']/div/span[2]/div[2]/span/span/h4/strong/a",
        ));

        $node2 = self::parseDomByXpaths($xpath, array(
            "//*[@id='traffic-rank-content']/div/span[2]/div[2]/span/span/div/strong/a",
            "//*[@id='traffic-rank-content']/div/span[2]/div[2]/span/span/div/strong",
        ));

        $node3 = self::parseDomByXpaths($xpath, array(
            "//*[@id='traffic-rank-content']/div/span[2]/div[2]/span/span/h4/a/@href",
            "//*[@id='traffic-rank-content']/div/span[2]/div[2]/span/span/h4/strong/a/@href",
        ));

        if (!is_null($node2) && $node2->item(0)) {
            $rank = self::retInt(strip_tags($node2->item(0)->nodeValue));
            $country_code = str_replace("/topsites/countries/", "", $node3->item(0)->nodeValue);
            if ($node1->item(0) && 0 != $rank) {
                return array(
                    'rank' => $rank,
                    'country' => $node1->item(0)->nodeValue,
                    'country_code' => $country_code,
                );
            }
        }

        return parent::noDataDefaultValue();
    }

    public static function getBacklinkCount($url = false)
    {
        $xpath = self::_getXPath($url);

        $queryList = array(
            "//section[@class='row-fluid panel-wrapper '][6]/section/div/span/div/span",
            "//*[@id='linksin_div']/section/div/div[1]/span"
        );

        return static::parseDomByXpathsToInteger($xpath, $queryList);
    }

    public static function getPageLoadTime($url = false)
    {
        $xpath = self::_getXPath($url);

        $queryList = array(
            "//section[@class='row-fluid panel-wrapper '][9]/section/p",
            "//*[@id='section-load']/div/section/p"
        );

        return static::parseDomByXpathsWithoutTags($xpath, $queryList);
    }

    /**
     * @access        public
     * @param         integer    $type      Specifies the graph type. Valid values are 1 to 6.
     * @param         integer    $width     Specifies the graph width (in px).
     * @param         integer    $height    Specifies the graph height (in px).
     * @param         integer    $period    Specifies the displayed time period. Valid values are 1 to 12.
     * @return        string                Returns a string, containing the HTML code of an image, showing Alexa Statistics as Graph.
     */
    public static function getTrafficGraph($type = 1, $url = false, $w = 660, $h = 330, $period = 1, $html = true)
    {
        $url    = self::getUrl($url);
        $domain = Helper\Url::parseHost($url);

        switch($type) {
            case 1: $gtype = 't'; break;
            case 2: $gtype = 'p'; break;
            case 3: $gtype = 'u'; break;
            case 4: $gtype = 's'; break;
            case 5: $gtype = 'b'; break;
            case 6: $gtype = 'q'; break;
            default: break;
        }

        $imgUrl = sprintf(Config\Services::ALEXA_GRAPH_URL, $gtype, $w, $h, $period, $domain);
        $imgTag = '<img src="%s" width="%s" height="%s" alt="Alexa Statistics Graph for %s"/>';

        return !$html ? $imgUrl : sprintf($imgTag, $imgUrl, $w, $h, $domain);
    }

    /**
     * @return DOMXPath
     */
    protected static function _getXPath($url) {
        $url = parent::getUrl($url);
        if (parent::getLastLoadedUrl() == $url && self::$_xpath) {
            return self::$_xpath;
        }

        $html  = static::_getAlexaPage($url);
        $doc   = parent::_getDOMDocument($html);
        $xpath = parent::_getDOMXPath($doc);

        self::$_xpath = $xpath;

        return $xpath;
    }

    protected static function _getAlexaPage($url)
    {
        $domain  = Helper\Url::parseHost($url);
        $dataUrl = sprintf(Config\Services::ALEXA_SITEINFO_URL, $domain);
        $html    = static::_getPage($dataUrl);
        return $html;
    }

    protected static function retInt($str)
    {
        $strim = trim(str_replace(',', '', $str));
        $intStr = 0 < strlen($strim) ? $strim : '0';
        return intval($intStr);
    }

    /**
     *
     * @return mixed nodeValue
     */
    protected static function parseDomByXpaths($xpathDom, $xpathQueryList) {

        foreach ( $xpathQueryList as $query ) {
            $nodes = @$xpathDom->query($query);

            if ( $nodes->length != 0 ) {
                return $nodes;
            }
        }

        return null;
    }

    /**
     *
     * @return mixed nodeValue
     */
    protected static function parseDomByXpathsGetValue($xpathDom, $xpathQueryList)
    {
        $nodes = static::parseDomByXpaths($xpathDom, $xpathQueryList);

        return ($nodes) ? $nodes->item(0)->nodeValue : null;
    }

    /**
     *
     * @return mixed nodeValue
     */
    protected static function parseDomByXpathsToInteger($xpathDom, $xpathQueryList)
    {
        $nodeValue = static::parseDomByXpathsGetValue($xpathDom, $xpathQueryList);

        if ($nodeValue === null) {
            return parent::noDataDefaultValue();
        }
        return self::retInt( $nodeValue );
    }

    /**
     *
     * @return mixed nodeValue
     */
    protected static function parseDomByXpathsWithoutTags($xpathDom, $xpathQueryList)
    {

        $nodeValue = static::parseDomByXpathsGetValue($xpathDom, $xpathQueryList);

        if ($nodeValue === null) {
            return parent::noDataDefaultValue();
        }

        return strip_tags($nodeValue);
    }

    /**
     *
     * @return mixed nodeValue
     */
    protected static function parseDomByXpathsToIntegerWithoutTags($xpathDom, $xpathQueryList)
    {
        $nodeValue = static::parseDomByXpathsGetValue($xpathDom, $xpathQueryList);

        if ($nodeValue === null) {
            return parent::noDataDefaultValue();
        }

        return self::retInt(strip_tags($nodeValue));
    }
}
