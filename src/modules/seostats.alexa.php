<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for Alexa data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/16
 */

class SEOstats_Alexa extends SEOstats implements services
{
    public function getGlobalRank($url = false)
    {
        $html = self::_alexa($url);

        $doc = new DOMDocument();
        @$doc->loadHtml($html);

        $xpath = new DOMXPath($doc);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[1]/div");

        return self::retInt( strip_tags($nodes->item(0)->nodeValue) );
    }

    public function getCountryRank($url = false)
    {
        $html = self::_alexa($url);

        $doc = new DOMDocument();
        @$doc->loadHtml($html);

        $xpath = new DOMXPath($doc);
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
        $html = self::_alexa($url);

        $doc = new DOMDocument();
        @$doc->loadHtml($html);

        $xpath = new DOMXPath($doc);
        $nodes = @$xpath->query("//*[@id='siteStats']/tbody/tr[1]/td[3]/div[1]/a");

        return self::retInt($nodes->item(0)->nodeValue);
    }

    public function getPageLoadTime($url = false)
    {
        $html = self::_alexa($url);

        $doc = new DOMDocument();
        @$doc->loadHtml($html);

        $xpath = new DOMXPath($doc);
        $nodes = @$xpath->query( "//*[@id='trafficstats_div']/div[4]/div[1]/p" );

        return strip_tags($nodes->item(0)->nodeValue);
    }

    private function _alexa($url)
    {
        $url = false != $url ? $url : self::getUrl();
        $domain = UrlHelper::getHost($url);
        $dataUrl = sprintf(services::ALEXA_SITEINFO_URL, $domain);
        return HttpRequest::sendRequest($dataUrl);
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