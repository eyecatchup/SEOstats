<?php
ini_set('max_execution_time', 300);

require '../src/seostats.php';

try {
    $url = "http://www.nahklick.de";
    /**
     * Create a new SEOstats object to request SEO metrics
     * for the given URL.
     */
    $seostats = new SEOstats($url);
?>
<!--
    Demo output for the SEOstats result data.
-->
<style>td{padding: 3px 5px; border:1px solid #999;}</style>
<table>
    <!-- ALEXA -->
    <?php
        // create a new object to request alexa metrics
        $alexa = $seostats->Alexa();
    ?>
    <tr>
        <td colspan="2" style="text-align:center;font-weight:bold;">Alexa</td>
    </tr>
    <tr>
        <td>Global Domain-Rank</td>
        <td><?php print $alexa->getGlobalRank(); ?></td>
    </tr>
    <tr>
        <td>Week Domain-Rank</td>
        <td><?php print $alexa->getWeekRank(); ?></td>
    </tr>
    <tr>
        <td>Month Domain-Rank</td>
        <td><?php print $alexa->getMonthRank(); ?></td>
    </tr>
    <tr><?php $ctryRk = $alexa->getCountryRank(); ?>
        <td>Country-specific Domain-Rank</td>
        <td><?php print $ctryRk['rank']; ?> (Rank in <?php print $ctryRk['country']; ?>)</td>
    </tr>
    <tr>
        <td>Total amount of incoming links</td>
        <td><?php print $alexa->getBacklinkCount(); ?></td>
    </tr>
    <tr>
        <td>Average Pageload Time</td>
        <td><?php print $alexa->getPageLoadTime(); ?></td>
    </tr>
    <!-- GOOGLE -->
    <?php
        // create a new object to request google metrics
        $google = $seostats->Google();
    ?>
    <tr>
        <td colspan="2" style="text-align:center;font-weight:bold;">Google</td>
    </tr>
    <tr>
        <td>Toolbar PageRank</td>
        <td><?php print $google->getPageRank(); ?></td>
    </tr>
    <tr>
        <td>Total amount of pages in Websearch-Index</td>
        <td><?php print $google->getSiteindexTotal(); ?></td>
    </tr>
    <tr>
        <td>Total amount of incoming links</td>
        <td><?php print $google->getBacklinksTotal(); ?></td>
    </tr>
    <tr>
        <td>PageSpeed total score</td>
        <td><?php print $google->getPagespeedScore(); ?>/100</td>
    </tr>
    <!-- OPENSITEEXPLORER -->
    <?php
        // create a new object to request open site explorer (by seomoz) metrics
        $openSiteExplorer = $seostats->OpenSiteExplorer()->getPageMetrics();
    ?>
    <tr>
        <td colspan="2" style="text-align:center;font-weight:bold;">OpenSiteExplorer (by SEOmoz)</td>
    </tr>
    <tr>
        <td>SEOmoz Page-Authority</td>
        <td><?php print $openSiteExplorer['pageAuthority']; ?></td>
    </tr>
    <tr>
        <td>SEOmoz Domain-Authority</td>
        <td><?php print $openSiteExplorer['domainAuthority']; ?></td>
    </tr>
    <tr>
        <td>Total amount of incoming links</td>
        <td><?php print $openSiteExplorer['totalInboundLinks']; ?></td>
    </tr>
    <tr>
        <td>Total amount of inlinking domains</td>
        <td><?php print $openSiteExplorer['linkingRootDomains']; ?></td>
    </tr>
    <!-- SEMRUSH -->
    <?php
        // create a new semrush object and request metrics
        $semrush = $seostats->SEMRush()->getDomainRank();
    ?>
    <tr>
        <td colspan="2" style="text-align:center;font-weight:bold;">SEMRush</td>
    </tr>
    <tr>
        <td>SEMRush Domain-Rank</td>
        <td><?php print $semrush['Rk']; ?></td>
    </tr>
    <tr>
        <td>Number of Keywords this site has in the TOP20 organic results</td>
        <td><?php print $semrush['Or']; ?></td>
    </tr>
    <tr>
        <td>Estimated number of visitors coming from the first 20 search results (per month)</td>
        <td><?php print $semrush['Ot']; ?></td>
    </tr>
    <tr>
        <td>Estimated cost of purchasing the same number of visitors through Ads</td>
        <td><?php print $semrush['Oc']; ?></td>
    </tr>
    <tr>
        <td>Estimated number of competitors in organic search</td>
        <td><?php print $semrush['Oo']; ?></td>
    </tr>
    <tr>
        <td>Estimated number of potential ad/traffic buyers</td>
        <td><?php print $semrush['Oa']; ?></td>
    </tr>
    <!-- SISTRIX -->
    <?php
        // create a new object to request sistrix metrics
        $sistrix = $seostats->Sistrix();
    ?>
    <tr>
        <td colspan="2" style="text-align:center;font-weight:bold;">Sistrix</td>
    </tr>
    <tr>
        <td>Visibility-Index</td>
        <td><?php print $sistrix->getVisibilityIndex(); ?></td>
    </tr>
    <!-- SOCIAL -->
    <?php
        // create a new object to request social media metrics
        $social = $seostats->Social();
    ?>
    <tr>
        <td colspan="2" style="text-align:center;font-weight:bold;">Social Visibility</td>
    </tr>
    <tr>
        <td>Total amount of Google +1s for the URL</td>
        <td><?php print $social->getGoogleShares(); ?></td>
    </tr>
    <tr><?php $fb = $social->getFacebookShares(); ?>
        <td>Total amount of Facebook Likes</td>
        <td><?php print $fb['like_count']; ?></td>
    </tr>
    <tr>
        <td>Total amount of Facebook Shares</td>
        <td><?php print $fb['share_count']; ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from Facebook Comments</td>
        <td><?php print $fb['comment_count']; ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from Tweets</td>
        <td><?php print $social->getTwitterShares(); ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from Delicious</td>
        <td><?php print $social->getDeliciousShares(); ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from Digg</td>
        <td><?php print $social->getDiggShares(); ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from LinkedIn</td>
        <td><?php print $social->getLinkedInShares(); ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from Pinterest</td>
        <td><?php print $social->getPinterestShares(); ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from StumbleUpon</td>
        <td><?php print $social->getStumbleUponShares(); ?></td>
    </tr>
    <tr>
        <td>Total amount of backlinks from VKontakte</td>
        <td><?php print $social->getVKontakteShares(); ?></td>
    </tr>
</table>
<?php
} catch (SEOstatsException $e) {
    die($e->getMessage());
}
