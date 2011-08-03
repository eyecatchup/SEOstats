<?php
	/**
	 *  PHP class SEOstats
	 *
	 *  @class      SEOstats_Seomoz
	 *  @package	class.seostats
	 *  @updated	2011/06/11
	 *  @author		Stephan Schmitz <eyecatchup@gmail.com>
	 *  @copyright	2010-present, Stephan Schmitz
	 *  @license	GNU General Public License (GPL)
	 */

class SEOstats_Seomoz extends SEOstats {

	/**
	 * Gets domain and URL authority from SEOmoz.
	 *
	 * @access		private
	 * @link 		http://www.seomoz.org/api		The SEOmoz API
	 * @return		array 					Returns array, containing authority data.
	 */	
	public static function Seomoz_Authority($uri)
	{
		// external helper class
		include_once ('ext/SeoMoz/Authenticator.php');

		$authenticator = new Authenticator();
		$url = urlencode($uri);
		$tmp = SEOstats::cURL('http://lsapi.seomoz.com/linkscape/url-metrics/'.$url.'?'.$authenticator->getAuthenticationStr());

		$data = json_decode($tmp);
		
		$result = array('URL Authority' 	=> $data->upa,
						'URL mozRank' 		=> $data->umrp,
						'Domain Authority' 	=> $data->pda,
						'Domain mozRank' 	=> $data->fmrp
						);
		return $result;
	}

	/**
	 * Gets Backlinkdetails from SEOmoz.
	 * Limited to 25 links per source domain, due to using a free API key.
	 *
	 * @access		private
	 * @link 		http://www.seomoz.org/api		The SEOmoz API
	 * @return		array 					Returns array, containing linkdetails.
	 */
    public static function Seomoz_Links($uri)
	{
		// external helper classes
		include_once('ext/SeoMoz/Authenticator.php');
		include_once('ext/SeoMoz/LinksService.php');
		include_once('ext/SeoMoz/LinksConstants.php');
		
		$authenticator = new Authenticator();
		$linksService = new LinksService($authenticator);
		$result = $linksService->getLinks($uri, LINKS_SCOPE_PAGE_TO_DOMAIN, null, LINKS_SORT_PAGE_AUTHORITY, LINKS_COL_URL, 0 , 25);
		
		return (!empty($result)) ? $result : 'No data available.';
	}
}
?>
