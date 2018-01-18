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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new featured member</a></p>\n";

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
		echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Select a season</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

       		echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
		echo "    <option>select a season</option>\n";
		echo "    <option>===============</option>\n";

// 19-Aug-2009
$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");
//	$db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();

			// output
			$id = $db->data[SeasonID];
			$season = $db->data[SeasonID];
			$sename = $db->data[SeasonName];

			echo "    <option value=\"main.php?SID=$SID&action=$action&do=byseason&season=$season&sename=$sename\">" . $db->data[SeasonName] . " season</option>\n";

		}

		echo "    </select></p>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		


	}
}


function show_main_menu_season($db,$season,$sename)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a featured member</a></p>\n";

	// check for empty database

	if (!$db->Exists("
			SELECT
				pl.PlayerFName, pl.PlayerLName,
				te.TeamName, te.TeamAbbrev,
				fm.FeaturedID, fm.FeaturedDetail, fm.season
			FROM
				featuredmember fm
			INNER JOIN
				players pl ON fm.FeaturedPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			WHERE
				fm.season=$season
			ORDER BY
				fm.FeaturedID DESC
	")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Members for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";
	  echo "<tr class=\"trrow1\">\n";

		echo "	<td align=\"left\">There are no featured players for this season.</td>\n";
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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Members for $sename</td>\n";
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
				fm.FeaturedID, fm.FeaturedDetail, fm.season
			FROM
				featuredmember fm
			INNER JOIN
				players pl ON fm.FeaturedPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			WHERE
				fm.season=$season
			ORDER BY
				fm.FeaturedID DESC
		");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$pfn = htmlentities(stripslashes($db->data[PlayerFName]));
			$pln = htmlentities(stripslashes($db->data[PlayerLName]));

			$tna = htmlentities(stripslashes($db->data[TeamName]));
			$tab = htmlentities(stripslashes($db->data[TeamAbbrev]));

			$det = htmlentities(stripslashes($db->data[FeaturedDetail]));
			$id = htmlentities(stripslashes($db->data[FeaturedID]));

			$sn = htmlentities(stripslashes($db->data[SeasonName]));

			if($x % 2) {
			  echo "<tr class=\"trrow2\">\n";
			} else {
			  echo "<tr class=\"trrow1\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			echo "	<td align=\"left\">$pfn $pln</td>\n";
			echo "	<td align=\"left\">$tab</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[FeaturedID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";
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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a POTW/Featured member</td>\n";
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
		echo "<select name=\"FeaturedPlayer\">\n";
		echo "	<option value=\"\">Select a player</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

// 19-Aug-2009;      22-Jun-2017 11:15pm Added PlayerFName too to the Order By
	if ($db->Exists("SELECT * FROM players where isactive = 0")) {
		$db->Query("SELECT * FROM players where isactive = 0 ORDER BY PlayerLName, PlayerFName");
//	if ($db->Exists("SELECT * FROM players")) {
//		$db->Query("SELECT * FROM players ORDER BY PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data[PlayerID] . "\">" . $db->data[PlayerLName] . ", " . $db->data[PlayerFName] . "</option>\n";
		}
	}

		echo "</select></p>\n";

		echo "<p>select the season from the drop-down menu<br>\n";
		echo "<select name=\"season\">\n";
		echo "	<option value=\"\">Select a season</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM seasons")) {
// 19-Aug-2009
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
//		$db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data[SeasonID] . "\">" . $db->data[SeasonName] . "</option>\n";
		}
	}

		echo "</select></p>\n";

	echo "<p>enter the featured member detail<br><textarea name=\"FeaturedDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"add member\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$FeaturedPlayer,$FeaturedDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$fp = addslashes(trim($FeaturedPlayer));
	$fd = addslashes(trim($FeaturedDetail));
	$se = addslashes(trim($season));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());


	// all okay

	$db->Insert("INSERT INTO featuredmember (FeaturedPlayer,FeaturedDetail,added,season) VALUES ('$fp','$fd','$d','$se')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new featured member</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another POTW/featured member</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to POTW/featured member list</a></p>\n";
	} else {
		echo "<p>The member could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to POTW/featured member list</a></p>\n";
	}
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all players
// 19-Aug-2009;            22-Jun-2017 11:15pm Added PlayerFName too to the Order By
	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players where isactive = 0 ORDER BY PlayerLName, PlayerFName");
//	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players where isactive = 0 ORDER BY PlayerLName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$players[$db->data[PlayerID]] = $db->data[PlayerName];
	}

	// get all seasons
// 19-Aug-2009
	$db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
//	$db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$seasons[$db->data[SeasonID]] = $db->data[SeasonName];
	}

	// query database

	$db->QueryRow("SELECT fm.*, pl.PlayerLName, pl.PlayerFName FROM featuredmember fm INNER JOIN players pl ON fm.FeaturedPlayer=pl.PlayerID WHERE fm.FeaturedID=$id");

	// setup variables

	$fd = htmlentities(stripslashes($db->data[FeaturedDetail]));
	$fp = htmlentities(stripslashes($db->data[FeaturedPlayer]));
	$pfn = htmlentities(stripslashes($db->data[PlayerFName]));
	$pln = htmlentities(stripslashes($db->data[PlayerLName]));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit POTW/Featured Member</td>\n";
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
	echo "<input type=\"hidden\" name=\"FeaturedPlayer\" value=\"$fp\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

	echo "<p>select the player from the drop-down menu<br>\n";
	echo "<select name=\"FeaturedPlayer\">\n";
	echo "	<option value=\"\">Select Player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($players as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['FeaturedPlayer'] ? ' selected' : ''), ">$name</option>\n";
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

	echo "<p>enter the featured player detail<br><textarea name=\"FeaturedDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$fd</textarea></p>\n";

	echo "<p><input type=\"submit\" value=\"edit featured member\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$FeaturedPlayer,$FeaturedDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$fp = addslashes(trim($FeaturedPlayer));
	$fd = addslashes(trim($FeaturedDetail));
	$se = addslashes(trim($season));



// query database

	$db->Update("UPDATE featuredmember SET FeaturedPlayer='$fp', FeaturedDetail='$fd',season='$se' WHERE FeaturedID=$id");
		echo "<p>You have now updated that featured member.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the featured member listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update featured member some more</a></p>\n";
}



// main program

if (!$USER[flags][$f_featuredmember_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Featured Member Administration</b></p>\n";

switch($do) {
case "byseason":
    show_main_menu_season($db,$season,$sename);
    break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$FeaturedPlayer,$FeaturedDetail,$season,$sename);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$FeaturedPlayer,$FeaturedDetail,$season,$sename);
	break;
default:
	show_main_menu($db);
	break;
}

?>
