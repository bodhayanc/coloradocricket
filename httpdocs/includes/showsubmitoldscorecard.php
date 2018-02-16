<?php

//------------------------------------------------------------------------------
// Scorecards v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


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
	echo "    <td><a href=\"http://www.mozilla.org/products/firefox/\" target=\"_new\"><img src=\"/images/firefox.jpg\" border=\"0\" align=\"right\"></a>\n";

	echo "<form action=\"/submitold/index.php?ccl_mode=1\" method=\"post\" enctype=\"multipart/form-data\" validate=\"onchange\" invalidColor=\"yellow\">\n";

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
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName");
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
	echo "  <input date size=\"20\" maxlength=\"25\" name=\"game_date\" required filter=\"[0-9/-]\" msg=\"Please enter the date in the format yyyy-mm-dd\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Game Date (yyyy-mm-dd) <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam\" required msg=\"Please select the visiting team from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Visiting Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam\" required msg=\"Please select the home team from the drop down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Home Team <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpires\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
	echo "  <input type=\"checkbox\" name=\"tied\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if match tied <img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"forfeit\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if won by forfeit <img src=\"/images/ballgreen.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"cancelled\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with no play <img src=\"/images/ballyellow.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"cancelledplay\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with some play</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"result\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Result <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"><img src=\"/images/ballwhite.gif\" width=\"8\" height=\"8\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"mom\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Man of the Match</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"maxovers\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Max Overs <img src=\"/images/ballred.gif\" width=\"8\" height=\"8\"> (example: 40)</td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"submit\" value=\"submit\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
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


function add_scorecard_step1_old($db)
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
	echo "    <td><a href=\"http://www.mozilla.org/products/firefox/\" target=\"_new\"><img src=\"/images/firefox.jpg\" border=\"0\" align=\"right\"></a>\n";

	echo "<form action=\"/submitold/index.php?ccl_mode=1\" method=\"post\" enctype=\"multipart/form-data\" validate=\"onchange\" invalidColor=\"yellow\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"season\" required msg=\"Please select a season from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM seasons")) {
		$db->Query("SELECT * FROM seasons ORDER BY SeasonName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">Season " . $db->data['SeasonName'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Season *<font color=\"red\">*</font><font color=\"green\">*</font></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input name=\"week\" size=\"10\" maxlength=\"10\" required filter=\"[0-9]\" msg=\"Please enter the week number using only numbers\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Week # *<font color=\"red\">*</font><font color=\"green\">*</font></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input date size=\"20\" maxlength=\"25\" name=\"game_date\" required filter=\"[0-9/-]\" msg=\"Please enter the date in the format yyyy-mm-dd\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Game Date (yyyy-mm-dd) *<font color=\"red\">*</font><font color=\"green\">*</font></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"awayteam\" required msg=\"Please select the visiting team from the drop-down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Visiting Team *<font color=\"red\">*</font><font color=\"green\">*</font></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"hometeam\" required msg=\"Please select the home team from the drop down menu.\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Home Team *<font color=\"red\">*</font><font color=\"green\">*</font></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"umpires\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
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
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Team Batting First *</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"batting_second_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Team Batting second *</td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <select name=\"result_won_id\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamAbbrev");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Victorious Team * (only if a result occurred)</td>\n";
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
	echo "  <td width=\"50%\" align=\"left\">Venue *</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"tied\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if match tied</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"forfeit\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if won by forfeit<font color=\"red\">*</font></td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"cancelled\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with no play<font color=\"green\">*</font></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"checkbox\" name=\"cancelledplay\" value=\"1\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Check if game cancelled with some play</td>\n";
	echo " </tr>\n";
	
	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"result\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Result *</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"mom\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Man of the Match</td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"maxovers\" size=\"20\" maxlength=\"255\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">Enter Max Overs * (example: 40)</td>\n";
	echo " </tr>\n";	

	echo " <tr>\n";
	echo "  <td width=\"50%\" align=\"right\">";
	echo "  <input type=\"submit\" value=\"submit\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	echo "  </td>\n";
	echo "  <td width=\"50%\" align=\"left\">&nbsp;</td>\n";
	echo " </tr>\n";	


	echo "</table>\n";

	echo "</form>\n";
	echo "<script src=\"../includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	
	echo "<p>* fields are required. <font color=\"red\">*</font> fields are required for a forfeit game. <font color=\"green\">*</font> fields are required for a cancelled game.</p>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	ob_end_flush();

}

function insert_scorecard_step1($db,$league_id,$season,$week,$awayteam,$hometeam,$umpires,$toss_won_id,$result_won_id,$batting_first_id,$batting_second_id,$ground_id,$game_date,$result,$tied,$forfeit,$cancelled,$cancelledplay,$mom,$maxovers)
{

	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	//////////////////////////////////////////////////////////////////////////////////////////			
	// This is a forfeited game
	//////////////////////////////////////////////////////////////////////////////////////////		

	if($forfeit == "1") {

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
	$ht = addslashes(trim($hometeam));
	$gd = addslashes(trim($game_date));
	$fo = addslashes(trim($forfeit));
	$rw = addslashes(trim($result_won_id));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));

	// all okay

	// Insert into the scorecard_game_details table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,game_date,result_won_id,forfeit,mom,maxovers,isactive) VALUES ('$li','$se','$we','$at','$ht','$gd','$rw','$fo','$mm','$mo',0)");

	// Update the results table for the home team
	
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
	
	// Update the results table for the away team
	
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
	
	header("Location: /submitold/index.php?ccl_mode=6");
	ob_end_flush();
	
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a cancelled game	
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else if($cancelled == "1") {
	
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
	$ht = addslashes(trim($hometeam));
	$gd = addslashes(trim($game_date));
	$ca = addslashes(trim($cancelled));
	$cg = addslashes(trim($cancelledplay));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));
	
	// all okay

	// Insert into the game header table

	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,game_date,cancelled,cancelledplay,mom,maxovers,isactive) VALUES ('$li','$se','$we','$at','$ht','$gd','$ca','$cg','$mm','$mo',0)");

	// Update the results table for the home team
	
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homenr = $db->data[nr];
		$homepl = $db->data['played'];
	
	$db->update("UPDATE results SET nr = $homenr + 1, played = $homepl + 1 WHERE team_id = $hometeam");

	// Update the results table for the away team
	
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaynr = $db->data[nr];
		$awaypl = $db->data['played'];

	$db->update("UPDATE results SET nr = $awaynr + 1, played = $awaypl +1 WHERE team_id = $awayteam");
		
	header("Location: /submitold/index.php?ccl_mode=6");
	ob_end_flush();	

	//////////////////////////////////////////////////////////////////////////////////////////
	// This is a tied game
	//////////////////////////////////////////////////////////////////////////////////////////

	} else if($tied == "1") {	

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
	$ht = addslashes(trim($hometeam));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = addslashes(trim($tied));
	$fo = addslashes(trim($forfeit));
	$ca = addslashes(trim($cancelled));
	$cg = addslashes(trim($cancelledplay));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));
	
	// all okay

	// Insert into the game header table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,cancelled,cancelledplay,mom,maxovers,isactive) VALUES ('$li','$se','$we','$at','$ht','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mo',0)");

	// Update the results table for the home team
	
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homepla = $db->data['played'];
		$hometie = $db->data['tied'];
	
	$db->update("UPDATE results SET played = $homepla + 1, tied = $hometie + 1 WHERE team_id = $hometeam");
	
	// Update the results table for the away team
	
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaypla = $db->data['played'];
		$awaytie = $db->data['tied'];
	
	$db->update("UPDATE results SET played = $awaypla + 1, tied = $awaytie + 1 WHERE team_id = $awayteam");

	
	header("Location: /submitold/index.php?season=$se&game_date=$gd&awayteam=$at&hometeam=$ht&ccl_mode=2");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////
	// This is a cancelled game with some play
	//////////////////////////////////////////////////////////////////////////////////////////

	} else if($cancelledplay == "1") {	

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
	$ht = addslashes(trim($hometeam));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = addslashes(trim($tied));
	$fo = addslashes(trim($forfeit));
	$ca = addslashes(trim($cancelled));
	$cg = addslashes(trim($cancelledplay));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));
	
	// all okay

	// Insert into the game header table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,cancelled,cancelledplay,mom,maxovers,isactive) VALUES ('$li','$se','$we','$at','$ht','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mo',0)");

	// Update the results table for the home team
	
	$db->QueryRow("SELECT * FROM results WHERE team_id=$hometeam");
		$homepla = $db->data['played'];
		$homecap = $db->data[nr];
	
	$db->update("UPDATE results SET played = $homepla + 1, nr = $homecap + 1 WHERE team_id = $hometeam");
	
	// Update the results table for the away team
	
	$db->QueryRow("SELECT * FROM results WHERE team_id=$awayteam");
		$awaypla = $db->data['played'];
		$awaycap = $db->data[nr];
	
	$db->update("UPDATE results SET played = $awaypla + 1, nr = $awaycap + 1 WHERE team_id = $awayteam");

	
	header("Location: /submitold/index.php?season=$se&game_date=$gd&awayteam=$at&hometeam=$ht&ccl_mode=2");
	ob_end_flush();
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a normal game
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else {
	

	// make sure info is present and correct

	if ($league_id == "" || $season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" || $game_date == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables

	$li = addslashes(trim($league_id));
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$ht = addslashes(trim($hometeam));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = addslashes(trim($tied));
	$fo = addslashes(trim($forfeit));
	$ca = addslashes(trim($cancelled));
	$cg = addslashes(trim($cancelledplay));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));
	
	// all okay

	// Insert into the game header table
	
	$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,cancelled,cancelledplay,mom,maxovers,isactive) VALUES ('$li','$se','$we','$at','$ht','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mo',0)");

	// Update the results table for the home team
	
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
	
	// Update the results table for the away team
	
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
	
	header("Location: /submitold/index.php?season=$se&game_date=$gd&awayteam=$at&hometeam=$ht&ccl_mode=2");
	ob_end_flush();
	
	}

}

function insert_scorecard_step1_old($db,$season,$week,$awayteam,$hometeam,$umpires,$toss_won_id,$result_won_id,$batting_first_id,$batting_second_id,$ground_id,$game_date,$result,$tied,$forfeit,$cancelled,$cancelledplay,$mom,$maxovers)
{

	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a forfeited game
	//////////////////////////////////////////////////////////////////////////////////////////		

	if($forfeit == "1") {

	// make sure forfeit info is present and correct

	if ($season == "" || $awayteam == "" || $hometeam == "" || $game_date == "" || $week == "") {
		echo "<p class=\"12pt\"><b>Forfeit Game Missing information</b></p>\n";
		echo "<p>You must complete all the required (<font color=\"red\">*</font>) forfeit game fields. Please go back and try again.</p>\n";
		return;
	}	
	
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$ht = addslashes(trim($hometeam));
	$gd = addslashes(trim($game_date));
	$fo = addslashes(trim($forfeit));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));

	// all okay

	$db->Insert("INSERT INTO scorecard_game_details (season,week,awayteam,hometeam,game_date,forfeit,mom,maxovers,isactive) VALUES ('$se','$we','$at','$ht','$gd','$fo','$mm',$mo,0)");

	header("Location: /submitold/index.php?ccl_mode=6");
	ob_end_flush();
	
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a cancelled game	
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else if($cancelled == "1") {
	
	// make sure cancelled info is present and correct

	if ($season == "" || $awayteam == "" || $hometeam == "" || $game_date == "" || $week == "") {
		echo "<p class=\"12pt\"><b>Cancelled Game Missing information</b></p>\n";
		echo "<p>You must complete all the required (<font color=\"green\">*</font>) cancelled game fields. Please go back and try again.</p>\n";
		return;
	}	
	
	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$ht = addslashes(trim($hometeam));
	$gd = addslashes(trim($game_date));
	$ca = addslashes(trim($cancelled));
	$cg = addslashes(trim($cancelledplay));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));
	
	// all okay

	$db->Insert("INSERT INTO scorecard_game_details (season,week,awayteam,hometeam,game_date,cancelled,cancelledplay,mom,maxovers,isactive) VALUES ('$se','$we','$at','$ht','$gd','$ca','$cg','$mm','$mo',0)");

	header("Location: /submitold/index.php?ccl_mode=6");
	ob_end_flush();	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////		
	// This is a normal game
	//////////////////////////////////////////////////////////////////////////////////////////		

	} else {
	

	// make sure info is present and correct

	if ($season == "" || $awayteam == "" || $hometeam == "" || $batting_first_id == "" || $batting_second_id == "" || $ground_id == "" || $game_date == "" || $result == "" || $maxovers == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
		return;
	}
	
	// setup variables

	$se = addslashes(trim($season));
	$we = addslashes(trim($week));
	$at = addslashes(trim($awayteam));
	$ht = addslashes(trim($hometeam));
	$um = addslashes(trim($umpires));
	$tw = addslashes(trim($toss_won_id));
	$rw = addslashes(trim($result_won_id));
	$bf = addslashes(trim($batting_first_id));
	$bs = addslashes(trim($batting_second_id));
	$gi = addslashes(trim($ground_id));
	$gd = addslashes(trim($game_date));
	$re = addslashes(trim($result));
	$ti = addslashes(trim($tied));
	$fo = addslashes(trim($forfeit));
	$ca = addslashes(trim($cancelled));
	$cg = addslashes(trim($cancelledplay));
	$mm = addslashes(trim($mom));
	$mo = addslashes(trim($maxovers));
	
	// all okay

	$db->Insert("INSERT INTO scorecard_game_details (season,week,awayteam,hometeam,umpires,toss_won_id,result_won_id,batting_first_id,batting_second_id,ground_id,game_date,result,tied,forfeit,cancelled,cancelledplay,mom,maxovers,isactive) VALUES ('$se','$we','$at','$ht','$um','$tw','$rw','$bf','$bs','$gi','$gd','$re','$ti','$fo','$ca','$cg','$mm','$mo',0)");

	header("Location: /submitold/index.php?season=$se&game_date=$gd&awayteam=$at&hometeam=$ht&ccl_mode=2");
	ob_end_flush();
	
	}

}

function add_scorecard_step2($db,$season,$game_date,$awayteam,$hometeam)
{
	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	$at = addslashes(trim($awayteam));
	$ht = addslashes(trim($hometeam));
	$gd = addslashes(trim($game_date));
	$se = addslashes(trim($season));
	
	if ($db->a_rows != -1) {

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
	  s.game_date = '$gd' AND s.awayteam = '$at' AND s.hometeam = '$ht'
	");

	$db->BagAndTag();

	$gid = $db->data['game_id'];
	$gsc = $db->data['season'];
	$gli = $db->data[league_id];
	
	$ght = $db->data['homeabbrev'];
	$ghi = $db->data['homeid'];
	$gat = $db->data['awayabbrev'];
	$gai = $db->data['awayid'];
	
	$gut = $db->data['umpireabbrev'];
	$ggr = $db->data['GroundName'];
	$ggi = $db->data['GroundID'];
	$gre = $db->data['result'];
	$gtt = $db->data[WonTossAbbrev];

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
	echo "<p align=\"left\"><b><font color=\"red\">IMPORTANT!</font></b> If you are using <a href=\"http://www.getfirefox.com\" target=\"_new\">Firefox</a> then you may <a href=\"addplayer.php\" target=\"_new\">add new players</a> at any time. Please REFRESH the page once adding, Firefox should remember your selections. If you have another browser, check the drop-down menu's first to make sure that all players exist. Other browsers may not remember form selection data.</p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Team Batting 1st Details - $bat1st</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<form action=\"/submitold/index.php?ccl_mode=3\" method=\"post\" enctype=\"multipart/form-data\" name=\"comboForm\">\n";
	
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

	echo " <tr>\n";
	
	echo "  <td width=\"70%\" colspan=\"4\">&nbsp;</td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Runs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Balls</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>4s</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>6s</b></td>\n";
	
	echo " </tr>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                1st Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneplayer_id\" id=\"combobox1\">\n";
	echo "	<option value=\"\">1st Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneassist\" id=\"combobox2\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"onebowler\" id=\"combobox3\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"oneruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"oneballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoplayer_id\" id=\"combobox4\">\n";
	echo "	<option value=\"\">2nd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoassist\" id=\"combobox5\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twobowler\" id=\"combobox6\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tworuns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twofours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twosixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeplayer_id\" id=\"combobox7\">\n";
	echo "	<option value=\"\">3rd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeassist\" id=\"combobox8\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threebowler\" id=\"combobox9\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threeruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threeballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourplayer_id\" id=\"combobox10\">\n";
	echo "	<option value=\"\">4th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourassist\" id=\"combobox11\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourbowler\" id=\"combobox12\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"foursixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveplayer_id\" id=\"combobox13\">\n";
	echo "	<option value=\"\">5th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveassist\" id=\"combobox14\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fivebowler\" id=\"combobox15\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fiveruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fiveballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixplayer_id\" id=\"combobox16\">\n";
	echo "	<option value=\"\">6th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixassist\" id=\"combobox17\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixbowler\" id=\"combobox18\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixsixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenplayer_id\" id=\"combobox19\">\n";
	echo "	<option value=\"\">7th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenassist\" id=\"combobox20\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenbowler\" id=\"combobox21\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevensixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightplayer_id\" id=\"combobox22\">\n";
	echo "	<option value=\"\">8th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightassist\" id=\"combobox23\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightbowler\" id=\"combobox24\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightsixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineplayer_id\" id=\"combobox25\">\n";
	echo "	<option value=\"\">9th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineassist\" id=\"combobox26\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"ninebowler\" id=\"combobox27\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"nineruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"nineballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Bastman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenplayer_id\" id=\"combobox28\">\n";
	echo "	<option value=\"\">10th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenassist\" id=\"combobox29\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenbowler\" id=\"combobox30\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tensixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Bastman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenplayer_id\" id=\"combobox31\">\n";
	echo "	<option value=\"\">11th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenassist\" id=\"combobox32\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenbowler\" id=\"combobox33\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevensixes\" size=\"5\" maxlength=\"7\">\n";
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
	echo "  <td width=\"8%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Legbyes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Byes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wides</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Noballs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Total</b></td>\n";
	
	echo " </tr>\n";
	echo " <tr>\n";
	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totwickets\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totovers\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"tottotal\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extlegbyes\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extbyes\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extwides\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extnoballs\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"exttotal\" size=\"5\" maxlength=\"7\"></td>\n";	
	
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
	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowone\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowtwo\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowthree\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfour\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfive\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowsix\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowseven\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"foweight\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fownine\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowten\" size=\"5\" maxlength=\"7\"></td>\n";	
	
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
	echo "  <select name=\"onebowler_id\" id=\"combobox34\">\n";
	echo "	<option value=\"\">1st Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"oneovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"twobowler_id\" id=\"combobox35\">\n";
	echo "	<option value=\"\">2nd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twoovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twomaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twobowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twowickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twonoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twowides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"threebowler_id\" id=\"combobox36\">\n";
	echo "	<option value=\"\">3rd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threeovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fourbowler_id\" id=\"combobox37\">\n";
	echo "	<option value=\"\">4th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fournoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fivebowler_id\" id=\"combobox38\">\n";
	echo "	<option value=\"\">5th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fiveovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sixbowler_id\" id=\"combobox39\">\n";
	echo "	<option value=\"\">6th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixnoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sevenbowler_id\" id=\"combobox40\">\n";
	echo "	<option value=\"\">7th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevennoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"eightbowler_id\" id=\"combobox41\">\n";
	echo "	<option value=\"\">8th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightnoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"ninebowler_id\" id=\"combobox42\">\n";
	echo "	<option value=\"\">9th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"nineovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Bowler Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"tenbowler_id\" id=\"combobox43\">\n";
	echo "	<option value=\"\">10th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tennoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"elevenbowler_id\" id=\"combobox44\">\n";
	echo "	<option value=\"\">11th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevennoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<input type=\"submit\" value=\"submit\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	
	echo "</form>\n";

	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
	
	
	} else {
		echo "<p class=\"12pt\"><b>Uh oh!</b></p>\n";
		echo "<p>There was a problem adding the game details, please try again.</p>\n";
	}
}


function insert_scorecard_step2($db,$game_id,$season,$innings_id,$oneplayer_id,$onehow_out,$oneassist,$onebowler,$oneruns,$oneballs,$onefours,$onesixes,$twoplayer_id,$twohow_out,$twoassist,$twobowler,$tworuns,$twoballs,$twofours,$twosixes,$threeplayer_id,$threehow_out,$threeassist,$threebowler,$threeruns,$threeballs,$threefours,$threesixes,$fourplayer_id,$fourhow_out,$fourassist,$fourbowler,$fourruns,$fourballs,$fourfours,$foursixes,$fiveplayer_id,$fivehow_out,$fiveassist,$fivebowler,$fiveruns,$fiveballs,$fivefours,$fivesixes,$sixplayer_id,$sixhow_out,$sixassist,$sixbowler,$sixruns,$sixballs,$sixfours,$sixsixes,$sevenplayer_id,$sevenhow_out,$sevenassist,$sevenbowler,$sevenruns,$sevenballs,$sevenfours,$sevensixes,$eightplayer_id,$eighthow_out,$eightassist,$eightbowler,$eightruns,$eightballs,$eightfours,$eightsixes,$nineplayer_id,$ninehow_out,$nineassist,$ninebowler,$nineruns,$nineballs,$ninefours,$ninesixes,$tenplayer_id,$tenhow_out,$tenassist,$tenbowler,$tenruns,$tenballs,$tenfours,$tensixes,$elevenplayer_id,$elevenhow_out,$elevenassist,$elevenbowler,$elevenruns,$elevenballs,$elevenfours,$elevensixes,$totwickets,$totovers,$tottotal,$extlegbyes,$extbyes,$extwides,$extnoballs,$exttotal,$fowone,$fowtwo,$fowthree,$fowfour,$fowfive,$fowsix,$fowseven,$foweight,$fownine,$fowten,$onebowler_id,$oneovers,$onemaidens,$onebowruns,$onewickets,$onenoballs,$onewides,$twobowler_id,$twoovers,$twomaidens,$twobowruns,$twowickets,$twonoballs,$twowides,$threebowler_id,$threeovers,$threemaidens,$threebowruns,$threewickets,$threenoballs,$threewides,$fourbowler_id,$fourovers,$fourmaidens,$fourbowruns,$fourwickets,$fournoballs,$fourwides,$fivebowler_id,$fiveovers,$fivemaidens,$fivebowruns,$fivewickets,$fivenoballs,$fivewides,$sixbowler_id,$sixovers,$sixmaidens,$sixbowruns,$sixwickets,$sixnoballs,$sixwides,$sevenbowler_id,$sevenovers,$sevenmaidens,$sevenbowruns,$sevenwickets,$sevennoballs,$sevenwides,$eightbowler_id,$eightovers,$eightmaidens,$eightbowruns,$eightwickets,$eightnoballs,$eightwides,$ninebowler_id,$nineovers,$ninemaidens,$ninebowruns,$ninewickets,$ninenoballs,$ninewides,$tenbowler_id,$tenovers,$tenmaidens,$tenbowruns,$tenwickets,$tennoballs,$tenwides,$elevenbowler_id,$elevenovers,$elevenmaidens,$elevenbowruns,$elevenwickets,$elevennoballs,$elevenwides,$onebatpos,$twobatpos,$threebatpos,$fourbatpos,$fivebatpos,$sixbatpos,$sevenbatpos,$eightbatpos,$ninebatpos,$tenbatpos,$elevenbatpos,$onebowpos,$twobowpos,$threebowpos,$fourbowpos,$fivebowpos,$sixbowpos,$sevenbowpos,$eightbowpos,$ninebowpos,$tenbowpos,$elevenbowpos,$oneteam,$twoteam,$threeteam,$fourteam,$fiveteam,$sixteam,$seventeam,$eightteam,$nineteam,$tenteam,$eleventeam,$oneopponent,$twoopponent,$threeopponent,$fouropponent,$fiveopponent,$sixopponent,$sevenopponent,$eightopponent,$nineopponent,$tenopponent,$elevenopponent,$bowloneteam,$bowltwoteam,$bowlthreeteam,$bowlfourteam,$bowlfiveteam,$bowlsixteam,$bowlseventeam,$bowleightteam,$bowlnineteam,$bowltenteam,$bowleleventeam,$bowloneopponent,$bowltwoopponent,$bowlthreeopponent,$bowlfouropponent,$bowlfiveopponent,$bowlsixopponent,$bowlsevenopponent,$bowleightopponent,$bowlnineopponent,$bowltenopponent,$bowlelevenopponent)
{
	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	// make sure info is present and correct

	//if ($totwickets == "" || $totovers || $tottotal) {
	//	echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
	//	echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
	//	return;
	//}

	// setup variables

	$game_id = addslashes(trim($game_id));
	$season = addslashes(trim($season));
	$innings_id = addslashes(trim($innings_id));
	
	$onepl = addslashes(trim($oneplayer_id));
	$oneho = addslashes(trim($onehow_out));
	$oneas = addslashes(trim($oneassist));
	$onebo = addslashes(trim($onebowler));
	$oneru = addslashes(trim($oneruns));
	$oneba = addslashes(trim($oneballs));
	$onefo = addslashes(trim($onefours));
	$onesi = addslashes(trim($onesixes));
	if($onehow_out != "2" && $onehow_out != "8") { $oneno == "0"; } else { $oneno = "1"; }

	$twopl = addslashes(trim($twoplayer_id));
	$twoho = addslashes(trim($twohow_out));
	$twoas = addslashes(trim($twoassist));
	$twobo = addslashes(trim($twobowler));
	$tworu = addslashes(trim($tworuns));
	$twoba = addslashes(trim($twoballs));
	$twofo = addslashes(trim($twofours));
	$twosi = addslashes(trim($twosixes));
	if($twohow_out != "2" && $twohow_out != "8") { $twono == "0"; } else { $twono = "1"; }

	$threepl = addslashes(trim($threeplayer_id));
	$threeho = addslashes(trim($threehow_out));
	$threeas = addslashes(trim($threeassist));
	$threebo = addslashes(trim($threebowler));
	$threeru = addslashes(trim($threeruns));
	$threeba = addslashes(trim($threeballs));
	$threefo = addslashes(trim($threefours));
	$threesi = addslashes(trim($threesixes));
	if($threehow_out != "2" && $threehow_out != "8") { $threeno == "0"; } else { $threeno = "1"; }

	$fourpl = addslashes(trim($fourplayer_id));
	$fourho = addslashes(trim($fourhow_out));
	$fouras = addslashes(trim($fourassist));
	$fourbo = addslashes(trim($fourbowler));
	$fourru = addslashes(trim($fourruns));
	$fourba = addslashes(trim($fourballs));
	$fourfo = addslashes(trim($fourfours));
	$foursi = addslashes(trim($foursixes));
	if($fourhow_out != "2" && $fourhow_out != "8") { $fourno == "0"; } else { $fourno = "1"; }

	$fivepl = addslashes(trim($fiveplayer_id));
	$fiveho = addslashes(trim($fivehow_out));
	$fiveas = addslashes(trim($fiveassist));
	$fivebo = addslashes(trim($fivebowler));
	$fiveru = addslashes(trim($fiveruns));
	$fiveba = addslashes(trim($fiveballs));
	$fivefo = addslashes(trim($fivefours));
	$fivesi = addslashes(trim($fivesixes));
	if($fivehow_out != "2" && $fivehow_out != "8") { $fiveno == "0"; } else { $fiveno = "1"; }

	$sixpl = addslashes(trim($sixplayer_id));
	$sixho = addslashes(trim($sixhow_out));
	$sixas = addslashes(trim($sixassist));
	$sixbo = addslashes(trim($sixbowler));
	$sixru = addslashes(trim($sixruns));
	$sixba = addslashes(trim($sixballs));
	$sixfo = addslashes(trim($sixfours));
	$sixsi = addslashes(trim($sixsixes));
	if($sixhow_out != "2" && $sixhow_out != "8") { $sixno == "0"; } else { $sixno = "1"; }

	$sevenpl = addslashes(trim($sevenplayer_id));
	$sevenho = addslashes(trim($sevenhow_out));
	$sevenas = addslashes(trim($sevenassist));
	$sevenbo = addslashes(trim($sevenbowler));
	$sevenru = addslashes(trim($sevenruns));
	$sevenba = addslashes(trim($sevenballs));
	$sevenfo = addslashes(trim($sevenfours));
	$sevensi = addslashes(trim($sevensixes));
	if($sevenhow_out != "2" && $sevenhow_out != "8") { $sevenno == "0"; } else { $sevenno = "1"; }

	$eightpl = addslashes(trim($eightplayer_id));
	$eightho = addslashes(trim($eighthow_out));
	$eightas = addslashes(trim($eightassist));
	$eightbo = addslashes(trim($eightbowler));
	$eightru = addslashes(trim($eightruns));
	$eightba = addslashes(trim($eightballs));
	$eightfo = addslashes(trim($eightfours));
	$eightsi = addslashes(trim($eightsixes));
	if($eighthow_out != "2" && $eighthow_out != "8") { $eightno == "0"; } else { $eightno = "1"; }

	$ninepl = addslashes(trim($nineplayer_id));
	$nineho = addslashes(trim($ninehow_out));
	$nineas = addslashes(trim($nineassist));
	$ninebo = addslashes(trim($ninebowler));
	$nineru = addslashes(trim($nineruns));
	$nineba = addslashes(trim($nineballs));
	$ninefo = addslashes(trim($ninefours));
	$ninesi = addslashes(trim($ninesixes));
	if($ninehow_out != "2" && $ninehow_out != "8") { $nineno == "0"; } else { $nineno = "1"; }

	$tenpl = addslashes(trim($tenplayer_id));
	$tenho = addslashes(trim($tenhow_out));
	$tenas = addslashes(trim($tenassist));
	$tenbo = addslashes(trim($tenbowler));
	$tenru = addslashes(trim($tenruns));
	$tenba = addslashes(trim($tenballs));
	$tenfo = addslashes(trim($tenfours));
	$tensi = addslashes(trim($tensixes));
	if($tenhow_out != "2" && $tenhow_out != "8") { $tenno == "0"; } else { $tenno = "1"; }

	$elevenpl = addslashes(trim($elevenplayer_id));
	$elevenho = addslashes(trim($elevenhow_out));
	$elevenas = addslashes(trim($elevenassist));
	$elevenbo = addslashes(trim($elevenbowler));
	$elevenru = addslashes(trim($elevenruns));
	$elevenba = addslashes(trim($elevenballs));
	$elevenfo = addslashes(trim($elevenfours));
	$elevensi = addslashes(trim($elevensixes));
	if($elevenhow_out != "2" && $elevenhow_out != "8") { $elevenno == "0"; } else { $elevenno = "1"; }

	$totw = addslashes(trim($totwickets));
	$toto = addslashes(trim($totovers));
	$tott = addslashes(trim($tottotal));
	
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

	$twobow = addslashes(trim($twobowler_id));
	$twoove = addslashes(trim($twoovers));
	$twomai = addslashes(trim($twomaidens));
	$twobru = addslashes(trim($twobowruns));
	$twowic = addslashes(trim($twowickets));
	$twonob = addslashes(trim($twonoballs));
	$twowid = addslashes(trim($twowides));

	$threebow = addslashes(trim($threebowler_id));
	$threeove = addslashes(trim($threeovers));
	$threemai = addslashes(trim($threemaidens));
	$threebru = addslashes(trim($threebowruns));
	$threewic = addslashes(trim($threewickets));
	$threenob = addslashes(trim($threenoballs));
	$threewid = addslashes(trim($threewides));

	$fourbow = addslashes(trim($fourbowler_id));
	$fourove = addslashes(trim($fourovers));
	$fourmai = addslashes(trim($fourmaidens));
	$fourbru = addslashes(trim($fourbowruns));
	$fourwic = addslashes(trim($fourwickets));
	$fournob = addslashes(trim($fournoballs));
	$fourwid = addslashes(trim($fourwides));

	$fivebow = addslashes(trim($fivebowler_id));
	$fiveove = addslashes(trim($fiveovers));
	$fivemai = addslashes(trim($fivemaidens));
	$fivebru = addslashes(trim($fivebowruns));
	$fivewic = addslashes(trim($fivewickets));
	$fivenob = addslashes(trim($fivenoballs));
	$fivewid = addslashes(trim($fivewides));

	$sixbow = addslashes(trim($sixbowler_id));
	$sixove = addslashes(trim($sixovers));
	$sixmai = addslashes(trim($sixmaidens));
	$sixbru = addslashes(trim($sixbowruns));
	$sixwic = addslashes(trim($sixwickets));
	$sixnob = addslashes(trim($sixnoballs));
	$sixwid = addslashes(trim($sixwides));

	$sevenbow = addslashes(trim($sevenbowler_id));
	$sevenove = addslashes(trim($sevenovers));
	$sevenmai = addslashes(trim($sevenmaidens));
	$sevenbru = addslashes(trim($sevenbowruns));
	$sevenwic = addslashes(trim($sevenwickets));
	$sevennob = addslashes(trim($sevennoballs));
	$sevenwid = addslashes(trim($sevenwides));

	$eightbow = addslashes(trim($eightbowler_id));
	$eightove = addslashes(trim($eightovers));
	$eightmai = addslashes(trim($eightmaidens));
	$eightbru = addslashes(trim($eightbowruns));
	$eightwic = addslashes(trim($eightwickets));
	$eightnob = addslashes(trim($eightnoballs));
	$eightwid = addslashes(trim($eightwides));

	$ninebow = addslashes(trim($ninebowler_id));
	$nineove = addslashes(trim($nineovers));
	$ninemai = addslashes(trim($ninemaidens));
	$ninebru = addslashes(trim($ninebowruns));
	$ninewic = addslashes(trim($ninewickets));
	$ninenob = addslashes(trim($ninenoballs));
	$ninewid = addslashes(trim($ninewides));

	$tenbow = addslashes(trim($tenbowler_id));
	$tenove = addslashes(trim($tenovers));
	$tenmai = addslashes(trim($tenmaidens));
	$tenbru = addslashes(trim($tenbowruns));
	$tenwic = addslashes(trim($tenwickets));
	$tennob = addslashes(trim($tennoballs));
	$tenwid = addslashes(trim($tenwides));

	$elevenbow = addslashes(trim($elevenbowler_id));
	$elevenove = addslashes(trim($elevenovers));
	$elevenmai = addslashes(trim($elevenmaidens));
	$elevenbru = addslashes(trim($elevenbowruns));
	$elevenwic = addslashes(trim($elevenwickets));
	$elevennob = addslashes(trim($elevennoballs));
	$elevenwid = addslashes(trim($elevenwides));

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
	

	$db->Insert("INSERT INTO scorecard_extras_details (game_id,innings_id,legbyes,byes,wides,noballs,total) VALUES ('$game_id','$innings_id','$extl','$extb','$extw','$extn','$extt')");
	$db->Insert("INSERT INTO scorecard_total_details (game_id,innings_id,team,wickets,total,overs) VALUES ('$game_id','$innings_id','$oneteam','$totw','$tott','$toto')");
	$db->Insert("INSERT INTO scorecard_fow_details (game_id,innings_id,fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10) VALUES ('$game_id','$innings_id','$f1','$f2','$f3','$f4','$f5','$f6','$f7','$f8','$f9','$f10')");	


	// check to see if there is an entry of batter

	if ($onepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$onepl','$onebap','$oneho','$oneru','$oneas','$onebo','$oneba','$onefo','$onesi','$oneno','$onetm','$oneopp')");
	if ($twopl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$twopl','$twobap','$twoho','$tworu','$twoas','$twobo','$twoba','$twofo','$twosi','$twono','$twotm','$twoopp')");
	if ($threepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$threepl','$threebap','$threeho','$threeru','$threeas','$threebo','$threeba','$threefo','$threesi','$threeno','$threetm','$threeopp')");
	if ($fourpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$fourpl','$fourbap','$fourho','$fourru','$fouras','$fourbo','$fourba','$fourfo','$foursi','$fourno','$fourtm','$fouropp')");
	if ($fivepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$fivepl','$fivebap','$fiveho','$fiveru','$fiveas','$fivebo','$fiveba','$fivefo','$fivesi','$fiveno','$fivetm','$fiveopp')");
	if ($sixpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$sixpl','$sixbap','$sixho','$sixru','$sixas','$sixbo','$sixba','$sixfo','$sixsi','$sixno','$sixtm','$sixopp')");
	if ($sevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$sevenpl','$sevenbap','$sevenho','$sevenru','$sevenas','$sevenbo','$sevenba','$sevenfo','$sevensi','$sevenno','$seventm','$sevenopp')");
	if ($eightpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$eightpl','$eightbap','$eightho','$eightru','$eightas','$eightbo','$eightba','$eightfo','$eightsi','$eightno','$eighttm','$eightopp')");
	if ($ninepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$ninepl','$ninebap','$nineho','$nineru','$nineas','$ninebo','$nineba','$ninefo','$ninesi','$nineno','$ninetm','$nineopp')");
	if ($tenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$tenpl','$tenbap','$tenho','$tenru','$tenas','$tenbo','$tenba','$tenfo','$tensi','$tenno','$tentm','$tenopp')");
	if ($elevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$elevenpl','$elevenbap','$elevenho','$elevenru','$elevenas','$elevenbo','$elevenba','$elevenfo','$elevensi','$elevenno','$eleventm','$elevenopp')");

	// check to see if there is an entry of bowler

	if ($onebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$onebow','$onebop','$oneove','$onemai','$onebru','$onewic','$onenob','$onewid','$bowlonetm','$bowloneopp')");
	if ($twobow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$twobow','$twobop','$twoove','$twomai','$twobru','$twowic','$twonob','$twowid','$bowltwotm','$bowltwoopp')");
	if ($threebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$threebow','$threebop','$threeove','$threemai','$threebru','$threewic','$threenob','$threewid','$bowlthreetm','$bowlthreeopp')");
	if ($fourbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$fourbow','$fourbop','$fourove','$fourmai','$fourbru','$fourwic','$fournob','$fourwid','$bowlfourtm','$bowlfouropp')");
	if ($fivebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$fivebow','$fivebop','$fiveove','$fivemai','$fivebru','$fivewic','$fivenob','$fivewid','$bowlfivetm','$bowlfiveopp')");
	if ($sixbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$sixbow','$sixbop','$sixove','$sixmai','$sixbru','$sixwic','$sixnob','$sixwid','$bowlsixtm','$bowlsixopp')");
	if ($sevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$sevenbow','$sevenbop','$sevenove','$sevenmai','$sevenbru','$sevenwic','$sevennob','$sevenwid','$bowlseventm','$bowlsevenopp')");
	if ($eightbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$eightbow','$eightbop','$eightove','$eightmai','$eightbru','$eightwic','$eightnob','$eightwid','$bowleighttm','$bowleightopp')");
	if ($ninebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$ninebow','$ninebop','$nineove','$ninemai','$ninebru','$ninewic','$ninenob','$ninewid','$bowlninetm','$bowlnineopp')");
	if ($tenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$tenbow','$tenbop','$tenove','$tenmai','$tenbru','$tenwic','$tennob','$tenwid','$bowltentm','$bowltenopp')");
	if ($elevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$elevenbow','$elevenbop','$elevenove','$elevenmai','$elevenbru','$elevenwic','$elevennob','$elevenwid','$bowleleventm','$bowlelevenopp')");

	header("Location: /submitold/index.php?game_id=$game_id&ccl_mode=4");
	ob_end_flush();

}

function add_scorecard_step3($db,$game_id,$season)
{
	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	$gid = addslashes(trim($game_id));
	$sid = addslashes(trim($season));
	
	if ($db->a_rows != -1) {

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
	  s.game_id = '$gid' 
	");

	$db->BagAndTag();

	$gid = $db->data['game_id'];
	$gsc = $db->data['season'];

	$b1  = $db->data[batting_first_id];
	$at  = $db->data[awayteam];
	$ht  = $db->data[hometeam];

	$ght = $db->data['homeabbrev'];
	$ghi = $db->data['homeid'];
	$gat = $db->data['awayabbrev'];
	$gai = $db->data['awayid'];
	$gut = $db->data['umpireabbrev'];
	$ggr = $db->data['GroundName'];
	$ggi = $db->data['GroundID'];
	$gre = $db->data['result'];
	$gtt = $db->data[WonTossAbbrev];

	$gda = sqldate_to_string($db->data['game_date']);

	$bat1st = $db->data['BatFirstAbbrev'];
	$bat1stid = $db->data['BatFirstID'];
	$bat2nd = $db->data['BatSecondAbbrev'];
	$bat2ndid = $db->data['BatSecondID'];



	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";

	echo "<p class=\"14px\">Step 3 - Enter 2nd Innings Details<br><img src=\"/images/66.gif\"></p>\n";
	
	echo "<p>You are working with <b>Game #$gid</b>, <b>$bat1st</b> vs <b>$bat2nd</b> on <b>$gda</b></p>\n";
	echo "<p align=\"left\"><b><font color=\"red\">IMPORTANT!</font></b>  If you are using <a href=\"http://www.getfirefox.com\" target=\"_new\">Firefox</a> then you may <a href=\"addplayer.php\" target=\"_new\">add new players</a> at any time. Please REFRESH the page once adding, Firefox should remember your selections. If you have another browser, check the drop-down menu's first to make sure that all players exist. Other browsers may not remember form selection data.</p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Enter Team Batting 2nd Details - $bat1st</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";
	
	echo "<form action=\"/submitold/index.php?ccl_mode=5\" method=\"post\" enctype=\"multipart/form-data\" name=\"comboForm\">\n";
	
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

	echo " <tr>\n";
	
	echo "  <td width=\"70%\" colspan=\"4\">&nbsp;</td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Runs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Balls</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>4s</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>6s</b></td>\n";
	
	echo " </tr>\n";

////////////////////////////////////////////////////////////////////////////////////////////
//                                1st Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneplayer_id\" id=\"combobox1\">\n";
	echo "	<option value=\"\">1st Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"oneassist\" id=\"combobox2\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"onebowler\" id=\"combobox3\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"oneruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"oneballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoplayer_id\" id=\"combobox4\">\n";
	echo "	<option value=\"\">2nd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twoassist\" id=\"combobox5\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"twobowler\" id=\"combobox6\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tworuns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twofours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twosixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeplayer_id\" id=\"combobox7\">\n";
	echo "	<option value=\"\">3rd Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threeassist\" id=\"combobox8\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"threebowler\" id=\"combobox9\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threeruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threeballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourplayer_id\" id=\"combobox10\">\n";
	echo "	<option value=\"\">4th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourassist\" id=\"combobox11\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fourbowler\" id=\"combobox12\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"foursixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveplayer_id\" id=\"combobox13\">\n";
	echo "	<option value=\"\">5th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fiveassist\" id=\"combobox14\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"fivebowler\" id=\"combobox15\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fiveruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fiveballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixplayer_id\" id=\"combobox16\">\n";
	echo "	<option value=\"\">6th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixassist\" id=\"combobox17\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sixbowler\" id=\"combobox18\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixsixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenplayer_id\" id=\"combobox19\">\n";
	echo "	<option value=\"\">7th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenassist\" id=\"combobox20\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"sevenbowler\" id=\"combobox21\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevensixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightplayer_id\" id=\"combobox22\">\n";
	echo "	<option value=\"\">8th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightassist\" id=\"combobox23\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"eightbowler\" id=\"combobox24\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightsixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Bastman Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineplayer_id\" id=\"combobox25\">\n";
	echo "	<option value=\"\">9th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"nineassist\" id=\"combobox26\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"ninebowler\" id=\"combobox27\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"nineruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"nineballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninefours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninesixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Bastman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenplayer_id\" id=\"combobox28\">\n";
	echo "	<option value=\"\">10th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenassist\" id=\"combobox29\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"tenbowler\" id=\"combobox30\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tensixes\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Bastman Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenplayer_id\" id=\"combobox31\">\n";
	echo "	<option value=\"\">11th Batsman</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
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
			echo "<option value=\"" . $db->data['HowOutID'] . "\">" . $db->data[HowOutName] . "</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenassist\" id=\"combobox32\">\n";
	echo "	<option value=\"\">Assist</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"20%\" align=\"left\">";
	echo "  <select name=\"elevenbowler\" id=\"combobox33\">\n";
	echo "	<option value=\"\">Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenfours\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"7%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevensixes\" size=\"5\" maxlength=\"7\">\n";
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
	echo "  <td width=\"8%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Legbyes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Byes</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Wides</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Noballs</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><b>Total</b></td>\n";
	
	echo " </tr>\n";
	echo " <tr>\n";
	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totwickets\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"totovers\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"tottotal\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"28%\" align=\"right\"><b>&nbsp;</b></td>\n";
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extlegbyes\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extbyes\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extwides\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"extnoballs\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"8%\" align=\"right\"><input type=\"text\" name=\"exttotal\" size=\"5\" maxlength=\"7\"></td>\n";	
	
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
	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowone\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowtwo\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowthree\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfour\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowfive\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowsix\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowseven\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"foweight\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fownine\" size=\"5\" maxlength=\"7\"></td>\n";	
	echo "  <td width=\"10%\" align=\"right\"><input type=\"text\" name=\"fowten\" size=\"5\" maxlength=\"7\"></td>\n";	
	
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
	echo "  <select name=\"onebowler_id\" id=\"combobox34\">\n";
	echo "	<option value=\"\">1st Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"oneovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"onewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	

////////////////////////////////////////////////////////////////////////////////////////////
//                                2nd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"twobowler_id\" id=\"combobox35\">\n";
	echo "	<option value=\"\">2nd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twoovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twomaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twobowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twowickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twonoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"twowides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                3rd Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"threebowler_id\" id=\"combobox36\">\n";
	echo "	<option value=\"\">3rd Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threeovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"threewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                4th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fourbowler_id\" id=\"combobox37\">\n";
	echo "	<option value=\"\">4th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fournoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fourwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                5th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"fivebowler_id\" id=\"combobox38\">\n";
	echo "	<option value=\"\">5th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fiveovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"fivewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                6th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sixbowler_id\" id=\"combobox39\">\n";
	echo "	<option value=\"\">6th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixnoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sixwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                7th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"sevenbowler_id\" id=\"combobox40\">\n";
	echo "	<option value=\"\">7th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevennoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"sevenwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                8th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"eightbowler_id\" id=\"combobox41\">\n";
	echo "	<option value=\"\">8th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightnoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"eightwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                9th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"ninebowler_id\" id=\"combobox42\">\n";
	echo "	<option value=\"\">9th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"nineovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninemaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninebowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninewickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninenoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"ninewides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                10th Bowler Details                                     //
////////////////////////////////////////////////////////////////////////////////////////////

	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"tenbowler_id\" id=\"combobox43\">\n";
	echo "	<option value=\"\">10th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tennoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"tenwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";
	
////////////////////////////////////////////////////////////////////////////////////////////
//                                11th Bowler Details                                    //
////////////////////////////////////////////////////////////////////////////////////////////


	echo "<tr>\n";	
	echo "  <td width=\"52%\" align=\"left\">";
	echo "  <select name=\"elevenbowler_id\" id=\"combobox44\">\n";
	echo "	<option value=\"\">11th Bowler</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerFName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerFName'] . " " . $db->data['PlayerLName'] . " (" . $db->data['PlayerTeam'] . ")</option>\n";
		}
	}
	echo "</select>\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenovers\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenmaidens\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenbowruns\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenwickets\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevennoballs\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";
	echo "  <td width=\"8%\" align=\"right\">";
	echo "  <input type=\"text\" name=\"elevenwides\" size=\"5\" maxlength=\"7\">\n";
	echo "  </td>\n";	
	echo " </tr>\n";	
	
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<input type=\"submit\" value=\"submit\">&nbsp;<input type=\"reset\" value=\"reset\">\n";
	
	echo "</form>\n";

	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";

	
	
	} else {
		echo "<p class=\"12pt\"><b>Uh oh!</b></p>\n";
		echo "<p>There was a problem adding the game details, please try again.</p>\n";
	}
}



function insert_scorecard_step3($db,$game_id,$season,$innings_id,$oneplayer_id,$onehow_out,$oneassist,$onebowler,$oneruns,$oneballs,$onefours,$onesixes,$twoplayer_id,$twohow_out,$twoassist,$twobowler,$tworuns,$twoballs,$twofours,$twosixes,$threeplayer_id,$threehow_out,$threeassist,$threebowler,$threeruns,$threeballs,$threefours,$threesixes,$fourplayer_id,$fourhow_out,$fourassist,$fourbowler,$fourruns,$fourballs,$fourfours,$foursixes,$fiveplayer_id,$fivehow_out,$fiveassist,$fivebowler,$fiveruns,$fiveballs,$fivefours,$fivesixes,$sixplayer_id,$sixhow_out,$sixassist,$sixbowler,$sixruns,$sixballs,$sixfours,$sixsixes,$sevenplayer_id,$sevenhow_out,$sevenassist,$sevenbowler,$sevenruns,$sevenballs,$sevenfours,$sevensixes,$eightplayer_id,$eighthow_out,$eightassist,$eightbowler,$eightruns,$eightballs,$eightfours,$eightsixes,$nineplayer_id,$ninehow_out,$nineassist,$ninebowler,$nineruns,$nineballs,$ninefours,$ninesixes,$tenplayer_id,$tenhow_out,$tenassist,$tenbowler,$tenruns,$tenballs,$tenfours,$tensixes,$elevenplayer_id,$elevenhow_out,$elevenassist,$elevenbowler,$elevenruns,$elevenballs,$elevenfours,$elevensixes,$totwickets,$totovers,$tottotal,$extlegbyes,$extbyes,$extwides,$extnoballs,$exttotal,$fowone,$fowtwo,$fowthree,$fowfour,$fowfive,$fowsix,$fowseven,$foweight,$fownine,$fowten,$onebowler_id,$oneovers,$onemaidens,$onebowruns,$onewickets,$onenoballs,$onewides,$twobowler_id,$twoovers,$twomaidens,$twobowruns,$twowickets,$twonoballs,$twowides,$threebowler_id,$threeovers,$threemaidens,$threebowruns,$threewickets,$threenoballs,$threewides,$fourbowler_id,$fourovers,$fourmaidens,$fourbowruns,$fourwickets,$fournoballs,$fourwides,$fivebowler_id,$fiveovers,$fivemaidens,$fivebowruns,$fivewickets,$fivenoballs,$fivewides,$sixbowler_id,$sixovers,$sixmaidens,$sixbowruns,$sixwickets,$sixnoballs,$sixwides,$sevenbowler_id,$sevenovers,$sevenmaidens,$sevenbowruns,$sevenwickets,$sevennoballs,$sevenwides,$eightbowler_id,$eightovers,$eightmaidens,$eightbowruns,$eightwickets,$eightnoballs,$eightwides,$ninebowler_id,$nineovers,$ninemaidens,$ninebowruns,$ninewickets,$ninenoballs,$ninewides,$tenbowler_id,$tenovers,$tenmaidens,$tenbowruns,$tenwickets,$tennoballs,$tenwides,$elevenbowler_id,$elevenovers,$elevenmaidens,$elevenbowruns,$elevenwickets,$elevennoballs,$elevenwides,$onebatpos,$twobatpos,$threebatpos,$fourbatpos,$fivebatpos,$sixbatpos,$sevenbatpos,$eightbatpos,$ninebatpos,$tenbatpos,$elevenbatpos,$onebowpos,$twobowpos,$threebowpos,$fourbowpos,$fivebowpos,$sixbowpos,$sevenbowpos,$eightbowpos,$ninebowpos,$tenbowpos,$elevenbowpos,$oneteam,$twoteam,$threeteam,$fourteam,$fiveteam,$sixteam,$seventeam,$eightteam,$nineteam,$tenteam,$eleventeam,$oneopponent,$twoopponent,$threeopponent,$fouropponent,$fiveopponent,$sixopponent,$sevenopponent,$eightopponent,$nineopponent,$tenopponent,$elevenopponent,$bowloneteam,$bowltwoteam,$bowlthreeteam,$bowlfourteam,$bowlfiveteam,$bowlsixteam,$bowlseventeam,$bowleightteam,$bowlnineteam,$bowltenteam,$bowleleventeam,$bowloneopponent,$bowltwoopponent,$bowlthreeopponent,$bowlfouropponent,$bowlfiveopponent,$bowlsixopponent,$bowlsevenopponent,$bowleightopponent,$bowlnineopponent,$bowltenopponent,$bowlelevenopponent)
{
	global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	// make sure info is present and correct

	//if ($totwickets == "" || $totovers || $tottotal) {
	//	echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
	//	echo "<p>You must complete all the required (*) fields. Please go back and try again.</p>\n";
	//	return;
	//}

	// setup variables

	$game_id = addslashes(trim($game_id));
	$innings_id = addslashes(trim($innings_id));
	$season = addslashes(trim($season));
	
	$onepl = addslashes(trim($oneplayer_id));
	$oneho = addslashes(trim($onehow_out));
	$oneas = addslashes(trim($oneassist));
	$onebo = addslashes(trim($onebowler));
	$oneru = addslashes(trim($oneruns));
	$oneba = addslashes(trim($oneballs));
	$onefo = addslashes(trim($onefours));
	$onesi = addslashes(trim($onesixes));
	if($onehow_out != "2" && $onehow_out != "8") { $oneno == "0"; } else { $oneno = "1"; }

	$twopl = addslashes(trim($twoplayer_id));
	$twoho = addslashes(trim($twohow_out));
	$twoas = addslashes(trim($twoassist));
	$twobo = addslashes(trim($twobowler));
	$tworu = addslashes(trim($tworuns));
	$twoba = addslashes(trim($twoballs));
	$twofo = addslashes(trim($twofours));
	$twosi = addslashes(trim($twosixes));
	if($twohow_out != "2" && $twohow_out != "8") { $twono == "0"; } else { $twono = "1"; }

	$threepl = addslashes(trim($threeplayer_id));
	$threeho = addslashes(trim($threehow_out));
	$threeas = addslashes(trim($threeassist));
	$threebo = addslashes(trim($threebowler));
	$threeru = addslashes(trim($threeruns));
	$threeba = addslashes(trim($threeballs));
	$threefo = addslashes(trim($threefours));
	$threesi = addslashes(trim($threesixes));
	if($threehow_out != "2" && $threehow_out != "8") { $threeno == "0"; } else { $threeno = "1"; }

	$fourpl = addslashes(trim($fourplayer_id));
	$fourho = addslashes(trim($fourhow_out));
	$fouras = addslashes(trim($fourassist));
	$fourbo = addslashes(trim($fourbowler));
	$fourru = addslashes(trim($fourruns));
	$fourba = addslashes(trim($fourballs));
	$fourfo = addslashes(trim($fourfours));
	$foursi = addslashes(trim($foursixes));
	if($fourhow_out != "2" && $fourhow_out != "8") { $fourno == "0"; } else { $fourno = "1"; }

	$fivepl = addslashes(trim($fiveplayer_id));
	$fiveho = addslashes(trim($fivehow_out));
	$fiveas = addslashes(trim($fiveassist));
	$fivebo = addslashes(trim($fivebowler));
	$fiveru = addslashes(trim($fiveruns));
	$fiveba = addslashes(trim($fiveballs));
	$fivefo = addslashes(trim($fivefours));
	$fivesi = addslashes(trim($fivesixes));
	if($fivehow_out != "2" && $fivehow_out != "8") { $fiveno == "0"; } else { $fiveno = "1"; }

	$sixpl = addslashes(trim($sixplayer_id));
	$sixho = addslashes(trim($sixhow_out));
	$sixas = addslashes(trim($sixassist));
	$sixbo = addslashes(trim($sixbowler));
	$sixru = addslashes(trim($sixruns));
	$sixba = addslashes(trim($sixballs));
	$sixfo = addslashes(trim($sixfours));
	$sixsi = addslashes(trim($sixsixes));
	if($sixhow_out != "2" && $sixhow_out != "8") { $sixno == "0"; } else { $sixno = "1"; }

	$sevenpl = addslashes(trim($sevenplayer_id));
	$sevenho = addslashes(trim($sevenhow_out));
	$sevenas = addslashes(trim($sevenassist));
	$sevenbo = addslashes(trim($sevenbowler));
	$sevenru = addslashes(trim($sevenruns));
	$sevenba = addslashes(trim($sevenballs));
	$sevenfo = addslashes(trim($sevenfours));
	$sevensi = addslashes(trim($sevensixes));
	if($sevenhow_out != "2" && $sevenhow_out != "8") { $sevenno == "0"; } else { $sevenno = "1"; }

	$eightpl = addslashes(trim($eightplayer_id));
	$eightho = addslashes(trim($eighthow_out));
	$eightas = addslashes(trim($eightassist));
	$eightbo = addslashes(trim($eightbowler));
	$eightru = addslashes(trim($eightruns));
	$eightba = addslashes(trim($eightballs));
	$eightfo = addslashes(trim($eightfours));
	$eightsi = addslashes(trim($eightsixes));
	if($eighthow_out != "2" && $eighthow_out != "8") { $eightno == "0"; } else { $eightno = "1"; }

	$ninepl = addslashes(trim($nineplayer_id));
	$nineho = addslashes(trim($ninehow_out));
	$nineas = addslashes(trim($nineassist));
	$ninebo = addslashes(trim($ninebowler));
	$nineru = addslashes(trim($nineruns));
	$nineba = addslashes(trim($nineballs));
	$ninefo = addslashes(trim($ninefours));
	$ninesi = addslashes(trim($ninesixes));
	if($ninehow_out != "2" && $ninehow_out != "8") { $nineno == "0"; } else { $nineno = "1"; }

	$tenpl = addslashes(trim($tenplayer_id));
	$tenho = addslashes(trim($tenhow_out));
	$tenas = addslashes(trim($tenassist));
	$tenbo = addslashes(trim($tenbowler));
	$tenru = addslashes(trim($tenruns));
	$tenba = addslashes(trim($tenballs));
	$tenfo = addslashes(trim($tenfours));
	$tensi = addslashes(trim($tensixes));
	if($tenhow_out != "2" && $tenhow_out != "8") { $tenno == "0"; } else { $tenno = "1"; }

	$elevenpl = addslashes(trim($elevenplayer_id));
	$elevenho = addslashes(trim($elevenhow_out));
	$elevenas = addslashes(trim($elevenassist));
	$elevenbo = addslashes(trim($elevenbowler));
	$elevenru = addslashes(trim($elevenruns));
	$elevenba = addslashes(trim($elevenballs));
	$elevenfo = addslashes(trim($elevenfours));
	$elevensi = addslashes(trim($elevensixes));
	if($elevenhow_out != "2" && $elevenhow_out != "8") { $elevenno == "0"; } else { $elevenno = "1"; }

	$totw = addslashes(trim($totwickets));
	$toto = addslashes(trim($totovers));
	$tott = addslashes(trim($tottotal));
	
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

	$twobow = addslashes(trim($twobowler_id));
	$twoove = addslashes(trim($twoovers));
	$twomai = addslashes(trim($twomaidens));
	$twobru = addslashes(trim($twobowruns));
	$twowic = addslashes(trim($twowickets));
	$twonob = addslashes(trim($twonoballs));
	$twowid = addslashes(trim($twowides));

	$threebow = addslashes(trim($threebowler_id));
	$threeove = addslashes(trim($threeovers));
	$threemai = addslashes(trim($threemaidens));
	$threebru = addslashes(trim($threebowruns));
	$threewic = addslashes(trim($threewickets));
	$threenob = addslashes(trim($threenoballs));
	$threewid = addslashes(trim($threewides));

	$fourbow = addslashes(trim($fourbowler_id));
	$fourove = addslashes(trim($fourovers));
	$fourmai = addslashes(trim($fourmaidens));
	$fourbru = addslashes(trim($fourbowruns));
	$fourwic = addslashes(trim($fourwickets));
	$fournob = addslashes(trim($fournoballs));
	$fourwid = addslashes(trim($fourwides));

	$fivebow = addslashes(trim($fivebowler_id));
	$fiveove = addslashes(trim($fiveovers));
	$fivemai = addslashes(trim($fivemaidens));
	$fivebru = addslashes(trim($fivebowruns));
	$fivewic = addslashes(trim($fivewickets));
	$fivenob = addslashes(trim($fivenoballs));
	$fivewid = addslashes(trim($fivewides));

	$sixbow = addslashes(trim($sixbowler_id));
	$sixove = addslashes(trim($sixovers));
	$sixmai = addslashes(trim($sixmaidens));
	$sixbru = addslashes(trim($sixbowruns));
	$sixwic = addslashes(trim($sixwickets));
	$sixnob = addslashes(trim($sixnoballs));
	$sixwid = addslashes(trim($sixwides));

	$sevenbow = addslashes(trim($sevenbowler_id));
	$sevenove = addslashes(trim($sevenovers));
	$sevenmai = addslashes(trim($sevenmaidens));
	$sevenbru = addslashes(trim($sevenbowruns));
	$sevenwic = addslashes(trim($sevenwickets));
	$sevennob = addslashes(trim($sevennoballs));
	$sevenwid = addslashes(trim($sevenwides));

	$eightbow = addslashes(trim($eightbowler_id));
	$eightove = addslashes(trim($eightovers));
	$eightmai = addslashes(trim($eightmaidens));
	$eightbru = addslashes(trim($eightbowruns));
	$eightwic = addslashes(trim($eightwickets));
	$eightnob = addslashes(trim($eightnoballs));
	$eightwid = addslashes(trim($eightwides));

	$ninebow = addslashes(trim($ninebowler_id));
	$nineove = addslashes(trim($nineovers));
	$ninemai = addslashes(trim($ninemaidens));
	$ninebru = addslashes(trim($ninebowruns));
	$ninewic = addslashes(trim($ninewickets));
	$ninenob = addslashes(trim($ninenoballs));
	$ninewid = addslashes(trim($ninewides));

	$tenbow = addslashes(trim($tenbowler_id));
	$tenove = addslashes(trim($tenovers));
	$tenmai = addslashes(trim($tenmaidens));
	$tenbru = addslashes(trim($tenbowruns));
	$tenwic = addslashes(trim($tenwickets));
	$tennob = addslashes(trim($tennoballs));
	$tenwid = addslashes(trim($tenwides));

	$elevenbow = addslashes(trim($elevenbowler_id));
	$elevenove = addslashes(trim($elevenovers));
	$elevenmai = addslashes(trim($elevenmaidens));
	$elevenbru = addslashes(trim($elevenbowruns));
	$elevenwic = addslashes(trim($elevenwickets));
	$elevennob = addslashes(trim($elevennoballs));
	$elevenwid = addslashes(trim($elevenwides));

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
	
	
	$db->Insert("INSERT INTO scorecard_extras_details (game_id,innings_id,legbyes,byes,wides,noballs,total) VALUES ('$game_id','$innings_id','$extl','$extb','$extw','$extn','$extt')");
	$db->Insert("INSERT INTO scorecard_total_details (game_id,innings_id,team,wickets,total,overs) VALUES ('$game_id','$innings_id','$oneteam','$totw','$tott','$toto')");
	$db->Insert("INSERT INTO scorecard_fow_details (game_id,innings_id,fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10) VALUES ('$game_id','$innings_id','$f1','$f2','$f3','$f4','$f5','$f6','$f7','$f8','$f9','$f10')");	


	// check to see if there is an entry of batter

	if ($onepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$onepl','$onebap','$oneho','$oneru','$oneas','$onebo','$oneba','$onefo','$onesi','$oneno','$onetm','$oneopp')");
	if ($twopl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$twopl','$twobap','$twoho','$tworu','$twoas','$twobo','$twoba','$twofo','$twosi','$twono','$twotm','$twoopp')");
	if ($threepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$threepl','$threebap','$threeho','$threeru','$threeas','$threebo','$threeba','$threefo','$threesi','$threeno','$threetm','$threeopp')");
	if ($fourpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$fourpl','$fourbap','$fourho','$fourru','$fouras','$fourbo','$fourba','$fourfo','$foursi','$fourno','$fourtm','$fouropp')");
	if ($fivepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$fivepl','$fivebap','$fiveho','$fiveru','$fiveas','$fivebo','$fiveba','$fivefo','$fivesi','$fiveno','$fivetm','$fiveopp')");
	if ($sixpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$sixpl','$sixbap','$sixho','$sixru','$sixas','$sixbo','$sixba','$sixfo','$sixsi','$sixno','$sixtm','$sixopp')");
	if ($sevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$sevenpl','$sevenbap','$sevenho','$sevenru','$sevenas','$sevenbo','$sevenba','$sevenfo','$sevensi','$sevenno','$seventm','$sevenopp')");
	if ($eightpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$eightpl','$eightbap','$eightho','$eightru','$eightas','$eightbo','$eightba','$eightfo','$eightsi','$eightno','$eighttm','$eightopp')");
	if ($ninepl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$ninepl','$ninebap','$nineho','$nineru','$nineas','$ninebo','$nineba','$ninefo','$ninesi','$nineno','$ninetm','$nineopp')");
	if ($tenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$tenpl','$tenbap','$tenho','$tenru','$tenas','$tenbo','$tenba','$tenfo','$tensi','$tenno','$tentm','$tenopp')");
	if ($elevenpl != "") 	$db->Insert("INSERT INTO scorecard_batting_details (game_id,season,innings_id,player_id,batting_position,how_out,runs,assist,bowler,balls,fours,sixes,notout,team,opponent) VALUES ('$game_id','$season','$innings_id','$elevenpl','$elevenbap','$elevenho','$elevenru','$elevenas','$elevenbo','$elevenba','$elevenfo','$elevensi','$elevenno','$eleventm','$elevenopp')");

	// check to see if there is an entry of bowler

	if ($onebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$onebow','$onebop','$oneove','$onemai','$onebru','$onewic','$onenob','$onewid','$bowlonetm','$bowloneopp')");
	if ($twobow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$twobow','$twobop','$twoove','$twomai','$twobru','$twowic','$twonob','$twowid','$bowltwotm','$bowltwoopp')");
	if ($threebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$threebow','$threebop','$threeove','$threemai','$threebru','$threewic','$threenob','$threewid','$bowlthreetm','$bowlthreeopp')");
	if ($fourbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$fourbow','$fourbop','$fourove','$fourmai','$fourbru','$fourwic','$fournob','$fourwid','$bowlfourtm','$bowlfouropp')");
	if ($fivebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$fivebow','$fivebop','$fiveove','$fivemai','$fivebru','$fivewic','$fivenob','$fivewid','$bowlfivetm','$bowlfiveopp')");
	if ($sixbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$sixbow','$sixbop','$sixove','$sixmai','$sixbru','$sixwic','$sixnob','$sixwid','$bowlsixtm','$bowlsixopp')");
	if ($sevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$sevenbow','$sevenbop','$sevenove','$sevenmai','$sevenbru','$sevenwic','$sevennob','$sevenwid','$bowlseventm','$bowlsevenopp')");
	if ($eightbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$eightbow','$eightbop','$eightove','$eightmai','$eightbru','$eightwic','$eightnob','$eightwid','$bowleighttm','$bowleightopp')");
	if ($ninebow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$ninebow','$ninebop','$nineove','$ninemai','$ninebru','$ninewic','$ninenob','$ninewid','$bowlninetm','$bowlnineopp')");
	if ($tenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$tenbow','$tenbop','$tenove','$tenmai','$tenbru','$tenwic','$tennob','$tenwid','$bowltentm','$bowltenopp')");
	if ($elevenbow != "") 	$db->Insert("INSERT INTO scorecard_bowling_details (game_id,season,innings_id,player_id,bowling_position,overs,maidens,runs,wickets,noballs,wides,team,opponent) VALUES ('$game_id','$season','$innings_id','$elevenbow','$elevenbop','$elevenove','$elevenmai','$elevenbru','$elevenwic','$elevennob','$elevenwid','$bowleleventm','$bowlelevenopp')");
	
	header("Location: /submitold/index.php?ccl_mode=6");
	ob_end_flush();

}

function finished($db)
{
	global $PHP_SELF, $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

	if ($db->a_rows != -1) {

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

	echo "<p>You have now submitted a scorecard for approval. Thanks!</p>\n";
	echo "<p>&laquo; <a href=\"/\">return to colorado cricket</a></p>\n";
	echo "<p>&raquo; <a href=\"/submitold/\">add another scorecard</a></p>\n";

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
		

	} else {

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";

	echo "<p class=\"14px\">Step 3 - Failure!<br><img src=\"/images/66.gif\"></p>\n";
	
	echo "<p>You are working with <b>Game #$gid</b>, <b>$bat1st</b> vs <b>$bat2nd</b> on <b>$gda</b></p>\n";
	echo "<p align=\"left\">Team Batting 1st: $bat1stid $bat1st<br>\n";
	echo "Team Batting 2nd: $bat2ndid $bat2nd</p>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "<tr>\n";
    	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Failure!</td>\n";
    	echo "</tr>\n";
    	echo "<tr>\n";
	echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	echo "<p>There was a problem! <a href=\"/submit\">Please try again.</a></p>\n";

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
}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



switch($ccl_mode) {
case 0:
	add_scorecard_step1($db);
	break;
case 1:
	insert_scorecard_step1($db,$league_id,$season,$week,$awayteam,$hometeam,$umpires,$toss_won_id,$result_won_id,$batting_first_id,$batting_second_id,$ground_id,$game_date,$result,$tied,$forfeit,$cancelled,$cancelledplay,$mom,$maxovers);
	break;
case 2:
	add_scorecard_step2($db,$season,$game_date,$awayteam,$hometeam);
	break;
case 3:
	insert_scorecard_step2($db,$game_id,$season,$innings_id,$oneplayer_id,$onehow_out,$oneassist,$onebowler,$oneruns,$oneballs,$onefours,$onesixes,$twoplayer_id,$twohow_out,$twoassist,$twobowler,$tworuns,$twoballs,$twofours,$twosixes,$threeplayer_id,$threehow_out,$threeassist,$threebowler,$threeruns,$threeballs,$threefours,$threesixes,$fourplayer_id,$fourhow_out,$fourassist,$fourbowler,$fourruns,$fourballs,$fourfours,$foursixes,$fiveplayer_id,$fivehow_out,$fiveassist,$fivebowler,$fiveruns,$fiveballs,$fivefours,$fivesixes,$sixplayer_id,$sixhow_out,$sixassist,$sixbowler,$sixruns,$sixballs,$sixfours,$sixsixes,$sevenplayer_id,$sevenhow_out,$sevenassist,$sevenbowler,$sevenruns,$sevenballs,$sevenfours,$sevensixes,$eightplayer_id,$eighthow_out,$eightassist,$eightbowler,$eightruns,$eightballs,$eightfours,$eightsixes,$nineplayer_id,$ninehow_out,$nineassist,$ninebowler,$nineruns,$nineballs,$ninefours,$ninesixes,$tenplayer_id,$tenhow_out,$tenassist,$tenbowler,$tenruns,$tenballs,$tenfours,$tensixes,$elevenplayer_id,$elevenhow_out,$elevenassist,$elevenbowler,$elevenruns,$elevenballs,$elevenfours,$elevensixes,$totwickets,$totovers,$tottotal,$extlegbyes,$extbyes,$extwides,$extnoballs,$exttotal,$fowone,$fowtwo,$fowthree,$fowfour,$fowfive,$fowsix,$fowseven,$foweight,$fownine,$fowten,$onebowler_id,$oneovers,$onemaidens,$onebowruns,$onewickets,$onenoballs,$onewides,$twobowler_id,$twoovers,$twomaidens,$twobowruns,$twowickets,$twonoballs,$twowides,$threebowler_id,$threeovers,$threemaidens,$threebowruns,$threewickets,$threenoballs,$threewides,$fourbowler_id,$fourovers,$fourmaidens,$fourbowruns,$fourwickets,$fournoballs,$fourwides,$fivebowler_id,$fiveovers,$fivemaidens,$fivebowruns,$fivewickets,$fivenoballs,$fivewides,$sixbowler_id,$sixovers,$sixmaidens,$sixbowruns,$sixwickets,$sixnoballs,$sixwides,$sevenbowler_id,$sevenovers,$sevenmaidens,$sevenbowruns,$sevenwickets,$sevennoballs,$sevenwides,$eightbowler_id,$eightovers,$eightmaidens,$eightbowruns,$eightwickets,$eightnoballs,$eightwides,$ninebowler_id,$nineovers,$ninemaidens,$ninebowruns,$ninewickets,$ninenoballs,$ninewides,$tenbowler_id,$tenovers,$tenmaidens,$tenbowruns,$tenwickets,$tennoballs,$tenwides,$elevenbowler_id,$elevenovers,$elevenmaidens,$elevenbowruns,$elevenwickets,$elevennoballs,$elevenwides,$onebatpos,$twobatpos,$threebatpos,$fourbatpos,$fivebatpos,$sixbatpos,$sevenbatpos,$eightbatpos,$ninebatpos,$tenbatpos,$elevenbatpos,$onebowpos,$twobowpos,$threebowpos,$fourbowpos,$fivebowpos,$sixbowpos,$sevenbowpos,$eightbowpos,$ninebowpos,$tenbowpos,$elevenbowpos,$oneteam,$twoteam,$threeteam,$fourteam,$fiveteam,$sixteam,$seventeam,$eightteam,$nineteam,$tenteam,$eleventeam,$oneopponent,$twoopponent,$threeopponent,$fouropponent,$fiveopponent,$sixopponent,$sevenopponent,$eightopponent,$nineopponent,$tenopponent,$elevenopponent,$bowloneteam,$bowltwoteam,$bowlthreeteam,$bowlfourteam,$bowlfiveteam,$bowlsixteam,$bowlseventeam,$bowleightteam,$bowlnineteam,$bowltenteam,$bowleleventeam,$bowloneopponent,$bowltwoopponent,$bowlthreeopponent,$bowlfouropponent,$bowlfiveopponent,$bowlsixopponent,$bowlsevenopponent,$bowleightopponent,$bowlnineopponent,$bowltenopponent,$bowlelevenopponent);
	break;	
case 4:
	add_scorecard_step3($db,$game_id,$season);
	break;	
case 5:
	insert_scorecard_step3($db,$game_id,$season,$innings_id,$oneplayer_id,$onehow_out,$oneassist,$onebowler,$oneruns,$oneballs,$onefours,$onesixes,$twoplayer_id,$twohow_out,$twoassist,$twobowler,$tworuns,$twoballs,$twofours,$twosixes,$threeplayer_id,$threehow_out,$threeassist,$threebowler,$threeruns,$threeballs,$threefours,$threesixes,$fourplayer_id,$fourhow_out,$fourassist,$fourbowler,$fourruns,$fourballs,$fourfours,$foursixes,$fiveplayer_id,$fivehow_out,$fiveassist,$fivebowler,$fiveruns,$fiveballs,$fivefours,$fivesixes,$sixplayer_id,$sixhow_out,$sixassist,$sixbowler,$sixruns,$sixballs,$sixfours,$sixsixes,$sevenplayer_id,$sevenhow_out,$sevenassist,$sevenbowler,$sevenruns,$sevenballs,$sevenfours,$sevensixes,$eightplayer_id,$eighthow_out,$eightassist,$eightbowler,$eightruns,$eightballs,$eightfours,$eightsixes,$nineplayer_id,$ninehow_out,$nineassist,$ninebowler,$nineruns,$nineballs,$ninefours,$ninesixes,$tenplayer_id,$tenhow_out,$tenassist,$tenbowler,$tenruns,$tenballs,$tenfours,$tensixes,$elevenplayer_id,$elevenhow_out,$elevenassist,$elevenbowler,$elevenruns,$elevenballs,$elevenfours,$elevensixes,$totwickets,$totovers,$tottotal,$extlegbyes,$extbyes,$extwides,$extnoballs,$exttotal,$fowone,$fowtwo,$fowthree,$fowfour,$fowfive,$fowsix,$fowseven,$foweight,$fownine,$fowten,$onebowler_id,$oneovers,$onemaidens,$onebowruns,$onewickets,$onenoballs,$onewides,$twobowler_id,$twoovers,$twomaidens,$twobowruns,$twowickets,$twonoballs,$twowides,$threebowler_id,$threeovers,$threemaidens,$threebowruns,$threewickets,$threenoballs,$threewides,$fourbowler_id,$fourovers,$fourmaidens,$fourbowruns,$fourwickets,$fournoballs,$fourwides,$fivebowler_id,$fiveovers,$fivemaidens,$fivebowruns,$fivewickets,$fivenoballs,$fivewides,$sixbowler_id,$sixovers,$sixmaidens,$sixbowruns,$sixwickets,$sixnoballs,$sixwides,$sevenbowler_id,$sevenovers,$sevenmaidens,$sevenbowruns,$sevenwickets,$sevennoballs,$sevenwides,$eightbowler_id,$eightovers,$eightmaidens,$eightbowruns,$eightwickets,$eightnoballs,$eightwides,$ninebowler_id,$nineovers,$ninemaidens,$ninebowruns,$ninewickets,$ninenoballs,$ninewides,$tenbowler_id,$tenovers,$tenmaidens,$tenbowruns,$tenwickets,$tennoballs,$tenwides,$elevenbowler_id,$elevenovers,$elevenmaidens,$elevenbowruns,$elevenwickets,$elevennoballs,$elevenwides,$onebatpos,$twobatpos,$threebatpos,$fourbatpos,$fivebatpos,$sixbatpos,$sevenbatpos,$eightbatpos,$ninebatpos,$tenbatpos,$elevenbatpos,$onebowpos,$twobowpos,$threebowpos,$fourbowpos,$fivebowpos,$sixbowpos,$sevenbowpos,$eightbowpos,$ninebowpos,$tenbowpos,$elevenbowpos,$oneteam,$twoteam,$threeteam,$fourteam,$fiveteam,$sixteam,$seventeam,$eightteam,$nineteam,$tenteam,$eleventeam,$oneopponent,$twoopponent,$threeopponent,$fouropponent,$fiveopponent,$sixopponent,$sevenopponent,$eightopponent,$nineopponent,$tenopponent,$elevenopponent,$bowloneteam,$bowltwoteam,$bowlthreeteam,$bowlfourteam,$bowlfiveteam,$bowlsixteam,$bowlseventeam,$bowleightteam,$bowlnineteam,$bowltenteam,$bowleleventeam,$bowloneopponent,$bowltwoopponent,$bowlthreeopponent,$bowlfouropponent,$bowlfiveopponent,$bowlsixopponent,$bowlsevenopponent,$bowleightopponent,$bowlnineopponent,$bowltenopponent,$bowlelevenopponent);
	break;	
case 6:
	finished($db);
	break;
default:
	add_scorecard_step1($db);
	break;
}

?>