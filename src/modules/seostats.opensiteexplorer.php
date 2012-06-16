<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats extension for SEOmoz' OpenSiteExplorer data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/15
 */

class SEOstats_OpenSiteExplorer extends SEOstats implements services
{
    public function getPageMetrics($url = false)
    {
        $url = false != $url ? $url : self::getUrl();
        $dataUrl = sprintf(services::OPENSITEEXPLORER_URL, 'links', '1', $url);

        $html = HttpRequest::sendRequest($dataUrl);

        $doc = new DOMDocument();
        @$doc->loadHtml($html);

        $xpath = new DOMXPath($doc);
        $data = @$xpath->query("//table[@id='page-metrics']/tr[2]/td");

        return array(
            'pageAuthority'      => trim(strip_tags(@$data->item(0)->textContent)),
            'domainAuthority'    => trim(strip_tags(@$data->item(1)->textContent)),
            'linkingRootDomains' => trim(strip_tags(@$data->item(2)->textContent)),
            'totalInboundLinks'  => trim(strip_tags(@$data->item(3)->textContent))
        );
    }
}

/* End of file seostats.opensiteexplorer.php */
/* Location: ./src/modules/seostats.opensiteexplorer.php */