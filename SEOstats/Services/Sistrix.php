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
    public static function getDBs()
    {
      return array(
        'de', //   de – Germany
        'at', //   at – Austria
        'ch', //   ch – Switzerland
        'us', //   us – USA
        'uk', //   uk – England
        'es', //   es – Spain
        'fr', //   fr – France
        'it', //   it – Italy
      );
    }

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

    /**
     * Returns the Sistrix visibility index by using the SISTRIX API
     *
     * @access        public
     * @param   url   string     The URL to check.
     * @return        integer    Returns the Sistrix visibility index.
     * @link    http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
     */
    public static function getVisibilityIndexByAPI($url = false, $db = false)
    {
      if ('' == Config\ApiKeys::SISTRIX_API_ACCESS_KEY) {
        throw new E('In order to use the SISTRIX API, you must obtain
                and set an API key first (see SEOstats\Config\ApiKeys.php).');
        exit(0);
      }

      $db = ($db == false) ? Config\DefaultSettings::SISTRIX_DB : $db;

      $url     = parent::getUrl($url);
      $domain  = Helper\Url::parseHost($url);

      if (false === $domain) {
        self::exc('Invalid domain name.');
      }
      else if (false === $db) {
        self::exc('db');
      }
      else {
        $dataUrl = sprintf(Config\Services::SISTRIX_API_VI_URL, Config\ApiKeys::SISTRIX_API_ACCESS_KEY, urlencode($domain), $db);

        $json = parent::_getPage($dataUrl);

        if(!empty($json)) {
          $json_decoded = (Helper\Json::decode($json, true));
          return $json_decoded['answer'][0]['sichtbarkeitsindex'][0]['value'];
        } else {
          return parent::noDataDefaultValue();
        }
      }
    }

    private static function checkDatabase($db)
    {
      return !in_array($db, self::getDBs()) ? false : $db;
    }

    private static function exc($err)
    {
      $e = ($err == 'db') ? "Invalid database. Choose one of: " .
        substr( implode(", ", self::getDBs()), 0, -2) : $err;
      throw new E($e);
      exit(0);
    }
}
