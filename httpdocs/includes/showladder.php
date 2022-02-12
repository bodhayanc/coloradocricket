<?php

//------------------------------------------------------------------------------
// Standings v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_ladder_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT la.season, se.SeasonName FROM ladder la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC")) {
        $db->QueryRow("SELECT la.season, se.SeasonName FROM ladder la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Standings</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Standings</b><br><br>\n";


        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEASON SELECTION</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        echo "<p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "  <option>select a season</option>\n";
        echo "  <option>=====================</option>\n";
        for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);
        $db->BagAndTag();

        $sen = $db->data['SeasonName'];
        $sid = $db->data['season'];
        
        echo "    <option value=\"$PHP_SELF?ladder=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";


        }
        echo "</select></p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        } else {

            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";
            echo "    <p>There are no ladders in the database.</p>\n";
            echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
    }
}


function show_ladder($db,$ladder,$round)
{
    global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
    
	// instantiate new db class
		$subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
		$subdb->SelectDB($dbcfg['db']);
		$subdb1 = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
		$subdb1->SelectDB($dbcfg['db']);

        if (!$db->Exists("SELECT * FROM ladder")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &gt; <font class=\"10px\">Standings</font></p>\n";
        echo "    <p>There are currently no ladder games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        } else {

                $db->Query("SELECT se.* FROM seasons se INNER JOIN ladder la ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY se.SeasonID ORDER BY se.SeasonName DESC");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";

            echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";
            echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/ladder.php\">Standings</a> &raquo; <font class=\"10px\">{$seasons[$ladder]}</font></p>\n";
            echo "  </td>\n";
            //echo "  <td align=\"right\" valign=\"top\">\n";
            //require ("navtop.php");
            //echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


            echo "<b class=\"16px\">{$seasons[$ladder]} Standings</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Option Box
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

        $db->Query("SELECT la.season, se.SeasonName FROM ladder la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data['SeasonName'];
            $sid = $db->data['season'];
        if($sid == $ladder) {
			$selected = "selected";
		} else {
			$selected = "";
		}
        echo "    <option $selected value=\"$PHP_SELF?ladder=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
        
        }
        echo "    </select>\n";

        if ($db->Exists("SELECT Round FROM groups WHERE SeasonID = $ladder GROUP BY Round ORDER BY Round ASC")) {
			// List rounds for season

			echo "Round: ";
			echo "<select name=round onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
			echo "<option value=\"$PHP_SELF?ladder=$ladder&round=&ccl_mode=1\" selected>all</option>\n";

			$db->Query("SELECT Round FROM groups WHERE SeasonID = $ladder GROUP BY Round ORDER BY Round ASC");
			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);
				$db->BagAndTag();
				$rid = $db->data['Round'];
				if($rid == $round) {
					$selected = "selected";
				} else {
					$selected = "";
				}
			  
				echo "    <option $selected value=\"$PHP_SELF?ladder=$ladder&round=$rid&ccl_mode=1\" class=\"10px\">$rid</option>\n";
			
			}
			echo "    </select>\n";
		}
        echo "    </p></td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // League Standings
    //////////////////////////////////////////////////////////////////////////////////////////
    
        if (!$subdb1->Exists("
                SELECT *
                FROM
                  groups
                WHERE
                  SeasonID=$ladder
        ") || $round == "") {
			echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE STANDINGS</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

        if (!$db->Exists("
                SELECT
                  lad.* , tm.TeamAbbrev AS team
                FROM
                  ladder lad
                INNER JOIN
                  teams tm ON lad.team = tm.TeamID
                WHERE
                    season=$ladder AND tm.LeagueID = 1 ORDER BY won DESC, lost ASC
        ")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded in {$seasons[$ladder]}.</p></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
			$divisions = false;
            $db->Query("
                    SELECT division FROM ladder lad WHERE season=$ladder group by division");
			if($db->rows > 1) {
				$divisions = true;
			}
			$db->Query("
                    SELECT
                  lad. * , tm.TeamAbbrev AS TeamName, tm.TeamID as tid
                FROM
                  ladder lad
                INNER JOIN
                  teams tm ON lad.team = tm.TeamID
                WHERE
                    season=$ladder AND tm.LeagueID = 1 AND division = 1 ORDER BY rank_sort ASC
            ");

			if($divisions == true) {
				echo "<tr>\n";
				echo "  <td colspan=\"12\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;DIVISION 1 LEAGUE STANDINGS</td>\n";
				echo "</tr>\n";
			}
                echo "<tr class=\"colhead\">\n";
                echo "  <td align=\"left\"><b>TEAM</b></td>\n";
                echo "  <td align=\"center\"><b>Pl</b></td>\n";
                echo "  <td align=\"center\"><b>W</b></td>\n";
                echo "  <td align=\"center\"><b>T</b></td>\n";
                echo "  <td align=\"center\"><b>L</b></td>\n";
                echo "  <td align=\"center\"><b>NR</b></td>\n";
                echo "  <td align=\"center\"><b>Pt</b></td>\n";
                echo "  <td align=\"center\"><b>Pen</b></td>\n";
                echo "  <td align=\"center\"><b>Total</b></td>\n";
                echo "  <td align=\"center\"><b>NRR</b></td>\n";
                echo "  <td align=\"center\"><b>Runs For</b></td>\n";
                echo "  <td align=\"center\"><b>Runs Against</b></td>\n";
                //echo "    <td align=\"center\"><b>Avg</b></td>\n";
                echo "</tr>\n";

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);

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
					if ($pl == 0){
						$av = 0;
					}else {
						$av = Round($tp / $pl,2);
					}

					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and t.team = $tid");
					$subdb->BagAndTag();
					$trf = $subdb->data['TotalRunsFor'];
					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.awayteam) OR (g.awayteam = $tid and t.team = g.hometeam))");
					$subdb->BagAndTag();
					$tra = $subdb->data['TotalRunsAgainst'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
					$subdb->BagAndTag();
					$tof = $subdb->data['TotalOverFor'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
					$subdb->BagAndTag();
					$toa = $subdb->data['TotalOverAgainst'];
					if($trf > 0 && $tof > 0) {
						if($tra > 0 && $toa > 0) {
							$nrr = Round(($trf * 6 / $tof) - ($tra * 6 / $toa), 2);
						} else {
							$nrr = Round(($trf * 6 / $tof), 2);
						}
					} else {
						$nrr = 0;
					}
					$nrr = number_format($nrr, 2);
					$tof_o = Round(($tof / 6), 2); 
					$tof_floor = floor($tof_o); 

					if($tof_o == $tof_floor + 0.17) { 
					  $tof_formatted = $tof_floor + 0.1; 
					} else 
					if($tof_o == $tof_floor + 0.33) { 
					  $tof_formatted = $tof_floor + 0.2; 
					} else 
					if($tof_o == $tof_floor + 0.5) { 
					  $tof_formatted = $tof_floor + 0.3;        
					} else 
					if($tof_o == $tof_floor + 0.67) { 
					  $tof_formatted = $tof_floor + 0.4;        
					} else 
					if($tof_o == $tof_floor + 0.83) { 
					  $tof_formatted = $tof_floor + 0.5; 
					} else { 
					  $tof_formatted = $tof_floor; 
					}
					
					$toa_o = Round(($toa / 6), 2); 
					$toa_floor = floor($toa_o); 

					if($toa_o == $toa_floor + 0.17) { 
					  $toa_formatted = $toa_floor + 0.1; 
					} else 
					if($toa_o == $toa_floor + 0.33) { 
					  $toa_formatted = $toa_floor + 0.2; 
					} else 
					if($toa_o == $toa_floor + 0.5) { 
					  $toa_formatted = $toa_floor + 0.3;        
					} else 
					if($toa_o == $toa_floor + 0.67) { 
					  $toa_formatted = $toa_floor + 0.4;        
					} else 
					if($toa_o == $toa_floor + 0.83) { 
					  $toa_formatted = $toa_floor + 0.5; 
					} else { 
					  $toa_formatted = $toa_floor; 
					}     
            if($x % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

                    echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
                    echo "  <td align=\"center\">$pl</td>\n";
                    echo "  <td align=\"center\">$wo</td>\n";
                    echo "  <td align=\"center\">$ti</td>\n";
                    echo "  <td align=\"center\">$lo</td>\n";
                    echo "  <td align=\"center\">$nr</td>\n";
                    echo "  <td align=\"center\">$pt</td>\n";
                    echo "  <td align=\"center\">$pe</td>\n";
                    echo "  <td align=\"center\">$tp</td>\n";
                    echo "  <td align=\"center\">$nrr</td>\n";
                    echo "  <td align=\"center\">$trf/$tof_formatted</td>\n";
                    echo "  <td align=\"center\">$tra/$toa_formatted</td>\n";
                    //echo "    <td align=\"center\">$av</td>\n";
                    echo "</tr>\n";

                }
				
            $db->Query("
                    SELECT
                  lad. * , tm.TeamAbbrev AS TeamName, tm.TeamID as tid
                FROM
                  ladder lad
                INNER JOIN
                  teams tm ON lad.team = tm.TeamID
                WHERE
                    season=$ladder AND tm.LeagueID = 1 AND division = 2 ORDER BY rank_sort ASC
            ");

			if($divisions == true) {
                echo "<tr>\n";
				echo "  <td colspan=\"12\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;DIVISION 2 LEAGUE STANDINGS</td>\n";
				echo "</tr>\n";
                echo "<tr class=\"colhead\">\n";
                echo "  <td align=\"left\"><b>TEAM</b></td>\n";
                echo "  <td align=\"center\"><b>Pl</b></td>\n";
                echo "  <td align=\"center\"><b>W</b></td>\n";
                echo "  <td align=\"center\"><b>T</b></td>\n";
                echo "  <td align=\"center\"><b>L</b></td>\n";
                echo "  <td align=\"center\"><b>NR</b></td>\n";
                echo "  <td align=\"center\"><b>Pt</b></td>\n";
                echo "  <td align=\"center\"><b>Pen</b></td>\n";
                echo "  <td align=\"center\"><b>Total</b></td>\n";
                echo "  <td align=\"center\"><b>NRR</b></td>\n";
                echo "  <td align=\"center\"><b>Runs For</b></td>\n";
                echo "  <td align=\"center\"><b>Runs Against</b></td>\n";
                //echo "    <td align=\"center\"><b>Avg</b></td>\n";
                echo "</tr>\n";
			}
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);

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
					if ($pl == 0){
						$av = 0;
					}else {
						$av = Round($tp / $pl,2);
					}

					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and t.team = $tid");
					$subdb->BagAndTag();
					$trf = $subdb->data['TotalRunsFor'];
					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.awayteam) OR (g.awayteam = $tid and t.team = g.hometeam))");
					$subdb->BagAndTag();
					$tra = $subdb->data['TotalRunsAgainst'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
					$subdb->BagAndTag();
					$tof = $subdb->data['TotalOverFor'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
					$subdb->BagAndTag();
					$toa = $subdb->data['TotalOverAgainst'];
					if($trf > 0 && $tof > 0) {
						if($tra > 0 && $toa > 0) {
							$nrr = Round(($trf * 6 / $tof) - ($tra * 6 / $toa), 2);
						} else {
							$nrr = Round(($trf * 6 / $tof), 2);
						}
					} else {
						$nrr = 0;
					}
					$nrr = number_format($nrr, 2);
					$tof_o = Round(($tof / 6), 2); 
					$tof_floor = floor($tof_o); 

					if($tof_o == $tof_floor + 0.17) { 
					  $tof_formatted = $tof_floor + 0.1; 
					} else 
					if($tof_o == $tof_floor + 0.33) { 
					  $tof_formatted = $tof_floor + 0.2; 
					} else 
					if($tof_o == $tof_floor + 0.5) { 
					  $tof_formatted = $tof_floor + 0.3;        
					} else 
					if($tof_o == $tof_floor + 0.67) { 
					  $tof_formatted = $tof_floor + 0.4;        
					} else 
					if($tof_o == $tof_floor + 0.83) { 
					  $tof_formatted = $tof_floor + 0.5; 
					} else { 
					  $tof_formatted = $tof_floor; 
					}
					
					$toa_o = Round(($toa / 6), 2); 
					$toa_floor = floor($toa_o); 

					if($toa_o == $toa_floor + 0.17) { 
					  $toa_formatted = $toa_floor + 0.1; 
					} else 
					if($toa_o == $toa_floor + 0.33) { 
					  $toa_formatted = $toa_floor + 0.2; 
					} else 
					if($toa_o == $toa_floor + 0.5) { 
					  $toa_formatted = $toa_floor + 0.3;        
					} else 
					if($toa_o == $toa_floor + 0.67) { 
					  $toa_formatted = $toa_floor + 0.4;        
					} else 
					if($toa_o == $toa_floor + 0.83) { 
					  $toa_formatted = $toa_floor + 0.5; 
					} else { 
					  $toa_formatted = $toa_floor; 
					}     
            if($x % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

                    echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
                    echo "  <td align=\"center\">$pl</td>\n";
                    echo "  <td align=\"center\">$wo</td>\n";
                    echo "  <td align=\"center\">$ti</td>\n";
                    echo "  <td align=\"center\">$lo</td>\n";
                    echo "  <td align=\"center\">$nr</td>\n";
                    echo "  <td align=\"center\">$pt</td>\n";
                    echo "  <td align=\"center\">$pe</td>\n";
                    echo "  <td align=\"center\">$tp</td>\n";
                    echo "  <td align=\"center\">$nrr</td>\n";
                    echo "  <td align=\"center\">$trf/$tof_formatted</td>\n";
                    echo "  <td align=\"center\">$tra/$toa_formatted</td>\n";
                    //echo "    <td align=\"center\">$av</td>\n";
                    echo "</tr>\n";

                }
        }
                        echo "</table>\n";

                        echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";
		} else {
            $subdb1->Query("
                SELECT GroupName
                FROM
                  groups
                WHERE
                  SeasonID=$ladder AND Round = $round group by GroupName
            ");
			for ($t=0; $t<$subdb1->rows; $t++) {
                $subdb1->GetRow($t);
                $grpnm = $subdb1->data['GroupName'];
					
				echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
				echo "  <tr>\n";
				echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Round $round GROUP $grpnm STANDINGS</td>\n";
				echo "  </tr>\n";
				echo "  <tr>\n";
				echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

				echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

        if (!$db->Exists("SELECT hometeam as team FROM schedule WHERE season=$ladder 
						union 
						SELECT awayteam as team FROM schedule WHERE season=$ladder
        ")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded in {$seasons[$ladder]}.</p></td>\n";
            echo "</tr>\n";
        } else {
			$lad_data = null;
            $db->Query("SELECT hometeam as team FROM schedule s, groups g WHERE s.season=$ladder AND g.SeasonID = s.season AND s.hometeam = g.TeamID AND g.GroupName = '$grpnm' AND g.Round = $round
						union 
						SELECT awayteam as team FROM schedule s, groups g WHERE s.season=$ladder AND g.SeasonID = s.season AND s.awayteam = g.TeamID AND g.GroupName = '$grpnm' AND g.Round = $round");
			
                echo "<tr class=\"colhead\">\n";
                echo "  <td align=\"left\"><b>TEAM</b></td>\n";
                echo "  <td align=\"center\"><b>Pl</b></td>\n";
                echo "  <td align=\"center\"><b>W</b></td>\n";
                echo "  <td align=\"center\"><b>T</b></td>\n";
                echo "  <td align=\"center\"><b>L</b></td>\n";
                echo "  <td align=\"center\"><b>NR</b></td>\n";
                echo "  <td align=\"center\"><b>Pt</b></td>\n";
                echo "  <td align=\"center\"><b>Pen</b></td>\n";
                echo "  <td align=\"center\"><b>Total</b></td>\n";
                echo "  <td align=\"center\"><b>NRR</b></td>\n";
                echo "  <td align=\"center\"><b>Runs For</b></td>\n";
                echo "  <td align=\"center\"><b>Runs Against</b></td>\n";
                //echo "    <td align=\"center\"><b>Avg</b></td>\n";
                echo "</tr>\n";
				
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$tid = $db->data['team'];
					$te = $subdb->QueryItem("SELECT TeamAbbrev FROM teams WHERE TeamID = $tid");
					if($subdb->Exists("SELECT penalty FROM ladder WHERE season=$ladder and team = $tid")) {
						$pe = $subdb->QueryItem("SELECT penalty FROM ladder WHERE season=$ladder and team = $tid");
					} else {
						$pe = 0;
					}
					$subdb->Query("SELECT * FROM groups where round = $round and GroupName = (SELECT GroupName FROM groups where TeamID = $tid and round = $round AND SeasonID = $ladder) AND TeamID != $tid AND SeasonID = $ladder");
					$groupTeamsArr = array();
					for ($r=0; $r<$subdb->rows; $r++) {
						$subdb->GetRow($r);
						$gtid = $subdb->data['TeamID'];
						$groupTeamsArr[$r] = $gtid;
					}
					$groupTeams = implode(",", $groupTeamsArr);
					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.hometeam and g.awayteam in ($groupTeams)) OR (g.awayteam = $tid and t.team = g.awayteam and g.hometeam in ($groupTeams)))");
					$subdb->BagAndTag();
					$trf = $subdb->data['TotalRunsFor'];
					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.awayteam and g.awayteam in ($groupTeams)) OR (g.awayteam = $tid and t.team = g.hometeam and g.hometeam in ($groupTeams)))");
					$subdb->BagAndTag();
					$tra = $subdb->data['TotalRunsAgainst'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and g.batting_second_id in ($groupTeams) and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and g.batting_first_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and g.batting_first_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
					$subdb->BagAndTag();
					$tof = $subdb->data['TotalOverFor'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_second_id = $tid and g.batting_first_id in ($groupTeams) and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and g.batting_second_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $ladder and g.isplayoff = 0 and g.batting_first_id = $tid and g.batting_second_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
					$subdb->BagAndTag();
					$toa = $subdb->data['TotalOverAgainst'];
					if($trf > 0 && $tof > 0) {
						if($tra > 0 && $toa > 0) {
							$nrr = Round(($trf * 6 / $tof) - ($tra * 6 / $toa), 2);
						} else {
							$nrr = Round(($trf * 6 / $tof), 2);
						}
					} else {
						$nrr = 0;
					}
					$pl = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$ladder AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND (points = 1 OR points = \"\" OR points is NULL)");
					$wo = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$ladder AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND cancelledplay=0 AND cancelled=0 AND result_won_id = $tid");
					$ti = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$ladder AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND cancelledplay=0 AND cancelled=0 AND result_won_id=0");
					$nr = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$ladder AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND (cancelledplay=1 OR cancelled=1) AND points = 1");
					$lo = $pl - $wo - $ti - $nr;
					$pt = ($wo * 2) + $ti + $nr;
					$tp = $pt - $pe;
					$tof_o = Round(($tof / 6), 2); 
					$tof_floor = floor($tof_o); 

					if($tof_o == $tof_floor + 0.17) { 
					  $tof_formatted = $tof_floor + 0.1; 
					} else 
					if($tof_o == $tof_floor + 0.33) { 
					  $tof_formatted = $tof_floor + 0.2; 
					} else 
					if($tof_o == $tof_floor + 0.5) { 
					  $tof_formatted = $tof_floor + 0.3;        
					} else 
					if($tof_o == $tof_floor + 0.67) { 
					  $tof_formatted = $tof_floor + 0.4;        
					} else 
					if($tof_o == $tof_floor + 0.83) { 
					  $tof_formatted = $tof_floor + 0.5; 
					} else { 
					  $tof_formatted = $tof_floor; 
					}
					
					$toa_o = Round(($toa / 6), 2); 
					$toa_floor = floor($toa_o); 

					if($toa_o == $toa_floor + 0.17) { 
					  $toa_formatted = $toa_floor + 0.1; 
					} else 
					if($toa_o == $toa_floor + 0.33) { 
					  $toa_formatted = $toa_floor + 0.2; 
					} else 
					if($toa_o == $toa_floor + 0.5) { 
					  $toa_formatted = $toa_floor + 0.3;        
					} else 
					if($toa_o == $toa_floor + 0.67) { 
					  $toa_formatted = $toa_floor + 0.4;        
					} else 
					if($toa_o == $toa_floor + 0.83) { 
					  $toa_formatted = $toa_floor + 0.5; 
					} else { 
					  $toa_formatted = $toa_floor; 
					}
					$lad_rec = array ("TeamID" => $tid, "TeamName" => $te, "played" => $pl, "won" => $wo, "lost" => $lo, "tied" => $ti, "nr" => $nr, "point" => $pt, "penalty" => $pe, "totalpoint" => $tp, "nrr" => $nrr, "trf" => $trf, "tof_formatted" => $tof_formatted, "tra" => $tra, "toa_formatted" => $toa_formatted);
					$lad_data[$x] = $lad_rec;


                }
				array_multisort (array_column($lad_data, 'totalpoint'), SORT_DESC, array_column($lad_data, 'nrr'), SORT_DESC, $lad_data);
				for ($i = 0; $i < count($lad_data); $i++) {
					$tid = $lad_data[$i]['TeamID'];
					$te = $lad_data[$i]['TeamName'];
					$pl = $lad_data[$i]['played'];
					$wo = $lad_data[$i]['won'];
					$lo = $lad_data[$i]['lost'];
					$ti = $lad_data[$i]['tied'];
					$nr = $lad_data[$i]['nr'];
					$pt = $lad_data[$i]['point'];
					$pe = $lad_data[$i]['penalty'];
					$tp = $lad_data[$i]['totalpoint'];
					$nrr = $lad_data[$i]['nrr'];
					$trf = $lad_data[$i]['trf'];
					$tof_formatted = $lad_data[$i]['tof_formatted'];
					$tra = $lad_data[$i]['tra'];
					$toa_formatted = $lad_data[$i]['toa_formatted'];
					if($i % 2) {
					  echo "<tr class=\"trrow2\">\n";
					} else {
					  echo "<tr class=\"trrow1\">\n";
					}

                    echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
                    echo "  <td align=\"center\">$pl</td>\n";
                    echo "  <td align=\"center\">$wo</td>\n";
                    echo "  <td align=\"center\">$ti</td>\n";
                    echo "  <td align=\"center\">$lo</td>\n";
                    echo "  <td align=\"center\">$nr</td>\n";
                    echo "  <td align=\"center\">$pt</td>\n";
                    echo "  <td align=\"center\">$pe</td>\n";
                    echo "  <td align=\"center\">$tp</td>\n";
                    echo "  <td align=\"center\">$nrr</td>\n";
                    echo "  <td align=\"center\">$trf/$tof_formatted</td>\n";
                    echo "  <td align=\"center\">$tra/$toa_formatted</td>\n";
                    //echo "    <td align=\"center\">$av</td>\n";
                    echo "</tr>\n";
				}
        }
				echo "</table>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";
			}
		}
            
    //////////////////////////////////////////////////////////////////////////////////////////
    // Detailed Record
    //////////////////////////////////////////////////////////////////////////////////////////

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;TEAM FORM</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\"><b>TEAM</b></td>\n";
            echo "  <td align=\"center\"><b>Streak</b></td>\n";
            echo "  <td align=\"left\"><b>Form</b></td>\n";
            echo "  <td align=\"center\"><b>Home</b></td>\n";
            echo "  <td align=\"center\"><b>Away</b></td>\n";
            echo "</tr>\n";


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$ladder")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded in {$seasons[$ladder]}.</p></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("SELECT te.TeamID, te.TeamAbbrev FROM teams te INNER JOIN scorecard_batting_details ba ON te.TeamID=ba.team WHERE ba.season=$ladder AND te.LeagueID = 1 GROUP BY TeamID, TeamAbbrev");
            $db->BagAndTag();


            for ($r=0; $r<$db->rows; $r++) {
            $db->GetRow($r);

            $tid = $db->data['TeamID'];
            $te  = $db->data['TeamAbbrev'];
            
        // Get Home Games
            if (!$subdb->Exists("SELECT hometeam, count(hometeam) AS Homegames FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam")) {
              $homegames = "0";
            } else {
            $subdb->QueryRow("SELECT hometeam, count(hometeam) AS Homegames FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam");     
              $homegames = $subdb->data['Homegames'];
            }

        // Get Home Ties
            if (!$subdb->Exists("SELECT hometeam, count(hometeam) AS Hometies FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY hometeam")) {
              $hometies = "0";
            } else {
            $subdb->QueryRow("SELECT hometeam, count(hometeam) AS Hometies FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY hometeam");      
              $hometies = $subdb->data['Hometies'];
            }

                        
        // Get Home Wins            
            if (!$subdb->Exists("SELECT hometeam, count(result_won_id) AS Homewins FROM scorecard_game_details WHERE hometeam=result_won_id AND hometeam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam")) {
              $homewins = "0";
            } else {
            $subdb->QueryRow("SELECT hometeam, count(result_won_id) AS Homewins FROM scorecard_game_details WHERE hometeam=result_won_id AND hometeam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam");      
              $homewins = $subdb->data['Homewins'];
            }

        // Get Home Losses          
            $homelosses = $homegames - $homewins - $hometies;           
            

        // Get Away Games           
            if (!$subdb->Exists("SELECT awayteam, count(awayteam) AS Awaygames FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam")) {
              $awaygames = "0";
            } else {
            $subdb->QueryRow("SELECT awayteam, count(awayteam) AS Awaygames FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam");     
              $awaygames = $subdb->data['Awaygames'];
            }

        // Get Away Ties
            if (!$subdb->Exists("SELECT awayteam, count(awayteam) AS Awayties FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY awayteam")) {
              $awayties = "0";
            } else {
            $subdb->QueryRow("SELECT awayteam, count(awayteam) AS Awayties FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY awayteam");      
              $awayties = $subdb->data['Awayties'];
            }

        // Get Away Wins            
            if (!$subdb->Exists("SELECT awayteam, count(result_won_id) AS Awaywins FROM scorecard_game_details WHERE awayteam=result_won_id AND awayteam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam")) {
              $awaywins = "0";
            } else {
            $subdb->QueryRow("SELECT awayteam, count(result_won_id) AS Awaywins FROM scorecard_game_details WHERE awayteam=result_won_id AND awayteam=$tid AND season=$ladder and isplayoff = 0 AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam");      
              $awaywins = $subdb->data['Awaywins'];
            }

        // Get Away Losses          
            $awaylosses = $awaygames - $awaywins - $awayties;   



            $sql = "SELECT
               game_id,game_date,hometeam,awayteam,result_won_id FROM
               scorecard_game_details WHERE season=$ladder and isplayoff = 0 AND cancelledplay=0 AND
               cancelled=0 AND (hometeam=$tid OR awayteam=$tid) GROUP BY game_id
               ORDER BY game_date DESC";

            $streak  = 0;
            $winning = null;
            $history = '';
            $count   = true;

            if (!defined('WIN')) define('WIN',  1);
            if (!defined('LOSE')) define('LOSE', 2);
            if (!defined('TIE')) define('TIE',  3);

            $subdb->Query($sql);
            for ($s = 0; $s < $subdb->rows; $s++) {
               $subdb->GetRow($s);
			   $type = ($subdb->data['result_won_id'] == "0") ? TIE:(($subdb->data['result_won_id'] == $tid) ? WIN : LOSE);

        // Get streak
               if ($winning !== null && $type !== $winning && $count) {
                   $count = false;
               }
               if ($winning === null) {
                   $winning = $type;
               }
               if ($count) {
                   ++$streak;
               }

        // Get form
               $history .= ($type == TIE) ? 'T' : (($type == WIN) ? 'W' : 'L');
            }

            $plural  = ($streak == 1) ? '' : ($winning == LOSE ? 'es' : 's');
            $streak  = (string)$streak . (($winning == TIE) ? " tie$plural" :($winning ==WIN ? " win$plural" : " loss$plural"));
            $history = strrev($history);


            echo '<tr class="trrow', ($r % 2 ? '2' : '1'), '">';

            echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
            echo "  <td align=\"center\">$streak</td>\n";
            echo "  <td align=\"left\" class=\"standings\">$history</td>\n";
            echo "  <td align=\"center\">$homewins-$hometies-$homelosses</td>\n";
            echo "  <td align=\"center\">$awaywins-$awayties-$awaylosses</td>\n";
            echo "</tr>\n";

            }
        }
                echo "</table>\n";

                echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
            

    //////////////////////////////////////////////////////////////////////////////////////////
    // Points Table Legend
    //////////////////////////////////////////////////////////////////////////////////////////

            //echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            //echo "  <tr>\n";
            //echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;LEGEND - 100 POINTS PER GAME</td>\n";
            //echo "  </tr>\n";
            //echo "  <tr>\n";
        //echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        //echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">40 Result Points</td><td width=\"70%\">40 points to winning team, 0 points to losing team.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">20 Batting Points<br><i>(10 per inning)</i></td><td width=\"70%\">1 point for every 15 runs or 10% of opponent’s score whichever is higher.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">20 Bowling Points<br><i>(10 per inning)</i></td><td width=\"70%\">1 point for every wicket.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">20 Margin Points</td><td width=\"70%\">If team batting first wins:<br>2 points for every 10% victory margin.<br><br>If team batting second wins:<br>2 points for every 10% overs to spare.<br><br>Left over Batting, Bowling and Margin points go to the opponent.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">Washed Out Games</td><td width=\"70%\">20 points per team. Game must be replayed.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">Forfeited Games</td><td width=\"70%\">80 points to winning team.</td>\n";
        //echo "  </tr>\n";         
        //echo "</table>\n";

        //echo "  </td>\n";
        //echo "</tr>\n";
        //echo "</table>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        }
}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



switch($_GET['ccl_mode']) {
case 0:
    show_ladder_listing($db,$s,$id,$pr);
    break;
case 1:
    show_ladder($db,$_GET['ladder'], isset($_GET['round']) ? $_GET['round'] : "");
    break;
default:
    show_ladder_listing($db,$s,$id,$pr);
    break;
}

?>
