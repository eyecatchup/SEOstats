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
 * Client API keys
 */
class ApiKeys extends ConfigAbstract
{
    protected static $config = [
        'GOOGLE_SIMPLE_API_ACCESS_KEY' => '',
        'MOZSCAPE_ACCESS_ID' => '',
        'MOZSCAPE_SECRET_KEY' => '',
        'SISTRIX_API_ACCESS_KEY' => '',
    ];
}
