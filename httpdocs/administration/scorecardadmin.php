<?php

//------------------------------------------------------------------------------
// Scorecards v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new scorecard</a></p>\n";

	echo "<p>or, please select a season to work with.</p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM scorecard_game_details")) {
		echo "<p>There are currently no scorecard in the database.</p>\n";
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

		$db->QueryRow("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();

			// output
			$id = $db->data['season'];
			$season = $db->data['season'];
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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new scorecard</a></p>\n";
	if (!$db->Exists("SELECT * FROM scorecard_game_details")) {
		echo "<p>There are currently no scorecard in the database.</p>\n";
		return;
	} else {
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

		$db->QueryRow("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();

			// output
			$id = $db->data['season'];
			$snm = $db->data['SeasonName'];
			$seasons[$id] = $snm;
			if($id == $season) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "    <option $selected value=\"main.php?SID=$SID&action=$action&do=byseason&season=$id&sename=$snm\">" . $snm . " season</option>\n";

		}

		echo "    </select></p>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
        	
      		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      		echo "<tr>\n";
      		echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$sename Season Scorecards</td>\n";
      		echo "</tr>\n";
      		echo "<tr>\n";
			echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"0\">\n";
			echo "<tr class=\"trtop\">\n";
			echo "	<td align=\"left\" width=\"15%\"><b class=\"white\">Date</b></td>\n";
			echo "	<td align=\"left\" width=\"20%\"><b class=\"white\">Visitor @ Home</b></td>\n";
			echo "	<td align=\"left\" width=\"25%\"><b class=\"white\">Result</b></td>\n";
			echo "	<td align=\"right\" width=\"10%\"><b class=\"white\">&nbsp;</b></td>\n";
			echo "</tr>\n";
			if (!$db->Exists("
				SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$season AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName")) {

					if($x % 2) {
					  echo "<tr class=\"trrow1\">\n";
					} else {
					  echo "<tr class=\"trrow2\">\n";
    				}

				echo "	<td align=\"left\" width=\"100%\" colspan=\"5\"><p>No games yet for $sename.</p></td>\n";
				echo "</tr>\n";
			} else {

				$db->Query("SELECT
				  s.*,
				  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
				  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev'
				FROM
				  scorecard_game_details s
				INNER JOIN
				  teams a ON s.awayteam = a.TeamID
				INNER JOIN
				  teams h ON s.hometeam = h.TeamID
				WHERE
				  s.season=$season AND s.isactive=0 AND (s.league_id = 1 OR league_id = 4)
				ORDER BY
				  s.game_date, s.game_id
				");

				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$t1 = $db->data['homeabbrev'];
					$t2 = $db->data['awayabbrev'];
					$t1id = $db->data['homeid'];
					$t2id = $db->data['awayid'];
					$d = sqldate_to_string($db->data['game_date']);
					$re = $db->data['result'];
					$id = $db->data['game_id'];
					$wk = $db->data['week'];
					$fo = $db->data['forfeit'];
					$ca = $db->data['cancelled'];
					if($x % 2) {
					  echo "<tr class=\"trrow1\">\n";
					} else {
					  echo "<tr class=\"trrow2\">\n";
					}

					echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
					echo "  <td align=\"left\" class=\"9px\">$t2 at $t1</td>\n";

					echo "  <td align=\"left\" class=\"9px\">$re</td>\n";
					echo "	<td align=\"right\" width=\"10%\">";
					echo "<a href=\"main.php?SID=$SID&action=scorecardadmin&do=sedit&game_id=" . $id . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a>
<a href=\"main.php?SID=$SID&action=scorecardadmin&do=sdel&game_id=" . $id . "\"><img src=\"/images/icons/icon_delete.gif\" alt=\"Delete\" border=\"0\"></a></td>\n";

//					echo "<a href=\"main.php?SID=$SID&action=scheduleadmin&do=sedit&game_id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a></td>\n";

					echo "</tr>\n";
				}
			}
		}
					echo "</table>\n";
					
					echo "</td>\n";
					echo "</tr>\n";
					echo "</table>\n";
}

function add_scorecard_step1($db)
{

	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

 	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";
	
	echo "<p class=\"14px\">Step 1 - Enter Game Details<br><img src=\"/images/0.gif\"></p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter the Game Details</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
//	echo "    <td><a href=\"http://www.mozilla.org/products/firefox/\" target=\"_new\"><img src=\"/images/firefox.jpg\" border=\"0\" align=\"right\"></a>\n";

	echo "<form name=\"frm\" action=\"main.php?SID=$SID&action=scorecardadmin&do=insert\" method=\"post\" enctype=\"multipart/form-data\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"league_id\" required msg=\"Please select a league from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select a league</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data[LeagueID] . "\"> " . $db->data['LeagueName'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">League <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"season\" required msg=\"Please select a season from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM seasons")) {
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">Season " . $db->data['SeasonName'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Season <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input name=\"week\" size=\"10\" maxlength=\"10\" required filter=\"[0-9]\" msg=\"Please enter the week number using only numbers\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Week # <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" size=\"20\" maxlength=\"255\" name=\"game_date\">\n";
	echo "<script language=\"javascript\" src=\"/includes/javascript/simplecalendar.js\" type=\"text/javascript\"></script>";
	echo "<link rel=\"stylesheet\" href=\"/includes/css/calendar.css\" type=\"text/css\">";
	echo "  <a href=\"javascript: void(0);\" onmouseover=\"if (timeoutId) clearTimeout(timeoutId);window.status='Show Calendar';return true;\" onmouseout=\"if (timeoutDelay) calendarTimeout();window.status='';\" onclick=\"g_Calendar.show(event,'frm.game_date',false); return false;\"><img src=\"http://www.coloradocricket.org/images/calendar/calendar.gif\" name=\"imgCalendar\" border=\"0\" alt=\"\" style=\"vertical-align: middle\"></a>\n"; 
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Game Date (yyyy-mm-dd) <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam\" required msg=\"Please select the visiting team from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Visiting Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	// Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam_captain\" >\n";
	echo "	<option value=\"\">Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Captain</td>\n";
	echo " </tr>\n";
	
	// Vice Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam_vcaptain\"  >\n";
	echo "	<option value=\"\">Vice Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Vice Captain</td>\n";
	echo " </tr>\n";
	
	// W Keeper 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam_wk\"  >\n";
	echo "	<option value=\"\">Wicket Keeper</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Wicket Keeper</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam\" required msg=\"Please select the home team from the drop down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Home Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	// Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam_captain\" >\n";
	echo "	<option value=\"\">Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Captain</td>\n";
	echo " </tr>\n";
	
	// Vice Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam_vcaptain\"  >\n";
	echo "	<option value=\"\">Vice Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Vice Captain</td>\n";
	echo " </tr>\n";
	
	// W Keeper 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam_wk\" >\n";
	echo "	<option value=\"\">Wicket Keeper</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Wicket Keeper</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpires\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Umpiring Team</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"toss_won_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Who Won Toss?</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"batting_first_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Team Batting First <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"batting_second_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Team Batting Second <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"result_won_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Victorious Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"> (only if a result occurred)</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"ground_id\">\n";
	echo "	<option value=\"\">Select Venue</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM grounds")) {
		$db->Query("SELECT * FROM grounds ORDER BY GroundID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['GroundID'] . "\">" . $db->data['GroundName'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Venue <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"radio\" name=\"result_type\" checked value=\"normal\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if match ended normally</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"radio\" name=\"result_type\" value=\"tied\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if match tied <img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"radio\" name=\"result_type\" value=\"forfeit\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if won by forfeit <img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"radio\" name=\"result_type\" value=\"cancelled\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with no play <img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"radio\" name=\"result_type\" value=\"cancelledplay\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with some play</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"result\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Result <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	//echo " <tr>\n";
	//echo "  <td width=\"50%\" align=\"right\">";
	//echo "  <input type=\"text\" name=\"mom\" size=\"20\" maxlength=\"255\">\n";
	//echo "  </td>\n";
	//echo "  <td width=\"50%\" align=\"left\">Enter Man of the Match</td>\n";
	//echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"mom\" id=\"combobox1\" >\n";
	echo "	<option value=\"\">Man of the match</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Man of Match</td>\n";
	echo " </tr>\n";
	
	// Man of Match2 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"mom2\">\n";
	echo "	<option value=\"\">Man of the match 2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Man of Match 2</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpire1\" id=\"combobox1\" >\n";
	echo "	<option value=\"\">Umpire 1</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Umpire 1</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpire2\" id=\"combobox1\" >\n";
	echo "	<option value=\"\">Umpire 2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";	
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Umpire 2</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"maxovers\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Max Overs <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"> (example: 40)</td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"cricclubs_game_id\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">CricClubs Game ID</td>\n";
	echo " </tr>\n";	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"report\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Match Report Link</td>\n";
	echo " </tr>\n";	

	//disabling captcha as it is part of admin pancel now
/* 	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	include("simple-php-captcha.php");
	$_SESSION['captcha'] = simple_php_captcha();	
	echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">";
	echo "<p>please enter above code correctly to submit this page<br><input type=\"text\" name=\"code\" size=24/></p>";
	echo "  </td>\n";
	echo " </tr>\n";
 */	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input name=\"submit\" type=\"submit\" value=\"Save and Next\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">&nbsp;</td>\n";
	echo " </tr>\n";


	echo "</table>\n";

	echo "</form>\n";
	echo "<script src=\"../includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";

	echo "<p><img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"> fields are required. <br><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"> fields are required for a forfeit game. <br><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"> fields are required for a cancelled game.<br><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"> fields are required for a tied game.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	ob_end_flush();

}

function insert_scorecard_step1($db,$league_id,$season,$week,$awayteam, $awayteam_captain, $awayteam_vcaptain, $awayteam_wk, $hometeam, $hometeam_captain, $hometeam_vcaptain, $hometeam_wk ,$umpires,$toss_won_id,$result_won_id,$batting_first_id,$batting_second_id,$ground_id,
$ground_name,$game_date,$result,$result_type,$mom, $mom2,$umpire1,$umpire2,$maxovers,$cricclubs_game_id,$report)
{

	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	//disabling captcha as it is within admin pancel now
/* 	if(strtolower($_POST['code']) != strtolower($_SESSION['captcha']['code'])) {
    	echo "<p>Sorry, the code you entered was invalid.  Please try again.</p>";
    	return;
  	}
 */
    //////////////////////////////////////////////////////////////////////////////////////////			
	// This is a forfeited game
	//////////////////////////////////////////////////////////////////////////////////////////		

	if($result_type == "forfeit") {

	// make sure forfeit info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $result_won_id == "" || $game_date == "" || $week == "") {
		echo "<p class=\"12pt\"><b>Forfeit Game Missing information</b></p>\n";
		echo "<p>You must complete all the required (<img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\">) forfeit game fields. Please go back and try again.</p>\n";
		return;
	}	
	
	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$gd = addslashes(trim($game_date));
	$fo = 1;
	$rw = addslashes(trim($result_won_id));
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Insert into the scorecard_game_details table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,awayteam_captain,awayteam_vcaptain,awayteam_wk,hometeam,hometeam_captain,hometeam_vcaptain,hometeam_wk,game_date,result_won_id,forfeit,mom, mom2,umpire1,umpire2,maxovers,cricclubs_game_id,report,isactive) VALUES  ('$li','$se','$we','$at','$at_c','$at_vc','$at_wk','$ht','$ht_c','$ht_vc','$ht_wk','$gd','$rw','$fo','$mm','$mm2','$u1','$u2','$mo','$cci','$mr',0)");
	$db->QueryRow("SELECT LAST_INSERT_ID() AS GAME_ID");
	$game_id = $db->data['GAME_ID'];
	
	// Update the results table for the home team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$hometeam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homepla = $db->data['played'];
		$homewon = $db->data['won'];
		$homelos = $db->data['lost'];
	
	$db->update("UPDATE results SET played = $homepla + 1 WHERE team_id = $hometeam");
	if($hometeam == $result_won_id) {
	  $db->update("UPDATE results SET won = $homewon + 1 WHERE team_id = $hometeam");
	} else {
	  $db->update("UPDATE results SET lost = $homelos + 1 WHERE team_id = $hometeam");
	}
	
	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$hometeam','1','0','0','0','0')");
	}
	
	// Update the results table for the away team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$awayteam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaypla = $db->data['played'];
		$awaywon = $db->data['won'];
		$awaylos = $db->data['lost'];
	
	$db->update("UPDATE results SET played = $awaypla + 1 WHERE team_id = $awayteam");
	if($awayteam == $result_won_id) {
	  $db->update("UPDATE results SET won = $awaywon + 1 WHERE team_id = $awayteam");
	} else {
	  $db->update("UPDATE results SET lost = $awaylos + 1 WHERE team_id = $awayteam");
	}	

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$awayteam','1','0','0','0','0')");
	}
	
	header("Location: main.php?SID=$SID&action=$action&do=update6&game_id=$game_id");
	ob_end_flush();
	
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a cancelled game	
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else if($result_type == "cancelled") {
	
	// make sure cancelled info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $game_date == "" || $week == "") {
		echo "<p class=\"12pt\"><b>Cancelled Game Missing information</b></p>\n";
		echo "<p>You must complete all the required (<img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\">) cancelled game fields. Please go back and try again.</p>\n";
		return;
	}	
	
	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$gd = addslashes(trim($game_date));
	$ca = 1;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Insert into the game header table

	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,awayteam_captain,awayteam_vcaptain,awayteam_wk,hometeam,hometeam_captain,hometeam_vcaptain,hometeam_wk,game_date,cancelled,cancelledplay,mom,mom2,umpire1,umpire2,maxovers,cricclubs_game_id,report,isactive) VALUES 
('$li','$se','$we','$at','$at_c','$at_vc','$at_wk','$ht','$ht_c','$ht_vc','$ht_wk','$gd','$ca','$cg','$mm','$mm2','$u1','$u2','$mo','$cci','$mr',0)");
	$db->QueryRow("SELECT LAST_INSERT_ID() AS GAME_ID");
	$game_id = $db->data['GAME_ID'];
	
	// Update the results table for the home team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$hometeam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homenr = $db->data[nr];
		$homepl = $db->data['played'];
	
	$db->update("UPDATE results SET nr = $homenr + 1, played = $homepl + 1 WHERE team_id = $hometeam");

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$hometeam','1','0','0','0','1')");
	}
	
	// Update the results table for the away team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$awayteam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaynr = $db->data[nr];
		$awaypl = $db->data['played'];

	$db->update("UPDATE results SET nr = $awaynr + 1, played = $awaypl +1 WHERE team_id = $awayteam");

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$awayteam','1','0','0','0','1')");
	}
	
	header("Location: main.php?SID=$SID&action=$action&do=update6&game_id=$game_id");
	ob_end_flush();

	//////////////////////////////////////////////////////////////////////////////////////////
	// This is a tied game
	//////////////////////////////////////////////////////////////////////////////////////////

	} else if($result_type == "tied") {	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" || $game_date == "" || $tied == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables
	
	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 1;
	$fo = 0;
	$ca = 0;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Insert into the game header table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,awayteam_captain,awayteam_vcaptain,awayteam_wk,hometeam,hometeam_captain,hometeam_vcaptain,hometeam_wk,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,
cancelled,cancelledplay,mom, mom2,umpire1,umpire2,maxovers,cricclubs_game_id,report,isactive) VALUES ('$li','$se','$we','$at','$at_c','$at_vc','$at_wk','$ht','$ht_c','$ht_vc','$ht_wk','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mm2','$u1','$u2','$mo','$cci','$mr',0)");
	$db->QueryRow("SELECT LAST_INSERT_ID() AS GAME_ID");
	$game_id = $db->data['GAME_ID'];
	
	// Update the results table for the home team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$hometeam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homepla = $db->data['played'];
		$hometie = $db->data['tied'];
	
	$db->update("UPDATE results SET played = $homepla + 1, tied = $hometie + 1 WHERE team_id = $hometeam");

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$hometeam','1','0','0','1','0')");
	}
	
	// Update the results table for the away team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$awayteam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaypla = $db->data['played'];
		$awaytie = $db->data['tied'];
	
	$db->update("UPDATE results SET played = $awaypla + 1, tied = $awaytie + 1 WHERE team_id = $awayteam");

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$awayteam','1','0','0','1','0')");
	}
	
	header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////
	// This is a cancelled game with some play
	//////////////////////////////////////////////////////////////////////////////////////////

	} else if($result_type == "cancelledplay") {	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" || $game_date == "" || $cancelledplay == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables

	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 0;
	$fo = 0;
	$ca = 0;
	$cg = 1;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Insert into the game header table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,awayteam_captain,awayteam_vcaptain,awayteam_wk,hometeam,hometeam_captain,hometeam_vcaptain,hometeam_wk,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,
cancelled,cancelledplay,mom, mom2,umpire1,umpire2,maxovers,cricclubs_game_id,report,isactive) VALUES ('$li','$se','$we','$at','$at_c','$at_vc','$at_wk','$ht','$ht_c','$ht_vc','$ht_wk','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mm2','$u1','$u2','$mo','$cci','$mr',0)");
	$db->QueryRow("SELECT LAST_INSERT_ID() AS GAME_ID");
	$game_id = $db->data['GAME_ID'];
	
	// Update the results table for the home team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$hometeam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homepla = $db->data['played'];
		$homecap = $db->data[nr];
	
	$db->update("UPDATE results SET played = $homepla + 1, nr = $homecap + 1 WHERE team_id = $hometeam");

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$hometeam','1','0','0','0','1')");
	}
	
	// Update the results table for the away team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$awayteam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaypla = $db->data['played'];
		$awaycap = $db->data[nr];
	
	$db->update("UPDATE results SET played = $awaypla + 1, nr = $awaycap + 1 WHERE team_id = $awayteam");

	} else {
	$db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$awayteam','1','0','0','0','1')");
	}
	
	header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a normal game
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else {
	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" && $ground_name == "" || $game_date == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables

	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gn = addslashes(trim($ground_name));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 0;
	$fo = 0;
	$ca = 0;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

 	// Insert into the game header table
	
	$db->Insert("INSERT INTO scorecard_game_details 

(league_id,season,week,awayteam,awayteam_captain,awayteam_vcaptain,awayteam_wk,hometeam,hometeam_captain,hometeam_vcaptain,hometeam_wk,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,cancelled,cancelledplay,mom,mom2,umpire1,umpire2,maxovers,cricclubs_game_id,report,isactive) VALUES 
('$li','$se','$we','$at','$at_c','$at_vc','$at_wk','$ht','$ht_c','$ht_vc','$ht_wk','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mm2','$u1','$u2','$mo','$cci','$mr',0)");
	$db->QueryRow("SELECT LAST_INSERT_ID() AS GAME_ID");
	$game_id = $db->data['GAME_ID'];
	// Update the results table for the home team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$hometeam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homepla = $db->data['played'];
		$homewon = $db->data['won'];
		$homelos = $db->data['lost'];
	
	$db->update("UPDATE results SET played = $homepla + 1 WHERE team_id = $hometeam");
	if($hometeam == $result_won_id) {
	  $db->update("UPDATE results SET won = $homewon + 1 WHERE team_id = $hometeam");
	} else {
	  $db->update("UPDATE results SET lost = $homelos + 1 WHERE team_id = $hometeam");
	}
	
	} else {
	  if($hometeam == $result_won_id) {
	    $db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$hometeam','1','1','0','0','0')");
	  } else {
	    $db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$hometeam','1','0','1','0','0')");
	  }	
	}	
	
	// Update the results table for the away team
	
	if ($db->Exists("SELECT * FROM results WHERE team_id=$awayteam")) {
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaypla = $db->data['played'];
		$awaywon = $db->data['won'];
		$awaylos = $db->data['lost'];
	
	$db->update("UPDATE results SET played = $awaypla + 1 WHERE team_id = $awayteam");
	if($awayteam == $result_won_id) {
	  $db->update("UPDATE results SET won = $awaywon + 1 WHERE team_id = $awayteam");
	} else {
	  $db->update("UPDATE results SET lost = $awaylos + 1 WHERE team_id = $awayteam");
	}

	} else {
	  if($awayteam == $result_won_id) {
	    $db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$awayteam','1','1','0','0','0')");
	  } else {
	    $db->Insert("INSERT INTO results (team_id,played,won,lost,tied,nr) VALUES ('$awayteam','1','0','1','0','0')");
	  }	
	}
	
	header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
	ob_end_flush();
	
	}

}

function finished_update($db, $game_id)
{
	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";

	echo "<p class=\"14px\">Step 4 - You Are Finished!<br><img src=\"/images/100.gif\"></p>\n";
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Success</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	echo "<p>You have now updated a scorecard. Thanks!</p>\n";
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to scorecard admin page</a></p>\n";
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&game_id=$game_id\">edit the scorecard again</a></p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";	
	
	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
		

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

	$db->QueryItem("SELECT * FROM scorecard_game_details WHERE game_id=$id");

	$date = sqldate_to_string($db->data['game_date']);
	$t1 = htmlentities(stripslashes($teams[$db->data['awayteam']]));
	$t2 = htmlentities(stripslashes($teams[$db->data['hometeam']]));
	$ve = htmlentities(stripslashes($grounds[$db->data['ground_id']]));
	$re = htmlentities(stripslashes($db->data['result']));
	
	echo "<p>Are you sure you wish to delete the following scorecard:</p>\n";
	echo "<p><b>$date</b></p>\n";
	echo "<p><b>$t1</b> v <b>$t2</b> at <b>$ve</b> which <b>$re</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=dodel&game_id=$id\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=dontdel&game_id=$id\">NO</a></p>\n";
}

function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	if (!$doit) echo "<p>You have chosen NOT to delete that scorecard.</p>\n";
	else {
		$db->Delete("DELETE FROM scorecard_extras_details WHERE game_id=$id");
		$db->Delete("DELETE FROM scorecard_total_details WHERE game_id=$id");
		$db->Delete("DELETE FROM scorecard_fow_details WHERE game_id=$id");
		$db->Delete("DELETE FROM scorecard_batting_details WHERE game_id=$id");
		$db->Delete("DELETE FROM scorecard_bowling_details WHERE game_id=$id");
		$db->Delete("DELETE FROM scorecard_game_details WHERE game_id=$id");
		echo "<p>You have now deleted that scorecard.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the scorecard list</a></p>\n";
}

function edit_scorecard_step1($db, $game_id)
{

	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

 	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";
	
	echo "<p class=\"14px\">Step 1 - Enter Game Details<br><img src=\"/images/0.gif\"></p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter the Game Details</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
//	echo "    <td><a href=\"http://www.mozilla.org/products/firefox/\" target=\"_new\"><img src=\"/images/firefox.jpg\" border=\"0\" align=\"right\"></a>\n";

	echo "<form name=\"frm\" action=\"main.php?SID=$SID&action=scorecardadmin&do=update\" method=\"post\" enctype=\"multipart/form-data\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "<input type=\"hidden\" name=\"game_id\" value=\"$game_id\">\n";

	$db->QueryRow("
    SELECT
      s.*,
      o.SeasonName,
      l.LeagueName,
      a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
      h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
      u.TeamID AS 'umpireid', u.TeamName AS UmpireName, u.TeamAbbrev AS 'umpireabbrev',
      t.TeamID AS WonTossID, t.TeamName AS WonTossName, t.TeamAbbrev AS WonTossAbbrev,
      b.TeamID AS BatFirstID, b.TeamName AS BatFirstName, b.TeamAbbrev AS BatFirstAbbrev,
      n.TeamID AS BatSecondID, n.TeamName AS BatSecondName, n.TeamAbbrev AS BatSecondAbbrev,
      p.PlayerID AS MomID, p.PlayerLName AS MomLName, p.PlayerFName AS MomFName,
      p2.PlayerID AS MomID2, p2.PlayerLName AS MomLName2, p2.PlayerFName AS MomFName2,
      u1.PlayerID AS Ump1ID, u1.PlayerLName AS Ump1LName, u1.PlayerFName AS Ump1FName,
      u2.PlayerID AS Ump2ID, u2.PlayerLName AS Ump2LName, u2.PlayerFName AS Ump2FName,
      g.GroundID, g.GroundName
    FROM
      scorecard_game_details s
    LEFT JOIN
      grounds g ON s.ground_id = g.GroundID
    INNER JOIN
      teams a ON s.awayteam = a.TeamID
    INNER JOIN
      teams h ON s.hometeam = h.TeamID
    LEFT JOIN
      teams u ON s.umpires = u.TeamID
    LEFT JOIN
      teams t ON s.toss_won_id = t.TeamID
    LEFT JOIN
      teams b ON s.batting_first_id = b.TeamID
    LEFT JOIN
      teams n ON s.batting_second_id = n.TeamID
    INNER JOIN
      seasons o ON s.season = o.SeasonID
    INNER JOIN
      leaguemanagement l ON s.league_id = l.LeagueID
    
    LEFT JOIN
      players p ON s.mom = p.PlayerID
    LEFT JOIN
      players p2 ON s.mom2 = p2.PlayerID
    LEFT JOIN
      players u1 ON s.umpire1 = u1.PlayerID
    LEFT JOIN
      players u2 ON s.umpire2 = u2.PlayerID
	
    WHERE
      s.game_id=$game_id");

    $db->BagAndTag();

    $id = $db->data['game_id'];
    $sc = $db->data['season'];
    $lid = $db->data['league_id'];
    $wk = $db->data['week'];
    $sn = $db->data['SeasonName'];
    $ln = $db->data['LeagueName'];
    $mo = $db->data['maxovers'];
    $ht = $db->data['homeabbrev'];
    $hi = $db->data['homeid'];
    $hc = $db->data['HOMETEAM_CAPTAIN'];
    $hvc = $db->data['HOMETEAM_VCAPTAIN'];
    $hwk = $db->data['HOMETEAM_WK'];
    $at = $db->data['awayabbrev'];
    $ai = $db->data['awayid'];
    $ac = $db->data['AWAYTEAM_CAPTAIN'];
    $avc = $db->data['AWAYTEAM_VCAPTAIN'];
    $awk = $db->data['AWAYTEAM_WK'];
    $ut = $db->data['umpires'];
    $un = $db->data['UmpireName'];
    $gr = $db->data['GroundName'];
    $gi = $db->data['GroundID'];
    $re = $db->data['result'];
    $po = $db->data['points'];
	$ttid = $db->data['WonTossID'];   // 8-June-2015 11:04pm
    $tt = $db->data['WonTossName'];
	$rw = $db->data['result_won_id'];
    $mm  = $db->data['mom'];
    $u1  = $db->data['umpire1'];
    $u2  = $db->data['umpire2'];
    $mmi = $db->data['MomID'];
    $mmf = $db->data['MomFName'];
    $mml = $db->data['MomLName'];
    
    // Adding New Mom2 - 05/27/2010
    
    $mm2  = $db->data['mom2'];
    $mmi2 = $db->data['MomID2'];
    $mmf2 = $db->data['MomFName2'];
    $mml2 = $db->data['MomLName2'];
    
    $u1f = $db->data['Ump1FName'];
    $u1l = $db->data['Ump1LName'];
    $u2f = $db->data['Ump2FName'];
    $u2l = $db->data['Ump2LName'];
    $fo = $db->data['forfeit'];
    $ca = $db->data['cancelled'];
    $cp = $db->data['cancelledplay'];
    $ti = $db->data['tied'];

    $da = $db->data['game_date'];
	$cci = $db->data['cricclubs_game_id'];

    $bat1st = $db->data['BatFirstAbbrev'];
    $bat1stid = $db->data['BatFirstID'];

    $bat2nd = $db->data['BatSecondAbbrev'];
    $bat2ndid = $db->data['BatSecondID'];
	$mr = $db->data['report'];
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"league_id\" required msg=\"Please select a league from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select a league</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if ($lid == $db->data['LeagueID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
				
			echo "<option $selected value=\"" . $db->data['LeagueID'] . "\"> " . $db->data['LeagueName'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">League <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"season\" required msg=\"Please select a season from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM seasons")) {
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if ($sc == $db->data['SeasonID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['SeasonID'] . "\">Season " . $db->data['SeasonName'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Season <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input name=\"week\" size=\"10\" maxlength=\"10\" required filter=\"[0-9]\" msg=\"Please enter the week number using only numbers\" value =\"$wk\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Week # <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" size=\"20\" maxlength=\"255\" name=\"game_date\" value=\"$da\">\n";
	echo "<script language=\"javascript\" src=\"/includes/javascript/simplecalendar.js\" type=\"text/javascript\"></script>";
	echo "<link rel=\"stylesheet\" href=\"/includes/css/calendar.css\" type=\"text/css\">";
	echo "  <a href=\"javascript: void(0);\" onmouseover=\"if (timeoutId) clearTimeout(timeoutId);window.status='Show Calendar';return true;\" onmouseout=\"if (timeoutDelay) calendarTimeout();window.status='';\" onclick=\"g_Calendar.show(event,'frm.game_date',false); return false;\"><img src=\"http://www.coloradocricket.org/images/calendar/calendar.gif\" name=\"imgCalendar\" border=\"0\" alt=\"\" style=\"vertical-align: middle\"></a>\n"; 
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Game Date (yyyy-mm-dd) <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam\" required msg=\"Please select the visiting team from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($ai == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Visiting Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	// Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam_captain\" >\n";
	echo "	<option value=\"\">Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($ac == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Captain</td>\n";
	echo " </tr>\n";
	
	// Vice Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam_vcaptain\"  >\n";
	echo "	<option value=\"\">Vice Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($avc == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Vice Captain</td>\n";
	echo " </tr>\n";
	
	// W Keeper 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam_wk\"  >\n";
	echo "	<option value=\"\">Wicket Keeper</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($awk == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Wicket Keeper</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam\" required msg=\"Please select the home team from the drop down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($hi == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Home Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	// Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam_captain\" >\n";
	echo "	<option value=\"\">Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($hc == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Captain</td>\n";
	echo " </tr>\n";
	
	// Vice Captain 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam_vcaptain\"  >\n";
	echo "	<option value=\"\">Vice Captain</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($hvc == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Vice Captain</td>\n";
	echo " </tr>\n";
	
	// W Keeper 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam_wk\" >\n";
	echo "	<option value=\"\">Wicket Keeper</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($hwk == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Wicket Keeper</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpires\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($ut == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Umpiring Team</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"toss_won_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($ttid == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Who Won Toss?</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"batting_first_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($bat1stid == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Team Batting First <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"batting_second_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($bat2ndid == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Team Batting Second <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"result_won_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		$db->Query("SELECT * FROM teams where teamactive = 1 ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($rw == $db->data['TeamID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Victorious Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"> (only if a result occurred)</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"ground_id\">\n";
	echo "	<option value=\"\">Select Venue</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM grounds")) {
		$db->Query("SELECT * FROM grounds ORDER BY GroundID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($gi == $db->data['GroundID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['GroundID'] . "\">" . $db->data['GroundName'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Venue <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	if($ti == 0 && $fo == 0 && $ca == 0 && $cp == 0) {
		$checked = "checked";
	} else {
		$checked = "";
	}
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"radio\" name=\"result_type\" $checked value=\"normal\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if match ended normally</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	if($ti == 1) {
		$checked = "checked";
	} else {
		$checked = "";
	}
	echo "  <input type=\"radio\" name=\"result_type\" $checked value=\"tied\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if match tied <img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	if($fo == 1) {
		$checked = "checked";
	} else {
		$checked = "";
	}
	echo "  <input type=\"radio\" name=\"result_type\" $checked value=\"forfeit\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if won by forfeit <img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	if($ca == 1) {
		$checked = "checked";
	} else {
		$checked = "";
	}
	echo "  <input type=\"radio\" name=\"result_type\" $checked value=\"cancelled\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with no play <img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	if($cp == 1) {
		$checked = "checked";
	} else {
		$checked = "";
	}
	echo "  <input type=\"radio\" name=\"result_type\" $checked value=\"cancelledplay\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with some play</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"result\" size=\"20\" maxlength=\"255\" value=\"$re\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Result <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	//echo " <tr>\n";
	//echo "  <td width=\"50%\" align=\"right\">";
	//echo "  <input type=\"text\" name=\"mom\" size=\"20\" maxlength=\"255\">\n";
	//echo "  </td>\n";
	//echo "  <td width=\"50%\" align=\"left\">Enter Man of the Match</td>\n";
	//echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"mom\" id=\"combobox1\" >\n";
	echo "	<option value=\"\">Man of the match</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($mm == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Man of Match</td>\n";
	echo " </tr>\n";
	
	// Man of Match2 11-Apr-2010 Sachin Gupta
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"mom2\">\n";
	echo "	<option value=\"\">Man of the match 2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
	// 11-Apr-2010
	//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($mm2 == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Man of Match 2</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpire1\" id=\"combobox1\" >\n";
	echo "	<option value=\"\">Umpire 1</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($u1 == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Umpire 1</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpire2\" id=\"combobox1\" >\n";
	echo "	<option value=\"\">Umpire 2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
// 11-Apr-2010
//		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) ORDER BY PlayerFName,PlayerLName");
		$db->Query("SELECT * FROM players as p,teams as t where (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) and p.isactive = 0 ORDER BY PlayerFName,PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			if($u2 == $db->data['PlayerID']) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['TeamAbbrev'] . ")</option>\n";
		}
	}
	echo "</select>\n";	
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Select Umpire 2</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"maxovers\" size=\"20\" maxlength=\"255\" value=\"$mo\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Max Overs <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"> (example: 40)</td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"cricclubs_game_id\" size=\"20\" maxlength=\"255\" value=\"$cci\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">CricClubs Game ID</td>\n";
	echo " </tr>\n";	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"report\" size=\"20\" maxlength=\"255\" value=\"$mr\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Match Report Link</td>\n";
	echo " </tr>\n";	

	//disabling captcha as it is part of admin panel now
/* // ben added captcha 
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	include("simple-php-captcha.php");
	$_SESSION['captcha'] = simple_php_captcha();	
	echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA code">';
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">";
	echo "<p>please enter above code correctly to submit this page<br><input type=\"text\" name=\"code\" size=24/></p>";
	echo "  </td>\n";
	echo " </tr>\n";
 */	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input name=\"submit\" type=\"submit\" value=\"Next\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Save and Next\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">&nbsp;</td>\n";
	echo " </tr>\n";	


	echo "</table>\n";

	echo "</form>\n";
	echo "<script src=\"../includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";

	echo "<p><img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"> fields are required. <br><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"> fields are required for a forfeit game. <br><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"> fields are required for a cancelled game.<br><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"> fields are required for a tied game.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	ob_end_flush();

}

function update_scorecard_step1($db,$submit,$game_id,$league_id,$season,$week,$awayteam, $awayteam_captain, $awayteam_vcaptain, $awayteam_wk, $hometeam, $hometeam_captain, $hometeam_vcaptain, $hometeam_wk ,$umpires,$toss_won_id,$result_won_id,$batting_first_id,$batting_second_id,$ground_id,
$ground_name,$game_date,$result,$result_type,$mom, $mom2,$umpire1,$umpire2,$maxovers,$cricclubs_game_id,$report)
{

	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	//disabling captcha as it is part of admin panel now
/* 	if(strtolower($_POST['code']) != strtolower($_SESSION['captcha']['code'])) {
    	echo "<p>Sorry, the code you entered was invalid.  Please try again.</p>";
    	return;
  	}
 */

	if($submit == "Next") {
		header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
		ob_end_flush();
	}
    //////////////////////////////////////////////////////////////////////////////////////////			
	// This is a forfeited game
	//////////////////////////////////////////////////////////////////////////////////////////		

	else if($result_type == "forfeit") {

	// make sure forfeit info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $result_won_id == "" || $game_date == "" || $week == "") {
		echo "<p class=\"12pt\"><b>Forfeit Game Missing information</b></p>\n";
		echo "<p>You must complete all the required (<img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\">) forfeit game fields. Please go back and try again.</p>\n";
		return;
	}	
	
	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 0;
	$fo = 1;
	$ca = 0;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Update the scorecard_game_details table
	
	$db->Update("UPDATE scorecard_game_details set league_id = '$li', season = '$se', week = '$we',awayteam = '$at', awayteam_captain = '$at_c', awayteam_vcaptain = '$at_vc',awayteam_wk = '$at_wk', hometeam = '$ht', hometeam_captain = '$ht_c', hometeam_vcaptain = '$ht_vc', hometeam_wk = '$ht_wk', umpires = '$um', toss_won_id = '$tw', result_won_id = '$rw', batting_first_id = '$bf', batting_second_id = '$bs', ground_id = '$gi',game_date = '$gd', result = '$re', tied = '$ti', forfeit = '$fo', cancelled = '$ca', cancelledplay = '$cg', mom = '$mm', mom2 = '$mm2', umpire1 = '$u1', umpire2 = '$u2', maxovers = '$mo', cricclubs_game_id = '$cci', report = '$mr' WHERE game_id = $game_id");
	
	header("Location: main.php?SID=$SID&action=$action&do=update6&game_id=$game_id");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a cancelled game	
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else if($result_type == "cancelled") {
	
	// make sure cancelled info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $game_date == "" || $week == "") {
		echo "<p class=\"12pt\"><b>Cancelled Game Missing information</b></p>\n";
		echo "<p>You must complete all the required (<img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\">) cancelled game fields. Please go back and try again.</p>\n";
		return;
	}	
	
	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 0;
	$fo = 0;
	$ca = 1;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Update the game header table

	$db->Update("UPDATE scorecard_game_details set league_id = '$li', season = '$se', week = '$we',awayteam = '$at', awayteam_captain = '$at_c', awayteam_vcaptain = '$at_vc',awayteam_wk = '$at_wk', hometeam = '$ht', hometeam_captain = '$ht_c', hometeam_vcaptain = '$ht_vc', hometeam_wk = '$ht_wk', umpires = '$um', toss_won_id = '$tw', result_won_id = '$rw', batting_first_id = '$bf', batting_second_id = '$bs', ground_id = '$gi',game_date = '$gd', result = '$re', tied = '$ti', forfeit = '$fo', cancelled = '$ca', cancelledplay = '$cg', mom = '$mm', mom2 = '$mm2', umpire1 = '$u1', umpire2 = '$u2', maxovers = '$mo', cricclubs_game_id = '$cci', report = '$mr' WHERE game_id = $game_id");
	
	header("Location: main.php?SID=$SID&action=$action&do=update6&game_id=$game_id");
	ob_end_flush();	

	//////////////////////////////////////////////////////////////////////////////////////////
	// This is a tied game
	//////////////////////////////////////////////////////////////////////////////////////////

	} else if($result_type == "tied") {	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" || $game_date == "" || $tied == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables
	
	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 1;
	$fo = 0;
	$ca = 0;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Update the game header table
	
	$db->Update("UPDATE scorecard_game_details set league_id = '$li', season = '$se', week = '$we',awayteam = '$at', awayteam_captain = '$at_c', awayteam_vcaptain = '$at_vc',awayteam_wk = '$at_wk', hometeam = '$ht', hometeam_captain = '$ht_c', hometeam_vcaptain = '$ht_vc', hometeam_wk = '$ht_wk', umpires = '$um', toss_won_id = '$tw', result_won_id = '$rw', batting_first_id = '$bf', batting_second_id = '$bs', ground_id = '$gi',game_date = '$gd', result = '$re', tied = '$ti', forfeit = '$fo', cancelled = '$ca', cancelledplay = '$cg', mom = '$mm', mom2 = '$mm2', umpire1 = '$u1', umpire2 = '$u2', maxovers = '$mo', cricclubs_game_id = '$cci', report = '$mr' WHERE game_id = $game_id");
	
	header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////
	// This is a cancelled game with some play
	//////////////////////////////////////////////////////////////////////////////////////////

	} else if($result_type == "cancelledplay") {	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" || $game_date == "" ||  $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables

	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 0;
	$fo = 0;
	$ca = 0;
	$cg = 1;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

	// Update the game header table
	
	$db->Update("UPDATE scorecard_game_details set league_id = '$li', season = '$se', week = '$we',awayteam = '$at', awayteam_captain = '$at_c', awayteam_vcaptain = '$at_vc',awayteam_wk = '$at_wk', hometeam = '$ht', hometeam_captain = '$ht_c', hometeam_vcaptain = '$ht_vc', hometeam_wk = '$ht_wk', umpires = '$um', toss_won_id = '$tw', result_won_id = '$rw', batting_first_id = '$bf', batting_second_id = '$bs', ground_id = '$gi',game_date = '$gd', result = '$re', tied = '$ti', forfeit = '$fo', cancelled = '$ca', cancelledplay = '$cg', mom = '$mm', mom2 = '$mm2', umpire1 = '$u1', umpire2 = '$u2', maxovers = '$mo', cricclubs_game_id = '$cci', report = '$mr' WHERE game_id = $game_id");

	header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a normal game
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else {
	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" && $ground_name == "" || $game_date == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables

	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$at_c = addslashes(trim($awayteam_captain));
	$at_vc = addslashes(trim($awayteam_vcaptain));
	$at_wk = addslashes(trim($awayteam_wk));
	$ht = addslashes(trim($hometeam));
	$ht_c = addslashes(trim($hometeam_captain));
	$ht_vc = addslashes(trim($hometeam_vcaptain));
	$ht_wk = addslashes(trim($hometeam_wk));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gn = addslashes(trim($ground_name));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = 0;
	$fo = 0;
	$ca = 0;
	$cg = 0;
	$mm = addslashes(trim($mom));
	$mm2 = addslashes(trim($mom2));
	$u1 = addslashes(trim($umpire1));
	$u2 = addslashes(trim($umpire2));
	$mo = addslashes(trim($maxovers));
	$cci = addslashes(trim($cricclubs_game_id));
	$mr = addslashes(trim($report));
	
	// all okay

 	// Update the game header table
	
	$db->Update("UPDATE scorecard_game_details set league_id = '$li', season = '$se', week = '$we',awayteam = '$at', awayteam_captain = '$at_c', awayteam_vcaptain = '$at_vc',awayteam_wk = '$at_wk', hometeam = '$ht', hometeam_captain = '$ht_c', hometeam_vcaptain = '$ht_vc', hometeam_wk = '$ht_wk', umpires = '$um', toss_won_id = '$tw', result_won_id = '$rw', batting_first_id = '$bf', batting_second_id = '$bs', ground_id = '$gi',game_date = '$gd', result = '$re', tied = '$ti', forfeit = '$fo', cancelled = '$ca', cancelledplay = '$cg', mom = '$mm', mom2 = '$mm2', umpire1 = '$u1', umpire2 = '$u2', maxovers = '$mo', cricclubs_game_id = '$cci', report = '$mr' WHERE game_id = $game_id");

	header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
	ob_end_flush();
	
	}

}

function edit_scorecard_step2($db,$game_id)
{
	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	$db->QueryRow("
	SELECT
	  s.*,
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	  u.TeamID AS 'umpireid', u.TeamName AS UmpireName, u.TeamAbbrev AS 'umpireabbrev',
	  t.TeamID AS WonTossID, t.TeamName AS WonTossName, t.TeamAbbrev AS WonTossAbbrev,
	  b.TeamID AS BatFirstID, b.TeamName AS BatFirstName, b.TeamAbbrev AS BatFirstAbbrev,
	  n.TeamID AS BatSecondID, n.TeamName AS BatSecondName, n.TeamAbbrev AS BatSecondAbbrev,
	  g.GroundID, g.GroundName
	FROM
	  scorecard_game_details s
	INNER JOIN
	  grounds g ON s.ground_id = g.GroundID
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	LEFT JOIN
	  teams u ON s.umpires = u.TeamID
	LEFT JOIN
	  teams t ON s.toss_won_id = t.TeamID
	INNER JOIN
	  teams b ON s.batting_first_id = b.TeamID
	INNER JOIN
	  teams n ON s.batting_second_id = n.TeamID	  
	WHERE 
	  s.game_id = $game_id
	");

	$db->BagAndTag();

	$gid = $db->data['game_id'];
	$gsc = $db->data['season'];
	$gli = $db->data['league_id'];
	
	$ght = $db->data['homeabbrev'];
	$ghi = $db->data['homeid'];
	$gat = $db->data['awayabbrev'];
	$gai = $db->data['awayid'];
	
	$gut = $db->data['umpireabbrev'];
	$ggr = $db->data['GroundName'];
	$ggi = $db->data['GroundID'];
	$gre = $db->data['result'];
	$gtt = $db->data['WonTossAbbrev'];

	$gda = sqldate_to_string($db->data['game_date']);

	$bat1st   = $db->data['BatFirstAbbrev'];
	$bat1stid = $db->data['BatFirstID'];
	$bat2nd   = $db->data['BatSecondAbbrev'];
	$bat2ndid = $db->data['BatSecondID'];
	

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";

	echo "<p class=\"14px\">Step 2 - Enter 1st Innings Details<br><img src=\"/images/33.gif\"></p>\n";
	
	echo "<p>You are working with <b>Game #$gid</b>, <b>$bat1st</b> ($bat1stid) vs <b>$bat2nd</b> ($bat2ndid) on <b>$gda</b></p>\n";
//	echo "<p align=\"left\"><b><font color=\"red\">IMPORTANT!</font></b> If you are using <a href=\"http://www.getfirefox.com\" target=\"_new\">Firefox</a> then you may <a href=\"addplayer.php\" target=\"_new\">add new players</a> at any time. Please REFRESH the page once adding, Firefox should remember your selections. If you have another browser, check the drop-down menu's first to make sure that all players exist. Other browsers may not remember form selection data.</p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Team Batting 1st Details - $bat1st</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<form action=\"main.php?SID=$SID&action=$action&do=update3\" method=\"post\" enctype=\"multipart/form-data\" name=\"comboForm\">\n";
	
	echo "<input type=\"hidden\" name=\"innings_id\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"game_id\" value=\"$gid\">\n";
	echo "<input type=\"hidden\" name=\"season\" value=\"$gsc\">\n";
	echo "<input type=\"hidden\" name=\"onebatpos\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"twobatpos\" value=\"2\">\n";
	echo "<input type=\"hidden\" name=\"threebatpos\" value=\"3\">\n";
	echo "<input type=\"hidden\" name=\"fourbatpos\" value=\"4\">\n";
	echo "<input type=\"hidden\" name=\"fivebatpos\" value=\"5\">\n";
	echo "<input type=\"hidden\" name=\"sixbatpos\" value=\"6\">\n";
	echo "<input type=\"hidden\" name=\"sevenbatpos\" value=\"7\">\n";
	echo "<input type=\"hidden\" name=\"eightbatpos\" value=\"8\">\n";
	echo "<input type=\"hidden\" name=\"ninebatpos\" value=\"9\">\n";
	echo "<input type=\"hidden\" name=\"tenbatpos\" value=\"10\">\n";
	echo "<input type=\"hidden\" name=\"elevenbatpos\" value=\"11\">\n";
	echo "<input type=\"hidden\" name=\"onebowpos\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"twobowpos\" value=\"2\">\n";
	echo "<input type=\"hidden\" name=\"threebowpos\" value=\"3\">\n";
	echo "<input type=\"hidden\" name=\"fourbowpos\" value=\"4\">\n";
	echo "<input type=\"hidden\" name=\"fivebowpos\" value=\"5\">\n";
	echo "<input type=\"hidden\" name=\"sixbowpos\" value=\"6\">\n";
	echo "<input type=\"hidden\" name=\"sevenbowpos\" value=\"7\">\n";
	echo "<input type=\"hidden\" name=\"eightbowpos\" value=\"8\">\n";
	echo "<input type=\"hidden\" name=\"ninebowpos\" value=\"9\">\n";
	echo "<input type=\"hidden\" name=\"tenbowpos\" value=\"10\">\n";
	echo "<input type=\"hidden\" name=\"elevenbowpos\" value=\"11\">\n";
	echo "<input type=\"hidden\" name=\"oneteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"twoteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"threeteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"fourteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"fiveteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"sixteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"seventeam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"eightteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"nineteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"tenteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"eleventeam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"oneopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"twoopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"threeopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"fouropponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"fiveopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"sixopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"sevenopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"eightopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"nineopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"tenopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"elevenopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowloneteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowltwoteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlthreeteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfourteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfiveteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlsixteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlseventeam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowleightteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlnineteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowltenteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowleleventeam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowloneopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowltwoopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlthreeopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfouropponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfiveopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlsixopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlsevenopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowleightopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlnineopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowltenopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlelevenopponent\" value=\"$bat1stid\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	$pl_data = array();
	$db->Query("
    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,s.howout_video,s.highlights_video,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev, b.PlayerID AS BowlerID, a.PlayerID AS AssistID,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial, 
	  a2.PlayerID AS AssistID2, a2.PlayerLName AS AssistLName2, a2.PlayerFName AS AssistFName2, LEFT(a2.PlayerFName,1) AS AssistFInitial2,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players a2 ON a2.PlayerID = s.assist2
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $gid AND s.innings_id = 1
    ORDER BY
      s.batting_position
    ");

    for ($x=0; $x<$db->rows; $x++) {
    $db->GetRow($x);

    $pid = $db->data['BatterID'];
    $bid = $db->data['BowlerID'];
    $aid = $db->data['AssistID'];
    $pln = $db->data['BatterLName'];
    $pfn = $db->data['BatterFName'];
    $pin = $db->data['BatterFInitial'];
    $bln = $db->data['BowlerLName'];
    $bfn = $db->data['BowlerFName'];
    $bin = $db->data['BowlerFInitial'];
    $aln = $db->data['AssistLName'];
    $afn = $db->data['AssistFName'];
    $ain = $db->data['AssistFInitial'];
    $a2id = $db->data['AssistID2'];
    $a2ln = $db->data['AssistLName2'];
    $a2fn = $db->data['AssistFName2'];
    $a2in = $db->data['AssistFInitial2'];
    $out = $db->data['HowOutAbbrev'];
    $oid = $db->data['HowOutID'];
    $run = $db->data['runs'];
    $bal = $db->data['balls'];
    $fou = $db->data['fours'];
    $six = $db->data['sixes'];
    $hwv = $db->data['howout_video'];
	$hlv = $db->data['highlights_video'];
	$pl_rec = array ($pid, $bid, $aid, $oid, $run, $bal, $fou, $six, $hwv, $hlv, $a2id);
	$pl_data[$x] = $pl_rec;
	}
	echo " <tr>\n";
	
	echo "  <td width=\"70%\" colspan=\"5\">&nbsp;</td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Runs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Balls</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>4s</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>6s</b></td>\n";
	
	echo " </tr>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                1st Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneplayer_id\" id=\"combobox1\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">1st Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"onehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneassist\" id=\"combobox2\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"onebowler\" id=\"combobox3\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][4] : '';
	echo "  <input type=\"text\" name=\"oneruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][5] : '';
	echo "  <input type=\"text\" name=\"oneballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][6] : '';
	echo "  <input type=\"text\" name=\"onefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][7] : '';
	echo "  <input type=\"text\" name=\"onesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 0 ? $pl_data[0][8] : '';
	echo "  <input type=\"text\" name=\"onehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 0 ? $pl_data[0][9] : '';
	echo "  <input type=\"text\" name=\"onehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoplayer_id\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">2nd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"twohow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoassist\" id=\"combobox5\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[1][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twobowler\" id=\"combobox6\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][4] : '';
	echo "  <input type=\"text\" name=\"tworuns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][5] : '';
	echo "  <input type=\"text\" name=\"twoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][6] : '';
	echo "  <input type=\"text\" name=\"twofours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][7] : '';
	echo "  <input type=\"text\" name=\"twosixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 1 ? $pl_data[1][8] : '';
	echo "  <input type=\"text\" name=\"twohwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 1 ? $pl_data[1][9] : '';
	echo "  <input type=\"text\" name=\"twohlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeplayer_id\" id=\"combobox7\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">3rd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"threehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeassist\" id=\"combobox8\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[2][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threebowler\" id=\"combobox9\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][4] : '';
	echo "  <input type=\"text\" name=\"threeruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][5] : '';
	echo "  <input type=\"text\" name=\"threeballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][6] : '';
	echo "  <input type=\"text\" name=\"threefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][7] : '';
	echo "  <input type=\"text\" name=\"threesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 2 ? $pl_data[2][8] : '';
	echo "  <input type=\"text\" name=\"threehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 2 ? $pl_data[2][9] : '';
	echo "  <input type=\"text\" name=\"threehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourplayer_id\" id=\"combobox10\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">4th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"fourhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourassist\" id=\"combobox11\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[3][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourbowler\" id=\"combobox12\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][4] : '';
	echo "  <input type=\"text\" name=\"fourruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][5] : '';
	echo "  <input type=\"text\" name=\"fourballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][6] : '';
	echo "  <input type=\"text\" name=\"fourfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][7] : '';
	echo "  <input type=\"text\" name=\"foursixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 3 ? $pl_data[3][8] : '';
	echo "  <input type=\"text\" name=\"fourhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 3 ? $pl_data[3][9] : '';
	echo "  <input type=\"text\" name=\"fourhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveplayer_id\" id=\"combobox13\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">5th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"fivehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveassist\" id=\"combobox14\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[4][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fivebowler\" id=\"combobox15\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][4] : '';
	echo "  <input type=\"text\" name=\"fiveruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][5] : '';
	echo "  <input type=\"text\" name=\"fiveballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][6] : '';
	echo "  <input type=\"text\" name=\"fivefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][7] : '';
	echo "  <input type=\"text\" name=\"fivesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 4 ? $pl_data[4][8] : '';
	echo "  <input type=\"text\" name=\"fivehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 4 ? $pl_data[4][9] : '';
	echo "  <input type=\"text\" name=\"fivehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixplayer_id\" id=\"combobox16\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">6th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"sixhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixassist\" id=\"combobox17\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[5][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixbowler\" id=\"combobox18\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][4] : '';
	echo "  <input type=\"text\" name=\"sixruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][5] : '';
	echo "  <input type=\"text\" name=\"sixballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][6] : '';
	echo "  <input type=\"text\" name=\"sixfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][7] : '';
	echo "  <input type=\"text\" name=\"sixsixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 5 ? $pl_data[5][8] : '';
	echo "  <input type=\"text\" name=\"sixhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 5 ? $pl_data[5][9] : '';
	echo "  <input type=\"text\" name=\"sixhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenplayer_id\" id=\"combobox19\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">7th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"sevenhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenassist\" id=\"combobox20\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[6][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenbowler\" id=\"combobox21\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][4] : '';
	echo "  <input type=\"text\" name=\"sevenruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][5] : '';
	echo "  <input type=\"text\" name=\"sevenballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][6] : '';
	echo "  <input type=\"text\" name=\"sevenfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][7] : '';
	echo "  <input type=\"text\" name=\"sevensixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 6 ? $pl_data[6][8] : '';
	echo "  <input type=\"text\" name=\"sevenhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 6 ? $pl_data[6][9] : '';
	echo "  <input type=\"text\" name=\"sevenhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightplayer_id\" id=\"combobox22\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">8th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"eighthow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightassist\" id=\"combobox23\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[7][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightbowler\" id=\"combobox24\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][4] : '';
	echo "  <input type=\"text\" name=\"eightruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][5] : '';
	echo "  <input type=\"text\" name=\"eightballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][6] : '';
	echo "  <input type=\"text\" name=\"eightfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][7] : '';
	echo "  <input type=\"text\" name=\"eightsixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 7 ? $pl_data[7][8] : '';
	echo "  <input type=\"text\" name=\"eighthwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 7 ? $pl_data[7][9] : '';
	echo "  <input type=\"text\" name=\"eighthlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineplayer_id\" id=\"combobox25\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">9th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"ninehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineassist\" id=\"combobox26\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[8][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"ninebowler\" id=\"combobox27\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][4] : '';
	echo "  <input type=\"text\" name=\"nineruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][5] : '';
	echo "  <input type=\"text\" name=\"nineballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][6] : '';
	echo "  <input type=\"text\" name=\"ninefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][7] : '';
	echo "  <input type=\"text\" name=\"ninesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 8 ? $pl_data[8][8] : '';
	echo "  <input type=\"text\" name=\"ninehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 8 ? $pl_data[8][9] : '';
	echo "  <input type=\"text\" name=\"ninehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Batsman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenplayer_id\" id=\"combobox28\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">10th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"tenhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenassist\" id=\"combobox29\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[9][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenbowler\" id=\"combobox30\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][4] : '';
	echo "  <input type=\"text\" name=\"tenruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][5] : '';
	echo "  <input type=\"text\" name=\"tenballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][6] : '';
	echo "  <input type=\"text\" name=\"tenfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][7] : '';
	echo "  <input type=\"text\" name=\"tensixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 9 ? $pl_data[9][8] : '';
	echo "  <input type=\"text\" name=\"tenhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 9 ? $pl_data[9][9] : '';
	echo "  <input type=\"text\" name=\"tenhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Batsman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenplayer_id\" id=\"combobox31\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">11th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"elevenhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenassist\" id=\"combobox32\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[10][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenbowler\" id=\"combobox33\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][4] : '';
	echo "  <input type=\"text\" name=\"elevenruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][5] : '';
	echo "  <input type=\"text\" name=\"elevenballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][6] : '';
	echo "  <input type=\"text\" name=\"elevenfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][7] : '';
	echo "  <input type=\"text\" name=\"elevensixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 10 ? $pl_data[10][8] : '';
	echo "  <input type=\"text\" name=\"elevenhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 10 ? $pl_data[10][9] : '';
	echo "  <input type=\"text\" name=\"elevenhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

	echo "</table>\n";

	echo "<p>* Batter, how out and runs are required.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<br>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                           Extras & Toals Details                                       //
////////////////////////////////////////////////////////////////////////////////////////////


	$by = $lb = $wd = $nb = $et = $wi = $to = $ov = "";
	if($db->Exists("
		SELECT
		  legbyes, byes, wides, noballs, total
		FROM
		  scorecard_extras_details
		WHERE
		  game_id = $gid AND innings_id = 1
		")) {
		$db->QueryItem("
			SELECT
			  legbyes, byes, wides, noballs, total
			FROM
			  scorecard_extras_details
			WHERE
			  game_id = $gid AND innings_id = 1
			");

		$by = $db->data['byes'];
		$lb = $db->data['legbyes'];
		$wd = $db->data['wides'];
		$nb = $db->data['noballs'];
		$et = $db->data['total'];
	}
	if($db->Exists("
		SELECT
		  wickets, total, overs, dl_total
		FROM
		  scorecard_total_details
		WHERE
		  game_id = $gid AND innings_id = 1
		")) {
		$db->QueryItem("
			SELECT
			  wickets, total, overs, dl_total
			FROM
			  scorecard_total_details
			WHERE
			  game_id = $gid AND innings_id = 1
			");

		$wi = $db->data['wickets'];
		$to = $db->data['total'];
		$ov = $db->data['overs'];
		$dl_to = $db->data['dl_total'];
	}

	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
   	echo "<tr>\n";
   	echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Total & Extras Details - $bat1st</td>\n";
   	echo "</tr>\n";
   	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	echo " <tr>\n";
	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wickets</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Overs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Total</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>DL Total</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Legbyes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Byes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wides</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Noballs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Total</b></td>\n";
	
	echo " </tr>\n";
	echo " <tr>\n";
	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totwickets\" size=\"5\" maxlength=\"7\" value=\"$wi\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totovers\" size=\"5\" maxlength=\"7\" value=\"$ov\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"tottotal\" size=\"5\" maxlength=\"7\" value=\"$to\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totdltotal\" size=\"5\" maxlength=\"7\" value=\"$dl_to\"></td>\n";	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extlegbyes\" size=\"5\" maxlength=\"7\" value=\"$lb\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extbyes\" size=\"5\" maxlength=\"7\" value=\"$by\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extwides\" size=\"5\" maxlength=\"7\" value=\"$wd\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extnoballs\" size=\"5\" maxlength=\"7\" value=\"$nb\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"exttotal\" size=\"5\" maxlength=\"7\" value=\"$et\"></td>\n";	
	
	echo " </tr>\n";
	echo "</table>\n";


	
	echo "<p>* Wickets, Total Runs and Overs required.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<br>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                    Fow Details                                         //
////////////////////////////////////////////////////////////////////////////////////////////

	$f1 = $f2 = $f3 = $f4 = $f5 = $f6 = $f7 = $f8 = $f9 = $f10 = "";
	if($db->Exists("
		SELECT
		  legbyes, byes, wides, noballs, total
		FROM
		  scorecard_extras_details
		WHERE
		  game_id = $gid AND innings_id = 1
		")) {
		$db->QueryItem("
			SELECT
			  fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10
			FROM
			  scorecard_fow_details
			WHERE
			  game_id = $gid AND innings_id = 1
			");

		$f1 = $db->data['fow1'];
		$f2 = $db->data['fow2'];
		$f3 = $db->data['fow3'];
		$f4 = $db->data['fow4'];
		$f5 = $db->data['fow5'];
		$f6 = $db->data['fow6'];
		$f7 = $db->data['fow7'];
		$f8 = $db->data['fow8'];
		$f9 = $db->data['fow9'];
		$f10 = $db->data['fow10'];
	}	
   	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
   	echo "<tr>\n";
   	echo "  <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter FoW Details - $bat1st</td>\n";
   	echo "</tr>\n";
   	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	echo " <tr>\n";
	
	echo "  <td width=\"10%\" align=\"right\"><b>FoW1</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW2</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW3</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW4</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW5</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW6</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW7</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW8</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW9</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW10</b></td>\n";

	
	echo " </tr>\n";
	echo " <tr>\n";
	
	$value = ($f1 != "777") ? $f1 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowone\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f2 != "777") ? $f2 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowtwo\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f3 != "777") ? $f3 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowthree\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f4 != "777") ? $f4 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfour\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f5 != "777") ? $f5 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfive\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f6 != "777") ? $f6 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowsix\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f7 != "777") ? $f7 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowseven\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f8 != "777") ? $f8 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"foweight\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f9 != "777") ? $f9 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fownine\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f10 != "777") ? $f10 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowten\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	
	echo " </tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<br>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Bowling Details - $bat2nd</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	$pl_data = array();
	$db->Query("

    SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,s.highlights_video,
      p.PlayerID AS BowlerID, p.PlayerLName AS BowlerLName, p.PlayerFName AS BowlerFName, LEFT(p.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_bowling_details s
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    WHERE
      s.game_id = $gid AND s.innings_id = 1
    ORDER BY
      s.bowling_position

    ");

    for ($x=0; $x<$db->rows; $x++) {
		$db->GetRow($x);

		$pid = $db->data['BowlerID'];
		$pln = $db->data['BowlerLName'];
		$pfn = $db->data['BowlerFName'];
		$pin = $db->data['BowlerFInitial'];
		$ov = $db->data['overs'];
		$ma = $db->data['maidens'];
		$ru = $db->data['runs'];
		$wi = $db->data['wickets'];
		$no = $db->data['noballs'];
		$wd = $db->data['wides'];
		$hlv = $db->data['highlights_video'];
		$pl_rec = array ($pid, $ov, $ma, $ru, $wi, $no, $wd, $hlv);
		$pl_data[$x] = $pl_rec;
	
	}
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                1st Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo " <tr>\n";
	echo "  <td width=\"52%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Overs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Maidens</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Runs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wickets</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Noballs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wides</b></td>\n";
	echo " </tr>\n";
	

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"onebowler_id\" id=\"combobox34\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">1st Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][1] : '';
	echo "  <input type=\"text\" name=\"oneovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][2] : '';
	echo "  <input type=\"text\" name=\"onemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][3] : '';
	echo "  <input type=\"text\" name=\"onebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][4] : '';
	echo "  <input type=\"text\" name=\"onewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][5] : '';
	echo "  <input type=\"text\" name=\"onenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][6] : '';
	echo "  <input type=\"text\" name=\"onewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 0 ? $pl_data[0][7] : '';
	echo "  <input type=\"text\" name=\"onehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"twobowler_id\" id=\"combobox35\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">2nd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][1] : '';
	
	echo "  <input type=\"text\" name=\"twoovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][2] : '';
	
	echo "  <input type=\"text\" name=\"twomaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][3] : '';

	echo "  <input type=\"text\" name=\"twobowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][4] : '';

	echo "  <input type=\"text\" name=\"twowickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][5] : '';

	echo "  <input type=\"text\" name=\"twonoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][6] : '';

	echo "  <input type=\"text\" name=\"twowides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 1 ? $pl_data[1][7] : '';
	echo "  <input type=\"text\" name=\"twohlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"threebowler_id\" id=\"combobox36\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">3rd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][1] : '';

	echo "  <input type=\"text\" name=\"threeovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][2] : '';

	echo "  <input type=\"text\" name=\"threemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][3] : '';

	echo "  <input type=\"text\" name=\"threebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][4] : '';

	echo "  <input type=\"text\" name=\"threewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][5] : '';

	echo "  <input type=\"text\" name=\"threenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][6] : '';

	echo "  <input type=\"text\" name=\"threewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 2 ? $pl_data[2][7] : '';
	echo "  <input type=\"text\" name=\"threehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fourbowler_id\" id=\"combobox37\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">4th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][1] : '';

	echo "  <input type=\"text\" name=\"fourovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][2] : '';

	echo "  <input type=\"text\" name=\"fourmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][3] : '';

	echo "  <input type=\"text\" name=\"fourbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][4] : '';

	echo "  <input type=\"text\" name=\"fourwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][5] : '';

	echo "  <input type=\"text\" name=\"fournoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][6] : '';

	echo "  <input type=\"text\" name=\"fourwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 3 ? $pl_data[3][7] : '';
	echo "  <input type=\"text\" name=\"fourhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fivebowler_id\" id=\"combobox38\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">5th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][1] : '';

	echo "  <input type=\"text\" name=\"fiveovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][2] : '';

	echo "  <input type=\"text\" name=\"fivemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][3] : '';

	echo "  <input type=\"text\" name=\"fivebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][4] : '';

	echo "  <input type=\"text\" name=\"fivewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][5] : '';

	echo "  <input type=\"text\" name=\"fivenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][6] : '';

	echo "  <input type=\"text\" name=\"fivewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 4 ? $pl_data[4][7] : '';
	echo "  <input type=\"text\" name=\"fivehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sixbowler_id\" id=\"combobox39\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">6th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][1] : '';

	echo "  <input type=\"text\" name=\"sixovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][2] : '';

	echo "  <input type=\"text\" name=\"sixmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][3] : '';

	echo "  <input type=\"text\" name=\"sixbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][4] : '';

	echo "  <input type=\"text\" name=\"sixwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][5] : '';

	echo "  <input type=\"text\" name=\"sixnoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][6] : '';

	echo "  <input type=\"text\" name=\"sixwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 5 ? $pl_data[5][7] : '';
	echo "  <input type=\"text\" name=\"sixhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sevenbowler_id\" id=\"combobox40\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">7th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][1] : '';

	echo "  <input type=\"text\" name=\"sevenovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][2] : '';

	echo "  <input type=\"text\" name=\"sevenmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][3] : '';

	echo "  <input type=\"text\" name=\"sevenbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][4] : '';

	echo "  <input type=\"text\" name=\"sevenwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][5] : '';

	echo "  <input type=\"text\" name=\"sevennoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][6] : '';

	echo "  <input type=\"text\" name=\"sevenwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 6 ? $pl_data[6][7] : '';
	echo "  <input type=\"text\" name=\"sevenhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"eightbowler_id\" id=\"combobox41\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">8th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][1] : '';

	echo "  <input type=\"text\" name=\"eightovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][2] : '';

	echo "  <input type=\"text\" name=\"eightmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][3] : '';

	echo "  <input type=\"text\" name=\"eightbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][4] : '';

	echo "  <input type=\"text\" name=\"eightwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][5] : '';

	echo "  <input type=\"text\" name=\"eightnoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][6] : '';

	echo "  <input type=\"text\" name=\"eightwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > [7] ? $pl_data[7][7] : '';
	echo "  <input type=\"text\" name=\"eighthlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"ninebowler_id\" id=\"combobox42\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">9th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][1] : '';

	echo "  <input type=\"text\" name=\"nineovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][2] : '';

	echo "  <input type=\"text\" name=\"ninemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][3] : '';

	echo "  <input type=\"text\" name=\"ninebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][4] : '';

	echo "  <input type=\"text\" name=\"ninewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][5] : '';

	echo "  <input type=\"text\" name=\"ninenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][6] : '';

	echo "  <input type=\"text\" name=\"ninewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 8 ? $pl_data[8][7] : '';
	echo "  <input type=\"text\" name=\"ninehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Bowler Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"tenbowler_id\" id=\"combobox43\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">10th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][1] : '';

	echo "  <input type=\"text\" name=\"tenovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][2] : '';

	echo "  <input type=\"text\" name=\"tenmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][3] : '';

	echo "  <input type=\"text\" name=\"tenbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][4] : '';

	echo "  <input type=\"text\" name=\"tenwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][5] : '';

	echo "  <input type=\"text\" name=\"tennoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][6] : '';

	echo "  <input type=\"text\" name=\"tenwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 9 ? $pl_data[9][7] : '';
	echo "  <input type=\"text\" name=\"tenhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"elevenbowler_id\" id=\"combobox44\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">11th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][1] : '';

	echo "  <input type=\"text\" name=\"elevenovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][2] : '';

	echo "  <input type=\"text\" name=\"elevenmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][3] : '';

	echo "  <input type=\"text\" name=\"elevenbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][4] : '';

	echo "  <input type=\"text\" name=\"elevenwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][5] : '';

	echo "  <input type=\"text\" name=\"elevennoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][6] : '';

	echo "  <input type=\"text\" name=\"elevenwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 10 ? $pl_data[10][7] : '';
	echo "  <input type=\"text\" name=\"elevenhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<input name=\"submit\" type=\"submit\" value=\"Previous\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Save and Previous\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Save and Next\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Next\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	
	echo "</form>\n";

	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	
}

function 

update_scorecard_step2($db,$submit,$game_id,$season,$innings_id,$oneplayer_id,$onehow_out,$oneassist,$oneassist2,$onebowler,$oneruns,$oneballs,$onefours,$onesixes,$onehwv,$onehlv,$twoplayer_id,
$twohow_out,$twoassist,$twoassist2,$twobowler,$tworuns,$twoballs,$twofours,$twosixes,$twohwv,$twohlv,$threeplayer_id,$threehow_out,$threeassist,$threeassist2,$threebowler,$threeruns,$threeballs,
$threefours,$threesixes,$threehwv,$threehlv,$fourplayer_id,$fourhow_out,$fourassist,$fourassist2,$fourbowler,$fourruns,$fourballs,$fourfours,$foursixes,$fourhwv,$fourhlv,$fiveplayer_id,$fivehow_out,$fiveassist,$fiveassist2,
$fivebowler,$fiveruns,$fiveballs,$fivefours,$fivesixes,$fivehwv,$fivehlv,$sixplayer_id,$sixhow_out,$sixassist,$sixassist2,$sixbowler,$sixruns,$sixballs,$sixfours,$sixsixes,$sixhwv,$sixhlv,$sevenplayer_id,$sevenhow_out,$sevenassist,$sevenassist2,$sevenbowler,$sevenruns,
$sevenballs,$sevenfours,$sevensixes,$sevenhwv,$sevenhlv,$eightplayer_id,$eighthow_out,$eightassist,$eightassist2,$eightbowler,$eightruns,$eightballs,$eightfours,$eightsixes,$eighthwv,$eighthlv,$nineplayer_id,$ninehow_out,$nineassist,$nineassist2,$ninebowler,$nineruns,
$nineballs,$ninefours,$ninesixes,$ninehwv,$ninehlv,$tenplayer_id,$tenhow_out,$tenassist,$tenassist2,$tenbowler,$tenruns,$tenballs,$tenfours,$tensixes,$tenhwv,$tenhlv,$elevenplayer_id,$elevenhow_out,$elevenassist,$elevenassist2,$elevenbowler,$elevenruns,$elevenballs,$elevenfours,
$elevensixes,$elevenhwv,$elevenhlv,$totwickets,$totovers,$tottotal,$totdltotal,$extlegbyes,$extbyes,$extwides,$extnoballs,$exttotal,$fowone,$fowtwo,$fowthree,$fowfour,$fowfive,$fowsix,$fowseven,
$foweight,$fownine,$fowten,$onebowler_id,$oneovers,$onemaidens,$onebowruns,$onewickets,$onenoballs,$onewides,$onehlvb,$twobowler_id,$twoovers,$twomaidens,$twobowruns,
$twowickets,$twonoballs,$twowides,$twohlvb,$threebowler_id,$threeovers,$threemaidens,$threebowruns,$threewickets,$threenoballs,$threewides,$threehlvb,$fourbowler_id,$fourovers,
$fourmaidens,$fourbowruns,$fourwickets,$fournoballs,$fourwides,$fourhlvb,$fivebowler_id,$fiveovers,$fivemaidens,$fivebowruns,$fivewickets,$fivenoballs,$fivewides,$fivehlvb,$sixbowler_id,$sixovers,$sixmaidens,
$sixbowruns,$sixwickets,$sixnoballs,$sixwides,$sixhlvb,$sevenbowler_id,$sevenovers,$sevenmaidens,$sevenbowruns,$sevenwickets,$sevennoballs,$sevenwides,$sevenhlvb,$eightbowler_id,$eightovers,$eightmaidens,
$eightbowruns,$eightwickets,$eightnoballs,$eightwides,$eighthlvb,$ninebowler_id,$nineovers,$ninemaidens,$ninebowruns,$ninewickets,$ninenoballs,$ninewides,$ninehlvb,$tenbowler_id,$tenovers,$tenmaidens,$tenbowruns,$tenwickets,
$tennoballs,$tenwides,$tenhlvb,$elevenbowler_id,$elevenovers,$elevenmaidens,$elevenbowruns,$elevenwickets,$elevennoballs,$elevenwides,$elevenhlvb,$onebatpos,$twobatpos,$threebatpos,$fourbatpos,$fivebatpos,$sixbatpos,$sevenbatpos,
$eightbatpos,$ninebatpos,$tenbatpos,$elevenbatpos,$onebowpos,$twobowpos,$threebowpos,$fourbowpos,$fivebowpos,$sixbowpos,$sevenbowpos,$eightbowpos,$ninebowpos,
$tenbowpos,$elevenbowpos,$oneteam,$twoteam,$threeteam,$fourteam,$fiveteam,$sixteam,$seventeam,$eightteam,$nineteam,$tenteam,$eleventeam,$oneopponent,$twoopponent,$threeopponent,$fouropponent,
$fiveopponent,$sixopponent,$sevenopponent,$eightopponent,$nineopponent,$tenopponent,$elevenopponent,$bowloneteam,$bowltwoteam,$bowlthreeteam,$bowlfourteam,$bowlfiveteam,$bowlsixteam,$bowlseventeam,
$bowleightteam,$bowlnineteam,$bowltenteam,$bowleleventeam,$bowloneopponent,$bowltwoopponent,$bowlthreeopponent,$bowlfouropponent,$bowlfiveopponent,$bowlsixopponent,$bowlsevenopponent,
$bowleightopponent,$bowlnineopponent,$bowltenopponent,$bowlelevenopponent)
{
	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	// make sure info is present and correct

	//if ($totwickets == "" || $totovers || $tottotal) {
	//	echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
	//	echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
	//	return;
	//}

	// setup variables

	if($submit == "Save and Previous" || $submit == "Save and Next") {
		$game_id = addslashes(trim($game_id));
		$season = addslashes(trim($season));
		$innings_id = addslashes(trim($innings_id));
		
		$onepl = addslashes(trim($oneplayer_id));
		$oneho = addslashes(trim($onehow_out));
		$oneas = addslashes(trim($oneassist));
		$oneas2 = addslashes(trim($oneassist2));
		$onebo = addslashes(trim($onebowler));
		$oneru = addslashes(trim($oneruns));
		$oneba = addslashes(trim($oneballs));
		$onefo = addslashes(trim($onefours));
		$onesi = addslashes(trim($onesixes));
		$onehv = addslashes(trim($onehwv));
		$onehl = addslashes(trim($onehlv));
		if($onehow_out != "2" && $onehow_out != "8") { $oneno = "0"; } else { $oneno = "1"; }

		$twopl = addslashes(trim($twoplayer_id));
		$twoho = addslashes(trim($twohow_out));
		$twoas = addslashes(trim($twoassist));
		$twoas2 = addslashes(trim($twoassist2));
		$twobo = addslashes(trim($twobowler));
		$tworu = addslashes(trim($tworuns));
		$twoba = addslashes(trim($twoballs));
		$twofo = addslashes(trim($twofours));
		$twosi = addslashes(trim($twosixes));
		$twohv = addslashes(trim($twohwv));
		$twohl = addslashes(trim($twohlv));
		if($twohow_out != "2" && $twohow_out != "8") { $twono = "0"; } else { $twono = "1"; }

		$threepl = addslashes(trim($threeplayer_id));
		$threeho = addslashes(trim($threehow_out));
		$threeas = addslashes(trim($threeassist));
		$threeas2 = addslashes(trim($threeassist2));
		$threebo = addslashes(trim($threebowler));
		$threeru = addslashes(trim($threeruns));
		$threeba = addslashes(trim($threeballs));
		$threefo = addslashes(trim($threefours));
		$threesi = addslashes(trim($threesixes));
		$threehv = addslashes(trim($threehwv));
		$threehl = addslashes(trim($threehlv));
		if($threehow_out != "2" && $threehow_out != "8") { $threeno = "0"; } else { $threeno = "1"; }

		$fourpl = addslashes(trim($fourplayer_id));
		$fourho = addslashes(trim($fourhow_out));
		$fouras = addslashes(trim($fourassist));
		$fouras2 = addslashes(trim($fourassist2));
		$fourbo = addslashes(trim($fourbowler));
		$fourru = addslashes(trim($fourruns));
		$fourba = addslashes(trim($fourballs));
		$fourfo = addslashes(trim($fourfours));
		$foursi = addslashes(trim($foursixes));
		$fourhv = addslashes(trim($fourhwv));
		$fourhl = addslashes(trim($fourhlv));
		if($fourhow_out != "2" && $fourhow_out != "8") { $fourno = "0"; } else { $fourno = "1"; }

		$fivepl = addslashes(trim($fiveplayer_id));
		$fiveho = addslashes(trim($fivehow_out));
		$fiveas = addslashes(trim($fiveassist));
		$fiveas2 = addslashes(trim($fiveassist2));
		$fivebo = addslashes(trim($fivebowler));
		$fiveru = addslashes(trim($fiveruns));
		$fiveba = addslashes(trim($fiveballs));
		$fivefo = addslashes(trim($fivefours));
		$fivesi = addslashes(trim($fivesixes));
		$fivehv = addslashes(trim($fivehwv));
		$fivehl = addslashes(trim($fivehlv));
		if($fivehow_out != "2" && $fivehow_out != "8") { $fiveno = "0"; } else { $fiveno = "1"; }

		$sixpl = addslashes(trim($sixplayer_id));
		$sixho = addslashes(trim($sixhow_out));
		$sixas = addslashes(trim($sixassist));
		$sixas2 = addslashes(trim($sixassist2));
		$sixbo = addslashes(trim($sixbowler));
		$sixru = addslashes(trim($sixruns));
		$sixba = addslashes(trim($sixballs));
		$sixfo = addslashes(trim($sixfours));
		$sixsi = addslashes(trim($sixsixes));
		$sixhv = addslashes(trim($sixhwv));
		$sixhl = addslashes(trim($sixhlv));
		if($sixhow_out != "2" && $sixhow_out != "8") { $sixno = "0"; } else { $sixno = "1"; }

		$sevenpl = addslashes(trim($sevenplayer_id));
		$sevenho = addslashes(trim($sevenhow_out));
		$sevenas = addslashes(trim($sevenassist));
		$sevenas2 = addslashes(trim($sevenassist2));
		$sevenbo = addslashes(trim($sevenbowler));
		$sevenru = addslashes(trim($sevenruns));
		$sevenba = addslashes(trim($sevenballs));
		$sevenfo = addslashes(trim($sevenfours));
		$sevensi = addslashes(trim($sevensixes));
		$sevenhv = addslashes(trim($sevenhwv));
		$sevenhl = addslashes(trim($sevenhlv));
		if($sevenhow_out != "2" && $sevenhow_out != "8") { $sevenno = "0"; } else { $sevenno = "1"; }

		$eightpl = addslashes(trim($eightplayer_id));
		$eightho = addslashes(trim($eighthow_out));
		$eightas = addslashes(trim($eightassist));
		$eightas2 = addslashes(trim($eightassist2));
		$eightbo = addslashes(trim($eightbowler));
		$eightru = addslashes(trim($eightruns));
		$eightba = addslashes(trim($eightballs));
		$eightfo = addslashes(trim($eightfours));
		$eightsi = addslashes(trim($eightsixes));
		$eighthv = addslashes(trim($eighthwv));
		$eighthl = addslashes(trim($eighthlv));
		if($eighthow_out != "2" && $eighthow_out != "8") { $eightno = "0"; } else { $eightno = "1"; }

		$ninepl = addslashes(trim($nineplayer_id));
		$nineho = addslashes(trim($ninehow_out));
		$nineas = addslashes(trim($nineassist));
		$nineas2 = addslashes(trim($nineassist2));
		$ninebo = addslashes(trim($ninebowler));
		$nineru = addslashes(trim($nineruns));
		$nineba = addslashes(trim($nineballs));
		$ninefo = addslashes(trim($ninefours));
		$ninesi = addslashes(trim($ninesixes));
		$ninehv = addslashes(trim($ninehwv));
		$ninehl = addslashes(trim($ninehlv));
		if($ninehow_out != "2" && $ninehow_out != "8") { $nineno = "0"; } else { $nineno = "1"; }

		$tenpl = addslashes(trim($tenplayer_id));
		$tenho = addslashes(trim($tenhow_out));
		$tenas = addslashes(trim($tenassist));
		$tenas2 = addslashes(trim($tenassist2));
		$tenbo = addslashes(trim($tenbowler));
		$tenru = addslashes(trim($tenruns));
		$tenba = addslashes(trim($tenballs));
		$tenfo = addslashes(trim($tenfours));
		$tensi = addslashes(trim($tensixes));
		$tenhv = addslashes(trim($tenhwv));
		$tenhl = addslashes(trim($tenhlv));
		if($tenhow_out != "2" && $tenhow_out != "8") { $tenno = "0"; } else { $tenno = "1"; }

		$elevenpl = addslashes(trim($elevenplayer_id));
		$elevenho = addslashes(trim($elevenhow_out));
		$elevenas = addslashes(trim($elevenassist));
		$elevenas2 = addslashes(trim($elevenassist2));
		$elevenbo = addslashes(trim($elevenbowler));
		$elevenru = addslashes(trim($elevenruns));
		$elevenba = addslashes(trim($elevenballs));
		$elevenfo = addslashes(trim($elevenfours));
		$elevensi = addslashes(trim($elevensixes));
		$elevenhv = addslashes(trim($elevenhwv));
		$elevenhl = addslashes(trim($elevenhlv));
		if($elevenhow_out != "2" && $elevenhow_out != "8") { $elevenno = "0"; } else { $elevenno = "1"; }

		$totw = addslashes(trim($totwickets));
		$toto = addslashes(trim($totovers));
		$tott = addslashes(trim($tottotal));
		$totdt = addslashes(trim($totdltotal));
		
		$extl = addslashes(trim($extlegbyes));
		$extb = addslashes(trim($extbyes));
		$extw = addslashes(trim($extwides));
		$extn = addslashes(trim($extnoballs));
		$extt = addslashes(trim($exttotal));
		
		// Need to set the FoW to 777 if it is NULL
		
		if($fowone !="") {
		  $f1 = addslashes(trim($fowone));
		} else {
		  $f1 = "777";
		}
		
		if($fowtwo !="") {
		  $f2 = addslashes(trim($fowtwo));
		} else {
		  $f2 = "777";
		}
		if($fowthree !="") {
		  $f3 = addslashes(trim($fowthree));
		} else {
		  $f3 = "777";
		}
		if($fowfour !="") {
		  $f4 = addslashes(trim($fowfour));
		} else {
		  $f4 = "777";
		}
		if($fowfive !="") {
		  $f5 = addslashes(trim($fowfive));
		} else {
		  $f5 = "777";
		}
		if($fowsix !="") {
		  $f6 = addslashes(trim($fowsix));
		} else {
		  $f6 = "777";
		}
		if($fowseven !="") {
		  $f7 = addslashes(trim($fowseven));
		} else {
		  $f7 = "777";
		}
		if($foweight !="") {
		  $f8 = addslashes(trim($foweight));
		} else {
		  $f8 = "777";
		}
		if($fownine !="") {
		  $f9 = addslashes(trim($fownine));
		} else {
		  $f9 = "777";
		}
		if($fowten !="") {
		  $f10 = addslashes(trim($fowten));
		} else {
		  $f10 = "777";
		}
		
		$onebow = addslashes(trim($onebowler_id));
		$oneove = addslashes(trim($oneovers));
		$onemai = addslashes(trim($onemaidens));
		$onebru = addslashes(trim($onebowruns));
		$onewic = addslashes(trim($onewickets));
		$onenob = addslashes(trim($onenoballs));
		$onewid = addslashes(trim($onewides));
		$onehlb = addslashes(trim($onehlvb));

		$twobow = addslashes(trim($twobowler_id));
		$twoove = addslashes(trim($twoovers));
		$twomai = addslashes(trim($twomaidens));
		$twobru = addslashes(trim($twobowruns));
		$twowic = addslashes(trim($twowickets));
		$twonob = addslashes(trim($twonoballs));
		$twowid = addslashes(trim($twowides));
		$twohlb = addslashes(trim($twohlvb));

		$threebow = addslashes(trim($threebowler_id));
		$threeove = addslashes(trim($threeovers));
		$threemai = addslashes(trim($threemaidens));
		$threebru = addslashes(trim($threebowruns));
		$threewic = addslashes(trim($threewickets));
		$threenob = addslashes(trim($threenoballs));
		$threewid = addslashes(trim($threewides));
		$threehlb = addslashes(trim($threehlvb));

		$fourbow = addslashes(trim($fourbowler_id));
		$fourove = addslashes(trim($fourovers));
		$fourmai = addslashes(trim($fourmaidens));
		$fourbru = addslashes(trim($fourbowruns));
		$fourwic = addslashes(trim($fourwickets));
		$fournob = addslashes(trim($fournoballs));
		$fourwid = addslashes(trim($fourwides));
		$fourhlb = addslashes(trim($fourhlvb));

		$fivebow = addslashes(trim($fivebowler_id));
		$fiveove = addslashes(trim($fiveovers));
		$fivemai = addslashes(trim($fivemaidens));
		$fivebru = addslashes(trim($fivebowruns));
		$fivewic = addslashes(trim($fivewickets));
		$fivenob = addslashes(trim($fivenoballs));
		$fivewid = addslashes(trim($fivewides));
		$fivehlb = addslashes(trim($fivehlvb));

		$sixbow = addslashes(trim($sixbowler_id));
		$sixove = addslashes(trim($sixovers));
		$sixmai = addslashes(trim($sixmaidens));
		$sixbru = addslashes(trim($sixbowruns));
		$sixwic = addslashes(trim($sixwickets));
		$sixnob = addslashes(trim($sixnoballs));
		$sixwid = addslashes(trim($sixwides));
		$sixhlb = addslashes(trim($sixhlvb));

		$sevenbow = addslashes(trim($sevenbowler_id));
		$sevenove = addslashes(trim($sevenovers));
		$sevenmai = addslashes(trim($sevenmaidens));
		$sevenbru = addslashes(trim($sevenbowruns));
		$sevenwic = addslashes(trim($sevenwickets));
		$sevennob = addslashes(trim($sevennoballs));
		$sevenwid = addslashes(trim($sevenwides));
		$sevenhlb = addslashes(trim($sevenhlvb));

		$eightbow = addslashes(trim($eightbowler_id));
		$eightove = addslashes(trim($eightovers));
		$eightmai = addslashes(trim($eightmaidens));
		$eightbru = addslashes(trim($eightbowruns));
		$eightwic = addslashes(trim($eightwickets));
		$eightnob = addslashes(trim($eightnoballs));
		$eightwid = addslashes(trim($eightwides));
		$eighthlb = addslashes(trim($eighthlvb));

		$ninebow = addslashes(trim($ninebowler_id));
		$nineove = addslashes(trim($nineovers));
		$ninemai = addslashes(trim($ninemaidens));
		$ninebru = addslashes(trim($ninebowruns));
		$ninewic = addslashes(trim($ninewickets));
		$ninenob = addslashes(trim($ninenoballs));
		$ninewid = addslashes(trim($ninewides));
		$ninehlb = addslashes(trim($ninehlvb));

		$tenbow = addslashes(trim($tenbowler_id));
		$tenove = addslashes(trim($tenovers));
		$tenmai = addslashes(trim($tenmaidens));
		$tenbru = addslashes(trim($tenbowruns));
		$tenwic = addslashes(trim($tenwickets));
		$tennob = addslashes(trim($tennoballs));
		$tenwid = addslashes(trim($tenwides));
		$tenhlb = addslashes(trim($tenhlvb));

		$elevenbow = addslashes(trim($elevenbowler_id));
		$elevenove = addslashes(trim($elevenovers));
		$elevenmai = addslashes(trim($elevenmaidens));
		$elevenbru = addslashes(trim($elevenbowruns));
		$elevenwic = addslashes(trim($elevenwickets));
		$elevennob = addslashes(trim($elevennoballs));
		$elevenwid = addslashes(trim($elevenwides));
		$elevenhlb = addslashes(trim($elevenhlvb));

		$onebap = addslashes(trim($onebatpos));
		$twobap = addslashes(trim($twobatpos));
		$threebap = addslashes(trim($threebatpos));
		$fourbap = addslashes(trim($fourbatpos));
		$fivebap = addslashes(trim($fivebatpos));
		$sixbap = addslashes(trim($sixbatpos));
		$sevenbap = addslashes(trim($sevenbatpos));
		$eightbap = addslashes(trim($eightbatpos));
		$ninebap = addslashes(trim($ninebatpos));
		$tenbap = addslashes(trim($tenbatpos));
		$elevenbap = addslashes(trim($elevenbatpos));
		$onebop = addslashes(trim($onebowpos));
		$twobop = addslashes(trim($twobowpos));
		$threebop = addslashes(trim($threebowpos));
		$fourbop = addslashes(trim($fourbowpos));
		$fivebop = addslashes(trim($fivebowpos));
		$sixbop = addslashes(trim($sixbowpos));
		$sevenbop = addslashes(trim($sevenbowpos));
		$eightbop = addslashes(trim($eightbowpos));
		$ninebop = addslashes(trim($ninebowpos));
		$tenbop = addslashes(trim($tenbowpos));
		$elevenbop = addslashes(trim($elevenbowpos));
		
		$onetm = addslashes(trim($oneteam));
		$twotm = addslashes(trim($twoteam));
		$threetm = addslashes(trim($threeteam));
		$fourtm = addslashes(trim($fourteam));
		$fivetm = addslashes(trim($fiveteam));
		$sixtm = addslashes(trim($sixteam));
		$seventm = addslashes(trim($seventeam));
		$eighttm = addslashes(trim($eightteam));
		$ninetm = addslashes(trim($nineteam));
		$tentm = addslashes(trim($tenteam));
		$eleventm = addslashes(trim($eleventeam));
		$oneopp = addslashes(trim($oneopponent));
		$twoopp = addslashes(trim($twoopponent));
		$threeopp = addslashes(trim($threeopponent));
		$fouropp = addslashes(trim($fouropponent));
		$fiveopp = addslashes(trim($fiveopponent));
		$sixopp = addslashes(trim($sixopponent));
		$sevenopp = addslashes(trim($sevenopponent));
		$eightopp = addslashes(trim($eightopponent));
		$nineopp = addslashes(trim($nineopponent));
		$tenopp = addslashes(trim($tenopponent));
		$elevenopp = addslashes(trim($elevenopponent));

		$bowlonetm = addslashes(trim($bowloneteam));
		$bowltwotm = addslashes(trim($bowltwoteam));
		$bowlthreetm = addslashes(trim($bowlthreeteam));
		$bowlfourtm = addslashes(trim($bowlfourteam));
		$bowlfivetm = addslashes(trim($bowlfiveteam));
		$bowlsixtm = addslashes(trim($bowlsixteam));
		$bowlseventm = addslashes(trim($bowlseventeam));
		$bowleighttm = addslashes(trim($bowleightteam));
		$bowlninetm = addslashes(trim($bowlnineteam));
		$bowltentm = addslashes(trim($bowltenteam));
		$bowleleventm = addslashes(trim($bowleleventeam));
		$bowloneopp = addslashes(trim($bowloneopponent));
		$bowltwoopp = addslashes(trim($bowltwoopponent));
		$bowlthreeopp = addslashes(trim($bowlthreeopponent));
		$bowlfouropp = addslashes(trim($bowlfouropponent));
		$bowlfiveopp = addslashes(trim($bowlfiveopponent));
		$bowlsixopp = addslashes(trim($bowlsixopponent));
		$bowlsevenopp = addslashes(trim($bowlsevenopponent));
		$bowleightopp = addslashes(trim($bowleightopponent));
		$bowlnineopp = addslashes(trim($bowlnineopponent));
		$bowltenopp = addslashes(trim($bowltenopponent));
		$bowlelevenopp = addslashes(trim($bowlelevenopponent));
		

		$db->Delete("DELETE FROM scorecard_extras_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		$db->Insert("INSERT INTO scorecard_extras_details (game_id,innings_id,legbyes,byes,wides,noballs,total) VALUES ('$game_id','$innings_id','$extl','$extb','$extw','$extn','$extt')");
		$db->Delete("DELETE FROM scorecard_total_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		$db->Insert("INSERT INTO scorecard_total_details (game_id,innings_id,team,wickets,total,overs,dl_total) VALUES ('$game_id','$innings_id','$oneteam','$totw','$tott','$toto','$totdt')");
		$db->Delete("DELETE FROM scorecard_fow_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		$db->Insert("INSERT INTO scorecard_fow_details (game_id,innings_id,fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10) VALUES 
	('$game_id','$innings_id','$f1','$f2','$f3','$f4','$f5','$f6','$f7','$f8','$f9','$f10')");	


		// check to see if there is an entry of batter

		$db->Delete("DELETE FROM scorecard_batting_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		if ($onepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details 
		(game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$onepl','$onebap','$oneho','$oneru','$oneas','$oneas2','$onebo','$oneba','$onefo','$onesi','$onehv','$onehl','$oneno','$onetm','$oneopp')");
		if ($twopl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$twopl','$twobap','$twoho','$tworu','$twoas','$twoas2','$twobo','$twoba','$twofo','$twosi','$twohv','$twohl','$twono','$twotm','$twoopp')");
		if ($threepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$threepl','$threebap','$threeho','$threeru','$threeas','$threeas2','$threebo','$threeba','$threefo','$threesi','$threehv','$threehl','$threeno','$threetm','$threeopp')");
		if ($fourpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fourpl','$fourbap','$fourho','$fourru','$fouras','$fouras2','$fourbo','$fourba','$fourfo','$foursi','$fourhv','$fourhl','$fourno','$fourtm','$fouropp')");
		if ($fivepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fivepl','$fivebap','$fiveho','$fiveru','$fiveas','$fiveas2','$fivebo','$fiveba','$fivefo','$fivesi','$fivehv','$fivehl','$fiveno','$fivetm','$fiveopp')");
		if ($sixpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sixpl','$sixbap','$sixho','$sixru','$sixas','$sixas2','$sixbo','$sixba','$sixfo','$sixsi','$sixhv','$sixhl','$sixno','$sixtm','$sixopp')");
		if ($sevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sevenpl','$sevenbap','$sevenho','$sevenru','$sevenas','$sevenas2','$sevenbo','$sevenba','$sevenfo','$sevensi','$sevenhv','$sevenhl','$sevenno','$seventm','$sevenopp')");
		if ($eightpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$eightpl','$eightbap','$eightho','$eightru','$eightas','$eightas2','$eightbo','$eightba','$eightfo','$eightsi','$eighthv','$eighthl','$eightno','$eighttm','$eightopp')");
		if ($ninepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$ninepl','$ninebap','$nineho','$nineru','$nineas','$nineas2','$ninebo','$nineba','$ninefo','$ninesi','$ninehv','$ninehl','$nineno','$ninetm','$nineopp')");
		if ($tenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$tenpl','$tenbap','$tenho','$tenru','$tenas','$tenas2','$tenbo','$tenba','$tenfo','$tensi','$tenhv','$tenhl','$tenno','$tentm','$tenopp')");
		if ($elevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$elevenpl','$elevenbap','$elevenho','$elevenru','$elevenas','$elevenas2','$elevenbo','$elevenba','$elevenfo','$elevensi','$elevenhv','$elevenhl','$elevenno','$eleventm','$elevenopp')");

		// check to see if there is an entry of bowler

		$db->Delete("DELETE FROM scorecard_bowling_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		if ($onebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$onebow','$onebop','$oneove','$onemai','$onebru','$onewic','$onenob','$onewid','$onehlb','$bowlonetm','$bowloneopp')");
		if ($twobow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$twobow','$twobop','$twoove','$twomai','$twobru','$twowic','$twonob','$twowid','$twohlb','$bowltwotm','$bowltwoopp')");
		if ($threebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$threebow','$threebop','$threeove','$threemai','$threebru','$threewic','$threenob','$threewid','$threehlb','$bowlthreetm','$bowlthreeopp')");
		if ($fourbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fourbow','$fourbop','$fourove','$fourmai','$fourbru','$fourwic','$fournob','$fourwid','$fourhlb','$bowlfourtm','$bowlfouropp')");
		if ($fivebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fivebow','$fivebop','$fiveove','$fivemai','$fivebru','$fivewic','$fivenob','$fivewid','$fivehlb','$bowlfivetm','$bowlfiveopp')");
		if ($sixbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sixbow','$sixbop','$sixove','$sixmai','$sixbru','$sixwic','$sixnob','$sixwid','$sixhlb','$bowlsixtm','$bowlsixopp')");
		if ($sevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sevenbow','$sevenbop','$sevenove','$sevenmai','$sevenbru','$sevenwic','$sevennob','$sevenwid','$sevenhlb','$bowlseventm','$bowlsevenopp')");
		if ($eightbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$eightbow','$eightbop','$eightove','$eightmai','$eightbru','$eightwic','$eightnob','$eightwid','$eighthlb','$bowleighttm','$bowleightopp')");
		if ($ninebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$ninebow','$ninebop','$nineove','$ninemai','$ninebru','$ninewic','$ninenob','$ninewid','$ninehlb','$bowlninetm','$bowlnineopp')");
		if ($tenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$tenbow','$tenbop','$tenove','$tenmai','$tenbru','$tenwic','$tennob','$tenwid','$tenhlb','$bowltentm','$bowltenopp')");
		if ($elevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$elevenbow','$elevenbop','$elevenove','$elevenmai','$elevenbru','$elevenwic','$elevennob','$elevenwid','$elevenhlb','$bowleleventm','$bowlelevenopp')");

		if($submit == "Save and Previous") {
			header("Location: main.php?SID=$SID&action=$action&do=sedit&game_id=$game_id");
			ob_end_flush();
		} else {
			header("Location: main.php?SID=$SID&action=$action&do=update4&game_id=$game_id");
			ob_end_flush();
		}
	} else if($submit == "Previous") {
		header("Location: main.php?SID=$SID&action=$action&do=sedit&game_id=$game_id");
		ob_end_flush();
	} else {
		header("Location: main.php?SID=$SID&action=$action&do=update4&game_id=$game_id");
		ob_end_flush();
	}

}

function edit_scorecard_step3($db,$game_id)
{
	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	$db->QueryRow("
	SELECT
	  s.*,
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	  u.TeamID AS 'umpireid', u.TeamName AS UmpireName, u.TeamAbbrev AS 'umpireabbrev',
	  t.TeamID AS WonTossID, t.TeamName AS WonTossName, t.TeamAbbrev AS WonTossAbbrev,
	  b.TeamID AS BatFirstID, b.TeamName AS BatFirstName, b.TeamAbbrev AS BatFirstAbbrev,
	  n.TeamID AS BatSecondID, n.TeamName AS BatSecondName, n.TeamAbbrev AS BatSecondAbbrev,
	  g.GroundID, g.GroundName
	FROM
	  scorecard_game_details s
	INNER JOIN
	  grounds g ON s.ground_id = g.GroundID
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	LEFT JOIN
	  teams u ON s.umpires = u.TeamID
	LEFT JOIN
	  teams t ON s.toss_won_id = t.TeamID
	INNER JOIN
	  teams b ON s.batting_first_id = b.TeamID
	INNER JOIN
	  teams n ON s.batting_second_id = n.TeamID	  
	WHERE 
	  s.game_id = '$game_id' 
	");

	$db->BagAndTag();

	$gid = $db->data['game_id'];
	$gsc = $db->data['season'];
	$gli = $db->data['league_id'];
	
	$ght = $db->data['homeabbrev'];
	$ghi = $db->data['homeid'];
	$gat = $db->data['awayabbrev'];
	$gai = $db->data['awayid'];
	
	$gut = $db->data['umpireabbrev'];
	$ggr = $db->data['GroundName'];
	$ggi = $db->data['GroundID'];
	$gre = $db->data['result'];
	$gtt = $db->data['WonTossAbbrev'];

	$gda = sqldate_to_string($db->data['game_date']);

	$bat1st   = $db->data['BatFirstAbbrev'];
	$bat1stid = $db->data['BatFirstID'];
	$bat2nd   = $db->data['BatSecondAbbrev'];
	$bat2ndid = $db->data['BatSecondID'];
	

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";

	echo "<p class=\"14px\">Step 3 - Enter 2nd Innings Details<br><img src=\"/images/66.gif\"></p>\n";
	
	echo "<p>You are working with <b>Game #$gid</b>, <b>$bat1st</b> ($bat1stid) vs <b>$bat2nd</b> ($bat2ndid) on <b>$gda</b></p>\n";
//	echo "<p align=\"left\"><b><font color=\"red\">IMPORTANT!</font></b> If you are using <a href=\"http://www.getfirefox.com\" target=\"_new\">Firefox</a> then you may <a href=\"addplayer.php\" target=\"_new\">add new players</a> at any time. Please REFRESH the page once adding, Firefox should remember your selections. If you have another browser, check the drop-down menu's first to make sure that all players exist. Other browsers may not remember form selection data.</p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Team Batting 2nd Details - $bat2nd</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<form action=\"main.php?SID=$SID&action=$action&do=update5\" method=\"post\" enctype=\"multipart/form-data\" name=\"comboForm\">\n";
	
	echo "<input type=\"hidden\" name=\"innings_id\" value=\"2\">\n";
	echo "<input type=\"hidden\" name=\"game_id\" value=\"$gid\">\n";
	echo "<input type=\"hidden\" name=\"season\" value=\"$gsc\">\n";
	echo "<input type=\"hidden\" name=\"onebatpos\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"twobatpos\" value=\"2\">\n";
	echo "<input type=\"hidden\" name=\"threebatpos\" value=\"3\">\n";
	echo "<input type=\"hidden\" name=\"fourbatpos\" value=\"4\">\n";
	echo "<input type=\"hidden\" name=\"fivebatpos\" value=\"5\">\n";
	echo "<input type=\"hidden\" name=\"sixbatpos\" value=\"6\">\n";
	echo "<input type=\"hidden\" name=\"sevenbatpos\" value=\"7\">\n";
	echo "<input type=\"hidden\" name=\"eightbatpos\" value=\"8\">\n";
	echo "<input type=\"hidden\" name=\"ninebatpos\" value=\"9\">\n";
	echo "<input type=\"hidden\" name=\"tenbatpos\" value=\"10\">\n";
	echo "<input type=\"hidden\" name=\"elevenbatpos\" value=\"11\">\n";
	echo "<input type=\"hidden\" name=\"onebowpos\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"twobowpos\" value=\"2\">\n";
	echo "<input type=\"hidden\" name=\"threebowpos\" value=\"3\">\n";
	echo "<input type=\"hidden\" name=\"fourbowpos\" value=\"4\">\n";
	echo "<input type=\"hidden\" name=\"fivebowpos\" value=\"5\">\n";
	echo "<input type=\"hidden\" name=\"sixbowpos\" value=\"6\">\n";
	echo "<input type=\"hidden\" name=\"sevenbowpos\" value=\"7\">\n";
	echo "<input type=\"hidden\" name=\"eightbowpos\" value=\"8\">\n";
	echo "<input type=\"hidden\" name=\"ninebowpos\" value=\"9\">\n";
	echo "<input type=\"hidden\" name=\"tenbowpos\" value=\"10\">\n";
	echo "<input type=\"hidden\" name=\"elevenbowpos\" value=\"11\">\n";
	echo "<input type=\"hidden\" name=\"oneteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"twoteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"threeteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"fourteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"fiveteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"sixteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"seventeam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"eightteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"nineteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"tenteam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"eleventeam\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"oneopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"twoopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"threeopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"fouropponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"fiveopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"sixopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"sevenopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"eightopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"nineopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"tenopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"elevenopponent\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowloneteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowltwoteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlthreeteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfourteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfiveteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlsixteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlseventeam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowleightteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowlnineteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowltenteam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowleleventeam\" value=\"$bat1stid\">\n";
	echo "<input type=\"hidden\" name=\"bowloneopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowltwoopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlthreeopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfouropponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlfiveopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlsixopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlsevenopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowleightopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlnineopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowltenopponent\" value=\"$bat2ndid\">\n";
	echo "<input type=\"hidden\" name=\"bowlelevenopponent\" value=\"$bat2ndid\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	$pl_data = array();
	$db->Query("
    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,s.howout_video,s.highlights_video,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev, b.PlayerID AS BowlerID, a.PlayerID AS AssistID,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
	  a2.PlayerID AS AssistID2, a2.PlayerLName AS AssistLName2, a2.PlayerFName AS AssistFName2, LEFT(a2.PlayerFName,1) AS AssistFInitial2,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players a2 ON a2.PlayerID = s.assist2
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $gid AND s.innings_id = 2
    ORDER BY
      s.batting_position
    ");

    for ($x=0; $x<$db->rows; $x++) {
    $db->GetRow($x);

    $pid = $db->data['BatterID'];
    $bid = $db->data['BowlerID'];
    $aid = $db->data['AssistID'];
    $pln = $db->data['BatterLName'];
    $pfn = $db->data['BatterFName'];
    $pin = $db->data['BatterFInitial'];
    $bln = $db->data['BowlerLName'];
    $bfn = $db->data['BowlerFName'];
    $bin = $db->data['BowlerFInitial'];
    $aln = $db->data['AssistLName'];
    $afn = $db->data['AssistFName'];
    $ain = $db->data['AssistFInitial'];
    $a2id = $db->data['AssistID2'];
    $a2ln = $db->data['AssistLName2'];
    $a2fn = $db->data['AssistFName2'];
    $a2in = $db->data['AssistFInitial2'];
    $out = $db->data['HowOutAbbrev'];
    $oid = $db->data['HowOutID'];
    $run = $db->data['runs'];
    $bal = $db->data['balls'];
    $fou = $db->data['fours'];
    $six = $db->data['sixes'];
    $hwv = $db->data['howout_video'];
	$hlv = $db->data['highlights_video'];
	$pl_rec = array ($pid, $bid, $aid, $oid, $run, $bal, $fou, $six, $hwv, $hlv, $a2id);
	$pl_data[$x] = $pl_rec;
	}
	echo " <tr>\n";
	
	echo "  <td width=\"70%\" colspan=\"5\">&nbsp;</td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Runs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Balls</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>4s</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>6s</b></td>\n";
	
	echo " </tr>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                1st Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneplayer_id\" id=\"combobox1\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">1st Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"onehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneassist\" id=\"combobox2\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"onebowler\" id=\"combobox3\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][4] : '';
	echo "  <input type=\"text\" name=\"oneruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][5] : '';
	echo "  <input type=\"text\" name=\"oneballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][6] : '';
	echo "  <input type=\"text\" name=\"onefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][7] : '';
	echo "  <input type=\"text\" name=\"onesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 0 ? $pl_data[0][8] : '';
	echo "  <input type=\"text\" name=\"onehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 0 ? $pl_data[0][9] : '';
	echo "  <input type=\"text\" name=\"onehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoplayer_id\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">2nd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"twohow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoassist\" id=\"combobox5\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[1][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twobowler\" id=\"combobox6\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][4] : '';
	echo "  <input type=\"text\" name=\"tworuns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][5] : '';
	echo "  <input type=\"text\" name=\"twoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][6] : '';
	echo "  <input type=\"text\" name=\"twofours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][7] : '';
	echo "  <input type=\"text\" name=\"twosixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 1 ? $pl_data[1][8] : '';
	echo "  <input type=\"text\" name=\"twohwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 1 ? $pl_data[1][9] : '';
	echo "  <input type=\"text\" name=\"twohlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeplayer_id\" id=\"combobox7\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">3rd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"threehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeassist\" id=\"combobox8\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[2][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threebowler\" id=\"combobox9\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][4] : '';
	echo "  <input type=\"text\" name=\"threeruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][5] : '';
	echo "  <input type=\"text\" name=\"threeballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][6] : '';
	echo "  <input type=\"text\" name=\"threefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][7] : '';
	echo "  <input type=\"text\" name=\"threesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 2 ? $pl_data[2][8] : '';
	echo "  <input type=\"text\" name=\"threehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 2 ? $pl_data[2][9] : '';
	echo "  <input type=\"text\" name=\"threehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourplayer_id\" id=\"combobox10\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">4th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"fourhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourassist\" id=\"combobox11\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[3][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourbowler\" id=\"combobox12\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][4] : '';
	echo "  <input type=\"text\" name=\"fourruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][5] : '';
	echo "  <input type=\"text\" name=\"fourballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][6] : '';
	echo "  <input type=\"text\" name=\"fourfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][7] : '';
	echo "  <input type=\"text\" name=\"foursixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 3 ? $pl_data[3][8] : '';
	echo "  <input type=\"text\" name=\"fourhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 3 ? $pl_data[3][9] : '';
	echo "  <input type=\"text\" name=\"fourhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveplayer_id\" id=\"combobox13\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">5th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"fivehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveassist\" id=\"combobox14\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[4][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fivebowler\" id=\"combobox15\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][4] : '';
	echo "  <input type=\"text\" name=\"fiveruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][5] : '';
	echo "  <input type=\"text\" name=\"fiveballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][6] : '';
	echo "  <input type=\"text\" name=\"fivefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][7] : '';
	echo "  <input type=\"text\" name=\"fivesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 4 ? $pl_data[4][8] : '';
	echo "  <input type=\"text\" name=\"fivehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 4 ? $pl_data[4][9] : '';
	echo "  <input type=\"text\" name=\"fivehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixplayer_id\" id=\"combobox16\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">6th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"sixhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixassist\" id=\"combobox17\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[5][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixbowler\" id=\"combobox18\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][4] : '';
	echo "  <input type=\"text\" name=\"sixruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][5] : '';
	echo "  <input type=\"text\" name=\"sixballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][6] : '';
	echo "  <input type=\"text\" name=\"sixfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][7] : '';
	echo "  <input type=\"text\" name=\"sixsixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 5 ? $pl_data[5][8] : '';
	echo "  <input type=\"text\" name=\"sixhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 5 ? $pl_data[5][9] : '';
	echo "  <input type=\"text\" name=\"sixhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenplayer_id\" id=\"combobox19\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">7th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"sevenhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenassist\" id=\"combobox20\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[6][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenbowler\" id=\"combobox21\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][4] : '';
	echo "  <input type=\"text\" name=\"sevenruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][5] : '';
	echo "  <input type=\"text\" name=\"sevenballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][6] : '';
	echo "  <input type=\"text\" name=\"sevenfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][7] : '';
	echo "  <input type=\"text\" name=\"sevensixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 6 ? $pl_data[6][8] : '';
	echo "  <input type=\"text\" name=\"sevenhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 6 ? $pl_data[6][9] : '';
	echo "  <input type=\"text\" name=\"sevenhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightplayer_id\" id=\"combobox22\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">8th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"eighthow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightassist\" id=\"combobox23\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[7][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightbowler\" id=\"combobox24\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 7) {
				if($pl_data[7][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][4] : '';
	echo "  <input type=\"text\" name=\"eightruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][5] : '';
	echo "  <input type=\"text\" name=\"eightballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][6] : '';
	echo "  <input type=\"text\" name=\"eightfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][7] : '';
	echo "  <input type=\"text\" name=\"eightsixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 7 ? $pl_data[7][8] : '';
	echo "  <input type=\"text\" name=\"eighthwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 7 ? $pl_data[7][9] : '';
	echo "  <input type=\"text\" name=\"eighthlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Batsman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineplayer_id\" id=\"combobox25\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">9th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"ninehow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineassist\" id=\"combobox26\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[8][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"ninebowler\" id=\"combobox27\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][4] : '';
	echo "  <input type=\"text\" name=\"nineruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][5] : '';
	echo "  <input type=\"text\" name=\"nineballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][6] : '';
	echo "  <input type=\"text\" name=\"ninefours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][7] : '';
	echo "  <input type=\"text\" name=\"ninesixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 8 ? $pl_data[8][8] : '';
	echo "  <input type=\"text\" name=\"ninehwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 8 ? $pl_data[8][9] : '';
	echo "  <input type=\"text\" name=\"ninehlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Batsman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenplayer_id\" id=\"combobox28\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">10th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"tenhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenassist\" id=\"combobox29\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[9][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenbowler\" id=\"combobox30\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][4] : '';
	echo "  <input type=\"text\" name=\"tenruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][5] : '';
	echo "  <input type=\"text\" name=\"tenballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][6] : '';
	echo "  <input type=\"text\" name=\"tenfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][7] : '';
	echo "  <input type=\"text\" name=\"tensixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 9 ? $pl_data[9][8] : '';
	echo "  <input type=\"text\" name=\"tenhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 9 ? $pl_data[9][9] : '';
	echo "  <input type=\"text\" name=\"tenhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Batsman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenplayer_id\" id=\"combobox31\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">11th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat2ndid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"10%\" align=\"left\">";
	echo "  <select name=\"elevenhow_out\">\n";
	echo "	<option value=\"\">howout</option>\n";
	echo "	<option value=\"\">------</option>\n";
	if ($db->Exists("SELECT * FROM howout")) {
		$db->Query("SELECT * FROM howout ORDER BY HowOutID");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][3] == $db->data['HowOutID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenassist\" id=\"combobox32\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][2] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenassist2\" id=\"combobox4\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Assist2</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[10][10] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenbowler\" id=\"combobox33\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][1] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][4] : '';
	echo "  <input type=\"text\" name=\"elevenruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][5] : '';
	echo "  <input type=\"text\" name=\"elevenballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][6] : '';
	echo "  <input type=\"text\" name=\"elevenfours\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][7] : '';
	echo "  <input type=\"text\" name=\"elevensixes\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"8\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"left\"><b>How Out Video link:</b>";
	$value = count($pl_data) > 10 ? $pl_data[10][8] : '';
	echo "  <input type=\"text\" name=\"elevenhwv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 10 ? $pl_data[10][9] : '';
	echo "  <input type=\"text\" name=\"elevenhlv\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	

	echo "</table>\n";

	echo "<p>* Batter, how out and runs are required.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<br>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                           Extras & Toals Details                                       //
////////////////////////////////////////////////////////////////////////////////////////////


	$by = $lb = $wd = $nb = $et = $wi = $to = $ov = "";
	if($db->Exists("
		SELECT
		  legbyes, byes, wides, noballs, total
		FROM
		  scorecard_extras_details
		WHERE
		  game_id = $gid AND innings_id = 2
		")) {
		$db->QueryItem("
			SELECT
			  legbyes, byes, wides, noballs, total
			FROM
			  scorecard_extras_details
			WHERE
			  game_id = $gid AND innings_id = 2
			");

		$by = $db->data['byes'];
		$lb = $db->data['legbyes'];
		$wd = $db->data['wides'];
		$nb = $db->data['noballs'];
		$et = $db->data['total'];
	}
	if($db->Exists("
		SELECT
		  wickets, total, overs, dl_total
		FROM
		  scorecard_total_details
		WHERE
		  game_id = $gid AND innings_id = 2
		")) {
		$db->QueryItem("
			SELECT
			  wickets, total, overs, dl_total
			FROM
			  scorecard_total_details
			WHERE
			  game_id = $gid AND innings_id = 2
			");

		$wi = $db->data['wickets'];
		$to = $db->data['total'];
		$ov = $db->data['overs'];
		$dl_to = $db->data['dl_total'];
	}

	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
   	echo "<tr>\n";
   	echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Total & Extras Details - $bat2nd</td>\n";
   	echo "</tr>\n";
   	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	echo " <tr>\n";
	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wickets</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Overs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Total</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>DL Total</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Legbyes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Byes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wides</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Noballs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Total</b></td>\n";
	
	echo " </tr>\n";
	echo " <tr>\n";
	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totwickets\" size=\"5\" maxlength=\"7\" value=\"$wi\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totovers\" size=\"5\" maxlength=\"7\" value=\"$ov\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"tottotal\" size=\"5\" maxlength=\"7\" value=\"$to\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totdltotal\" size=\"5\" maxlength=\"7\" value=\"$dl_to\"></td>\n";	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extlegbyes\" size=\"5\" maxlength=\"7\" value=\"$lb\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extbyes\" size=\"5\" maxlength=\"7\" value=\"$by\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extwides\" size=\"5\" maxlength=\"7\" value=\"$wd\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extnoballs\" size=\"5\" maxlength=\"7\" value=\"$nb\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"exttotal\" size=\"5\" maxlength=\"7\" value=\"$et\"></td>\n";	
	
	echo " </tr>\n";
	echo "</table>\n";


	
	echo "<p>* Wickets, Total Runs and Overs required.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<br>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                    Fow Details                                         //
////////////////////////////////////////////////////////////////////////////////////////////

	$f1 = $f2 = $f3 = $f4 = $f5 = $f6 = $f7 = $f8 = $f9 = $f10 = "";
	if($db->Exists("
		SELECT
		  legbyes, byes, wides, noballs, total
		FROM
		  scorecard_extras_details
		WHERE
		  game_id = $gid AND innings_id = 2
		")) {
		$db->QueryItem("
			SELECT
			  fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10
			FROM
			  scorecard_fow_details
			WHERE
			  game_id = $gid AND innings_id = 2
			");

		$f1 = $db->data['fow1'];
		$f2 = $db->data['fow2'];
		$f3 = $db->data['fow3'];
		$f4 = $db->data['fow4'];
		$f5 = $db->data['fow5'];
		$f6 = $db->data['fow6'];
		$f7 = $db->data['fow7'];
		$f8 = $db->data['fow8'];
		$f9 = $db->data['fow9'];
		$f10 = $db->data['fow10'];
	}	
   	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
   	echo "<tr>\n";
   	echo "  <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter FoW Details - $bat2nd</td>\n";
   	echo "</tr>\n";
   	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

	echo " <tr>\n";
	
	echo "  <td width=\"10%\" align=\"right\"><b>FoW1</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW2</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW3</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW4</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW5</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW6</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW7</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW8</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW9</b></td>\n";
	echo "  <td width=\"10%\" align=\"right\"><b>FoW10</b></td>\n";

	
	echo " </tr>\n";
	echo " <tr>\n";
	
	$value = ($f1 != "777") ? $f1 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowone\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f2 != "777") ? $f2 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowtwo\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f3 != "777") ? $f3 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowthree\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f4 != "777") ? $f4 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfour\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f5 != "777") ? $f5 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfive\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f6 != "777") ? $f6 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowsix\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f7 != "777") ? $f7 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowseven\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f8 != "777") ? $f8 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"foweight\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f9 != "777") ? $f9 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fownine\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	$value = ($f10 != "777") ? $f10 : '';
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowten\" size=\"5\" maxlength=\"7\" value=\"$value\"></td>\n";	
	
	echo " </tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<br>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Bowling Details - $bat1st</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	$pl_data = array();
	$db->Query("

    SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,s.highlights_video,
      p.PlayerID AS BowlerID, p.PlayerLName AS BowlerLName, p.PlayerFName AS BowlerFName, LEFT(p.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_bowling_details s
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    WHERE
      s.game_id = $gid AND s.innings_id = 2
    ORDER BY
      s.bowling_position

    ");

    for ($x=0; $x<$db->rows; $x++) {
		$db->GetRow($x);

		$pid = $db->data['BowlerID'];
		$pln = $db->data['BowlerLName'];
		$pfn = $db->data['BowlerFName'];
		$pin = $db->data['BowlerFInitial'];
		$ov = $db->data['overs'];
		$ma = $db->data['maidens'];
		$ru = $db->data['runs'];
		$wi = $db->data['wickets'];
		$no = $db->data['noballs'];
		$wd = $db->data['wides'];
		$hlv = $db->data['highlights_video'];
		$pl_rec = array ($pid, $ov, $ma, $ru, $wi, $no, $wd, $hlv);
		$pl_data[$x] = $pl_rec;
	
	}
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                1st Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo " <tr>\n";
	echo "  <td width=\"52%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Overs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Maidens</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Runs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wickets</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Noballs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wides</b></td>\n";
	echo " </tr>\n";
	

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"onebowler_id\" id=\"combobox34\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">1st Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 0) {
				if($pl_data[0][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][1] : '';
	echo "  <input type=\"text\" name=\"oneovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][2] : '';
	echo "  <input type=\"text\" name=\"onemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][3] : '';
	echo "  <input type=\"text\" name=\"onebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][4] : '';
	echo "  <input type=\"text\" name=\"onewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][5] : '';
	echo "  <input type=\"text\" name=\"onenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 0 ? $pl_data[0][6] : '';
	echo "  <input type=\"text\" name=\"onewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 0 ? $pl_data[0][7] : '';
	echo "  <input type=\"text\" name=\"onehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"twobowler_id\" id=\"combobox35\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">2nd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 1) {
				if($pl_data[1][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][1] : '';
	
	echo "  <input type=\"text\" name=\"twoovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][2] : '';
	
	echo "  <input type=\"text\" name=\"twomaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][3] : '';

	echo "  <input type=\"text\" name=\"twobowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][4] : '';

	echo "  <input type=\"text\" name=\"twowickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][5] : '';

	echo "  <input type=\"text\" name=\"twonoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 1 ? $pl_data[1][6] : '';

	echo "  <input type=\"text\" name=\"twowides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 1 ? $pl_data[1][7] : '';
	echo "  <input type=\"text\" name=\"twohlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"threebowler_id\" id=\"combobox36\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">3rd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 2) {
				if($pl_data[2][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][1] : '';

	echo "  <input type=\"text\" name=\"threeovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][2] : '';

	echo "  <input type=\"text\" name=\"threemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][3] : '';

	echo "  <input type=\"text\" name=\"threebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][4] : '';

	echo "  <input type=\"text\" name=\"threewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][5] : '';

	echo "  <input type=\"text\" name=\"threenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 2 ? $pl_data[2][6] : '';

	echo "  <input type=\"text\" name=\"threewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 2 ? $pl_data[2][7] : '';
	echo "  <input type=\"text\" name=\"threehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fourbowler_id\" id=\"combobox37\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">4th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 3) {
				if($pl_data[3][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][1] : '';

	echo "  <input type=\"text\" name=\"fourovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][2] : '';

	echo "  <input type=\"text\" name=\"fourmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][3] : '';

	echo "  <input type=\"text\" name=\"fourbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][4] : '';

	echo "  <input type=\"text\" name=\"fourwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][5] : '';

	echo "  <input type=\"text\" name=\"fournoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 3 ? $pl_data[3][6] : '';

	echo "  <input type=\"text\" name=\"fourwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 3 ? $pl_data[3][7] : '';
	echo "  <input type=\"text\" name=\"fourhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fivebowler_id\" id=\"combobox38\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">5th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 4) {
				if($pl_data[4][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][1] : '';

	echo "  <input type=\"text\" name=\"fiveovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][2] : '';

	echo "  <input type=\"text\" name=\"fivemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][3] : '';

	echo "  <input type=\"text\" name=\"fivebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][4] : '';

	echo "  <input type=\"text\" name=\"fivewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][5] : '';

	echo "  <input type=\"text\" name=\"fivenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 4 ? $pl_data[4][6] : '';

	echo "  <input type=\"text\" name=\"fivewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 4 ? $pl_data[4][7] : '';
	echo "  <input type=\"text\" name=\"fivehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sixbowler_id\" id=\"combobox39\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">6th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 5) {
				if($pl_data[5][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][1] : '';

	echo "  <input type=\"text\" name=\"sixovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][2] : '';

	echo "  <input type=\"text\" name=\"sixmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][3] : '';

	echo "  <input type=\"text\" name=\"sixbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][4] : '';

	echo "  <input type=\"text\" name=\"sixwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][5] : '';

	echo "  <input type=\"text\" name=\"sixnoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 5 ? $pl_data[5][6] : '';

	echo "  <input type=\"text\" name=\"sixwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 5 ? $pl_data[5][7] : '';
	echo "  <input type=\"text\" name=\"sixhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sevenbowler_id\" id=\"combobox40\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">7th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 6) {
				if($pl_data[6][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][1] : '';

	echo "  <input type=\"text\" name=\"sevenovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][2] : '';

	echo "  <input type=\"text\" name=\"sevenmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][3] : '';

	echo "  <input type=\"text\" name=\"sevenbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][4] : '';

	echo "  <input type=\"text\" name=\"sevenwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][5] : '';

	echo "  <input type=\"text\" name=\"sevennoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 6 ? $pl_data[6][6] : '';

	echo "  <input type=\"text\" name=\"sevenwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 6 ? $pl_data[6][7] : '';
	echo "  <input type=\"text\" name=\"sevenhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"eightbowler_id\" id=\"combobox41\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">8th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][1] : '';

	echo "  <input type=\"text\" name=\"eightovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][2] : '';

	echo "  <input type=\"text\" name=\"eightmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][3] : '';

	echo "  <input type=\"text\" name=\"eightbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][4] : '';

	echo "  <input type=\"text\" name=\"eightwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][5] : '';

	echo "  <input type=\"text\" name=\"eightnoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 7 ? $pl_data[7][6] : '';

	echo "  <input type=\"text\" name=\"eightwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > [7] ? $pl_data[7][7] : '';
	echo "  <input type=\"text\" name=\"eighthlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"ninebowler_id\" id=\"combobox42\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">9th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 8) {
				if($pl_data[8][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][1] : '';

	echo "  <input type=\"text\" name=\"nineovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][2] : '';

	echo "  <input type=\"text\" name=\"ninemaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][3] : '';

	echo "  <input type=\"text\" name=\"ninebowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][4] : '';

	echo "  <input type=\"text\" name=\"ninewickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][5] : '';

	echo "  <input type=\"text\" name=\"ninenoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 8 ? $pl_data[8][6] : '';

	echo "  <input type=\"text\" name=\"ninewides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 8 ? $pl_data[8][7] : '';
	echo "  <input type=\"text\" name=\"ninehlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Bowler Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"tenbowler_id\" id=\"combobox43\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">10th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 9) {
				if($pl_data[9][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][1] : '';

	echo "  <input type=\"text\" name=\"tenovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][2] : '';

	echo "  <input type=\"text\" name=\"tenmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][3] : '';

	echo "  <input type=\"text\" name=\"tenbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][4] : '';

	echo "  <input type=\"text\" name=\"tenwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][5] : '';

	echo "  <input type=\"text\" name=\"tennoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 9 ? $pl_data[9][6] : '';

	echo "  <input type=\"text\" name=\"tenwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 9 ? $pl_data[9][7] : '';
	echo "  <input type=\"text\" name=\"tenhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"elevenbowler_id\" id=\"combobox44\" style=\"width:170px;\">\n";
	echo "	<option value=\"\">11th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT p.*,t.* FROM players p, teams t WHERE t.TeamID = $bat1stid AND (p.PlayerTeam = t.TeamID OR p.PlayerTeam2 = t.TeamID) AND p.isactive = 0 ORDER BY p.PlayerFName,p.PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$selected = "";
			if(count($pl_data) > 10) {
				if($pl_data[10][0] == $db->data['PlayerID']) {
					$selected = "selected";
				}
			}
			echo "<option $selected value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] ."</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][1] : '';

	echo "  <input type=\"text\" name=\"elevenovers\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][2] : '';

	echo "  <input type=\"text\" name=\"elevenmaidens\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][3] : '';

	echo "  <input type=\"text\" name=\"elevenbowruns\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][4] : '';

	echo "  <input type=\"text\" name=\"elevenwickets\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][5] : '';

	echo "  <input type=\"text\" name=\"elevennoballs\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	$value = count($pl_data) > 10 ? $pl_data[10][6] : '';

	echo "  <input type=\"text\" name=\"elevenwides\" size=\"5\" maxlength=\"7\" value=\"$value\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	echo "<tr>\n";
	echo "  <td colspan=\"7\">";
	echo "  <table width=\"100%\"><tr>";
	echo "  <td align=\"right\"><b>Highlight Video link:</b>";
	$value = count($pl_data) > 10 ? $pl_data[10][7] : '';
	echo "  <input type=\"text\" name=\"elevenhlvb\" size=\"30\" value=\"$value\">\n";
	echo "  </td>\n";
	echo "  </tr></table>";
	echo "  </td>\n";
	echo " </tr>\n";	
	
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<input name=\"submit\" type=\"submit\" value=\"Previous\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Save and Previous\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Save and Next\">&nbsp;<input name=\"submit\" type=\"submit\" value=\"Next\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	
	echo "</form>\n";

	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
}
function 

update_scorecard_step3($db,$submit,$game_id,$season,$innings_id,$oneplayer_id,$onehow_out,$oneassist,$oneassist2,$onebowler,$oneruns,$oneballs,$onefours,$onesixes,$onehwv,$onehlv,$twoplayer_id,
$twohow_out,$twoassist,$twoassist2,$twobowler,$tworuns,$twoballs,$twofours,$twosixes,$twohwv,$twohlv,$threeplayer_id,$threehow_out,$threeassist,$threeassist2,$threebowler,$threeruns,$threeballs,
$threefours,$threesixes,$threehwv,$threehlv,$fourplayer_id,$fourhow_out,$fourassist,$fourassist2,$fourbowler,$fourruns,$fourballs,$fourfours,$foursixes,$fourhwv,$fourhlv,$fiveplayer_id,$fivehow_out,$fiveassist,$fiveassist2,
$fivebowler,$fiveruns,$fiveballs,$fivefours,$fivesixes,$fivehwv,$fivehlv,$sixplayer_id,$sixhow_out,$sixassist,$sixassist2,$sixbowler,$sixruns,$sixballs,$sixfours,$sixsixes,$sixhwv,$sixhlv,$sevenplayer_id,$sevenhow_out,$sevenassist,$sevenassist2,$sevenbowler,$sevenruns,
$sevenballs,$sevenfours,$sevensixes,$sevenhwv,$sevenhlv,$eightplayer_id,$eighthow_out,$eightassist,$eightassist2,$eightbowler,$eightruns,$eightballs,$eightfours,$eightsixes,$eighthwv,$eighthlv,$nineplayer_id,$ninehow_out,$nineassist,$nineassist2,$ninebowler,$nineruns,
$nineballs,$ninefours,$ninesixes,$ninehwv,$ninehlv,$tenplayer_id,$tenhow_out,$tenassist,$tenassist2,$tenbowler,$tenruns,$tenballs,$tenfours,$tensixes,$tenhwv,$tenhlv,$elevenplayer_id,$elevenhow_out,$elevenassist,$elevenassist2,$elevenbowler,$elevenruns,$elevenballs,$elevenfours,
$elevensixes,$elevenhwv,$elevenhlv,$totwickets,$totovers,$tottotal,$totdltotal,$extlegbyes,$extbyes,$extwides,$extnoballs,$exttotal,$fowone,$fowtwo,$fowthree,$fowfour,$fowfive,$fowsix,$fowseven,
$foweight,$fownine,$fowten,$onebowler_id,$oneovers,$onemaidens,$onebowruns,$onewickets,$onenoballs,$onewides,$onehlvb,$twobowler_id,$twoovers,$twomaidens,$twobowruns,
$twowickets,$twonoballs,$twowides,$twohlvb,$threebowler_id,$threeovers,$threemaidens,$threebowruns,$threewickets,$threenoballs,$threewides,$threehlvb,$fourbowler_id,$fourovers,
$fourmaidens,$fourbowruns,$fourwickets,$fournoballs,$fourwides,$fourhlvb,$fivebowler_id,$fiveovers,$fivemaidens,$fivebowruns,$fivewickets,$fivenoballs,$fivewides,$fivehlvb,$sixbowler_id,$sixovers,$sixmaidens,
$sixbowruns,$sixwickets,$sixnoballs,$sixwides,$sixhlvb,$sevenbowler_id,$sevenovers,$sevenmaidens,$sevenbowruns,$sevenwickets,$sevennoballs,$sevenwides,$sevenhlvb,$eightbowler_id,$eightovers,$eightmaidens,
$eightbowruns,$eightwickets,$eightnoballs,$eightwides,$eighthlvb,$ninebowler_id,$nineovers,$ninemaidens,$ninebowruns,$ninewickets,$ninenoballs,$ninewides,$ninehlvb,$tenbowler_id,$tenovers,$tenmaidens,$tenbowruns,$tenwickets,
$tennoballs,$tenwides,$tenhlvb,$elevenbowler_id,$elevenovers,$elevenmaidens,$elevenbowruns,$elevenwickets,$elevennoballs,$elevenwides,$elevenhlvb,$onebatpos,$twobatpos,$threebatpos,$fourbatpos,$fivebatpos,$sixbatpos,$sevenbatpos,
$eightbatpos,$ninebatpos,$tenbatpos,$elevenbatpos,$onebowpos,$twobowpos,$threebowpos,$fourbowpos,$fivebowpos,$sixbowpos,$sevenbowpos,$eightbowpos,$ninebowpos,
$tenbowpos,$elevenbowpos,$oneteam,$twoteam,$threeteam,$fourteam,$fiveteam,$sixteam,$seventeam,$eightteam,$nineteam,$tenteam,$eleventeam,$oneopponent,$twoopponent,$threeopponent,$fouropponent,
$fiveopponent,$sixopponent,$sevenopponent,$eightopponent,$nineopponent,$tenopponent,$elevenopponent,$bowloneteam,$bowltwoteam,$bowlthreeteam,$bowlfourteam,$bowlfiveteam,$bowlsixteam,$bowlseventeam,
$bowleightteam,$bowlnineteam,$bowltenteam,$bowleleventeam,$bowloneopponent,$bowltwoopponent,$bowlthreeopponent,$bowlfouropponent,$bowlfiveopponent,$bowlsixopponent,$bowlsevenopponent,
$bowleightopponent,$bowlnineopponent,$bowltenopponent,$bowlelevenopponent)
{
	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	// make sure info is present and correct

	//if ($totwickets == "" || $totovers || $tottotal) {
	//	echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
	//	echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
	//	return;
	//}

	// setup variables

	if($submit == "Save and Previous" || $submit == "Save and Next") {
		$game_id = addslashes(trim($game_id));
		$season = addslashes(trim($season));
		$innings_id = addslashes(trim($innings_id));
		
		$onepl = addslashes(trim($oneplayer_id));
		$oneho = addslashes(trim($onehow_out));
		$oneas = addslashes(trim($oneassist));
		$oneas2 = addslashes(trim($oneassist2));
		$onebo = addslashes(trim($onebowler));
		$oneru = addslashes(trim($oneruns));
		$oneba = addslashes(trim($oneballs));
		$onefo = addslashes(trim($onefours));
		$onesi = addslashes(trim($onesixes));
		$onehv = addslashes(trim($onehwv));
		$onehl = addslashes(trim($onehlv));
		if($onehow_out != "2" && $onehow_out != "8") { $oneno = "0"; } else { $oneno = "1"; }

		$twopl = addslashes(trim($twoplayer_id));
		$twoho = addslashes(trim($twohow_out));
		$twoas = addslashes(trim($twoassist));
		$twoas2 = addslashes(trim($twoassist2));
		$twobo = addslashes(trim($twobowler));
		$tworu = addslashes(trim($tworuns));
		$twoba = addslashes(trim($twoballs));
		$twofo = addslashes(trim($twofours));
		$twosi = addslashes(trim($twosixes));
		$twohv = addslashes(trim($twohwv));
		$twohl = addslashes(trim($twohlv));
		if($twohow_out != "2" && $twohow_out != "8") { $twono = "0"; } else { $twono = "1"; }

		$threepl = addslashes(trim($threeplayer_id));
		$threeho = addslashes(trim($threehow_out));
		$threeas = addslashes(trim($threeassist));
		$threeas2 = addslashes(trim($threeassist2));
		$threebo = addslashes(trim($threebowler));
		$threeru = addslashes(trim($threeruns));
		$threeba = addslashes(trim($threeballs));
		$threefo = addslashes(trim($threefours));
		$threesi = addslashes(trim($threesixes));
		$threehv = addslashes(trim($threehwv));
		$threehl = addslashes(trim($threehlv));
		if($threehow_out != "2" && $threehow_out != "8") { $threeno = "0"; } else { $threeno = "1"; }

		$fourpl = addslashes(trim($fourplayer_id));
		$fourho = addslashes(trim($fourhow_out));
		$fouras = addslashes(trim($fourassist));
		$fouras2 = addslashes(trim($fourassist2));
		$fourbo = addslashes(trim($fourbowler));
		$fourru = addslashes(trim($fourruns));
		$fourba = addslashes(trim($fourballs));
		$fourfo = addslashes(trim($fourfours));
		$foursi = addslashes(trim($foursixes));
		$fourhv = addslashes(trim($fourhwv));
		$fourhl = addslashes(trim($fourhlv));
		if($fourhow_out != "2" && $fourhow_out != "8") { $fourno = "0"; } else { $fourno = "1"; }

		$fivepl = addslashes(trim($fiveplayer_id));
		$fiveho = addslashes(trim($fivehow_out));
		$fiveas = addslashes(trim($fiveassist));
		$fiveas2 = addslashes(trim($fiveassist2));
		$fivebo = addslashes(trim($fivebowler));
		$fiveru = addslashes(trim($fiveruns));
		$fiveba = addslashes(trim($fiveballs));
		$fivefo = addslashes(trim($fivefours));
		$fivesi = addslashes(trim($fivesixes));
		$fivehv = addslashes(trim($fivehwv));
		$fivehl = addslashes(trim($fivehlv));
		if($fivehow_out != "2" && $fivehow_out != "8") { $fiveno = "0"; } else { $fiveno = "1"; }

		$sixpl = addslashes(trim($sixplayer_id));
		$sixho = addslashes(trim($sixhow_out));
		$sixas = addslashes(trim($sixassist));
		$sixas2 = addslashes(trim($sixassist2));
		$sixbo = addslashes(trim($sixbowler));
		$sixru = addslashes(trim($sixruns));
		$sixba = addslashes(trim($sixballs));
		$sixfo = addslashes(trim($sixfours));
		$sixsi = addslashes(trim($sixsixes));
		$sixhv = addslashes(trim($sixhwv));
		$sixhl = addslashes(trim($sixhlv));
		if($sixhow_out != "2" && $sixhow_out != "8") { $sixno = "0"; } else { $sixno = "1"; }

		$sevenpl = addslashes(trim($sevenplayer_id));
		$sevenho = addslashes(trim($sevenhow_out));
		$sevenas = addslashes(trim($sevenassist));
		$sevenas2 = addslashes(trim($sevenassist2));
		$sevenbo = addslashes(trim($sevenbowler));
		$sevenru = addslashes(trim($sevenruns));
		$sevenba = addslashes(trim($sevenballs));
		$sevenfo = addslashes(trim($sevenfours));
		$sevensi = addslashes(trim($sevensixes));
		$sevenhv = addslashes(trim($sevenhwv));
		$sevenhl = addslashes(trim($sevenhlv));
		if($sevenhow_out != "2" && $sevenhow_out != "8") { $sevenno = "0"; } else { $sevenno = "1"; }

		$eightpl = addslashes(trim($eightplayer_id));
		$eightho = addslashes(trim($eighthow_out));
		$eightas = addslashes(trim($eightassist));
		$eightas2 = addslashes(trim($eightassist2));
		$eightbo = addslashes(trim($eightbowler));
		$eightru = addslashes(trim($eightruns));
		$eightba = addslashes(trim($eightballs));
		$eightfo = addslashes(trim($eightfours));
		$eightsi = addslashes(trim($eightsixes));
		$eighthv = addslashes(trim($eighthwv));
		$eighthl = addslashes(trim($eighthlv));
		if($eighthow_out != "2" && $eighthow_out != "8") { $eightno = "0"; } else { $eightno = "1"; }

		$ninepl = addslashes(trim($nineplayer_id));
		$nineho = addslashes(trim($ninehow_out));
		$nineas = addslashes(trim($nineassist));
		$nineas2 = addslashes(trim($nineassist2));
		$ninebo = addslashes(trim($ninebowler));
		$nineru = addslashes(trim($nineruns));
		$nineba = addslashes(trim($nineballs));
		$ninefo = addslashes(trim($ninefours));
		$ninesi = addslashes(trim($ninesixes));
		$ninehv = addslashes(trim($ninehwv));
		$ninehl = addslashes(trim($ninehlv));
		if($ninehow_out != "2" && $ninehow_out != "8") { $nineno = "0"; } else { $nineno = "1"; }

		$tenpl = addslashes(trim($tenplayer_id));
		$tenho = addslashes(trim($tenhow_out));
		$tenas = addslashes(trim($tenassist));
		$tenas2 = addslashes(trim($tenassist2));
		$tenbo = addslashes(trim($tenbowler));
		$tenru = addslashes(trim($tenruns));
		$tenba = addslashes(trim($tenballs));
		$tenfo = addslashes(trim($tenfours));
		$tensi = addslashes(trim($tensixes));
		$tenhv = addslashes(trim($tenhwv));
		$tenhl = addslashes(trim($tenhlv));
		if($tenhow_out != "2" && $tenhow_out != "8") { $tenno = "0"; } else { $tenno = "1"; }

		$elevenpl = addslashes(trim($elevenplayer_id));
		$elevenho = addslashes(trim($elevenhow_out));
		$elevenas = addslashes(trim($elevenassist));
		$elevenas2 = addslashes(trim($elevenassist2));
		$elevenbo = addslashes(trim($elevenbowler));
		$elevenru = addslashes(trim($elevenruns));
		$elevenba = addslashes(trim($elevenballs));
		$elevenfo = addslashes(trim($elevenfours));
		$elevensi = addslashes(trim($elevensixes));
		$elevenhv = addslashes(trim($elevenhwv));
		$elevenhl = addslashes(trim($elevenhlv));
		if($elevenhow_out != "2" && $elevenhow_out != "8") { $elevenno = "0"; } else { $elevenno = "1"; }

		$totw = addslashes(trim($totwickets));
		$toto = addslashes(trim($totovers));
		$tott = addslashes(trim($tottotal));
		$totdt = addslashes(trim($totdltotal));
		
		$extl = addslashes(trim($extlegbyes));
		$extb = addslashes(trim($extbyes));
		$extw = addslashes(trim($extwides));
		$extn = addslashes(trim($extnoballs));
		$extt = addslashes(trim($exttotal));
		
		// Need to set the FoW to 777 if it is NULL
		
		if($fowone !="") {
		  $f1 = addslashes(trim($fowone));
		} else {
		  $f1 = "777";
		}
		
		if($fowtwo !="") {
		  $f2 = addslashes(trim($fowtwo));
		} else {
		  $f2 = "777";
		}
		if($fowthree !="") {
		  $f3 = addslashes(trim($fowthree));
		} else {
		  $f3 = "777";
		}
		if($fowfour !="") {
		  $f4 = addslashes(trim($fowfour));
		} else {
		  $f4 = "777";
		}
		if($fowfive !="") {
		  $f5 = addslashes(trim($fowfive));
		} else {
		  $f5 = "777";
		}
		if($fowsix !="") {
		  $f6 = addslashes(trim($fowsix));
		} else {
		  $f6 = "777";
		}
		if($fowseven !="") {
		  $f7 = addslashes(trim($fowseven));
		} else {
		  $f7 = "777";
		}
		if($foweight !="") {
		  $f8 = addslashes(trim($foweight));
		} else {
		  $f8 = "777";
		}
		if($fownine !="") {
		  $f9 = addslashes(trim($fownine));
		} else {
		  $f9 = "777";
		}
		if($fowten !="") {
		  $f10 = addslashes(trim($fowten));
		} else {
		  $f10 = "777";
		}
		
		$onebow = addslashes(trim($onebowler_id));
		$oneove = addslashes(trim($oneovers));
		$onemai = addslashes(trim($onemaidens));
		$onebru = addslashes(trim($onebowruns));
		$onewic = addslashes(trim($onewickets));
		$onenob = addslashes(trim($onenoballs));
		$onewid = addslashes(trim($onewides));
		$onehlb = addslashes(trim($onehlvb));

		$twobow = addslashes(trim($twobowler_id));
		$twoove = addslashes(trim($twoovers));
		$twomai = addslashes(trim($twomaidens));
		$twobru = addslashes(trim($twobowruns));
		$twowic = addslashes(trim($twowickets));
		$twonob = addslashes(trim($twonoballs));
		$twowid = addslashes(trim($twowides));
		$twohlb = addslashes(trim($twohlvb));

		$threebow = addslashes(trim($threebowler_id));
		$threeove = addslashes(trim($threeovers));
		$threemai = addslashes(trim($threemaidens));
		$threebru = addslashes(trim($threebowruns));
		$threewic = addslashes(trim($threewickets));
		$threenob = addslashes(trim($threenoballs));
		$threewid = addslashes(trim($threewides));
		$threehlb = addslashes(trim($threehlvb));

		$fourbow = addslashes(trim($fourbowler_id));
		$fourove = addslashes(trim($fourovers));
		$fourmai = addslashes(trim($fourmaidens));
		$fourbru = addslashes(trim($fourbowruns));
		$fourwic = addslashes(trim($fourwickets));
		$fournob = addslashes(trim($fournoballs));
		$fourwid = addslashes(trim($fourwides));
		$fourhlb = addslashes(trim($fourhlvb));

		$fivebow = addslashes(trim($fivebowler_id));
		$fiveove = addslashes(trim($fiveovers));
		$fivemai = addslashes(trim($fivemaidens));
		$fivebru = addslashes(trim($fivebowruns));
		$fivewic = addslashes(trim($fivewickets));
		$fivenob = addslashes(trim($fivenoballs));
		$fivewid = addslashes(trim($fivewides));
		$fivehlb = addslashes(trim($fivehlvb));

		$sixbow = addslashes(trim($sixbowler_id));
		$sixove = addslashes(trim($sixovers));
		$sixmai = addslashes(trim($sixmaidens));
		$sixbru = addslashes(trim($sixbowruns));
		$sixwic = addslashes(trim($sixwickets));
		$sixnob = addslashes(trim($sixnoballs));
		$sixwid = addslashes(trim($sixwides));
		$sixhlb = addslashes(trim($sixhlvb));

		$sevenbow = addslashes(trim($sevenbowler_id));
		$sevenove = addslashes(trim($sevenovers));
		$sevenmai = addslashes(trim($sevenmaidens));
		$sevenbru = addslashes(trim($sevenbowruns));
		$sevenwic = addslashes(trim($sevenwickets));
		$sevennob = addslashes(trim($sevennoballs));
		$sevenwid = addslashes(trim($sevenwides));
		$sevenhlb = addslashes(trim($sevenhlvb));

		$eightbow = addslashes(trim($eightbowler_id));
		$eightove = addslashes(trim($eightovers));
		$eightmai = addslashes(trim($eightmaidens));
		$eightbru = addslashes(trim($eightbowruns));
		$eightwic = addslashes(trim($eightwickets));
		$eightnob = addslashes(trim($eightnoballs));
		$eightwid = addslashes(trim($eightwides));
		$eighthlb = addslashes(trim($eighthlvb));

		$ninebow = addslashes(trim($ninebowler_id));
		$nineove = addslashes(trim($nineovers));
		$ninemai = addslashes(trim($ninemaidens));
		$ninebru = addslashes(trim($ninebowruns));
		$ninewic = addslashes(trim($ninewickets));
		$ninenob = addslashes(trim($ninenoballs));
		$ninewid = addslashes(trim($ninewides));
		$ninehlb = addslashes(trim($ninehlvb));

		$tenbow = addslashes(trim($tenbowler_id));
		$tenove = addslashes(trim($tenovers));
		$tenmai = addslashes(trim($tenmaidens));
		$tenbru = addslashes(trim($tenbowruns));
		$tenwic = addslashes(trim($tenwickets));
		$tennob = addslashes(trim($tennoballs));
		$tenwid = addslashes(trim($tenwides));
		$tenhlb = addslashes(trim($tenhlvb));

		$elevenbow = addslashes(trim($elevenbowler_id));
		$elevenove = addslashes(trim($elevenovers));
		$elevenmai = addslashes(trim($elevenmaidens));
		$elevenbru = addslashes(trim($elevenbowruns));
		$elevenwic = addslashes(trim($elevenwickets));
		$elevennob = addslashes(trim($elevennoballs));
		$elevenwid = addslashes(trim($elevenwides));
		$elevenhlb = addslashes(trim($elevenhlvb));

		$onebap = addslashes(trim($onebatpos));
		$twobap = addslashes(trim($twobatpos));
		$threebap = addslashes(trim($threebatpos));
		$fourbap = addslashes(trim($fourbatpos));
		$fivebap = addslashes(trim($fivebatpos));
		$sixbap = addslashes(trim($sixbatpos));
		$sevenbap = addslashes(trim($sevenbatpos));
		$eightbap = addslashes(trim($eightbatpos));
		$ninebap = addslashes(trim($ninebatpos));
		$tenbap = addslashes(trim($tenbatpos));
		$elevenbap = addslashes(trim($elevenbatpos));
		$onebop = addslashes(trim($onebowpos));
		$twobop = addslashes(trim($twobowpos));
		$threebop = addslashes(trim($threebowpos));
		$fourbop = addslashes(trim($fourbowpos));
		$fivebop = addslashes(trim($fivebowpos));
		$sixbop = addslashes(trim($sixbowpos));
		$sevenbop = addslashes(trim($sevenbowpos));
		$eightbop = addslashes(trim($eightbowpos));
		$ninebop = addslashes(trim($ninebowpos));
		$tenbop = addslashes(trim($tenbowpos));
		$elevenbop = addslashes(trim($elevenbowpos));
		
		$onetm = addslashes(trim($oneteam));
		$twotm = addslashes(trim($twoteam));
		$threetm = addslashes(trim($threeteam));
		$fourtm = addslashes(trim($fourteam));
		$fivetm = addslashes(trim($fiveteam));
		$sixtm = addslashes(trim($sixteam));
		$seventm = addslashes(trim($seventeam));
		$eighttm = addslashes(trim($eightteam));
		$ninetm = addslashes(trim($nineteam));
		$tentm = addslashes(trim($tenteam));
		$eleventm = addslashes(trim($eleventeam));
		$oneopp = addslashes(trim($oneopponent));
		$twoopp = addslashes(trim($twoopponent));
		$threeopp = addslashes(trim($threeopponent));
		$fouropp = addslashes(trim($fouropponent));
		$fiveopp = addslashes(trim($fiveopponent));
		$sixopp = addslashes(trim($sixopponent));
		$sevenopp = addslashes(trim($sevenopponent));
		$eightopp = addslashes(trim($eightopponent));
		$nineopp = addslashes(trim($nineopponent));
		$tenopp = addslashes(trim($tenopponent));
		$elevenopp = addslashes(trim($elevenopponent));

		$bowlonetm = addslashes(trim($bowloneteam));
		$bowltwotm = addslashes(trim($bowltwoteam));
		$bowlthreetm = addslashes(trim($bowlthreeteam));
		$bowlfourtm = addslashes(trim($bowlfourteam));
		$bowlfivetm = addslashes(trim($bowlfiveteam));
		$bowlsixtm = addslashes(trim($bowlsixteam));
		$bowlseventm = addslashes(trim($bowlseventeam));
		$bowleighttm = addslashes(trim($bowleightteam));
		$bowlninetm = addslashes(trim($bowlnineteam));
		$bowltentm = addslashes(trim($bowltenteam));
		$bowleleventm = addslashes(trim($bowleleventeam));
		$bowloneopp = addslashes(trim($bowloneopponent));
		$bowltwoopp = addslashes(trim($bowltwoopponent));
		$bowlthreeopp = addslashes(trim($bowlthreeopponent));
		$bowlfouropp = addslashes(trim($bowlfouropponent));
		$bowlfiveopp = addslashes(trim($bowlfiveopponent));
		$bowlsixopp = addslashes(trim($bowlsixopponent));
		$bowlsevenopp = addslashes(trim($bowlsevenopponent));
		$bowleightopp = addslashes(trim($bowleightopponent));
		$bowlnineopp = addslashes(trim($bowlnineopponent));
		$bowltenopp = addslashes(trim($bowltenopponent));
		$bowlelevenopp = addslashes(trim($bowlelevenopponent));
		

		$db->Delete("DELETE FROM scorecard_extras_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");

		$db->Insert("INSERT INTO scorecard_extras_details (game_id,innings_id,legbyes,byes,wides,noballs,total) VALUES ('$game_id','$innings_id','$extl','$extb','$extw','$extn','$extt')");
		$db->Delete("DELETE FROM scorecard_total_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		$db->Insert("INSERT INTO scorecard_total_details (game_id,innings_id,team,wickets,total,overs,dl_total) VALUES ('$game_id','$innings_id','$oneteam','$totw','$tott','$toto','$totdt')");
		$db->Delete("DELETE FROM scorecard_fow_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		$db->Insert("INSERT INTO scorecard_fow_details (game_id,innings_id,fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10) VALUES 
	('$game_id','$innings_id','$f1','$f2','$f3','$f4','$f5','$f6','$f7','$f8','$f9','$f10')");	


		// check to see if there is an entry of batter

		$db->Delete("DELETE FROM scorecard_batting_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		if ($onepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details 

	(game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$onepl','$onebap','$oneho','$oneru','$oneas','$oneas2','$onebo','$oneba','$onefo','$onesi','$onehv','$onehl','$oneno','$onetm','$oneopp')");
		if ($twopl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$twopl','$twobap','$twoho','$tworu','$twoas','$twoas2','$twobo','$twoba','$twofo','$twosi','$twohv','$twohl','$twono','$twotm','$twoopp')");
		if ($threepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$threepl','$threebap','$threeho','$threeru','$threeas','$threeas2','$threebo','$threeba','$threefo','$threesi','$threehv','$threehl','$threeno','$threetm','$threeopp')");
		if ($fourpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fourpl','$fourbap','$fourho','$fourru','$fouras','$fouras2','$fourbo','$fourba','$fourfo','$foursi','$fourhv','$fourhl','$fourno','$fourtm','$fouropp')");
		if ($fivepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fivepl','$fivebap','$fiveho','$fiveru','$fiveas','$fiveas2','$fivebo','$fiveba','$fivefo','$fivesi','$fivehv','$fivehl','$fiveno','$fivetm','$fiveopp')");
		if ($sixpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sixpl','$sixbap','$sixho','$sixru','$sixas','$sixas2','$sixbo','$sixba','$sixfo','$sixsi','$sixhv','$sixhl','$sixno','$sixtm','$sixopp')");
		if ($sevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sevenpl','$sevenbap','$sevenho','$sevenru','$sevenas','$sevenas2','$sevenbo','$sevenba','$sevenfo','$sevensi','$sevenhv','$sevenhl','$sevenno','$seventm','$sevenopp')");
		if ($eightpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$eightpl','$eightbap','$eightho','$eightru','$eightas','$eightas2','$eightbo','$eightba','$eightfo','$eightsi','$eighthv','$eighthl','$eightno','$eighttm','$eightopp')");
		if ($ninepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$ninepl','$ninebap','$nineho','$nineru','$nineas','$nineas2','$ninebo','$nineba','$ninefo','$ninesi','$ninehv','$ninehl','$nineno','$ninetm','$nineopp')");
		if ($tenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$tenpl','$tenbap','$tenho','$tenru','$tenas','$tenas2','$tenbo','$tenba','$tenfo','$tensi','$tenhv','$tenhl','$tenno','$tentm','$tenopp')");
		if ($elevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,assist2,bowler,balls,fours,sixes,howout_video,highlights_video,notout,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$elevenpl','$elevenbap','$elevenho','$elevenru','$elevenas','$elevenas2','$elevenbo','$elevenba','$elevenfo','$elevensi','$elevenhv','$elevenhl','$elevenno','$eleventm','$elevenopp')");

		// check to see if there is an entry of bowler

		$db->Delete("DELETE FROM scorecard_bowling_details WHERE  game_id = '$game_id' AND innings_id = '$innings_id'");
		if ($onebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$onebow','$onebop','$oneove','$onemai','$onebru','$onewic','$onenob','$onewid','$onehlb','$bowlonetm','$bowloneopp')");
		if ($twobow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$twobow','$twobop','$twoove','$twomai','$twobru','$twowic','$twonob','$twowid','$twohlb','$bowltwotm','$bowltwoopp')");
		if ($threebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$threebow','$threebop','$threeove','$threemai','$threebru','$threewic','$threenob','$threewid','$threehlb','$bowlthreetm','$bowlthreeopp')");
		if ($fourbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fourbow','$fourbop','$fourove','$fourmai','$fourbru','$fourwic','$fournob','$fourwid','$fourhlb','$bowlfourtm','$bowlfouropp')");
		if ($fivebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$fivebow','$fivebop','$fiveove','$fivemai','$fivebru','$fivewic','$fivenob','$fivewid','$fivehlb','$bowlfivetm','$bowlfiveopp')");
		if ($sixbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sixbow','$sixbop','$sixove','$sixmai','$sixbru','$sixwic','$sixnob','$sixwid','$sixhlb','$bowlsixtm','$bowlsixopp')");
		if ($sevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$sevenbow','$sevenbop','$sevenove','$sevenmai','$sevenbru','$sevenwic','$sevennob','$sevenwid','$sevenhlb','$bowlseventm','$bowlsevenopp')");
		if ($eightbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$eightbow','$eightbop','$eightove','$eightmai','$eightbru','$eightwic','$eightnob','$eightwid','$eighthlb','$bowleighttm','$bowleightopp')");
		if ($ninebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$ninebow','$ninebop','$nineove','$ninemai','$ninebru','$ninewic','$ninenob','$ninewid','$ninehlb','$bowlninetm','$bowlnineopp')");
		if ($tenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$tenbow','$tenbop','$tenove','$tenmai','$tenbru','$tenwic','$tennob','$tenwid','$tenhlb','$bowltentm','$bowltenopp')");
		if ($elevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,highlights_video,team,opponent) VALUES 
	('$game_id','$season','$innings_id','$elevenbow','$elevenbop','$elevenove','$elevenmai','$elevenbru','$elevenwic','$elevennob','$elevenwid','$elevenhlb','$bowleleventm','$bowlelevenopp')");

		if($submit == "Save and Previous") {
			header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
			ob_end_flush();
		} else {
			header("Location: main.php?SID=$SID&action=$action&do=update6&game_id=$game_id");
			ob_end_flush();
		}
	} else if($submit == "Previous") {
		header("Location: main.php?SID=$SID&action=$action&do=update2&game_id=$game_id");
		ob_end_flush();
	} else {
		header("Location: main.php?SID=$SID&action=$action&do=update6&game_id=$game_id");
		ob_end_flush();
	}

}

echo "<p class=\"16px\"><b>Scorecard Administration</b></p>\n";


if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
}
else {
	$do = '';
}

switch($do) {
case "byseason":
    show_main_menu_season($db,$_GET['season'],$_GET['sename']);
    break;
case "sadd":
	add_scorecard_step1($db);
	break;
case "insert":
	insert_scorecard_step1($db, $_POST['league_id'], $_POST['season'], $_POST['week'], $_POST['awayteam'], $_POST['awayteam_captain'], $_POST['awayteam_vcaptain'], $_POST['awayteam_wk'],$_POST['hometeam'], $_POST['hometeam_captain'], $_POST['hometeam_vcaptain'], $_POST['hometeam_wk'],$_POST['umpires'],$_POST['toss_won_id'],$_POST['result_won_id'],$_POST['batting_first_id'],$_POST['batting_second_id'],$_POST['ground_id'], '',$_POST['game_date'],$_POST['result'], $_POST['result_type'],$_POST['mom'], $_POST['mom2'],$_POST['umpire1'],$_POST['umpire2'],$_POST['maxovers'],$_POST['cricclubs_game_id'],$_POST['report']);
	break;
case "sdel":
	delete_category_check($db,$_GET['game_id']);
	break;
case "dodel":
	do_delete_category($db,$_GET['game_id'],1);
	break;
case "dontdel":
	do_delete_category($db,$_GET['game_id'],0);
	break;
case "sedit":
	edit_scorecard_step1($db, $_GET['game_id']);
	break;
case "update":
	update_scorecard_step1($db, $_POST['submit'], $_POST['game_id'], $_POST['league_id'], $_POST['season'], $_POST['week'], $_POST['awayteam'], $_POST['awayteam_captain'], $_POST['awayteam_vcaptain'], $_POST['awayteam_wk'],$_POST['hometeam'], $_POST['hometeam_captain'], $_POST['hometeam_vcaptain'], $_POST['hometeam_wk'],$_POST['umpires'],$_POST['toss_won_id'],$_POST['result_won_id'],$_POST['batting_first_id'],$_POST['batting_second_id'],$_POST['ground_id'], '',$_POST['game_date'],$_POST['result'],$_POST['result_type'],$_POST['mom'], $_POST['mom2'],$_POST['umpire1'],$_POST['umpire2'],$_POST['maxovers'],$_POST['cricclubs_game_id'],$_POST['report']);
	break;
case "update2":
	edit_scorecard_step2($db, $_GET['game_id']);
	break;
case "update3":
	update_scorecard_step2($db,$_POST['submit'], $_POST['game_id'],$_POST['season'],$_POST['innings_id'],$_POST['oneplayer_id'],$_POST['onehow_out'],$_POST['oneassist'],$_POST['oneassist2'],$_POST['onebowler'],$_POST['oneruns'],$_POST['oneballs'],$_POST['onefours'],$_POST['onesixes'],$_POST['onehwv'],$_POST['onehlv'],$_POST['twoplayer_id'], $_POST['twohow_out'],$_POST['twoassist'],$_POST['twoassist2'],$_POST['twobowler'],$_POST['tworuns'],$_POST['twoballs'],$_POST['twofours'],$_POST['twosixes'],$_POST['twohwv'],$_POST['twohlv'],$_POST['threeplayer_id'],$_POST['threehow_out'],$_POST['threeassist'],$_POST['threeassist2'],$_POST['threebowler'],$_POST['threeruns'],$_POST['threeballs'],$_POST['threefours'],$_POST['threesixes'],$_POST['threehwv'],$_POST['threehlv'],$_POST['fourplayer_id'],$_POST['fourhow_out'],$_POST['fourassist'],$_POST['fourassist2'],$_POST['fourbowler'],$_POST['fourruns'],$_POST['fourballs'],$_POST['fourfours'],$_POST['foursixes'],$_POST['fourhwv'],$_POST['fourhlv'],$_POST['fiveplayer_id'],$_POST['fivehow_out'],$_POST['fiveassist'],$_POST['fiveassist2'],$_POST['fivebowler'],$_POST['fiveruns'],$_POST['fiveballs'],$_POST['fivefours'],$_POST['fivesixes'],$_POST['fivehwv'],$_POST['fivehlv'],$_POST['sixplayer_id'],$_POST['sixhow_out'],$_POST['sixassist'],$_POST['sixassist2'],$_POST['sixbowler'],$_POST['sixruns'],$_POST['sixballs'],$_POST['sixfours'],$_POST['sixsixes'],$_POST['sixhwv'],$_POST['sixhlv'],$_POST['sevenplayer_id'],$_POST['sevenhow_out'],$_POST['sevenassist'],$_POST['sevenassist2'],$_POST['sevenbowler'],$_POST['sevenruns'],$_POST['sevenballs'],$_POST['sevenfours'],$_POST['sevensixes'],$_POST['sevenhwv'],$_POST['sevenhlv'],$_POST['eightplayer_id'],$_POST['eighthow_out'],$_POST['eightassist'],$_POST['eightassist2'],$_POST['eightbowler'],$_POST['eightruns'],$_POST['eightballs'],$_POST['eightfours'],$_POST['eightsixes'],$_POST['eighthwv'],$_POST['eighthlv'],$_POST['nineplayer_id'],$_POST['ninehow_out'],$_POST['nineassist'],$_POST['nineassist2'],$_POST['ninebowler'],$_POST['nineruns'],$_POST['nineballs'],$_POST['ninefours'],$_POST['ninesixes'],$_POST['ninehwv'],$_POST['ninehlv'],$_POST['tenplayer_id'],$_POST['tenhow_out'],$_POST['tenassist'],$_POST['tenassist2'],$_POST['tenbowler'],$_POST['tenruns'],$_POST['tenballs'],$_POST['tenfours'],$_POST['tensixes'],$_POST['tenhwv'],$_POST['tenhlv'],$_POST['elevenplayer_id'],$_POST['elevenhow_out'],$_POST['elevenassist'],$_POST['elevenassist2'],$_POST['elevenbowler'],$_POST['elevenruns'],$_POST['elevenballs'],$_POST['elevenfours'],$_POST['elevensixes'],$_POST['elevenhwv'],$_POST['elevenhlv'],$_POST['totwickets'],$_POST['totovers'],$_POST['tottotal'],$_POST['totdltotal'],$_POST['extlegbyes'],$_POST['extbyes'],$_POST['extwides'],$_POST['extnoballs'],$_POST['exttotal'],$_POST['fowone'],$_POST['fowtwo'],$_POST['fowthree'],$_POST['fowfour'],$_POST['fowfive'],$_POST['fowsix'],$_POST['fowseven'], $_POST['foweight'],$_POST['fownine'],$_POST['fowten'],$_POST['onebowler_id'],$_POST['oneovers'],$_POST['onemaidens'],$_POST['onebowruns'],$_POST['onewickets'],$_POST['onenoballs'],$_POST['onewides'],$_POST['onehlvb'],$_POST['twobowler_id'],$_POST['twoovers'],$_POST['twomaidens'],$_POST['twobowruns'], $_POST['twowickets'],$_POST['twonoballs'],$_POST['twowides'],$_POST['twohlvb'],$_POST['threebowler_id'],$_POST['threeovers'],$_POST['threemaidens'],$_POST['threebowruns'],$_POST['threewickets'],$_POST['threenoballs'],$_POST['threewides'],$_POST['threehlvb'],$_POST['fourbowler_id'],$_POST['fourovers'],$_POST['fourmaidens'],$_POST['fourbowruns'],$_POST['fourwickets'],$_POST['fournoballs'],$_POST['fourwides'],$_POST['fourhlvb'],$_POST['fivebowler_id'],$_POST['fiveovers'],$_POST['fivemaidens'],$_POST['fivebowruns'],$_POST['fivewickets'],$_POST['fivenoballs'],$_POST['fivewides'],$_POST['fivehlvb'],$_POST['sixbowler_id'],$_POST['sixovers'],$_POST['sixmaidens'],$_POST['sixbowruns'],$_POST['sixwickets'],$_POST['sixnoballs'],$_POST['sixwides'],$_POST['sixhlvb'],$_POST['sevenbowler_id'],$_POST['sevenovers'],$_POST['sevenmaidens'],$_POST['sevenbowruns'],$_POST['sevenwickets'],$_POST['sevennoballs'],$_POST['sevenwides'],$_POST['sevenhlvb'],$_POST['eightbowler_id'],$_POST['eightovers'],$_POST['eightmaidens'],$_POST['eightbowruns'],$_POST['eightwickets'],$_POST['eightnoballs'],$_POST['eightwides'],$_POST['eighthlvb'],$_POST['ninebowler_id'],$_POST['nineovers'],$_POST['ninemaidens'],$_POST['ninebowruns'],$_POST['ninewickets'],$_POST['ninenoballs'],$_POST['ninewides'],$_POST['ninehlvb'],$_POST['tenbowler_id'],$_POST['tenovers'],$_POST['tenmaidens'],$_POST['tenbowruns'],$_POST['tenwickets'],$_POST['tennoballs'],$_POST['tenwides'],$_POST['tenhlvb'],$_POST['elevenbowler_id'],$_POST['elevenovers'],$_POST['elevenmaidens'],$_POST['elevenbowruns'],$_POST['elevenwickets'],$_POST['elevennoballs'],$_POST['elevenwides'],$_POST['elevenhlvb'],$_POST['onebatpos'],$_POST['twobatpos'],$_POST['threebatpos'],$_POST['fourbatpos'],$_POST['fivebatpos'],$_POST['sixbatpos'],$_POST['sevenbatpos'],$_POST['eightbatpos'],$_POST['ninebatpos'],$_POST['tenbatpos'],$_POST['elevenbatpos'],$_POST['onebowpos'], $_POST['twobowpos'],$_POST['threebowpos'],$_POST['fourbowpos'],$_POST['fivebowpos'],$_POST['sixbowpos'],$_POST['sevenbowpos'],$_POST['eightbowpos'],$_POST['ninebowpos'],$_POST['tenbowpos'],$_POST['elevenbowpos'],$_POST['oneteam'],$_POST['twoteam'],$_POST['threeteam'],$_POST['fourteam'],$_POST['fiveteam'],$_POST['sixteam'],$_POST['seventeam'],$_POST['eightteam'], $_POST['nineteam'],$_POST['tenteam'],$_POST['eleventeam'],$_POST['oneopponent'],$_POST['twoopponent'],$_POST['threeopponent'],$_POST['fouropponent'],$_POST['fiveopponent'],$_POST['sixopponent'],$_POST['sevenopponent'],$_POST['eightopponent'],$_POST['nineopponent'],$_POST['tenopponent'],$_POST['elevenopponent'],$_POST['bowloneteam'], $_POST['bowltwoteam'],$_POST['bowlthreeteam'],$_POST['bowlfourteam'],$_POST['bowlfiveteam'],$_POST['bowlsixteam'],$_POST['bowlseventeam'],$_POST['bowleightteam'],$_POST['bowlnineteam'],$_POST['bowltenteam'],$_POST['bowleleventeam'],$_POST['bowloneopponent'],$_POST['bowltwoopponent'],$_POST['bowlthreeopponent'], $_POST['bowlfouropponent'],$_POST['bowlfouropponent'],$_POST['bowlsixopponent'],$_POST['bowlsevenopponent'],$_POST['bowleightopponent'],$_POST['bowlnineopponent'],$_POST['bowltenopponent'],$_POST['bowlelevenopponent']);
	break;	
case "update4":
	edit_scorecard_step3($db,$_GET['game_id']);
	break;	
case "update5":
	update_scorecard_step3($db,$_POST['submit'], $_POST['game_id'],$_POST['season'],$_POST['innings_id'],$_POST['oneplayer_id'],$_POST['onehow_out'],$_POST['oneassist'],$_POST['oneassist2'],$_POST['onebowler'],$_POST['oneruns'],$_POST['oneballs'],$_POST['onefours'],$_POST['onesixes'],$_POST['onehwv'],$_POST['onehlv'],$_POST['twoplayer_id'], $_POST['twohow_out'],$_POST['twoassist'],$_POST['twoassist2'],$_POST['twobowler'],$_POST['tworuns'],$_POST['twoballs'],$_POST['twofours'],$_POST['twosixes'],$_POST['twohwv'],$_POST['twohlv'],$_POST['threeplayer_id'],$_POST['threehow_out'],$_POST['threeassist'],$_POST['threeassist2'],$_POST['threebowler'],$_POST['threeruns'],$_POST['threeballs'],$_POST['threefours'],$_POST['threesixes'],$_POST['threehwv'],$_POST['threehlv'],$_POST['fourplayer_id'],$_POST['fourhow_out'],$_POST['fourassist'],$_POST['fourassist2'],$_POST['fourbowler'],$_POST['fourruns'],$_POST['fourballs'],$_POST['fourfours'],$_POST['foursixes'],$_POST['fourhwv'],$_POST['fourhlv'],$_POST['fiveplayer_id'],$_POST['fivehow_out'],$_POST['fiveassist'],$_POST['fiveassist2'],$_POST['fivebowler'],$_POST['fiveruns'],$_POST['fiveballs'],$_POST['fivefours'],$_POST['fivesixes'],$_POST['fivehwv'],$_POST['fivehlv'],$_POST['sixplayer_id'],$_POST['sixhow_out'],$_POST['sixassist'],$_POST['sixassist2'],$_POST['sixbowler'],$_POST['sixruns'],$_POST['sixballs'],$_POST['sixfours'],$_POST['sixsixes'],$_POST['sixhwv'],$_POST['sixhlv'],$_POST['sevenplayer_id'],$_POST['sevenhow_out'],$_POST['sevenassist'],$_POST['sevenassist2'],$_POST['sevenbowler'],$_POST['sevenruns'],$_POST['sevenballs'],$_POST['sevenfours'],$_POST['sevensixes'],$_POST['sevenhwv'],$_POST['sevenhlv'],$_POST['eightplayer_id'],$_POST['eighthow_out'],$_POST['eightassist'],$_POST['eightassist2'],$_POST['eightbowler'],$_POST['eightruns'],$_POST['eightballs'],$_POST['eightfours'],$_POST['eightsixes'],$_POST['eighthwv'],$_POST['eighthlv'],$_POST['nineplayer_id'],$_POST['ninehow_out'],$_POST['nineassist'],$_POST['nineassist2'],$_POST['ninebowler'],$_POST['nineruns'],$_POST['nineballs'],$_POST['ninefours'],$_POST['ninesixes'],$_POST['ninehwv'],$_POST['ninehlv'],$_POST['tenplayer_id'],$_POST['tenhow_out'],$_POST['tenassist'],$_POST['tenassist2'],$_POST['tenbowler'],$_POST['tenruns'],$_POST['tenballs'],$_POST['tenfours'],$_POST['tensixes'],$_POST['tenhwv'],$_POST['tenhlv'],$_POST['elevenplayer_id'],$_POST['elevenhow_out'],$_POST['elevenassist'],$_POST['elevenassist2'],$_POST['elevenbowler'],$_POST['elevenruns'],$_POST['elevenballs'],$_POST['elevenfours'],$_POST['elevensixes'],$_POST['elevenhwv'],$_POST['elevenhlv'],$_POST['totwickets'],$_POST['totovers'],$_POST['tottotal'],$_POST['totdltotal'],$_POST['extlegbyes'],$_POST['extbyes'],$_POST['extwides'],$_POST['extnoballs'],$_POST['exttotal'],$_POST['fowone'],$_POST['fowtwo'],$_POST['fowthree'],$_POST['fowfour'],$_POST['fowfive'],$_POST['fowsix'],$_POST['fowseven'], $_POST['foweight'],$_POST['fownine'],$_POST['fowten'],$_POST['onebowler_id'],$_POST['oneovers'],$_POST['onemaidens'],$_POST['onebowruns'],$_POST['onewickets'],$_POST['onenoballs'],$_POST['onewides'],$_POST['onehlvb'],$_POST['twobowler_id'],$_POST['twoovers'],$_POST['twomaidens'],$_POST['twobowruns'], $_POST['twowickets'],$_POST['twonoballs'],$_POST['twowides'],$_POST['twohlvb'],$_POST['threebowler_id'],$_POST['threeovers'],$_POST['threemaidens'],$_POST['threebowruns'],$_POST['threewickets'],$_POST['threenoballs'],$_POST['threewides'],$_POST['threehlvb'],$_POST['fourbowler_id'],$_POST['fourovers'],$_POST['fourmaidens'],$_POST['fourbowruns'],$_POST['fourwickets'],$_POST['fournoballs'],$_POST['fourwides'],$_POST['fourhlvb'],$_POST['fivebowler_id'],$_POST['fiveovers'],$_POST['fivemaidens'],$_POST['fivebowruns'],$_POST['fivewickets'],$_POST['fivenoballs'],$_POST['fivewides'],$_POST['fivehlvb'],$_POST['sixbowler_id'],$_POST['sixovers'],$_POST['sixmaidens'],$_POST['sixbowruns'],$_POST['sixwickets'],$_POST['sixnoballs'],$_POST['sixwides'],$_POST['sixhlvb'],$_POST['sevenbowler_id'],$_POST['sevenovers'],$_POST['sevenmaidens'],$_POST['sevenbowruns'],$_POST['sevenwickets'],$_POST['sevennoballs'],$_POST['sevenwides'],$_POST['sevenhlvb'],$_POST['eightbowler_id'],$_POST['eightovers'],$_POST['eightmaidens'],$_POST['eightbowruns'],$_POST['eightwickets'],$_POST['eightnoballs'],$_POST['eightwides'],$_POST['eighthlvb'],$_POST['ninebowler_id'],$_POST['nineovers'],$_POST['ninemaidens'],$_POST['ninebowruns'],$_POST['ninewickets'],$_POST['ninenoballs'],$_POST['ninewides'],$_POST['ninehlvb'],$_POST['tenbowler_id'],$_POST['tenovers'],$_POST['tenmaidens'],$_POST['tenbowruns'],$_POST['tenwickets'],$_POST['tennoballs'],$_POST['tenwides'],$_POST['tenhlvb'],$_POST['elevenbowler_id'],$_POST['elevenovers'],$_POST['elevenmaidens'],$_POST['elevenbowruns'],$_POST['elevenwickets'],$_POST['elevennoballs'],$_POST['elevenwides'],$_POST['elevenhlvb'],$_POST['onebatpos'],$_POST['twobatpos'],$_POST['threebatpos'],$_POST['fourbatpos'],$_POST['fivebatpos'],$_POST['sixbatpos'],$_POST['sevenbatpos'],$_POST['eightbatpos'],$_POST['ninebatpos'],$_POST['tenbatpos'],$_POST['elevenbatpos'],$_POST['onebowpos'], $_POST['twobowpos'],$_POST['threebowpos'],$_POST['fourbowpos'],$_POST['fivebowpos'],$_POST['sixbowpos'],$_POST['sevenbowpos'],$_POST['eightbowpos'],$_POST['ninebowpos'],$_POST['tenbowpos'],$_POST['elevenbowpos'],$_POST['oneteam'],$_POST['twoteam'],$_POST['threeteam'],$_POST['fourteam'],$_POST['fiveteam'],$_POST['sixteam'],$_POST['seventeam'],$_POST['eightteam'], $_POST['nineteam'],$_POST['tenteam'],$_POST['eleventeam'],$_POST['oneopponent'],$_POST['twoopponent'],$_POST['threeopponent'],$_POST['fouropponent'],$_POST['fiveopponent'],$_POST['sixopponent'],$_POST['sevenopponent'],$_POST['eightopponent'],$_POST['nineopponent'],$_POST['tenopponent'],$_POST['elevenopponent'],$_POST['bowloneteam'], $_POST['bowltwoteam'],$_POST['bowlthreeteam'],$_POST['bowlfourteam'],$_POST['bowlfiveteam'],$_POST['bowlsixteam'],$_POST['bowlseventeam'],$_POST['bowleightteam'],$_POST['bowlnineteam'],$_POST['bowltenteam'],$_POST['bowleleventeam'],$_POST['bowloneopponent'],$_POST['bowltwoopponent'],$_POST['bowlthreeopponent'], $_POST['bowlfouropponent'],$_POST['bowlfouropponent'],$_POST['bowlsixopponent'],$_POST['bowlsevenopponent'],$_POST['bowleightopponent'],$_POST['bowlnineopponent'],$_POST['bowltenopponent'],$_POST['bowlelevenopponent']);
	break;	
case "update6":
	finished_update($db, $_GET['game_id']);
	break;
default:
	show_main_menu($db);
	break;
}

?>
