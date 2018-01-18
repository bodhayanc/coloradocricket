<?php


//------------------------------------------------------------------------------
// Print Schedule v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_schedule_listing($db,$schedule,$id,$pr,$team,$week)
{
    global $PHP_SELF;

    if ($db->Exists("SELECT * FROM seasons")) {
        $db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &gt; Schedule</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        echo "    <p>Please select a schedule you wish to view:</p>\n";
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
            echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

        } else {

            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";
            echo "    <p>There are no schedules in the database.</p>\n";
            echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
    }
}


function show_schedule($db,$schedule,$id,$pr,$team,$week)
{
        global $content,$action,$SID,$USER;

        if (!$db->Exists("SELECT * FROM schedule")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &gt; Schedule</p>\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

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
                }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";



            echo "<p class=\"16px\">{$seasons[$schedule]} Schedule</p>\n";
            echo "<p>Please click on a location name to get directions to the game.</p>\n";

            echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td width=\"50%\" valign=\"top\">\n";

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;Team Specific Schedules</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td>\n";

            if ($db->Exists("SELECT * FROM teams")) {
            $db->QueryRow("SELECT * FROM teams ORDER BY TeamName");
                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
                echo "    <option>select a team</option>\n";
                echo "    <option>=============</option>\n";
                for ($x=0; $x<$db->rows; $x++) {
                    $db->GetRow($x);
                    $db->BagAndTag();
                    $id = $db->data[TeamID];
                    // output article

                echo "    <option value=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">" . $db->data['TeamAbbrev'] . "</option>\n";
                }
        }
                echo "    </select></p>\n";
                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";

                echo "</td>\n";
                echo "</tr>\n";
                echo "</table>\n";

            echo "</td>\n";
            echo "<td width=\"50%\" valign=\"top\">\n";

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;Week Specific Schedules</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td>\n";

            if ($db->Exists("SELECT * FROM schedule")) {

            $db->Query("
                SELECT
                    *
                FROM
                    schedule
                WHERE
                    season = $schedule
                GROUP BY
                    week
                ");

                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
                echo "    <option>select a week</option>\n";
                echo "    <option>=============</option>\n";
                for ($x=0; $x<$db->rows; $x++) {
                    $db->GetRow($x);
                    $db->BagAndTag();
                    $week = $db->data[week];
                    $da  = $db->data['formatted_date'];
                    // output article

                echo "    <option value=\"$PHP_SELF?schedule=$schedule&week=$week&ccl_mode=3\">Week " . $db->data[week] . " ($da)</option>\n";
                }
        }
                echo "    </select></p>\n";
                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table><br>\n";

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#9E3228\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"#9E3228\" class=\"whitemain\" height=\"23\">&nbsp;League Schedule</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("SELECT sch.*,grn.GroundName as ground FROM schedule sch inner join grounds grn on sch.venue = grn.GroundID WHERE season=$schedule ORDER BY id");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = htmlentities(stripslashes($teams[$db->data[hometeam]]));
                $t2 = htmlentities(stripslashes($teams[$db->data[awayteam]]));
                $um = htmlentities(stripslashes($teams[$db->data[umpires]]));
                $t = htmlentities(stripslashes($db->data[TeamName]));
                $d = sqldate_to_string($db->data[date]);
                $v = htmlentities(stripslashes($db->data[ground]));
                $vl = htmlentities(stripslashes($db->data[venue]));

            if($x % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }
                echo "  <td align=\"left\">$d</td>\n";
                echo "  <td align=\"left\">$um</td>\n";
                echo "  <td align=\"left\">$t2 @ $t1</td>\n";
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
            $db->QueryRow("SELECT * FROM seasons ORDER BY SeasonID DESC");

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;Season Selection</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

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

            echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

        }
}


function show_schedule_team($db,$schedule,$id,$pr,$team,$week)
{
        global $content,$action,$SID,$USER;

        if (!$db->Exists("SELECT * FROM schedule")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &gt; Schedule</p>\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

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
                        $teamname[$db->data[TeamID]] = $db->data[TeamName];
                        $teamcolour[$db->data[TeamID]] = $db->data[TeamColour];
                        $teamaway = $teams;
                        $teamhome = $teams;
                }

            echo "<!-- Fetch Here -->\n";

            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";


            echo "<p class=\"16px\">{$seasons[$schedule]} Schedule for {$teams[$team]}</p>\n";
            echo "<p>Please click on a location name to get directions to the game.</p>\n";

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;Schedule - {$teamname[$team]}</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("SELECT sch.*,grn.GroundName as ground FROM schedule sch inner join grounds grn on sch.venue = grn.GroundID WHERE season=$schedule AND (awayteam=$team OR hometeam=$team) ORDER BY id");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = htmlentities(stripslashes($teams[$db->data[hometeam]]));
                $t2 = htmlentities(stripslashes($teams[$db->data[awayteam]]));
                $um = htmlentities(stripslashes($teams[$db->data[umpires]]));
                $t = htmlentities(stripslashes($db->data[TeamName]));
                $d = sqldate_to_string($db->data[date]);
                $v = htmlentities(stripslashes($db->data[ground]));
                $vl = htmlentities(stripslashes($db->data[venue]));
    if($x % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }
                echo "  <td align=\"left\">$d</td>\n";
                echo "  <td align=\"left\">$um</td>\n";
                echo "  <td align=\"left\">$t2 @ $t1</td>\n";
                echo "  <td align=\"left\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
                echo "</tr>\n";
                }
        }
                            echo "</table>\n";

                            echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";



                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";


                    echo "    </td>\n";
                    echo "  </tr>\n";
                    echo "</table><br>\n";
                    
        echo "<!-- End Here -->\n";

        }
}


function show_schedule_week($db,$schedule,$id,$pr,$team,$week)
{
        global $content,$action,$SID,$USER;

        if (!$db->Exists("SELECT * FROM schedule")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <p>There are currently no scheduled games in the database.</p>\n";
        echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

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
                        $teamname[$db->data[TeamID]] = $db->data[TeamName];
                        $teamcolour[$db->data[TeamID]] = $db->data[TeamColour];
                        $teamaway = $teams;
                        $teamhome = $teams;
                }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";



            echo "<p class=\"16px\">{$seasons[$schedule]} Schedule for Week {$weeks[$week]}</p>\n";
            echo "<p>Please click on a location name to get directions to the game.</p>\n";

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#9E3228\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"#9E3228\" class=\"whitemain\" height=\"23\">&nbsp;Schedule - Week {$weeks[$week]}</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        if (!$db->Exists("SELECT * FROM schedule WHERE season=$schedule")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No games.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("SELECT sch.*,grn.GroundName as ground FROM schedule sch inner join grounds grn on sch.venue = grn.GroundID WHERE season=$schedule and week=$week ORDER BY id");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = htmlentities(stripslashes($teams[$db->data[hometeam]]));
                $t2 = htmlentities(stripslashes($teams[$db->data[awayteam]]));
                $um = htmlentities(stripslashes($teams[$db->data[umpires]]));
                $t = htmlentities(stripslashes($db->data[TeamName]));
                $d = sqldate_to_string($db->data[date]);
                $v = htmlentities(stripslashes($db->data[ground]));
                $vl = htmlentities(stripslashes($db->data[venue]));
    if($x % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }
                echo "  <td align=\"left\">$d</td>\n";
                echo "  <td align=\"left\">$um</td>\n";
                echo "  <td align=\"left\">$t2 @ $t1</td>\n";
                echo "  <td align=\"left\"><a href=\"/grounds.php?grounds=$vl&ccl_mode=1\">$v</a></td>\n";
                echo "</tr>\n";
                }
        }
                            echo "</table>\n";

                            echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table><br>\n";


            echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td width=\"50%\" valign=\"top\">\n";

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;Team Specific Schedules</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td>\n";

            if ($db->Exists("SELECT * FROM teams")) {
            $db->QueryRow("SELECT * FROM teams ORDER BY TeamName");
                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
                echo "    <option>select a team</option>\n";
                echo "    <option>=============</option>\n";
                for ($x=0; $x<$db->rows; $x++) {
                    $db->GetRow($x);
                    $db->BagAndTag();
                    $id = $db->data[TeamID];
                    // output article

                echo "    <option value=\"$PHP_SELF?schedule=$schedule&team=$id&ccl_mode=2\">" . $db->data['TeamAbbrev'] . "</option>\n";
                }
        }
                echo "    </select></p>\n";
                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";

                echo "</td>\n";
                echo "</tr>\n";
                echo "</table>\n";

            echo "</td>\n";
            echo "<td width=\"50%\" valign=\"top\">\n";

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;Week Specific Schedules</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td>\n";

            if ($db->Exists("SELECT * FROM schedule")) {
            $db->QueryRow("SELECT * FROM schedule WHERE season=$schedule GROUP BY week");
                echo "    <p>&nbsp;&nbsp;<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
                echo "    <option>select a week</option>\n";
                echo "    <option>=============</option>\n";
                for ($x=0; $x<$db->rows; $x++) {
                    $db->GetRow($x);
                    $db->BagAndTag();
                    $week = $db->data[week];
                    // output article

                echo "    <option value=\"$PHP_SELF?schedule=$schedule&week=$week&ccl_mode=3\">Week " . $db->data[week] . "</option>\n";
                }
        }
                echo "    </select></p>\n";
                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table><br>\n";


            // begin season selection

            if ($db->Exists("SELECT * FROM seasons")) {
            $db->QueryRow("SELECT * FROM seasons ORDER BY SeasonID DESC");

                    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#{$teamcolour[$team]}\" align=\"center\">\n";
                    echo "  <tr>\n";
                    echo "    <td bgcolor=\"#{$teamcolour[$team]}\" class=\"whitemain\" height=\"23\">&nbsp;Season Selection</td>\n";
                    echo "  </tr>\n";
                    echo "  <tr>\n";
                echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

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

                echo "<option value=\"$PHP_SELF?schedule=$id&team=$team&ccl_mode=2\">" . $db->data['SeasonName'] . "</option>\n";
                }
        }
                echo "    </select></p>\n";
                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";

                echo "    </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";


                    echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
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
