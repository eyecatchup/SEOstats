<?php ini_set('max_execution_time', 180);
 
include '../src/class.seostats.php';

try 
{
	$url = new SEOstats($_GET['url']);
	
	echo $url->Alexa_Avg_Load_Time();

} 
catch (SEOstatsException $e) 
{
	/**
	 * Error handling (print it, log it, leave it.. whatever you want.)
	 */
	die($e->getMessage());
}
?>