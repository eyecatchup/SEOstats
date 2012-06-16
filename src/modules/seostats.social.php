<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for Social-Media data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/15
 */

class SEOstats_Social extends SEOstats implements services
{
    /**
     * Returns the total count of Google+ Plus Ones
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of Plus Ones for a URL.
     */
    public function getGoogleShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::GOOGLE_PLUSONE_URL, urlencode($url));

        $html = HttpRequest::sendRequest($dataUrl);
        preg_match_all('#c: (.*?)\.0#si', $html, $matches);

        return isset($matches[1][0]) ? intval($matches[1][0]) : intval('0');
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
    public function getFacebookShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $fql = sprintf('SELECT total_count, share_count, like_count, comment_count, commentsbox_count, click_count FROM link_stat WHERE url="%s"', $url);
        $dataUrl = sprintf(services::FB_LINKSTATS_URL, rawurlencode($fql));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode($jsonData, true);

        return isset($phpArray[0]) ? $phpArray[0] : intval('0');
    }

    /**
     * Returns the total count of Twitter mentions
     *
     * @access       public
     * @param   url  string             The URL to check.
     * @return       integer            Returns the total count of Twitter mentions for a URL.
     * @link         https://dev.twitter.com/discussions/5653#comment-11514
     */
    public function getTwitterShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::TWEETCOUNT_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode($jsonData, true);

        return isset($phpArray['count']) ? intval($phpArray['count']) : intval('0');
    }

    /**
     * Returns the total count of URL shares via Delicious
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public function getDeliciousShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::DELICIOUS_INFO_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode($jsonData, true);

        return isset($phpArray[0]['total_posts']) ? intval($phpArray[0]['total_posts']) : intval('0');
    }

    /**
     * Returns the Top10 tags from Delicious
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        array      Returns the top ten delicious tags for a URL (if exist; else an empty array).
     */
    public function getDeliciousTopTags($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::DELICIOUS_INFO_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode($jsonData, true);

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
    public function getDiggShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::DIGG_INFO_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode(substr($jsonData, 2, -2), true);

        return isset($phpArray['diggs']) ? intval($phpArray['diggs']) : intval('0');
    }

    /**
     * Returns the total count of URL shares via LinkedIn
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public function getLinkedInShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::LINKEDIN_INFO_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode(substr($jsonData, 2, -2), true);

        return isset($phpArray['count']) ? intval($phpArray['count']) : intval('0');
    }

    /**
     * Returns the total count of URL shares via Pinterest
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public function getPinterestShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::PINTEREST_INFO_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode(substr($jsonData, 2, -1), true);

        return isset($phpArray['count']) ? intval($phpArray['count']) : intval('0');
    }

    /**
     * Returns the total count of URL shares via StumpleUpon
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public function getStumbleUponShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::STUMBLEUPON_INFO_URL, urlencode($url));

        $jsonData = HttpRequest::sendRequest($dataUrl);
        $phpArray = json_decode($jsonData, true);

        return isset($phpArray['result']['in_index']) && true == $phpArray['result']['in_index']
            ? intval($phpArray['result']['views']) : intval('0');
    }

    /**
     * Returns the total count of URL shares via VKontakte
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total count of URL shares.
     */
    public function getVKontakteShares($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::VKONTAKTE_INFO_URL, urlencode($url));

        $htmlData = HttpRequest::sendRequest($dataUrl);
        preg_match_all('#^VK\.Share\.count\(1, (\d+)\);$#si', $htmlData, $matches);

        return isset($matches[1][0]) ? intval($matches[1][0]) : intval('0');
    }
}

/* End of file seostats.social.php */
/* Location: ./src/modules/seostats.social.php */