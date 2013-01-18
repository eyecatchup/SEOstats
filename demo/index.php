<style>td{padding: 3px 5px; border:1px solid #999;}</style>
<?php
require("../src/seostats.php");
try 
{
  $url = "http://www.nahklick.de";
  $seostats = new SEOstats($url);
?>
<table>
	<!-- ALEXA -->
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">Alexa</td>
	</tr>
	<tr>
		<td>Global Domain-Rank</td>
		<td><?php 
        $alexa = $seostats->Alexa(); 
		print $alexa->getGlobalRank(); ?></td>
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
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">Google</td>
	</tr>
	<tr>
		<td>Toolbar PageRank</td>
		<td><?php
		$google = $seostats->Google();
		print $google->getPageRank(); ?></td>
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
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">OpenSiteExplorer (by SEOmoz)</td>
	</tr>
	<tr><?php $data = $seostats->OpenSiteExplorer()->getPageMetrics(); ?>
		<td>SEOmoz Page-Authority</td>
		<td><?php print $data['pageAuthority']; ?></td>
	</tr>
	<tr>
		<td>SEOmoz Domain-Authority</td>
		<td><?php print $data['domainAuthority']; ?></td>
	</tr>
	<tr>
		<td>Total amount of incoming links</td>
		<td><?php print $data['totalInboundLinks']; ?></td>
	</tr>
	<tr>
		<td>Total amount of inlinking domains</td>
		<td><?php print $data['linkingRootDomains']; ?></td>
	</tr>
	<!-- SEMRUSH -->
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">SEMRush</td>
	</tr>
	<tr><?php $data2 = $seostats->SEMRush()->getDomainRank(); ?>
		<td>SEMRush Domain-Rank</td>
		<td><?php print $data2['Rk']; ?></td>
	</tr>
	<tr>
		<td>Number of Keywords this site has in the TOP20 organic results</td>
		<td><?php print $data2['Or']; ?></td>
	</tr>
	<tr>
		<td>Estimated number of visitors coming from the first 20 search results (per month)</td>
		<td><?php print $data2['Ot']; ?></td>
	</tr>
	<tr>
		<td>Estimated cost of purchasing the same number of visitors through Ads</td>
		<td><?php print $data2['Oc']; ?></td>
	</tr>
	<tr>
		<td>Estimated number of competitors in organic search</td>
		<td><?php print $data2['Oo']; ?></td>
	</tr>
	<tr>
		<td>Estimated number of potential ad/traffic buyers</td>
		<td><?php print $data2['Oa']; ?></td>
	</tr>
	<!-- SISTRIX -->
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">Sistrix / OpenLinkGraph</td>
	</tr>
	<tr>
		<td>Visibility-Index</td>
		<td><?php 
		$sistrix = $seostats->Sistrix();
		print $sistrix->getVisibilityIndex(); ?></td>
	</tr>
	<tr><?php $data3 = $sistrix->OpenLinkGraph()->getSummary(); ?>
		<td>Total amount of incoming links</td>
		<td><?php print $data3['totalInlinks']; ?></td>
	</tr>
	<tr>
		<td>Total amount of inlinking hosts</td>
		<td><?php print $data3['inlinkingHosts']; ?></td>
	</tr>
	<tr>
		<td>Total amount of inlinking domains</td>
		<td><?php print $data3['inlinkingDomains']; ?></td>
	</tr>
	<tr>
		<td>Total amount of inlinking IPs</td>
		<td><?php print $data3['inlinkingIPs']; ?></td>
	</tr>
	<tr>
		<td>Total amount of inlinking /24 sub-networks</td>
		<td><?php print $data3['inlinking24Subnets']; ?></td>
	</tr>
	<!-- SOCIAL -->
	<tr>
		<td colspan="2" style="text-align:center;font-weight:bold;">Social Visibility</td>
	</tr>
	<tr>
		<td>Total amount of Google +1s for the URL</td>
		<td><?php
		$social = $seostats->Social(); 
		print $social->getGoogleShares(); ?></td>
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
<?
} 
catch (SEOstatsException $e) 
{
  die($e->getMessage());
}
