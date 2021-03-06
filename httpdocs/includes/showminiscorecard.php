<?php

//------------------------------------------------------------------------------
// Mini Scorecard v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_mini_scorecard($db, $season)
{
    $seasons = $teams = array();

	$db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Twenty20%' ORDER BY SeasonID DESC LIMIT 1");
	$db->BagAndTag();
	$t20sid = $db->data['SeasonID'];
    $db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Premier%' ORDER BY SeasonID DESC LIMIT 1");
	$db->BagAndTag();
	$p40sid = $db->data['SeasonID'];
	
    if (!$db->Exists('SELECT id FROM schedule LIMIT 1')) {
        echo "<p>There are currently no scheduled games in the database.</p>\n";
        return;

    } else {

        $db->Query('SELECT * FROM seasons ORDER BY SeasonID');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
        }

        $db->Query('SELECT * FROM teams ORDER BY TeamName');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
        }

		if ($db->Exists("SELECT
			  s.*,
			  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
			  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
			  ss.SeasonName as SeasonName
			FROM
			  scorecard_game_details s
			INNER JOIN
			  teams a ON s.awayteam = a.TeamID
			INNER JOIN
			  teams h ON s.hometeam = h.TeamID
			INNER JOIN
			  seasons ss ON ss.SeasonID = s.season
			WHERE
			  s.isactive=0 AND
				DATEDIFF(CURDATE(), s.game_date) < 14
			ORDER BY
			  s.game_date DESC, s.game_id DESC
        ")) {
			$db->Query("
				SELECT
				  s.*,
				  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
				  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
				  ss.SeasonName as SeasonName
				FROM
				  scorecard_game_details s
				INNER JOIN
				  teams a ON s.awayteam = a.TeamID
				INNER JOIN
				  teams h ON s.hometeam = h.TeamID
				INNER JOIN
				  seasons ss ON ss.SeasonID = s.season
				WHERE
				  s.isactive=0 AND
					s.game_date <= CURDATE() AND
					DATEDIFF((SELECT s.game_date FROM scorecard_game_details s WHERE 
					s.game_date <= CURDATE() ORDER BY s.game_date DESC, s.game_id DESC LIMIT 1), s.game_date) < 3
				ORDER BY
				  s.game_date DESC, s.game_id DESC
			");
		} else {
			$db->Query("
				SELECT
				  s.*,
				  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
				  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev',
				  ss.SeasonName as SeasonName
				FROM
				  scorecard_game_details s
				INNER JOIN
				  teams a ON s.awayteam = a.TeamID
				INNER JOIN
				  teams h ON s.hometeam = h.TeamID
				INNER JOIN
				  seasons ss ON ss.SeasonID = s.season
				WHERE
				  s.isactive=0 AND
					s.result like '%FINAL%' AND s.result not like '%SEMI%' AND
					DATEDIFF(CURDATE(), s.game_date) < 300
				ORDER BY
				  s.game_date DESC, s.game_id DESC
			");
		}

        if (!$db->rows) {

        	echo "<p>No Games last week.</p>\n";

        } else {

        echo "<table width=\"100%\" border-right=\"1\" cellpadding=\"2\" cellspacing=\"1\" class=\"tablehead\" bordercolor=\"#025A43\">\n";

        	for ($x = 0; $x < $db->rows && $x<6; $x++) {
                $db->GetRow($x);

		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$d = sqldate_to_string($db->data['game_date']);
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$wk = $db->data['week'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];
		$ss = explode(" ", $db->data['SeasonName']);

// Gets the last 2 digits of the year
//		$ss1 = substr($ss[0],2);
// Gets the 4 digits of the year
//		$ss1 = substr($ss[0],0, 4);

// Removes the first 4 digits
                $ss1 = substr($ss[0],4);
				$ss2 = $ss[1];
		// 2017-04-21 - Bodha - Shortened Premier to P40 and Twenty20 to T20
                //$ss_final_text = $ss2. " -";
		if($ss2 == 'Premier') {
			$ss_final_text = "P40 -";
		} else {
			$ss_final_text = "T20 -";
		}

// 2010_04_19 - Added the apostrophe in  front of the 2 digit year
//		$ss_final_text = "'". $ss1. " " .$ss2 . " -";
//		$ss_final_text = $ss1. " " .$ss2 . " -";
				

		if($x % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

				
		echo "  <td align=\"left\" class=\"9px\">$ss_final_text $t2 at $t1<br><a href=\"/scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
	   
        echo "</tr>\n";
        }
		if($x % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

				
		echo "  <td align=\"left\" class=\"9px\">More Details: <a href=\"/scorecard.php?schedule=$t20sid&ccl_mode=1\">T20</a> | <a href=\"/scorecard.php?schedule=$p40sid&ccl_mode=1\">P40</a></td>\n";
	   
        echo "</tr>\n";
        echo "</table>\n";
        }
    }
}


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_mini_scorecard($db, 4);

?>
