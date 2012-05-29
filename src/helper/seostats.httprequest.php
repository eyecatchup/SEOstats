<?php  if ( ! defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  HTTP Request Helper Class
 *
 *  @package	SEOstats
 *  @author	    Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated	2012/05/28
 */

class HttpRequest extends SEOstats
{
    /**
	 *  HTTP GET/POST request with curl.
	 *  @access    public
	 *  @param     String      $a        The Request URL
	 *  @param     Array       $b        Optional: POST data array to be send.
	 *  @return    Mixed                 On success, returns the response string. 
	 *                                   Else, the the HTTP status code received 
	 *                                   in reponse to the request.
	 */	
    public static function sendRequest($a, $b=FALSE)
    {
        $ch = curl_init($a);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SEOstats '. SEOstats::BUILD_NO .' 
			https://github.com/eyecatchup/SEOstats');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);		
		if (FALSE !== $b) {
			// Parameter key value pair array expected
			// eg array('param' => 'value', 'filter' => 'none')
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($b));
		}
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode == 200) {
            return $response;
        }
        else { return $httpCode; }
    }

    /**
     * HTTP HEAD request with curl.
     *
     * @access        private
     * @param         String        $a        The request URL
     * @return        Integer                 Returns the HTTP status code received in 
	 *                                        response to a GET request of the input URL.
     */
    public static function getHttpCode($a)
    {
        $ch = curl_init($a);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SEOstats '. SEOstats::BUILD_NO .' 
			https://github.com/eyecatchup/SEOstats');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return (int) $httpCode;
    }
}

/* End of file seostats.httprequest.php */
/* Location: ./src/helper/seostats.httprequest.php */
?>