<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for Sistrix data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/15
 */

class SEOstats_Sistrix extends SEOstats implements services
{
    /**
     * Returns the Sistrix visibility index
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the Sistrix visibility index.
     * @link    http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
     */
    public function getVisibilityIndex($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $domain = preg_replace('#^https?://#', '', UrlHelper::getHost($url));
        $apiUrl = sprintf(services::SISTRIX_VI_URL, urlencode($domain));
        $htmlData = HttpRequest::sendRequest($apiUrl);
        preg_match_all('#<h3>(.*?)<\/h3>#si', $htmlData, $matches);
        return isset($matches[1][0]) ? $matches[1][0] : intval('0');
    }
}

/* End of file seostats.sistrix.php */
/* Location: ./src/modules/seostats.sistrix.php */