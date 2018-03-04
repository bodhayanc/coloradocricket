<?php

    $currentYear = date("Y");
    $previousYear = date("Y") - 1;
    
	// 6-May 2015 10:25pm
	$curr_premier = 72;
	$curr_t20 = 73;
	$prev_premier = 65;
	$prev_t20 = 66;
	
    // 5-Jan-2010 
//    $db->Query("SELECT * FROM scorecard_game_details where YEAR(game_date) = $currentYear");  commented 26-Mar-2014 11:39pm
    $db->Query("SELECT * FROM schedule where YEAR(date) = $currentYear"); 
    if (!$db->rows) 
    { 
    	$currentYear = date("Y") - 1 ;
    	$previousYear = date("Y") - 2; 
    }
    

 	echo "<td width=\"450\" bgcolor=\"#D0C7C0\" valign=\"top\">\n";
	
	echo "<p align=\"center\">&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
	echo "	<option value=\"\">select another team page</option>\n";
	echo "	<option value=\"\">--------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams WHERE (LeagueID=1 OR LeagueID=4) AND TeamActive=1 ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"teamdetails.php?teams=" . $db->data['TeamID'] . "&ccl_mode=1\">" . $db->data['TeamAbbrev'] . "</option>\n";
		}
	}
	echo "</select></p>\n";
	
    //-------------------------------------------------
    // Teams Scorecards This Year
    //-------------------------------------------------
    
    echo "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." PREMIER SEASON Schedule</td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." TWENTY20 SEASON Schedule</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
    
        $db->Query("
	SELECT
	  s.*,
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	  DATE_FORMAT(s.game_date, '%b %e') as formatted_date
	FROM
	  scorecard_game_details s
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	WHERE
	 s.season=$curr_premier  AND (s.league_id=1 OR s.league_id=4)
	AND
	  (s.awayteam=$pr OR s.hometeam=$pr)
	ORDER BY
	  s.game_date, s.game_id");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No Games this year.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
	echo "<tr class=\"colhead\">\n";
	echo "	<td align=\"left\"><b>Date</b></td>\n";
	echo "	<td align=\"left\" colspan=\"2\"><b>Game</b></td>\n";
	echo "</tr>\n";
	
        	for ($c = 0; $c < $db->rows; $c++) {
                $db->GetRow($c);
                
		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$d = $db->data['formatted_date'];
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$wk = $db->data['week'];
		$ti = $db->data['tied'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];
		$rwi = $db->data['result_won_id'];
		
		if($ca == '1') {
		  $wl = "C";
		} else if($ti == '1') {
		  $wl = "T";
		} else if($rwi == $pr) {
		  $wl = "W";
		} else if($rwi == 0) {
		  $wl = "NR";
		} else if($rwi <> $pr) {
		  $wl = "L";
		} else {
		  $wl = "N/A";
		}

		if($c % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

                echo "  <td align=\"left\" class=\"10px\">$d</td>\n";
                echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$wl</a></td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }    
    echo "  </td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
    
        $db->Query("
	SELECT
	  s.*,
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	  DATE_FORMAT(s.game_date, '%b %e') as formatted_date
	FROM
	  scorecard_game_details s
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	WHERE
	 s.season=$curr_t20  AND (s.league_id=1 OR s.league_id=4)
	AND
	  (s.awayteam=$pr OR s.hometeam=$pr)
	ORDER BY
	  s.game_date, s.game_id");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No Games this year.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
	echo "<tr class=\"colhead\">\n";
	echo "	<td align=\"left\"><b>Date</b></td>\n";
	echo "	<td align=\"left\" colspan=\"2\"><b>Game</b></td>\n";
	echo "</tr>\n";
	
        	for ($c = 0; $c < $db->rows; $c++) {
                $db->GetRow($c);
                
		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$d = $db->data['formatted_date'];
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$wk = $db->data['week'];
		$ti = $db->data['tied'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];
		$rwi = $db->data['result_won_id'];
		
		if($ca == '1') {
		  $wl = "C";
		} else if($ti == '1') {
		  $wl = "T";
		} else if($rwi == $pr) {
		  $wl = "W";
		} else if($rwi == 0) {
		  $wl = "NR";
		} else if($rwi <> $pr) {
		  $wl = "L";
		} else {
		  $wl = "N/A";
		}

		if($c % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

                echo "  <td align=\"left\" class=\"10px\">$d</td>\n";
                echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$wl</a></td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }    
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    echo "<br>\n";

    //-------------------------------------------------
    // Teams Scorecards Last Year
    //-------------------------------------------------
    
    echo "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$previousYear." PREMIER SEASON Schedule</td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$previousYear." TWENTY20 SEASON Schedule</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
    
        $db->Query("
	SELECT
	  s.*,
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	  DATE_FORMAT(s.game_date, '%b %e') as formatted_date
	FROM
	  scorecard_game_details s
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	WHERE
	  s.season= $prev_premier AND s.league_id=1
	AND
	  (s.awayteam=$pr OR s.hometeam=$pr)
	ORDER BY
	  s.week, s.game_date, s.game_id");

	if (!$db->rows) {
    	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
		echo "<tr class=\"colhead\">\n";
		echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No Games this year.</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
    } else {

	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
	echo "<tr class=\"colhead\">\n";
	echo "	<td align=\"left\"><b>Date</b></td>\n";
	echo "	<td align=\"left\" colspan=\"2\"><b>Game</b></td>\n";
	echo "</tr>\n";
	for ($c = 0; $c < $db->rows; $c++) {
		$db->GetRow($c);
                
		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$d = $db->data['formatted_date'];
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$wk = $db->data['week'];
		$ti = $db->data['tied'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];
		$rwi = $db->data['result_won_id'];
		
		if($ca == '1') {
		  $wl = "C";
		} else if($ti == '1') {
		  $wl = "T";
		} else if($rwi == $pr) {
		  $wl = "W";
		} else if($rwi == 0) {
		  $wl = "NR";
		} else if($rwi <> $pr) {
		  $wl = "L";
		} else {
		  $wl = "N/A";
		}

		if($c % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

                echo "  <td align=\"left\" class=\"10px\">$d</td>\n";
                echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$wl</a></td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }    
    echo "  </td>\n";
	echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
    
        $db->Query("
	SELECT
	  s.*,
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
	  DATE_FORMAT(s.game_date, '%b %e') as formatted_date
	FROM
	  scorecard_game_details s
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	WHERE
	  s.season= $prev_t20 AND (s.league_id=1 OR s.league_id=4)
	AND
	  (s.awayteam=$pr OR s.hometeam=$pr)
	ORDER BY
	  s.week, s.game_date, s.game_id");

	if (!$db->rows) {
    	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
		echo "<tr class=\"colhead\">\n";
		echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No Games this year.</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
    } else {
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
	echo "<tr class=\"colhead\">\n";
	echo "	<td align=\"left\"><b>Date</b></td>\n";
	echo "	<td align=\"left\" colspan=\"2\"><b>Game</b></td>\n";
	echo "</tr>\n";
	for ($c = 0; $c < $db->rows; $c++) {
		$db->GetRow($c);
                
		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$d = $db->data['formatted_date'];
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$wk = $db->data['week'];
		$ti = $db->data['tied'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];
		$rwi = $db->data['result_won_id'];
		
		if($ca == '1') {
		  $wl = "C";
		} else if($ti == '1') {
		  $wl = "T";
		} else if($rwi == $pr) {
		  $wl = "W";
		} else if($rwi == 0) {
		  $wl = "NR";
		} else if($rwi <> $pr) {
		  $wl = "L";
		} else {
		  $wl = "N/A";
		}

		if($c % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

                echo "  <td align=\"left\" class=\"10px\">$d</td>\n";
                echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$t2id&ccl_mode=1\">$t2</a> at <a href=\"/teamdetails.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$wl</a></td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }    
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    echo "<br>\n";
    
    
    //-------------------------------------------------
    // Teams Ladder 
    //-------------------------------------------------
    
    echo "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." PREMIER TEAM STANDINGS</td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." TWENTY20 TEAM STANDINGS</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
    
	$db->Query("
	SELECT
	  lad. * , tm.TeamAbbrev AS TeamName, tm.TeamID as tid
	FROM
	  ladder lad
	INNER JOIN
	  teams tm ON lad.team = tm.TeamID
	WHERE
	  season=$curr_premier  ORDER BY won DESC, lost ASC
	");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No statistics right now.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
	echo "<tr class=\"colhead\">\n";
	echo "	<td align=\"left\"><b>TM</b></td>\n";
	echo "	<td align=\"center\"><b>P</b></td>\n";
	echo "	<td align=\"center\"><b>W</b></td>\n";
	echo "	<td align=\"center\"><b>T</b></td>\n";
	echo "	<td align=\"center\"><b>L</b></td>\n";
	echo "	<td align=\"center\"><b>NR</b></td>\n";
	echo "	<td align=\"center\"><b>Pt</b></td>\n";
	echo "</tr>\n";
	
        	for ($l = 0; $l < $db->rows; $l++) {
                $db->GetRow($l);
                
		$tid = $db->data['tid'];
		$te = htmlentities(stripslashes($db->data['TeamName']));
		$pl = htmlentities(stripslashes($db->data['played']));
		$wo = htmlentities(stripslashes($db->data['won']));
		$lo = htmlentities(stripslashes($db->data['lost']));
		$ti = htmlentities(stripslashes($db->data['tied']));
		$nr = htmlentities(stripslashes($db->data['nrr']));
		$pt = htmlentities(stripslashes($db->data['points']));
		$pe = htmlentities(stripslashes($db->data['penalty']));
		$tp = htmlentities(stripslashes($db->data['totalpoints']));
		

		if($l % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		if($tid == $pr) { 
		echo "	<td align=\"left\" class=\"trhighlight\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\" class=\"right\">$te</a></td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$pl</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$wo</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$ti</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$lo</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$nr</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$tp</td>\n";
				
		echo "</tr>\n";
		} else {

		echo "	<td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\" class=\"right\">$te</a></td>\n";
		echo "	<td align=\"center\">$pl</td>\n";
		echo "	<td align=\"center\">$wo</td>\n";
		echo "	<td align=\"center\">$ti</td>\n";
		echo "	<td align=\"center\">$lo</td>\n";
		echo "	<td align=\"center\">$nr</td>\n";
		echo "	<td align=\"center\">$tp</td>\n";
		
		echo "</tr>\n";
		
		}
            }
           

            echo "</table>\n";
        }    
    echo "  </td>\n";
	echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
    
	$db->Query("
	SELECT
	  lad. * , tm.TeamAbbrev AS TeamName, tm.TeamID as tid
	FROM
	  ladder lad
	INNER JOIN
	  teams tm ON lad.team = tm.TeamID
	WHERE
	  season=$curr_t20  ORDER BY won DESC, lost ASC
	");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No statistics right now.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
	echo "<tr class=\"colhead\">\n";
	echo "	<td align=\"left\"><b>TM</b></td>\n";
	echo "	<td align=\"center\"><b>P</b></td>\n";
	echo "	<td align=\"center\"><b>W</b></td>\n";
	echo "	<td align=\"center\"><b>T</b></td>\n";
	echo "	<td align=\"center\"><b>L</b></td>\n";
	echo "	<td align=\"center\"><b>NR</b></td>\n";
	echo "	<td align=\"center\"><b>Pt</b></td>\n";
	echo "</tr>\n";
	
        	for ($l = 0; $l < $db->rows; $l++) {
                $db->GetRow($l);
                
		$tid = $db->data['tid'];
		$te = htmlentities(stripslashes($db->data['TeamName']));
		$pl = htmlentities(stripslashes($db->data['played']));
		$wo = htmlentities(stripslashes($db->data['won']));
		$lo = htmlentities(stripslashes($db->data['lost']));
		$ti = htmlentities(stripslashes($db->data['tied']));
		$nr = htmlentities(stripslashes($db->data['nrr']));
		$pt = htmlentities(stripslashes($db->data['points']));
		$pe = htmlentities(stripslashes($db->data['penalty']));
		$tp = htmlentities(stripslashes($db->data['totalpoints']));
		

		if($l % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		if($tid == $pr) { 
		echo "	<td align=\"left\" class=\"trhighlight\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\" class=\"right\">$te</a></td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$pl</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$wo</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$ti</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$lo</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$nr</td>\n";
		echo "	<td align=\"center\" class=\"trhighlight\">$tp</td>\n";
				
		echo "</tr>\n";
		} else {

		echo "	<td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\" class=\"right\">$te</a></td>\n";
		echo "	<td align=\"center\">$pl</td>\n";
		echo "	<td align=\"center\">$wo</td>\n";
		echo "	<td align=\"center\">$ti</td>\n";
		echo "	<td align=\"center\">$lo</td>\n";
		echo "	<td align=\"center\">$nr</td>\n";
		echo "	<td align=\"center\">$tp</td>\n";
		
		echo "</tr>\n";
		
		}
            }
           

            echo "</table>\n";
        }    
    echo "  </td>\n";    echo " </tr>\n";
    echo "</table>\n";
    echo "<br>\n"; 
    
    //-------------------------------------------------
    // Teams Premier Batting and Bowling Leaders 
    //-------------------------------------------------
    
    echo "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." Premier Batting Leaders</td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." Premier Bowling Leaders</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
	
	$db->Query("
		SELECT 
		  g.season, n.SeasonName, 
		  COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, 
		  SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / (COUNT( s.player_id ) - SUM( s.notout )) AS Average, 
		  s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev, p.picture 
		FROM 
		  scorecard_batting_details s
		INNER JOIN
		  players p 
		ON 
		  s.player_id = p.PlayerID
		INNER JOIN 
		  scorecard_game_details g
		ON
		  s.game_id = g.game_id
		INNER JOIN
		  seasons n 
		ON
		  g.season = n.SeasonID			  
		WHERE 
		 (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND ((s.season=$curr_premier AND g.league_id=1))
		GROUP BY 
		  s.player_id
		ORDER BY
		  Runs DESC, p.PlayerLName, p.PlayerFName
		LIMIT 1
	");

        if (!$db->rows) {

        	echo "";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co\">\n";
	echo " <tr class=\"trrow1\">\n";
	echo "  <td align=\"left\" valign=\"middle\">\n";
        
                $db->GetRow(0);
                
		$playerid = $db->data['PlayerID'];
		$init = $db->data['PlayerInitial'];
		$fname = $db->data['PlayerFName'];
		$lname = $db->data['PlayerLName'];
		$labbr = $db->data['PlayerLAbbrev'];
		$scinn = $db->data['Matches'];
		$scrun = $db->data['Runs'];
		$pic   = $db->data['picture'];
		$ss = explode(" ", $db->data['SeasonName']);
		$ss2 = $ss[1];
		
		if($pic != "") {
		echo "<img src=\"/uploadphotos/players/$pic\" align=\"left\" border=\"1\" width=\"50\">\n";
		} else {
		echo "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"left\" width=\"50\">\n";
		}	
	
		echo "<a href=\"players.php?players=$playerid&ccl_mode=1\">$init $lname</a><br>\n";
		echo "$scrun runs\n";
		
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}
	
	$db->Query("
		SELECT 
		  g.season, n.SeasonName, 
		  COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, 
		  SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / (COUNT( s.player_id ) - SUM( s.notout )) AS Average, 
		  s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev 
		FROM 
		  scorecard_batting_details s
		INNER JOIN
		  players p 
		ON 
		  s.player_id = p.PlayerID
		INNER JOIN 
		  scorecard_game_details g
		ON
		  s.game_id = g.game_id
		INNER JOIN
		  seasons n 
		ON
		  g.season = n.SeasonID			  
		WHERE 
		  (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND ((s.season=$curr_premier AND g.league_id=1))
		GROUP BY 
		  s.player_id
		ORDER BY
		  Runs DESC, p.PlayerLName, p.PlayerFName
		LIMIT 1,5
	");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No statistics right now.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co; border-bottom:3px solid #$co\" class=\"tablehead\">\n";
	echo " <tr class=\"colhead\">\n";
	echo "  <td align=\"left\"><b>Name</b></td>\n";
	echo "  <td align=\"left\"><b>Runs</b></td>\n";
	echo " </tr>\n";
	
        	for ($b = 0; $b < $db->rows; $b++) {
                $db->GetRow($b);
                
		$playerid = $db->data['player_id'];
		$init = $db->data['PlayerInitial'];
		$fname = $db->data['PlayerFName'];
		$lname = $db->data['PlayerLName'];
		$labbr = $db->data['PlayerLAbbrev'];
		$scinn = $db->data['Matches'];
		$scrun = $db->data['Runs'];

				if($b % 2) {
				  echo "<tr class=\"trrow2\">\n";
				} else {
				  echo "<tr class=\"trrow1\">\n";
				}

                echo "  <td align=\"left\" class=\"10px\"><a href=\"players.php?players=$playerid&ccl_mode=1\">$init $lname</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\">$scrun</td>\n";
                echo "</tr>\n";
            }
            
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" class=\"10px\" colspan=\"2\"><img src=\"/images/icons/icon_arrows.gif\"> <a href=\"statistics.php?statistics=".$currentYear."&team=$pr&ccl_mode=2\">complete team statistics</a></td>\n";
            echo "</tr>\n";           
            
            echo "</table>\n";
        }    
    echo "  </td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td valign=\"top\" class=\"main\">\n";

	$db->Query("
	SELECT 
	  g.season, n.SeasonName, 
	  b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, 
	  p.PlayerID, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial, p.picture
	FROM 
	  scorecard_bowling_details b 
	INNER JOIN 
	  players p 
	ON 
	  b.player_id = p.PlayerID 
	INNER JOIN 
	  scorecard_game_details g
	ON
	  b.game_id = g.game_id	
	INNER JOIN
	  seasons n 
	ON
	  g.season = n.SeasonID					  
	WHERE 
	  (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND ((b.season=$curr_premier AND g.league_id=1))
	GROUP BY 
	  b.player_id
	ORDER BY
	  Wickets DESC, p.PlayerLName, p.PlayerFName
	LIMIT 1
	");
	
        if (!$db->rows) {

        	echo "";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co; \">\n";
	echo " <tr class=\"trrow1\">\n";
	echo "  <td align=\"left\" valign=\"middle\">\n";
        
                $db->GetRow(0);
                
	  	$bplayerid = $db->data['PlayerID'];
	  	$binit = $db->data['PlayerInitial'];
	  	$bfname = $db->data['PlayerFName'];
	  	$blname = $db->data['PlayerLName'];
	  	$blabbr = $db->data['PlayerLAbbrev'];
	  	$scmai = $db->data['Maidens'];
	  	$scbru = $db->data['BRuns'];
	  	$scwic = $db->data['Wickets'];
		$bpic   = $db->data['picture'];
		
		if($bpic != "") {
		echo "<img src=\"/uploadphotos/players/$bpic\" align=\"left\" border=\"1\" width=\"50\">\n";
		} else {
		echo "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"left\" width=\"50\">\n";
		}	
	
		echo "<a href=\"players.php?players=$bplayerid&ccl_mode=1\">$binit $blname</a><br>\n";
		echo "$scwic wickets\n";
		
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}
	
	$db->Query("
	SELECT 
	  g.season, n.SeasonName, 
	  b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, 
	  p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial
	FROM 
	  scorecard_bowling_details b 
	INNER JOIN 
	  players p 
	ON 
	  b.player_id = p.PlayerID 
	INNER JOIN 
	  scorecard_game_details g
	ON
	  b.game_id = g.game_id	
	INNER JOIN
	  seasons n 
	ON
	  g.season = n.SeasonID					  
	WHERE 
	  (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND ((b.season=$curr_premier AND g.league_id=1))
	GROUP BY 
	  b.player_id
	ORDER BY
	  Wickets DESC, p.PlayerLName, p.PlayerFName
	LIMIT 1,5
	");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No statistics right now.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co; border-bottom:3px solid #$co\" class=\"tablehead\">\n";
	echo " <tr class=\"colhead\">\n";
	echo "  <td align=\"left\"><b>Name</b></td>\n";
	echo "  <td align=\"left\"><b>Wickets</b></td>\n";
	echo " </tr>\n";
	
        	for ($o = 0; $o < $db->rows; $o++) {
                $db->GetRow($o);
                
	  	$playerid = $db->data['player_id'];
	  	$init = $db->data['PlayerInitial'];
	  	$fname = $db->data['PlayerFName'];
	  	$lname = $db->data['PlayerLName'];
	  	$labbr = $db->data['PlayerLAbbrev'];
	  	$scmai = $db->data['Maidens'];
	  	$scbru = $db->data['BRuns'];
	  	$scwic = $db->data['Wickets'];

				if($o % 2) {
				  echo "<tr class=\"trrow2\">\n";
				} else {
				  echo "<tr class=\"trrow1\">\n";
				}

                echo "  <td align=\"left\" class=\"10px\"><a href=\"players.php?players=$playerid&ccl_mode=1\">$init $lname</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\">$scwic</td>\n";
                echo "</tr>\n";
            }
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" class=\"10px\" colspan=\"2\"><img src=\"/images/icons/icon_arrows.gif\"> <a href=\"statistics.php?statistics=".$currentYear."&team=$pr&ccl_mode=2\">complete team statistics</a></td>\n";
            echo "</tr>\n";           

            echo "</table>\n";
        }    
        
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    echo "<br>\n";    
  
    //-------------------------------------------------
    // Teams T20 Batting and Bowling Leaders 
    //-------------------------------------------------
    
    echo "<table width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." Twenty20 Batting Leaders</td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td width=\"50%\" bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;".$currentYear." Twenty20 Bowling Leaders</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo " <td valign=\"top\" class=\"main\">\n";
	
	$db->Query("
		SELECT 
		  g.season, n.SeasonName, 
		  COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, 
		  SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / (COUNT( s.player_id ) - SUM( s.notout )) AS Average, 
		  s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev, p.picture 
		FROM 
		  scorecard_batting_details s
		INNER JOIN
		  players p 
		ON 
		  s.player_id = p.PlayerID
		INNER JOIN 
		  scorecard_game_details g
		ON
		  s.game_id = g.game_id
		INNER JOIN
		  seasons n 
		ON
		  g.season = n.SeasonID			  
		WHERE 
		 (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND (s.season=$curr_t20 AND (g.league_id=1 OR g.league_id=4))
		GROUP BY 
		  s.player_id
		ORDER BY
		  Runs DESC, p.PlayerLName, p.PlayerFName
		LIMIT 1
	");

        if (!$db->rows) {

        	echo "";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co;\">\n";
	echo " <tr class=\"trrow1\">\n";
	echo "  <td align=\"left\" valign=\"middle\">\n";
        $db->GetRow(0);
                
		$playerid = $db->data['PlayerID'];
		$init = $db->data['PlayerInitial'];
		$fname = $db->data['PlayerFName'];
		$lname = $db->data['PlayerLName'];
		$labbr = $db->data['PlayerLAbbrev'];
		$scinn = $db->data['Matches'];
		$scrun = $db->data['Runs'];
		$pic   = $db->data['picture'];
		$ss = explode(" ", $db->data['SeasonName']);
		$ss2 = $ss[1];
		
		if($pic != "") {
		echo "<img src=\"/uploadphotos/players/$pic\" align=\"left\" border=\"1\" width=\"50\">\n";
		} else {
		echo "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"left\" width=\"50\">\n";
		}	
	
		echo "<a href=\"players.php?players=$playerid&ccl_mode=1\">$init $lname</a><br>\n";
		echo "$scrun runs\n";
		
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}
	
	$db->Query("
		SELECT 
		  g.season, n.SeasonName, 
		  COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, 
		  SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / (COUNT( s.player_id ) - SUM( s.notout )) AS Average, 
		  s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev 
		FROM 
		  scorecard_batting_details s
		INNER JOIN
		  players p 
		ON 
		  s.player_id = p.PlayerID
		INNER JOIN 
		  scorecard_game_details g
		ON
		  s.game_id = g.game_id
		INNER JOIN
		  seasons n 
		ON
		  g.season = n.SeasonID			  
		WHERE 
		  (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND (s.season=$curr_t20 AND (g.league_id=1 OR g.league_id=4))
		GROUP BY 
		  s.player_id
		ORDER BY
		  Runs DESC, p.PlayerLName, p.PlayerFName
		LIMIT 1,5
	");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No statistics right now.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co; border-bottom:3px solid #$co\" class=\"tablehead\">\n";
	echo " <tr class=\"colhead\">\n";
	echo "  <td align=\"left\"><b>Name</b></td>\n";
	echo "  <td align=\"left\"><b>Runs</b></td>\n";
	echo " </tr>\n";
	
        	for ($b = 0; $b < $db->rows; $b++) {
                $db->GetRow($b);
                
		$playerid = $db->data['player_id'];
		$init = $db->data['PlayerInitial'];
		$fname = $db->data['PlayerFName'];
		$lname = $db->data['PlayerLName'];
		$labbr = $db->data['PlayerLAbbrev'];
		$scinn = $db->data['Matches'];
		$scrun = $db->data['Runs'];

				if($b % 2) {
				  echo "<tr class=\"trrow2\">\n";
				} else {
				  echo "<tr class=\"trrow1\">\n";
				}

                echo "  <td align=\"left\" class=\"10px\"><a href=\"players.php?players=$playerid&ccl_mode=1\">$init $lname</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\">$scrun</td>\n";
                echo "</tr>\n";
            }
            
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" class=\"10px\" colspan=\"2\"><img src=\"/images/icons/icon_arrows.gif\"> <a href=\"statistics.php?statistics=".$currentYear."&team=$pr&ccl_mode=2\">complete team statistics</a></td>\n";
            echo "</tr>\n";           
            
            echo "</table>\n";
        }    
    echo "  </td>\n";
    echo " <td width=\"2px\" bgcolor=\"#D0C7C0\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo " <td valign=\"top\" class=\"main\">\n";

	$db->Query("
	SELECT 
	  g.season, n.SeasonName, 
	  b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, 
	  p.PlayerID, p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial, p.picture
	FROM 
	  scorecard_bowling_details b 
	INNER JOIN 
	  players p 
	ON 
	  b.player_id = p.PlayerID 
	INNER JOIN 
	  scorecard_game_details g
	ON
	  b.game_id = g.game_id	
	INNER JOIN
	  seasons n 
	ON
	  g.season = n.SeasonID					  
	WHERE 
	  (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND (b.season=$curr_t20 AND (g.league_id=1 OR g.league_id=4))
	GROUP BY 
	  b.player_id
	ORDER BY
	  Wickets DESC, p.PlayerLName, p.PlayerFName
	LIMIT 1
	");
	
        if (!$db->rows) {

        	echo "";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co;\">\n";
	echo " <tr class=\"trrow1\">\n";
	echo "  <td align=\"left\" valign=\"middle\">\n";
        
                $db->GetRow(0);
                
	  	$bplayerid = $db->data['PlayerID'];
	  	$binit = $db->data['PlayerInitial'];
	  	$bfname = $db->data['PlayerFName'];
	  	$blname = $db->data['PlayerLName'];
	  	$blabbr = $db->data['PlayerLAbbrev'];
	  	$scmai = $db->data['Maidens'];
	  	$scbru = $db->data['BRuns'];
	  	$scwic = $db->data['Wickets'];
		$bpic   = $db->data['picture'];
		
		if($bpic != "") {
		echo "<img src=\"/uploadphotos/players/$bpic\" align=\"left\" border=\"1\" width=\"50\">\n";
		} else {
		echo "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"left\" width=\"50\">\n";
		}	
	
		echo "<a href=\"players.php?players=$bplayerid&ccl_mode=1\">$binit $blname</a><br>\n";
		echo "$scwic wickets\n";
		
		echo "</td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}
	
	$db->Query("
	SELECT 
	  g.season, n.SeasonName, 
	  b.player_id, SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, 
	  p.PlayerLName, p.PlayerFName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS PlayerInitial
	FROM 
	  scorecard_bowling_details b 
	INNER JOIN 
	  players p 
	ON 
	  b.player_id = p.PlayerID 
	INNER JOIN 
	  scorecard_game_details g
	ON
	  b.game_id = g.game_id	
	INNER JOIN
	  seasons n 
	ON
	  g.season = n.SeasonID					  
	WHERE 
	  (p.PlayerTeam = $pr OR p.PlayerTeam2 = $pr) AND (b.season=$curr_t20 AND (g.league_id=1 OR g.league_id=4))
	GROUP BY 
	  b.player_id
	ORDER BY
	  Wickets DESC, p.PlayerLName, p.PlayerFName
	LIMIT 1,5
	");

        if (!$db->rows) {

        	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#$co\" class=\"tablehead\">\n";
			echo "<tr class=\"colhead\">\n";
			echo "	<td bgcolor=\"#FFFFFF\" align=\"left\">No statistics right now.</td>\n";
			echo "</tr>\n";
			echo "</table>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" style=\"border-left:3px solid #$co; border-right:3px solid #$co; border-bottom:3px solid #$co\" class=\"tablehead\">\n";
	echo " <tr class=\"colhead\">\n";
	echo "  <td align=\"left\"><b>Name</b></td>\n";
	echo "  <td align=\"left\"><b>Wickets</b></td>\n";
	echo " </tr>\n";
	
        	for ($o = 0; $o < $db->rows; $o++) {
                $db->GetRow($o);
                
	  	$playerid = $db->data['player_id'];
	  	$init = $db->data['PlayerInitial'];
	  	$fname = $db->data['PlayerFName'];
	  	$lname = $db->data['PlayerLName'];
	  	$labbr = $db->data['PlayerLAbbrev'];
	  	$scmai = $db->data['Maidens'];
	  	$scbru = $db->data['BRuns'];
	  	$scwic = $db->data['Wickets'];

				if($o % 2) {
				  echo "<tr class=\"trrow2\">\n";
				} else {
				  echo "<tr class=\"trrow1\">\n";
				}

                echo "  <td align=\"left\" class=\"10px\"><a href=\"players.php?players=$playerid&ccl_mode=1\">$init $lname</a></td>\n";
                echo "  <td align=\"left\" class=\"10px\">$scwic</td>\n";
                echo "</tr>\n";
            }
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" class=\"10px\" colspan=\"2\"><img src=\"/images/icons/icon_arrows.gif\"> <a href=\"statistics.php?statistics=".$currentYear."&team=$pr&ccl_mode=2\">complete team statistics</a></td>\n";
            echo "</tr>\n";           

            echo "</table>\n";
        }    
        
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    echo "<br>\n";    
  
    echo "</td>\n";

?>
