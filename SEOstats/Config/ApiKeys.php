<?php
namespace SEOstats\Config;

/**
 * Configuration constants for the SEOstats library.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/12/11
 */

/**
 * Client API keys
 * @package    SEOstats
 */
class ApiKeys
{
    // To acquire an API key, visit Google's APIs Console here:
    //      https://code.google.com/apis/console
    // In the Services pane, activate the "PageSpeed Insights API" (not the service!).
    // Next, go to the API Access pane. The API key is near the bottom of that pane,
    // in the section titled "Simple API Access.".
    protected static $GOOGLE_SIMPLE_API_ACCESS_KEY = '';

    // To acquire a Mozscape (f.k.a. SEOmoz) API key, visit:
    //      https://moz.com/products/api/keys
    protected static $MOZSCAPE_ACCESS_ID  = '';
    protected static $MOZSCAPE_SECRET_KEY = '';

    // To acquire a SISTRIX API key, visit:
    //      http://www.sistrix.de
    protected static $SISTRIX_API_ACCESS_KEY = '';

    public static function getGoogleSimpleApiAccessKey() {
        return static::$GOOGLE_SIMPLE_API_ACCESS_KEY;
    }

    public static function setGoogleSimpleApiAccessKey($value) {
        return static::$GOOGLE_SIMPLE_API_ACCESS_KEY = $value;
    }

    public static function getMozscapeAccessId() {
        return static::$MOZSCAPE_ACCESS_ID;
    }

    public static function setMozscapeAccessId($value) {
        return static::$MOZSCAPE_ACCESS_ID = $value;
    }

    public static function getMozscapeSecretKey()
    {
        return static::$MOZSCAPE_SECRET_KEY;
    }

    public static function setMozscapeSecretKey($value)
    {
        return static::$MOZSCAPE_SECRET_KEY = $value;
    }

    public static function getSistrixApiAccessKey()
    {
        return static::$SISTRIX_API_ACCESS_KEY;
    }

    public static function setSistrixApiAccessKey($value)
    {
        return static::$SISTRIX_API_ACCESS_KEY = $value;
    }
}
