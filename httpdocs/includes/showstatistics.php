<?php

//------------------------------------------------------------------------------
// Statistics v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_statistics_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM seasons")) {

// 19-Aug-2009
  $db->QueryRow("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//       $db->QueryRow("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' 
// GROUP BY la.season ORDER BY se.SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Statistics</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("includes/navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Statistics</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // Statistics Select Box
    //////////////////////////////////////////////////////////////////////////////////////////      

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEASON SELECTOR</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

            echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "    <option>year</option>\n";

        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->BagAndTag();

            // output
            $id = $db->data['SeasonID'];
            $seasonname = $db->data['SeasonName'];
            
         	$selected = "";
	        if ($statistics == $seasonname) {
	        	$selected = "selected";
	        }

            echo "    <option value=\"$PHP_SELF?statistics=$seasonname&ccl_mode=1\" $selected>$seasonname</option>\n";
        }
        echo "    <option value=\"$PHP_SELF?statistics=&ccl_mode=1\">all</option>\n";
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // Statistics Choices Box
    //////////////////////////////////////////////////////////////////////////////////////////      
        
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;CAREER STATISTICS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "<br><ul>\n";

        // Show by team statistics

        if ($db->Exists("SELECT * FROM teams")) {
        $db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data['TeamID'];
                $ab = $db->data['TeamName'];

        echo "<li><a href=\"$PHP_SELF?statistics=&team=$id&ccl_mode=2\">Career Team Averages: $ab</a></li>\n";
        }
    }

        echo "</ul>\n";     
        echo "<ul>\n";
        echo "<li><a href=\"$PHP_SELF?option=allcareer&sort=Average&sort2=Runs&ccl_mode=3\">Career Batting: Highest Averages</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=allcareer&sort=Runs&sort2=Average&ccl_mode=3\">Career Batting: Most Runs</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=allcareer&ccl_mode=4\">Career Batting: Highest Innings Scores</li>\n";
        echo "</ul>\n";
        echo "<ul>\n";          
        echo "<li><a href=\"$PHP_SELF?option=allcareer&sort=Average&direction=asc&ccl_mode=5\">Career Bowling: Best Averages</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=allcareer&sort=Wickets&direction=desc&ccl_mode=5\">Career Bowling: Most Wickets</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=allcareer&ccl_mode=6\">Career Bowling: Best Innings Performances</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=allcareer&sort=Balls&direction=desc&ccl_mode=5\">Career Bowling: Workhorses</li>\n";
        echo "</ul>\n";             
        echo "<ul>\n";          
        echo "<li><a href=\"$PHP_SELF?option=allcareer&ccl_mode=7\">Career All-Round: 500 Runs & 50 Wickets</li>\n";
        echo "</ul>\n"; 
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";
        

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <p>There are no statistics in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
    }
}


function show_statistics_byseason($db,$statistics)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Statistics</font></p>\n";
        echo "    <p>There are currently no statisticsd games in the database.</p>\n";
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

                $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
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
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; <font class=\"10px\">$statistics statistics</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

		if(date("Y") == $statistics) {
        echo "<b class=\"16px\">$statistics Statistics</b><br>Including Knock-Outs<br><br>\n";
		}else{
		echo "<b class=\"16px\">$statistics Statistics</b><br><br>\n";
		}

    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP // BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
        echo "    <option value=\"$PHP_SELF?statistics=$sen&ccl_mode=1\" class=\"10px\" $selected>$sen</option>\n";
        }
        echo "    <option value=\"$PHP_SELF?statistics=&ccl_mode=1\" class=\"10px\">all</option>\n";
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Selections Box
    //////////////////////////////////////////////////////////////////////////////////////////
        
        
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;STATISTICS OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "<br><ul>\n";

        // Show by team statistics

        if ($db->Exists("SELECT * FROM teams")) {
        $db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data['TeamID'];
                $ab = $db->data['TeamName'];

        echo "<li><a href=\"$PHP_SELF?statistics=$statistics&team=$id&ccl_mode=2\">Team Averages: $ab</a></li>\n";
        }
    }

        echo "</ul>\n";
        echo "<ul>\n";
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Average&sort2=Runs&ccl_mode=3\">Batting: Highest Averages</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Runs&sort2=Average&ccl_mode=3\">Batting: Most Runs</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&ccl_mode=4\">Batting: Highest Innings Scores</li>\n";
        echo "</ul>\n";
        echo "<ul>\n";          
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Average&direction=asc&ccl_mode=5\">Bowling: Best Averages</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Wickets&direction=desc&ccl_mode=5\">Bowling: Most Wickets</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&ccl_mode=6\">Bowling: Best Innings Performances</li>\n";
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Balls&direction=desc&ccl_mode=5\">Bowling: Workhorses</li>\n";
        echo "</ul>\n";             
      
		
      	//Adding here for Rookies
       echo "<ul>\n";          
       echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Runs&sort2=Average&ccl_mode=8\">Rookie Batting: Most Runs</li>\n";
       echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&sort=Wickets&direction=desc&ccl_mode=9\">Rookie Bowling: Most Wickets</li>\n";
       echo "</ul>\n";             
                 
        echo "<ul>\n";   
        echo "<li><a href=\"$PHP_SELF?option=byseason&statistics=$statistics&ccl_mode=7\">All-Round: 100 Runs & 10 Wickets</li>\n";
        echo "</ul>\n"; 
        echo "</td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        }
}



function show_statistics_team($db,$statistics,$team,$status)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
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
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; <font class=\"10px\">$statistics statistics</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    if(date("Y") == $statistics) {
    echo "<b class=\"16px\">$statistics Statistics for {$teams[$team]}</b><br>Including Knock-Outs<br><br>\n";
    } else {
    echo "<b class=\"16px\">$statistics Statistics for {$teams[$team]}</b><br><br>\n";	
    }
    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\" colspan=\"2\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" style=\"border:0\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
   $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP // BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
        echo "    <option $selected value=\"$PHP_SELF?statistics=$sen&team=$team&ccl_mode=2\" class=\"10px\">$sen</option>\n";        
        }
        echo "    <option value=\"$PHP_SELF?statistics=&team=$team&ccl_mode=2\" class=\"10px\">all</option>\n";

        echo "    </select></p>\n";

        echo "    </td>\n";
		echo "    <td class=\"trrow1\" valign=\"top\" style=\"border:0\" class=\"main\" align=\"right\">\n";
		$sel = "";
		$sel1 = "";
		$sel2 = "";
		if (isset($_GET['status'])) {
			if($_GET['status'] == "2") {
				$sel = "selected";
			}
			else if($_GET['status'] == "0" || $_GET['status'] == "") {
				$sel1 = "selected";
			}
			else if($_GET['status'] == "1") {
				$sel2 = "selected";
			}
		} else {
			$sel1 = "selected";
		}
		echo "PLAYER STATUS: <select id=\"status\" name=\"status\" onChange=\"gotosite(this.options[this.selectedIndex].value); \">";
		echo"<option value=\"$PHP_SELF?statistics=$statistics&team=$team&ccl_mode=2&status=0,1\" $sel>All</option>";
		echo"<option value=\"$PHP_SELF?statistics=$statistics&team=$team&ccl_mode=2&status=0\" $sel1>Active</option>";
		echo"<option value=\"$PHP_SELF?statistics=$statistics&team=$team&ccl_mode=2&status=1\" $sel2>Inactive</option>";
		echo"<\select>";
		echo "    </td>\n";
		
        echo "  </tr>\n";
        echo "</table><br>\n";

        if (!$db->Exists("
    SELECT 
      COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, SUM( s.notout) AS Notouts, 
      s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName 
    FROM 
      scorecard_batting_details s
    INNER JOIN
      players p 
    ON 
      s.player_id = p.PlayerID AND p.isActive IN($status)
    INNER JOIN
      scorecard_game_details g
    ON
      s.game_id = g.game_id
    INNER JOIN
      seasons n 
    ON
      g.season = n.SeasonID
    WHERE 
      (g.league_id=1 OR g.league_id = 4) AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND n.SeasonName LIKE '%{$statistics}%' 
    GROUP BY 
      s.player_id        
        ")) {

    echo " There are no statistics for this year for this team.\n";
            

        } else {

    //////////////////////////////////////////////////////////////////////////////////////////
    // BATTING STATISTICS
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;BATTING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\"><b>#</b></td>\n";
    echo "  <td align=\"left\" width=\"25%\"><b>NAME</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>SR</b></td>\n";
    echo "  <td align=\"center\" width=\"8%\"><b>100</b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>50</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>St</b></td>\n";
    echo " </tr>\n";

    $db->Query("
            SELECT 
              COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, 
              SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 )) ) AS Average, SUM( s.runs ) * 100 / SUM( s.balls ) AS StrikeRate, 
              s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev 
            FROM 
              scorecard_batting_details s
            INNER JOIN
              players p 
            ON 
              s.player_id = p.PlayerID AND p.isActive IN ($status)
            INNER JOIN 
              scorecard_game_details g
            ON
              s.game_id = g.game_id
            INNER JOIN
              seasons n 
            ON
              g.season = n.SeasonID           
            WHERE 
              (g.league_id=1  OR g.league_id = 4) AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND n.SeasonName LIKE '%{$statistics}%' 
            GROUP BY 
              s.player_id
            ORDER BY
              Runs DESC, p.PlayerLName, p.PlayerFName
    ");

		
    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

	// print_r($db->GetRow(22));
	// print_r($db->GetRow(23));
	
    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            

	
    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];
    $labbr = $db->data['PlayerLAbbrev'];
    $scinn = $db->data['Matches'];
    $scrun = $db->data['Runs'];

    $innings = $db->data['Innings'];  

    if($db->data['Notouts'] != 0) {
      $notouts = $db->data['Notouts'];
    } else {
      $notouts = "-";
    }

    if($db->data['Average'] != "") {
      $average = $db->data['Average'];
	  $average = number_format($average, 2);
    } else {
      $average = "-";
    }

    if($db->data['StrikeRate'] != "") {
      $sr = $db->data['StrikeRate'];
	  $sr = number_format($sr, 2);
    } else {
      $sr = "-";
    }

    // Get Hundreds
    
    if (!$subdb->Exists("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.runs >= 100")){
      $schun = "-";
    } else {
    $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.runs >= 100");
    if($subdb->data['Hundred'] != "0") {
      $schun = $subdb->data['Hundred'];   
    } else {
      $schun = "-";
    }
    }

	
    // Get Fifties
    
    if (!$subdb->Exists("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.runs BETWEEN 50 AND 99)")) {
      $scfif = "-";
    } else {
    $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.runs BETWEEN 50 AND 99)");
    if($subdb->data['Fifty'] != "0") {
    $scfif = $subdb->data['Fifty'];   
    } else {
    $scfif = "-";
    }
    }

    // Get Caughts
    
    if (!$subdb->Exists("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.assist = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.how_out = 4 OR b.how_out = 17)")) {
      $scctc = "0";
    } else {
    $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.assist = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.how_out = 4 OR b.how_out = 17)");
      $scctc = $subdb->data['Caught'];    
    }

	
    // Get Caught and Bowleds
    
    if (!$subdb->Exists("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.bowler = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 5")) {
      $sccab = "0";
    } else {
    $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.bowler = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 5");
      $sccab = $subdb->data['CandB']; 
    }

    if($scctc + $sccab != "0") {
      $sccat = $scctc + $sccab;
    } else {
      $sccat = "-";
    }

    // Get Stumpeds
    
    if (!$subdb->Exists("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.assist = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 10")) {
      $scstu = "-";
    } else {
    $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.assist = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 10");
    if($subdb->data['Stumped'] != "0") {
      $scstu = $subdb->data['Stumped'];   
    } else {
      $scstu = "-";
    }
    }
    
	
    // Get Highest Score
    //* 2016-01-18 10:20pm Had to comment this as it was not displaying the Bowling stats especially for 2015 Twenty20 season.
	/*  $subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
      $schig = $subdb->data['HS'];
	  $scnot = $subdb->data['notout'];
	*/
	
	//  echo "$playerid : $schig  : $scnot"; 
	  
	if (!$subdb->Exists("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1")) {
      $schig = "";
	  $scnot = "";
    } else {
    $subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
      $schig = $subdb->data['HS'];
	  $scnot = $subdb->data['notout'];
	}
	
    if($r % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }

    echo "  <td align=\"left\" width=\"2%\">";
    echo ($r+1);
    echo "  </td>\n";
    
    echo "  <td align=\"left\" width=\"25%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";
    } elseif ($fname != "" && $lname == "") {
      echo "$fname\n";
    } else {
      echo "$lname\n";
    }           

    echo "  </a></td>\n";
	
	
    echo "  <td align=\"right\" width=\"6%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$notouts</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$schig";
    if($scnot == "1") echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"9%\">$average</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$sr</td>\n";
    echo "  <td align=\"center\" width=\"8%\">$schun</td>\n";
    echo "  <td align=\"center\" width=\"6%\">$scfif</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scstu</td>\n";  
    	
    echo " </tr>\n";
    
    }

    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // BOWLING STATISTICS           
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\"><b>#</b></td>\n";
    echo "  <td align=\"left\" width=\"30%\"><b>NAME</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BBI</b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>4W</b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>5W</b></td>\n";
    echo "  <td align=\"right\" width=\"15%\"><b>ECO</b></td>\n";
    echo " </tr>\n";

	  
    $db->Query("
    SELECT 
      b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, 
      p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial
    FROM 
      scorecard_bowling_details b 
    INNER JOIN 
      players p 
    ON 
      b.player_id = p.PlayerID AND p.isActive IN ($status)
    INNER JOIN 
      scorecard_game_details g
    ON
      b.game_id = g.game_id 
    INNER JOIN
      seasons n 
    ON
      g.season = n.SeasonID                   
    WHERE 
      (g.league_id=1 OR g.league_id = 4) AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND n.SeasonName LIKE '%{$statistics}%'
    GROUP BY 
      b.player_id
    ORDER BY
      Wickets DESC, p.PlayerLName, p.PlayerFName
    ");

    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

    for ($r=0; $r<$db->rows; $r++) {
      $db->GetRow($r);          

      $playerid = $db->data['player_id'];
      $init = $db->data['PlayerInitial'];
      $fname = $db->data['PlayerFName'];
      $lname = $db->data['PlayerLName'];
      $labbr = $db->data['PlayerLAbbrev'];
      $scmai = $db->data['Maidens'];
      $scbru = $db->data['BRuns'];
      $scwic = $db->data['Wickets'];

      $bnum = $db->data['Balls']; 
      $bovers = Round(($bnum / 6), 2); 
      $bfloor = floor($bovers); 

      if($bovers == $bfloor + 0.17) { 
        $scove = $bfloor + 0.1; 
      } else 
        if($bovers == $bfloor + 0.33) { 
          $scove = $bfloor + 0.2; 
        } else 
          if($bovers == $bfloor + 0.5) { 
        $scove = $bfloor + 0.3;        
        } else 
          if($bovers == $bfloor + 0.67) { 
          $scove = $bfloor + 0.4;        
        } else 
          if($bovers == $bfloor + 0.83) { 
        $scove = $bfloor + 0.5; 
          } else { 
        $scove = $bfloor; 
        }           

    $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.wickets = 4");
    if($subdb->data['fourwickets'] != "0") {
      $scbfo = $subdb->data['fourwickets'];
    } else {
      $scbfo = "-";
    }

    $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.wickets >= 5");
    if($subdb->data['fivewickets'] != "0") {
      $scbfi = $subdb->data['fivewickets'];
    } else {
      $scbfi = "-";
    }           


    if($scbru >= 1 && $scwic >= 1) {
      $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
      $boavg = "-";
    }

    if($scbru >= 1 && $scove >= 0.1) {  
      $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
      $boeco = "-";
    }   

    $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id = 4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    $scbbw = $subdb->data['wickets'];
    $scbbr = $subdb->data['runs'];


    if($r % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }

    echo "  <td align=\"left\" width=\"2%\">";
    echo ($r+1);
    echo "  </td>\n";
    
    echo "  <td align=\"left\" width=\"30%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    
     if($lname == "" && $fname == "") {
      echo "";
     } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
     } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";
     } elseif ($fname != "" && $lname == "") {
      echo "$fname\n";
    } else {
                  echo "$lname\n";
                }   

    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"center\" width=\"4%\">$scbfo</td>\n";
    echo "  <td align=\"center\" width=\"4%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"13%\">$boeco</td>\n";   
    echo " </tr>\n";

    }

    echo "</table>\n";          

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";


        }
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
}


function show_statistics_mostruns($db,$statistics,$sort,$sort2,$option,$team)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
                    
    if(!$db->Exists("SELECT COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id GROUP BY s.player_id")) {
        
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no batting statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no batting statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no batting statistics in the database for the team <b>$team.</b></p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

        } else {


    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    
    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics statistics</p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; Career statistics</p>\n";
    if($option == "teamcareer") echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; {$teams[$team]} statistics</p>\n";
    
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);  
    $inc = ''; 
    if(date("Y") == $statistics) {
    	$inc = "Including Knock-Outs.";
    }
    if($option == "byseason") {
      if($sort == "Average") echo "<b class=\"16px\">$statistics Batting - Highest Averages</b><br> $inc Qualification 25 runs<br><br>\n";
      if($sort == "Runs") echo "<b class=\"16px\">$statistics Batting - Most Runs</b><br>$inc Qualification 25 runs<br><br>\n";
    }
    
    if($option == "allcareer") {
      if($sort == "Average") echo "<b class=\"16px\">Career Batting - Highest Averages</b><br>From <b>$d</b> to the present. Qualification 100 runs<br><br>\n";
      if($sort == "Runs") echo "<b class=\"16px\">Career Batting - Most Runs</b><br>From <b>$d</b> to the present. Qualification 100 runs<br><br>\n";   
    }
    
    if($option == "teamcareer") {
		if($statistics == "") {
			if($sort == "Average") echo "<b class=\"16px\">{$teams[$team]} Career Batting - Highest Averages</b><br>From <b>$d</b> to the present. Qualification 3 innings<br><br>\n";
			if($sort == "Runs") echo "<b class=\"16px\">{$teams[$team]} Career Batting - Most Runs</b><br>From <b>$d</b> to the present. Qualification 3 innings<br><br>\n";  
		} else {
			if($sort == "Average") echo "<b class=\"16px\">$statistics {$teams[$team]} Career Batting - Highest Averages</b><br> Qualification 3 innings<br><br>\n";
			if($sort == "Runs") echo "<b class=\"16px\">$statistics {$teams[$team]} Career Batting - Most Runs</b><br> Qualification 3 innings<br><br>\n";  
		}
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
$db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP // BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
			if($option == "teamcareer") {
				if($sort == "Average") echo "    <option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&sort=Average&sort2=Runs&ccl_mode=3&team=$team\" class=\"10px\">$sen</option>\n";
				if($sort == "Runs")    echo "    <option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&sort=Runs&sort2=Average&ccl_mode=3&team=$team\" class=\"10px\">$sen</option>\n";
			} else {
				if($sort == "Average") echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Average&sort2=Runs&ccl_mode=3\" class=\"10px\">$sen</option>\n";
				if($sort == "Runs")    echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Runs&sort2=Average&ccl_mode=3\" class=\"10px\">$sen</option>\n";
			}
        }
		if($option == "teamcareer") {
			if($sort == "Average") echo "    <option value=\"$PHP_SELF?option=teamcareer&statistics=&sort=Average&sort2=Runs&ccl_mode=3&team=$team\" class=\"10px\">all</option>\n";
			if($sort == "Runs")    echo "    <option value=\"$PHP_SELF?option=teamcareer&statistics=&sort=Runs&sort2=Average&ccl_mode=3&team=$team\" class=\"10px\">all</option>\n";
		} else {
			if($sort == "Average") echo "    <option value=\"$PHP_SELF?option=byseason&statistics=&sort=Average&sort2=Runs&ccl_mode=3\" class=\"10px\">all</option>\n";
			if($sort == "Runs")    echo "    <option value=\"$PHP_SELF?option=byseason&statistics=&sort=Runs&sort2=Average&ccl_mode=3\" class=\"10px\">all</option>\n";
		}
		
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;BATTING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";   
    
    //////////////////////////////////////////////////////////////////////////////////////////
    // begin batting statistics
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\"><b>#</b></td>\n";
    echo "  <td align=\"left\" width=\"23%\"><b>NAME</b></td>\n";
    echo "  <td align=\"center\" width=\"4%\"><b>M</b></td>\n";
    echo "  <td align=\"center\" width=\"4%\"><b>I</b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>SR</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>100</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>50</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>St</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM 2</b></td>\n";
    echo " </tr>\n";

    if($option == "byseason")   $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE n.SeasonName LIKE '%{$statistics}%' AND (g.league_id=1 OR g.league_id=4) GROUP BY s.player_id HAVING (SUM( s.runs )) >=25 ORDER BY $sort DESC, $sort2 DESC");
    if($option == "allcareer")  $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4)  GROUP BY s.player_id HAVING (SUM( s.runs )) >=100 ORDER BY $sort DESC, $sort2 DESC");
    if($option == "teamcareer") {
		if($statistics == "") {
			$db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND (g.league_id=1 OR g.league_id=4)  GROUP BY s.player_id HAVING (COUNT( s.player_id )) >=3 ORDER BY $sort DESC, $sort2 DESC");
		} else {
			$db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND n.SeasonName LIKE '%{$statistics}%' AND (g.league_id=1 OR g.league_id=4)  GROUP BY s.player_id HAVING (COUNT( s.player_id )) >=3 ORDER BY $sort DESC, $sort2 DESC");
		}
	}
    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            

    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];
    $labbr = $db->data['PlayerLAbbrev'];
    $match = $db->data['Matches'];
    $scrun = $db->data['Runs'];
    //$schig = $db->data['HS'];   
    $teama = $db->data['TeamAbbrev'];
    $teamid = $db->data['TeamID'];

    $teama2 = $db->data['TeamAbbrev2'];
    $teamid2 = $db->data['TeamID2'];

    $innings = $db->data['Innings'];

    if($db->data['Notouts'] != 0) {
      $notouts = $db->data['Notouts'];
    } else {
      $notouts = "-";
    }

    if($db->data['Average'] != "") {
      $average = $db->data['Average'];
	  $average = number_format($average, 2);
    } else {
      $average = "-";
    }

    if($db->data['StrikeRate'] != "") {
      $sr = $db->data['StrikeRate'];
	  $sr = number_format($sr, 2);
    } else {
      $sr = "-";
    }

    // Get Hundreds
    
    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND b.runs >= 100");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND b.runs >= 100");
    if($option == "teamcareer") {
		if($statistics == "") {
			$subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND b.runs >= 100");
		} else {
			$subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.runs >= 100");
		}
    }
	
    if($subdb->data['Hundred'] != "0") {
      $schun = $subdb->data['Hundred'];   
    } else {
      $schun = "-";
    }

    // Get Fifties
    
    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND (b.runs BETWEEN 50 AND 99) ");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND (b.runs BETWEEN 50 AND 99) ");
    if($option == "teamcareer") {
		if($statistics == "") {
			$subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND (b.runs BETWEEN 50 AND 99) ");
		} else {
			$subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.runs BETWEEN 50 AND 99) ");
		}
    }
	
    if($subdb->data['Fifty'] != "0") {
    $scfif = $subdb->data['Fifty'];   
    } else {
    $scfif = "-";
    }

    // Get Catches
    
    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND (b.how_out = 4 OR b.how_out = 17)");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND (b.how_out = 4 OR b.how_out = 17)");
    if($option == "teamcareer") {
		if($statistics == "") {
			$subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.assist = $playerid AND (b.how_out = 4 OR b.how_out = 17)");
		} else {
			$subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.assist = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.how_out = 4 OR b.how_out = 17)");
		}
    }
	
      $scctc = $subdb->data['Caught'];    

    // Get Caught and Bowleds

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 5");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid  AND b.how_out = 5");
    if($option == "teamcareer") {
		if($statistics == "") {
			$subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid AND b.how_out = 5");
		} else {
			$subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 5");
		}
    }
	
      $sccab = $subdb->data['CandB']; 

    if($scctc + $sccab != "0") {
      $sccat = $scctc + $sccab;
    } else {
      $sccat = "-";
    }
    
    // Get Stumpings

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 10");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND b.how_out = 10");
    if($option == "teamcareer") {
		if($statistics == "") {
			$subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid AND b.how_out = 10");
		} else {
			$subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.assist = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 10");
		}
    }

    if($subdb->data['Stumped'] != "0") {
      $scstu = $subdb->data['Stumped'];   
    } else {
      $scstu = "-";
    }
    
    // Get Highest Score

    if($option == "byseason") {
		if (!$subdb->Exists("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1")) {
			$schig = "";
			$scnot = "";
		} else {
			$subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
			$schig = $subdb->data['HS'];
			$scnot = $subdb->data['notout'];
		}
	}
    if($option == "allcareer" || $option == "teamcareer") {
		if($statistics == "") {
			if (!$subdb->Exists("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid GROUP BY b.notout ORDER BY HS DESC LIMIT 1")) {
				$schig = "";
				$scnot = "";
			} else {
				$subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
				$schig = $subdb->data['HS'];
				$scnot = $subdb->data['notout'];
			}
		} else {
			if (!$subdb->Exists("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1")) {
				$schig = "";
				$scnot = "";
			} else {
				$subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
				$schig = $subdb->data['HS'];
				$scnot = $subdb->data['notout'];
			}
		}
	}    

    if($r % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }
    
    echo "  <td align=\"left\" width=\"2%\">";
    echo ($r+1);
    echo "  </td>\n";
    echo "  <td align=\"left\" width=\"23%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";    
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"center\" width=\"4%\">$match</td>\n";
    echo "  <td align=\"center\" width=\"4%\">$innings</td>\n";
    echo "  <td align=\"center\" width=\"6%\">$notouts</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$schig";
    if($scnot == "1") echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"7%\">$average</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$sr</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$schun</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scfif</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scstu</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}

// Start function for rookies most runs

function show_statistics_mostruns_rookies($db,$statistics,$sort,$sort2,$option,$team)
{
    global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
	$db_roo_all = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_roo_all->SelectDB($dbcfg['db']);
	$db_roo_sea = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_roo_sea->SelectDB($dbcfg['db']);
	$db_roo_min = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_roo_min->SelectDB($dbcfg['db']);
		
    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
                    
    if(!$db->Exists("SELECT COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id GROUP BY s.player_id")) {
        
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no batting statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no batting statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no batting statistics in the database for the team <b>$team.</b></p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

        } else {


    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    
    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics statistics</p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; Career statistics</p>\n";
    if($option == "teamcareer") echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; {$teams[$team]} statistics</p>\n";
    
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
    $inc = '';
    if(date("Y") == $statistics){
    	$inc = "Including Knock-Outs.";
    }
    if($option == "byseason") {
      if($sort == "Average") echo "<b class=\"16px\">$statistics  - Rookie Batting - Highest Averages</b><br> $inc Qualification 25 runs<br><br>\n";
      if($sort == "Runs") echo "<b class=\"16px\">$statistics  - Rookie Batting - Most Runs</b><br>$inc Qualification 25 runs<br><br>\n";
    }
    
    if($option == "allcareer") {
      if($sort == "Average") echo "<b class=\"16px\">Career Batting - Highest Averages</b><br>From <b>$d</b> to the present. Qualification 100 runs<br><br>\n";
      if($sort == "Runs") echo "<b class=\"16px\">Career Batting - Most Runs</b><br>From <b>$d</b> to the present. Qualification 100 runs<br><br>\n";   
    }
    
    if($option == "teamcareer") {
      if($sort == "Average") echo "<b class=\"16px\">{$teams[$team]} Career Batting - Highest Averages</b><br>From <b>$d</b> to the present. Qualification 3 innings<br><br>\n";
      if($sort == "Runs") echo "<b class=\"16px\">{$teams[$team]} Career Batting - Most Runs</b><br>From <b>$d</b> to the present. Qualification 3 innings<br><br>\n";  
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
$db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP // BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
        if($sort == "Average") echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Average&sort2=Runs&ccl_mode=8\" class=\"10px\">$sen</option>\n";
        if($sort == "Runs")    echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Runs&sort2=Average&ccl_mode=8\" class=\"10px\">$sen</option>\n";
        }
        if($sort == "Average") echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=&sort=Average&sort2=Runs&ccl_mode=8\" class=\"10px\">all</option>\n";
        if($sort == "Runs")    echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=&sort=Runs&sort2=Average&ccl_mode=8\" class=\"10px\">all</option>\n";

        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;BATTING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";   
    
    //////////////////////////////////////////////////////////////////////////////////////////
    // begin batting statistics
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\"><b>#</b></td>\n";
    echo "  <td align=\"left\" width=\"23%\"><b>NAME</b></td>\n";
    echo "  <td align=\"center\" width=\"4%\"><b>M</b></td>\n";
    echo "  <td align=\"center\" width=\"4%\"><b>I</b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>SR</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>100</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>50</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>St</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM 2</b></td>\n";
    echo " </tr>\n";
    $season_year = substr($statistics, 0, 4);
	$final_players = '';
	$db_roo_all->Query(" SELECT DISTINCT playerid, sum(bat.runs) as sum_runs FROM scorecard_game_details gd, scorecard_batting_details bat, players p WHERE gd.game_id = bat.game_id AND bat.player_id = p.playerid AND YEAR( game_date ) = $season_year group by 1 order by sum_runs desc");
	for ($i=0; $i<$db_roo_all->rows; $i++) {
		$db_roo_all->GetRow($i);
		$db_roo_all->BagAndTag();
		$playerid_all = $db_roo_all->data['playerid'];
		//echo $db_roo_all->data[sum_runs]."<BR>";
		$db_roo_min->Query(" SELECT MIN( gd1.game_date ) as min_date  FROM scorecard_game_details gd1, scorecard_batting_details bat1 WHERE gd1.game_id = bat1.game_id AND bat1.player_id = $playerid_all ");
		$j = 0;
		$db_roo_min->GetRow($j);
		$db_roo_min->BagAndTag();
		$min_date = $db_roo_min->data['min_date'];
		if(substr($min_date,0,4) == $season_year) {
			if($final_players == '') {
				$final_players = $playerid_all;
			}else{
				$final_players .= ",". $playerid_all;
			}
		}
	}
	$array_final_player = explode(",", $final_players);
	
	$db_roo_sea->Query("Select player_id, sum(runs) from scorecard_batting_details, seasons where seasons.seasonid=scorecard_batting_details.season AND SeasonName LIKE '%{$statistics}%' AND player_id in (".$final_players.") group by 1 order by 2 desc");	
	
	$i = 0;
    for ($k=0; $k<$db_roo_sea->rows; $k++) {
        $db_roo_sea->GetRow($k);
		$db_roo_sea->BagAndTag();
        $playerid = $db_roo_sea->data['player_id'];
       
  
    if($option == "byseason")   $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE n.SeasonName LIKE '%{$statistics}%' AND s.player_id = ".$playerid ." AND (g.league_id=1 OR g.league_id=4) GROUP BY s.player_id HAVING (SUM( s.runs )) >=25 ORDER BY $sort DESC, $sort2 DESC");
    if($option == "allcareer")  $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4) AND s.player_id = ".$playerid." GROUP BY s.player_id HAVING (SUM( s.runs )) >=100 ORDER BY $sort DESC, $sort2 DESC");
    if($option == "teamcareer") $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.how_out=1 ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, SUM( s.runs ) * 100 / SUM( s.balls) AS StrikeRate, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND (g.league_id=1 OR g.league_id=4) AND s.player_id = ".$playerid." GROUP BY s.player_id HAVING (COUNT( s.player_id )) >=3 ORDER BY $sort DESC, $sort2 DESC");

    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);
	
    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            
	$i = $i + 1;
    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];
    $labbr = $db->data['PlayerLAbbrev'];
    $match = $db->data['Matches'];
    $scrun = $db->data['Runs'];
    //$schig = $db->data['HS'];   
    $teama = $db->data['TeamAbbrev'];
    $teamid = $db->data['TeamID'];
    $teama2 = $db->data['TeamAbbrev2'];
    $teamid2 = $db->data['TeamID2'];

    $innings = $db->data['Innings'];

    if($db->data['Notouts'] != 0) {
      $notouts = $db->data['Notouts'];
    } else {
      $notouts = "-";
    }

    if($db->data['Average'] != "") {
      $average = $db->data['Average'];
    } else {
      $average = "-";
    }

    if($db->data['Average'] != "") {
      $average = $db->data['Average'];
	  $average = number_format($average, 2);
    } else {
      $average = "-";
    }

    if($db->data['StrikeRate'] != "") {
      $sr = $db->data['StrikeRate'];
	  $sr = number_format($sr, 2);
    } else {
      $sr = "-";
    }


    // Get Hundreds
    
    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND b.runs >= 100");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND b.runs >= 100");
    if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.runs >= 100");
    
    if($subdb->data['Hundred'] != "0") {
      $schun = $subdb->data['Hundred'];   
    } else {
      $schun = "-";
    }

    // Get Fifties
    
    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND (b.runs BETWEEN 50 AND 99) ");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND (b.runs BETWEEN 50 AND 99) ");
    if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid  AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND (b.runs BETWEEN 50 AND 99) ");      
    
    if($subdb->data['Fifty'] != "0") {
    $scfif = $subdb->data['Fifty'];   
    } else {
    $scfif = "-";
    }

    // Get Catches
    
    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND (b.how_out = 4 OR b.how_out = 17)");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND (b.how_out = 4 OR b.how_out = 17)");
    if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND (b.opponent = $team OR (b.opponent IN (SELECT PlayerTeam from players where PlayerTeam2 = $team) OR b.opponent IN (SELECT PlayerTeam2 from players where PlayerTeam = $team))) AND (b.how_out = 4 OR b.how_out = 17)");

      $scctc = $subdb->data['Caught'];    

    // Get Caught and Bowleds

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 5");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid  AND b.how_out = 5");
    if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.bowler = $playerid  AND (b.opponent = $team OR (b.opponent IN (SELECT PlayerTeam from players where PlayerTeam2 = $team) OR b.opponent IN (SELECT PlayerTeam2 from players where PlayerTeam = $team))) AND b.how_out = 5");

      $sccab = $subdb->data['CandB']; 

    if($scctc + $sccab != "0") {
      $sccat = $scctc + $sccab;
    } else {
      $sccat = "-";
    }
    
    // Get Stumpings

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND s.SeasonName LIKE '%{$statistics}%' AND b.how_out = 10");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND b.how_out = 10");
    if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.assist = $playerid  AND (b.opponent = $team OR (b.opponent IN (SELECT PlayerTeam from players where PlayerTeam2 = $team) OR b.opponent IN (SELECT PlayerTeam2 from players where PlayerTeam = $team))) AND b.how_out = 10");

    if($subdb->data['Stumped'] != "0") {
      $scstu = $subdb->data['Stumped'];   
    } else {
      $scstu = "-";
    }
    
    // Get Highest Score

    if($option == "byseason") {
		if (!$subdb->Exists("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1")) {
			$schig = "";
			$scnot = "";
		} else {
			$subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
			$schig = $subdb->data['HS'];
			$scnot = $subdb->data['notout'];
		}
	}
    if($option == "allcareer" || $option == "teamcareer") {
		if (!$subdb->Exists("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid GROUP BY b.notout ORDER BY HS DESC LIMIT 1")) {
			$schig = "";
			$scnot = "";
		} else {
			$subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = $playerid GROUP BY b.notout ORDER BY HS DESC LIMIT 1");
			$schig = $subdb->data['HS'];
			$scnot = $subdb->data['notout'];
		}
	}
	
    if($i % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }
    
    echo "  <td align=\"left\" width=\"2%\">";
    echo ($i);
    echo "  </td>\n";
    echo "  <td align=\"left\" width=\"23%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";    
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"center\" width=\"4%\">$match</td>\n";
    echo "  <td align=\"center\" width=\"4%\">$innings</td>\n";
    echo "  <td align=\"center\" width=\"6%\">$notouts</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$schig";
    if($scnot == "1") echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"7%\">$average</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$sr</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$schun</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scfif</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scstu</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";

    }
    }

    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}


// Function Ends




function show_statistics_bestinnings($db,$statistics,$option,$team)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
                    
    if(!$db->Exists("SELECT COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / ((COUNT( s.player_id ) - SUM( s.notout )) - SUM(( s.how_out=1 ))) AS Average, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id GROUP BY s.player_id")) {
        
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no batting statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no batting statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no batting statistics in the database for the team <b>$team.</b></p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

        } else {
        
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    
    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics statistics</p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; Career statistics</p>\n";
    if($option == "teamcareer") echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; {$teams[$team]} statistics</p>\n";
    
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
    $inc = '';
    if(date("Y") == $statistics){
    	$inc = "Including Knock-Outs.";
    }
    if($option == "byseason")   echo "<b class=\"16px\">$statistics Batting - Highest Innings Scores</b><br>$inc Qualification 35 runs.<br><br>\n";
    if($option == "allcareer")  echo "<b class=\"16px\">Career Batting - Highest Innings Scores</b><br>From <b>$d</b> to the present. Qualification 50 runs.<br><br>\n";
    if($option == "teamcareer") {
		if($statistics == "") {
			echo "<b class=\"16px\">{$teams[$team]} Career Batting - Highest Innings Scores</b><br>From <b>$d</b> to the present. Qualification 35 runs.<br><br>\n";
		} else {
			echo "<b class=\"16px\">$statistics {$teams[$team]} Career Batting - Highest Innings Scores</b><br>Qualification 35 runs.<br><br>\n";
		}
	}
            
    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP //BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
			if($option == "teamcareer") {
				echo "    <option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&ccl_mode=4&team=$team\" class=\"10px\">$sen</option>\n";
			} else {
				echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&ccl_mode=4\" class=\"10px\">$sen</option>\n";
			}
        }
		if($option == "teamcareer") {
			echo "    <option value=\"$PHP_SELF?option=teamcareer&statistics=&ccl_mode=4&team=$team\" class=\"10px\">all</option>\n";
		} else {
			echo "    <option value=\"$PHP_SELF?option=byseason&statistics=&ccl_mode=4\" class=\"10px\">all</option>\n";
		}
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // begin highest innings statistics         
    //////////////////////////////////////////////////////////////////////////////////////////
            
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;HIGHEST SCORES</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    
    echo "<tr class=\"colhead\">\n";
    echo "  <td align=\"left\"><b>RUNS</b></td>\n";
    echo "  <td align=\"left\"><b>PLAYER</b></td>\n";
    echo "  <td align=\"left\"><b>MATCH</b></td>\n";
    echo "</tr>\n";
    
    
    if($option == "byseason")   $db->Query("SELECT g.season, n.SeasonName, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS Runs, b.notout, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID INNER JOIN seasons n ON g.season = n.SeasonID WHERE n.SeasonName LIKE '%{$statistics}%' AND Runs >= 35 AND (g.league_id=1 OR g.league_id=4)  ORDER BY Runs DESC");
    if($option == "allcareer")  $db->Query("SELECT g.season, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS Runs, b.notout, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID WHERE Runs >= 50 AND (g.league_id=1 OR g.league_id=4)  ORDER BY Runs DESC");
    if($option == "teamcareer") {
		if($statistics == "") {
			$db->Query("SELECT g.season, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS Runs, b.notout, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID WHERE (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND Runs >= 35 AND (g.league_id=1 OR g.league_id=4)  ORDER BY Runs DESC");
		} else {
			$db->Query("SELECT g.season, n.SeasonName, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS Runs, b.notout, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID INNER JOIN seasons n ON g.season = n.SeasonID WHERE n.SeasonName LIKE '%{$statistics}%' AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND Runs >= 35 AND (g.league_id=1 OR g.league_id=4)  ORDER BY Runs DESC");
		}
	}

    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            

    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];
    $labbr = $db->data['PlayerLAbbrev'];
    $bruns = $db->data['Runs'];
    $notou = $db->data['notout'];
    $awayt = $db->data['awayabbrev'];
    $homet = $db->data['homeabbrev'];
    $groun = $db->data['Ground'];
    $gamed = $db->data['game_date'];
    $gamei = $db->data['game_id'];

    if($r % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }
    

    echo "  <td align=\"left\">$bruns";
    if($notou == "1") echo "*";
    echo "  </td>\n";

    echo "  <td align=\"left\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";   
    echo "  <td align=\"left\">$awayt v $homet at $groun, $gamed [<a href=\"/scorecardfull.php?game_id=$gamei&ccl_mode=4\">$gamei</a>]</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";          

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";


    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}


function show_statistics_bowling($db,$statistics,$sort,$direction,$option, $team)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
    
    
    if (!$db->Exists("SELECT COUNT( s.player_id ) AS Matches, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_bowling_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN seasons n ON g.season = n.SeasonID GROUP BY s.player_id")) {

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no bowling statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no bowling statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no bowling statistics in the database for the team <b>$team.</b></p>\n";
	
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
                
        } else {        
                
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics statistics</p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; Career statistics</p>\n";
    if($option == "teamcareer") {
		if($statistics == "") {
			echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; {$teams[$team]} statistics</p>\n";
		} else {
			echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics {$teams[$team]} statistics</p>\n";
		}
	}

    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
	$inc = '';
	if(date("Y") == $statistics){
		$inc = "Including Knock-Outs. ";
	}
    if($option == "byseason") {
      if($sort == "Wickets") echo "<b class=\"16px\">$statistics Bowling - Most Wickets</b><br>$inc Qualification 1 over.<br><br>\n";
      if($sort == "Balls")   echo "<b class=\"16px\">$statistics Bowling - Workhorses</b><br>$inc Qualification 1 over.<br><br>\n";
      if($sort == "Average") echo "<b class=\"16px\">$statistics Bowling - Best Averages</b><br>$inc  Qualification 1 over.<br><br>\n";
    }

    if($option == "allcareer") {
      if($sort == "Wickets") echo "<b class=\"16px\">Career Bowling - Most Wickets</b><br>From <b>$d</b> to the present. Qualification 50 overs.<br><br>\n";
      if($sort == "Balls")   echo "<b class=\"16px\">Career Bowling - Workhorses</b><br>From <b>$d</b> to the present. Qualification 50 overs.<br><br>\n";
      if($sort == "Average") echo "<b class=\"16px\">Career Bowling - Best Averages</b><br>From <b>$d</b> to the present. Qualification 50 overs.<br><br>\n";
    }

    if($option == "teamcareer") {
		if($statistics == "") {
		  if($sort == "Wickets") echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Most Wickets</b><br>From <b>$d</b> to the present.<br><br>\n";
		  if($sort == "Balls")   echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Workhorses</b><br>From <b>$d</b> to the present.<br><br>\n";
		  if($sort == "Average") echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Best Averages</b><br>From <b>$d</b> to the present.<br><br>\n";
		} else {
		  if($sort == "Wickets") echo "<b class=\"16px\">$statistics {$teams[$team]} Career Bowling - Most Wickets</b><br>\n";
		  if($sort == "Balls")   echo "<b class=\"16px\">$statistics {$teams[$team]} Career Bowling - Workhorses</b><br>\n";
		  if($sort == "Average") echo "<b class=\"16px\">$statistics {$teams[$team]} Career Bowling - Best Averages</b><br>\n";
		}
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
       $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP //BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
			if ($option == "teamcareer") {
              if($sort == "Average") echo "<option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&sort=Average&direction=asc&ccl_mode=5&team=$team\" class=\"10px\">$sen</option>\n";
              if($sort == "Wickets") echo "<option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&sort=Wickets&direction=desc&ccl_mode=5&team=$team\" class=\"10px\">$sen</option>\n";
              if($sort == "Balls") echo "<option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&sort=Balls&direction=desc&ccl_mode=5&team=$team\" class=\"10px\">$sen</option>\n";
			} else {
              if($sort == "Average") echo "<option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Average&direction=asc&ccl_mode=5\" class=\"10px\">$sen</option>\n";
              if($sort == "Wickets") echo "<option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Wickets&direction=desc&ccl_mode=5\" class=\"10px\">$sen</option>\n";
              if($sort == "Balls") echo "<option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Balls&direction=desc&ccl_mode=5\" class=\"10px\">$sen</option>\n";
			}
        }
		if ($option == "teamcareer") {
		  if($sort == "Average") echo "<option value=\"$PHP_SELF?option=teamcareer&statistics=&sort=Average&direction=asc&ccl_mode=5&team=$team\" class=\"10px\">all</option>\n";
		  if($sort == "Wickets") echo "<option value=\"$PHP_SELF?option=teamcareer&statistics=&sort=Wickets&direction=desc&ccl_mode=5&team=$team\" class=\"10px\">all</option>\n";
		  if($sort == "Balls") echo "<option value=\"$PHP_SELF?option=teamcareer&statistics=&sort=Balls&direction=desc&ccl_mode=5&team=$team\" class=\"10px\">all</option>\n";
		} else {
		  if($sort == "Average") echo "<option value=\"$PHP_SELF?option=byseason&statistics=&sort=Average&direction=asc&ccl_mode=5\" class=\"10px\">all</option>\n";
		  if($sort == "Wickets") echo "<option value=\"$PHP_SELF?option=byseason&statistics=&sort=Wickets&direction=desc&ccl_mode=5\" class=\"10px\">all</option>\n";
		  if($sort == "Balls") echo "<option value=\"$PHP_SELF?option=byseason&statistics=&sort=Balls&direction=desc&ccl_mode=5\" class=\"10px\">all</option>\n";
		}
        
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // begin bowling statistics         
    //////////////////////////////////////////////////////////////////////////////////////////
            
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\">#</td>\n";
    echo "  <td align=\"left\" width=\"26%\"><b>NAME</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>BBI</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>4w</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>ECO</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM 2</b></td>\n";
    echo " </tr>\n";

    if($option == "byseason")   $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id AS player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4)  AND n.SeasonName LIKE '%{$statistics}%' GROUP BY b.player_id HAVING Balls >=6 ORDER BY $sort $direction, Average ASC");
    if($option == "allcareer")  $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id AS player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4)  GROUP BY b.player_id HAVING Balls >=300 ORDER BY $sort $direction, Average ASC");
    if($option == "teamcareer") {
		if($statistics == "") {
			$db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id AS player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4)  AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) GROUP BY b.player_id ORDER BY $sort $direction, Average ASC");
		} else {
			$db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id AS player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4) AND n.SeasonName LIKE '%{$statistics}%' AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) GROUP BY b.player_id ORDER BY $sort $direction, Average ASC");
		}
	}
	
    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);
    //
    // serialNumber will be used later to display the serial number
    // of the bowlers with best averages etc.
    //                  Jarrar (2nd August 2005).
        $serialNumber=0;
    for ($r=0; $r<$db->rows; $r++) {
      
      $db->GetRow($r);          

      $playerid = $db->data['player_id'];
      $init = $db->data['PlayerInitial'];
      $fname = $db->data['PlayerFName'];
      $lname = $db->data['PlayerLName'];
      $labbr = $db->data['PlayerLAbbrev'];
      $scmai = $db->data['Maidens'];
      $scbru = $db->data['BRuns'];
      $scwic = $db->data['Wickets'];
      $teama = $db->data['TeamAbbrev'];
      $teamid = $db->data['TeamID']; 
      
      $teama2 = $db->data['TeamAbbrev2'];
      $teamid2 = $db->data['TeamID2'];      

      if($db->data['Average'] != "") {
      $average = $db->data['Average'];
      } else {
        $average = "-";
      }

      $bnum = $db->data['Balls']; 
      $bovers = Round(($bnum / 6), 2); 
      $bfloor = floor($bovers); 

      if($bovers == $bfloor + 0.17) { 
        $scove = $bfloor + 0.1; 
      } else 
      if($bovers == $bfloor + 0.33) { 
        $scove = $bfloor + 0.2; 
      } else 
      if($bovers == $bfloor + 0.5) { 
        $scove = $bfloor + 0.3;        
      } else 
      if($bovers == $bfloor + 0.67) { 
        $scove = $bfloor + 0.4;        
      } else 
      if($bovers == $bfloor + 0.83) { 
        $scove = $bfloor + 0.5; 
      } else { 
        $scove = $bfloor; 
      }                 

      if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.wickets = 4");
      if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND b.wickets = 4");
      if($option == "teamcareer") {
		  if($statistics == "") {
			  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.wickets = 4");
		  } else {
			  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.wickets = 4");
		  }
	  }

      if($subdb->data['fourwickets'] != "0") {
        $scbfo = $subdb->data['fourwickets'];
      } else {
        $scbfo = "-";
      }

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.wickets >= 5");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND b.wickets >= 5");
    if($option == "teamcareer") {
		if($statistics == "") {
		  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.wickets >= 5");
		} else {
		  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.wickets >= 5");
		}
	}

    if($subdb->data['fivewickets'] != "0") {
      $scbfi = $subdb->data['fivewickets'];
    } else {
      $scbfi = "-";
    }           


    if($scbru >= 1 && $scwic >= 1) {
      $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
      $boavg = "-";
    }

    if($scbru >= 1 && $scove >= 0.1) {  
      $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
      $boeco = "-";
    }   

    if($option == "byseason")   $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    if($option == "allcareer")  $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    if($option == "teamcareer") {
		if($statistics == "") {
		  $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
		} else {
		  $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
		}
	}
	
    $scbbw = $subdb->data['wickets'];
    $scbbr = $subdb->data['runs'];
    
    // Hide all those bowlers who haven't yet taken a wicket

    if($scwic != "0") {

    if($r % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"2%\">";
    echo (++$serialNumber);
    echo "  </td>\n";           
    echo "  <td align=\"left\" width=\"26%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";
    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";    
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$average</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scbfo</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$boeco</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";
    
    } else {
    }

    }

    echo "</table>\n";          

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";


    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}

// Adding This for Rookies
function show_statistics_bowling_rookies($db,$statistics,$sort,$direction,$option,$team)
{
    global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
    $db_roo_all = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_roo_all->SelectDB($dbcfg['db']);
	$db_roo_min = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_roo_min->SelectDB($dbcfg['db']);
	$db_roo_sea = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
	$db_roo_sea->SelectDB($dbcfg['db']);
	 
    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
    
    
    if (!$db->Exists("SELECT COUNT( s.player_id ) AS Matches, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_bowling_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN seasons n ON g.season = n.SeasonID GROUP BY s.player_id")) {

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no bowling statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no bowling statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no bowling statistics in the database for the team <b>$team.</b></p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
                
        } else {        
                
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics statistics</p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; Career statistics</p>\n";
    if($option == "teamcareer") echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; {$teams[$team]} statistics</p>\n";

    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
	$inc = '';
	if(date("Y") == $statistics) {
		$inc = "Including Knock-Outs.";
	}
    if($option == "byseason") {
      if($sort == "Wickets") echo "<b class=\"16px\">$statistics - Rookie Bowling - Most Wickets</b><br>$inc Qualification 1 over.<br><br>\n";
      if($sort == "Balls")   echo "<b class=\"16px\">$statistics  - Rookie Bowling - Workhorses</b><br>$inc Qualification 1 over.<br><br>\n";
      if($sort == "Average") echo "<b class=\"16px\">$statistics  - Rookie Bowling - Best Averages</b><br>$inc  Qualification 1 over.<br><br>\n";
    }

    if($option == "allcareer") {
      if($sort == "Wickets") echo "<b class=\"16px\">Career Bowling - Most Wickets</b><br>From <b>$d</b> to the present. Qualification 50 overs.<br><br>\n";
      if($sort == "Balls")   echo "<b class=\"16px\">Career Bowling - Workhorses</b><br>From <b>$d</b> to the present. Qualification 50 overs.<br><br>\n";
      if($sort == "Average") echo "<b class=\"16px\">Career Bowling - Best Averages</b><br>From <b>$d</b> to the present. Qualification 50 overs.<br><br>\n";
    }

    if($option == "teamcareer") {
      if($sort == "Wickets") echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Most Wickets</b><br>From <b>$d</b> to the present.<br><br>\n";
      if($sort == "Balls")   echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Workhorses</b><br>From <b>$d</b> to the present.<br><br>\n";
      if($sort == "Average") echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Best Averages</b><br>From <b>$d</b> to the present.<br><br>\n";
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
       $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP //BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
              if($sort == "Average") echo "<option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Average&direction=asc&ccl_mode=9\" class=\"10px\">$sen</option>\n";
              if($sort == "Wickets") echo "<option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Wickets&direction=desc&ccl_mode=9\" class=\"10px\">$sen</option>\n";
              if($sort == "Balls") echo "<option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&sort=Balls&direction=desc&ccl_mode=9\" class=\"10px\">$sen</option>\n";
        }

              if($sort == "Average") echo "<option value=\"$PHP_SELF?option=byseason&statistics=&sort=Average&direction=asc&ccl_mode=9\" class=\"10px\">all</option>\n";
              if($sort == "Wickets") echo "<option value=\"$PHP_SELF?option=byseason&statistics=&sort=Wickets&direction=desc&ccl_mode=9\" class=\"10px\">all</option>\n";
              if($sort == "Balls") echo "<option value=\"$PHP_SELF?option=byseason&statistics=&sort=Balls&direction=desc&ccl_mode=9\" class=\"10px\">all</option>\n";
        
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // begin bowling statistics         
    //////////////////////////////////////////////////////////////////////////////////////////
            
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\">#</td>\n";
    echo "  <td align=\"left\" width=\"26%\"><b>NAME</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>BBI</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>4w</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>ECO</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>TEAM 2</b></td>\n";
    echo " </tr>\n";
	
    
    $season_year = substr($statistics, 0, 4);
	$final_players = '';
	$db_roo_all->Query(" SELECT DISTINCT playerid FROM scorecard_game_details gd, scorecard_bowling_details bowl, players p WHERE gd.game_id = bowl.game_id AND bowl.player_id = p.playerid AND YEAR( game_date ) = $season_year ");
	for ($i=0; $i<$db_roo_all->rows; $i++) {
		$db_roo_all->GetRow($i);
		$db_roo_all->BagAndTag();
		$playerid_all = $db_roo_all->data['playerid'];
		//echo $db_roo_all->data[sum_runs]."<BR>";
		$db_roo_min->Query(" SELECT MIN( gd1.game_date ) as min_date  FROM scorecard_game_details gd1, scorecard_batting_details bt WHERE gd1.game_id = bt.game_id AND bt.player_id = $playerid_all ");
		$j = 0;
		$db_roo_min->GetRow($j);
		$db_roo_min->BagAndTag();
		$min_date = $db_roo_min->data['min_date'];
			if(substr($min_date,0,4) == $season_year) {
				if($final_players == '') {
					$final_players = $playerid_all;
				}else{
					$final_players .= ",". $playerid_all;
				}
			}
	}
	
	$array_final_player = explode(",", $final_players);
	
	$db_roo_sea->Query("Select player_id, sum(wickets) from scorecard_bowling_details, seasons where seasons.seasonid=scorecard_bowling_details.season AND SeasonName LIKE '%{$statistics}%' AND player_id in (".$final_players.") group by 1 order by 2 desc");
	
	
	$i = 0;
    for ($k=0; $k<$db_roo_sea->rows; $k++) {
        $db_roo_sea->GetRow($k);
        $db_roo_sea->BagAndTag();
		$playerid = $db_roo_sea->data['player_id'];
    
    
    
    if($option == "byseason")   $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4)  AND n.SeasonName LIKE '%{$statistics}%' AND b.player_id = ".$playerid ." GROUP BY b.player_id HAVING Balls >=6 ORDER BY $sort $direction, Average ASC");
    if($option == "allcareer")  $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = ".$playerid ." GROUP BY b.player_id HAVING Balls >=300 ORDER BY $sort $direction, Average ASC");
    if($option == "teamcareer") $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, SUM( b.runs ) / SUM( b.wickets ) AS Average, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4) AND b.player_id = ".$playerid ." AND p.PlayerTeam = $team GROUP BY b.player_id ORDER BY $sort $direction, Average ASC");

    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);
    //
    // serialNumber will be used later to display the serial number
    // of the bowlers with best averages etc.
    //                  Jarrar (2nd August 2005).
        $serialNumber=0;
    for ($r=0; $r<$db->rows; $r++) {
      
      $db->GetRow($r);          
		$i = $i + 1;
      $playerid = $db->data['player_id'];
     
      $init = $db->data['PlayerInitial'];
      $fname = $db->data['PlayerFName'];
      $lname = $db->data['PlayerLName'];
      $labbr = $db->data['PlayerLAbbrev'];
      $scmai = $db->data['Maidens'];
      $scbru = $db->data['BRuns'];
      $scwic = $db->data['Wickets'];
      $teama = $db->data['TeamAbbrev'];
      $teamid = $db->data['TeamID']; 
      $teama2 = $db->data['TeamAbbrev2'];
      $teamid2 = $db->data['TeamID2']; 
      
      if($db->data['Average'] != "") {
      $average = $db->data['Average'];
      } else {
        $average = "-";
      }

      $bnum = $db->data['Balls']; 
      $bovers = Round(($bnum / 6), 2); 
      $bfloor = floor($bovers); 

      if($bovers == $bfloor + 0.17) { 
        $scove = $bfloor + 0.1; 
      } else 
      if($bovers == $bfloor + 0.33) { 
        $scove = $bfloor + 0.2; 
      } else 
      if($bovers == $bfloor + 0.5) { 
        $scove = $bfloor + 0.3;        
      } else 
      if($bovers == $bfloor + 0.67) { 
        $scove = $bfloor + 0.4;        
      } else 
      if($bovers == $bfloor + 0.83) { 
        $scove = $bfloor + 0.5; 
      } else { 
        $scove = $bfloor; 
      }                 

      if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.wickets = 4");
      if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND b.wickets = 4");
      if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.wickets = 4");

      if($subdb->data['fourwickets'] != "0") {
        $scbfo = $subdb->data['fourwickets'];
      } else {
        $scbfo = "-";
      }

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' AND b.wickets >= 5");
    if($option == "allcareer")  $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND b.wickets >= 5");
    if($option == "teamcareer") $subdb->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) AND b.wickets >= 5");

    if($subdb->data['fivewickets'] != "0") {
      $scbfi = $subdb->data['fivewickets'];
    } else {
      $scbfi = "-";
    }           


    if($scbru >= 1 && $scwic >= 1) {
      $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
      $boavg = "-";
    }

    if($scbru >= 1 && $scove >= 0.1) {  
      $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
      $boeco = "-";
    }   

    if($option == "byseason")   $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    if($option == "allcareer")  $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    if($option == "teamcareer") $subdb->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE (g.league_id=1 OR g.league_id=4)  AND b.player_id = $playerid ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");

    $scbbw = $subdb->data['wickets'];
    $scbbr = $subdb->data['runs'];
    
    // Hide all those bowlers who haven't yet taken a wicket

    if($scwic != "0") {

    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"2%\">";
    echo $i;
    echo "  </td>\n";           
    echo "  <td align=\"left\" width=\"26%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname != "" && $fname == "") {
      echo $lname;
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";    
    } else {
      echo "$fname\n";
    }           
	
    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$average</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scbfo</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$boeco</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";
    
    } 
    }
    }
    
    echo "</table>\n";          

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";


    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
        
}
// End of Rookies Bowling

function show_statistics_bestbowling($db,$statistics,$option,$team)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }
    
    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
    
    if (!$db->Exists("SELECT COUNT( s.player_id ) AS Matches, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_bowling_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN seasons n ON g.season = n.SeasonID GROUP BY s.player_id")) {

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no bowling statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no bowling statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no bowling statistics in the database for the team <b>$team.</b></p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
                
        } else {        
                
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; <font class=\"10px\">$statistics statistics</font></p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; <font class=\"10px\">Career statistics</font></p>\n";
    if($option == "teamcareer") echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; <font class=\"10px\">{$teams[$team]} statistics</font></p>\n";

    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
    $inc = '';
    if(date("Y") == $statistics) {
    	$inc = "Including Knock-Outs.";
    }
    if($option == "byseason")   echo "<b class=\"16px\">$statistics Bowling - Best Innings Bowling</b><br>$inc Qualification 3 wickets.<br><br>\n";
    if($option == "allcareer")  echo "<b class=\"16px\">Career Bowling - Best Innings Bowling</b><br>From <b>$d</b> to the present. Qualification 4 wickets.<br><br>\n";
    if($option == "teamcareer") {
		if($statistics == "") {
			echo "<b class=\"16px\">{$teams[$team]} Career Bowling - Best Innings Bowling</b><br>From <b>$d</b> to the present. Qualification 3 wickets.<br><br>\n";
		} else {
			echo "<b class=\"16px\">$statistics {$teams[$team]} Career Bowling - Best Innings Bowling</b><br>Qualification 3 wickets.<br><br>\n";
		}
	}
    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP // BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
			if($option == "teamcareer") {
				echo "    <option $selected value=\"$PHP_SELF?option=teamcareer&statistics=$sen&ccl_mode=6&team=$team\" class=\"10px\">$sen</option>\n";       
			} else {
				echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&ccl_mode=6\" class=\"10px\">$sen</option>\n";       
			}
        }
        if($option == "teamcareer") {
			echo "    <option value=\"$PHP_SELF?option=teamcareer&statistics=&ccl_mode=6&team=$team\" class=\"10px\">all</option>\n";
		} else {
			echo "    <option value=\"$PHP_SELF?option=byseason&statistics=&ccl_mode=6\" class=\"10px\">all</option>\n";
		}
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // begin bowling statistics         
    //////////////////////////////////////////////////////////////////////////////////////////
            
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING STATS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo "<tr class=\"colhead\">\n";
    echo "  <td align=\"left\"><b>FIG</b></td>\n";
    echo "  <td align=\"left\"><b>NAME</b></td>\n";
    echo "  <td align=\"left\"><b>MATCH</b></td>\n";
    echo "</tr>\n";
    

    if($option == "byseason")   $db->Query("SELECT g.season, n.SeasonName, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS BRuns, b.wickets AS Wickets, p.PlayerLName, p.PlayerLAbbrev, p.PlayerFName, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4)  AND n.SeasonName LIKE '%{$statistics}%' AND Wickets >= 3 ORDER BY Wickets DESC, Runs ASC");
    if($option == "allcareer")  $db->Query("SELECT g.season, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS BRuns, b.wickets AS Wickets, p.PlayerLName, p.PlayerLAbbrev, p.PlayerFName, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID WHERE (g.league_id=1 OR g.league_id=4)  AND Wickets >= 4 ORDER BY Wickets DESC, Runs ASC");
    if($option == "teamcareer") {
		if ($statistics == "") {
			$db->Query("SELECT g.season, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS BRuns, b.wickets AS Wickets, p.PlayerLName, p.PlayerLAbbrev, p.PlayerFName, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID WHERE (g.league_id=1 OR g.league_id=4)  AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND Wickets >= 3 ORDER BY Wickets DESC, Runs ASC");
		} else {
			$db->Query("SELECT g.season, n.SeasonName, g.game_id, g.game_date, g.awayteam, g.hometeam, r.GroundAbbrev AS Ground, a.TeamAbbrev AS 'awayabbrev', h.TeamAbbrev AS 'homeabbrev', b.player_id, b.runs AS BRuns, b.wickets AS Wickets, p.PlayerLName, p.PlayerLAbbrev, p.PlayerFName, LEFT(p.PlayerFName,1) AS PlayerInitial FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN teams a ON g.awayteam = a.TeamID INNER JOIN teams h ON g.hometeam = h.TeamID INNER JOIN grounds r ON g.ground_id = r.GroundID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4) AND n.SeasonName LIKE '%{$statistics}%' AND (p.PlayerTeam = $team OR p.PlayerTeam2 = $team) AND Wickets >= 3 ORDER BY Wickets DESC, Runs ASC");
		}
	}

    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            

    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];
    $labbr = $db->data['PlayerLAbbrev'];
    $scbru = $db->data['BRuns'];
    $scwic = $db->data['Wickets'];
    $awayt = $db->data['awayabbrev'];
    $homet = $db->data['homeabbrev'];
    $groun = $db->data['Ground'];
    $gamed = $db->data['game_date'];
    $gamei = $db->data['game_id'];


    if($r % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\">$scwic/$scbru</td>\n";

    echo "  <td align=\"left\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";   
    echo "  <td align=\"left\">$awayt v $homet at $groun, $gamed [<a href=\"/scorecardfull.php?game_id=$gamei&ccl_mode=4\">$gamei</a>]</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";          

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}


function show_statistics_allrounders($db,$statistics,$option,$team)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }

    $db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
    
    if (!$db->Exists("SELECT t.TeamAbbrev, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4) GROUP BY s.player_id ORDER BY p.PlayerLName")) {

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    if($option == "byseason")   echo "<p>There are no all-round statistics in the database for the year <b>$statistics.</b></p>\n";
    if($option == "allcareer")  echo "<p>There are no all-round statistics in the database.</p>\n";
    if($option == "teamcareer") echo "<p>There are no all-round statistics in the database for the team <b>$team.</b></p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

        } else {


    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    if($option == "byseason")   echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; $statistics statistics</p>\n";
    if($option == "allcareer")  echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; Career statistics</p>\n";
    if($option == "teamcareer") echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/statistics.php\">Statistics</a> &raquo; {$teams[$team]} statistics</p>\n";

    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
	$inc = '';
	if(date("Y") == $statistics) {
		$inc = "<br>Including Knock-Outs";
	}
    if($option == "byseason")   echo "<b class=\"16px\">$statistics All-Round - 100 Runs & 10 Wickets</b>$inc <br><br>\n";
    if($option == "allcareer")  echo "<b class=\"16px\">Career All-Round - 500 Runs & 50 Wickets</b><br>From <b>$d</b> to the present.<br><br>\n";
    if($option == "teamcareer") echo "<b class=\"16px\">{$teams[$team]} Career All-Round - 250 Runs & 25 Wickets</b><br>From <b>$d</b> to the present.<br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

// 19-Aug-2009
        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
//        $db->Query("SELECT la.season, se.SeasonName FROM scorecard_batting_details la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP // BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        	$selected = "";
	        if ($statistics == $sen) {
	        	$selected = "selected";
	        }
        echo "    <option $selected value=\"$PHP_SELF?option=byseason&statistics=$sen&ccl_mode=7\" class=\"10px\">$sen</option>\n";       
        }
        echo "    <option value=\"$PHP_SELF?option=byseason&statistics=&ccl_mode=7\" class=\"10px\">all</option>\n";
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // begin batting statistics
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Batting Statistics</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"25%\"><b>Name</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>Runs</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>Ave</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>Wkts</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>Ave</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BB</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>Team</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>Team 2</b></td>\n";
    echo " </tr>\n";

    if($option == "byseason")   $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLAbbrev, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID INNER JOIN seasons n ON g.season = n.SeasonID WHERE (g.league_id=1 OR g.league_id=4) AND n.SeasonName LIKE '%{$statistics}%' GROUP BY p.PlayerID ORDER BY p.PlayerLName");
    if($option == "allcareer")  $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLAbbrev, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4) AND GROUP BY p.PlayerID ORDER BY p.PlayerLName");
    if($option == "teamcareer") $db->Query("SELECT t.TeamID TeamID, t.TeamAbbrev TeamAbbrev, t2.TeamID TeamID2, t2.TeamAbbrev TeamAbbrev2, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLAbbrev, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID LEFT OUTER JOIN teams t2 ON p.PlayerTeam2 = t2.TeamID WHERE (g.league_id=1 OR g.league_id=4) AND p.PlayerTeam = $team GROUP BY p.PlayerID ORDER BY p.PlayerLName");

    $db->BagAndTag();

    // instantiate new db class
    $subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

	$k = 0;
    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            

    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];
    $labbr = $db->data['PlayerLAbbrev'];
    $teamid = $db->data['TeamID'];
    $teama = $db->data['TeamAbbrev'];         
    $teama2 = $db->data['TeamAbbrev2'];
    $teamid2 = $db->data['TeamID2'];
    $scinn = $db->data['Matches'];
    $scrun = $db->data['Runs'];
    $schig = $db->data['HS'];             

    // Get Sum of Notouts

    if($option == "byseason")   $subdb->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID WHERE b.how_out = 2 AND b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%'");
    if($option == "allcareer")   $subdb->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b WHERE b.how_out = 2 AND b.player_id = $playerid");
    if($option == "teamcareer")   $subdb->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b WHERE b.how_out = 2 AND b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid)))");

    if($subdb->data['Notout'] != "0") {
      $scnot = $subdb->data['Notout'];    
    } else {
      $scnot = "-";
    }

    $outin = $scinn - $scnot;

    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = "-";
    }           

    // Get Sum of Runs Against

    if($option == "byseason")   $subruns = htmlentities(stripslashes($subdb->QueryItem("SELECT SUM(b.runs) AS Runs FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID WHERE b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%'")));
    if($option == "allcareer")   $subruns = htmlentities(stripslashes($subdb->QueryItem("SELECT SUM(b.runs) AS Runs FROM scorecard_bowling_details b WHERE b.player_id = $playerid")));
    if($option == "teamcareer")   $subruns = htmlentities(stripslashes($subdb->QueryItem("SELECT SUM(b.runs) AS Runs FROM scorecard_bowling_details b WHERE b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid)))")));

    if($subruns != "") {
      $scbru = $subruns;
    } else {
      $scbru = "1";
    }

    // Get Sum of Wickets

    if($option == "byseason")   $subwic = htmlentities(stripslashes($subdb->QueryItem("SELECT SUM(b.wickets) AS Wickets FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID WHERE b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%'")));
    if($option == "allcareer")   $subwic = htmlentities(stripslashes($subdb->QueryItem("SELECT SUM(b.wickets) AS Wickets FROM scorecard_bowling_details b WHERE b.player_id = $playerid")));
    if($option == "teamcareer")   $subwic = htmlentities(stripslashes($subdb->QueryItem("SELECT SUM(b.wickets) AS Wickets FROM scorecard_bowling_details b WHERE b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid)))")));

    if($subwic != "") {
      $scwic = $subwic;
    } else {
      $scwic = "1";
    }

    // Get Bowling Average

    if($scwic >= 1 && $scbru >= 1) {
    $scbav = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
    $scbav = "-";
    }

    // Get Best Bowling Effort (Wickets)

    if($option == "byseason")   $exist1 = htmlentities(stripslashes($subdb->QueryItem("SELECT MAX(b.player_id) AS Player FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID WHERE b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%'")));
    if($option == "allcareer")   $exist1 = htmlentities(stripslashes($subdb->QueryItem("SELECT MAX(b.player_id) AS Player FROM scorecard_bowling_details b WHERE b.player_id = $playerid")));
    if($option == "teamcareer")   $exist1 = htmlentities(stripslashes($subdb->QueryItem("SELECT MAX(b.player_id) AS Player FROM scorecard_bowling_details b WHERE b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid)))")));

    if($exist1 != "") {

    if($option == "byseason")   $subdb->QueryRow("SELECT b.wickets AS BWickets, b.runs AS BRuns FROM scorecard_bowling_details b INNER JOIN seasons s ON b.season = s.SeasonID WHERE b.player_id = $playerid AND s.SeasonName LIKE '%{$statistics}%' ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    if($option == "allcareer")   $subdb->QueryRow("SELECT b.wickets AS BWickets, b.runs AS BRuns FROM scorecard_bowling_details b WHERE b.player_id = $playerid ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    if($option == "teamcareer")   $subdb->QueryRow("SELECT b.wickets AS BWickets, b.runs AS BRuns FROM scorecard_bowling_details b WHERE b.player_id = $playerid AND (b.team = $team OR (b.team IN (SELECT PlayerTeam from players where PlayerTeam2 = $team AND PlayerID = $playerid) OR b.team IN (SELECT PlayerTeam2 from players where PlayerTeam = $team AND player_id = $playerid))) ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");

      $scbbr = $subdb->data['BRuns'];  
      $scbbw = $subdb->data['BWickets'];  
    } else {
      $scbbw = "-";
      $scbbr = "-";
    }


    if($option == "byseason" && $scrun >= "100" && $scwic >= "10") {


    if($k % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
	$k = $k + 1;
    echo "  <td align=\"left\" width=\"25%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "" && $labbr != "") {
      echo "$fname $labbr";
    } elseif($fname != "" && $lname != "" && $labbr == "") {
      echo "$fname $lname";
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scbav</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";

    } else if($option == "allcareer" && $scrun >= "500" && $scwic >= "50") {
    

    if($k % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }
	$k = $k + 1;
    echo "  <td align=\"left\" width=\"25%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "") {
      echo "$init $lname";
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scbav</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";    
    
    } else if($option == "teamcareer" && $scrun >= "250" && $scwic >= "25") {
    

    echo "<tr class=\"trrow1\">\n"; 
    echo "  <td align=\"left\" width=\"25%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "") {
      echo "$fname $lname";
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$scbav</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid&ccl_mode=2\" class=\"statistics\">$teama</a></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><a href=\"/statistics.php?statistics=$statistics&team=$teamid2&ccl_mode=2\" class=\"statistics\">$teama2</a></td>\n";
    echo " </tr>\n";
    
    } else {
    }
    }

    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



if (isset($_GET['ccl_mode'])) {
	switch($_GET['ccl_mode']) {
	case 0:
		show_statistics_listing($db);
		break;
	case 1:
		show_statistics_byseason($db,$_GET['statistics']);
		break;
	case 2:
		show_statistics_team($db,$_GET['statistics'],$_GET['team'],isset($_GET['status']) ? $_GET['status'] : '0');
		break;
	case 3:
		show_statistics_mostruns($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['sort'],$_GET['sort2'],$_GET['option'],isset($_GET['team']) ? $_GET['team'] : '');
		break;  
	case 4:
		show_statistics_bestinnings($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['option'], isset($_GET['team']) ? $_GET['team'] : '');
		break;  
	case 5:
		show_statistics_bowling($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['sort'],$_GET['direction'],$_GET['option'], isset($_GET['team']) ? $_GET['team'] : '');
		break;
	case 6:
		show_statistics_bestbowling($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['option'],isset($_GET['team']) ? $_GET['team'] : '');
		break;  
	case 7:
		show_statistics_allrounders($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['option'],isset($_GET['team']) ? $_GET['team'] : '');
		break;
	case 8:
		show_statistics_mostruns_rookies($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['sort'],$_GET['sort2'],$_GET['option'],isset($_GET['team']) ? $_GET['team'] : '');
		break;
	case 9:
		show_statistics_bowling_rookies($db,isset($_GET['statistics']) ? $_GET['statistics'] : '',$_GET['sort'],$_GET['direction'],$_GET['option'],isset($_GET['team']) ? $_GET['team'] : '');
		break;
	default:
		show_statistics_listing($db);
		break;
	}
} else {
	show_statistics_listing($db);
}
?>
