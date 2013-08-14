<?php
namespace SEOstats\Services;

/**
 * SEOstats extension for SEOmoz' OpenSiteExplorer data.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/14
 */

use SEOstats\SEOstats as SEOstats;
use SEOstats\Config as Config;

class OpenSiteExplorer extends SEOstats
{
    public static function getPageMetrics($url = false)
    {
        $url     = parent::getUrl($url);
        $dataUrl = sprintf(Config\Services::OPENSITEEXPLORER_URL, 'links', '1', $url);

        $html = parent::_getPage($dataUrl);
        $doc  = parent::_getDOMDocument($html);
        $data = @$doc->getElementsByTagName('td');

        if ($data->item(0)) {
            return array(
                'domainAuthority'    => trim(strip_tags($data->item(0)->textContent)),
                'pageAuthority'      => trim(strip_tags($data->item(1)->textContent)),
                'linkingRootDomains' => trim(strip_tags($data->item(2)->textContent)),
                'totalInboundLinks'  => trim(strip_tags($data->item(3)->textContent))
            );
        }
        else {
            return parent::noDataDefaultValue();
        }
    }
}
