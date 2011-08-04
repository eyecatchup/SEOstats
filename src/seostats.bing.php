<?php
    /**
     *  PHP class SEOstats
     *
     *  @class      SEOstats_Bing
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2011/04/29
     *  @author     Chris Alvares <mail@chrisalvares.com>
     *  @copyright  2010-present, Chris Alvares/Stephan Schmitz
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./seostats.bing.php
     *  @desc       Child class of SEOstats, extending the main class
     *              by methods for http://www.bing.com
     *
     *  @changelog
     *  date        author              method: change(s)
     */

class SEOstats_Bing extends SEOstats {

    /**
     * Returns the total amount of pages for a Domain indexed at Bing
     *
     * @access       public
     * @link         http://www.bing.com:80/developers/        Get your own application ID here
     * @return       integer                 Returns the total amount of pages for a Domain indexed at Bing
     */
    public static function bingSiteIndexTotal($uri)
    {
        $url = 'http://api.bing.net/json.aspx?&Version=2.2&Market=en-US&Sources=web&Web.Count=1&JsonType=function';

        $url .= '&AppId=' . BING_APP_ID;
        $url .= '&Query=site:' . urlencode($uri);

        $str  = SEOstats::cURL($url);

        $str = str_ireplace("function BingGetResponse(){return", "", $str);
        $str = str_ireplace("; /* pageview_candidate */}", "", $str);

        $data = json_decode($str);
        return $data->SearchResponse->Web->Total;
    }

    /**
     * Returns array, containing details about the pages indexed at Bing
     *
     * @access       public
     * @link         http://www.bing.com:80/developers/        Get your own application ID here
     * @return       array                     Returns array, containing details about the pages indexed at Bing
     */
    public static function bingSiteindexArray($uri)
    {
        $url  = 'http://api.bing.net/json.aspx?&Version=2.2&Market=en-US&Sources=web&Web.Count=50&JsonType=function';
        $url .= '&AppId=' . BING_APP_ID;
        $url .= '&Query=site:' . urlencode($uri);

        $str  = SEOstats::cURL($url);
        $str  = str_ireplace("function BingGetResponse(){return", "", $str);
        $str  = str_ireplace("; /* pageview_candidate */}", "", $str);

        $data = json_decode($str);

        $result = array();
        foreach($data->SearchResponse->Web->Results as $entry)
        {
            $result[] = array(
                'Title'     => $entry->Title,
                'URL'       => $entry->DisplayUrl,
                'Click URL' => $entry->Url
            );
        }
        return $result;
    }
}
?>
