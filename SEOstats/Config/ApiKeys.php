<?php
namespace SEOstats\Config;

/**
 * Configuration constants for the SEOstats library.
 *
 * @package    SEOstats
 * @author     Stephan Schmitz <eyecatchup@gmail.com>
 * @copyright  Copyright (c) 2010 - present Stephan Schmitz
 * @license    http://eyecatchup.mit-license.org/  MIT License
 * @updated    2013/08/14
 */

/**
 * Client API keys
 * @package    SEOstats
 */
interface ApiKeys
{
    // To acquire an API key, visit Google's APIs Console here:
    //      https://code.google.com/apis/console
    // In the Services pane, activate the "PageSpeed Insights API" (not the service!).
    // Next, go to the API Access pane. The API key is near the bottom of that pane,
    // in the section titled "Simple API Access.".
    const GOOGLE_SIMPLE_API_ACCESS_KEY = '';
}
