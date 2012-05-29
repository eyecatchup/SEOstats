<?php
/** PHP Class SEOstats
 *  ================================================================================
 *  PHP class to request a bunch of SEO data, such as Backlinkdetails,
 *  Traffic Statistics, Pageauthority the PageRank and much more.
 *  ================================================================================
 *  @category
 *  @package     SEOstats
 *  @version     CVS: $Id: SEOstats.php,v 2.5.0 2012/05/28 17:01:17 ssc Exp $
 *  @author      Stephan Schmitz <eyecatchup@gmail.com>
 *  @copyright   2010 - present, Stephan Schmitz
 *  @license     http://eyecatchup.mit-license.org
 *  @link        https://github.com/eyecatchup/SEOstats/
 *  ================================================================================
 *  LICENSE: Permission is hereby granted, free of charge, to any person obtaining 
 *  a copy of this software and associated documentation files (the "Software"), 
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
 *  Resolve the system path for increased reliability.
 */	 

$base_path = __DIR__;	
if (realpath( $base_path ) !== false) {
	$base_path = realpath($base_path).'/';
}
$base_path = rtrim($base_path, '/').'/';	
$base_path = str_replace("\\", "/", $base_path);
define('SEOSTATSPATH', $base_path);
	 
/*
 *---------------------------------------------------------------
 *  SEOSTATS INTERFACES
 *---------------------------------------------------------------
 */

require(SEOSTATSPATH ."interfaces/default-settings.php");
require(SEOSTATSPATH ."interfaces/services.php");
require(SEOSTATSPATH ."interfaces/api-keys.php");
require(SEOSTATSPATH ."interfaces/package.php");

/*
 *---------------------------------------------------------------
 *  SEOSTATS CORE CLASS
 *---------------------------------------------------------------
 */

class SEOstats implements default_settings, services, api_keys, package
{
    const BUILD_NO = package::VERSION_CODE;
	
	function __construct($a=NULL) {
		$this->url = NULL;
		if (NULL !== $a) {
			self::setUrl($a);
		}
	}
	
	public function getUrl() {
		return $this->url; }
	public function setUrl($a) {
		$this->url = $a; }
		
	# return alexa object
	public function Alexa() {
		return new SEOstats_Alexa(); }

	# return bing object
	public function Bing() {
		return new SEOstats_Bing(); }

	# return facebook object
	public function Facebook() {
		return new SEOstats_Facebook(); }

	# return google object
	public function Google() {
		return new SEOstats_Google(self::getUrl()); }	

	# return semrush object
	public function SEMRush() {
		return new SEOstats_SEMRush(); }
		
	# return seomoz object
	public function SEOmoz() {
		return new SEOstats_SEOmoz(); }	

	# return twitter object
	public function Twitter() {
		return new SEOstats_Twitter(); }
		
	# return yahoo object
	public function Yahoo() {
		return new SEOstats_Yahoo(); }
}

/**
 *  HTTP Request Helper Class
 */
require(SEOSTATSPATH ."helper/seostats.httprequest.php");
/**
 *  Custom Exception Class
 */
require(SEOSTATSPATH ."helper/seostats.exception.php");

/*
 *---------------------------------------------------------------
 *  SEOSTATS CHILD CLASS
 *---------------------------------------------------------------
 */

require(SEOSTATSPATH ."modules/seostats.alexa.php");
require(SEOSTATSPATH ."modules/seostats.bing.php");
require(SEOSTATSPATH ."modules/seostats.facebook.php");
require(SEOSTATSPATH ."modules/seostats.google.php");
require(SEOSTATSPATH ."modules/seostats.semrush.php");
require(SEOSTATSPATH ."modules/seostats.seomoz.php");
require(SEOSTATSPATH ."modules/seostats.twitter.php");
require(SEOSTATSPATH ."modules/seostats.yahoo.php");

?>
