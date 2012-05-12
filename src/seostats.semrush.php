<?php
    /**
     *  PHP class SEOstats
     *
     *  @class      SEOstats_SEMRush
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2012/05/12
     *  @author     Stephan Schmitz <eyecatchup@gmail.com>
     *  @copyright  2010-present, Stephan Schmitz
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./seostats.semrush.php
     *  @desc       Child class of SEOstats, extending the main class
     *              by methods for http://www.semrush.com
     *
     *  @changelog
     *  date        author              method: change(s)
     *  2012/05/12  Stephan Schmitz     initial commit
     */

class SEOstats_SEMRush extends SEOstats {

    /**
     * Returns the SEMRush main report data.
     * (Only main report is public available.)
     *
     * @access       public
     * @param   host string             Domain name only, eg. "ebay.com" (/wo quotes).
     * @param   db   string             Optional: The database to use. Valid values are:
     *                                  au, br, ca, de, es, fr, it, ru, uk, us, us.bing (us is default)
     * @return       array              Returns an array containing the main report data.
     * @link         http://www.semrush.com/api.html
     */
    public static function semrushMainReport($host, $db="us")
    {
        $domain_name = $host;
        $dbs = array(
            "au",     # Google.com.au (Australia)
            "br",     # Google.com.br (Brazil)
            "ca",     # Google.ca (Canada)
            "de",     # Google.de (Germany)
            "es",     # Google.es (Spain)
            "fr",     # Google.fr (France)
            "it",     # Google.it (Italy)
            "ru",     # Google.ru (Russia)
            "uk",     # Google.co.uk (United Kingdom)
            "us",     # Google.com (United States)
            "us.bing" # Bing.com
        );
        if(!in_array($db,$dbs)) {
            $err_msg = "Invalid database. Choose one of: " . substr( implode(", ", $dbs), 0, -2);
            throw new SEOstatsException($err_msg);
            exit(0);
        }
        else {
            // API request url
            $api_uri = "http://$db.api.semrush.com/" .
                       "?action=report" .
                       "&type=domain_rank" .
                       "&key=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX" .
                       "&export=api" .
                       "&export_columns=Dn,Rk,Or,Ot,Oc,Ad,At,Ac" .
                       "&domain=" . $domain_name;
            // Send the request
            $str = SEOstats::cURL($api_uri);

            // We want the 2nd line of the result string.
            // RegExp matches line breaks on Windows, Mac and Linux.
            $tmp = @preg_split('/$\R?^/m', $str);

            if(!array($tmp) || sizeof($tmp) == 0) {
                return "No data available for $domain_name.";
            }
            else {
                $names = explode(";", $tmp[0]);
                $values = explode(";", $tmp[1]);
                $keys = array("Dn","Rk","Or","Ot","Oc","Ad","At","Ac");
                $descrs = array(
                    "The requested site name.",
                    "Rating of sites by the number of visitors coming from the first 20 search results.",
                    "Number of Keywords this site has in the TOP20 organic results.",
                    "Estimated number of visitors coming from the first 20 search results (per month).",
                    "Estimated cost of purchasing the same number of visitors through Ads.",
                    "Number of Keywords this site has in the TOP20 Ads results.",
                    "Estimated number of visitors coming from Ads (per month).",
                    "Estimated expenses the site has for advertising in Ads (per month)."
                );
                $ret = array();
                for($i=0;$i<=7;$i++) {
                    $ret[] = array(
                        "name" => $names[$i],
                        "value" => $values[$i],
                        "semrush_key" => $keys[$i],
                        "description" => $descrs[$i]
                    );
                }
                return $ret;
            }
        }
    }

}
?>
