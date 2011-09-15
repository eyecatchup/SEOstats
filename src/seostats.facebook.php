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

class SEOstats_Facebook extends SEOstats {

    /**
     * Returns the total amount of Facebook Shares for a single page
     *
     * @access        public
     * @link          https://graph.facebook.com/
     * @param   q     string     The URL to check.
     * @return        integer    Returns the total amount of Facebook
	 */
    public static function getFacebookShares($q)
    {
        $url = 'http://graph.facebook.com/?id=';

        // Parameters
        $url .= urlencode($q);

        //Execution and result of Json in $str
        $str  = SEOstats::cURL($url);

        //Decode Json object
        $data = json_decode($str);

        //Return only number of facebook shares
        $r = $data->shares;
        return ($r != NULL) ? $r : intval('0');
    }
	
	/**
	 * Returns the internal ID, the Facebook Graph API registers to - and uses for identifying - a Domain.
	 *
	 * @access		  public
	 * @link		  http://developers.facebook.com/docs/reference/api/domain/
	 * @param   host  string  	 The URL to get the ID for.
	 * @return        integer    Returns 0, or the unique Domain-ID.
	 */
	public static function fbGraphApiIdByHost($host)
	{
	    $url = 'http://graph.facebook.com/?domain=' . $host;
		$str = SEOstats::cURL($url);
		
		$obj = json_decode($str);
		
		return (isset($obj->id) && $obj->id != NULL) ? $obj->id : intval('0');
	}
}
?>
