<?php
namespace SEOstats\Services;

/**
 * SEOstats extension for Mozscape (f.k.a. Seomoz) metrics.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz &lt;eyecatchup@gmail.com&gt;
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/11
 */

use SEOstats\Common\SEOstatsException as E;
use SEOstats\SEOstats as SEOstats;
use SEOstats\Config as Config;
use SEOstats\Helper as Helper;

class Mozscape extends SEOstats
{

    protected static $lastLoadedDomain;
    protected static $lastLoadedPage;
    // A normalized 100-point score representing the likelihood
    // of the URL to rank well in search engine results.
    public static function getPageAuthority($url = false)
    {
        $data = static::getCols($url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['upa'];
    }

    // A normalized 100-point score representing the likelihood
    // of the domain of the URL to rank well in search engine results.
    public static function getDomainAuthority($url = false)
    {
        $data = static::getCols(Helper\Url::parseHost($url));
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['pda'];
    }

    // The number of external equity links to the URL.
    // http://apiwiki.moz.com/glossary#equity
    public static function getEquityLinkCount($url = false)
    {
        $data = static::getCols($url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['uid'];
    }

    // The number of links (equity or nonequity or not, internal or external) to the URL.
    public static function getLinkCount($url = false)
    {
        $data = static::getCols($url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['uid'];
    }

    // The normalized 10-point MozRank score of the URL.
    public static function getMozRank($url = false)
    {
        $data = static::getCols($url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['umrp'];
    }

    // The raw MozRank score of the URL.
    public static function getMozRankRaw($url = false)
    {
        $data = static::getCols($url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            number_format($data['umrr'], 16);
    }

    /**
     * Return Link metrics from the (free) Mozscape (f.k.a. Seomoz) API.
     *
     * @access        public
     * @param   cols  string     The bit flags you want returned.
     * @param   url   string     The URL to get metrics for.
     * @return mixed
     */
    public static function getCols($url = false)
    {
        if ('' == Config\ApiKeys::get('MOZSCAPE_ACCESS_ID') ||
            '' == Config\ApiKeys::get('MOZSCAPE_SECRET_KEY')
        ) {
            throw new E('In order to use the Mozscape API, you must obtain
                and set an API key first (see SEOstats\Config\ApiKeys.php).');
        }

        $verbose = getenv('SEOSTATS_VERBOSE');
        $host = Helper\Url::parseHost(parent::getUrl($url));
        if ($verbose) {
            print "[SEOSTATS] Request for url $url\n";
            print "[SEOSTATS] Request host $host\n";
        }

        if (static::$lastLoadedDomain == $host) {
            if ($verbose) {
                print "[SEOSTATS] Return value:\n";
                print_r(static::$lastLoadedPage);
                print "\n";
            }
            return static::$lastLoadedPage;
        }

        $expires = time() + 300;
        $apiEndpoint = sprintf(Config\Services::MOZSCAPE_API_URL,
            urlencode($host),
            Config\ApiKeys::get('MOZSCAPE_ACCESS_ID'),
            $expires,
            urlencode(self::_getUrlSafeSignature($expires))
        );
        if ($verbose) {
            print "[SEOSTATS] Endpoint url:\n";
            print_r($apiEndpoint);
            print "\n";
        }
        $ret = static::_getPage($apiEndpoint);
        if ($verbose) {
            print "[SEOSTATS] Raw responce:\n";
            print_r($ret);
            print "\n";
        }
        static::$lastLoadedDomain = $host;
        static::$lastLoadedPage = (!$ret || empty($ret) || '{}' == (string)$ret)
            ? parent::noDataDefaultValue()
            : Helper\Json::decode($ret, true);
        if ($verbose) {
            print "[SEOSTATS] Return value:\n";
            print_r(static::$lastLoadedPage);
            print "\n";
        }
        return static::$lastLoadedPage;
    }

    private static function _getUrlSafeSignature($expires)
    {
        $data = Config\ApiKeys::get('MOZSCAPE_ACCESS_ID') . "\n{$expires}";
        $sig = self::_hmacsha1($data, Config\ApiKeys::get('MOZSCAPE_SECRET_KEY'));

        return base64_encode($sig);
    }

    private static function _hmacsha1($data, $key)
    {
        // Use PHP's built in functionality if available
        // (~20% faster than the custom implementation below).
        if (function_exists('hash_hmac')) {
            return hash_hmac('sha1', $data, $key, true);
        }

        return self::_hmacsha1Rebuild($data, $key);
    }

    private static function _hmacsha1Rebuild($data, $key)
    {
        $blocksize = 64;
        $hashfunc = 'sha1';

        if (strlen($key) > $blocksize) {
            $key = pack('H*', $hashfunc($key));
        }

        $key = str_pad($key, $blocksize, chr(0x00));
        $ipad = str_repeat(chr(0x36), $blocksize);
        $opad = str_repeat(chr(0x5c), $blocksize);
        $hmac = pack('H*', $hashfunc(($key ^ $opad) .
            pack('H*', $hashfunc(($key ^ $ipad) . $data))));
        return $hmac;
    }
}
