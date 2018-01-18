<?php

//------------------------------------------------------------------------------
// Mini Schedule v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_mini_schedule($db, $season)
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


        $db->Query("
            SELECT
                sch.*, DATE_FORMAT(sch.date, '%b %e') as formatted_date, 
                te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev
            FROM
                tennisschedule sch
	    INNER JOIN
	        tennisteams te
	    ON
	        sch.awayteam = te.TeamID
	    INNER JOIN
	        tennisteams t1
	    ON
		sch.hometeam = t1.TeamID             
            WHERE
                sch.date >= NOW() AND
                sch.date <= DATE_ADD(NOW(), INTERVAL 7 DAY)
            ORDER BY
                sch.id
            ");

        if (!$db->rows) {

        	echo "<p>No Games in the near future.</p>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\" class=\"tablehead\">\n";

        	for ($x = 0; $x < $db->rows; $x++) {
                $db->GetRow($x);
		$t1 = $db->data[homeabbrev];
		$t2 = $db->data[awayabbrev];
		$um = $db->data[umpireabbrev];
		$t1id = $db->data[homeid];
		$t2id = $db->data[awayid];
                $da  = $db->data['formatted_date'];
                $we  = $db->data['week'];
                $se  = $db->data['season'];


				if($x % 2) {
				  echo "<tr class=\"trrow2\">\n";
				} else {
				  echo "<tr class=\"trrow1\">\n";
				}

                echo "  <td align=\"left\" class=\"10px\">$da</td>\n";
                echo "  <td align=\"left\" class=\"10px\"><a href=\"teams.php?teams=$t2id&ccl_mode=1\">$t2</a> v <a href=\"teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }
    }
}


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_mini_schedule($db, 4);

?>
