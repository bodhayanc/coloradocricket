<?php

//------------------------------------------------------------------------------
// Schedule v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_schedule_listing($db,$schedule,$id,$pr,$team,$week)
{
	global $PHP_SELF;

	if ($db->Exists("SELECT * FROM seasons")) {
		$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");


		echo "<b class=\"16px\">Schedules</b><br><br>\n";

		// Schedule Select Box

		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#014B01\" align=\"center\">\n";
		echo "  <tr>\n";
		echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">Select a season schedule</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

       		echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
		echo "    <option>select a season</option>\n";
		echo "    <option>===============</option>\n";

		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();

			// output
			$id = $db->data['SeasonID'];

			echo "    <option value=\"$PHP_SELF?schedule=$id&ccl_mode=1\">" . $db->data['SeasonName'] . " season</option>\n";

		}

		echo "    </select></p>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";

		} else {

			echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
			echo "  <tr>\n";
			echo "    <td align=\"left\" valign=\"top\">\n";
			echo "    <p>There are no schedules in the database.</p>\n";
			echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
			echo "    </td>\n";
			echo "  </tr>\n";
			echo "</table>\n";
	}
}



function show_schedule($db,$schedule,$id,$pr,$team,$week)
{
        global $PHP_SELF;

        if (!$db->Exists("SELECT * FROM schedule ORDER BY week")) {

	 	echo "    <p>There are currently no scheduled games in the database.</p>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['teamname'];
                }





			echo "<b class=\"16px\">{$seasons[$schedule]} Schedule</b><br><br>\n";

		    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
		    	echo "  <tr>\n";
		    	echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;Select your schedule option</td>\n";
        		echo "  </tr>\n";
        		echo "  <tr>\n";
			echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";

			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Team:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM teams")) {
			$db->QueryRow("SELECT * FROM teams ORDER BY TeamName");
                	echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "    <option>select your team</option>\n";
				echo "    <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$id = $db->data[TeamID];
					$ab = $db->data['teamname'];
					// output article

				echo "    <option value=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">" . $db->data['teamname'] . "</option>\n";
				//echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";

			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Week:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM schedule")) {
			$db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY week");

                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "    <option>select a week</option>\n";
				echo "    <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$week = $db->data[week];
					$da  = $db->data['formatted_date'];
					// output article

				echo "    <option value=\"$PHP_SELF?schedule=$schedule&week=$week&ccl_mode=3\">Week " . $db->data[week] . "</option>\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";

				echo "</table>\n";

				echo "</td>\n";
				echo "</tr>\n";
				echo "</table><br>\n";

		        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
		        echo "  <tr>\n";
		        echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;League Schedule</td>\n";
        		echo "  </tr>\n";
        		echo "  <tr>\n";
			echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
		if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
			echo "<tr class=\"trrow2\">\n";
			echo "  <td align=\"left\"<p>No games.</p></td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
		} else {
			$db->Query("
			SELECT
			  sch.*,te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev,t2.TeamID AS umpireid,t2.TeamAbbrev AS umpireabbrev, grn.GroundName AS ground
			FROM
			  ((((schedule sch
			INNER JOIN
			  grounds grn
			ON
			  sch.venue = grn.GroundID)
			INNER JOIN
			  teams te
			ON
			  sch.awayteam = te.TeamID)
			INNER JOIN
			  teams t1
			ON
			  sch.hometeam = t1.TeamID)
			INNER JOIN
			  teams t2
			ON
			  sch.umpires = t2.TeamID)
			WHERE
			  sch.season=$schedule
			ORDER BY
			  sch.week, sch.date");

			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);
				$t1 = $db->data[homeabbrev];
				$t2 = $db->data[awayabbrev];
				$um = $db->data[umpireabbrev];
				$t1id = $db->data[homeid];
				$t2id = $db->data[awayid];
				$umid = $db->data[umpireid];
				$t = htmlentities(stripslashes($db->data['teamname']));
				$d = sqldate_to_string($db->data[date]);
				$v = htmlentities(stripslashes($db->data[ground]));
				$vl = htmlentities(stripslashes($db->data[venue]));

			if($x % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}
				echo "  <td align=\"left\" class=\"12px\">$d</td>\n";
				echo "  <td align=\"left\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> @ <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
				echo "  <td align=\"left\" class=\"12px\">$um Umpiring</td>\n";
				echo "  <td align=\"left\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
				echo "</tr>\n";
				}
		}
                        echo "</table>\n";

                        echo "    </td>\n";
			echo "  </tr>\n";
			echo "</table><br>\n";

// begin season selection

			if ($db->Exists("SELECT * FROM seasons")) {
			$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");

 		        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
 		        echo "  <tr>\n";
 		        echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;Season Selection</td>\n";
         		echo "  </tr>\n";
         		echo "  <tr>\n";
 			echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

 			echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";
 			echo "  <tr>\n";
			echo "    <td>\n";

 			echo "<p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
			echo "  <option>select another season</option>\n";
			echo "  <option>=====================</option>\n";
			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);
				$db->BagAndTag();
				$id = $db->data['SeasonID'];
				// output article

			echo "<option value=\"$PHP_SELF?schedule=$id&ccl_mode=1\">" . $db->data['SeasonName'] . "</option>\n";
			}
		}
			echo "</select></p>\n";
			echo "  </td>\n";
			echo "</tr>\n";
			echo "</table>\n";

			echo "  </td>\n";
			echo "</tr>\n";
			echo "</table>\n";

			echo "    </td>\n";
			echo "  </tr>\n";
			echo "</table>\n";

        }
}

function show_schedule_team($db,$schedule,$id,$pr,$team,$week)
{
        global $PHP_SELF;

        if (!$db->Exists("SELECT * FROM schedule")) {

	 	echo "    <p>There are currently no scheduled games in the database.</p>\n";


                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
                        $teamname[$db->data[TeamID]] = $db->data['teamname'];
                        $teamcolour[$db->data[TeamID]] = $db->data[TeamColour];
                        $teamaway = $teams;
                        $teamhome = $teams;
                }



			echo "<b class=\"16px\">{$seasons[$schedule]} Schedule for {$teams[$team]}</b><br><br>\n";


		    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
		    echo "  <tr>\n";
		    echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;Schedule - {$teamname[$team]}</td>\n";
        	echo "  </tr>\n";
        	echo "  <tr>\n";
			echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
		if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
			echo "<tr class=\"trrow2\">\n";
			echo "  <td align=\"left\"<p>No games.</p></td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
		} else {
			$db->Query("
			SELECT
			  sch.*,te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev,t2.TeamID AS umpireid,t2.TeamAbbrev AS umpireabbrev, grn.GroundName AS ground
			FROM
			  ((((schedule sch
			INNER JOIN
			  grounds grn
			ON
			  sch.venue = grn.GroundID)
			INNER JOIN
			  teams te
			ON
			  sch.awayteam = te.TeamID)
			INNER JOIN
			  teams t1
			ON
			  sch.hometeam = t1.TeamID)
			INNER JOIN
			  teams t2
			ON
			  sch.umpires = t2.TeamID)
			WHERE
			  sch.season=$schedule
			AND
			(awayteam=$team OR hometeam=$team)
			ORDER BY
			  sch.week, sch.date");

			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);
				$t1 = $db->data[homeabbrev];
				$t2 = $db->data[awayabbrev];
				$um = $db->data[umpireabbrev];
				$t1id = $db->data[homeid];
				$t2id = $db->data[awayid];
				$umid = $db->data[umpireid];
				$t = htmlentities(stripslashes($db->data['teamname']));
				$d = sqldate_to_string($db->data[date]);
				$v = htmlentities(stripslashes($db->data[ground]));
				$vl = htmlentities(stripslashes($db->data[venue]));

			if($x % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}
				echo "  <td align=\"left\" class=\"12px\">$d</td>\n";
				echo "  <td align=\"left\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> @ <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
				echo "  <td align=\"left\" class=\"12px\">$um Umpiring</td>\n";
				echo "  <td align=\"left\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
				echo "</tr>\n";
				}
		}
                        echo "</table>\n";

                echo "    </td>\n";
				echo "  </tr>\n";
				echo "</table><br>\n";


		    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
		    echo "  <tr>\n";
		    echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;Select your schedule option</td>\n";
        	echo "  </tr>\n";
        	echo "  <tr>\n";
			echo "  <td class=\"trrow2\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";

			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Team:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM teams")) {
			$db->QueryRow("SELECT * FROM teams ORDER BY TeamName");
                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "    <option>select your team</option>\n";
				echo "    <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$id = $db->data[TeamID];
					$ab = $db->data['teamname'];
					// output article

				echo "    <option value=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">" . $db->data['teamname'] . "</option>\n";
				//echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";

			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Week:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM schedule")) {
			$db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY week");

                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "    <option>select a week</option>\n";
				echo "    <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$week = $db->data[week];
					$da  = $db->data['formatted_date'];
					// output article

				echo "    <option value=\"$PHP_SELF?schedule=$schedule&week=$week&ccl_mode=3\">Week " . $db->data[week] . "</option>\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";


			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Season:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM seasons")) {
			$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");

				echo "<p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "  <option>select a season</option>\n";
				echo "  <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$id = $db->data['SeasonID'];
					// output article

				echo "<option value=\"$PHP_SELF?schedule=$id&team=$team&ccl_mode=2\">" . $db->data['SeasonName'] . "</option>\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";


				echo "</table>\n";

				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";


				echo "    </td>\n";
				echo "  </tr>\n";
				echo "</table>\n";

        }
}


function show_schedule_week($db,$schedule,$id,$pr,$team,$week)
{
        global $PHP_SELF;

        if (!$db->Exists("SELECT * FROM schedule")) {


	 	echo "    <p>There are currently no scheduled games in the database.</p>\n";


                return;

        } else {

                $db->Query("SELECT * FROM schedule ORDER BY id");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $weeks[$db->data[week]] = $db->data[week];
                }


                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
                        $teamname[$db->data[TeamID]] = $db->data['teamname'];
                        $teamcolour[$db->data[TeamID]] = $db->data[TeamColour];
                        $teamaway = $teams;
                        $teamhome = $teams;
                }



			echo "<b class=\"16px\">{$seasons[$schedule]} Schedule for Week {$weeks[$week]}</b><br><br>\n";

		        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
		        echo "  <tr>\n";
		        echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;Schedule - Week {$weeks[$week]}</td>\n";
        		echo "  </tr>\n";
        		echo "  <tr>\n";
			echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
		if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
			echo "<tr class=\"trrow2\">\n";
			echo "  <td align=\"left\"<p>No games.</p></td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "  <td align=\"left\">&nbsp;</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
		} else {
			$db->Query("
			SELECT
			  sch.*,te.TeamID AS awayid,te.TeamAbbrev AS awayabbrev,t1.TeamID AS homeid,t1.TeamAbbrev AS homeabbrev,t2.TeamID AS umpireid,t2.TeamAbbrev AS umpireabbrev, grn.GroundName AS ground
			FROM
			  ((((schedule sch
			INNER JOIN
			  grounds grn
			ON
			  sch.venue = grn.GroundID)
			INNER JOIN
			  teams te
			ON
			  sch.awayteam = te.TeamID)
			INNER JOIN
			  teams t1
			ON
			  sch.hometeam = t1.TeamID)
			INNER JOIN
			  teams t2
			ON
			  sch.umpires = t2.TeamID)
			WHERE
			  sch.season=$schedule
			AND
			  week=$week
			ORDER BY
			  sch.date");

			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);
				$t1 = $db->data[homeabbrev];
				$t2 = $db->data[awayabbrev];
				$um = $db->data[umpireabbrev];
				$t1id = $db->data[homeid];
				$t2id = $db->data[awayid];
				$umid = $db->data[umpireid];
				$t = htmlentities(stripslashes($db->data['teamname']));
				$d = sqldate_to_string($db->data[date]);
				$v = htmlentities(stripslashes($db->data[ground]));
				$vl = htmlentities(stripslashes($db->data[venue]));

			if($x % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}
				echo "  <td align=\"left\" class=\"12px\">$d</td>\n";
				echo "  <td align=\"left\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> @ <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
				echo "  <td align=\"left\" class=\"12px\">$um Umpiring</td>\n";
				echo "  <td align=\"left\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
				echo "</tr>\n";
				}
		}
                        echo "</table>\n";



                        	echo "    </td>\n";
				echo "  </tr>\n";
				echo "</table><br>\n";



		    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#014B01\" align=\"center\">\n";
		    echo "  <tr>\n";
		    echo "    <td bgcolor=\"#014B01\" class=\"whitemain\" height=\"23\">&nbsp;Select your schedule option</td>\n";
        	echo "  </tr>\n";
        	echo "  <tr>\n";
			echo "  <td class=\"trrow2\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";

			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Team:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM teams")) {
			$db->QueryRow("SELECT * FROM teams ORDER BY TeamName");
                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "    <option>select your team</option>\n";
				echo "    <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$id = $db->data[TeamID];
					$ab = $db->data['teamname'];
					// output article

				echo "    <option value=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">" . $db->data['teamname'] . "</option>\n";
				//echo "<a href=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">$ab</a> |\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";

			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Week:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM schedule")) {
			$db->Query("SELECT * FROM schedule WHERE season = $schedule GROUP BY week");

                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "    <option>select a week</option>\n";
				echo "    <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$week = $db->data[week];
					$da  = $db->data['formatted_date'];
					// output article

				echo "    <option value=\"$PHP_SELF?schedule=$schedule&week=$week&ccl_mode=3\">Week " . $db->data[week] . "</option>\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";


			echo "  <tr>\n";
			echo "    <td width=\"25%\">by Season:</td>\n";
			echo "    <td width=\"75%\">\n";

			if ($db->Exists("SELECT * FROM seasons")) {
			$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");

				echo "<p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
				echo "  <option>select a season</option>\n";
				echo "  <option>=============</option>\n";
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);
					$db->BagAndTag();
					$id = $db->data['SeasonID'];
					// output article

				echo "<option value=\"$PHP_SELF?schedule=$id&ccl_mode=1\">" . $db->data['SeasonName'] . "</option>\n";
				}
		}
				echo "    </select></p>\n";
				echo "    </td>\n";
				echo "  </tr>\n";


				echo "</table>\n";

				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";

					echo "    </td>\n";
					echo "  </tr>\n";
					echo "</table><br>\n";

        }
}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



switch($ccl_mode) {
case 0:
	show_schedule_listing($db,$schedule,$id,$pr,$team,$week);
	break;
case 1:
	show_schedule($db,$schedule,$id,$pr,$team,$week);
	break;
case 2:
	show_schedule_team($db,$schedule,$id,$pr,$team,$week);
	break;
case 3:
	show_schedule_week($db,$schedule,$id,$pr,$team,$week);
	break;
default:
	show_schedule_listing($db,$schedule,$id,$pr,$team,$week);
	break;
}

?>

