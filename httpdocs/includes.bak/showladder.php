<?php

//------------------------------------------------------------------------------
// Standings v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_ladder_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT la.season, se.SeasonName FROM ladder la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC")) {
        $db->QueryRow("SELECT la.season, se.SeasonName FROM ladder la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Standings</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Standings</b><br><br>\n";


        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEASON SELECTION</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        echo "<p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "  <option>select a season</option>\n";
        echo "  <option>=====================</option>\n";
        for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);
        $db->BagAndTag();

        $sen = $db->data[SeasonName];
        $sid = $db->data[season];
        
        echo "    <option value=\"$PHP_SELF?ladder=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";


        }
        echo "</select></p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        } else {

            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "  <tr>\n";
            echo "    <td align=\"left\" valign=\"top\">\n";
            echo "    <p>There are no ladders in the database.</p>\n";
            echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
    }
}


function show_ladder($db,$s,$id,$pr,$ladder)
{
    global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
    
        if (!$db->Exists("SELECT * FROM ladder")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &gt; <font class=\"10px\">Standings</font></p>\n";
        echo "    <p>There are currently no ladder games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        } else {

                $db->Query("SELECT se.* FROM seasons se INNER JOIN ladder la ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY se.SeasonID ORDER BY se.SeasonName DESC");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
                }


            echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";

            echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
            echo "<tr>\n";
            echo "  <td align=\"left\" valign=\"top\">\n";
            echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/ladder.php\">Standings</a> &raquo; <font class=\"10px\">{$seasons[$ladder]}</font></p>\n";
            echo "  </td>\n";
            //echo "  <td align=\"right\" valign=\"top\">\n";
            //require ("navtop.php");
            //echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


            echo "<b class=\"16px\">{$seasons[$ladder]} Standings</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        
        // List by season for schedule

        echo "<p class=\"10px\">Season: ";
        echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "<option value=\"\" selected>year</option>\n";

        $db->Query("SELECT la.season, se.SeasonName FROM ladder la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            $db->BagAndTag();
            $sen = $db->data[SeasonName];
            $sid = $db->data[season];
        
        echo "    <option value=\"$PHP_SELF?ladder=$sid&ccl_mode=1\" class=\"10px\">$sen</option>\n";
        
        }
        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // League Standings
    //////////////////////////////////////////////////////////////////////////////////////////
    
        
            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE STANDINGS</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

        if (!$db->Exists("
                SELECT
                  lad.* , tm.TeamAbbrev AS team
                FROM
                  ladder lad
                INNER JOIN
                  teams tm ON lad.team = tm.TeamID
                WHERE
                    season=$ladder AND tm.LeagueID = 1 ORDER BY won DESC, lost ASC
        ")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded in {$seasons[$ladder]}.</p></td>\n";
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
                    season=$ladder AND tm.LeagueID = 1 ORDER BY won DESC, lost ASC
            ");

                echo "<tr class=\"colhead\">\n";
                echo "  <td align=\"left\"><b>TEAM</b></td>\n";
                echo "  <td align=\"center\"><b>Pl</b></td>\n";
                echo "  <td align=\"center\"><b>W</b></td>\n";
                echo "  <td align=\"center\"><b>T</b></td>\n";
                echo "  <td align=\"center\"><b>L</b></td>\n";
                echo "  <td align=\"center\"><b>NR</b></td>\n";
                echo "  <td align=\"center\"><b>Pt</b></td>\n";
                echo "  <td align=\"center\"><b>Pen</b></td>\n";
                echo "  <td align=\"center\"><b>Total</b></td>\n";
                //echo "    <td align=\"center\"><b>Avg</b></td>\n";
                echo "</tr>\n";

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);

                    $tid = $db->data[tid];
                    $te = htmlentities(stripslashes($db->data[teamname]));
                    $pl = htmlentities(stripslashes($db->data[played]));
                    $wo = htmlentities(stripslashes($db->data[won]));
                    $lo = htmlentities(stripslashes($db->data[lost]));
                    $ti = htmlentities(stripslashes($db->data[tied]));
                    $nr = htmlentities(stripslashes($db->data[nrr]));
                    $pt = htmlentities(stripslashes($db->data[points]));
                    $pe = htmlentities(stripslashes($db->data[penalty]));
                    $tp = htmlentities(stripslashes($db->data[totalpoints]));
                                        if ($pl == 0){
                                        $av = 0;
                                        }else {
                                        $av = Round($tp / $pl,2);
                                        }

            if($x % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

                    echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
                    echo "  <td align=\"center\">$pl</td>\n";
                    echo "  <td align=\"center\">$wo</td>\n";
                    echo "  <td align=\"center\">$ti</td>\n";
                    echo "  <td align=\"center\">$lo</td>\n";
                    echo "  <td align=\"center\">$nr</td>\n";
                    echo "  <td align=\"center\">$pt</td>\n";
                    echo "  <td align=\"center\">$pe</td>\n";
                    echo "  <td align=\"center\">$tp</td>\n";
                    //echo "    <td align=\"center\">$av</td>\n";
                    echo "</tr>\n";

                }
        }
                        echo "</table>\n";

                        echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Detailed Record
    //////////////////////////////////////////////////////////////////////////////////////////

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;TEAM FORM</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\"><b>TEAM</b></td>\n";
            echo "  <td align=\"center\"><b>Streak</b></td>\n";
            echo "  <td align=\"left\"><b>Form</b></td>\n";
            echo "  <td align=\"center\"><b>Home</b></td>\n";
            echo "  <td align=\"center\"><b>Away</b></td>\n";
            echo "</tr>\n";


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE season=$ladder")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\" colspan=\"4\"><p>No games recorded in {$seasons[$ladder]}.</p></td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("SELECT te.TeamID, te.TeamAbbrev FROM teams te INNER JOIN scorecard_batting_details ba ON te.TeamID=ba.team WHERE ba.season=$ladder AND te.LeagueID = 1 GROUP BY TeamAbbrev");
            $db->BagAndTag();

        // instantiate new db class
            $subdb =& new mysql_class($dbcfg[login],$dbcfg[pword],$dbcfg[server]);
            $subdb->SelectDB($dbcfg[db]);

            for ($r=0; $r<$db->rows; $r++) {
            $db->GetRow($r);

            $tid = $db->data[TeamID];
            $te  = $db->data[TeamAbbrev];
            
        // Get Home Games
            if (!$subdb->Exists("SELECT hometeam, count(hometeam) AS Homegames FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam")) {
              $homegames = "0";
            } else {
            $subdb->QueryRow("SELECT hometeam, count(hometeam) AS Homegames FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam");     
              $homegames = $subdb->data[Homegames];
            }

        // Get Home Ties
            if (!$subdb->Exists("SELECT hometeam, count(hometeam) AS Hometies FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY hometeam")) {
              $hometies = "0";
            } else {
            $subdb->QueryRow("SELECT hometeam, count(hometeam) AS Hometies FROM scorecard_game_details WHERE hometeam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY hometeam");      
              $hometies = $subdb->data[Hometies];
            }

                        
        // Get Home Wins            
            if (!$subdb->Exists("SELECT hometeam, count(result_won_id) AS Homewins FROM scorecard_game_details WHERE hometeam=result_won_id AND hometeam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam")) {
              $homewins = "0";
            } else {
            $subdb->QueryRow("SELECT hometeam, count(result_won_id) AS Homewins FROM scorecard_game_details WHERE hometeam=result_won_id AND hometeam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY hometeam");      
              $homewins = $subdb->data[Homewins];
            }

        // Get Home Losses          
            $homelosses = $homegames - $homewins - $hometies;           
            

        // Get Away Games           
            if (!$subdb->Exists("SELECT awayteam, count(awayteam) AS Awaygames FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam")) {
              $awaygames = "0";
            } else {
            $subdb->QueryRow("SELECT awayteam, count(awayteam) AS Awaygames FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam");     
              $awaygames = $subdb->data[Awaygames];
            }

        // Get Away Ties
            if (!$subdb->Exists("SELECT awayteam, count(awayteam) AS Awayties FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY awayteam")) {
              $awayties = "0";
            } else {
            $subdb->QueryRow("SELECT awayteam, count(awayteam) AS Awayties FROM scorecard_game_details WHERE awayteam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 AND result_won_id=0 GROUP BY awayteam");      
              $awayties = $subdb->data[Awayties];
            }

        // Get Away Wins            
            if (!$subdb->Exists("SELECT awayteam, count(result_won_id) AS Awaywins FROM scorecard_game_details WHERE awayteam=result_won_id AND awayteam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam")) {
              $awaywins = "0";
            } else {
            $subdb->QueryRow("SELECT awayteam, count(result_won_id) AS Awaywins FROM scorecard_game_details WHERE awayteam=result_won_id AND awayteam=$tid AND season=$ladder AND cancelledplay=0 AND cancelled=0 GROUP BY awayteam");      
              $awaywins = $subdb->data[Awaywins];
            }

        // Get Away Losses          
            $awaylosses = $awaygames - $awaywins - $awayties;   



            $sql = "SELECT
               game_id,game_date,hometeam,awayteam,result_won_id FROM
               scorecard_game_details WHERE season=$ladder AND cancelledplay=0 AND
               cancelled=0 AND (hometeam=$tid OR awayteam=$tid) GROUP BY game_id
               ORDER BY game_date DESC";

            $streak  = 0;
            $winning = null;
            $history = '';
            $count   = true;

            define('WIN',  1);
            define('LOSE', 2);
            define('TIE',  3);

            $subdb->Query($sql);
            for ($s = 0; $s < $subdb->rows; $s++) {
               $subdb->GetRow($s);
               $type = (!$subdb->data['result_won_id']) ? TIE:($subdb->data['result_won_id'] == $tid) ? WIN : LOSE;

        // Get streak
               if ($winning !== null && $type !== $winning && $count) {
                   $count = false;
               }
               if ($winning === null) {
                   $winning = $type;
               }
               if ($count) {
                   ++$streak;
               }

        // Get form
               $history .= ($type == TIE) ? 'T' : (($type == WIN) ? 'W' : 'L');
            }

            $plural  = ($streak == 1) ? '' : ($winning == LOSE ? 'es' : 's');
            $streak  = (string)$streak . (($winning == TIE) ? " tie$plural" :($winning ==WIN ? " win$plural" : " loss$plural"));
            $history = strrev($history);


            echo '<tr class="trrow', ($r % 2 ? '2' : '1'), '">';

            echo "  <td align=\"left\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
            echo "  <td align=\"center\">$streak</td>\n";
            echo "  <td align=\"left\" class=\"standings\">$history</td>\n";
            echo "  <td align=\"center\">$homewins-$hometies-$homelosses</td>\n";
            echo "  <td align=\"center\">$awaywins-$awayties-$awaylosses</td>\n";
            echo "</tr>\n";

            }
        }
                echo "</table>\n";

                echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
            

    //////////////////////////////////////////////////////////////////////////////////////////
    // Points Table Legend
    //////////////////////////////////////////////////////////////////////////////////////////

            //echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            //echo "  <tr>\n";
            //echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;LEGEND - 100 POINTS PER GAME</td>\n";
            //echo "  </tr>\n";
            //echo "  <tr>\n";
        //echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        //echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">40 Result Points</td><td width=\"70%\">40 points to winning team, 0 points to losing team.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">20 Batting Points<br><i>(10 per inning)</i></td><td width=\"70%\">1 point for every 15 runs or 10% of opponent’s score whichever is higher.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">20 Bowling Points<br><i>(10 per inning)</i></td><td width=\"70%\">1 point for every wicket.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">20 Margin Points</td><td width=\"70%\">If team batting first wins:<br>2 points for every 10% victory margin.<br><br>If team batting second wins:<br>2 points for every 10% overs to spare.<br><br>Left over Batting, Bowling and Margin points go to the opponent.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">Washed Out Games</td><td width=\"70%\">20 points per team. Game must be replayed.</td>\n";
        //echo "  </tr>\n";
        //echo "  <tr class=\"trrow1\">\n";
        //echo "    <td width=\"30%\">Forfeited Games</td><td width=\"70%\">80 points to winning team.</td>\n";
        //echo "  </tr>\n";         
        //echo "</table>\n";

        //echo "  </td>\n";
        //echo "</tr>\n";
        //echo "</table>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        }
}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg[login],$dbcfg[pword],$dbcfg[server]);
$db->SelectDB($dbcfg[db]);



switch($ccl_mode) {
case 0:
    show_ladder_listing($db,$s,$id,$pr);
    break;
case 1:
    show_ladder($db,$s,$id,$pr,$ladder);
    break;
default:
    show_ladder_listing($db,$s,$id,$pr);
    break;
}

?>
