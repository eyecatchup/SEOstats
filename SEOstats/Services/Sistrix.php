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

use SEOstats\Common\SEOstatsException as E;
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
      self::checkAPIKey();

      if(self::checkAPICredits()) {
        $db = ($db == false) ? Config\DefaultSettings::SISTRIX_DB : $db;

        $url     = parent::getUrl($url);
        $domain  = Helper\Url::parseHost($url);
        $database = self::checkDatabase($db);

        if (false === $domain) {
          self::exc('Invalid domain name.');
        }
        else if (false === $database) {
          self::exc('db');
        }
        else {
          $dataUrl = sprintf(Config\Services::SISTRIX_API_VI_URL, Config\ApiKeys::SISTRIX_API_ACCESS_KEY, urlencode($domain), $database);

          $json = parent::_getPage($dataUrl);

          if(!empty($json)) {
            $json_decoded = (Helper\Json::decode($json, true));
            return $json_decoded['answer'][0]['sichtbarkeitsindex'][0]['value'];
          } else {
            return parent::noDataDefaultValue();
          }
        }
      } else {
        // not enough api credits
        self::exc('Not enough API credits');
      }
    }

    public static function getAPICredits() {
      self::checkAPIKey();

      $dataUrl = sprintf(Config\Services::SISTRIX_API_CREDITS_URL, Config\ApiKeys::SISTRIX_API_ACCESS_KEY);

      $json = parent::_getPage($dataUrl);

      if(!empty($json)) {
        $json_decoded = (Helper\Json::decode($json, true));
        return $json_decoded['answer'][0]['credits'][0]['value'];
      } else {
        return parent::noDataDefaultValue();
      }
    }

    private static function checkAPICredits() {
      return self::getAPICredits() > 0;
    }

    private static function checkAPIKey() {
      if ('' == Config\ApiKeys::SISTRIX_API_ACCESS_KEY) {
        throw new E('In order to use the SISTRIX API, you must obtain
                and set an API key first (see SEOstats\Config\ApiKeys.php).');
        exit(0);
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
