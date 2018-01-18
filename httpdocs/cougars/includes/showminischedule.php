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

    if (!$db->Exists('SELECT id FROM cougarsschedule LIMIT 1')) {
        echo "<p>There are currently no scheduled games in the database.</p>\n";
        return;

    } else {

        $db->Query('SELECT * FROM seasons ORDER BY SeasonID');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
        }

        $db->Query('SELECT * FROM cougarsteams ORDER BY TeamName');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
        }

        $db->Query("
            SELECT
                sch.*,
                DATE_FORMAT(sch.date, '%b %e') as formatted_date
            FROM
                cougarsschedule sch
            WHERE
                sch.date >= NOW() AND
                sch.date <= DATE_ADD(NOW(), INTERVAL 168 DAY)
            ORDER BY
                sch.id
            ");

        if (!$db->rows) {

        	echo "<p>No Games in the near future.</p>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";

        	for ($x = 0; $x < $db->rows; $x++) {
                $db->GetRow($x);
                $t1 = htmlentities(stripslashes($teams[$db->data['hometeam']]));
                $t2 = htmlentities(stripslashes($teams[$db->data['awayteam']]));
                $da  = $db->data['formatted_date'];
                $we  = $db->data['week'];
                $se  = $db->data['season'];
                $l1  = $db->data['hometeam'];
                $l2  = $db->data['awayteam'];

				if($x % 2) {
				  echo "<tr class=\"trrow1\">\n";
				} else {
				  echo "<tr class=\"trrow2\">\n";
				}

                echo "  <td align=\"left\" class=\"9px\">$da</td>\n";
                echo "  <td align=\"left\" class=\"9px\">$t2 v $t1</td>\n";
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
