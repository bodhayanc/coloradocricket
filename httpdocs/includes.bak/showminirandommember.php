<?php

//------------------------------------------------------------------------------
// Mini Featured Member v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_minirandommember($db)
{
	global $PHP_SELF;

	if (!$db->Exists("SELECT * FROM players")) {
		echo "<p>The Random Member is being edited. Please check back shortly.</p>\n";
		return;
	} else {
		$db->QueryRow("
			SELECT 
			  te.TeamID, te.TeamAbbrev, pl.* 
			FROM 
			  players pl 
			INNER JOIN 
			  teams te 
			ON 
			  pl.PlayerTeam = te.TeamID 
			WHERE
			  pl.picture <> '' AND pl.isactive = 0
			ORDER BY 
			  Rand() 
			LIMIT 1;
		");
		$db->BagAndTag();

		// setup variables

		$pid = $db->data['PlayerID'];
		$pfn = $db->data['PlayerFName'];
		$pln = $db->data['PlayerLName'];
		$pic = $db->data['picture'];

		$tna = $db->data['TeamName'];
		$tab = $db->data['TeamAbbrev'];
		
		$bat = $db->data['BattingStyle'];
		$bow = $db->data['BowlingStyle'];

		// output story, show the image, if no image show the title

		echo "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">\n";
		echo "<tr>\n";
		echo "  <td align=\"left\" valigh=\"top\">\n";
		echo "	<p><b><a href=\"/players.php?players=$pid&ccl_mode=1\">$pfn $pln</a></b> ($tab)</p>\n";

		if($pic != "") {
		echo "  <p align=\"center\"><a href=\"/players.php?players=$pid&ccl_mode=1\"><font color=\"#000000\"><img src=\"/uploadphotos/players/$pic\" width=\"150\" border=\"1\" style=\"border-color: #000000\"></font></a></p>\n";
		} else {
		echo "  <p align=\"center\"><a href=\"/players.php?players=$pid&ccl_mode=1\"><font color=\"#000000\"><img src=\"/uploadphotos/players/HeadNoMan.jpg\" width=\"100\" border=\"1\" style=\"border-color: #000000\"></font></a></p>\n";
		}
		
		if($bat != "") echo "  $bat<br>\n";
		if($bow != "") echo "  $bow<br>\n";
		
		echo "  <p><img src=\"/images/icons/icon_arrows.gif\"><a href=\"/players.php\">find other players</a></p>\n";
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		}
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_minirandommember($db);

?>