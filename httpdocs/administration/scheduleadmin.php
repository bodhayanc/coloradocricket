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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new scheduled game</a></p>\n";

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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new scheduled game</a></p>\n";
	if (!$db->Exists("SELECT * FROM schedule")) {
		echo "<p>There are currently no scheduled games in the database.</p>\n";
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
      		echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$sename Season Scheduler</td>\n";
      		echo "</tr>\n";
      		echo "<tr>\n";
		echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

				echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"0\">\n";
				echo "<tr class=\"trtop\">\n";
				echo "	<td align=\"left\" width=\"15%\"><b class=\"white\">Date</b></td>\n";
				echo "	<td align=\"left\" width=\"20%\"><b class=\"white\">Visitor @ Home</b></td>\n";
				echo "	<td align=\"left\" width=\"25%\"><b class=\"white\">Umps</b></td>\n";
				echo "	<td align=\"left\" width=\"30%\"><b class=\"white\">Location</b></td>\n";
				echo "	<td align=\"right\" width=\"10%\"><b class=\"white\">&nbsp;</b></td>\n";
				echo "</tr>\n";
			if (!$db->Exists("
				SELECT
					sch.*,
					u1.TeamAbbrev AS Ump1Abbrev,
					u2.TeamAbbrev AS Ump2Abbrev,
					DATE_FORMAT(sch.date, '%b %e') as formatted_date,
					grn.GroundName as ground
				FROM
					schedule sch
				INNER JOIN
					grounds grn on sch.venue = grn.GroundID
				LEFT JOIN
					teams u1 ON sch.umpire1 = u1.TeamID
				LEFT JOIN
					teams u2 ON sch.umpire2 = u2.TeamID					
				WHERE
					sch.season=$season ORDER BY sch.date			
			")) {

					if($x % 2) {
					  echo "<tr class=\"trrow1\">\n";
					} else {
					  echo "<tr class=\"trrow2\">\n";
    				}

				echo "	<td align=\"left\" width=\"100%\" colspan=\"5\"><p>No games yet for $sename.</p></td>\n";
				echo "</tr>\n";
			} else {

				$db->Query("
				SELECT
					sch.*,
					u1.TeamAbbrev AS Ump1Abbrev,
					u2.TeamAbbrev AS Ump2Abbrev,
					DATE_FORMAT(sch.date, '%b %e') as formatted_date,
					grn.GroundName as ground
				FROM
					schedule sch
				INNER JOIN
					grounds grn on sch.venue = grn.GroundID
				LEFT JOIN
					teams u1 ON sch.umpire1 = u1.TeamID
				LEFT JOIN
					teams u2 ON sch.umpire2 = u2.TeamID					
				WHERE
					sch.season=$season ORDER BY sch.date	
				");

				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$t2 = htmlentities(stripslashes($teams[$db->data[hometeam]]));
					$t1 = htmlentities(stripslashes($teams[$db->data[awayteam]]));
					$um = htmlentities(stripslashes($teams[$db->data[umpires]]));
					$tn = htmlentities(stripslashes($db->data['teamname']));
					$da = htmlentities(stripslashes($db->data['formatted_date']));
					$ve = htmlentities(stripslashes($db->data[ground]));
					$u1 = htmlentities(stripslashes($db->data[Ump1Abbrev]));
					$u2 = htmlentities(stripslashes($db->data[Ump2Abbrev]));



					if($x % 2) {
					  echo "<tr class=\"trrow1\">\n";
					} else {
					  echo "<tr class=\"trrow2\">\n";
    				}

					echo "	<td align=\"left\" width=\"15%\">$da</td>\n";
					echo "	<td align=\"left\" width=\"20%\">$t1 @ $t2</td>\n";
					echo "	<td align=\"left\" width=\"25%\">$u1";
					if($u2 != "") echo " and $u2";
					echo "  </td>\n";
					echo "	<td align=\"left\" width=\"30%\">$ve</td>\n";
					echo "	<td align=\"right\" width=\"10%\">";
//					echo "<a href=\"main.php?SID=$SID&action=scheduleadmin&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a>
//<a href=\"main.php?SID=$SID&action=scheduleadmin&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" alt=\"Delete\" border=\"0\"></a></td>\n";

					echo "<a href=\"main.php?SID=$SID&action=scheduleadmin&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a></td>\n";

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

	echo "<p>Add a new scheduled game.</p>\n";

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
		echo "<p>enter the week number<br><input type=\"text\" name=\"week\" size=\"10\" maxlength=\"10\"></p>\n";
		echo "<p>enter the game date <i>(yyyy-mm-dd)</i><br><input type=\"text\" name=\"date\" size=\"40\" maxlength=\"255\"></p>\n";
                                           echo "<p>enter the game time<i>(hh:mm - use 24 hour format 14:00 = 2pm)</i><br><input type=\"text\" name=\"time\" size=\"40\" maxlength=\"255\"></p>\n";
		echo "<p>select away team<br><select name=\"awayteam\">\n";
		echo "	<option value=\"\">Select Visiting Team</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
		}
	}

		echo "</select></p>\n";
		echo "<p>select home team<br><select name=\"hometeam\">\n";
		echo "	<option value=\"\">Select Home Team</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
		}
	}
		echo "</select></p>\n";

		echo "<p>select umpire1:<br>\n";
		echo "  <select name=\"umpire1\">\n";
		echo "	<option value=\"\">Umpire 1</option>\n";
		echo "	<option value=\"\">---------------</option>\n";
		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
			}
		}
		echo "</select></p>\n";


		echo "<p>select umpire2:<br>\n";
		echo "  <select name=\"umpire2\">\n";
		echo "	<option value=\"\">Umpire 2</option>\n";
		echo "	<option value=\"\">---------------</option>\n";
		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
			}
		}
		echo "</select></p>\n";	
	
		echo "<p>select venue<br><select name=\"venue\">\n";
		echo "	<option value=\"\">Select Venue</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM grounds")) {
		$db->Query("SELECT * FROM grounds WHERE LeagueID = 1 ORDER BY GroundID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['GroundID'] . "\">" . $db->data['GroundName'] . "</option>\n";
		}
	}
		echo "</select></p>\n";
		echo "<p><input type=\"submit\" value=\"add schedule\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
		echo "</form>\n";
}


function do_add_category($db,$season,$week,$date,$awayteam,$hometeam,$venue,$umpires,$umpire1,$umpire2, $time)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// make sure info is present and correct

	if ($season == "" || $week == "" || $date == "" || $awayteam == "" || $hometeam == "" || $venue == "") {
		echo "<p>You must select a season, a week, enter the date, awayteam, hometeam, umpiring team and venue.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to schedule list</a></p>\n";
		return;
	}

	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$da = addslashes(trim($date));
	$t1 = addslashes(trim($awayteam));
	$t2 = addslashes(trim($hometeam));
	$ve = addslashes(trim($venue));
	$um = addslashes(trim($umpires));
	$um1 = addslashes(trim($umpire1));
	$um2 = addslashes(trim($umpire2));
                     $ti = addslashes(trim($time));

	// check to see if it exists first
	if ($db->Exists("SELECT * FROM schedule WHERE date='$da' AND awayteam='$t1' AND hometeam='$t2'")) {
		echo "<p>That game already exists in the database.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to schedule list</a></p>\n";
		return;
	}
	// all okay
	$db->Insert("INSERT INTO schedule (season, league_id, week, date, awayteam, hometeam, venue, umpires, umpire1, umpire2, time) VALUES ('$se','1','$we','$da','$t1','$t2','$ve','$um','$um1','$um2', '$ti')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new game.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">add another scheduled game</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to schedule list</a></p>\n";
	} else {
		echo "<p>The game could not be added to the database at this time.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to schedule list</a></p>\n";
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

	$db->Query("SELECT * FROM grounds ORDER BY GroundName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$grounds[$db->data['GroundID']] = $db->data['GroundName'];
	}

	$db->QueryItem("SELECT * FROM schedule WHERE id=$id");

	$date = sqldate_to_string($db->data[date]);
	$t1 = htmlentities(stripslashes($teams[$db->data[awayteam]]));
	$t2 = htmlentities(stripslashes($teams[$db->data[hometeam]]));
	$ve = htmlentities(stripslashes($grounds[$db->data[venue]]));

	echo "<p>Are you sure you wish to delete the following scheduled game:</p>\n";
	echo "<p><b>$date</b></p>\n";
	echo "<p><b>$t1</b> v <b>$t2</b> at <b>$ve</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	if (!$doit) echo "<p>You have chosen NOT to delete that scheduled game.</p>\n";
	else {
		$db->Delete("DELETE FROM schedule WHERE id=$id");
		echo "<p>You have now deleted that scheduled game.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the schedule list</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

        $db_se = $db;
	// get all seasons
	$db_se->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
	

	// get all teams
	$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
        $db->BagAndTag();
		$teams[$db->data['TeamID']] = $db->data['teamname'];
		$teams2 = $teams;
		//$umpires = $teams;
	}
	// get all grounds
	$db->Query("SELECT * FROM grounds WHERE LeagueID = 1 ORDER BY GroundID");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
        $db->BagAndTag();
		$grounds[$db->data['GroundID']] = $db->data['GroundName'];
	}

	
	// get article details

	$db->QueryRow("SELECT s.*,g.GroundName, t1.TeamName AS awayteamname,t2.TeamName as hometeamname,u1.TeamAbbrev AS Ump1Abbrev,u2.TeamAbbrev AS Ump2Abbrev FROM schedule s INNER JOIN teams t1 ON s.awayteam = t1.TeamID INNER JOIN teams t2 ON s.hometeam = t2.TeamID LEFT JOIN teams u1 ON s.umpire1 = u1.TeamID LEFT JOIN teams u2 ON s.umpire2 = u2.TeamID INNER JOIN grounds g ON s.venue = g.GroundID WHERE id=$id");

	$se = stripslashes($db->data[season]);
	$we = stripslashes($db->data[week]);
	$da = stripslashes($db->data[date]);
	$t1 = stripslashes($db->data[awayteam]);
	$t1n = stripslashes($db->data[awayteamname]);
	$t2 = stripslashes($db->data[hometeam]);
	$t2n = stripslashes($db->data[hometeamname]);
	$ve = stripslashes($db->data[venue]);
	$ven = stripslashes($db->data['GroundName']);
	$um = stripslashes($db->data[umpires]);
	$um1 = stripslashes($db->data[umpire1]);
	$um1a = stripslashes($db->data[Ump1Abbrev]);
	$um2a = stripslashes($db->data[Ump2Abbrev]);
	$um2 = stripslashes($db->data[umpire2]);
	$re = stripslashes($db->data[result]);
                     $ti = stripslashes($db->data[time]);

	echo "<p>Edit the scheduled game.</p>\n";

		echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
		echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
		echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
		echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
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
				echo "<option value=\"$season\"" . ($season ==$db->data[season]?" selected":"") . ">" . $sename . "</option>\n";
			}

		echo "</select></p>\n";

		echo "<p>enter the week<br><input type=\"text\" name=\"week\" size=\"10\" maxlength=\"10\" value=\"$we\"></p>\n";

		echo "<p>enter the game date <i>(yyyy-mm-dd)</i><br><input type=\"text\" name=\"date\" size=\"40\" maxlength=\"255\" value=\"$da\"></p>\n";
                                           echo "<p>enter the game time <i>(hh:mm - use 24 hour format 14:00 = 2pm)</i><br><input type=\"text\" name=\"time\" size=\"40\" maxlength=\"255\" value=\"$ti\"></p>\n";


		echo "<p>select away team:<br>\n";
		echo "<select name=\"awayteam\">\n";
		echo "	<option value=\"\">Select Visitor Team</option>\n";
		echo "<option value=\"$t1\" selected>$t1n</option>\n";
	
		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
			}
		}

		echo "</select></p>\n";

		echo "<p>select home team:<br>\n";
		echo "<select name=\"hometeam\">\n";
		echo "	<option value=\"\">Select Home Team</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";
		echo "<option value=\"$t2\" selected>$t2n</option>\n";
	
		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['teamname'] . "</option>\n";
			}
		}

		echo "</select></p>\n";

		echo "<p>select umpire1:<br>\n";
		echo "  <select name=\"umpire1\">\n";
		echo "	<option value=\"\">Umpire 1</option>\n";
		echo "	<option value=\"\">---------------</option>\n";
		echo "  <option value=\"$um1\" selected>$um1a</option>\n";
		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
			}
		}
		echo "</select></p>\n";


		echo "<p>select umpire2:<br>\n";
		echo "  <select name=\"umpire2\">\n";
		echo "	<option value=\"\">Umpire 2</option>\n";
		echo "	<option value=\"\">---------------</option>\n";
		echo "  <option value=\"$um2\" selected>$um2a</option>\n";
		if ($db->Exists("SELECT * FROM teams")) {
			$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
			}
		}

		echo "</select></p>\n";		
	
		echo "<p>select venue:<br>\n";
		echo "<select name=\"venue\">\n";
		echo "	<option value=\"\">Select Venue</option>\n";
		echo "	<option value=\"\">--------------------------</option>\n";
		echo "<option value=\"$ve\" selected>$ven</option>\n";
	
		if ($db->Exists("SELECT * FROM grounds")) {
			$db->Query("SELECT * FROM grounds WHERE LeagueID = 1 ORDER BY GroundName");
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['GroundID'] . "\">" . $db->data['GroundName'] . "</option>\n";
			}
		}

		echo "</select></p>\n";

		echo "<p><input type=\"submit\" value=\"edit schedule\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
		echo "</form>\n";

}


function do_update_category($db,$id,$season,$week,$date,$awayteam,$hometeam,$venue,$umpires,$umpire1,$umpire2, $time)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// make sure info is present and correct

	if ($season == "" || $week == "" || $date == "" || $awayteam == "" || $hometeam == "" || $venue == "") {
		echo "<p>You must select a season, a week, enter the date, awayteam, hometeam, umpiring team and venue.</p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sedit&id=$id\">try again</a></p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to schedule list</a></p>\n";
		return;
	}

	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$da = addslashes(trim($date));
	$t1 = addslashes(trim($awayteam));
	$t2 = addslashes(trim($hometeam));
	$ve = addslashes(trim($venue));
	$um = addslashes(trim($umpires));
	$um1 = addslashes(trim($umpire1));
	$um2 = addslashes(trim($umpire2));
                     $ti = addslashes(trim($time));
	$re = addslashes(trim($result));
	$pa = eregi_replace("\r","",$photo);

	// do update
	$db->Update("UPDATE schedule SET season='$se',league_id='1',week='$we',date='$da',awayteam='$t1',hometeam='$t2',venue='$ve',umpires='$um',umpire1='$um1',umpire2='$um2', time='$ti' WHERE id=$id");
	if ($db->a_rows != -1) {
		echo "<p>You have now updated that schedule.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the schedule list</a></p>\n";
	} else {
		echo "<p>That schedule could not be changed at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the schedule list</a></p>\n";
	}
}



// do scorecard stuff here - doesn't like being passed to a function!

if ($usercard_name != "") {
	$scorecard = urldecode($usercard_name);
	$scorecard = ereg_replace(" ","_",$scorecard);
	$scorecard = ereg_replace("&","_and_",$scorecard);

// put scorecard in right place

	if (!copy($usercard,"../scorecards/$season/$scorecard")) {
		echo "<p>That scorecard could not be uploaded at this time - no scorecard was added to the database.</p>\n";
		unlink($usercard);
		return;
	}
	unlink($usercard);
	$setcard = ",scorecard='$scorecard'";
} else {
	$scorecard = "";
	$setcard = "";
}


// main program

if (!$USER['flags'][$f_schedule_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Schedule Administration</b></p>\n";

switch($do) {
case "byseason":
    	show_main_menu_season($db,$season,$sename);
    	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$season,$week,$date,$awayteam,$hometeam,$venue,$umpires,$umpire1,$umpire2, $time);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$season,$week,$date,$awayteam,$hometeam,$venue,$umpires,$umpire1,$umpire2, $time);
	break;
default:
	show_main_menu($db);
	break;
}

?>
