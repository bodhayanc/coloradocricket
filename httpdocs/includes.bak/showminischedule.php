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
        // 10-Apr-2010 Removed NOW() and replaced it with CURDATE()
	// 16-Apr-2017 Modified to get the season name to show in front of the scheduled game
        $db->Query("
            SELECT
                sch.*,
                DATE_FORMAT(sch.date, '%b %e') as formatted_date,
                Date_Format(time,'%h %p') AS thetime,
                grn.GroundName as ground, ss.SeasonName as SeasonName
            FROM
                schedule sch, grounds grn, seasons ss
            WHERE
                sch.venue = grn.GroundID AND
                sch.date >= CURDATE() AND
                sch.date <= DATE_ADD(NOW(), INTERVAL 8 DAY) AND
                ss.SeasonID = sch.season
            ORDER BY
                sch.date, sch.id
            ");

        if (!$db->rows) {

        	echo "<p>No Games next week.</p>\n";

        } else {

        echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\" class=\"tablehead\">\n";

        	for ($x = 0; $x < $db->rows; $x++) {
                $db->GetRow($x);
                $t1 = htmlentities(stripslashes($teams[$db->data['hometeam']]));
                $t2 = htmlentities(stripslashes($teams[$db->data['awayteam']]));
                $da  = $db->data['formatted_date'];
                // 2017-04-21 - Bodha - Added shortened time with date
                $ti  = $db->data['thetime'];
                $we  = $db->data['week'];
                $se  = $db->data['season'];
                $l1  = $db->data['hometeam'];
                $l2  = $db->data['awayteam'];
		// 16-Apr-2017 Modified to get the season name to show in front of the scheduled game
		$ss = explode(" ", $db->data[SeasonName]);
		$ss1 = substr($ss[0],4);
		// 2017-04-21 - Bodha - Shortened Premier to P40 and Twenty20 to T20
                if($ss[1] == 'Premier') {
			$ss_final_text = "P40 - ";
		} else {
			$ss_final_text = "T20 - ";
		}
		// $ss_final_text = $ss[1]. " - ";

				if($x % 2) {
				  echo "<tr class=\"trrow2\">\n";
				} else {
				  echo "<tr class=\"trrow1\">\n";
				}

                echo "  <td align=\"left\"><a href=\"/schedule.php?schedule=$se&week=$we&ccl_mode=3\" class=\"right\">$da $ti</a></td>\n";
                echo "  <td align=\"left\">$ss_final_text<a href=\"/schedule.php?schedule=$se&team=$l2&ccl_mode=2\" class=\"right\">$t2</a> at <a href=\"/schedule.php?schedule=$se&team=$l1&ccl_mode=2\" class=\"right\">$t1</a></td>\n";
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
