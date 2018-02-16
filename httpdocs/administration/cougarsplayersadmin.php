<?php

//------------------------------------------------------------------------------
// Cougars Players Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a cougar player</a></p>\n";

	echo "<p>or, please select a season to work with.</p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM seasons ORDER BY SeasonName")) {
		echo "<p>There are currently no seasons in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

		// Schedule Select Box

		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
		echo "  <tr>\n";
		echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Select a season schedule</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

       		echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
		echo "    <option>select a season</option>\n";
		echo "    <option>===============</option>\n";

		$db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%'ORDER BY SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();

			// output
			$id = $db->data['SeasonID'];
			$season = $db->data['SeasonID'];
			$sename = $db->data['SeasonName'];

			echo "    <option value=\"main.php?SID=$SID&action=$action&do=byseason&season=$season&sename=$sename\">" . $db->data['SeasonName'] . " season</option>\n";

		}

		echo "    </select></p>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		


	}
}

//function show_main_menu_season($db,$season,$sename)
function show_main_menu_season($db,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr,$sename;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a cougar player</a></p>\n";

	// check for empty database

	if (!$db->Exists("
			SELECT * FROM cougarsplayers WHERE season=$season
	")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Player Administration for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";
	  echo "<tr class=\"trrow1\">\n";

		echo "	<td align=\"left\">There are no players for this season.</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";


		return;
	} else {

		// output header, not to be included in for loop

	// Teams Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->QueryRow("SELECT * FROM cougarsteams ORDER BY TeamName");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$id = htmlentities(stripslashes($db->data['TeamID']));
		$na = htmlentities(stripslashes($db->data['teamname']));
		$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
		$di = htmlentities(stripslashes($db->data['TeamDirections']));

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td width=\"100%\"><a href=\"main.php?SID=$SID&action=$action&do=byteam&season=$season&sename=$sename&team=$id&teamname=$na\"><b>$na</b></a>&nbsp;\n";
		echo "    </td>\n";
		echo "  </tr>\n";
	}
	
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";


}



function show_byteam_menu($db,$season,$sename,$team,$teamname)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr,$sename;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a cougar player</a></p>\n";

	// check for empty database

	if (!$db->Exists("
			SELECT * from cougarsplayers WHERE season = $season AND CougarTeam = $team
	")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Cougar Players for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";
	  echo "<tr class=\"trrow1\">\n";

		echo "	<td align=\"left\">There are no players for this season.</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";


		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Players for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database if Cougar
		
		if($team != "1") {
		
		$db->Query("
			SELECT
				te.TeamName, te.TeamAbbrev,
				cp.CougarID, cp.NotCougarPlayer, cp.season
			FROM
				cougarsplayers cp
			INNER JOIN
				cougarsteams te ON cp.CougarTeam = te.TeamID
			WHERE
				cp.season=$season AND cp.CougarTeam = $team
			ORDER BY
				cp.CougarID DESC
		");		
		
		} else {

		$db->Query("
			SELECT
				pl.PlayerFName, pl.PlayerLName,
				te.TeamName, te.TeamAbbrev,
				cp.CougarID, cp.CougarDetail, cp.season
			FROM
				cougarsplayers cp
			INNER JOIN
				players pl ON cp.CougarPlayer = pl.PlayerID
			INNER JOIN
				cougarsteams te ON cp.CougarTeam = te.TeamID
			WHERE
				cp.season=$season AND cp.CougarTeam = $team
			ORDER BY
				cp.CougarID DESC
		");
		
		}
		
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
			$pln = htmlentities(stripslashes($db->data['PlayerLName']));
			$ncp = htmlentities(stripslashes($db->data[NotCougarPlayer]));

			$tna = htmlentities(stripslashes($db->data['teamname']));
			$tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

			$det = htmlentities(stripslashes($db->data[CougarDetail]));
			$id = htmlentities(stripslashes($db->data[CougarID]));

			$sn = htmlentities(stripslashes($db->data['SeasonName']));

			if($x % 2) {
			  echo "<tr class=\"trrow2\">\n";
			} else {
			  echo "<tr class=\"trrow1\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			if($team != "1") {
			echo "	<td align=\"left\">$ncp</td>\n";
			} else {
			echo "	<td align=\"left\">$pfn $pln</td>\n";
			}
			echo "	<td align=\"left\">$tab</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&team=$team&id=" . $db->data[CougarID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo " </tr>\n";
		echo "</table>\n";
		
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";


	}
}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a cougar player</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";

	echo "<p>select the player from the drop-down menu<br>\n";
		echo "<select name=\"CougarPlayer\">\n";
		echo "	<option value=\"\">Select a player</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerLName'] . ", " . $db->data['PlayerFName'] . "</option>\n";
		}
	}

		echo "</select></p>\n";
		
		echo "<p>enter the name of the player <font color=\"red\">IF NOT Cougar</font><br><input type=\"text\" name=\"NotCougarPlayer\" size=\"25\" maxlength=\"64\"></p>\n";

	echo "<p><select name=\"CougarTeam\">\n";
	echo "	<option value=\"\">Team player belongs to</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM cougarsteams")) {
		$db->Query("SELECT * FROM cougarsteams ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
		}
	}
	echo "</select></p>\n";		

		echo "<p>select the season from the drop-down menu<br>\n";
		echo "<select name=\"season\">\n";
		echo "	<option value=\"\">Select a season</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM seasons")) {
		$db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">" . $db->data['SeasonName'] . "</option>\n";
		}
	}

		echo "</select></p>\n";

	echo "<p>enter the cougar player detail<br><textarea name=\"CougarDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"add member\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$id,$CougarPlayer,$NotCougarPlayer,$CougarTeam,$CougarDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$fp = addslashes(trim($CougarPlayer));
	$nc = addslashes(trim($NotCougarPlayer));
	$fd = addslashes(trim($CougarDetail));
	$ct = addslashes(trim($CougarTeam));
	$se = addslashes(trim($season));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());


	// all okay

	$db->Insert("INSERT INTO cougarsplayers (CougarPlayer,NotCougarPlayer,CougarTeam,CougarDetail,added,season) VALUES ('$fp','$nc','$ct','$fd','$d','$se')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new cougar player</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another cougar player</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to cougar player list</a></p>\n";
	} else {
		echo "<p>The member could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to cougar player list</a></p>\n";
	}
}


function edit_category_form($db,$id,$team)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all teams
	$db->Query("SELECT * FROM cougarsteams ORDER BY TeamName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
        $db->BagAndTag();
		$teams[$db->data['TeamID']] = $db->data['teamname'];
	}


	// get all players
	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players ORDER BY PlayerLName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$players[$db->data['PlayerID']] = $db->data['PlayerName'];
	}

	// get all seasons
	$db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
	}

	// query database

	if($team != "1") {
	$db->QueryRow("SELECT * FROM cougarsplayers WHERE CougarID=$id");
	} else {
	$db->QueryRow("SELECT cp.*, pl.PlayerLName, pl.PlayerFName FROM cougarsplayers cp INNER JOIN players pl ON cp.CougarPlayer=pl.PlayerID WHERE cp.CougarID=$id");
	}
	
	// setup variables

	$fd = htmlentities(stripslashes($db->data[CougarDetail]));
	$fp = htmlentities(stripslashes($db->data[CougarPlayer]));
	$nc = htmlentities(stripslashes($db->data[NotCougarPlayer]));
	$ct = htmlentities(stripslashes($db->data[CougarTeam]));
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pln = htmlentities(stripslashes($db->data['PlayerLName']));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Cougar Member</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"CougarPlayer\" value=\"$fp\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	
	if($ct != "1") {
	echo "<p>enter the players name <font color=\"red\">IF NOT Cougar</font><br><input type=\"text\" name=\"NotCougarPlayer\" size=\"25\" maxlength=\"64\" value=\"$nc\"></p>\n";
	} else {	
	echo "<p>select the player from the drop-down menu<br>\n";
	echo "<select name=\"CougarPlayer\">\n";
	echo "	<option value=\"\">Select Player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($players as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['CougarPlayer'] ? ' selected' : ''), ">$name</option>\n";
	}
	echo "</select></p>\n";
	}	
	

	echo "<p>select players team:<br>\n";
	echo "<select name=\"CougarTeam\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

		for ($i=1; $i<=count($teams); $i++) {
			echo "<option value=\"$i\"" . ($i==$db->data[CougarTeam]?" selected":"") . ">" . $teams[$i] . "</option>\n";
		}

	echo "</select></p>\n";
	
	echo "<p>select the season from the drop-down menu<br>\n";
	echo "<select name=\"season\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($seasons as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['season'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

	echo "<p>enter the featured player detail<br><textarea name=\"CougarDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$fd</textarea></p>\n";

	echo "<p><input type=\"submit\" value=\"edit cougar player\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$CougarPlayer,$NotCougarPlayer,$CougarTeam,$CougarDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$fp = addslashes(trim($CougarPlayer));
	$fd = addslashes(trim($CougarDetail));
	$nc = addslashes(trim($NotCougarPlayer));
	$ct = addslashes(trim($CougarTeam));
	$se = addslashes(trim($season));



// query database

	$db->Update("UPDATE cougarsplayers SET CougarPlayer='$fp',NotCougarPlayer='$nc',CougarTeam='$ct',CougarDetail='$fd',season='$se' WHERE CougarID=$id");
		echo "<p>You have now updated that cougar player.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the cougar player listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update cougar player some more</a></p>\n";
}



// main program

if (!$USER['flags'][$f_cougarsplayers_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"14px\"><b>Cougars Players Administration</b></p>\n";

switch($do) {
case "byseason":
    show_main_menu_season($db,$season);
    break;
case "byteam":
	show_byteam_menu($db,$season,$sename,$team,$teamname);
	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$id,$CougarPlayer,$NotCougarPlayer,$CougarTeam,$CougarDetail,$season,$sename);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$CougarPlayer,$NotCougarPlayer,$CougarTeam,$CougarDetail,$season,$sename);
	break;
default:
	show_main_menu($db);
	break;
}

?>
