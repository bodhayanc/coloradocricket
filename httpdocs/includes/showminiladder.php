<?php


function show_miniladder($db)
{
    global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
    
	// instantiate new db class
		$subdb = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
		$subdb->SelectDB($dbcfg['db']);

        if (!$db->Exists("SELECT * FROM ladder")) {

	echo "<p>There are currently no games in the database.</p>\n";

        } else {

// 28-Nov-09 - Kervyn
  //              $db->Query("SELECT * FROM seasons WHERE SeasonName LIKE '%2010 CCL%' ORDER BY SeasonName DESC");
			$db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Premier%' ORDER BY SeasonID DESC LIMIT 1");
			$db->BagAndTag();

			$sid = $db->data['SeasonID'];
			$snm = $db->data['SeasonName'];
			$yr = preg_split("/[\s,]+/", $snm)[0];

		echo "  <table width=\"100%\" border-right=\"1\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\" bordercolor=\"#DE9C06\">\n";

			echo "<tr class=\"colhead\">\n";
			echo "	<td align=\"left\"><b>Team</b></td>\n";
			echo "	<td align=\"center\"><b>P</b></td>\n";
			echo "	<td align=\"center\"><b>W</b></td>\n";
			echo "	<td align=\"center\"><b>T</b></td>\n";
			echo "	<td align=\"center\"><b>L</b></td>\n";
			echo "	<td align=\"center\"><b>NR</b></td>\n";
			echo "	<td align=\"center\"><b>Pt</b></td>\n";
			echo "	<td align=\"center\"><b>NRR</b></td>\n";
			echo "</tr>\n";
			
// 28-Nov-09 - Kervyn - Removed order by clause columns - ORDER BY won DESC, lost ASC, and replaced it with ORDER BY lad.totalpoints DESC, lad.rank_sort ASC
// 20-Apr-2010 Removed order by points
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
			echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded at this time.</p></td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";

		} else {
			$db->Query("
					SELECT
				  lad. * , tm.TeamAbbrev AS teamname, tm.TeamID as tid
				FROM
				  ladder lad
				INNER JOIN
				  teams tm ON lad.team = tm.TeamID
				WHERE
					season=$sid ORDER BY lad.totalpoints DESC, lad.rank_sort ASC
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
			$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsFor FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and t.team = $tid");
			$subdb->BagAndTag();
			$trf = $subdb->data['TotalRunsFor'];
			$subdb->QueryRow("SELECT IFNULL(SUM(CASE WHEN (t.dl_total IS NOT NULL AND t.dl_total != 0) THEN t.dl_total ELSE t.total END), 0) AS TotalRunsAgainst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and ((g.hometeam = $tid and t.team = g.awayteam) OR (g.awayteam = $tid and t.team = g.hometeam))");
			$subdb->BagAndTag();
			$tra = $subdb->data['TotalRunsAgainst'];
			$subdb->QueryRow("SELECT (IFNULL(TotalOversForFirst, 0) + IFNULL(TotalOversForSecondWin, 0) + IFNULL(TotalOversForSecondLoose, 0)) AS TotalOverFor FROM (SELECT TotalOversConvForFirst + TotalBallsForFirst AS TotalOversForFirst, TotalOversConvForSecondWin + TotalBallsForSecondWin AS TotalOversForSecondWin, TotalOversConvForSecondLoose + TotalBallsForSecondLoose AS TotalOversForSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvForFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.batting_first_id = $tid and t.innings_id = 1) sums,(SELECT sum(SUBSTRING_INDEX(t.overs,'.',1)) * 6 AS TotalOversConvForSecondWin, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsForSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsForSecWin,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvForSecondLoose, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsForSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.batting_second_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumForSecLoose) sumOversFor");
			$subdb->BagAndTag();
			$tof = $subdb->data['TotalOverFor'];
			$subdb->QueryRow("SELECT (IFNULL(TotalOversAgainstFirst, 0) + IFNULL(TotalOversAgainstSecondWin, 0) + IFNULL(TotalOversAgainstSecondLoose, 0)) AS TotalOverAgainst FROM (SELECT TotalOversConvAgainstFirst + TotalBallsAgainstFirst AS TotalOversAgainstFirst, TotalOversConvAgainstSecondWin + TotalBallsAgainstSecondWin AS TotalOversAgainstSecondWin, TotalOversConvAgainstSecondLoose + TotalBallsAgainstSecondLoose AS TotalOversAgainstSecondLoose FROM (SELECT sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',1)) * 6 AS TotalOversConvAgainstFirst, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstFirst FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.batting_second_id = $tid and t.innings_id = 1) sumAgainstFirst,(SELECT sum(SUBSTRING_INDEX(g.maxovers,'.',1)) * 6 AS TotalOversConvAgainstSecondWin, sum(SUBSTRING_INDEX(FORMAT(g.maxovers, 1),'.',-1)) AS TotalBallsAgainstSecondWin FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id = $tid) sumsAgainstSecWin,(SELECT sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',1)) * 6 AS TotalOversConvAgainstSecondLoose, sum(SUBSTRING_INDEX(FORMAT(t.overs, 1),'.',-1)) AS TotalBallsAgainstSecondLoose FROM scorecard_game_details g INNER JOIN scorecard_total_details t ON t.game_id = g.game_id WHERE (g.cancelled = 0 and g.cancelledplay = 0) and g.season = $sid and g.batting_first_id = $tid and t.innings_id = 2 and g.result_won_id != $tid) sumAgainstSecLoose) sumOversAgainst");
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
					echo "	<td align=\"center\">$nrr</td>\n";
					echo "</tr>\n";


				}
				if($x % 2) {
				echo "<tr class=\"trrow1\">\n";
			} else {
				echo "<tr class=\"trrow2\">\n";
			}
			echo "	<td align=\"center\" colspan=8><a href=\"/ladder.php?ladder=$sid&ccl_mode=1\" class=\"right\">More Details</a></td>\n";
			echo "</tr>\n";
		}
		
                        echo "</table>\n";

        }
}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_miniladder($db);


?>
