<?php ini_set('max_execution_time', 180);
 
include '../src/class.seostats.php';

try 
{
	$url = new SEOstats($_GET['url']);
	$url->print_array('All_Totals');

} 
catch (SEOstatsException $e) 
{
	/**
	 * Error handling (print it, log it, leave it.. whatever you want.)
	 */
	die($e->getMessage());
}
?>