<?php
namespace SEOstats\Services;

/**
 *  SEOstats extension for Social-Media data.
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

class Social extends SEOstats
{
    /**
     * Returns the total count of Google+ Plus Ones
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of Plus Ones for a URL.
     */
    public static function getGoogleShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::GOOGLE_PLUSONE_URL, urlencode($url));
        $html    = parent::_getPage($dataUrl);
        @preg_match_all('#c: (.*?)\.0#si', $html, $matches);

        return isset($matches[1][0]) ? intval($matches[1][0]) : parent::noDataDefaultValue();
    }

    /**
     * Returns Facebook Sharing data
     *
     * @access        public
     * @link          http://developers.facebook.com/docs/reference/fql/link_stat/
     * @param   url   string     The URL to check.
     * @return        array      Returns an array of total counts for 1. all Facebook interactions,
     *                           2. FB shares, 3. FB likes, 4. FB comments and 5. outgoing clicks for a URL.
     */
    public static function getFacebookShares($url = false)
    {
        $url     = parent::getUrl($url);
        $fql     = sprintf('SELECT total_count, share_count, like_count, comment_count, commentsbox_count, click_count FROM link_stat WHERE url="%s"', $url);
        $dataUrl = sprintf(Config\Services::FB_LINKSTATS_URL, rawurlencode($fql));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode($jsonData, true);

        return isset($phpArray[0]) ? $phpArray[0] : parent::noDataDefaultValue();
    }

    /**
     * Returns the total count of Twitter mentions
     *
     * @access       public
     * @param   url  string             The URL to check.
     * @return       integer            Returns the total count of Twitter mentions for a URL.
     * @link         https://dev.twitter.com/discussions/5653#comment-11514
     */
    public static function getTwitterShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::TWEETCOUNT_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode($jsonData, true);

        return isset($phpArray['count']) ? intval($phpArray['count']) : parent::noDataDefaultValue();
    }

    /**
     * Returns the total count of URL shares via Delicious
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public static function getDeliciousShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::DELICIOUS_INFO_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode($jsonData, true);

        return isset($phpArray[0]['total_posts']) ? intval($phpArray[0]['total_posts']) : parent::noDataDefaultValue();
    }

    /**
     * Returns the Top10 tags from Delicious
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        array      Returns the top ten delicious tags for a URL (if exist; else an empty array).
     */
    public static function getDeliciousTopTags($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::DELICIOUS_INFO_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode($jsonData, true);

        $ret = array();
        if (isset($phpArray[0]['top_tags']) && 0 < sizeof($phpArray[0]['top_tags'])) {
            foreach($phpArray[0]['top_tags'] as $k => $v) {
                $ret[] = $k;
            }
        }
        return $ret;
    }

    /**
     * Returns the total count of URL shares via Digg
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public static function getDiggShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::DIGG_INFO_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode(substr($jsonData, 2, -2), true);

        return isset($phpArray['diggs']) ? intval($phpArray['diggs']) : parent::noDataDefaultValue();
    }

    /**
     * Returns the total count of URL shares via LinkedIn
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public static function getLinkedInShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::LINKEDIN_INFO_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode(substr($jsonData, 2, -2), true);

        return isset($phpArray['count']) ? intval($phpArray['count']) : parent::noDataDefaultValue();
    }

    /**
     * Returns the total count of URL shares via Pinterest
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public static function getPinterestShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::PINTEREST_INFO_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode(substr($jsonData, 2, -1), true);

        return isset($phpArray['count']) ? intval($phpArray['count']) : parent::noDataDefaultValue();
    }

    /**
     * Returns the total count of URL shares via StumpleUpon
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public static function getStumbleUponShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::STUMBLEUPON_INFO_URL, urlencode($url));

        $jsonData = parent::_getPage($dataUrl);
        $phpArray = Helper\Json::decode($jsonData, true);

        return isset($phpArray['result']['in_index']) && true == $phpArray['result']['in_index']
            ? intval($phpArray['result']['views']) : parent::noDataDefaultValue();
    }

    /**
     * Returns the total count of URL shares via VKontakte
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public static function getVKontakteShares($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::VKONTAKTE_INFO_URL, urlencode($url));

        $htmlData = parent::_getPage($dataUrl);
        @preg_match_all('#^VK\.Share\.count\(1, (\d+)\);$#si', $htmlData, $matches);

        return isset($matches[1][0]) ? intval($matches[1][0]) : parent::noDataDefaultValue();
    }
}
