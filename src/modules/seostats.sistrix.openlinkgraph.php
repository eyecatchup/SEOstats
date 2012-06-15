<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for OpenLinkGraph data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/15
 */

class SEOstats_OpenLinkGraph extends SEOstats_Sistrix implements services
{
    public function getSummary($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $domain = UrlHelper::getHost($url);

        $total       = HttpRequest::sendRequest( sprintf(services::OPENLINKGRAPH_TOTALS_URL, $domain, '') );
        $hosts       = HttpRequest::sendRequest( sprintf(services::OPENLINKGRAPH_TOTALS_URL, $domain, 'H') );
        $domains     = HttpRequest::sendRequest( sprintf(services::OPENLINKGRAPH_TOTALS_URL, $domain, 'D') );
        $ips         = HttpRequest::sendRequest( sprintf(services::OPENLINKGRAPH_TOTALS_URL, $domain, 'I') );
        $_24networks = HttpRequest::sendRequest( sprintf(services::OPENLINKGRAPH_TOTALS_URL, $domain, 'N') );

        return array(
            'totalInlinks'       => self::strintval($total),
            'inlinkingHosts'     => self::strintval($hosts),
            'inlinkingDomains'   => self::strintval($domains),
            'inlinkingIPs'       => self::strintval($ips),
            'inlinking24Subnets' => self::strintval($_24networks)
        );
    }

    private function strintval($str)
    {
        $str = trim($str);
        if (0 == strlen($str)) {
            return intval('0');
        }
        $str = str_replace('.', '', $str);
        return intval($str);
    }
}

/* End of file seostats.sistrix.openlinkgraph.php */
/* Location: ./src/modules/seostats.sistrix.openlinkgraph.php */