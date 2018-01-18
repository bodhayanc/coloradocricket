<?php

//------------------------------------------------------------------------------
// Featured Members v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------

function show_featuredmember_listing($db,$id,$fm)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Featured Member</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">Featured Players</p>\n";
    echo "<p>First, please select a season to work with.</p>\n";

    // check for empty database

    if (!$db->Exists("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName")) {
        echo "<p>There are currently no seasons in the database.</p>\n";
        return;
    } else {

        // output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Player Season</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
      echo "  <tr>\n";
      echo "    <td>\n";

        // query database

        $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);

            // setup variables

            $sename = htmlentities(stripslashes($db->data['SeasonName']));
            $season = htmlentities(stripslashes($db->data['SeasonID']));

            if($x % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

            // output

            echo "  <td align=\"left\"><a href=\"$PHP_SELF?season=$season&sename=$sename&ccl_mode=2\">$sename</a></td>\n";
            echo "</tr>\n";
            }
        echo "</table>\n";

        echo "</td>\n";
        echo "</tr>\n";
        echo "</table>\n";
    }

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
        
}

function show_featuredmember_season($db,$id,$fm,$season,$sename)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr,$sename;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Featured Member</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">Featured Players - $sename</p>\n";
    // check for empty database

    if (!$db->Exists("
            SELECT
                pl.PlayerFName, pl.PlayerLName,
                te.TeamName, te.TeamAbbrev,
                fm.FeaturedID, fm.FeaturedDetail, fm.season
            FROM
                featuredmember fm
            INNER JOIN
                players pl ON fm.FeaturedPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.season=$season
            ORDER BY
                fm.FeaturedID DESC
    ")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Members for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
      echo "  <tr>\n";
      echo "    <td>\n";
      echo "<tr class=\"trrow1\">\n";

        echo "  <td align=\"left\">There are no featured players for this season.</td>\n";
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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Members for $sename</td>\n";
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
                fm.FeaturedID, fm.FeaturedDetail, fm.season, fm.added
            FROM
                featuredmember fm
            INNER JOIN
                players pl ON fm.FeaturedPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.season=$season
            ORDER BY
                fm.FeaturedID DESC
        ");
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);

            // setup variables

            $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
            $pln = htmlentities(stripslashes($db->data['PlayerLName']));

            $tna = htmlentities(stripslashes($db->data[TeamName]));
            $tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

            $det = htmlentities(stripslashes($db->data[FeaturedDetail]));
            $id = htmlentities(stripslashes($db->data[FeaturedID]));
            $sn = htmlentities(stripslashes($db->data['SeasonName']));
            $a = sqldate_to_string($db->data[added]);

            if($x % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

            // output

            echo "  <td align=\"left\"><a href=\"$PHP_SELF?fm=$id&ccl_mode=1\"><b>$pfn $pln ($tab)</b></a></td>\n";
            echo "  <td align=\"left\">$a</td>\n";
            echo "</tr>\n";
        }
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


    }

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}


function show_featuredmember_listinga($db,$s,$id,$pr)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    if ($db->Exists("SELECT * FROM featuredmember")) {
    $db->QueryRow("
        SELECT
            pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.picture,
            te.TeamName, te.TeamAbbrev,
            fm.FeaturedID, fm.FeaturedDetail
        FROM
            featuredmember fm
        INNER JOIN
            players pl ON fm.FeaturedPlayer = pl.PlayerID
        INNER JOIN
            teams te ON pl.PlayerTeam = te.TeamID
        ORDER BY fm.FeaturedID DESC
    ");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Featured Member</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";



    // FeaturedMember Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Member Archives</td>\n";
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

        $tna = $db->data[TeamName];
        $tab = $db->data['TeamAbbrev'];

        $det = $db->data[FeaturedDetail];
        $id  = $db->data[FeaturedID];
        $pr  = $db->data[FeaturedID];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?fm=$pr&ccl_mode=1\"><b>$pfn $pln ($tab)</b></a></td>\n";
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

function show_featuredmember($db,$s,$id,$pr)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    if (!$db->Exists("SELECT * FROM featuredmember")) {
        echo "<p>The Featured Member is being edited. Please check back shortly.</p>\n";
        return;
    } else {
        $db->QueryRow("
            SELECT
                pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.picture,
                te.TeamName, te.TeamAbbrev,
                fm.FeaturedID, fm.FeaturedDetail
            FROM
                featuredmember fm
            INNER JOIN
                players pl ON fm.FeaturedPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.FeaturedID = $pr
            ORDER BY fm.FeaturedID DESC;
        ");
        $db->BagAndTag();

        // setup variables

        $pfn = $db->data['PlayerFName'];
        $pln = $db->data['PlayerLName'];
        $pic = $db->data['picture'];
        $pid = $db->data['PlayerID'];

        $tna = $db->data[TeamName];
        $tab = $db->data['TeamAbbrev'];

        $det = $db->data[FeaturedDetail];

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; Featured Member</p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("navtop.php");
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
        echo "  <p align=\"center\"><img src=\"/uploadphotos/players/$pic\" width=\"150\" border=\"1\"></p>\n";
        } else {
        echo "  &nbsp;\n";
        }

        echo "  <p>$det</p>\n";
        echo "  <p>$pfn wins a $20 gift certificate to <a href=\"http://www.denverwoodlands.com\" target=\"_new\">Denver Woodlands</a> Kosher Indian Restaurant and Bakery. Congratulations $pfn!</p>\n";
        echo "  <p><a href=\"players.php?players=$pid&ccl_mode=1\"><img src=\"/images/icons/icon_members.gif\" border=\"0\"> view $pfn's profile</a></p>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p><a href=\"$PHP_SELF\">&laquo; back to featured member listing</a></p>\n";
        echo "<p>&nbsp;</p><p align=\"center\"><a href=\"http://www.denverwoodlands.com\" target=\"_new\"><img src=\"/images/bannaz/denverwoodlands.gif\" border=\"0\"></a></p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        }
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


switch($ccl_mode) {
case 0:
    show_featuredmember_listing($db,$id,$fm);
    break;
case 1:
    show_featuredmember($db,$s,$id,$fm);
    break;
case 2:
    show_featuredmember_season($db,$id,$fm,$season,$sename);
    break;  
default:
    show_featuredmember_listing($db,$s,$id,$fm);
    break;
}

?>