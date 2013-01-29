<?php  if ( ! defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  URL-String Helper Class
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2012/06/06
 */

class UrlHelper extends SEOstats
{
    public static function getHost($url)
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
    private function isRfc($url)
    {
        if(isset($url) && 1 < strlen($url))
        {
            $host   = self::getHost($url);
            $scheme = parse_url($url, PHP_URL_SCHEME);
            if (false !== $host && in_array(strtolower($scheme), array('http','https')))
            {
                $pattern  = '([A-Za-z][A-Za-z0-9+.-]{1,120}:[A-Za-z0-9/](([A-Za-z0-9$_.+!*,;/?:@&~=-])';
                $pattern .= '|%[A-Fa-f0-9]{2}){1,333}(#([a-zA-Z0-9][a-zA-Z0-9$_.+!*,;/?:@&~=%-]{0,1000}))?)';

                return (bool) preg_match($pattern, $url);
            }
        }
        return false;
    }
}

/* End of file seostats.urlhelper.php */
/* Location: ./src/helper/seostats.urlhelper.php */