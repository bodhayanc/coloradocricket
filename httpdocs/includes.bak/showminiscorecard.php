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
          s.game_date <= NOW() AND
          s.game_date >= DATE_SUB(NOW(), INTERVAL 8 DAY)	  
	ORDER BY
	  s.week DESC, s.game_date DESC, s.game_id DESC
            ");

        if (!$db->rows) {

        	echo "<p>No Games last week.</p>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\" class=\"tablehead\">\n";

        	for ($x = 0; $x < $db->rows; $x++) {
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

				
		if($fo == "0" && $ca == "0") {						
                echo "  <td align=\"left\" class=\"9px\">$ss_final_text $t2 at $t1<br><a href=\"/scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                } else if($fo == "1" && $ca == "0") {
                echo "  <td align=\"left\" class=\"9px\">$ss_final_text $t2 at $t1<br>Forfeit</td>\n";
                } else if($ca == "1" && $fo = "1") {
                echo "  <td align=\"left\" class=\"9px\">$ss_final_text $t2 at $t1<br>Game cancelled</span></td>\n";
                } else {
                echo "  <td align=\"left\" class=\"9px\">$ss_final_text $t2 at $t1<br><a href=\"/scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
	        }

                echo "</tr>\n";
            }
            echo "</table>\n";
        }
    }
}


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_mini_scorecard($db, 4);

?>
