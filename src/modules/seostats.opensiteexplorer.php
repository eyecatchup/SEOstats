<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 *  SEOstats extension for SEOmoz' OpenSiteExplorer data.
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2013/01/29
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
		
		$data = $doc->getElementsByTagName('td');

        return array(
			'domainAuthority'    => trim(strip_tags($data->item(0)->textContent)),
            'pageAuthority'      => trim(strip_tags($data->item(1)->textContent)),          
            'linkingRootDomains' => trim(strip_tags($data->item(2)->textContent)),
            'totalInboundLinks'  => trim(strip_tags($data->item(3)->textContent))
        );
    }
}

/* End of file seostats.opensiteexplorer.php */
/* Location: ./src/modules/seostats.opensiteexplorer.php */