<?php
    /**
     *  PHP class SEOstats
     *
     *  @class      SEOstats_Facebook
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2011/09/15
     *  @author     Florent Cima <florentcm@gmail.com>,
     *              Stephan Schmitz <eyecatchup@gmail.com>
     *  @copyright  2010-present, Stephan Schmitz, Florent Cima
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./seostats.facebook.php
     *  @desc       Child class of SEOstats, extending the main class
     *              by methods for http://www.facebook.com
     *
     *  @changelog
     *  date        author              method: change(s)
     *  2011/08/08  Florent Cima        first commit
     *  2011/09/15  Stephan Schmitz     Updated SEOstats_Facebook::getFacebookShares
     */

class SEOstats_Facebook extends SEOstats 
{
    /**
     * Returns the total amount of Facebook Shares for a single page
     *
     * @access        public
     * @link          http://developers.facebook.com/docs/reference/fql/link_stat/
     * @param   url   string     The URL to check.
     * @return        integer    Returns the total amount of Facebook interactions.
	 */
	public function getUrlStats($url = false)
	{
		$url = false != $url ? $url : self::getUrl();
		$fqlQuery = sprintf('SELECT total_count, share_count, like_count, comment_count, commentsbox_count, click_count FROM link_stat WHERE url="%s"', $url);
		$apiUrl   = sprintf('https://api.facebook.com/method/fql.query?query=%s&format=json', rawurlencode($fqlQuery));
		$jsonData = HttpRequest::sendRequest($apiUrl);
		$phpArray = json_decode($jsonData, true);
		return isset($phpArray[0]) ? $phpArray[0] : false;
	}

	/**
	 * Returns the internal ID, the Facebook Graph API registers to - and uses for identifying - a Domain.
	 *
	 * @access		  public
	 * @link		  http://developers.facebook.com/docs/reference/api/domain/
	 * @param   host  string  	 The URL to get the ID for.
	 * @return        integer    Returns 0, or the unique Domain-ID.
	 */
	public function getDomainId($url = false)
	{
		$url = false != $url ? $url : self::getUrl();
		$host = UrlHelper::getHost($url);
		if (FALSE === $host) {
			return FALSE; } 
		else {
			$url = 'http://graph.facebook.com/?domain=' . $host;
			$jsonData = HttpRequest::sendRequest($url);		
			$phpArray = json_decode($jsonData, true);		
			return isset($phpArray['id']) ? $phpArray['id'] : '';
		}
	}
}