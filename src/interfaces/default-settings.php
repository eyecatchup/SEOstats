<?php  if ( ! defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  Default client settings
 *
 *  @package    SEOstats
 *  @updated    2013/01/29
 */

interface default_settings
{
    const GOOGLE_TLD = 'com';

    // Note: Google search results, doesn't matter which tld you request, vary depending on
    // the value sent for the HTTP header attribute 'Accept-Language'!  Ex:

    // I am from Germany. Even if i use the ncr (no country redirect), the search results
    // i get in response to any search on google.com, will be localized to German, due to the fact
    // my browser sent a Accept-Language header value of 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3'.
    // On the other side, if i change my browser settings to send a value of 'en-us;q=0.8,en;q=0.3',
    // all my searches on google.de (the german Google page) will be localized English.

    // So, if you want to get the same results, as you see them for a search using your browser,
    // you need to set the value below to be the same used by your browser!
    const HTTP_HEADER_ACCEPT_LANGUAGE = 'en-us;q=0.8,en;q=0.3';

    // For curl instances: Whether to allow Google to store cookies, or not.
    const ALLOW_GOOGLE_COOKIES = '0';

	// Choose the local SEMRush database to use.
    const SEMRUSH_DB = 'de';

    const EXPORT_DIR = 'data/';
}

/* End of file default-settings.php */
/* Location: ./src/interfaces/default-settings.php */