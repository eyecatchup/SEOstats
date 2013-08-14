<?php
namespace SEOstats\Services;

/**
 * SEOstats extension for Alexa data.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/14
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

    /**
     * The global rank is compute as an average over three months
     *
     * @return int
     */
    public static function getGlobalRank($url = false)
    {
        return self::getQuarterRank($url);
    }

    /**
     * Get the average rank over the week
     * @return int
     */
    public static function getWeekRank($url = false)
    {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[2]/td[1]");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    /**
     * Get the average rank over the week
     * @return int
     */
    public static function getMonthRank($url = false)
    {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[3]/td[1]");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }


    public static function getQuarterRank($url = false) {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[1]/div");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    public static function getCountryRank($url = false)
    {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[2]/div");

        if ($nodes->item(0)) {
            $rank = self::retInt(strip_tags($nodes->item(0)->nodeValue));
            if ($nodes->item(1) && 0 != $rank) {
                $cntry = @explode("\n", $nodes->item(1)->nodeValue, 3);
                return array(
                    'rank' => $rank,
                    'country' => $cntry[1]
                );
            }
        }

        return parent::noDataDefaultValue();
    }

    public static function getBacklinkCount($url = false)
    {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[3]/div[1]/a");

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            self::retInt($nodes->item(0)->nodeValue);
    }

    public static function getPageLoadTime($url = false)
    {
        $xpath = self::_getXPath($url);
        $nodes = @$xpath->query( "//*[@id='trafficstats_div']/div[4]/div[1]/p" );

        return !$nodes->item(0) ? parent::noDataDefaultValue() :
            strip_tags($nodes->item(0)->nodeValue);
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
    private static function _getXPath($url) {
        $url = parent::getUrl($url);
        if (parent::getLastLoadedUrl() == $url && self::$_xpath) {
            return self::$_xpath;
        }

        $html  = self::_getAlexaPage($url);
        $doc   = parent::_getDOMDocument($html);
        $xpath = parent::_getDOMXPath($doc);

        self::$_xpath = $xpath;

        return $xpath;
    }

    private static function _getAlexaPage($url)
    {
        $domain  = Helper\Url::parseHost($url);
        $dataUrl = sprintf(Config\Services::ALEXA_SITEINFO_URL, $domain);
        $html    = parent::_getPage($dataUrl);
        return $html;
    }

    private static function retInt($str)
    {
        $strim = trim(str_replace(',', '', $str));
        $intStr = 0 < strlen($strim) ? $strim : '0';
        return intval($intStr);
    }
}
