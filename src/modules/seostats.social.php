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
        $apiUrl = sprintf(services::GOOGLE_PLUSONE_URL, urlencode($url));
        $htmlData = HttpRequest::sendRequest($apiUrl);
        preg_match_all('#c: (.*?)\.0#si', $htmlData, $matches);
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
        $fqlQuery = sprintf('SELECT total_count, share_count, like_count, comment_count, commentsbox_count, click_count FROM link_stat WHERE url="%s"', $url);
        $apiUrl   = sprintf(services::FB_LINKSTATS_URL, rawurlencode($fqlQuery));
        $jsonData = HttpRequest::sendRequest($apiUrl);
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
        $apiUrl = sprintf(services::TWEETCOUNT_URL, urlencode($url));
        $jsonData = HttpRequest::sendRequest($apiUrl);
        $phpArray = json_decode($jsonData, true);
        return isset($phpArray['count']) ? intval($phpArray['count']) : intval('0');
    }
}

/* End of file seostats.social.php */
/* Location: ./src/modules/seostats.social.php */