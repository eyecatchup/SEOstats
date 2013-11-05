<?php
namespace SEOstats\Helper;

/**
 * URL-String Helper Class
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/02/03
 */

class Json
{
   /**
    * Decodes a JSON string into appropriate variable.
    *
    * @param    string  $str    JSON-formatted string
    * @param    boolean $accos  When true, returned objects will be converted into associative arrays.
    * @return   mixed   number, boolean, string, array, or object corresponding to given JSON input string.
    *                   Note that decode() always returns strings in ASCII or UTF-8 format!
    */
    public static function decode($str, $assoc = false)
    {
        if (!function_exists('json_decode')) {
            $j = self::getJsonService();
            return $j->decode($str);
        }
        else {
            return $assoc ? json_decode($str, true) : json_decode($str);
        }
    }

   /**
    * Encodes an arbitrary variable into JSON format.
    *
    * @param    mixed   $var    any number, boolean, string, array, or object to be encoded.
    *                           if var is a string, note that encode() always expects it
    *                           to be in ASCII or UTF-8 format!
    * @return   mixed   JSON string representation of input var or an error if a problem occurs
    */
    public static function encode($var)
    {
        if (!function_exists('json_encode')) {
            $j = self::getJsonService();
            return $j->encode($var);
        }
        else {
            return json_encode($var);
        }
    }

    /**
     * Return a new object of Services_JSON class.
     * Used if native PHP JSON extension is not available.
     */
    private static function getJsonService()
    {
        return new \Services_JSON();
    }

}
