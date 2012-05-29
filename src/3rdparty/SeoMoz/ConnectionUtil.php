<?php

/**
 * 
 * Utility Class to make a GET HTTP connection
 * to the given url and pass the output
 * 
 * @author Radeep Solutions
 *
 */
class ConnectionUtil 
{
	const CURL_CONNECTION_TIMEOUT = 120;
	
	/**
	 * 
	 * Method to make a GET HTTP connecton to 
	 * the given url and return the output
	 * 
	 * @param urlToFetch url to be connected
	 * @return the http get response
	 */
	public static function makeRequest($urlToFetch)
	{
		$curl_handle = curl_init();

		curl_setopt($curl_handle, CURLOPT_URL, "$urlToFetch");
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, ConnectionUtil::CURL_CONNECTION_TIMEOUT);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
				
		$buffer = curl_exec($curl_handle);
		//var_dump($buffer);
		curl_close($curl_handle);
		
		$arr = json_decode($buffer);
		
		return $arr;
	}
	
}

?>