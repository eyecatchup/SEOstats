<?php
    /**
     *  PHP class SEOstats
     *
     *  @class      SEOstats_Guid
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @created    2011/08/09
     *  @author     Sylvain Rocheleau <sylvainrocheleau@gmail.com>
     *  @copyright  2010-present, Stephan Schmitz, Sylvain Rocheleau
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./seostats.guid.php
     *  @desc      	 This module will return a unique GUID. It uses the PHP command uniqid to create a 23 character string appended to the IP address of the client without periods.
     *  @changelog
     *  date        author              change(s)
     *  YYYY/MM/DD  Your name           first commit
     **/

class SEOstats_Guid extends SEOstats {


    function CreateGUID()

        {
        //Append the IP address w/o periods to the front of the unique ID
        $varGUID = str_replace('.', '', uniqid($_SERVER['REMOTE_ADDR'], TRUE));
       
        //Return the GUID as the function value
        return $varGUID;
        }
 }
?>
