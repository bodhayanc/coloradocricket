<?php

//------------------------------------------------------------------------------
// Colorado Cricket Awards v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------

function show_awards_listing($db,$id,$fm)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM seasons")) {
        //$db->QueryRow("SELECT aw.season, se.SeasonID, se.SeasonName FROM awards aw INNER JOIN seasons se ON aw.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY aw.season ORDER BY se.SeasonName DESC");
       $db->QueryRow("SELECT aw.season, se.SeasonID, se.SeasonName FROM awards aw INNER JOIN seasons se ON aw.season = se.SeasonID GROUP BY aw.season ORDER BY se.SeasonName DESC");
        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Awards</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Player Awards</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Schedule Select Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEASON SELECTOR</td>\n";
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
            $season = $db->data['SeasonID'];
            $sename = $db->data['SeasonName'];

            echo "    <option value=\"$PHP_SELF?season=$season&sename=$sename&ccl_mode=2\">" . $db->data['SeasonName'] . " season</option>\n";

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
        echo "    <p>There are no player awards in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
    }
}

function show_awards_season($db,$id,$aw,$season,$sename)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr,$sename;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/awards.php\">Awards</a> &raquo; <font class=\"10px\">$sename Awards</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Player Awards - $sename</b><br><br>\n";
    
    //////////////////////////////////////////////////////////////////////////////////////////
    // Awards Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";


    // List by season for awards

    echo "<p class=\"10px\">Season: ";
    echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
    echo "<option value=\"\" selected>year</option>\n";

    //$db->Query("SELECT aw.season, se.SeasonName FROM awards aw INNER JOIN seasons se ON aw.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY aw.season ORDER BY se.SeasonName DESC");
    $db->Query("SELECT aw.season, se.SeasonName FROM awards aw INNER JOIN seasons se ON aw.season = se.SeasonID GROUP BY aw.season ORDER BY se.SeasonName DESC");
    for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);
        $db->BagAndTag();
        $sen = $db->data['SeasonName'];
        $sid = $db->data['season'];
        $selected = "";
        if ($sename == $sen) {
        	$selected = "selected";
        }

    echo "    <option value=\"$PHP_SELF?season=$sid&sename=$sen&ccl_mode=2\" class=\"10px\" $selected>$sen</option>\n";

    }
    echo "    </select></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";
        

    //////////////////////////////////////////////////////////////////////////////////////////
    // Awards Season Box
    //////////////////////////////////////////////////////////////////////////////////////////
    
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;PLAYER AWARDS</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    
    
    if (!$db->Exists("SELECT pl.PlayerFName,pl.picture, pl.PlayerLName, te.TeamName, te.TeamAbbrev, fm.*, at.* FROM awards fm INNER JOIN players pl ON fm.AwardPlayer = pl.PlayerID INNER JOIN teams te ON pl.PlayerTeam = te.TeamID INNER JOIN awardtypes at ON fm.AwardTitle = at.AwardID WHERE fm.season=$season ORDER BY fm.AwardID DESC")) {

    echo "<tr class=\"trrow1\">\n";
    echo "  <td align=\"left\">There are no player awards for this season.</td>\n";
    echo "</tr>\n";
    
    } else {

    $db->Query("SELECT pl.PlayerFName, pl.picture, pl.PlayerLName, te.TeamName, te.TeamAbbrev, fm.*, fm.AwardID AS plaward, at.* FROM awards fm INNER JOIN players pl ON fm.AwardPlayer = pl.PlayerID INNER JOIN teams te ON pl.PlayerTeam = te.TeamID INNER JOIN awardtypes at ON fm.AwardTitle = at.AwardID WHERE fm.season=$season ORDER BY at.AwardName ASC");
    for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);

        // setup variables

        $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
        $pln = htmlentities(stripslashes($db->data['PlayerLName']));

        $tna = htmlentities(stripslashes($db->data['TeamName']));
        $tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

        $det = htmlentities(stripslashes($db->data['AwardDetail']));
        $awn = htmlentities(stripslashes($db->data['AwardName']));
        $id = htmlentities(stripslashes($db->data['plaward']));
        $sn = htmlentities(stripslashes($db->data['SeasonName']));
        $ad = htmlentities(stripslashes($db->data['AwardDetail']));
        $pc = htmlentities(stripslashes($db->data['picture']));
        $a = sqldate_to_string($db->data['added']);

        if($x % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        // output

        echo "  <td width=120  align=center valign=top><center><img alt='$pln, $pfn' align=center width=100 border=1 src=\"/uploadphotos/players/$pc\"><br>$awn<br>$pln, $pfn<br></center></td>\n";
        echo "  <td valign=top><b>Team: $tab</b> <br>". $ad ." </td>\n";
        echo "</tr>\n";
            
    }
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    


    }

}

function show_awards($db,$s,$id,$aw,$season,$sename)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    if (!$db->Exists("SELECT * FROM awards")) {
        echo "<p>The Award Member is being edited. Please check back shortly.</p>\n";
        return;
    } else {
        $db->QueryRow("
            SELECT
                pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.picture,
                te.TeamName, te.TeamAbbrev,
                fm.AwardID, fm.AwardDetail, fm.AwardTitle,
                at.*
            FROM
                awards fm
            INNER JOIN
                players pl ON fm.AwardPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            INNER JOIN
                awardtypes at ON fm.AwardTitle = at.AwardID             
            WHERE
                fm.AwardID = $aw
            ORDER BY fm.AwardID DESC;
        ");
        $db->BagAndTag();

        // setup variables

        $pfn = $db->data['PlayerFName'];
        $pln = $db->data['PlayerLName'];
        $pic = $db->data['picture'];
        $pid = $db->data['PlayerID'];

        $tna = $db->data['TeamName'];
        $tab = $db->data['TeamAbbrev'];

        $det = $db->data['AwardDetail'];
        $awn = htmlentities(stripslashes($db->data['AwardName']));

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/awards.php\">Awards</a> &raquo; <font class=\"10px\">Award Member</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">$sename $awn</b><br><br>\n";

        // output story, show the image, if no image show the title

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>$pfn $pln</b> ($tab)</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "<tr class=\"trrow1\">\n";
        echo "  <td align=\"left\" valigh=\"top\">\n";

        if($pic != "") {
        echo "  <p align=\"left\"><img src=\"/uploadphotos/players/$pic\" width=\"150\" border=\"1\" align=\"left\"></p>\n";
        } else {
        echo "  &nbsp;\n";
        }

        echo "  <p>$det</p>\n";
        echo "  <p><a href=\"players.php?players=$pid&ccl_mode=1\"><img src=\"/images/icons/icon_members.gif\" border=\"0\"> view $pfn's profile</a></p>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p>&laquo; <a href=\"$PHP_SELF?season=$season&sename=$sename&ccl_mode=2\">back to awards listing</a></p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        }
}






$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


switch($ccl_mode) {
case 0:
    show_awards_listing($db,$id,$aw);
    break;
case 1:
    show_awards($db,$s,$id,$aw,$season,$sename);
    break;
case 2:
    show_awards_season($db,$id,$aw,$season,$sename);
    break;  
default:
    show_awards_listing($db,$s,$id,$aw);
    break;
}

?>
