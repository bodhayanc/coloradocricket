<?php

//------------------------------------------------------------------------------
// Site Control Champions Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr,$sename;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a champion entry</a></p>\n";

	// check for empty database

	if (!$db->Exists("
			SELECT * FROM champions
	")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Champions</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";
	  echo "<tr class=\"trrow1\">\n";

		echo "	<td align=\"left\">There are no champions recorded at this time.</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" width=25% height=\"23\">&nbsp;Champions</td>\n";

      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" width=25% height=\"23\">1st</td>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" width=24% height=\"23\">Runners</td>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" width=24% height=\"23\">3rd</td>\n";
      echo "  <td bgcolor=\"$bluebdr\" width=2% height=\"23\">&nbsp;</td>\n";


      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"5\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("
			SELECT
				ch.*,
				te.TeamName, te.TeamAbbrev,
	                        t2.TeamName as TeamName2, t2.TeamAbbrev as TeamAbbrev2,
	                        t3.TeamName as TeamName3, t3.TeamAbbrev as TeamAbbrev3,
				se.*
			FROM
				champions ch
			INNER JOIN
				teams te ON ch.ChampTeam = te.TeamID
                        LEFT JOIN
				teams t2 ON ch.ChampTeam2 = t2.TeamID
                        LEFT JOIN
				teams t3 ON ch.ChampTeam3 = t3.TeamID
			INNER JOIN 
				seasons se ON ch.ChampSeason = se.SeasonID
			ORDER BY
				se.SeasonName DESC
		");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables


			$tna = htmlentities(stripslashes($db->data['teamname']));
			$tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

                        // 6-Jan-2010
			$tna2 = htmlentities(stripslashes($db->data['TeamName2']));
			$tab2 = htmlentities(stripslashes($db->data['TeamAbbrev2']));
			$tna3 = htmlentities(stripslashes($db->data['TeamName3']));
			$tab3 = htmlentities(stripslashes($db->data['TeamAbbrev3']));

			$sn = htmlentities(stripslashes($db->data['SeasonName']));

			if($x % 2) {
			  echo "<tr class=\"trrow2\">\n";
			} else {
			  echo "<tr class=\"trrow1\">\n";
			}

			// output

                        // 6-Jan-2010                       
			echo "	<td align=\"left\" width=25%>$sn</td>\n";
			// echo "	<td align=\"left\">$sn Champions</td>\n";

			echo "	<td width=25% align=\"left\">$tna ($tab)</td>\n";

                        // 6-Jan-2010    
			echo "	<td width=24% align=\"left\">$tna2 ($tab2)</td>\n";
			echo "	<td width=24% align=\"left\">$tna3 ($tab3)</td>\n";

			echo "	<td align=\"right\" width=2%><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[ChampID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";


	}
}

function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a champion entry</td>\n";
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

	echo "<p>select the season from the drop-down menu<br>\n";
	echo "<select name=\"ChampSeason\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM seasons")) {
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">" . $db->data['SeasonName'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

	echo "<p>select the ChampTeam from the drop-down menu<br>\n";
	echo "<select name=\"ChampTeam\">\n";
	echo "	<option value=\"\">Select the ChampTeam</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM teams")) {
// 6-Jan-2010
		$db->Query("SELECT * FROM teams where Leagueid = 1 ORDER BY LeagueID ASC, TeamActive DESC, TeamName ASC");
//		$db->Query("SELECT * FROM teams ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

// 6-Jan-2010
	echo "<p>select the Runners-2nd team from the drop-down menu<br>\n";
	echo "<select name=\"Runners(2nd)\">\n";
	echo "	<option value=\"\">Select the 2nd team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM teams")) {
// 6-Jan-2010
		$db->Query("SELECT * FROM teams where Leagueid = 1 ORDER BY LeagueID ASC, TeamActive DESC, TeamName ASC");
//		$db->Query("SELECT * FROM teams ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

// 6-Jan-2010
	echo "<p>select the 3rd team from the drop-down menu<br>\n";
	echo "<select name=\"3rd Team\">\n";
	echo "	<option value=\"\">Select the 3rd team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM teams")) {
// 6-Jan-2010
		$db->Query("SELECT * FROM teams where Leagueid=1 ORDER BY LeagueID ASC, TeamActive DESC, TeamName ASC");
//		$db->Query("SELECT * FROM teams ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

	echo "<p><input type=\"submit\" value=\"add champion\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$ChampTeam,$ChampSeason,$ChampTeam2,$ChampTeam3)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables


	$se = addslashes(trim($ChampSeason));
	$te = addslashes(trim($ChampTeam));

	$t2 = addslashes(trim($ChampTeam2));
	$t3 = addslashes(trim($ChampTeam3));


	// all okay

	$db->Insert("INSERT INTO champions (ChampTeam,ChampSeason, ChampTeam2, ChampTeam3) VALUES ('$te','$se','$t2', '$t3')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a champion entry</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another champion entry</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to champions list</a></p>\n";
	} else {
		echo "<p>The champion could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to champions list</a></p>\n";
	}
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;


	// get all seasons
	$db->Query("SELECT * FROM seasons ORDER BY SeasonName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
	}

	// get all teams
	$db->Query("SELECT * FROM teams where leagueid = 1 ORDER BY TeamName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$teams[$db->data['TeamID']] = $db->data['teamname'];
	}	

	// query database

	$db->QueryRow("
		SELECT
			ch.ChampID, ch.ChampSeason, ch.ChampTeam, 
			te.TeamName, t2.TeamName as TeamName2,ch.ChampTeam2,ch.ChampTeam3,
            t3.TeamName as TeamName3, se.SeasonName
		FROM
			champions ch
		INNER JOIN
			teams te ON ch.ChampTeam = te.TeamID
		LEFT JOIN
			teams t2 ON ch.ChampTeam2 = t2.TeamID
		LEFT JOIN
			teams t3 ON ch.ChampTeam3 = t3.TeamID
		INNER JOIN 
			seasons se ON ch.ChampSeason = se.SeasonID
		WHERE
			ch.ChampID=$id
	");

	// setup variables

	$se = htmlentities(stripslashes($db->data['SeasonName']));
	$te = htmlentities(stripslashes($db->data['teamname']));

// 6-Jan-2010
$te2 = htmlentities(stripslashes($db->data['TeamName2']));
$te3 = htmlentities(stripslashes($db->data['TeamName3']));

	$ch = $db->data[ChampID];

			
      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Champion</td>\n";
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

	echo "<p>select the season from the drop-down menu<br>\n";
	echo "<select name=\"ChampSeason\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($seasons as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['ChampSeason'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";
	
	echo "<p>select the ChampTeam from the drop-down menu<br>\n";
	echo "<select name=\"ChampTeam\">\n";
	echo "	<option value=\"\">Select the ChampTeam</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($teams as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['ChampTeam'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";	

// 6-Jan-2010
echo "<p>select the Runners-2nd team from the drop-down menu<br>\n";
	echo "<select name=\"Runners\">\n";
	echo "	<option value=\"\">Select 2nd team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($teams as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['ChampTeam2'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

// 6-Jan-2010
echo "<p>select the 3rd team from the drop-down menu<br>\n";
	echo "<select name=\"3rd\">\n";
	echo "	<option value=\"\">Select 3rd team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($teams as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['ChampTeam3'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";


	echo "<p><input type=\"submit\" value=\"edit champ\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$ChampTeam,$ChampSeason, $ChampTeam2, $ChampTeam3)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$se = addslashes(trim($ChampSeason));
	$te = addslashes(trim($ChampTeam));

// 6-Jan-2010
$te2 = addslashes(trim($ChampTeam2));
$te3 = addslashes(trim($ChampTeam3));


// query database

	$db->Update("UPDATE champions SET ChampTeam='$te',ChampSeason='$se', ChampTeam2='$te2',ChampTeam3='$te3' WHERE ChampID=$id");
		echo "<p>You have now updated that champion entry.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the champions listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update champion some more</a></p>\n";
}



// main program

if (!$USER['flags'][$f_champions_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"14px\"><b>Champions Administration</b></p>\n";

switch($do) {
case "sbyseason":
    show_main_menu_season($db,$season);
    break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$ChampTeam,$ChampSeason,$ChampTeam2,$ChampTeam3);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$ChampTeam,$ChampSeason,$ChampTeam2,$ChampTeam3);
	break;
default:
	show_main_menu($db);
	break;
}

?>
