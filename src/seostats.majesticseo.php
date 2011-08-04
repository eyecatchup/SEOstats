<?php
    /**
     *  PHP class SEOstats
     *
     *  @class      SEOstats_Majesticseo
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2011/08/04
     *  @author     Stephan Schmitz <eyecatchup@gmail.com>,
     *              Florent Cima    <florentcm@gmail.com>
     *  @copyright  2010-present, Stephan Schmitz, Florent Cima
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./seostats.majesticseo.php
     *  @desc       Child class of SEOstats, extending the main class
     *              by methods for http://www.majesticseo.com
     *
     *  @changelog
     *  date        author              method: change(s)
     *  2011/08/04  Florent Cima        report: Added support for folder URL's.
     */

class SEOstats_Majesticseo extends SEOstats {

    /**
     * Helper. Gets the Majesticseo's free report webpage.
     *
     * @access      private
     * @return      string              String, containing the curl result of the the Majesticseo webpage.
     */
    public static function report($uri, $i)
    {
        $uri = str_replace('http://', '', $uri);
        $folder = false;
        $tmp = SEOstats::cURL( 'http://www.majesticseo.com/reports/site-explorer/summary/'.$uri );

        if((ereg('/',$uri)) && (strpos($uri,'/') != strlen($uri)-1))
        {
            $folder = true;
            $uri = str_replace('/', '%2F', $uri);
            $tmp = SEOstats::cURL( 'http://www.majesticseo.com/reports/site-explorer?folder=&q='.$uri );
        }

        $dom = new DOMDocument();
        @$dom->loadHTML($tmp);
        $xpath = new DOMXPath($dom);

        $p = $xpath->query("//table//tr//td//p");

        if($i==1 || $i==3)
        {
            $x = ($folder == true) ? $i+4 : $i;
            $r = str_replace(",", "", trim($p->item($x)->textContent));
        }
        else
        {
            switch($i)
            {
                    case 4: $regex = ' Referring IP addresses'; break;
                    case 5: $regex = ' are Class C subnets'; break;
                    case 6: $regex = ' Indexed URLs'; break;
                    default:break;
            }
            foreach ( $p as $paragraph )
            {
                if(preg_match('#'.$regex.'#i',$paragraph->textContent))
                {
                    $r = str_replace($regex,'',$paragraph->textContent);
                }
            }
        }
        return ($r != '') ? $r : intval('0');
    }
}
?>
