<?php

//------------------------------------------------------------------------------
// Team Administration v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new ladder record</a></p>\n";

    echo "<p>or, please select a season to work with.</p>\n";

    // check for empty database

    if (!$db->Exists("SELECT * FROM seasons ORDER BY SeasonName")) {
        echo "<p>There are currently no seasons in the database.</p>\n";
        return;
    } else {

        // output header, not to be included in for loop

        // Schedule Select Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Select a season schedule</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

            echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
        echo "    <option>select a season</option>\n";
        echo "    <option>===============</option>\n";

        $db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->BagAndTag();

            // output
            $id = $db->data['SeasonID'];
            $season = $db->data['SeasonID'];
            $sename = $db->data['SeasonName'];

            echo "    <option value=\"main.php?SID=$SID&action=$action&do=byseason&season=$season&sename=$sename\">" . $db->data['SeasonName'] . " season</option>\n";

        }

        echo "    </select></p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
        


    }
}

function show_main_menu_season($db,$season,$sename)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new ladder record</a></p>\n";
    if (!$db->Exists("SELECT * FROM ladder")) {
        echo "<p>There are currently no ladders in the database.</p>\n";
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
                        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
                }

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "<tr>\n";
            echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$sename Season Ladder</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
        echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
        
                echo "<table border=\"0\" width=\"100%\" cellpadding=\"3\" cellspacing=\"0\">\n";
                echo "<tr class=\"trtop\">\n";
                echo "  <td align=\"center\"><b class=\"white\">Team</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">Pl</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">W</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">T</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">L</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">NR</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">Pt</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">Pen</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">Total</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">Rank</b></td>\n";
                echo "  <td align=\"center\"><b class=\"white\">Division</b></td>\n";
                echo "  <td align=\"right\"><b class=\"white\">Modify</b></td>\n";
                echo "</tr>\n";
            if (!$db->Exists("SELECT * FROM ladder")) {

            echo '<tr class="trrow', ($x % 2 ? '2' : '1'), '">';

                echo "  <td align=\"left\" width=\"100%\" colspan=\"5\"><p>No games.</p></td>\n";
                echo "</tr>\n";
                echo "</table>\n";
                echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
            } else {

                $db->Query("
                SELECT
                  lad. * , tm.TeamAbbrev AS team
                FROM
                  ladder lad
                INNER JOIN
                  teams tm ON lad.team = tm.TeamID
                WHERE
                    lad.season=$season ORDER BY lad.totalpoints DESC, rank_sort ASC
                ");

                for ($x=0; $x<$db->rows; $x++) {
                    $db->GetRow($x);

                    $te = htmlentities(stripslashes($db->data['team']));
                    $pl = htmlentities(stripslashes($db->data['played']));
                    $wo = htmlentities(stripslashes($db->data['won']));
                    $lo = htmlentities(stripslashes($db->data['lost']));
                    $ti = htmlentities(stripslashes($db->data['tied']));
                    $nr = htmlentities(stripslashes($db->data['nrr']));
                    $pt = htmlentities(stripslashes($db->data['points']));
                    $pe = htmlentities(stripslashes($db->data['penalty']));
                    $tp = htmlentities(stripslashes($db->data['totalpoints']));
                    $rs = htmlentities(stripslashes($db->data['rank_sort']));
                    $di = htmlentities(stripslashes($db->data['division']));


                    if($x % 2) {
                      echo "<tr class=\"trrow1\">\n";
                    } else {
                      echo "<tr class=\"trrow2\">\n";
                    }

                    echo "  <td align=\"center\">$te</td>\n";
                    echo "  <td align=\"center\">$pl</td>\n";
                    echo "  <td align=\"center\">$wo</td>\n";
                    echo "  <td align=\"center\">$ti</td>\n";
                    echo "  <td align=\"center\">$lo</td>\n";
                    echo "  <td align=\"center\">$nr</td>\n";
                    echo "  <td align=\"center\">$pt</td>\n";
                    echo "  <td align=\"center\">$pe</td>\n";
                    echo "  <td align=\"center\">$tp</td>\n";
                    echo "  <td align=\"center\">$rs</td>\n";
                    echo "  <td align=\"center\">$di</td>\n";

                    echo "  <td align=\"right\">";
                    echo "<a href=\"main.php?SID=$SID&action=ladderadmin&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a>
<a href=\"main.php?SID=$SID&action=ladderadmin&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" alt=\"Delete\" border=\"0\"></a></td>\n";

//                    echo "<a href=\"main.php?SID=$SID&action=ladderadmin&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" alt=\"Edit\" border=\"0\"></a></td>\n";

                    echo "</tr>\n";
                }
            }
        }
                    echo "</table>\n";
                    
                    echo "</td>\n";
                    echo "</tr>\n";
                    echo "</table>\n";
}


function add_category_form($db)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    echo "<p>Add a new ladderd game.</p>\n";

        echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
        echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
        echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
        echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
        echo "<select name=\"season\">\n";
        echo "  <option value=\"\">Select a season</option>\n";
        echo "  <option value=\"\">--------------------------</option>\n";

    if ($db->Exists("SELECT * FROM seasons")) {
        $db->Query("SELECT * FROM seasons ORDER BY SeasonName");
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            echo "<option value=\"" . $db->data['SeasonID'] . "\">Season " . $db->data['SeasonName'] . "</option>\n";
        }
    }

        echo "</select>\n";

        echo "<p>select team<br><select name=\"team\">\n";
        echo "  <option value=\"\">Select Team</option>\n";
        echo "  <option value=\"\">--------------------------</option>\n";

    if ($db->Exists("SELECT * FROM teams")) {
        $db->Query("SELECT * FROM teams ORDER BY TeamName");
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamName'] . "</option>\n";
        }
    }

        echo "</select></p>\n";

        echo "<p>Played?<br><input type=\"text\" name=\"played\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Won?<br><input type=\"text\" name=\"won\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Tied?<br><input type=\"text\" name=\"tied\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Lost?<br><input type=\"text\" name=\"lost\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>No Result?<br><input type=\"text\" name=\"nrr\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Points?<br><input type=\"text\" name=\"points\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Penalty?<br><input type=\"text\" name=\"penalty\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Total Points?<br><input type=\"text\" name=\"totalpoints\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Rank ?<br><input type=\"text\" name=\"rank_sort\" size=\"40\" maxlength=\"255\"></p>\n";
        echo "<p>Division ?<br><input type=\"text\" name=\"division\" size=\"40\" maxlength=\"1\"></p>\n";


        echo "<p><input type=\"submit\" value=\"add ladder\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
        echo "</form>\n";
}


function do_add_category($db,$season,$team,$played,$won,$tied,$lost,$nrr,$points,$penalty,$totalpoints,$rank_sort,$division)
{
    global $PHP_SELF,$content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// make sure info is present and correct

    if ($season == "" || $team == "") {
        echo "<p>You must select a season and a team.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to ladder list</a></p>\n";
        return;
    }

    $se = addslashes(trim($season));
    $te = addslashes(trim($team));
    $pl = addslashes(trim($played));
    $wo = addslashes(trim($won));
    $ti = addslashes(trim($tied));
    $lo = addslashes(trim($lost));
    $nr = addslashes(trim($nrr));
    $pt = addslashes(trim($points));
    $pe = addslashes(trim($penalty));
    $tp = addslashes(trim($totalpoints));
    $rs = addslashes(trim($rank_sort));
    $di = addslashes(trim($division));


    // check to see if it exists first
    if ($db->Exists("SELECT * FROM ladder WHERE season='$se' AND team='$te'")) {
        echo "<p>That game already exists in the database.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to ladder list</a></p>\n";
        return;
    }
    // all okay
    $db->Insert("INSERT INTO ladder (season, team, played, won, tied, lost, nrr, points, penalty, totalpoints, rank_sort, division) VALUES ('$se','$te','$pl','$wo','$ti','$lo','$nr','$pt','$pe','$tp','$rs','$di')");
    if ($db->a_rows != -1) {
        echo "<p>You have now added a new game.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">add another ladderd game</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to ladder list</a></p>\n";
    } else {
        echo "<p>The game could not be added to the database at this time.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sadd\">try again</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to ladder list</a></p>\n";
    }
}


function delete_category_check($db,$id)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    $db->Query("SELECT * FROM teams ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }

    $db->Query("SELECT * FROM grounds ORDER BY GroundName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $grounds[$db->data['GroundID']] = $db->data['GroundName'];
    }

    $db->QueryItem("SELECT * FROM ladder WHERE id=$id");

    $tm = htmlentities(stripslashes($teams[$db->data['team']]));
    
    echo "<p>Are you sure you wish to delete the following ladder team:</p>\n";
    echo "<p><b>$tm</b></p>\n";
    echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    if (!$doit) echo "<p>You have chosen NOT to delete that ladderd team.</p>\n";
    else {
        $db->Delete("DELETE FROM ladder WHERE id=$id");
        echo "<p>You have now deleted that ladderd team.</p>\n";
    }
    echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the ladder list</a></p>\n";
}


function edit_category_form($db,$id)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    // get all seasons
    $db->Query("SELECT * FROM seasons ORDER BY SeasonName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $db->BagAndTag();
        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
    }
    // get all teams
    $db->Query("SELECT * FROM teams ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $db->BagAndTag();
        $teams[$db->data['TeamID']] = $db->data['TeamName'];
        $teams2 = $teams;
        $umpires = $teams;
    }
    // get all grounds
    $db->Query("SELECT * FROM grounds ORDER BY GroundID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $db->BagAndTag();
        $grounds[$db->data['GroundID']] = $db->data['GroundName'];
    }

    // get article details

    $db->QueryRow("SELECT * FROM ladder WHERE id=$id");

    $te = htmlentities(stripslashes($db->data['team']));
    $pl = htmlentities(stripslashes($db->data['played']));
    $wo = htmlentities(stripslashes($db->data['won']));
    $lo = htmlentities(stripslashes($db->data['lost']));
    $ti = htmlentities(stripslashes($db->data['tied']));
    $nr = htmlentities(stripslashes($db->data['nrr']));
    $pt = htmlentities(stripslashes($db->data['points']));
    $pe = htmlentities(stripslashes($db->data['penalty']));
    $tp = htmlentities(stripslashes($db->data['totalpoints']));
    $rs = htmlentities(stripslashes($db->data['rank_sort']));
    $di = htmlentities(stripslashes($db->data['division']));

    echo "<p>Edit the ladderd game.</p>\n";

        echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
        echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
        echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
        echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

        echo "<p>select season:<br>\n";
        echo "<select name=\"season\">\n";
        echo "  <option value=\"\">Select Season</option>\n";
        echo "  <option value=\"\">--------------------------</option>\n";

        /*
            for ($i=0; $i<count($seasons); $i++) {
                echo "<option value=\"$i\"" . ($i==$db->data['season']?" selected":"") . ">" . $seasons[$i] . "</option>\n";
            }
        */
        foreach ($seasons as $k => $v) {
          echo "<option value=\"$k\"" . ($k==$db->data['season']?" selected":"") . ">" . $v . "</option>\n";
        }

        echo "</select></p>\n";

        echo "<p>select team:<br>\n";
        echo "<select name=\"team\">\n";
        echo "  <option value=\"\">Select Team</option>\n";
        echo "  <option value=\"\">--------------------------</option>\n";

        /*
            for ($i=0; $i<count($teams); $i++) {
                echo "<option value=\"$i\"" . ($i==$db->data['team']?" selected":"") . ">" . $teams[$i] . "</option>\n";
            }
        */

        foreach ($teams as $k => $v) {
          echo "<option value=\"$k\"" . ($k==$db->data['team']?" selected":"") . ">" . $v . "</option>\n";
        }
        echo "</select></p>\n";

        echo "<p>Played?<br><input type=\"text\" name=\"played\" size=\"40\" maxlength=\"255\" value=\"$pl\"></p>\n";
        echo "<p>Won?<br><input type=\"text\" name=\"won\" size=\"40\" maxlength=\"255\" value=\"$wo\"></p>\n";
        echo "<p>Tied?<br><input type=\"text\" name=\"tied\" size=\"40\" maxlength=\"255\" value=\"$ti\"></p>\n";
        echo "<p>Lost?<br><input type=\"text\" name=\"lost\" size=\"40\" maxlength=\"255\" value=\"$lo\"></p>\n";
        echo "<p>No Result?<br><input type=\"text\" name=\"nrr\" size=\"40\" maxlength=\"255\" value=\"$nr\"></p>\n";
        echo "<p>Points?<br><input type=\"text\" name=\"points\" size=\"40\" maxlength=\"255\" value=\"$pt\"></p>\n";
        echo "<p>Penalty?<br><input type=\"text\" name=\"penalty\" size=\"40\" maxlength=\"255\" value=\"$pe\"></p>\n";
        echo "<p>Total Points?<br><input type=\"text\" name=\"totalpoints\" size=\"40\" maxlength=\"255\" value=\"$tp\"></p>\n";
        echo "<p>Rank?<br><input type=\"text\" name=\"rank_sort\" size=\"40\" maxlength=\"255\" value=\"$rs\"></p>\n";
        echo "<p>Division?<br><input type=\"text\" name=\"division\" size=\"40\" maxlength=\"1\" value=\"$di\"></p>\n";


        echo "<p><input type=\"submit\" value=\"edit ladder\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
        echo "</form>\n";

}


function do_update_category($db,$id,$season,$team,$played,$won,$tied,$lost,$nrr,$points,$penalty,$totalpoints,$rank_sort,$division)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// make sure info is present and correct

    if ($season == "" || $team == "") {
        echo "<p>You must select a season and a team.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=sedit&id=$id\">try again</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to ladder list</a></p>\n";
        return;
    }

    $se = addslashes(trim($season));
    $te = addslashes(trim($team));
    $pl = addslashes(trim($played));
    $wo = addslashes(trim($won));
    $ti = addslashes(trim($tied));
    $lo = addslashes(trim($lost));
    $nr = addslashes(trim($nrr));
    $pt = addslashes(trim($points));
    $pe = addslashes(trim($penalty));
    $tp = addslashes(trim($totalpoints));
	$rs = addslashes(trim($rank_sort));
	$di = addslashes(trim($division));

    // do update
    $db->Update("UPDATE ladder SET season='$se',team='$te',played='$pl',won='$wo',tied='$ti',lost='$lo',nrr='$nr',points='$pt',penalty='$pe',totalpoints='$tp',rank_sort='$rs',division='$di' WHERE id=$id");
    if ($db->a_rows != -1) {
        echo "<p>You have now updated that ladder.</p>\n";
        echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the ladder list</a></p>\n";
    } else {
        echo "<p>That ladder could not be changed at this time.</p>\n";
        echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the ladder list</a></p>\n";
    }
}

// main program

if (!$USER['flags'][$f_ladder_admin]) {
    header("Location: main.php?SID=$SID");
    exit;
}

echo "<p class=\"16px\"><b>Ladder Administration</b></p>\n";

if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
}
else {
	$do = '';
}

if(isset($_GET['doit'])) {
	$doit = $_GET['doit'];
} else if(isset($_POST['doit'])) {
	$doit = $_POST['doit'];
}

switch($do) {
case "byseason":
        show_main_menu_season($db,$_GET['season'],$_GET['sename']);
        break;
case "sadd":
    if (!isset($doit)) add_category_form($db);
    else do_add_category($db,$_POST['season'],$_POST['team'],$_POST['played'],$_POST['won'],$_POST['tied'],$_POST['lost'],$_POST['nrr'],$_POST['points'],$_POST['penalty'],$_POST['totalpoints'],$_POST['rank_sort'],$_POST['division']);
    break;
case "sdel":
    if (!isset($doit)) delete_category_check($db,$_GET['id']);
    else do_delete_category($db,$_GET['id'],$doit);
    break;
case "sedit":
    if (!isset($doit)) edit_category_form($db,$_GET['id']);
    else do_update_category($db,$_POST['id'],$_POST['season'],$_POST['team'],$_POST['played'],$_POST['won'],$_POST['tied'],$_POST['lost'],$_POST['nrr'],$_POST['points'],$_POST['penalty'],$_POST['totalpoints'],$_POST['rank_sort'],$_POST['division']);
    break;
default:
    show_main_menu($db);
    break;
}

?>
