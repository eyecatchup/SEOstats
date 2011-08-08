<?php
    /**
     *  PHP class SEOstats
     *
     *  @class      SEOstats_Facebook
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2011/08/08
     *  @author     Florent Cima <florentcm@gmail.com>
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
     */

class SEOstats_Facebook extends SEOstats {

    /**
     * Returns the total amount of Facebook Shares for a single page
     *
     * @access       public
     * @link         https://graph.facebook.com/
     * @param    q   string             The URL to check.
     * @return       integer            Returns the total amount of Facebook
     *                                  Shares for a single page.
     */
    public static function getFacebookShares($q)
    {
        $url = 'https://graph.facebook.com/?ids=';

        // Parameters
        $url .= urlencode($q);

        //Execution and result of Json in $str
        $str  = SEOstats::cURL($url);

        //Decode Json object
        $data = json_decode($str);

        //Return only number of facebook shares
        $r = $data->$q->shares;
        return ($r != NULL) ? $r : intval('0');
    }
}
?>
