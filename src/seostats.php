<?php
/** SEOstats
 *  ================================================================================
 *  PHP class package to request a bunch of SEO-related data, such as looking up the
 *  visibilty of a URL within organic search results, Pagespeed analysis, the
 *  Google Toolbar PageRank, Page-Authority, Backlink-Details, Traffic Statistics,
 *  social media relevance, comparing competing websites and a lot more.
 *  ================================================================================
 *  @category
 *  @package     SEOstats
 *  @copyright   2010 - present, Stephan Schmitz
 *  @license     http://eyecatchup.mit-license.org
 *  @version     CVS: $Id: SEOstats.php, v2.5.1 Rev 31 2013/01/29 03:57:17 ssc Exp $
 *  @author      Stephan Schmitz <eyecatchup@gmail.com>
 *  @link        https://github.com/eyecatchup/SEOstats/
 *  ================================================================================
 *  LICENSE: Permission is hereby granted, free of charge, to any person obtaining
 *  a copy of this software and associated documentation files (the "Software'),
 *  to deal in the Software without restriction, including without limitation the
 *  rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *    The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY
 *  WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *  ================================================================================
 */

/*
 *---------------------------------------------------------------
 *  SEOSTATS BASE PATH
 *---------------------------------------------------------------
 *  For increased reliability, resolve os-specific system path.
 */

$base_path = __DIR__;
if (realpath( $base_path ) !== false) {
    $base_path = realpath($base_path).'/';
}
$base_path = rtrim($base_path, '/').'/';
$base_path = str_replace('\\', '/', $base_path);

define('SEOSTATSPATH', $base_path);

/*
 *---------------------------------------------------------------
 *  SEOSTATS INTERFACES
 *---------------------------------------------------------------
 */
// Doesn't follow typical include path conventions, but is more convenient for users.
require SEOSTATSPATH . 'interfaces/default-settings.php';
require SEOSTATSPATH . 'interfaces/services.php';
require SEOSTATSPATH . 'interfaces/api-keys.php';
require SEOSTATSPATH . 'interfaces/package.php';

/*
 *---------------------------------------------------------------
 *  SEOSTATS CORE CLASS
 *---------------------------------------------------------------
 */

/**
 * Starting point for the SEOstats class library. Represents a URL resource and has
 * methods for pinging, adding, deleting, committing, optimizing and searching.
 *
 * Example Usage:
 * <code>
 * ...
 * $url = new SEOstats();
 * $url->setUrl('http://www.domain.tld'); //or explicitly new SEOstats('http://www.domain.tld')
 *
 * $url->Google()->getPageRank(); //returns the Google Toolbar PageRank value

 * $url->Google()->getSerps('query string'); //returns the first 100 results for a Google search for 'query string'
 * $url->Google()->getSerps('query string', 500); //returns the first 500 results for a Google search for 'query string'
 *
 * //checks the first 500 results for a Google search for 'query string' for occurrences of the given domain name
 * //and returns an array of matching URL's and their position within serps.
 * $url->Google()->getSerps('query string', 500, 'http://www.domain.tld');
 *
 * ...
 * </code>
 *
 */
class SEOstats implements default_settings, services, api_keys, package
{
    const BUILD_NO = package::VERSION_CODE;

    protected static $_url;

    public function __construct($url = false)
    {
        if (false !== $url) {
            self::setUrl($url);
        }
    }

    public function getUrl()
    {
        return self::$_url;
    }

    public function setUrl($url)
    {
        self::$_url = $url;
    }

    public function Alexa()
    {
        require_once SEOSTATSPATH . 'modules/seostats.alexa.php';
        return new SEOstats_Alexa();
    }

    public function Google()
    {
        require_once SEOSTATSPATH . 'modules/seostats.google.php';
        return new SEOstats_Google();
    }

    public function OpenSiteExplorer()
    {
        require_once SEOSTATSPATH . 'modules/seostats.opensiteexplorer.php';
        return new SEOstats_OpenSiteExplorer();
    }

    public function SEMRush()
    {
        require_once SEOSTATSPATH . 'modules/seostats.semrush.php';
        return new SEOstats_SEMRush();
    }

    public function Sistrix()
    {
        require_once SEOSTATSPATH . 'modules/seostats.sistrix.php';
        return new SEOstats_Sistrix();
    }

    public function Social()
    {
        require_once SEOSTATSPATH . 'modules/seostats.social.php';
        return new SEOstats_Social();
    }
}

// URL-String Helper Class
require SEOSTATSPATH . 'helper/seostats.urlhelper.php';
// HTTP Request Helper Class
require SEOSTATSPATH . 'helper/seostats.httprequest.php';
// SEOstats Exception Class
require SEOSTATSPATH . 'helper/seostats.exception.php';

/* End of file seostats.php */
/* Location: ./src/seostats.php */