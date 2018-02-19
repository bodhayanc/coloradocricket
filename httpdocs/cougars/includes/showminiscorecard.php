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

    if (!$db->Exists('SELECT game_id FROM scorecard_game_details LIMIT 1')) {
        echo "<p>There are currently no scheduled games in the database.</p>\n";
        return;

    } else {

        $db->Query('SELECT * FROM seasons ORDER BY SeasonID');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
        }

        $db->Query('SELECT * FROM teams WHERE LeagueID = 2 ORDER BY TeamName');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
        }

        $db->Query("
	SELECT
	  s.*,
	  DATE_FORMAT(s.game_date, '%e %b %y') as formatted_date, 
	  a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
	  h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev'
	FROM
	  scorecard_game_details s
	INNER JOIN
	  teams a ON s.awayteam = a.TeamID
	INNER JOIN
	  teams h ON s.hometeam = h.TeamID
	WHERE
	  s.league_id = 2
	ORDER BY
	  s.game_date DESC
	LIMIT 4
            ");

        if (!$db->rows) {

        	echo "<p>No Games recently.</p>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";

        	for ($x = 0; $x < $db->rows; $x++) {
                $db->GetRow($x);
		$t1 = $db->data['homeabbrev'];
		$t2 = $db->data['awayabbrev'];
		$t1id = $db->data['homeid'];
		$t2id = $db->data['awayid'];
		$d = $db->data['formatted_date'];
		$re = $db->data['result'];
		$id = $db->data['game_id'];
		$fo = $db->data['forfeit'];
		$ca = $db->data['cancelled'];

				if($x % 2) {
				  echo "<tr class=\"trrow1\">\n";
				} else {
				  echo "<tr class=\"trrow2\">\n";
				}

				echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
				echo "  <td align=\"left\" class=\"9px\">$t2 v $t1<br>\n";
				
				if($fo == "0" && $ca == "0") {
				  echo "  <a href=\"http://www.coloradocricket.org/scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
				} else if($fo == "1" && $ca == "0") {
				  echo "  Forfeit</td>\n";
				} else if($ca == "1" && $fo = "1") {
				  echo "  Game cancelled</td>\n";
				} else {
				  echo "  <a href=\"http://www.coloradocricket.org/scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
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
