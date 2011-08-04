<?php
    /**
     *  PHP class SEOstats
     *
     *  @package    class.seostats
     *  @link       https://github.com/eyecatchup/SEOstats/
     *  @updated    2011/08/04
     *  @author     Stephan Schmitz <eyecatchup@gmail.com>
     *  @copyright  2010-present, Stephan Schmitz
     *  @license    GNU General Public License (GPL)
     *
     *  @filename   ./modules.php
     *  @desc       Includes all SEOstats child classes.
     *
     *  @changelog
     *  date        author              change(s)
     *  2011/08/04  Stephan Schmitz     included seostats.bing.php
     */

include_once('seostats.google.php');
include_once('seostats.yahoo.php');
include_once('seostats.bing.php');
include_once('seostats.majesticseo.php');
include_once('seostats.seomoz.php');
include_once('seostats.alexa.php');
include_once('seostats.exception.php');
?>
