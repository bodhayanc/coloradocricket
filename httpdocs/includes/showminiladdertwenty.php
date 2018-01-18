<?php


function show_miniladdertwenty($db,$s,$id,$pr,$ladder)
{
	global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM ladder")) {

	echo "<p>There are currently no games in the database.</p>\n";

        } else {

//                $db->Query("SELECT * FROM seasons WHERE SeasonName LIKE '%2011 Twenty%' ORDER BY SeasonName DESC");
                   $db->Query("SELECT * FROM seasons WHERE SeasonName LIKE '%2017 Twenty%' ORDER BY SeasonName DESC");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

		echo "  <table border-right=\"1\" width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\" bordercolor=\"#DE9C06\">\n";

			echo "<tr class=\"colhead\">\n";
			echo "	<td align=\"left\"><b>Team</b></td>\n";
			echo "	<td align=\"center\"><b>P</b></td>\n";
			echo "	<td align=\"center\"><b>W</b></td>\n";
			echo "	<td align=\"center\"><b>T</b></td>\n";
			echo "	<td align=\"center\"><b>L</b></td>\n";
			echo "	<td align=\"center\"><b>NR</b></td>\n";
			echo "	<td align=\"center\"><b>Pt</b></td>\n";
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
				WHERE
					season=73 ORDER BY lad.rank_sort ASC
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
					season=73 ORDER BY lad.rank_sort ASC
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
					$pe = htmlentities(stripslashes($db->data[penalty]));
					$tp = htmlentities(stripslashes($db->data[totalpoints]));

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
					echo "</tr>\n";


				}
		}
                        echo "</table>\n";

        }
}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_miniladdertwenty($db,$s,$id,$pr,$ladder);


?>
