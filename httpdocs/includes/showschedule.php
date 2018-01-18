<?php

//------------------------------------------------------------------------------
// Colorado Cricket Schedule v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_schedule_listing($db,$schedule,$id,$pr,$team,$week)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT sch.season, se.SeasonName FROM schedule sch INNER JOIN seasons se ON sch.season = se.SeasonID GROUP BY sch.season ORDER BY se.SeasonName DESC")) {
        $db->QueryRow("SELECT sch.season, se.SeasonName FROM schedule sch INNER JOIN seasons se ON sch.season = se.SeasonID GROUP BY sch.season ORDER BY se.SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Schedule</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Schedules</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Select Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Select a season schedule</td>\n";
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



function show_schedule($db,$schedule,$id,$pr,$team,$week)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

		if (!$db->Exists("SELECT * FROM schedule ORDER BY week")) {
    //    if (!$db->Exists("SELECT * FROM schedule ORDER BY date, time")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Schedule</font></p>\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p>&laquo;<a href=\"/index.php\">back to homepage</a></p>\n";
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

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data[TeamName];
                }


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/schedule.php\">Schedule</a> &raquo; <font class=\"10px\">{$seasons[$schedule]}</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">{$seasons[$schedule]} Schedule</b><br>Games typically start at 10am. Click the ground for directions.<br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        // List by team for schedule 

        echo "<p class=\"10px\">Team: ";
        //16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
        //$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
        $db->QueryRow("SELECT t.* FROM teams t, schedule s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
        
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $id = $db->data[TeamID];
            $ab = $db->data['TeamAbbrev'];
            echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
        }
        echo "</p>\n";

        // List by week for schedule
        
        echo "<p class=\"10px\">Week: ";
        $db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY week");
//	 $db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY date, time");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $wk = $db->data[week];
            $da  = $db->data['formatted_date'];
        echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";                
        }
        echo "</p>\n";
            
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

        $db->Query("SELECT sch.season, se.SeasonName FROM schedule sch INNER JOIN seasons se ON sch.season = se.SeasonID GROUP BY sch.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data[season];

        echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
        }
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Detail Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE SCHEDULE</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";

        echo "<tr class=\"colhead\">\n";
        echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
        echo "  <td align=\"left\"><b>TIME</b></td>\n";
        echo "  <td align=\"left\"><b>GAME</b></td>\n";
        echo "  <td align=\"left\" class=\"9px\"><b>UMPIRES</b></td>\n";
        echo "  <td align=\"left\"><b>GROUND</b></td>\n";
        echo "  <td align=\"left\"><b>SCORECARD</b></td>\n";
        echo "</tr>\n";

    if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
        echo "<tr class=\"trrow2\">\n";
        echo "  <td align=\"left\"<p>No games.</p></td>\n";
        echo "  <td align=\"left\">&nbsp;</td>\n";
        echo "  <td align=\"left\">&nbsp;</td>\n";
        echo "  <td align=\"left\">&nbsp;</td>\n";
        echo "  <td align=\"left\">&nbsp;</td>\n";
        echo "  <td align=\"left\">&nbsp;</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";

    } else {
        $db->Query("
        SELECT
          sch.*, Date_Format(time,'%h:%i%p') AS thetime, u1.TeamAbbrev AS Ump1Abbrev,u2.TeamAbbrev AS Ump2Abbrev,te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev, grn.GroundName AS ground, s.game_id AS gameid, s.result AS result
        FROM
          schedule sch
        INNER JOIN
          grounds grn
        ON
          sch.venue = grn.GroundID
        INNER JOIN
          teams te
        ON
          sch.awayteam = te.TeamID
        INNER JOIN
          teams t1
        ON
          sch.hometeam = t1.TeamID
        LEFT JOIN
          teams u1
        ON
          sch.umpire1 = u1.TeamID
        LEFT JOIN
          teams u2
        ON
          sch.umpire2 = u2.TeamID
        LEFT JOIN
          scorecard_game_details s
        ON
          s.awayteam = sch.awayteam and s.hometeam = sch.hometeam and s.season = sch.season and Date_Format(sch.date,'%Y-%m-%d') = s.game_date
        WHERE
          sch.season=$schedule
        ORDER BY
          sch.week, sch.date, sch.time");

        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $t1 = $db->data[homeabbrev];
            $t2 = $db->data[awayabbrev];
            $um = $db->data[umpireabbrev];
            $t1id = $db->data[homeid];
            $t2id = $db->data[awayid];
            $umid = $db->data[umpireid];
            $t = htmlentities(stripslashes($db->data[TeamName]));
            $d = sqldate_to_string($db->data[date]);
            $v = htmlentities(stripslashes($db->data[ground]));
            $vl = htmlentities(stripslashes($db->data[venue]));
            $u1 = $db->data[Ump1Abbrev];
            $u2 = $db->data[Ump2Abbrev];
            $ti = $db->data[thetime];
			$gid = $db->data[gameid];
			$re = $db->data[result];


        if($x % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }
            echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
            echo "  <td align=\"left\">$ti</td>\n";
            echo "  <td align=\"left\" class=\"9px\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
            echo "  <td align=\"left\" class=\"9px\">$u1";
            if($u2 != "") echo " and $u2";
            echo "  </td>\n";           
            echo "  <td align=\"left\" class=\"9px\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
            echo "  <td align=\"left\"><a href=\"/scorecardfull.php?game_id=$gid&ccl_mode=4\">$re</a></td>\n";
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

function show_schedule_team($db,$schedule,$id,$pr,$team,$week)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM schedule")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/schedule.php\">Schedule</a> &raquo; <font class=\"10px\">Team Schedule</font></p>\n";
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
        	$db->Query("SELECT t.* FROM teams t, schedule s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
                
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
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
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/schedule.php\">Schedule</a> &raquo; <font class=\"10px\">{$seasons[$schedule]} - {$teams[$team]}</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">{$seasons[$schedule]} Schedule for {$teams[$team]}</b><br>Games typically start at 10am. Click the ground for directions.<br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        // List by team for schedule 

                echo "<p class=\"10px\">Team: ";
            //16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
	    //$db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
            $db->Query("SELECT t.* FROM teams t, schedule s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
            
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data[TeamID];
                $ab = $db->data['TeamAbbrev'];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for schedule
        
            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data[week];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";                
            }
            echo "</p>\n";
            
        // List by season for schedule

        
            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT sch.season, se.SeasonName FROM schedule sch INNER JOIN seasons se ON sch.season = se.SeasonID GROUP BY sch.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data['SeasonName'];
                $sid = $db->data[season];
                
            echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
            }
            echo "    </select></p>\n";
            

            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";
            
    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Box
    //////////////////////////////////////////////////////////////////////////////////////////
    
                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE SCHEDULE </td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\"><b>TIME</b></td>\n";
            echo "  <td align=\"left\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\"><b>UMPIRES</b></td>\n";
            echo "  <td align=\"left\"><b>GROUND</b></td>\n";
            echo "  <td align=\"left\"><b>SCORECARD</b></td>\n";
			echo "</tr>\n";
            
        if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";

            
        } else {
            $db->Query("
            SELECT
              sch.*,Date_Format(time,'%h:%i%p') AS thetime, u1.TeamAbbrev AS Ump1Abbrev,u2.TeamAbbrev AS Ump2Abbrev,te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev, grn.GroundName AS ground, s.game_id AS gameid, s.result AS result
            FROM
              schedule sch
            INNER JOIN
              grounds grn
            ON
              sch.venue = grn.GroundID
            INNER JOIN
              teams te
            ON
              sch.awayteam = te.TeamID
            INNER JOIN
              teams t1
            ON
              sch.hometeam = t1.TeamID
            LEFT JOIN
              teams u1
            ON
              sch.umpire1 = u1.TeamID
            LEFT JOIN
              teams u2
            ON
              sch.umpire2 = u2.TeamID
            LEFT JOIN
			  scorecard_game_details s
			ON
			  s.awayteam = sch.awayteam and s.hometeam = sch.hometeam and s.season = sch.season and Date_Format(sch.date,'%Y-%m-%d') = s.game_date
			WHERE
              sch.season=$schedule
            AND
            (sch.awayteam=$team OR sch.hometeam=$team)
            ORDER BY
              sch.week, sch.date, sch.time");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data[homeabbrev];
                $t2 = $db->data[awayabbrev];
                $um = $db->data[umpireabbrev];
                $t1id = $db->data[homeid];
                $t2id = $db->data[awayid];
                $umid = $db->data[umpireid];
                $t = htmlentities(stripslashes($db->data[TeamName]));
                $d = sqldate_to_string($db->data[date]);
                $v = htmlentities(stripslashes($db->data[ground]));
                $vl = htmlentities(stripslashes($db->data[venue]));
                $u1 = $db->data[Ump1Abbrev];
                $u2 = $db->data[Ump2Abbrev];
            	$ti = $db->data[thetime];
				$gid = $db->data[gameid];
				$re = $db->data[result];

            if($x % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }
                echo "  <td border=\"1\" align=\"left\" class=\"9px\">$d</td>\n";
                echo "  <td border=\"1\" align=\"left\" class=\"9px\">$ti</td>\n";
                echo "  <td border=\"1\" align=\"left\" class=\"9px\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "  <td align=\"left\" class=\"9px\">$u1";
                if($u2 != "") echo " and $u2";
                echo "  </td>\n";               
                echo "  <td border=\"1\" align=\"left\" class=\"9px\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
                echo "  <td align=\"left\"><a href=\"/scorecardfull.php?game_id=$gid&ccl_mode=4\">$re</a></td>\n";
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


function show_schedule_week($db,$schedule,$id,$pr,$team,$week)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM schedule")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM schedule ORDER BY id");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $weeks[$db->data[week]] = $db->data[week];
                }


                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

            	//16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
		//$db->Query("SELECT * FROM teams WHERE LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
	        $db->Query("SELECT t.* FROM teams t, schedule s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
                
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
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
            echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/schedule.php\">Schedule</a> &raquo; <font class=\"10px\">{$seasons[$schedule]} - Week {$weeks[$week]}</font></p>\n";
            echo "  </td>\n";
            //echo "  <td align=\"right\" valign=\"top\">\n";
            //require ("includes/navtop.php");
            //echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


            echo "<b class=\"16px\">{$seasons[$schedule]} Schedule for Week {$weeks[$week]}</b><br>Games typically start at 10am. Click the ground for directions.<br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        // List by team for schedule 

                echo "<p class=\"10px\">Team: ";
            //16-Apr-2017 - Bodha - Modified to list only the teams associated with the scorecards for that particular season
	    //$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 OR LeagueID = 4 ORDER BY TeamName");
	    $db->QueryRow("SELECT t.* FROM teams t, schedule s WHERE (s.awayteam = t.TeamID OR s.hometeam = t.TeamID) AND s.season=$schedule AND (s.league_id = 1 OR league_id = 4) GROUP BY t.TeamID ORDER BY TeamName");
            
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data[TeamID];
                $ab = $db->data['TeamAbbrev'];
                echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
            }
            echo "</p>\n";

        // List by week for schedule
        
            echo "<p class=\"10px\">Week: ";
            $db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY week");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $wk = $db->data[week];
                $da  = $db->data['formatted_date'];
            echo "    <a href=\"$PHP_SELF?schedule=$schedule&week=$wk&ccl_mode=3\">$wk</a> |\n";                
            }
            echo "</p>\n";
            
        // List by season for schedule

        
            echo "<p class=\"10px\">Season: ";
                echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "<option value=\"\" selected>year</option>\n";

            $db->Query("SELECT sch.season, se.SeasonName FROM schedule sch INNER JOIN seasons se ON sch.season = se.SeasonID GROUP BY sch.season ORDER BY se.SeasonName DESC");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $sen = $db->data['SeasonName'];
                $sid = $db->data[season];
                
            echo "    <option value=\"$PHP_SELF?schedule=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
            }
            echo "    </select></p>\n";
            
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Box
    //////////////////////////////////////////////////////////////////////////////////////////
    
                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE SCHEDULE </td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"1\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>TIME</b></td>\n";
            echo "  <td align=\"left\"><b>GAME</b></td>\n";
            echo "  <td align=\"left\" class=\"9px\"><b>UMPIRES</b></td>\n";
            echo "  <td align=\"left\"><b>GROUND</b></td>\n";
            echo "  <td align=\"left\"><b>SCORECARD</b></td>\n";
			echo "</tr>\n";
            
        if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("
            SELECT
              sch.*,Date_Format(time,'%h:%i%p') AS thetime, u1.TeamAbbrev AS Ump1Abbrev,u2.TeamAbbrev AS Ump2Abbrev,te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev, grn.GroundName AS ground, s.game_id AS gameid, s.result AS result
            FROM
              schedule sch
            INNER JOIN
              grounds grn
            ON
              sch.venue = grn.GroundID
            INNER JOIN
              teams te
            ON
              sch.awayteam = te.TeamID
            INNER JOIN
              teams t1
            ON
              sch.hometeam = t1.TeamID
            LEFT JOIN
              teams u1
            ON
              sch.umpire1 = u1.TeamID
            LEFT JOIN
              teams u2
            ON
              sch.umpire2 = u2.TeamID
            LEFT JOIN
			  scorecard_game_details s
			ON
			  s.awayteam = sch.awayteam and s.hometeam = sch.hometeam and s.season = sch.season and Date_Format(sch.date,'%Y-%m-%d') = s.game_date
			WHERE
              sch.season=$schedule
            AND
              sch.week=$week
            ORDER BY
              sch.date, sch.time");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data[homeabbrev];
                $t2 = $db->data[awayabbrev];
                $um = $db->data[umpireabbrev];
                $t1id = $db->data[homeid];
                $t2id = $db->data[awayid];
                $umid = $db->data[umpireid];
                $t = htmlentities(stripslashes($db->data[TeamName]));
                $dt = sqldate_to_string($db->data[date]);
                $v = htmlentities(stripslashes($db->data[ground]));
                $vl = htmlentities(stripslashes($db->data[venue]));
                $u1 = $db->data[Ump1Abbrev];
                $u2 = $db->data[Ump2Abbrev];
                $ti = $db->data[thetime];
				$gid = $db->data[gameid];
				$re = $db->data[result];

            if($x % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }
                echo "  <td align=\"left\" class=\"9px\">$dt</td>\n";
                echo "  <td align=\"left\" class=\"9px\">$ti</td>\n";
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "  <td align=\"left\" class=\"9px\">$u1";
                if($u2 != "") echo " and $u2";
                echo "  </td>\n";
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
                echo "  <td align=\"left\"><a href=\"/scorecardfull.php?game_id=$gid&ccl_mode=4\">$re</a></td>\n";
				echo "</tr>\n";
                }
        }
                        echo "</table>\n";



                            echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table><br>\n";


                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table><br>\n";

        }
}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



switch($ccl_mode) {
case 0:
    show_schedule_listing($db,$schedule,$id,$pr,$team,$week);
    break;
case 1:
    show_schedule($db,$schedule,$id,$pr,$team,$week);
    break;
case 2:
    show_schedule_team($db,$schedule,$id,$pr,$team,$week);
    break;
case 3:
    show_schedule_week($db,$schedule,$id,$pr,$team,$week);
    break;
default:
    show_schedule_listing($db,$schedule,$id,$pr,$team,$week);
    break;
}

?>

