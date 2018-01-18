<?php

//------------------------------------------------------------------------------
// Scorecards v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_schedule_listing($db,$schedule,$id,$pr,$team,$week,$game_id)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM seasons")) {
        $db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &gt; Scorecards</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("includes/navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Tennis Scorecards</b><br><br>\n";

        // Scorecards Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the schedule archives</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        echo "<form action=\"$PHP_SELF\">";
        echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
        echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

        // Scorecards Select Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Select a season schedule</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "    <option>select a season</option>\n";
        echo "    <option>===============</option>\n";

        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->BagAndTag();

            // output
            $id = $db->data[SeasonID];

            echo "    <option value=\"$PHP_SELF?schedule=$id&ccl_mode=1\">" . $db->data[SeasonName] . " season</option>\n";

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

        if (!$db->Exists("SELECT * FROM tennis_scorecard_game_details")) {

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

                $db->Query("SELECT * FROM tennisteams ORDER BY TeamName");
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
            echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/scorecard.php\">Scorecards</a> &raquo; {$seasons[$schedule]}</p>\n";
            echo "  </td>\n";
            echo "  <td align=\"right\" valign=\"top\">\n";
            require ("includes/navtop.php");
            echo "  </td>\n";
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
            $db->QueryRow("SELECT * FROM tennisteams WHERE TeamActive=1 ORDER BY TeamName");
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
            $db->Query("SELECT * FROM tennis_scorecard_game_details WHERE season = $schedule GROUP BY week");
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

            $db->Query("SELECT ga.season, se.SeasonName FROM tennis_scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC");
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
                echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>LEAGUE SCORECARDS</b></td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";
            
            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
            echo "</tr>\n";         
            
        if (!$db->Exists("SELECT * FROM tennis_scorecard_game_details WHERE season=$schedule")) {
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
              tennis_scorecard_game_details s
            INNER JOIN
              tennisteams a ON s.awayteam = a.TeamID
            INNER JOIN
              tennisteams h ON s.hometeam = h.TeamID
            WHERE
              s.season=$schedule AND s.isactive=0
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
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";

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

        if (!$db->Exists("SELECT * FROM tennis_scorecard_game_details")) {

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

                $db->Query("SELECT * FROM tennisteams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data[TeamAbbrev];
                        $teamname[$db->data[TeamID]] = $db->data[TeamName];
                        $teamcolour[$db->data[TeamID]] = $db->data[TeamColour];
                        $teamaway = $teams;
                        $teamhome = $teams;
                        }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";

            echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";
            echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/scorecard.php\">Scorecards</a> &raquo; {$seasons[$schedule]}</p>\n";
            echo "  </td>\n";
            echo "  <td align=\"right\" valign=\"top\">\n";
            require ("includes/navtop.php");
            echo "  </td>\n";
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
            $db->QueryRow("SELECT * FROM tennisteams WHERE TeamActive=1 ORDER BY TeamName");
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
            $db->Query("SELECT * FROM tennis_scorecard_game_details WHERE season = $schedule GROUP BY week");
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

            $db->Query("SELECT ga.season, se.SeasonName FROM tennis_scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC");
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
                echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;<b>LEAGUE SCORECARDS</b></td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";
            
            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
            echo "</tr>\n"; 
            
            if (!$db->Exists("SELECT * FROM tennis_scorecard_game_details WHERE season=$schedule")) {
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
              tennis_scorecard_game_details s
            INNER JOIN
              tennisteams a ON s.awayteam = a.TeamID
            INNER JOIN
              tennisteams h ON s.hometeam = h.TeamID
             WHERE
              s.season=$schedule AND (s.awayteam=$team OR s.hometeam=$team)
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
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";

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


function show_schedule_week($db,$schedule,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM tennis_scorecard_game_details")) {

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
        
                $db->Query("SELECT week FROM tennis_scorecard_game_details GROUP BY week");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $weeks[$db->data[week]] = $db->data[week];
                }        

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
                }

                $db->Query("SELECT * FROM tennisteams ORDER BY TeamName");
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
            echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/scorecard.php\">Scorecards</a> &raquo; {$seasons[$schedule]}</p>\n";
            echo "  </td>\n";
            echo "  <td align=\"right\" valign=\"top\">\n";
            require ("includes/navtop.php");
            echo "  </td>\n";
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
            $db->QueryRow("SELECT * FROM tennisteams WHERE TeamActive=1 ORDER BY TeamName");
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
            $db->Query("SELECT * FROM tennis_scorecard_game_details WHERE season = $schedule GROUP BY week");
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

            $db->Query("SELECT ga.season, se.SeasonName FROM tennis_scorecard_game_details ga INNER JOIN seasons se ON ga.season = se.SeasonID GROUP BY ga.season ORDER BY se.SeasonName DESC");
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
                echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>LEAGUE SCORECARDS</b></td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";
            
            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
            echo "</tr>\n"; 
            
            if (!$db->Exists("SELECT * FROM tennis_scorecard_game_details WHERE season=$schedule")) {
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
              tennis_scorecard_game_details s
            INNER JOIN
              tennisteams a ON s.awayteam = a.TeamID
            INNER JOIN
              tennisteams h ON s.hometeam = h.TeamID
             WHERE
              s.season=$schedule AND s.week=$week
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
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";

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
      g.GroundID, g.GroundName
    FROM
      tennis_scorecard_game_details s
    INNER JOIN
      grounds g ON s.ground_id = g.GroundID
    INNER JOIN
      tennisteams a ON s.awayteam = a.TeamID
    INNER JOIN
      tennisteams h ON s.hometeam = h.TeamID
    LEFT JOIN
      tennisteams u ON s.umpires = u.TeamID
    LEFT JOIN
      tennisteams t ON s.toss_won_id = t.TeamID
    INNER JOIN
      tennisteams b ON s.batting_first_id = b.TeamID
    INNER JOIN
      tennisteams n ON s.batting_second_id = n.TeamID 
    WHERE
      s.game_id=$game_id
    ");

    $db->BagAndTag();

    $id = $db->data[game_id];
    $sc = $db->data[season];
    $ht = $db->data[HomeName];
    $hi = $db->data[HomeID];
    $at = $db->data[AwayName];
    $ai = $db->data[AwayID];
    $ut = $db->data[UmpireName];
    $gr = $db->data[GroundName];
    $gi = $db->data[GroundID];
    $re = $db->data[result];
    $tt = $db->data[WonTossName];
    $mo = $db->data[maxovers];

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
    echo "$gr<br>\n";
    echo "$da</p>\n";

    echo "<p><b>Result:</b> $re</p>\n";

    if($tt != "") {
      echo "<p><b>Toss:</b> $tt<br>\n";
    } else {
      echo "<p><b>Toss:</b> n/a<br>";
    }
    
    if($ut != "") {
    echo "<b>Umpires:</b> $ut</p>\n";
    } else {
      echo "<b>Umpires:</b> n/a</p>";
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
      tennis_scorecard_batting_details s
    LEFT JOIN
      tennisplayers a ON a.PlayerID = s.assist
    LEFT JOIN
      tennisplayers p ON p.PlayerID = s.player_id
    LEFT JOIN
      tennisplayers b ON b.PlayerID = s.bowler
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
      echo "";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\" alt=\"$pfn $pln\">$pin $pln</a>";
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

    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      echo "$ain $aln";
    } else {
      echo "$afn\n";
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
      echo "$bin $bln";
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
      tennis_scorecard_extras_details
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
      tennis_scorecard_total_details
    WHERE
      game_id = $game_id AND innings_id = 1
    ");

    for ($t=0; $t<$db->rows; $t++) {
    $db->GetRow($t);

    $wi = $db->data[wickets];
    $to = $db->data[total];
    $ov = $db->data[overs];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\">Total</td>\n";
    echo "  <td class=\"scorecard\" width=\"56%\" align=\"left\" colspan=\"2\">($wi wickets, $ov overs)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$to</td>\n";
    echo "  <td class=\"scorecard\" width=\"11%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";

    // get FOW details

    $db->Query("
    SELECT
      fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10
    FROM
      tennis_scorecard_fow_details
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
    echo "  <td valign=\"top\" class=\"scorecard\" width=\"15%\" align=\"left\">FoW:</td>\n";
    echo "  <td valign=\"top\"class=\"scorecard\" width=\"85%\" align=\"left\" colspan=\"6\">";

    if($f1 != "") echo "1-$f1, ";
    if($f2 != "") echo "2-$f2, ";
    if($f3 != "") echo "3-$f3, ";
    if($f4 != "") echo "4-$f4, ";
    if($f5 != "") echo "5-$f5,<br>";
    if($f6 != "") echo "6-$f6, ";
    if($f7 != "") echo "7-$f7, ";
    if($f8 != "") echo "8-$f8, ";
    if($f9 != "") echo "9-$f9, ";
    if($f10 != "") echo "10-$f10";

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
      tennis_scorecard_bowling_details s
    LEFT JOIN
      tennisplayers p ON p.PlayerID = s.player_id
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
      echo "";
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
      tennis_scorecard_batting_details s
    LEFT JOIN
      tennisplayers a ON a.PlayerID = s.assist
    LEFT JOIN
      tennisplayers p ON p.PlayerID = s.player_id
    LEFT JOIN
      tennisplayers b ON b.PlayerID = s.bowler
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
      echo "";
    } elseif($pfn != "" && $pln != "") {
      echo "<a href=\"/players.php?players=$pid&ccl_mode=1\" class=\"scorecard\" alt=\"$pfn $pln\">$pin $pln</a>";
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

    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      echo "$ain $aln";
    } else {
      echo "$afn\n";
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
      echo "$bin $bln";
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
      tennis_scorecard_extras_details
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
      tennis_scorecard_total_details
    WHERE
      game_id = $game_id AND innings_id = 2
    ");

    for ($t=0; $t<$db->rows; $t++) {
    $db->GetRow($t);

    $wi = $db->data[wickets];
    $to = $db->data[total];
    $ov = $db->data[overs];

    echo " <tr>\n";
    echo "  <td class=\"scorecard\" width=\"29%\" align=\"left\">Total</td>\n";
    echo "  <td class=\"scorecard\" width=\"56%\" align=\"left\" colspan=\"2\">($wi wickets, $ov overs)</td>\n";
    echo "  <td class=\"scorecard\" width=\"4%\" align=\"right\">$to</td>\n";
    echo "  <td class=\"scorecard\" width=\"11%\" align=\"right\" colspan=\"3\">&nbsp;</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";

    // get FOW details

    $db->Query("
    SELECT
      fow1,fow2,fow3,fow4,fow5,fow6,fow7,fow8,fow9,fow10
    FROM
      tennis_scorecard_fow_details
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
    echo "  <td valign=\"top\" class=\"scorecard\" width=\"15%\" align=\"left\">FoW:</td>\n";
    echo "  <td valign=\"top\"class=\"scorecard\" width=\"85%\" align=\"left\" colspan=\"6\">";


    if($f1 != "") echo "1-$f1, ";
    if($f2 != "") echo "2-$f2, ";
    if($f3 != "") echo "3-$f3, ";
    if($f4 != "") echo "4-$f4, ";
    if($f5 != "") echo "5-$f5,<br>";
    if($f6 != "") echo "6-$f6, ";
    if($f7 != "") echo "7-$f7, ";
    if($f8 != "") echo "8-$f8, ";
    if($f9 != "") echo "9-$f9, ";
    if($f10 != "") echo "10-$f10";


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
      tennis_scorecard_bowling_details s
    LEFT JOIN
      tennisplayers p ON p.PlayerID = s.player_id
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
      echo "";
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


// 24-June-2010
if ($db->Exists("SELECT s.*, p.* FROM tennis_scorecard_game_details s, tennisplayers p where s.mom = p.PlayerID and  s.game_id=$game_id")) {
$db->QueryRow("SELECT s.*, p.* FROM tennis_scorecard_game_details s, tennisplayers p where s.mom = p.PlayerID and  s.game_id=$game_id");
// 24-June-2010 1:34am
   $mm  = $db->data[mom];
    $mmi = $db->data[PlayerID];
    $mmf = $db->data[PlayerFName];
    $mml = $db->data[PlayerLName];
}
else {
   $mm  = "";
    $mmi = "";
    $mmf = "";
    $mml = "";
}

// 24-June-2010 - 1:40am

 echo "<br>\n";

    if($mm != "") {
    	echo "<b>Player of the match: </b><a href=\"/players.php?players=$mmi&ccl_mode=1\" class=\"scorecard\">$mmf $mml</a>\n";
    } else {
    	echo "<b>Player of the match: </b> TBD";
    }

    echo "<hr>\n";

    echo "<p align=\"center\"><a href=\"scorecard.php?schedule=$sc&ccl_mode=1\" class=\"scorecard\">Season Index</a></p>\n";
    echo "<p align=\"center\"><b>Team Pages:</b> <a href=\"/teams.php?teams=$hi&ccl_mode=1\" class=\"scorecard\">$ht</a>, <a href=\"/teams.php?teams=$ai&ccl_mode=1\" class=\"scorecard\">$at</a></p>\n";
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
