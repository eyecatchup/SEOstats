<?php if (!defined('SEOSTATSPATH')) exit('No direct access allowed!');
/**
 *  SEOstats provider list and service URLs
 *
 *  @package    SEOstats
 *  @author     Stephan Schmitz <eyecatchup@gmail.com>
 *  @updated    2013/01/29
 */

interface services
{
    const PROVIDER = '["alexa","google","ose","semrush","seomoz","sistrix","social","yahoo"]';

    // Url to get Alexa stats from
    const ALEXA_SITEINFO_URL = 'http://www.alexa.com/siteinfo/%s';
    const ALEXA_GRAPH_URL = 'http://traffic.alexa.com/graph?&o=f&c=1&y=%s&b=ffffff&n=666666&w=%s&h=%s&r=%sm&u=%s';

    // Url to get the Sistrix visibility index from
    // @link http://www.sistrix.com/blog/870-sistrix-visibilityindex.html
    const SISTRIX_VI_URL = 'http://www.sichtbarkeitsindex.de/%s';

    const SEMRUSH_BE_URL = 'http://%s.backend.semrush.com/?action=report&type=%s&domain=%s';
    const SEMRUSH_GRAPH_URL = 'http://semrush.com/archive/graphs.php?domain=%s&db=%s&type=%s&w=%s&h=%s&lc=%s&dc=%s&l=%s';
    const SEMRUSH_WIDGET_URL = 'http://widget.semrush.com/widget.php?action=report&type=%s&db=%s&domain=%s';

    // Url to get Google search total counts from
    const GOOGLE_APISEARCH_URL = 'http://ajax.googleapis.com/ajax/services/search/web?v=1.0&rsz=%s&q=%s';

    // Url to get the Pagespeed analysis from
    const GOOGLE_PAGESPEED_URL = 'https://developers.google.com/_apps/pagespeed/run_pagespeed?url=%s&format=json';

    // Url to get the Plus One count from
    const GOOGLE_PLUSONE_URL = 'https://plusone.google.com/u/0/_/+1/fastbutton?count=true&url=%s';

    // Open Site Explorer base Url
    const OPENSITEEXPLORER_URL = 'http://www.opensiteexplorer.org/%s.html?group=0&page=%s&site=%s&sort=';

    // Url to get Facebook link stats from
    const FB_LINKSTATS_URL = 'https://api.facebook.com/method/fql.query?query=%s&format=json';

    // Url to get Twitter mentions from
    // @link https://dev.twitter.com/discussions/5653#comment-11514
    const TWEETCOUNT_URL = 'http://cdn.api.twitter.com/1/urls/count.json?url=%s';

    // Url to get share count via Delicious from
    const DELICIOUS_INFO_URL = 'http://feeds.delicious.com/v2/json/urlinfo/data?url=%s';

    // Url to get share count via Digg from
    // @link http://widgets.digg.com/buttons.js
    const DIGG_INFO_URL = 'http://widgets.digg.com/buttons/count?url=%s&cb=_';

    // Url to get share count via LinkedIn from
    // Replaces deprecated share count Url "http://www.linkedin.com/cws/share-count?url=%s".
    // @link http://developer.linkedin.com/forum/discrepancies-between-share-counts
    const LINKEDIN_INFO_URL = 'http://www.linkedin.com/countserv/count/share?url=%s&callback=_';

    // Url to get share count via Pinterest from
    const PINTEREST_INFO_URL = 'http://api.pinterest.com/v1/urls/count.json?url=%s&callback=_';

    // Url to get share count via StumbleUpon from
    const STUMBLEUPON_INFO_URL = 'http://www.stumbleupon.com/services/1.01/badge.getinfo?url=%s';

    // Url to get share count via VKontakte from
    const VKONTAKTE_INFO_URL = 'http://vk.com/share.php?act=count&index=1&url=%s';
}

/* End of file services.php */
/* Location: ./src/interfaces/services.php */