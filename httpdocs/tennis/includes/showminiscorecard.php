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

    if (!$db->Exists('SELECT id FROM tennisschedule LIMIT 1')) {
        echo "<p>There are currently no scheduled games in the database.</p>\n";
        return;

    } else {

        $db->Query('SELECT * FROM seasons ORDER BY SeasonID');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
        }

        $db->Query('SELECT * FROM tennisteams ORDER BY TeamName');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
        }

        $db->Query("
	SELECT
	  s.*,
	  DATE_FORMAT(s.game_date, '%b %e') as formatted_date, 
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev'
	FROM
	  tennis_scorecard_game_details s
	INNER JOIN
	  tennisteams a ON s.awayteam = a.TeamID
	INNER JOIN
	  tennisteams h ON s.hometeam = h.TeamID
	WHERE
          s.game_date <= NOW() AND
          s.game_date >= DATE_SUB(NOW(), INTERVAL 7 DAY) 
	ORDER BY
	  s.week, s.game_date, s.game_id  
            ");

        if (!$db->rows) {

        	echo "<p>No Games recently.</p>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\"  class=\"tablehead\">\n";

        	for ($x = 0; $x < $db->rows; $x++) {
                $db->GetRow($x);
		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$um = $db->data['umpireabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$umid = $db->data['umpireid'];
		$d = $db->data['formatted_date'];
		$sc =  $db->data['scorecard'];
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$wk = $db->data['week'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];

		if($x % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

			//echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
			
			echo "  <td align=\"left\" class=\"10px\"><b>$t1 v $t2</b><br>";
			//echo "  <a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";

			if($fo == "0" && $ca == "0") {
			  echo "  <a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
			} else if($fo == "1" && $ca == "0") {
			  echo "  Forfeit</td>\n";
			} else if($ca == "1" && $fo = "1") {
			  echo "  Game cancelled</td>\n";
			} else {
			  echo "  <a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
			}
			
			//if($fo == "0" && $ca == "0") {
			//  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$t1 v $t2</a></td>\n";
			//} else if($fo == "1" && $ca == "0") {
			//  echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
			//} else if($ca == "1" && $fo = "1") {
			//  echo "  <td align=\"left\" class=\"9px\">Game cancelled</td>\n";
			//} else {
			//  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$t1 v $t2</a></td>\n";
			//}

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
