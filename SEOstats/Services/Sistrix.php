<?php
namespace SEOstats\Services;

/**
 * SEOstats extension for Sistrix data.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/14
 */

use SEOstats\SEOstats as SEOstats;
use SEOstats\Config as Config;
use SEOstats\Helper as Helper;

class Sistrix extends SEOstats
{
    /**
     * Returns the Sistrix visibility index
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the Sistrix visibility index.
     * @link    http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
     */
    public static function getVisibilityIndex($url = false)
    {
        $url     = parent::getUrl($url);
        $domain  = Helper\Url::parseHost($url);
        $dataUrl = sprintf(Config\Services::SISTRIX_VI_URL, urlencode($domain));

        $html = parent::_getPage($dataUrl);
        @preg_match_all('#<h3>(.*?)<\/h3>#si', $html, $matches);

        return isset($matches[1][0]) ? $matches[1][0] : parent::noDataDefaultValue();
    }
}
