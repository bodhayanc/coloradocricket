<?php


function show_miniladdertwenty($db)
{
    global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
    $endOfSeasonLad = 1;
	$round = 2;
	// instantiate new db class
		$subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
		$subdb->SelectDB($dbcfg['db']);
		$subdb1 = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
		$subdb1->SelectDB($dbcfg['db']);

        if (!$db->Exists("SELECT * FROM ladder")) {

	echo "<p>There are currently no games in the database.</p>\n";

        } else {

//                $db->Query("SELECT * FROM seasons WHERE SeasonName LIKE '%2011 Twenty%' ORDER BY SeasonName DESC");
			$db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Twenty20%' ORDER BY SeasonID DESC LIMIT 1");
			$db->BagAndTag();

			$sid = $db->data['SeasonID'];
			$snm = $db->data['SeasonName'];
			$yr = preg_split("/[\s,]+/", $snm)[0];
			if (!$subdb1->Exists("
                SELECT *
                FROM
                  groups
                WHERE
                  SeasonID=$sid
        ") || $endOfSeasonLad == 1) {
			$divisions = false;
            $db->Query("
                    SELECT division FROM ladder lad WHERE season=$sid group by division");
			if($db->rows > 1) {
				$divisions = true;
			}
		echo "  <table border-right=\"1\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\" bordercolor=\"#DE9C06\">\n";


// 20-Apr-2010 - Kervyn - Removed order by clause columns - ORDER BY lad.totalpoints DESC, lad.rank_sort ASC and replaced it with just ORDER lad.rank_sort ASC
// 28-Nov-09 - Kervyn - Removed order by clause columns - ORDER BY won DESC, lost ASC, and replaced it with ORDER BY lad.totalpoints DESC, lad.rank_sort ASC			
		if (!$db->Exists("
				SELECT
				  lad.* , tm.TeamAbbrev AS team
				FROM
				  ladder lad
				INNER JOIN
				  teams tm ON lad.team = tm.TeamID
				WHERE
					season=$sid ORDER BY lad.rank_sort ASC
		")) {
			echo "<tr class=\"trrow2\">\n";
			echo "  <td align=\"left\" colspan=\"8\"><p>No games recorded at this time.</p></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";

		} else {
			if($divisions == true) {
				echo "<tr>\n";
				echo "  <td colspan=\"12\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;DIVISION 1 LEAGUE STANDINGS</td>\n";
				echo "</tr>\n";
			}
			echo "<tr class=\"colhead\">\n";
			echo "	<td align=\"left\"><b>Team</b></td>\n";
			echo "	<td align=\"center\"><b>P</b></td>\n";
			echo "	<td align=\"center\"><b>W</b></td>\n";
			echo "	<td align=\"center\"><b>T</b></td>\n";
			echo "	<td align=\"center\"><b>L</b></td>\n";
			echo "	<td align=\"center\"><b>NR</b></td>\n";
			echo "	<td align=\"center\"><b>Pt</b></td>\n";
			echo "	<td align=\"right\"><b>NRR</b></td>\n";
			echo "</tr>\n";
			$db->Query("
					SELECT
				  lad. * , tm.TeamAbbrev AS teamname, tm.TeamID as tid
				FROM
				  ladder lad
				INNER JOIN
				  teams tm ON lad.team = tm.TeamID
				WHERE
					season=$sid AND division = 1 ORDER BY lad.rank_sort ASC
			");


			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);

					$tid = $db->data['tid'];
					$te = htmlentities(stripslashes($db->data['teamname']));
					$pl = htmlentities(stripslashes($db->data['played']));
					$wo = htmlentities(stripslashes($db->data['won']));
					$lo = htmlentities(stripslashes($db->data['lost']));
					$ti = htmlentities(stripslashes($db->data['tied']));
					$nr = htmlentities(stripslashes($db->data['nrr']));
					$pt = htmlentities(stripslashes($db->data['points']));
					$pe = htmlentities(stripslashes($db->data['penalty']));
					$tp = htmlentities(stripslashes($db->data['totalpoints']));
			$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and t.team = $tid");
			$subdb->BagAndTag();
			$trf = $subdb->data['TotalRunsFor'];
			$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.awayteam) OR (g.awayteam = $tid and t.team = g.hometeam))");
			$subdb->BagAndTag();
			$tra = $subdb->data['TotalRunsAgainst'];
			$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
			$subdb->BagAndTag();
			$tof = $subdb->data['TotalOverFor'];
			$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
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

			if($x % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}



					echo "	<td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\" class=\"right\">$te</a></td>\n";
					echo "	<td align=\"center\">$pl</td>\n";
					echo "	<td align=\"center\">$wo</td>\n";
					echo "	<td align=\"center\">$ti</td>\n";
					echo "	<td align=\"center\">$lo</td>\n";
					echo "	<td align=\"center\">$nr</td>\n";
					echo "	<td align=\"center\">$tp</td>\n";
					echo "	<td align=\"right\">$nrr</td>\n";
					echo "</tr>\n";


				}
			if($x % 2) {
				echo "<tr class=\"trrow1\">\n";
			} else {
				echo "<tr class=\"trrow2\">\n";
			}
			if($divisions == true) {
				echo "<tr>\n";
				echo "  <td colspan=\"12\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;DIVISION 2 LEAGUE STANDINGS</td>\n";
				echo "</tr>\n";
			}
			echo "<tr class=\"colhead\">\n";
			echo "	<td align=\"left\"><b>Team</b></td>\n";
			echo "	<td align=\"center\"><b>P</b></td>\n";
			echo "	<td align=\"center\"><b>W</b></td>\n";
			echo "	<td align=\"center\"><b>T</b></td>\n";
			echo "	<td align=\"center\"><b>L</b></td>\n";
			echo "	<td align=\"center\"><b>NR</b></td>\n";
			echo "	<td align=\"center\"><b>Pt</b></td>\n";
			echo "	<td align=\"right\"><b>NRR</b></td>\n";
			echo "</tr>\n";
			$db->Query("
					SELECT
				  lad. * , tm.TeamAbbrev AS teamname, tm.TeamID as tid
				FROM
				  ladder lad
				INNER JOIN
				  teams tm ON lad.team = tm.TeamID
				WHERE
					season=$sid AND division = 2 ORDER BY lad.rank_sort ASC
			");


			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);

					$tid = $db->data['tid'];
					$te = htmlentities(stripslashes($db->data['teamname']));
					$pl = htmlentities(stripslashes($db->data['played']));
					$wo = htmlentities(stripslashes($db->data['won']));
					$lo = htmlentities(stripslashes($db->data['lost']));
					$ti = htmlentities(stripslashes($db->data['tied']));
					$nr = htmlentities(stripslashes($db->data['nrr']));
					$pt = htmlentities(stripslashes($db->data['points']));
					$pe = htmlentities(stripslashes($db->data['penalty']));
					$tp = htmlentities(stripslashes($db->data['totalpoints']));
			$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and t.team = $tid");
			$subdb->BagAndTag();
			$trf = $subdb->data['TotalRunsFor'];
			$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.awayteam) OR (g.awayteam = $tid and t.team = g.hometeam))");
			$subdb->BagAndTag();
			$tra = $subdb->data['TotalRunsAgainst'];
			$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
			$subdb->BagAndTag();
			$tof = $subdb->data['TotalOverFor'];
			$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
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

			if($x % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}



					echo "	<td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\" class=\"right\">$te</a></td>\n";
					echo "	<td align=\"center\">$pl</td>\n";
					echo "	<td align=\"center\">$wo</td>\n";
					echo "	<td align=\"center\">$ti</td>\n";
					echo "	<td align=\"center\">$lo</td>\n";
					echo "	<td align=\"center\">$nr</td>\n";
					echo "	<td align=\"center\">$tp</td>\n";
					echo "	<td align=\"right\">$nrr</td>\n";
					echo "</tr>\n";


				}
			if($x % 2) {
				echo "<tr class=\"trrow1\">\n";
			} else {
				echo "<tr class=\"trrow2\">\n";
			}
			echo "	<td align=\"center\" colspan=8 style=\"border: none;\"><a href=\"/ladder.php?ladder=$sid&ccl_mode=1\" class=\"right\">More Details</a></td>\n";
			echo "</tr>\n";
		}
                        echo "</table>\n";

        } else {
			$subdb1->Query("
                SELECT *
                FROM
                  groups
                WHERE
                  SeasonID=$sid group by GroupName
            ");
			for ($t=0; $t<$subdb1->rows; $t++) {
                $subdb1->GetRow($t);
                $grpnm = $subdb1->data['GroupName'];
				echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
				echo "  <tr>\n";
				echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\" style=\"border-left: solid #DE9C06; border-right: solid #DE9C06;\">&nbsp;Round $round GROUP $grpnm STANDINGS</td>\n";
				echo "  </tr>\n";
				echo "  <tr>\n";
				echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
			echo "  <table border-right=\"1\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\" bordercolor=\"#DE9C06\" style=\"border-bottom: none; border-top: none;\">\n";

			echo "<tr class=\"colhead\">\n";
			echo "	<td align=\"left\"><b>Team</b></td>\n";
			echo "	<td align=\"center\"><b>P</b></td>\n";
			echo "	<td align=\"center\"><b>W</b></td>\n";
			echo "	<td align=\"center\"><b>T</b></td>\n";
			echo "	<td align=\"center\"><b>L</b></td>\n";
			echo "	<td align=\"center\"><b>NR</b></td>\n";
			echo "	<td align=\"center\"><b>Pt</b></td>\n";
			echo "	<td align=\"right\"><b>NRR</b></td>\n";
			echo "</tr>\n";

// 20-Apr-2010 - Kervyn - Removed order by clause columns - ORDER BY lad.totalpoints DESC, lad.rank_sort ASC and replaced it with just ORDER lad.rank_sort ASC
// 28-Nov-09 - Kervyn - Removed order by clause columns - ORDER BY won DESC, lost ASC, and replaced it with ORDER BY lad.totalpoints DESC, lad.rank_sort ASC			
		if (!$db->Exists("
				SELECT
				  lad.* , tm.TeamAbbrev AS team
				FROM
				  ladder lad
				INNER JOIN
				  teams tm ON lad.team = tm.TeamID
				INNER JOIN
                  groups gp ON tm.TeamID = gp.TeamID
                WHERE
                  lad.season = gp.SeasonID AND season=$sid AND gp.GroupName = '$grpnm' ORDER BY lad.rank_sort ASC
		")) {
			echo "<tr class=\"trrow2\">\n";
			echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded at this time.</p></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";

		} else {
			$lad_data = null;
            $db->Query("SELECT hometeam as team FROM schedule s, groups g WHERE s.season=$sid AND g.SeasonID = s.season AND s.hometeam = g.TeamID AND g.GroupName = '$grpnm' AND g.Round = $round
						union 
						SELECT awayteam as team FROM schedule s, groups g WHERE s.season=$sid AND g.SeasonID = s.season AND s.awayteam = g.TeamID AND g.GroupName = '$grpnm' AND g.Round = $round");
			
                for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$tid = $db->data['team'];
					$te = $subdb->QueryItem("SELECT TeamAbbrev FROM teams WHERE TeamID = $tid");
					if($subdb->Exists("SELECT penalty FROM ladder WHERE season=$sid and team = $tid")) {
						$pe = $subdb->QueryItem("SELECT penalty FROM ladder WHERE season=$sid and team = $tid");
					} else {
						$pe = 0;
					}
					$subdb->Query("SELECT * FROM groups where round = $round and GroupName = (SELECT GroupName FROM groups where TeamID = $tid and round = $round AND SeasonID = $sid) AND TeamID != $tid AND SeasonID = $sid");
					$groupTeamsArr = array();
					for ($r=0; $r<$subdb->rows; $r++) {
						$subdb->GetRow($r);
						$gtid = $subdb->data['TeamID'];
						$groupTeamsArr[$r] = $gtid;
					}
					$groupTeams = implode(",", $groupTeamsArr);
					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and  ((g.hometeam = $tid and t.team = g.hometeam and g.awayteam in ($groupTeams)) OR (g.awayteam = $tid and t.team = g.awayteam and g.hometeam in ($groupTeams)))");
					$subdb->BagAndTag();
					$trf = $subdb->data['TotalRunsFor'];
					$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and ((g.hometeam = $tid and t.team = g.awayteam and g.awayteam in ($groupTeams)) OR (g.awayteam = $tid and t.team = g.hometeam and g.hometeam in ($groupTeams)))");
					$subdb->BagAndTag();
					$tra = $subdb->data['TotalRunsAgainst'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and g.batting_second_id in ($groupTeams) and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and g.batting_first_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and g.batting_first_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
					$subdb->BagAndTag();
					$tof = $subdb->data['TotalOverFor'];
					$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_second_id = $tid and g.batting_first_id in ($groupTeams) and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and g.batting_second_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.isplayoff = 0 and g.batting_first_id = $tid and g.batting_second_id in ($groupTeams) and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
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
						   scorecard_game_details WHERE season=$sid AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND (points = 1 OR points = \"\" OR points is NULL)");
					$wo = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$sid AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND cancelledplay=0 AND cancelled=0 AND result_won_id = $tid");
					$ti = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$sid AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND cancelledplay=0 AND cancelled=0 AND result_won_id=0");
					$nr = $subdb->QueryItem("SELECT count(game_id) FROM
						   scorecard_game_details WHERE season=$sid AND isplayoff = 0 AND ((hometeam=$tid and awayteam in ($groupTeams)) OR (awayteam=$tid and hometeam in ($groupTeams))) AND (cancelledplay=1 OR cancelled=1) AND points = 1");
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
				if($lad_data != null) {
					array_multisort (array_column($lad_data, 'totalpoint'), SORT_DESC, array_column($lad_data, 'nrr'), SORT_DESC, $lad_data);
				}
				for ($i = 0; $i < count($lad_data) && $i < 5; $i++) {
					$tid = $lad_data[$i]['TeamID'];
					$te = $lad_data[$i]['TeamName'];
					$pl = $lad_data[$i]['played'];
					$wo = $lad_data[$i]['won'];
					$lo = $lad_data[$i]['lost'];
					$ti = $lad_data[$i]['tied'];
					$nr = $lad_data[$i]['nr'];
					$tp = $lad_data[$i]['totalpoint'];
					$nrr = $lad_data[$i]['nrr'];
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
                    echo "  <td align=\"center\">$tp</td>\n";
                    echo "  <td align=\"center\">$nrr</td>\n";
                    echo "</tr>\n";
				}
        }
                        echo "</table>\n";
						echo "    </td>\n";
            echo "  </tr>\n";
			if($t == $subdb1->rows - 1) {
				echo "<tr class=\"trrow1\">\n";
				echo "	<td align=\"center\" colspan=8 style=\"border: solid #DE9C06; border-top: none;\"><a href=\"/ladder.php?ladder=$sid&round=$round&ccl_mode=1\" class=\"right\">More Details</a></td>\n";
				echo "</tr>\n";
			}
            echo "</table>\n";
			}

        }
		}
}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_miniladdertwenty($db);


?>
