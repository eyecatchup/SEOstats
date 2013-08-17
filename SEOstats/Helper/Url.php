<?php
namespace SEOstats\Helper;

/**
 * URL-String Helper Class
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/02/03
 */

class Url
{
    public static function parseHost($url)
    {
        $url = @parse_url('http://' . preg_replace('#^https?://#', '', $url));
        return (isset($url['host']) && !empty($url['host'])) ? $url['host'] : false;
    }

    /**
     * Validates the initialized object URL syntax.
     *
     * @access        private
     * @param         string        $url        String, containing the initialized object URL.
     * @return        string                    Returns string, containing the validation result.
     */
    public static function isRfc($url)
    {
        if(isset($url) && 1 < strlen($url)) {
            $host   = self::parseHost($url);
            $scheme = strtolower(parse_url($url, PHP_URL_SCHEME));
            if (false !== $host && ($scheme == 'http' || $scheme == 'https')) {
                $pattern  = '([A-Za-z][A-Za-z0-9+.-]{1,120}:[A-Za-z0-9/](([A-Za-z0-9$_.+!*,;/?:@&~=-])';
                $pattern .= '|%[A-Fa-f0-9]{2}){1,333}(#([a-zA-Z0-9][a-zA-Z0-9$_.+!*,;/?:@&~=%-]{0,1000}))?)';
                return (bool) preg_match($pattern, $url);
            }
        }
        return false;
    }
}
