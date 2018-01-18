<?php

//------------------------------------------------------------------------------
// Points Table v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_ladder_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM seasons")) {
        $db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; Points Table</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("includes/navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Points Table</b><br><br>\n";


        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Season Selection</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        echo "<p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "  <option>select a season</option>\n";
        echo "  <option>=====================</option>\n";
        for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);
        $db->BagAndTag();
        $tempid = $db->data['SeasonID'];
        // output article

        echo "<option value=\"$PHP_SELF?ladder=$id&ccl_mode=1\"" . (($tempid == $id) ? " selected" : "") . ">" . $db->data['SeasonName'] . "</option>\n";
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
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM tennisgroups ORDER BY GroupName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $groups[$db->data[GroupID]] = $db->data[GroupName];
    }
    
        if (!$db->Exists("SELECT * FROM tennis_ladder")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &gt; <a href=\"/ladder.php\">Points Table</a></p>\n";
        echo "    <p>There are currently no ladder games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        } else {

                $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/ladder.php\">Points Table</a> &raquo; {$seasons[$ladder]}</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("includes/navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">{$seasons[$ladder]} Points Table</b><br><br>\n";

// begin season selection

            if ($db->Exists("SELECT * FROM seasons")) {
            $db->QueryRow("SELECT * FROM seasons  WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Options</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

            echo "  <tr>\n";
            echo "    <td>\n";

            echo "<p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
            echo "  <option>year</option>\n";
            echo "  <option>====</option>\n";
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data['SeasonID'];
                // output article

            echo "<option value=\"$PHP_SELF?ladder=$id&ccl_mode=1\">" . $db->data['SeasonName'] . "</option>\n";
            }
        }
            echo "</select></p>\n";
            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table><br>\n";
            
            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
            
            for ($i=1; $i<=count($groups); $i++) {
            
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\" colspan=\"8\">" . htmlentities(stripslashes($groups[$i])) . " Points Table</td>\n";
            echo "  </tr>\n";
            
            echo "  <tr>\n";
        echo "    <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"8\">\n";
        echo "    <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

        if (!$db->Exists("
                SELECT
                  lad.* , tm.TeamAbbrev AS team
                FROM
                  tennis_ladder lad
                INNER JOIN
                  tennisteams tm ON lad.team = tm.TeamID
                WHERE
                    season=$ladder ORDER BY points DESC,netrunrate DESC
        ")) {
        echo "  <tr class=\"trrow2\">\n";
        echo "    <td align=\"left\" colspan=\"8\"><p>No games recorded in {$seasons[$ladder]}.</p></td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
        } else {
        
        echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
        
            $db->Query("
                    SELECT
                  lad. * , tm.TeamAbbrev AS teamname, tm.TeamID as tid
                FROM
                  tennis_ladder lad
                INNER JOIN
                  tennisteams tm ON lad.team = tm.TeamID
                WHERE
                  tm.TeamGroup=7 AND season=$ladder ORDER BY points DESC,netrunrate DESC
            ");

                echo "<tr class=\"trrow2\">\n";
                echo "  <td align=\"left\" width=\"30%\"><b>Team</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>Pl</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>W</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>T</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>L</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>NR</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>Pt</b></td>\n";
                echo "  <td align=\"center\" width=\"10%\"><b>NRR</b></td>\n";
                echo "</tr>\n";

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
                $pe = htmlentities(stripslashes($db->data[netrunrate]));
                $tp = htmlentities(stripslashes($db->data[totalpoints]));

            
            echo '<tr class="trrow', ($x % 2 ? '2' : '1'), '">';
            echo "  <td align=\"left\" width=\"30%\"><a href=\"/teams.php?teams=$tid&ccl_mode=1\">$te</a></td>\n";
            echo "  <td align=\"center\" width=\"10%\">$pl</td>\n";
            echo "  <td align=\"center\" width=\"10%\">$wo</td>\n";
            echo "  <td align=\"center\" width=\"10%\">$ti</td>\n";
            echo "  <td align=\"center\" width=\"10%\">$lo</td>\n";
            echo "  <td align=\"center\" width=\"10%\">$nr</td>\n";
            echo "  <td align=\"center\" width=\"10%\">$pt</td>\n";
            echo "  <td align=\"center\" width=\"10%\">$pe</td>\n";
            echo "</tr>\n";

                }
                
            echo "</table>\n";
        }
                       // echo "</table>\n";

                        echo "    </td>\n";
            echo "  </tr>\n";
        }
            echo "</table><br>\n";

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Points Table Legend</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

            echo "  <tr class=\"trrow1\">\n";
            echo "    <td>Win</td><td>2 points</td>\n";
            echo "  </tr>\n";
            echo "  <tr class=\"trrow2\">\n";
            echo "    <td>Tied/Rain/Cancelled</td><td>1 points</td>\n";
            echo "  </tr>\n";
            echo "  <tr class=\"trrow1\">\n";
            echo "    <td>Loss</td><td>0 points</td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";

        }
}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



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
