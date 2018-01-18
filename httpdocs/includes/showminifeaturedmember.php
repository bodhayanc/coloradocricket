<?php

//------------------------------------------------------------------------------
// Mini Featured Member v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_minifeaturedmember($db,$len=100)
{
	global $PHP_SELF;

	if (!$db->Exists("SELECT * FROM featuredmember")) {
		echo "<p>The Featured Member is being edited. Please check back shortly.</p>\n";
		return;
	} else {
		$db->QueryRow("
			SELECT
				pl.PlayerFName, pl.PlayerLName, pl.picture,
				te.TeamName, te.TeamAbbrev,
				fm.FeaturedID, fm.FeaturedDetail
			FROM
				featuredmember fm
			INNER JOIN
				players pl ON fm.FeaturedPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			ORDER BY fm.FeaturedID DESC LIMIT 1;

		");
		$db->BagAndTag();

		// get short version of story
		$story = "";
		if ($story != "" && strlen($story)>$len) {
			$story = substr($db->data[FeaturedDetail],0,$len);
			while($story[strlen($story)-1] != " ") {
				$story = substr($story,0,-1);
			}
			$story = substr($story,0,-1);
		} else {
			$story = substr($db->data[FeaturedDetail],0,$len);
		}

		$story .= "...";
		$a = $story;
			
		// setup variables

		$pfn = $db->data[PlayerFName];
		$pln = $db->data[PlayerLName];
		$pic = $db->data[picture];
		$tna = $db->data[TeamName];
		$tab = $db->data[TeamAbbrev];
		$fid = $db->data[FeaturedID];

		// output story, show the image, if no image show the title

		echo "<table width=\"100%\" border-right=\"1\" cellpadding=\"2\" cellspacing=\"0\" bordercolor=\"#9E3228\" bgcolor=\"#FFFFFF\" >\n";
		echo "<tr>\n";
		echo "  <td align=\"left\" valigh=\"top\">\n";
		echo "	<p align=\"center\"><b><a href=\"/featuredmember.php?fm=$fid&ccl_mode=1\">$pfn $pln</a></b><br>($tab)</p>\n";

		if($pic != "") {
		echo "  <p align=\"center\"><a href=\"/featuredmember.php?fm=$fid&ccl_mode=1\"><font color=\"#000000\"><img src=\"/uploadphotos/players/$pic\" width=\"150\" border=\"1\" style=\"border-color: #000000\"></font></a></p>\n";
		} else {
		echo "  <p align=\"center\"><a href=\"/featuredmember.php?fm=$fid&ccl_mode=1\"><font color=\"#000000\"><img src=\"/uploadphotos/players/HeadNoMan.jpg\" width=\"100\" border=\"1\" style=\"border-color: #000000\"></font></a></p>\n";
		}

		echo "	<p class=\"10px\">$a</p>\n";
		//echo "  <p align=\"center\" class=\"10px\"><i>Brought to you by</i><br> <a href=\"http://www.denverwoodlands.com\" target=\"_new\">Denver Woodlands</a></p>\n";		
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		}
}

$db = new mysql_class($dbcfg[login],$dbcfg[pword],$dbcfg[server]);
$db->SelectDB($dbcfg[db]);

show_minifeaturedmember($db);

?>