<?php

//------------------------------------------------------------------------------
// Cougar Players v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_cougarsplayers_listing($db,$id,$cougar)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM seasons")) {
        $db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%'ORDER BY SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &gt; Schedule</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("includes/navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p class=\"16px\">Cougar Players</p>\n";
        echo "<p>First, please select a season to work with.</p>\n";

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
            echo "    <p>There are no featured members in the database.</p>\n";
            echo "    <p><a href=\"/index.php\">&laquo; back to homepage</a></p>\n";
            echo "    </td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
    }
}





function show_cougarsplayers_season($db,$id,$cougar,$season,$sename)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr,$sename;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Cougar Player</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">Cougar Players - $sename</p>\n";
    // check for empty database

    if (!$db->Exists("
            SELECT
                pl.PlayerFName, pl.PlayerLName,
                te.TeamName, te.TeamAbbrev,
                fm.CougarID, fm.CougarDetail, fm.season
            FROM
                cougarsplayers fm
            INNER JOIN
                players pl ON fm.CougarPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.season=$season
            ORDER BY
                fm.CougarID DESC
    ")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Cougar Players for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
      echo "  <tr>\n";
      echo "    <td>\n";
      echo "<tr class=\"trrow1\">\n";

        echo "  <td align=\"left\">There are no colorado cougars for this season.</td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p>&laquo; <a href=\"$PHP_SELF\">return to season selection list</a></p>\n";

        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
    
        return;
    } else {

        // output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Cougar Players for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
      echo "  <tr>\n";
      echo "    <td>\n";

        // query database

        $db->Query("
            SELECT
                pl.PlayerFName, pl.PlayerLName,
                te.TeamName, te.TeamAbbrev,
                fm.CougarID, fm.CougarDetail, fm.season, fm.added
            FROM
                cougarsplayers fm
            INNER JOIN
                players pl ON fm.CougarPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.season=$season
            ORDER BY
                fm.CougarID DESC
        ");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);

            // setup variables

            $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
            $pln = htmlentities(stripslashes($db->data['PlayerLName']));

            $tna = htmlentities(stripslashes($db->data['TeamName']));
            $tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

            $det = htmlentities(stripslashes($db->data[CougarDetail]));
            $id = htmlentities(stripslashes($db->data[CougarID]));
            $sn = htmlentities(stripslashes($db->data['SeasonName']));
            $a = sqldate_to_string($db->data['added']);

            if($x % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

            // output

            echo "  <td align=\"left\"><a href=\"$PHP_SELF?cougar=$id&ccl_mode=1\"><b>$pfn $pln</b></a>  ($tab)</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


    }

    echo "<p>&laquo; <a href=\"$PHP_SELF\">return to season selection list</a></p>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}


function show_cougarsplayers_listinga($db,$s,$id,$pr)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    if ($db->Exists("SELECT * FROM cougarsplayers")) {
    $db->QueryRow("
        SELECT
            pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.picture,
            te.TeamName, te.TeamAbbrev,
            fm.CougarID, fm.CougarDetail
        FROM
            cougarsplayers fm
        INNER JOIN
            players pl ON fm.CougarPlayer = pl.PlayerID
        INNER JOIN
            teams te ON pl.PlayerTeam = te.TeamID
        ORDER BY fm.CougarID DESC
    ");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Cougar Player</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";



    // CougarMember Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Cougar Player Archives</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        // setup variables

        $pfn = $db->data['PlayerFName'];
        $pln = $db->data['PlayerLName'];
        $pic = $db->data['picture'];
        $pid = $db->data['PlayerID'];

        $tna = $db->data['TeamName'];
        $tab = $db->data['TeamAbbrev'];

        $det = $db->data[CougarDetail];
        $id  = $db->data[CougarID];
        $pr  = $db->data[CougarID];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?cougar=$pr&ccl_mode=1\"><b>$pfn $pln ($tab)</b></a></td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no news in the database\n";
    }
}

function show_cougarsplayers($db,$s,$id,$pr)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    if (!$db->Exists("SELECT * FROM cougarsplayers")) {
        echo "<p>The Cougar Player is being edited. Please check back shortly.</p>\n";
        return;
    } else {
        $db->QueryRow("
            SELECT
                pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.picture,
                te.TeamName, te.TeamAbbrev,
                fm.CougarID, fm.CougarDetail
            FROM
                cougarsplayers fm
            INNER JOIN
                players pl ON fm.CougarPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.CougarID = $pr
            ORDER BY fm.CougarID DESC;
        ");
        $db->BagAndTag();

        // setup variables

        $pfn = $db->data['PlayerFName'];
        $pln = $db->data['PlayerLName'];
        $pic = $db->data['picture'];
        $pid = $db->data['PlayerID'];

        $tna = $db->data['TeamName'];
        $tab = $db->data['TeamAbbrev'];

        $det = $db->data[CougarDetail];

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; Cougar Player</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("includes/navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

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
        echo "  <p align=\"center\"><img src=\"http://www.coloradocricket.org/uploadphotos/players/$pic\" width=\"150\" border=\"1\"></p>\n";
        } else {
        echo "  &nbsp;\n";
        }

        echo "  <p>$det</p>\n";
        echo "  <p><a href=\"http://www.coloradocricket.org/players.php?players=$pid&ccl_mode=1\"><img src=\"http://www.coloradocricket.org/images/icons/icon_members.gif\" border=\"0\"> view $pfn's profile</a></p>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p><a href=\"$PHP_SELF\">&laquo; back to colorado cougar listing</a></p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        }
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


switch($ccl_mode) {
case 0:
    show_cougarsplayers_listing($db,$id,$cougar);
    break;
case 1:
    show_cougarsplayers($db,$s,$id,$cougar);
    break;
case 2:
    show_cougarsplayers_season($db,$id,$cougar,$season,$sename);
    break;  
default:
    show_cougarsplayers_listing($db,$s,$id,$cougar);
    break;
}

?>