<?php

//------------------------------------------------------------------------------
// Team Administration v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new team to a group</a></p>\n";

	echo "<p>or, please select a season to work with.</p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM seasons ORDER BY SeasonName DESC")) {
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

		$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");
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

function show_main_menu_season($db,$season,$sename)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new team to a group</a></p>\n";
	if (!$db->Exists("SELECT * FROM groups")) {
		echo "<p>There are currently no groups in the database.</p>\n";
		return;
	} else {
                $db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName ASC");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
                }

      		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      		echo "<tr>\n";
      		echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$sename Season Groups</td>\n";
      		echo "</tr>\n";
      		echo "<tr>\n";
		echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

				echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"0\">\n";
				echo "<tr class=\"trtop\">\n";
				echo "	<td align=\"left\" width=\"45%\"><b class=\"white\">Team</b></td>\n";
				echo "	<td align=\"left\" width=\"15%\"><b class=\"white\">Round</b></td>\n";
				echo "	<td align=\"left\" width=\"30%\"><b class=\"white\">Group</b></td>\n";
				echo "	<td align=\"left\" width=\"10%\"></td>\n";
				echo "</tr>\n";
			if (!$db->Exists("
				SELECT *
				FROM
					groups sch
				WHERE
					SeasonID=$season ORDER BY Round DESC
			")) {

					if($x % 2) {
					  echo "<tr class=\"trrow1\">\n";
					} else {
					  echo "<tr class=\"trrow2\">\n";
    				}

				echo "	<td align=\"left\" width=\"100%\" colspan=\"5\"><p>No groups yet for $sename.</p></td>\n";
				echo "</tr>\n";
			} else {

				$db->Query("
				SELECT *
				FROM
					groups sch
				WHERE
					SeasonID=$season ORDER BY Round DESC, GroupName ASC
				");

				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$tm = htmlentities(stripslashes($teams[$db->data['TeamID']]));
					$gp = htmlentities(stripslashes($db->data['GroupName']));
					$rd = htmlentities(stripslashes($db->data['Round']));
					if($x % 2) {
					  echo "<tr class=\"trrow1\">\n";
					} else {
					  echo "<tr class=\"trrow2\">\n";
    				}

					echo "	<td align=\"left\" width=\"45%\">$tm</td>\n";
					echo "	<td align=\"left\" width=\"15%\">$rd</td>\n";
					echo "	<td align=\"left\" width=\"30%\">$gp</td>\n";
					echo "	<td align=\"right\" width=\"10%\">";
					echo "<a href=\"main.php?SID=$SID&action=groupadmin&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a>
<a href=\"main.php?SID=$SID&action=groupadmin&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" alt=\"Delete\" border=\"0\"></a></td>\n";

//					echo "<a href=\"main.php?SID=$SID&action=scheduleadmin&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a></td>\n";

					echo "</tr>\n";
				}
			}
		}
					echo "</table>\n";
					
					echo "</td>\n";
					echo "</tr>\n";
					echo "</table>\n";
}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>Add a team to a group.</p>\n";

		echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
		echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
		echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
		echo "<select name=\"season\">\n";
		echo "	<option value=\"\">Select a season</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM seasons")) {
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">Season " . $db->data['SeasonName'] . "</option>\n";
		}
	}

		echo "</select>\n";
		echo "<p>select team<br><select name=\"team\">\n";
		echo "	<option value=\"\">Select Team</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamName'] . "</option>\n";
			}
		}
		echo "</select>\n";
		
		echo "<p>select round<br><select name=\"round\">\n";
		echo "	<option value=\"\">Select Round</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		echo "<option value=\"1\">1</option>\n";
		echo "<option value=\"2\">2</option>\n";
		echo "<option value=\"3\">3</option>\n";
		echo "<option value=\"4\">4</option>\n";
	

		echo "</select></p>\n";
		echo "<p>select Group<br><select name=\"group\">\n";
		echo "	<option value=\"\">Select Group</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		echo "<option value=\"A\">A</option>\n";
		echo "<option value=\"B\">B</option>\n";
		echo "<option value=\"C\">C</option>\n";
		echo "<option value=\"D\">D</option>\n";
	

		echo "</select></p>\n";

		echo "<p><input type=\"submit\" value=\"add group\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
		echo "</form>\n";
}


function do_add_category($db,$season,$team,$round,$group)
{
	global $PHP_SELF,$content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// make sure info is present and correct

	if ($season == "" || $team == "" || $round == "" || $group == "") {
		echo "<p>You must select a season, a team, round and group.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
		return;
	}

	$se = addslashes(trim($season));
	$rd = addslashes(trim($round));
	$gp = addslashes(trim($group));
	$te = addslashes(trim($team));
	
	// check to see if it exists first
	if ($db->Exists("SELECT * FROM groups WHERE SeasonID='$se' AND TeamID='$te' AND Round='$rd' AND GroupName='$gp'")) {
		echo "<p>That team is already added to the group.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
		return;
	}
	// all okay
	$db->Insert("INSERT INTO groups (SeasonID, TeamID, GroupName, Round) VALUES ('$se','$te','$gp','$rd')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new team to a group.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">add another team</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
	} else {
		echo "<p>The team could not be added to the group at this time.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	$db->Query("SELECT * FROM teams ORDER BY TeamName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
	}
	
	$db->QueryItem("SELECT * FROM groups WHERE id=$id");

	$te = htmlentities(stripslashes($teams[$db->data['TeamID']]));
	$gp = htmlentities(stripslashes($db->data['GroupName']));
	$rd = htmlentities(stripslashes($db->data['Round']));
	
	echo "<p>Are you sure you wish to delete the team <b>$te</b> from group <b>$gp</b> for round <b>$rd</b>?</p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	if (!$doit) echo "<p>You have chosen NOT to delete that team from that group.</p>\n";
	else {
		$db->Delete("DELETE FROM groups WHERE id=$id");
		echo "<p>You have now removed that team from that group.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the group list</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $dbcfg,$content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    $db_se = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_se->SelectDB($dbcfg['db']);
	// get all seasons
	$db_se->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
	

	// get all teams
	$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
        $db->BagAndTag();
		$teams[$db->data['TeamID']] = $db->data['TeamName'];
	}

	
	// get group details

	$db->QueryRow("SELECT * from groups WHERE id=$id");

	$se = stripslashes($db->data['SeasonID']);
	$te = stripslashes($db->data['TeamID']);
	$gp = stripslashes($db->data['GroupName']);
	$rd = stripslashes($db->data['Round']);
	
	echo "<p>Edit the group.</p>\n";

		echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
		echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
		echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";


		echo "<p>Select a season:<br>\n";
		echo "<select name=\"season\">\n";
		echo "	<option value=\"\">Select a season</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		for ($i=0; $i<$db_se->rows; $i++) {
		$db_se->GetRow($i);
		$db_se->BagAndTag();

		// output
		$id = $db_se->data['SeasonID'];
		$season = $db_se->data['SeasonID'];
		$sename = $db_se->data['SeasonName'];
			echo "<option value=\"$season\"" . ($season ==$db->data['SeasonID']?" selected":"") . ">" . $sename . "</option>\n";
		}

		echo "</select></p>\n";

		echo "<p>select team<br><select name=\"team\">\n";
		echo "	<option value=\"\">Select Team</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		if ($db_se->Exists("SELECT * FROM teams")) {
			$db_se->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db_se->rows; $i++) {
				$db_se->GetRow($i);
				$db_se->BagAndTag();

				$id = $db_se->data['TeamID'];
				echo "<option ". ($te ==$db_se->data['TeamID']?" selected":"") . " value=\"" . $db_se->data['TeamID'] . "\">" . $db_se->data['TeamName'] . "</option>\n";
			}
		}
		echo "</select>\n";
		
		echo "<p>select round<br><select name=\"round\">\n";
		echo "	<option value=\"\">Select Round</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		echo "<option " . ($rd == '1'?" selected":"") . " value=\"1\">1</option>\n";
		echo "<option " . ($rd == '2'?" selected":"") . " value=\"2\">2</option>\n";
		echo "<option " . ($rd == '3'?" selected":"") . " value=\"3\">3</option>\n";
		echo "<option " . ($rd == '4'?" selected":"") . " value=\"4\">4</option>\n";
	

		echo "</select></p>\n";
		echo "<p>select Group<br><select name=\"group\">\n";
		echo "	<option value=\"\">Select Group</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

		echo "<option " . ($gp == 'A'?" selected":"") . " value=\"A\">A</option>\n";
		echo "<option " . ($gp == 'B'?" selected":"") . " value=\"B\">B</option>\n";
		echo "<option " . ($gp == 'C'?" selected":"") . " value=\"C\">C</option>\n";
		echo "<option " . ($gp == 'D'?" selected":"") . " value=\"D\">D</option>\n";
	

		echo "</select></p>\n";

		echo "<p><input type=\"submit\" value=\"edit group\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
		echo "</form>\n";

}


function do_update_category($db,$id,$season,$team,$round,$group)
{
	global $PHP_SELF,$content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// make sure info is present and correct

	if ($season == "" || $team == "" || $round == "" || $group == "") {
		echo "<p>You must select a season, a team, round and group.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sedit&id=$id\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
		return;
	}

	$se = addslashes(trim($season));
	$rd = addslashes(trim($round));
	$gp = addslashes(trim($group));
	$te = addslashes(trim($team));
	
	// do update
	$db->Update("UPDATE groups SET SeasonID = '$se', TeamID = '$te', GroupName = '$gp', Round = '$rd' WHERE id=$id");
	if ($db->a_rows != -1) {
		echo "<p>You have now updated the group.</p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
	} else {
		echo "<p>The team could not be updated at this time.</p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to group list</a></p>\n";
	}
}

// main program

if (!$USER['flags'][$f_schedule_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Group Administration</b></p>\n";

if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
}
else {
	$do = '';
}

if(isset($_GET['doit'])) {
	$doit = $_GET['doit'];
} else if(isset($_POST['doit'])) {
	$doit = $_POST['doit'];
}

switch($do) {
case "byseason":
    	show_main_menu_season($db,$_GET['season'],$_GET['sename']);
    	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$_POST['season'],$_POST['team'],$_POST['round'],$_POST['group']);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$_GET['id']);
	else do_delete_category($db,$_GET['id'],$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$_GET['id']);
	else do_update_category($db,$_POST['id'],$_POST['season'],$_POST['team'],$_POST['round'],$_POST['group']);
	break;
default:
	show_main_menu($db);
	break;
}

?>
