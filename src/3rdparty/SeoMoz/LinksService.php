<?php
include_once 'Authenticator.php';
include_once 'ConnectionUtil.php';

/**
 * 
 * Service class to call the various methods to 
 * Links API 
 * 
 * Links api returns a set of links to a page or domain.
 * 
 * @author Radeep Solutions
 *
 */
class LinksService 
{
	private $authenticator;
	
	public function __construct($authenticator)
	{
		$this->authenticator = $authenticator;		
	}
	
	/**
	 * This method returns a set of links to a page or domain.
	 * 
	 * @param objectURL
	 * @param scope determines the scope of the Target link, as well as the Source results.
	 * @param filters  filters the links returned to only include links of the specified type.  You may include one or more of the following values separated by '+'
	 * @param sort determines the sorting of the links, in combination with limit and offset, this allows fast access to the top links by several orders:
	 * @param sourceCol specifies data about the source of the link is included
	 * @param offset The start record of the page can be specified using the Offset parameter
	 * @param limit The size of the page can by specified using the Limit parameter.
	 * @return
	 */
	public function getLinks($objectURL, $scope = null, $filters = null, $sort = null, $sourceCol = 0, $offset = -1, $limit = -1)
	{
		$urlToFetch = "http://lsapi.seomoz.com/linkscape/links/" . urlencode($objectURL) . "?" . $this->authenticator->getAuthenticationStr();
		
		if($scope != null)
		{
			$urlToFetch = $urlToFetch . "&Scope=" . $scope;
		}
		if($filters != null)
		{
			$urlToFetch = $urlToFetch . "&Filter=" . $filters;
		}
		if($sort != null)
		{
			$urlToFetch = $urlToFetch . "&Sort=" . $sort;
		}
		if($sourceCol > 0)
		{
			$urlToFetch = $urlToFetch . "&SourceCols=" . $sourceCol;
		}
		if($offset >= 0)
		{
			$urlToFetch = $urlToFetch . "&Offset=" . $offset;
		}
		if($limit >= 0)
		{
			$urlToFetch = $urlToFetch . "&Limit=" . $limit;
		}
		$response = ConnectionUtil::makeRequest($urlToFetch);
		
		return $response;
	}
	
	/**
	 * @return the $authenticator
	 */
	public function getAuthenticator() {
		return $this->authenticator;
	}

	/**
	 * @param $authenticator the $authenticator to set
	 */
	public function setAuthenticator($authenticator) {
		$this->authenticator = $authenticator;
	}
	
}

?>