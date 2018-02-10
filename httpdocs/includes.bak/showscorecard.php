<?php

//------------------------------------------------------------------------------
// Colorado Cricket Documents v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_schedule_listing($db,$schedule,$id,$pr,$team,$week,$game_id)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC")) {
        $db->QueryRow("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Scorecards</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Scorecards</b><br><br>\n";


        // Scorecards Select Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEASON SELECTOR</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

            echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "    <option>select a season</option>\n";
        echo "    <option>===============</option>\n";

        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->BagAndTag();

            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];

            echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";

        }

        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        } else {

            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";
            echo "    <p>There are no schedules in the database.</p>\n";
            echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
    }
}



function show_schedule($db,$schedule,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Scorecards</font></p>\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

		//16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
            	//$db->Query("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
                $db->Query("SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
                }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";

            echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";
            echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/scorecard.php\">Scorecards</a> &raquo; <font class=\"10px\">{$seasons[$schedule]}</font></p>\n";
            echo "  </td>\n";
            //echo "  <td align=\"right\" valign=\"top\">\n";
            //require ("navtop.php");
            //echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


            echo "<b class=\"16px\">{$seasons[$schedule]} Scorecards</b><br><br>\n";

        // Scorecard Option Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        // List by team for scorecard

                echo "<p class=\"10px\">Team: ";
            	//16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
            	//$db->QueryRow("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 AND TeamActive=1 ORDER BY TeamName");
                $db->QueryRow("SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
                
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data['TeamID'];
                $ab = $db->data['TeamAbbrev'];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for scorecard

            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM scorecard_game_details WHERE season = $schedule AND (league_id = 1 OR league_id = 4) GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data['week'];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";
            }
            echo "</p>\n";

        // List by season for scorecard


            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID WHERE ga.league_id = 1 OR league_id = 4 GROUP BY ga.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data['SeasonName'];
                $sid = $db->data['season'];

            echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
            }
            echo "    </select></p>\n";


            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";


                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE SCORECARDS</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
            echo "</tr>\n";


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$schedule AND isactive=0 AND (league_id = 1 OR league_id = 4)")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No scorecards for this season.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("
            SELECT
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
              s.season=$schedule AND s.isactive=0 AND (s.league_id = 1 OR league_id = 4)
            ORDER BY
              s.game_date, s.game_id
            ");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data['homeabbrev'];
                $t2 = $db->data['awayabbrev'];
                $um = $db->data['umpireabbrev'];
                $t1id = $db->data['homeid'];
                $t2id = $db->data['awayid'];
                $umid = $db->data['umpireid'];
                $d = sqldate_to_string($db->data['game_date']);
                $sc =  $db->data['scorecard'];
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
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";

                if($fo == "0" && $ca == "0") {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                } else if($fo == "1" && $ca == "0") {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                  //echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
                } else if($ca == "1" && $fo = "1") {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">Game cancelled</a></td>\n";
                } else {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                }


                echo "</tr>\n";
                }
        }
                        echo "</table>\n";

                        echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";

            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

        }
}




function show_schedule_team($db,$schedule,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &gt; Scorecards</p>\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

            	//16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
            	//$db->Query("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
                $db->Query("SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
                
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
                        $teamcolour[$db->data['TeamID']] = $db->data['TeamColour'];
                }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";

            echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";
            echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/scorecard.php\">Scorecards</a> &raquo; <font class=\"10px\">{$seasons[$schedule]}</font></p>\n";
            echo "  </td>\n";
            //echo "  <td align=\"right\" valign=\"top\">\n";
            //require ("navtop.php");
            //echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


            echo "<b class=\"16px\">{$seasons[$schedule]} Scorecards for {$teams[$team]}</b><br><br>\n";

        // Scorecard Option Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        // List by team for scorecard

                echo "<p class=\"10px\">Team: ";
		//16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
            	//$db->QueryRow("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 AND TeamActive=1 ORDER BY TeamName");
		$db->QueryRow("SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data['TeamID'];
                $ab = $db->data['TeamAbbrev'];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for scorecard

            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM scorecard_game_details WHERE season = $schedule AND (league_id = 1 OR league_id = 4) GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data['week'];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";
            }
            echo "</p>\n";

        // List by season for scorecard


            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID  WHERE ga.league_id = 1 OR league_id = 4 GROUP BY ga.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data['SeasonName'];
                $sid = $db->data['season'];

            echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
            }
            echo "    </select></p>\n";


            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";


                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE SCORECARDS</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
            echo "</tr>\n";


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$schedule AND (league_id = 1 OR league_id = 4)")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {

            $db->Query("
            SELECT
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
              s.season=$schedule AND (s.league_id = 1 OR league_id = 4)
            AND
              (s.awayteam=$team OR s.hometeam=$team)
            ORDER BY
              s.week, s.game_date, s.game_id
            ");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data['homeabbrev'];
                $t2 = $db->data['awayabbrev'];
                $um = $db->data['umpireabbrev'];
                $t1id = $db->data['homeid'];
                $t2id = $db->data['awayid'];
                $umid = $db->data['umpireid'];
                $d = sqldate_to_string($db->data['game_date']);
                $sc =  $db->data['scorecard'];
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
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";


                if($fo == "0" && $ca == "0") {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                } else if($fo == "1") {
                  echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
                } else if($ca == "1") {
                  echo "  <td align=\"left\" class=\"9px\">Game Cancelled</td>\n";
                } else {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                }


                echo "</tr>\n";
                }
        }
                        echo "</table>\n";

                        echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";

            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

        }
}


function show_schedule_week($db,$schedule,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Scorecards</font></p>\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT week FROM scorecard_game_details WHERE (league_id = 1 OR league_id = 4) GROUP BY week");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $weeks[$db->data['week']] = $db->data['week'];
                }

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

            	//16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
            	//$db->Query("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
                $db->Query("SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");

                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
                }

            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";

            echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";
            echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/scorecard.php\">Scorecards</a> &raquo; <font class=\"10px\">{$seasons[$schedule]}</font></p>\n";
            echo "  </td>\n";
            //echo "  <td align=\"right\" valign=\"top\">\n";
            //require ("navtop.php");
            //echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


            echo "<b class=\"16px\">{$seasons[$schedule]} Scorecards for Week $week</b><br><br>\n";

        // Scorecard Option Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        // List by team for scorecard

                echo "<p class=\"10px\">Team: ";
            //16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
            //$db->QueryRow("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 AND TeamActive=1 ORDER BY TeamName");
            $db->QueryRow("SELECT t.* FROM teams t, scorecard_game_details s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data['TeamID'];
                $ab = $db->data['TeamAbbrev'];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for scorecard

            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM scorecard_game_details WHERE season = $schedule AND (league_id = 1 OR league_id = 4) GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data['week'];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";
            }
            echo "</p>\n";

        // List by season for scorecard


            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID WHERE ga.league_id = 1 OR league_id = 4 GROUP BY ga.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data['SeasonName'];
                $sid = $db->data['season'];

            echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
            }
            echo "    </select></p>\n";


            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";


                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE SCORECARDS</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
            echo "</tr>\n";


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$schedule AND (league_id = 1 OR league_id = 4)")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("
            SELECT
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
              s.season=$schedule AND s.week=$week AND (s.league_id = 1 OR league_id = 4)
            ORDER BY
              s.week, s.game_date, s.game_id
            ");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data['homeabbrev'];
                $t2 = $db->data['awayabbrev'];
                $um = $db->data['umpireabbrev'];
                $t1id = $db->data['homeid'];
                $t2id = $db->data['awayid'];
                $umid = $db->data['umpireid'];
                $d = sqldate_to_string($db->data['game_date']);
                $sc =  $db->data['scorecard'];
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
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";

                if($fo == "0" && $ca == "0") {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                } else if($fo == "1") {
                  echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
                } else if($ca == "1") {
                  echo "  <td align=\"left\" class=\"9px\">Game Cancelled</td>\n";
                } else {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                }


                echo "</tr>\n";
                }
        }
                        echo "</table>\n";

                        echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";

            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

        }
}




function show_schedule_game($db,$schedule,$id,$pr,$team,$week,$game_id)
{
    global $PHP_SELF, $greenbdr;
	// Checking to see for forfeit to execute diff. query with skipping fields. 10-15-2009 4:56PM  
    if($db->Exists("Select * from  scorecard_game_details where forfeit='1' and game_id=$game_id")) {
     $db->QueryRow("
	    SELECT
	      s.*,
	      o.SeasonName,
	      l.LeagueName,
	      a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	      h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	      g.GroundID, g.GroundName
	    FROM
	      scorecard_game_details s
	    INNER JOIN
	      grounds g ON s.ground_id = g.GroundID
	    INNER JOIN
	      teams a ON s.awayteam = a.TeamID
	    INNER JOIN
	      teams h ON s.hometeam = h.TeamID
	    INNER JOIN
	      seasons o ON s.season = o.SeasonID
	    INNER JOIN
	      leaguemanagement l ON s.league_id = l.LeagueID
	    WHERE
	      s.game_id=$game_id");
	
	    $db->BagAndTag();
	
	    $id = $db->data['game_id'];
	    $sc = $db->data['season'];
	    $sn = $db->data['SeasonName'];
	    $ln = $db->data['LeagueName'];
	    $mo = $db->data['maxovers'];
	    $ht = $db->data['homeabbrev'];
	    $hi = $db->data['homeid'];
	    $at = $db->data['awayabbrev'];
	    $ai = $db->data['awayid'];
	    $ut = '';
	    $gr = $db->data['GroundName'];
	    $gi = $db->data['GroundID'];
	    $re = $db->data['result'];
	    $po = $db->data['points'];
	    $tt = '';
	    $mm  = '';
	    $u1  = '';
	    $u2  = '';
	    $mmi = '';
	    $mmf = '';
	    $mml = '';
	    $u1f = '';
	    $u1l = '';
	    $u2f = '';
	    $u2l = '';
	    $fo = $db->data['forfeit'];
	    $ca = $db->data['cancelled'];
	
	    $da = sqldate_to_string($db->data['game_date']);
	
	    $bat1st = '';
	    $bat1stid = '';
	
	    $bat2nd = '';
	    $bat2ndid = '';	
    }
    else {
    // Query the Game Header
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
    $sn = $db->data['SeasonName'];
    $ln = $db->data['LeagueName'];
    $mo = $db->data['maxovers'];
    $ht = $db->data['homeabbrev'];
    $hi = $db->data['homeid'];
    $at = $db->data['awayabbrev'];
    $ai = $db->data['awayid'];
    $ut = $db->data['UmpireName'];
    $gr = $db->data['GroundName'];
    $gi = $db->data['GroundID'];
    $re = $db->data['result'];
    $po = $db->data['points'];
	$ttid = $db->data['WonTossID'];   // 8-June-2015 11:04pm
    $tt = $db->data['WonTossName'];
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

    $da = sqldate_to_string($db->data['game_date']);

    $bat1st = $db->data['BatFirstAbbrev'];
    $bat1stid = $db->data['BatFirstID'];

    $bat2nd = $db->data['BatSecondAbbrev'];
    $bat2ndid = $db->data['BatSecondID'];
	}
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";
    //echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    //echo "<tr>\n";
    //echo "  <td align=\"left\" valign=\"top\">\n";
    //echo "  <a href=\"/index.php\">Home</a> &gt; Scorecards</p>\n";
    //echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    //echo "</tr>\n";
    //echo "</table>\n";

    // Display the Game Header
// echo "<b>Team Pages: </b> <a href=\"/teamdetails.php?teams=$hi&ccl_mode=1\" class=\"scorecard\">$ht</a>, <a href=\"/teamdetails.php?teams=$ai&ccl_mode=1\" class=\"scorecard\">$at</a><br>\n";
 
 // echo "<br><p align=\"left\"><b>Season Homepage: </b> <a href=\"scorecard.php?schedule=$sc&ccl_mode=1\" class=\"scorecard\">$sn Season</a><br>\n";
 // echo "<b>Team Pages: </b> <a href=\"/teamdetails.php?teams=$hi&ccl_mode=1\" class=\"scorecard\">$ht</a>, <a href=\"/teamdetails.php?teams=$ai&ccl_mode=1\" class=\"scorecard\">$at</a><br>\n";
//  echo "<b>Ground Page: </b> <a href=\"/grounds.php?grounds=$gi&ccl_mode=1\" class=\"scorecard\">$gr Cricket Ground</a><br/>\n";
	
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\" class=\"scorecard\">Game no. $id</td>\n";
    echo "    <td align=\"center\" valign=\"top\" class=\"scorecard\">$ln Championship</td>\n";
    echo "    <td align=\"right\" valign=\"top\"><b><a href=\"scorecard.php?schedule=$sc&ccl_mode=1\" class=\"scorecard\">$sn Season</a></b></td>\n";
// 25-Oct-2014 12:46am    echo "    <td align=\"right\" valign=\"top\">$sn season</td>\n";

    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
// 25-Oct-2014 12:47am 	echo "    <td align=\"center\" valign=\"top\"><font size=\"+1\"><b>$ht v $at</b></font></td>\n";
//    echo "    <td align=\"center\" valign=\"top\"><font size=\"+1\"><b><a href=\"/teamdetails.php?teams=$hi&ccl_mode=1\">$ht</a> v <a href=\"/teamdetails.php?teams=$ai&ccl_mode=1\">$at</a></b></font></td>\n";
echo "    <td align=\"center\" valign=\"top\"><b><a href=\"/teamdetails.php?teams=$hi&ccl_mode=1\" class=\"scorecard\">$ht</a> v <a href=\"/teamdetails.php?teams=$ai&ccl_mode=1\" class=\"scorecard\">$at</a></b></td>\n";
    echo "    <td align=\"right\" valign=\"top\">&nbsp;</td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

// 25-Oct-2014 12:54am 
//    echo "<p>Played at $gr, on $da</p>\n";
echo "<p>Played at <b><a href=\"/grounds.php?grounds=$gi&ccl_mode=1\" class=\"scorecard\">$gr</a></b>, on $da</p>";
//echo "<p>Played at <b><a href=\"/grounds.php?grounds=$gi&ccl_mode=1\" class=\"scorecard\">$gr</a></b>, on $da</p>\n";
echo "<p><b>Result: </b> $re</p>\n";

    if(!($fo == "1" && $ca == "0")) {
    
    	
////////////////////////////////////////////////////////////////////////////////////////////
//                                Team Batting First                                      //
////////////////////////////////////////////////////////////////////////////////////////////

    // Display the Batting 1st Details
	
    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr height=\"25\" bgcolor=\"#cccccc\">\n";
    echo "   <td class=\"scorecard\" width=\"1%\" align=\"right\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"65%\" align=\"left\" colspan=\"4\"><b>$bat1st Batting innings</b> ($mo overs maximum)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>B</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;<b>4s</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">&nbsp;<b>6s</b></td>\n";
   
   echo "  <td class=\"scorecard\" width=\"9%\" align=\"center\">&nbsp;<b>SR</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">&nbsp;</td>\n";

    echo " </tr>\n";

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,s.howout_video,s.highlights_video,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev, b.PlayerID AS BowlerID, a.PlayerID AS AssistID,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $game_id AND s.innings_id = 1 AND s.how_out <> 1
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
    $out = $db->data['HowOutAbbrev'];
    $oid = $db->data['HowOutID'];
    $run = $db->data['runs'];
    $bal = $db->data['balls'];
    $fou = $db->data['fours'];
    $six = $db->data['sixes'];
    $hwv=  $db->data['howout_video'];
	$hlv=  $db->data['highlights_video'];
	$hwvideo='';
	$hlvideo='';
    if($hwv != ''){
    	$hwvideo = "<a href=\"$hwv\" target='_blank' class=\"scorecard\"><img src=\"/images/video.jpg\" border=0 width=18 alt=\"Watch How Out\"></a>";
    }
    if($hlv != ''){
    	$hlvideo = "<a href=\"$hlv\" target='_blank' class=\"scorecard\"><img src=\"/images/video_hl.jpg\" border=0 width=18 alt=\"Watch Batting Highlights\"></a>";
    }
    // Calculate the Strike Rate

    if($bal == 0) {
      $sr = "0.00";
    } else {
      //$sr  = round(($run/$bal)*100,2);
      $sr  = sprintf("%10.2f\n", round(($run/$bal)*100,2));
    }

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";


    // If Batter Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } elseif($pfn != "" && $pln == "")  {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }else{
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pln</a>\n";
    }

    echo "  </td>\n";
   echo "  <td class=\"scorecard\" width=\"5%\" align=\"left\">$hwvideo</td>\n";
    // If Assist Last Name is blank, just use first name

    // Make caught and bowled (c & part) align right in the assist column
    //if($oid == 5) {

    echo "  <td class=\"scorecard\" width=\"44%\" colspan=\"2\" align=\"left\">";
    if($out == "NOT OUT") {
    	echo "  <b><font color='blue'>$out</a></b>&nbsp;";
    }
    else {
    	echo "  <b>$out</b>&nbsp;";
    }

    //} else {

    //echo "  <td class=\"scorecard\" width=\"48%\" colspan=\"2\" align=\"left\">";

    // dont display bowled or caught and bowled
    //if($oid == 3 || $oid == 5) {
    //  echo "";
    //} else {
    //  echo "$out ";
    //}

    // show brackets around the runout effector
	$linkAssist = "<a href=\"/players.php?players=$aid&ccl_mode=1\" class=\"scorecard\">";
	$closeAssist = "</a>";
    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      if($oid == 9) {
        echo "(".$linkAssist.$ain." ".$aln.$closeAssist.")";
      } else if($oid == 4 || $oid == 10) {
        echo $linkAssist.$ain." ".$aln.$closeAssist." ";
     } else {
       echo $linkAssist.$ain." ".$aln.$closeAssist;
     }
      //if($oid == 9) echo ")";
    } else {
      if($oid == 9) {
        echo "(".$linkAssist.$afn.$closeAssist.")";
      } else if($oid == 4 || $oid == 10) {
        echo $linkAssist.$afn.$closeAssist;
      } else {
        echo $linkAssist.$afn.$closeAssist;
      }
      //if($oid == 9) echo ")";
    }

    //}
    //echo "  </td>\n";

    // If Bowler Last Name is blank, just use first name

    //echo "  <td class=\"scorecard\" width=\"25%\" align=\"left\">";

    // display bowled if it goes with the wicket type
    
    $linkBowler = "<a href=\"/players.php?players=$bid&ccl_mode=1\" class=\"scorecard\">";
    $closeBowler = "</a>";

// 3-Apr-2014 11:20pm removed the check for $oid == '5'
 if($oid == '4' || $oid == '6' || $oid == '7' || $oid == '10') {
//  if($oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {

// 24-Aug-2009
      echo "<b>b </b>";
//echo "b ";
    } else {
      echo "";
    }
	
    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "") {
      echo $linkBowler.$bin." ".$bln.$closeBowler;
    } else {
      echo $linkBowler.$bfn.$closeBowler."\n";
    }
    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b>$run</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$bal</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$fou</td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">$six</td>\n";
    echo "  <td class=\"scorecard\" width=\"13%\" align=\"right\">$sr</td>\n";
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">$hlvideo</td>\n";
    echo " </tr>\n";

    }

    // get Extras details

    $db->Query("
    SELECT
      legbyes, byes, wides, noballs, total
    FROM
      scorecard_extras_details
    WHERE
      game_id = $game_id AND innings_id = 1
    ");

    for ($e=0; $e<$db->rows; $e++) {
    $db->GetRow($e);

    $by = $db->data['byes'];
    $lb = $db->data['legbyes'];
    $wi = $db->data['wides'];
    $nb = $db->data['noballs'];
    $to = $db->data['total'];


    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"right\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">&nbsp;&nbsp;Extras: </td>\n";
    echo "  <td class=\"scorecard\" width=\"48%\" align=\"left\" colspan=\"3\">(b $by, lb $lb, w $wi, nb $nb)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b><u>$to</u></b></td>\n";
    echo "  <td class=\"scorecard\" width=\"20%\" align=\"right\" colspan=\"4\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"right\">&nbsp;</td>\n";
    echo " </tr>\n";

    }

    // get Totals details

    $db->Query("
    SELECT
      wickets, total, overs
    FROM
      scorecard_total_details
    WHERE
      game_id = $game_id AND innings_id = 1
    ");

    for ($t=0; $t<$db->rows; $t++) {
    $db->GetRow($t);

    $wi = $db->data['wickets'];
    $to = $db->data['total'];
    $ov = $db->data['overs'];
    $rr = Round(($to/$ov),3);    // 11-Oct-2014 10:55pm
//    $rs = "(". $rr. " runs per over)";     // 11-Oct-2014 10:55pm
    $rs = " (RR: ". $rr. ")";     // 11-Oct-2014 11pm

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"right\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">&nbsp;&nbsp;<b>Total: </b></td>\n";
    echo "  <td class=\"scorecard\" width=\"39%\" align=\"left\" colspan=\"3\">($wi wickets, $ov overs)</td>\n";

    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b>$to</b></td>\n";
//    echo "  <td class=\"scorecard\" width=\"20%\" align=\"right\" colspan=\"4\"><b>$to</b> $rs</td>\n";     // 11-Oct-2014 10:55pm Added colspan=6 and 20%

    echo "  <td class=\"scorecard\" width=\"24%\" align=\"right\" colspan=\"4\"> $rs</td>\n";     // 11-Oct-2014 11:36pm

//    echo "  <td class=\"scorecard\" width=\"24%\" align=\"right\" colspan=\"6\"><b>$to</b> $rs</td>\n";     // 11-Oct-2014 11:15pm Added colspan=2 and 20%

//    echo "  <td class=\"scorecard\" width=\"20%\" align=\"right\" colspan=\"4\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";   // 11-Oct-2014 10:55pm

    echo "  <td class=\"scorecard\" width=\"1%\" align=\"right\">&nbsp;</td>\n";
    echo " </tr>\n";

    }

    // get Did not Bat details

    if ($db->Exists("

    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $game_id AND s.innings_id = 1 AND s.how_out = 1
    ORDER BY
      s.batting_position

    ")) {

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,s.howout_video,s.highlights_video,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $game_id AND s.innings_id = 1 AND s.how_out = 1
    ORDER BY
      s.batting_position

    ");

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" colspan=\"5\" width=\"100%\" align=\"left\">\n";

    echo "<br><b>Did not bat: </b> ";

    for ($e=0; $e<$db->rows; $e++) {
    $db->GetRow($e);

    $pid = $db->data['BatterID'];
    $pln = $db->data['BatterLName'];
    $pin = $db->data['BatterFInitial'];
    $pfn = $db->data['BatterFName'];
 
	
    // If Batter Last Name is blank, just use first name

    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>, \n";
    } else {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>, \n";
    }

    }

    echo "  </td>\n";
    echo " </tr>\n";

    } else {
    }

    echo "</table>\n";

    // get FOW details

    $db->Query("
    SELECT
      fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10
    FROM
      scorecard_fow_details
    WHERE
      game_id = $game_id AND innings_id = 1
    ");

    for ($f=0; $f<$db->rows; $f++) {
    $db->GetRow($f);

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

    echo "<br>\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td valign=\"top\" class=\"scorecard\" width=\"100%\" align=\"left\" colspan=\"7\"><b>Fall of wickets: </b>\n";

    if($f1 != "777") echo "1-$f1, ";
    if($f2 != "777") echo "2-$f2, ";
    if($f3 != "777") echo "3-$f3, ";
    if($f4 != "777") echo "4-$f4, ";
    if($f5 != "777") echo "5-$f5, ";
    if($f6 != "777") echo "6-$f6, ";
    if($f7 != "777") echo "7-$f7, ";
    if($f8 != "777") echo "8-$f8, ";
    if($f9 != "777") echo "9-$f9, ";
    if($f10 != "777") echo "10-$f10";

    echo "  </td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";

    echo "<br>\n";

    // Display the Bowling Details

    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr height=\"25\" bgcolor=\"#cccccc\">\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\"><b>$bat2nd Bowling innings</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>O</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>M</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>W</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"10%\" align=\"right\"><b>Econ</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"21%\" align=\"right\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">&nbsp;</td>\n";
    echo " </tr>\n";

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,s.highlights_video,
      p.PlayerID AS BowlerID, p.PlayerLName AS BowlerLName, p.PlayerFName AS BowlerFName, LEFT(p.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_bowling_details s
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    WHERE
      s.game_id = $game_id AND s.innings_id = 1
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
	$hlvideo='';
    if($hlv != ''){
    	$hlvideo = "<a href=\"$hlv\" target='_blank' class=\"scorecard\"><img src=\"/images/video_hlb.jpg\" border=0 width=18 alt=\"Watch Bowling Highlights\"></a>";
    }
    // Calculate the Economy Rate
// echo " $ru<tr>\n";
// echo " $ov<tr>\n";

	// 23-Oct-2014 11:19pm Added the elseif to avoid the division by zero error 
    if($ru == 0 && $ov == 0) {
      $er = "0.00";
	  } elseif ($ru > 0 && $ov == 0) {
	   $er = "0.00";
      } else {
                   if ($ov<1){
                     $er  = sprintf("%10.2f\n", round(($ru/(((($ov*10)*100)/6)/100)),2));
                   }
                   else {
      $er  = sprintf("%10.2f\n", round(($ru/$ov),2));
                  }
    }


    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"right\">&nbsp;</td>\n";

    // If Bowler Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } elseif($pfn != "" && $pln == "")  {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }else{
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pln</a>\n";
    }

    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ov</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ma</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ru</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$wi</td>\n";
    echo "  <td class=\"scorecard\" width=\"10%\" align=\"right\">$er</td>\n";
    echo "  <td class=\"scorecard\" width=\"21%\" align=\"left\">&nbsp;&nbsp;";
	
    if($no == 0 && $wd == 0) {
      echo "";
    } elseif($no != 0 && $wd == 0) {
      echo "(nb $no)";
    } elseif($no == 0 && $wd != 0) {
      echo "(w $wd)";
    } else {
      echo "(nb $no, w $wd)";
    }

    echo "  </td>\n";
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">$hlvideo</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";


    echo "<p></p><p></p>\n";


////////////////////////////////////////////////////////////////////////////////////////////
//                                Team Batting Second                                     //
////////////////////////////////////////////////////////////////////////////////////////////


    // Display the Batting 2nd Details

    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr height=\"25\" bgcolor=\"#cccccc\">\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"65%\" align=\"left\" colspan=\"4\"><b>$bat2nd Batting innings</b> ($mo overs maximum)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>B</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;<b>4s</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">&nbsp;<b>6s</b></td>\n";    
    echo "  <td class=\"scorecard\" width=\"9%\" align=\"center\">&nbsp;<b>SR</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">&nbsp;<b></b></td>\n";
    echo " </tr>\n";


    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,s.howout_video, s.highlights_video,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev, b.PlayerID AS BowlerID, a.PlayerID AS AssistID,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $game_id AND s.innings_id = 2 and s.how_out <> 1
    ORDER BY
      s.batting_position

    ");

    for ($x=0; $x<$db->rows; $x++) {
    $db->GetRow($x);

    $pid = $db->data['BatterID'];
    $aid = $db->data['AssistID'];
    $bid = $db->data['BowlerID'];
    $pln = $db->data['BatterLName'];
    $pfn = $db->data['BatterFName'];
    $pin = $db->data['BatterFInitial'];
    $bln = $db->data['BowlerLName'];
    $bfn = $db->data['BowlerFName'];
    $bin = $db->data['BowlerFInitial'];
    $aln = $db->data['AssistLName'];
    $afn = $db->data['AssistFName'];
    $ain = $db->data['AssistFInitial'];
    $out = $db->data['HowOutAbbrev'];
    $oid = $db->data['HowOutID'];
    $run = $db->data['runs'];
    $bal = $db->data['balls'];
    $fou = $db->data['fours'];
    $six = $db->data['sixes'];
	$hwv = $db->data['howout_video'];
	$hlv = $db->data['highlights_video'];
	$hwvideo='';
	$hlvideo='';
    
	// Calculate the Strike Rate
    // 23-Oct-2014 10:57pm if($bal == 0 || is_null($bal) == TRUE || $run == 0 || is_null($run) == TRUE) {
//	echo "  <b><font color='blue'>$bal</a></b>&nbsp;";
//	echo "  <b><font color='blue'>$run</a></b>&nbsp;";
	
	  if($bal == 0) {
      $sr = "0.00";
    } else {
      //$sr  = round(($run/$bal)*100,2);
      $sr  = sprintf("%10.2f\n", round(($run/$bal)*100,2));
    }


    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";


    // If Batter Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } elseif($pfn != "" && $pln == "")  {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }else{
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pln</a>\n";
    }
    echo "  </td>\n";
	if($hwv != ''){
    	$hwvideo = "<a href=\"$hwv\" target='_blank' class=\"scorecard\"><img src=\"/images/video.jpg\" border=0 width=18 alt=\"Watch How Out\"></a>";
    }
	
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"left\">$hwvideo</td>\n";
    // If Assist Last Name is blank, just use first name

    // Make caught and bowled (c & part) align right in the assist column
    //if($oid == 5) {

    echo "  <td class=\"scorecard\" width=\"44%\" colspan=\"2\" align=\"left\">";
    
    if($out == "NOT OUT") {
    	echo "  <b><font color='blue'>$out</a></b>&nbsp;";
    }
    else {
    	echo "  <b>$out</b>&nbsp;";
    }

    //} else {

    //echo "  <td class=\"scorecard\" width=\"48%\" colspan=\"2\" align=\"left\">";

    // dont display bowled or caught and bowled
    //if($oid == 3 || $oid == 5) {
    //  echo "";
    //} else {
    //  echo "$out ";
    //}

    // show brackets around the runnout effector
	
    $linkAssist = "<a href=\"/players.php?players=$aid&ccl_mode=1\" class=\"scorecard\">";
	$closeAssist = "</a>";
    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      if($oid == 9) {
        echo "(".$linkAssist.$ain." ".$aln.$closeAssist.")";
      } else if($oid == 4 || $oid == 10) {
        echo $linkAssist.$ain." ".$aln.$closeAssist." ";
     } else {
       echo $linkAssist.$ain." ".$aln.$closeAssist;
     }
      //if($oid == 9) echo ")";
    } else {
      if($oid == 9) {
        echo "(".$linkAssist.$afn.$closeAssist.")";
      } else if($oid == 4 || $oid == 10) {
        echo $linkAssist.$afn.$closeAssist;
      } else {
        echo $linkAssist.$afn.$closeAssist;
      }
      //if($oid == 9) echo ")";
    }

    //}
    //echo "  </td>\n";

    // If Bowler Last Name is blank, just use first name

    //echo "  <td class=\"scorecard\" width=\"25%\" align=\"left\">";

    // display bowled if it goes with the wicket type
    
    $linkBowler = "<a href=\"/players.php?players=$bid&ccl_mode=1\" class=\"scorecard\">";
    $closeBowler = "</a>";

// 3-Apr-2014 11:20pm removed the check for $oid == '5'
    if($oid == '4' || $oid == '6' || $oid == '7' || $oid == '10') {
//    if($oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {

// 24-Aug-2009
        echo "<b>b </b>";
 //     echo "b ";
    } else {
      echo "";
    }
	
    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "") {
      echo $linkBowler.$bin." ".$bln.$closeBowler;
    } else {
      echo $linkBowler.$bfn.$closeBowler."\n";
    }
    
    
    /* Made Commented
     
    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      if($oid == 9) {
        echo "($aln)";
      } else if($oid == 4 || $oid == 10) {
        echo "$aln ";
     } else {
       echo "$aln";
     }
      //if($oid == 9) echo ")";
    } else {
      if($oid == 9) {
        echo "($afn)";
      } else if($oid == 4 || $oid == 10) {
        echo "$afn ";
      } else {
        echo "$afn";
      }
      //if($oid == 9) echo ")";
    }

    //}
    //echo "  </td>\n";

    // If Bowler Last Name is blank, just use first name

    //echo "  <td class=\"scorecard\" width=\"25%\" align=\"left\">";

    // display bowled if it goes with the wicket type
    if($oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {
      echo "<b>b </b>";
    } else {
      echo "";
    }

    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "") {
      echo "$bln";
    } elseif ($bfn != "" && $bln == ""){
      echo "$bfn\n";
    } else {
      echo "$bln\n";
     }
	*/
    
    echo "  </td>\n";

    
    if($hlv != ''){
    	$hlvideo = "<a href=\"$hlv\" target='_blank' class=\"scorecard\"><img src=\"/images/video_hl.jpg\" border=0 width=18 alt=\"Watch Batting Highlights\"></a>";
    }
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b>$run</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$bal</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$fou</td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">$six</td>\n";
    echo "  <td class=\"scorecard\" width=\"13%\" align=\"right\">$sr</td>\n";
     echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">$hlvideo</td>\n";


    echo "</tr>\n";

    }


    // get Extras details

    $db->Query("
    SELECT
      legbyes, byes, wides, noballs, total
    FROM
      scorecard_extras_details
    WHERE
      game_id = $game_id AND innings_id = 2
    ");

    for ($e=0; $e<$db->rows; $e++) {
    $db->GetRow($e);

    $by = $db->data['byes'];
    $lb = $db->data['legbyes'];
    $wi = $db->data['wides'];
    $nb = $db->data['noballs'];
    $to = $db->data['total'];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">&nbsp;&nbsp;Extras: </td>\n";
    echo "  <td class=\"scorecard\" width=\"48%\" align=\"left\" colspan=\"3\">(b $by, lb $lb, w $wi, nb $nb)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b><u>$to</u></b></td>\n";
    echo "  <td class=\"scorecard\" width=\"20%\" align=\"right\" colspan=\"4\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo " </tr>\n";

    }

    // get Totals details

    $db->Query("
    SELECT
      wickets, total, overs
    FROM
      scorecard_total_details
    WHERE
      game_id = $game_id AND innings_id = 2
    ");

    for ($t=0; $t<$db->rows; $t++) {
    $db->GetRow($t);

    $wi = $db->data['wickets'];
    $to = $db->data['total'];
    $ov = $db->data['overs'];

	// 23-Oct-2014 11:05pm checking to avoid division by zero
	If ($ov > 0 && $to > 0 ){
	    $rr = Round(($to/$ov),3);    // 11-Oct-2014 11:38pm
	} else {
        $rr = 0;
		}	
//    $rs = "(". $rr. " runs per over)";     // 11-Oct-2014 11:38pm
    $rs = " (RR: ". $rr. ")";     // 11-Oct-2014 11:38pm

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">&nbsp;&nbsp;<b>Total: </b></td>\n";
    echo "  <td class=\"scorecard\" width=\"48%\" align=\"left\" colspan=\"3\">($wi wickets, $ov overs)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b>$to</b></td>\n";

    echo "  <td class=\"scorecard\" width=\"24%\" align=\"right\" colspan=\"4\"> $rs</td>\n";     // 11-Oct-2014 11:40pm

    echo "  <td class=\"scorecard\" width=\"20%\" align=\"right\" colspan=\"4\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo " </tr>\n";

    }


    if ($db->Exists("

    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $game_id AND s.innings_id = 2 AND s.how_out = 1
    ORDER BY
      s.batting_position

    ")) {


    // get Did not Bat details

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.batting_position, s.runs, s.balls, s.fours, s.sixes,
      p.PlayerID AS BatterID, p.PlayerLName AS BatterLName, p.PlayerFName AS BatterFName, LEFT(p.PlayerFName,1) AS BatterFInitial,
      h.HowOutID, h.HowOutName, h.HowOutAbbrev,
      a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
      b.PlayerLName AS BowlerLName, b.PlayerFName AS BowlerFName, LEFT(b.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_batting_details s
    LEFT JOIN
      players a ON a.PlayerID = s.assist
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    LEFT JOIN
      players b ON b.PlayerID = s.bowler
    INNER JOIN
      howout h ON h.HowOutID = s.how_out
    WHERE
      s.game_id = $game_id AND s.innings_id = 2 AND s.how_out = 1
    ORDER BY
      s.batting_position

    ");

// 25-Oct-2014 1:57am commented these 3 lines
// echo " <tr>\n";
// echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
// echo "  <td class=\"scorecard\" colspan=\"5\" width=\"98%\" align=\"left\">\n";

  echo " <tr>\n";
  echo "  <td class=\"scorecard\" colspan=\"5\" width=\"100%\" align=\"left\">\n";
	
    echo "<br><b>Did not bat: </b> ";

    for ($e=0; $e<$db->rows; $e++) {
    $db->GetRow($e);

    $pid = $db->data['BatterID'];
    $pln = $db->data['BatterLName'];
    $pin = $db->data['BatterFInitial'];
    $pfn = $db->data['BatterFName'];

    // If Batter Last Name is blank, just use first name

    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>, \n";
    } elseif($pfn != "" && $pln == "")  {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }else{
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pln</a>\n";
    }
    }

    echo "  </td>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo " </tr>\n";

    } else {
    }

    echo "</table>\n";

    // get FOW details

    $db->Query("
    SELECT
      fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10
    FROM
      scorecard_fow_details
    WHERE
      game_id = $game_id AND innings_id = 2
    ");

    for ($f=0; $f<$db->rows; $f++) {
    $db->GetRow($f);

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

    echo "<br>\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td valign=\"top\" class=\"scorecard\" width=\"100%\" colspan=\"8\" align=\"left\"><b>Fall of wickets: </b> \n";


    if($f1 != "777") echo "1-$f1, ";
    if($f2 != "777") echo "2-$f2, ";
    if($f3 != "777") echo "3-$f3, ";
    if($f4 != "777") echo "4-$f4, ";
    if($f5 != "777") echo "5-$f5, ";
    if($f6 != "777") echo "6-$f6, ";
    if($f7 != "777") echo "7-$f7, ";
    if($f8 != "777") echo "8-$f8, ";
    if($f9 != "777") echo "9-$f9, ";
    if($f10 != "777") echo "10-$f10";


    echo "  </td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";

    echo "<br>\n";

    // Display the Bowling Details

    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr height=\"25\" bgcolor=\"#cccccc\">\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\"><b>$bat1st Bowling innings</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>O</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>M</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>W</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"10%\" align=\"right\"><b>Econ</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"21%\" align=\"right\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"right\">&nbsp;</td>\n";
    echo " </tr>\n";

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,s.highlights_video,
      p.PlayerID AS BowlerID, p.PlayerLName AS BowlerLName, p.PlayerFName AS BowlerFName, LEFT(p.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_bowling_details s
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    WHERE
      s.game_id = $game_id AND s.innings_id = 2
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
    $hlvideo='';
    if($hlv != ''){
    	$hlvideo = "<a href=\"$hlv\" target='_blank' class=\"scorecard\"><img src=\"/images/video_hlb.jpg\" border=0 width=18 alt=\"Watch Bowling Highlights\"></a>";
    }

    // Calculate the Economy Rate

	// 23-Oct-2014 11:25pm Added the elseif to avoid the division by zero error 
    if($ru == 0 && $ov == 0) {
      $er = "0.00";
	  } elseif ($ru > 0 && $ov == 0) {
	   $er = "0.00";
      } else {
                   if ($ov<1){
                     $er  = sprintf("%10.2f\n", round(($ru/(((($ov*10)*100)/6)/100)),2));
                   }
                   else {
      $er  = sprintf("%10.2f\n", round(($ru/$ov),2));
                  }
    }


    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";


    // If Bowler Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } elseif($pfn != "" && $pln == "")  {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }else{
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pln</a>\n";
    }
    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ov</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ma</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ru</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$wi</td>\n";
    echo "  <td class=\"scorecard\" width=\"10%\" align=\"right\">$er</td>\n";
    echo "  <td class=\"scorecard\" width=\"26%\" align=\"left\">&nbsp;&nbsp;";

    if($no == 0 && $wd == 0) {
      echo "";
    } elseif($no != 0 && $wd == 0) {
      echo "(nb $no)";
    } elseif($no == 0 && $wd != 0) {
      echo "(w $wd)";
    } else {
      echo "(nb $no, w $wd)";
    }

    echo "  </td>\n";
    
    echo "  <td class=\"scorecard\" width=\"5%\" align=\"left\">$hlvideo</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";
    
    }// Closing for forfeit game...
    
    echo "<br><hr color=\"#cccccc\" width=\"95%\" size=\"2\">\n";

    if($tt != "") {
      // echo "<p><b>Toss: </b> $tt<br>\n";
	  echo "<p><b>Toss: </b><a href=\"/teamdetails.php?teams=$ttid&ccl_mode=1\" class=\"scorecard\">$tt</a><br>\n";   // 8-June-2015 11:05pm using $ttid instead of $WonTossID
	  
    } else {
      echo "<p><b>Toss: </b> n/a<br>";
    }

    if($u1 != 0 && $u2 != 0) {
    echo "<b>Umpires: </b> <a href=\"/players.php?players=$u1&ccl_mode=1\" class=\"scorecard\">$u1f $u1l</a> and <a href=\"/players.php?players=$u2&ccl_mode=1\" class=\"scorecard\">$u2f $u2l</a><br>\n";
    } else if($u1 != 0 && $u2 == 0) {
    echo "<b>Umpire: </b> <a href=\"/players.php?players=$u1&ccl_mode=1\" class=\"scorecard\">$u1f $u1l</a><br>\n";
    } else {
    echo "<b>Umpires: </b> n/a<br>";
    }
    if($mm != "" || $mm2 != "") {
		echo "<b>Player of the Match: </b>";
    }
    if($mm != "") {
    	echo "<a href=\"/players.php?players=$mmi&ccl_mode=1\" class=\"scorecard\">$mmf $mml</a>\n";
    } else {
    	echo "n/a\n";
    }
   
	if($mm2 != "" && $mm2 != "0") {
    echo "and  <a href=\"/players.php?players=$mmi2&ccl_mode=1\" class=\"scorecard\">$mmf2 $mml2</a>\n";
    }
    
	if($mm != "" || $mm2 != "") {
		echo "</p>";
    }

    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr height=\"25\" bgcolor=\"#cccccc\">\n";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" width=\"99%\" align=\"left\"><b>General Information</b></td>\n";
    //echo " </tr>\n";
    //echo " <tr>\n";
	echo " </tr>";
    echo " <tr>";
    echo "  <td class=\"scorecard\" width=\"1%\" align=\"left\">&nbsp;</td>";
    echo "  <td class=\"scorecard\" width=\"9%\" align=\"left\">";

	// 25-Oct-2014 12:57am commented these 3 fields - They seemed extra - They are clickable/linkable on top header
//    echo "<br><p align=\"left\"><b>Season Homepage: </b> <a href=\"scorecard.php?schedule=$sc&ccl_mode=1\" class=\"scorecard\">$sn Season</a><br>\n";
//    echo "<b>Team Pages: </b> <a href=\"/teamdetails.php?teams=$hi&ccl_mode=1\" class=\"scorecard\">$ht</a>, <a href=\"/teamdetails.php?teams=$ai&ccl_mode=1\" class=\"scorecard\">$at</a><br>\n";
//    echo "<b>Ground Page: </b> <a href=\"/grounds.php?grounds=$gi&ccl_mode=1\" class=\"scorecard\">$gr Cricket Ground</a><br/>\n";


$db->QueryRow("SELECT report FROM scorecard_game_details WHERE game_id=$game_id");
//echo "<b>Report:</b> <a href='".$db->data["report"]."'target='_blank' class=\"scorecard\">CricHQ Scorecard</a>";
echo "<b>Report:</b> <a href='".$db->data["report"]."'target='_blank' class='scorecard'>".$db->data["report"]."</a>";
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_schedule_listing($db,$schedule,$id,$pr,$team,$week,$game_id);
    break;
case 1:
    show_schedule($db,$schedule,$id,$pr,$team,$week,$game_id);
    break;
case 2:
    show_schedule_team($db,$schedule,$id,$pr,$team,$week,$game_id);
    break;
case 3:
    show_schedule_week($db,$schedule,$id,$pr,$team,$week,$game_id);
    break;
case 4:
    show_schedule_game($db,$schedule,$id,$pr,$team,$week,$game_id);
    break;
default:
    show_schedule_listing($db,$schedule,$id,$pr,$team,$week,$game_id);
    break;
}

?>
