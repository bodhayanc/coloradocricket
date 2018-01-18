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
        //require ("includes/navtop.php");
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

            $sen = $db->data[SeasonName];
            $sid = $db->data[season];
                
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
                        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
                }

                $db->Query("SELECT * FROM teams WHERE LeagueID=2 ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data[TeamAbbrev];
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
            //require ("includes/navtop.php");
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
            $db->QueryRow("SELECT * FROM teams WHERE LeagueID=2 AND TeamActive=1 ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data[TeamID];
                $ab = $db->data[TeamAbbrev];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for scorecard
        
            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM scorecard_game_details WHERE season = $schedule AND league_id=2 GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data[week];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";                
            }
            echo "</p>\n";
            
        // List by season for scorecard

        
            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID WHERE ga.league_id=2 GROUP BY ga.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data[SeasonName];
                $sid = $db->data[season];
                
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


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$schedule AND isactive=0 AND league_id=2")) {
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
              a.TeamID AS AwayID, a.TeamName AS AwayName, a.TeamAbbrev AS AwayAbbrev,
              h.TeamID AS HomeID, h.TeamName AS HomeName, h.TeamAbbrev AS HomeAbbrev
            FROM
              scorecard_game_details s
            INNER JOIN
              teams a ON s.awayteam = a.TeamID
            INNER JOIN
              teams h ON s.hometeam = h.TeamID
            WHERE
              s.season=$schedule AND s.isactive=0 AND s.league_id=2
            ORDER BY
              s.week, s.game_date, s.game_id
            ");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data[HomeAbbrev];
                $t2 = $db->data[AwayAbbrev];
                $um = $db->data[UmpireAbbrev];
                $t1id = $db->data[HomeID];
                $t2id = $db->data[AwayID];
                $umid = $db->data[UmpireID];
                $d = sqldate_to_string($db->data[game_date]);
                $sc =  $db->data[scorecard];
                $re = $db->data[result];
                $id = $db->data[game_id];
                $wk = $db->data[week];
                $fo = $db->data[forfeit];
                $ca = $db->data[cancelled];

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
                  echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
                } else if($ca == "1" && $fo = "1") {
                  echo "  <td align=\"left\" class=\"9px\">Game cancelled</td>\n";
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
                        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
                }

                $db->Query("SELECT * FROM teams WHERE LeagueID=2 ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data[TeamAbbrev];
                        $teamcolour[$db->data[TeamID]] = $db->data[TeamColour];
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
            //require ("includes/navtop.php");
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
            $db->QueryRow("SELECT * FROM teams WHERE LeagueID=2 AND TeamActive=1 ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data[TeamID];
                $ab = $db->data[TeamAbbrev];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for scorecard
        
            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM scorecard_game_details WHERE season = $schedule AND league_id=2 GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data[week];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";                
            }
            echo "</p>\n";
            
        // List by season for scorecard

        
            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID  WHERE ga.league_id=2 GROUP BY ga.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data[SeasonName];
                $sid = $db->data[season];
                
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
            
            
        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$schedule AND league_id=2")) {
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
              a.TeamID AS AwayID, a.TeamName AS AwayName, a.TeamAbbrev AS AwayAbbrev,
              h.TeamID AS HomeID, h.TeamName AS HomeName, h.TeamAbbrev AS HomeAbbrev
            FROM
              scorecard_game_details s
            INNER JOIN
              teams a ON s.awayteam = a.TeamID
            INNER JOIN
              teams h ON s.hometeam = h.TeamID
            WHERE
              s.season=$schedule AND s.league_id=2
            AND
              (s.awayteam=$team OR s.hometeam=$team)
            ORDER BY
              s.week, s.game_date, s.game_id
            ");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data[HomeAbbrev];
                $t2 = $db->data[AwayAbbrev];
                $um = $db->data[UmpireAbbrev];
                $t1id = $db->data[HomeID];
                $t2id = $db->data[AwayID];
                $umid = $db->data[UmpireID];
                $d = sqldate_to_string($db->data[game_date]);
                $sc =  $db->data[scorecard];
                $re = $db->data[result];
                $id = $db->data[game_id];
                $wk = $db->data[week];
                $fo = $db->data[forfeit];
                $ca = $db->data[cancelled];

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
        
                $db->Query("SELECT week FROM scorecard_game_details WHERE league_id=2 GROUP BY week");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $weeks[$db->data[week]] = $db->data[week];
                }        

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
                }

                $db->Query("SELECT * FROM teams WHERE LeagueID=2 ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data[TeamAbbrev];
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
            //require ("includes/navtop.php");
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
            $db->QueryRow("SELECT * FROM teams WHERE LeagueID=2 AND TeamActive=1 ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data[TeamID];
                $ab = $db->data[TeamAbbrev];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for scorecard
        
            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM scorecard_game_details WHERE season = $schedule AND league_id=2 GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data[week];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";                
            }
            echo "</p>\n";
            
        // List by season for scorecard

        
            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT ga.season, se.SeasonName FROM scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID WHERE ga.league_id=2 GROUP BY ga.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data[SeasonName];
                $sid = $db->data[season];
                
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
            
            
        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$schedule AND league_id=2")) {
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
              a.TeamID AS AwayID, a.TeamName AS AwayName, a.TeamAbbrev AS AwayAbbrev,
              h.TeamID AS HomeID, h.TeamName AS HomeName, h.TeamAbbrev AS HomeAbbrev
            FROM
              scorecard_game_details s
            INNER JOIN
              teams a ON s.awayteam = a.TeamID
            INNER JOIN
              teams h ON s.hometeam = h.TeamID
            WHERE
              (s.season=$schedule AND s.week=$week) AND s.league_id=2
            ORDER BY
              s.week, s.game_date, s.game_id
            ");         
              
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data[HomeAbbrev];
                $t2 = $db->data[AwayAbbrev];
                $um = $db->data[UmpireAbbrev];
                $t1id = $db->data[HomeID];
                $t2id = $db->data[AwayID];
                $umid = $db->data[UmpireID];
                $d = sqldate_to_string($db->data[game_date]);
                $sc =  $db->data[scorecard];
                $re = $db->data[result];
                $id = $db->data[game_id];
                $wk = $db->data[week];
                $fo = $db->data[forfeit];
                $ca = $db->data[cancelled];

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

    // Query the Game Header

    $db->QueryRow("

    SELECT
      s.*,
      a.TeamID AS AwayID, a.TeamName AS AwayName, a.TeamAbbrev AS AwayAbbrev,
      h.TeamID AS HomeID, h.TeamName AS HomeName, h.TeamAbbrev AS HomeAbbrev,
      u.TeamID AS UmpireID, u.TeamName AS UmpireName, u.TeamAbbrev AS UmpireAbbrev,
      t.TeamID AS WonTossID, t.TeamName AS WonTossName, t.TeamAbbrev AS WonTossAbbrev,
      b.TeamID AS BatFirstID, b.TeamName AS BatFirstName, b.TeamAbbrev AS BatFirstAbbrev,
      n.TeamID AS BatSecondID, n.TeamName AS BatSecondName, n.TeamAbbrev AS BatSecondAbbrev,
      p.PlayerID AS MomID, p.PlayerLName AS MomLName, p.PlayerFName AS MomFName,
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
    INNER JOIN
      teams b ON s.batting_first_id = b.TeamID
    INNER JOIN
      teams n ON s.batting_second_id = n.TeamID 
    LEFT JOIN
      players p ON s.mom = p.PlayerID
    LEFT JOIN
      players u1 ON s.umpire1 = u1.PlayerID
    LEFT JOIN
      players u2 ON s.umpire2 = u2.PlayerID   
    WHERE
      s.game_id=$game_id

    ");

    $db->BagAndTag();

    $id = $db->data[game_id];
    $sc = $db->data[season];
    $mo = $db->data[maxovers];
    $ht = $db->data[HomeName];
    $hi = $db->data[HomeID];
    $at = $db->data[AwayName];
    $ai = $db->data[AwayID];
    $ut = $db->data[UmpireName];
    $gr = $db->data[GroundName];
    $gi = $db->data[GroundID];
    $re = $db->data[result];
    $po = $db->data[points];
    $tt = $db->data[WonTossName];
    $mm  = $db->data[mom];
    $u1  = $db->data[umpire1];
    $u2  = $db->data[umpire2];
    $mmf = $db->data[MomFName];
    $mml = $db->data[MomLName];
    $u1f = $db->data[Ump1FName];
    $u1l = $db->data[Ump1LName];
    $u2f = $db->data[Ump2FName];
    $u2l = $db->data[Ump2LName];
    
    
    
    $da = sqldate_to_string($db->data[game_date]);

    $bat1st = $db->data[BatFirstName];
    $bat1stid = $db->data[BatFirstID];

    $bat2nd = $db->data[BatSecondName];
    $bat2ndid = $db->data[BatSecondID];
    
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";
    //echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    //echo "<tr>\n";
    //echo "  <td align=\"left\" valign=\"top\">\n";
    //echo "  <a href=\"/index.php\">Home</a> &gt; Scorecards</p>\n";
    //echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
    //echo "</tr>\n";
    //echo "</table>\n";

    // Display the Game Header

    echo "<p>Game # $id<br>\n";
    echo "$ht v $at<br>\n";
    echo "Played at $gr<br>\n";
    echo "$da</p>\n";

    echo "<p><b>Result:</b> $re<br>\n";
    if($po != "") echo "<b>Points:</b> $po</p>\n";

    if($tt != "") {
      echo "<p><b>Toss:</b> $tt<br>\n";
    } else {
      echo "<p><b>Toss:</b> n/a<br>";
    }
    
    if($u1 != "" && $u2 != "") {
    echo "<b>Umpires:</b> $u1f $u1l and $u2f $u2l<br>\n";
    } else {
      echo "<b>Umpires:</b> n/a<br>";
    }
    
    if($mm != "") {
    echo "<b>Man of Match:</b> $mmf $mml</p>\n";
    } else {
    echo "<b>Man of Match:</b> n/a</p>\n";
    }
    
////////////////////////////////////////////////////////////////////////////////////////////
//                                Team Batting First                                      //
////////////////////////////////////////////////////////////////////////////////////////////

    // Display the Batting 1st Details

    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"85%\" align=\"left\" colspan=\"3\"><b>$bat1st innings</b> ($mo overs maximum)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>B</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;<b>4</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">&nbsp;<b>6</b></td>\n";
    echo " </tr>\n";

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
      s.game_id = $game_id AND s.innings_id = 1
    ORDER BY
      s.batting_position

    ");

    for ($x=0; $x<$db->rows; $x++) {
    $db->GetRow($x);

    $pid = $db->data[BatterID];
    $pln = $db->data[BatterLName];
    $pfn = $db->data[BatterFName];
    $pin = $db->data[BatterFInitial];
    $bln = $db->data[BowlerLName];
    $bfn = $db->data[BowlerFName];
    $bin = $db->data[BowlerFInitial];
    $aln = $db->data[AssistLName];
    $afn = $db->data[AssistFName];
    $ain = $db->data[AssistFInitial];
    $out = $db->data[HowOutAbbrev];
    $oid = $db->data[HowOutID];
    $run = $db->data[runs];
    $bal = $db->data[balls];
    $fou = $db->data[fours];
    $six = $db->data[sixes];

    echo " <tr>\n";

    // If Batter Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } else {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }
    echo "  </td>\n";

    // If Assist Last Name is blank, just use first name

    // Make caught and bowled (c & part) align right in the assist column
    if($oid == 5) {

    echo "  <td class=\"scorecard\" width=\"28%\" align=\"right\">";
    echo "  $out&nbsp;";

    } else {

    echo "  <td class=\"scorecard\" width=\"28%\" align=\"left\">";

    // dont display bowled or caught and bowled
    if($oid == 3 || $oid == 5) {
      echo "";
    } else {
      echo "$out ";
    }
    
    // show brackets around the runnout effector

    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      if($oid == 9) echo "(";
      echo "$aln";
      if($oid == 9) echo ")";
    } else {
      if($oid == 9) echo "(";
      echo "$afn\n";
      if($oid == 9) echo ")";
    }
    
    }
    echo "  </td>\n";

    // If Bowler Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"28%\" align=\"left\">";

    // display bowled if it goes with the wicket type
    if($oid == '3' || $oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {
      echo "b ";
    } else {
      echo "";
    }

    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "") {
      echo "$bln";
    } else {
      echo "$bfn\n";
    }
    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$run</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$bal</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$fou</td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">$six</td>\n";

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

    $by = $db->data[byes];
    $lb = $db->data[legbyes];
    $wi = $db->data[wides];
    $nb = $db->data[noballs];
    $to = $db->data[total];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\">Extras</td>\n";
    echo "  <td class=\"scorecard\" width=\"56%\" align=\"left\" colspan=\"2\">(b $by, lb $lb, w $wi, nb $nb)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$to</td>\n";
    echo "  <td class=\"scorecard\" width=\"11%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";
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

    $wi = $db->data[wickets];
    $to = $db->data[total];
    $ov = $db->data[overs];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\"><b>Total</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"56%\" align=\"left\" colspan=\"2\"><b>($wi wickets, $ov overs)</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b>$to</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"11%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";
    echo " </tr>\n";

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

    $f1 = $db->data[fow1];
    $f2 = $db->data[fow2];
    $f3 = $db->data[fow3];
    $f4 = $db->data[fow4];
    $f5 = $db->data[fow5];
    $f6 = $db->data[fow6];
    $f7 = $db->data[fow7];
    $f8 = $db->data[fow8];
    $f9 = $db->data[fow9];
    $f10 = $db->data[fow10];

    echo "<br>\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td valign=\"top\" class=\"scorecard\" width=\"15%\" align=\"left\"><b>FoW</b></td>\n";
    echo "  <td valign=\"top\"class=\"scorecard\" width=\"85%\" align=\"left\" colspan=\"6\">";

    if($f1 != "777") echo "1-$f1, ";
    if($f2 != "777") echo "2-$f2, ";
    if($f3 != "777") echo "3-$f3, ";
    if($f4 != "777") echo "4-$f4, ";
    if($f5 != "777") echo "5-$f5,<br>";
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

    echo "<table width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\"><b>Bowling</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>O</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>M</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>W</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"37%\" align=\"right\">&nbsp;</td>\n";
    echo " </tr>\n";

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,
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

    $pid = $db->data[BowlerID];
    $pln = $db->data[BowlerLName];
    $pfn = $db->data[BowlerFName];
    $pin = $db->data[BowlerFInitial];
    $ov = $db->data[overs];
    $ma = $db->data[maidens];
    $ru = $db->data[runs];
    $wi = $db->data[wickets];
    $no = $db->data[noballs];
    $wd = $db->data[wides];

    echo " <tr>\n";

    // If Bowler Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } else {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }
    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ov</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ma</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ru</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$wi</td>\n";
    echo "  <td class=\"scorecard\" width=\"37%\" align=\"left\">&nbsp;&nbsp;";

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
    echo " </tr>\n";

    }

    echo "</table>\n";


    echo "<p></p><p></p>\n";


////////////////////////////////////////////////////////////////////////////////////////////
//                                Team Batting Second                                     //
////////////////////////////////////////////////////////////////////////////////////////////


    // Display the Batting 2nd Details

    echo "<table width=\"95%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"85%\" align=\"left\" colspan=\"3\"><b>$bat2nd innings</b> ($mo overs maximum)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;&nbsp;&nbsp;<b>B</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">&nbsp;<b>4</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">&nbsp;<b>6</b></td>\n";
    echo " </tr>\n";

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
      s.game_id = $game_id AND s.innings_id = 2
    ORDER BY
      s.batting_position

    ");

    for ($x=0; $x<$db->rows; $x++) {
    $db->GetRow($x);

    $pid = $db->data[BatterID];
    $pln = $db->data[BatterLName];
    $pfn = $db->data[BatterFName];
    $pin = $db->data[BatterFInitial];
    $bln = $db->data[BowlerLName];
    $bfn = $db->data[BowlerFName];
    $bin = $db->data[BowlerFInitial];
    $aln = $db->data[AssistLName];
    $afn = $db->data[AssistFName];
    $ain = $db->data[AssistFInitial];
    $out = $db->data[HowOutAbbrev];
    $oid = $db->data[HowOutID];
    $run = $db->data[runs];
    $bal = $db->data[balls];
    $fou = $db->data[fours];
    $six = $db->data[sixes];

    echo " <tr>\n";

    // If Batter Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } else {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }
    echo "  </td>\n";

    // If Assist Last Name is blank, just use first name

    // Make caught and bowled (c & part) align right in the assist column
    if($oid == 5) {

    echo "  <td class=\"scorecard\" width=\"28%\" align=\"right\">";
    echo "  $out&nbsp;";

    } else {

    echo "  <td class=\"scorecard\" width=\"28%\" align=\"left\">";

    // dont display bowled or caught and bowled
    if($oid == 3 || $oid == 5) {
      echo "";
    } else {
      echo "$out ";
    }

    // show brackets around the runnout effector

    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      if($oid == 9) echo "(";
      echo "$aln";
      if($oid == 9) echo ")";
    } else {
      if($oid == 9) echo "(";
      echo "$afn\n";
      if($oid == 9) echo ")";
    }

    }
    echo "  </td>\n";

    // If Bowler Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"28%\" align=\"left\">";

    // display bowled if it goes with the wicket type
    if($oid == '3' || $oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {
      echo "b ";
    } else {
      echo "";
    }

    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "") {
      echo "$bln";
    } else {
      echo "$bfn\n";
    }
    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$run</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$bal</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$fou</td>\n";
    echo "  <td class=\"scorecard\" width=\"3%\" align=\"right\">$six</td>\n";

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

    $by = $db->data[byes];
    $lb = $db->data[legbyes];
    $wi = $db->data[wides];
    $nb = $db->data[noballs];
    $to = $db->data[total];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\">Extras</td>\n";
    echo "  <td class=\"scorecard\" width=\"56%\" align=\"left\" colspan=\"2\">(b $by, lb $lb, w $wi, nb $nb)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$to</td>\n";
    echo "  <td class=\"scorecard\" width=\"11%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";
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

    $wi = $db->data[wickets];
    $to = $db->data[total];
    $ov = $db->data[overs];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\"><b>Total</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"56%\" align=\"left\" colspan=\"2\"><b>($wi wickets, $ov overs)</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\"><b>$to</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"11%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";
    echo " </tr>\n";

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

    $f1 = $db->data[fow1];
    $f2 = $db->data[fow2];
    $f3 = $db->data[fow3];
    $f4 = $db->data[fow4];
    $f5 = $db->data[fow5];
    $f6 = $db->data[fow6];
    $f7 = $db->data[fow7];
    $f8 = $db->data[fow8];
    $f9 = $db->data[fow9];
    $f10 = $db->data[fow10];

    echo "<br>\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td valign=\"top\" class=\"scorecard\" width=\"15%\" align=\"left\"><b>FoW</b></td>\n";
    echo "  <td valign=\"top\"class=\"scorecard\" width=\"85%\" align=\"left\" colspan=\"6\">";


    if($f1 != "777") echo "1-$f1, ";
    if($f2 != "777") echo "2-$f2, ";
    if($f3 != "777") echo "3-$f3, ";
    if($f4 != "777") echo "4-$f4, ";
    if($f5 != "777") echo "5-$f5,<br>";
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

    echo "<table width=\"90%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\"><b>Bowling</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>O</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>M</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>R</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\"><b>W</b></td>\n";
    echo "  <td class=\"scorecard\" width=\"37%\" align=\"right\">&nbsp;</td>\n";
    echo " </tr>\n";

    $db->Query("

    SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,
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

    $pid = $db->data[BowlerID];
    $pln = $db->data[BowlerLName];
    $pfn = $db->data[BowlerFName];
    $pin = $db->data[BowlerFInitial];
    $ov = $db->data[overs];
    $ma = $db->data[maidens];
    $ru = $db->data[runs];
    $wi = $db->data[wickets];
    $no = $db->data[noballs];
    $wd = $db->data[wides];

    echo " <tr>\n";

    // If Bowler Last Name is blank, just use first name

    echo "  <td class=\"scorecard\" width=\"35%\" align=\"left\">";
    if($pln == "" && $pfn == "") {
      echo "n/a";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pin $pln</a>";
    } else {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\">$pfn</a>\n";
    }
    echo "  </td>\n";

    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ov</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ma</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$ru</td>\n";
    echo "  <td class=\"scorecard\" width=\"7%\" align=\"right\">$wi</td>\n";
    echo "  <td class=\"scorecard\" width=\"37%\" align=\"left\">&nbsp;&nbsp;";

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
    echo " </tr>\n";

    }

    echo "</table>\n";

    echo "<hr>\n";

    echo "<p align=\"center\"><a href=\"scorecard.php?schedule=$sc&ccl_mode=1\" class=\"scorecard\">Season Index</a></p>\n";
    echo "<p align=\"center\"><b>Team Pages:</b> <a href=\"/teamdetails.php?teams=$hi&ccl_mode=1\" class=\"scorecard\">$ht</a>, <a href=\"/teamdetails.php?teams=$ai&ccl_mode=1\" class=\"scorecard\">$at</a></p>\n";
    echo "<p align=\"center\"><b>Ground Page:</b> <a href=\"/grounds.php?grounds=$gi&ccl_mode=1\" class=\"scorecard\">$gr Cricket Ground</a></p>\n";


    echo "<p align=\"left\">&nbsp;</p>\n";
    echo "<p align=\"left\">Scorecard &copy; Michael Doig & Rajesh Joshi</p>\n";

    echo "<hr>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg[login],$dbcfg[pword],$dbcfg[server]);
$db->SelectDB($dbcfg[db]);



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
