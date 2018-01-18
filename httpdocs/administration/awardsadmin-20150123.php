<?php

//------------------------------------------------------------------------------
// Site Control History Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------



function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a player award</a></p>\n";

	echo "<p>or, please select a season to work with.</p>\n";

	// check for empty database

	// if (!$db->Exists("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName")) {
        if (!$db->Exists("SELECT * FROM seasons ORDER BY SeasonName")) {
		echo "<p>There are currently no seasons in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Award  Season</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		// $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
               $db->Query("SELECT * FROM seasons ORDER BY SeasonName");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$sename = htmlentities(stripslashes($db->data['SeasonName']));
			$season = htmlentities(stripslashes($db->data['SeasonID']));

			if($x % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}

			// output

			echo "	<td align=\"left\"><a href=\"main.php?SID=$SID&action=$action&do=sbyseason&season=$season&sename=$sename\">$sename</a></td>\n";
			echo "</tr>\n";
			}
		echo "</table>\n";

		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}


function show_main_menu_season($db,$season,$sename)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr,$sename;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a player award</a></p>\n";

	// check for empty database

	if (!$db->Exists("
			SELECT
				pl.PlayerFName, pl.PlayerLName,
				te.TeamName, te.TeamAbbrev,
				fm.AwardID, fm.AwardDetail, fm.season
			FROM
				awards fm
			INNER JOIN
				players pl ON fm.AwardPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			WHERE
				fm.season=$season
			ORDER BY
				fm.AwardID DESC
	")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Player Awards for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";
	  echo "<tr class=\"trrow1\">\n";

		echo "	<td align=\"left\">There are no player awards for this season.</td>\n";
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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Player Awards for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("
			SELECT
				pl.PlayerFName, pl.PlayerLName,
				te.TeamName, te.TeamAbbrev,
				at.*,
				fm.*
			FROM
				awards fm
			INNER JOIN
				players pl ON fm.AwardPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			INNER JOIN 
				awardtypes at ON fm.AwardTitle = at.AwardID
			WHERE
				fm.season=$season
			ORDER BY
				fm.AwardID DESC
		");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
			$pln = htmlentities(stripslashes($db->data['PlayerLName']));

			$tna = htmlentities(stripslashes($db->data[TeamName]));
			$tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

			$det = htmlentities(stripslashes($db->data[AwardDetail]));
			$tit = htmlentities(stripslashes($db->data[AwardTitle]));
			$id = htmlentities(stripslashes($db->data[AwardID]));
			
			$an = htmlentities(stripslashes($db->data[AwardName]));

			$sn = htmlentities(stripslashes($db->data['SeasonName']));

			if($x % 2) {
			  echo "<tr class=\"trrow2\">\n";
			} else {
			  echo "<tr class=\"trrow1\">\n";
			}

			// output

			echo "	<td align=\"left\">$an</td>\n";
			echo "	<td align=\"left\">$pfn $pln</td>\n";
			echo "	<td align=\"left\">$tab</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[AwardID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";

	}
}

function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a player awards</td>\n";
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

		echo "<p>select the award from the drop-down menu<br>\n";
		echo "<select name=\"AwardTitle\">\n";
		echo "	<option value=\"\">Select an award</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM awardtypes")) {
		$db->Query("SELECT * FROM awardtypes ORDER BY AwardName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data[AwardID] . "\">" . $db->data[AwardName] . "</option>\n";
		}
	}

		echo "</select></p>\n";
		
	echo "<p>select the player from the drop-down menu<br>\n";
		echo "<select name=\"AwardPlayer\">\n";
		echo "	<option value=\"\">Select a player</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM players where IsActive=0 ")) {
		$db->Query("SELECT * FROM players where IsActive=0 ORDER BY PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerLName'] . ", " . $db->data['PlayerFName'] . "</option>\n";
		}
	}

		echo "</select></p>\n";

		echo "<p>select the season from the drop-down menu<br>\n";
		echo "<select name=\"season\">\n";
		echo "	<option value=\"\">Select a season</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM seasons")) {
		// $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
                $db->Query("SELECT * FROM seasons ORDER BY SeasonName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">" . $db->data['SeasonName'] . "</option>\n";
		}
	}

		echo "</select></p>\n";

	echo "<p>enter the player awards detail<br><textarea name=\"AwardDetail\" cols=\"55\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"add award\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$AwardPlayer,$AwardTitle,$AwardDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$fp = addslashes(trim($AwardPlayer));
	$ti = addslashes(trim($AwardTitle));
	$fd = addslashes(trim($AwardDetail));
	$se = addslashes(trim($season));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());


	// all okay

	$db->Insert("INSERT INTO awards (AwardPlayer,AwardTitle,AwardDetail,added,season) VALUES ('$fp','$ti','$fd','$d','$se')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new player awards</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another player awards</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to player awards list</a></p>\n";
	} else {
		echo "<p>The member could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to player awards list</a></p>\n";
	}
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all players
	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players ORDER BY PlayerLName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$players[$db->data['PlayerID']] = $db->data[PlayerName];
	}

	// get all seasons
	// $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
        $db->Query("SELECT * FROM seasons ORDER BY SeasonName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
	}

	// get all award types
	$db->Query("SELECT * FROM awardtypes ORDER BY AwardName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$awards[$db->data[AwardID]] = $db->data[AwardName];
	}
	// query database

	$db->QueryRow("SELECT fm.*, pl.PlayerLName, pl.PlayerFName FROM awards fm INNER JOIN players pl ON fm.AwardPlayer=pl.PlayerID WHERE fm.AwardID=$id");

	// setup variables

	$ti = htmlentities(stripslashes($db->data[AwardTitle]));
	$fd = htmlentities(stripslashes($db->data[AwardDetail]));
	$fp = htmlentities(stripslashes($db->data[AwardPlayer]));
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pln = htmlentities(stripslashes($db->data['PlayerLName']));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Award </td>\n";
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
	echo "<input type=\"hidden\" name=\"AwardPlayer\" value=\"$fp\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

	echo "<p>select the award from the drop-down menu<br>\n";
	echo "<select name=\"AwardTitle\">\n";
	echo "	<option value=\"\">Select Award</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($awards as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['AwardTitle'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";
	echo "<p>select the player from the drop-down menu<br>\n";
	echo "<select name=\"AwardPlayer\">\n";
	echo "	<option value=\"\">Select Player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($players as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['AwardPlayer'] ? ' selected' : ''), ">$name</option>\n";
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

	echo "<p>enter the featured player detail<br><textarea name=\"AwardDetail\" cols=\"55\" rows=\"10\" wrap=\"virtual\">$fd</textarea></p>\n";

	echo "<p><input type=\"submit\" value=\"edit award\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$AwardPlayer,$AwardTitle,$AwardDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$fp = addslashes(trim($AwardPlayer));
	$ti = addslashes(trim($AwardTitle));
	$fd = addslashes(trim($AwardDetail));
	$se = addslashes(trim($season));



// query database

	$db->Update("UPDATE awards SET AwardPlayer='$fp',AwardTitle='$ti',AwardDetail='$fd',season='$se' WHERE AwardID=$id");
		echo "<p>You have now updated that player awards.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the player awards listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update player awards some more</a></p>\n";
}



// main program

if (!$USER[flags][$f_awards_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"14px\"><b>Awards Administration</b></p>\n";

switch($do) {
case "sbyseason":
    show_main_menu_season($db, $_GET['season'], $_GET['sename']);
    break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$AwardPlayer,$AwardTitle,$AwardDetail,$_POST['season'], $_POST['sename']);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$AwardPlayer,$AwardTitle,$AwardDetail,$_POST['season'], $_POST['sename']);
	break;
default:
	show_main_menu($db);
	break;
}

?>
