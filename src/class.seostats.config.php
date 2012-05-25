<?php
    /**
     *  PHP class SEOstats
     *
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2012/01/29
     *  @author     Stephan Schmitz <eyecatchup@gmail.com>
     *  @copyright  2010-present, Stephan Schmitz
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./class.seostats.config.php
     *  @desc       Defines some global used class constants.
     *
     *  @changelog
     *  date        author              change(s)
     *  2011/08/04  Stephan Schmitz     Defined BING_APP_ID constant.
     *  2012/01/28  Stephan Schmitz     Set USE_PAGERANK_CHECKSUM_API to false.
     *  2012/01/29  Stephan Schmitz     Added USE_PAGERANK_CHECKSUM_ALT.
     */

    define('GOOGLE_TLD',
        'com');
    define('USE_PAGERANK_CHECKSUM_API',
        false);
    define('USE_PAGERANK_CHECKSUM_ALT',
        false);

    /**
     *  Optional changes.
     */
    define('YAHOO_APP_ID',
        'oVFwwjnV34GEEZTLB3K_WW1YC9_VysYbCYQ4szoXTAHZscrDvMazUpFR7TR0wchmlA--');

    define('BING_APP_ID',
        'FCB316D398F094C53B8C7AA8DA24B2D58AB520F8');

    define('SEOMOZ_ACCESS_ID',
        'member-881a55bcfb');
    define('SEOMOZ_SECRET_KEY',
        '7145d56a1279285be92e6e4875bba8ee');

    define('ERR_LOG_ENABLED', true);
    define('ERR_LOG_PATH', 'errlog.txt');
?>
