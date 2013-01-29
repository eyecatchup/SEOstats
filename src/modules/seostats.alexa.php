<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for Alexa data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2013/01/18
 */

class SEOstats_Alexa extends SEOstats implements services
{
    /**
     * Used for cache
     * @var DOMXPath
     */
    private $_xpath = NULL;
    
    /**
     * Used for cache
     * @var DOMXPath
     */
    private $_lastLoadedUrl = NULL;
    
    /**
     * The global rank is compute as an average over three months
     * 
     * @return int
     */
    public function getGlobalRank($url = false)
    {
        return $this->getQuarterRank($url);
    }
    
    /**
     * Get the average rank over the week
     * @return int
     */
    public function getWeekRank($url = false)
    {
        $xpath = $this->_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[2]/td[1]");
        
        return self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }
    
    /**
     * Get the average rank over the week
     * @return int
     */
    public function getMonthRank($url = false)
    {
        $xpath = $this->_getXPath($url);
        $nodes = @$xpath->query("//*[@id='rank']/table/tr[3]/td[1]");
        
        return self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }
    
    
    public function getQuarterRank($url = false) {
        $xpath = $this->_getXPath($url);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[1]/div");

        return self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    public function getCountryRank($url = false)
    {
        $xpath = $this->_getXPath($url);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[2]/div");

        $rank = self::retInt( strip_tags($nodes->item(0)->nodeValue) );
        if (0 != $rank) {
            $cntry = @explode("\n", $nodes->item(1)->nodeValue, 3);
            return array(
                'rank' => $rank,
                'country' => $cntry[1]
            );
        } else {
            return 'No data available.';
        }
    }

    public function getBacklinkCount($url = false)
    {
        $xpath = $this->_getXPath($url);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[3]/div[1]/a");

        return self::retInt($nodes->item(0)->nodeValue);
    }

    public function getPageLoadTime($url = false)
    {
        $xpath = $this->_getXPath($url);
        $nodes = @$xpath->query( "//*[@id='trafficstats_div']/div[4]/div[1]/p" );

        return strip_tags($nodes->item(0)->nodeValue);
    }

    /**
     * @access        public
     * @param         integer    $type      Specifies the graph type. Valid values are 1 to 6.
     * @param         integer    $width     Specifies the graph width (in px).
     * @param         integer    $height    Specifies the graph height (in px).
     * @param         integer    $period    Specifies the displayed time period. Valid values are 1 to 12.
     * @return        string                Returns a string, containing the HTML code of an image, showing Alexa Statistics as Graph.
     */
    public function getTrafficGraph($type = 1, $url = false, $w = 660, $h = 330, $period = 1, $html = true)
    {
        $url = false != $url ? $url : self::getUrl();
        $domain = UrlHelper::getHost($url);

        switch($type)
        {
            case 1: $gtype = 't'; break;
            case 2: $gtype = 'p'; break;
            case 3: $gtype = 'u'; break;
            case 4: $gtype = 's'; break;
            case 5: $gtype = 'b'; break;
            case 6: $gtype = 'q'; break;
            default:break;
        }

        $imgUrl = sprintf(services::ALEXA_GRAPH_URL, $gtype, $w, $h, $period, $domain);

        if (true != $html) {
            return $imgUrl;
        }
        else {
            $imgTag = '<img src="%s" width="%s" height="%s" alt="Alexa Statistics Graph for %s"/>';
            return sprintf($imgTag, $imgUrl, $w, $h, $domain);
        }
    }
    
    /**
     * @return DOMXPath
     */
    private function _getXPath($url) {
        $url = $this->_getUrl($url);
        if ($this->_lastLoadedUrl == $url) {
            return $this->_xpath;
        }
        $html = $this->_getAlexaPage($url);
        $doc = $this->_getDOMDocument($html);
        $xpath = new DOMXPath($doc);
        $this->_lastLoadedUrl = $url;
        $this->_xpath = $xpath;
        
        return $xpath;
    }
    
    /**
     * @return DOMDocument
     */
    private function _getDOMDocument($html) {
        $doc = new DOMDocument();
        @$doc->loadHtml($html);
        return $doc;
    }

    private function _getAlexaPage($url)
    {
        $domain = UrlHelper::getHost($url);

        $dataUrl = sprintf(services::ALEXA_SITEINFO_URL, $domain);

        return HttpRequest::sendRequest($dataUrl);
    }
    
    /**
     * Ensure the URL is set, return default otherwise
     * @return string
     */
    private function _getUrl($url) {
        $url = false != $url ? $url : self::getUrl();
        return $url;
    }

    private function retInt($str)
    {
        $strim = trim(str_replace(',', '', $str));
        $intStr = 0 < strlen($strim) ? $strim : '0';

        return intval($intStr);
    }
}

/* End of file seostats.alexa.php */
/* Location: ./src/modules/seostats.alexa.php */