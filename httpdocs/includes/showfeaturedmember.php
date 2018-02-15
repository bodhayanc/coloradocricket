<?php

//------------------------------------------------------------------------------
// Colorado Cricket Featured Players v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------

function show_featuredmember_listing($db,$id,$fm)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM seasons")) {
        //$db->QueryRow("SELECT la.season, se.SeasonID, se.SeasonName FROM featuredmember la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");
		$db->QueryRow("SELECT la.season, se.SeasonID, se.SeasonName FROM featuredmember la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Featured Members</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Featured Players</b><br><br>\n";

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
        echo "    <p>There are no featured members in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";
    }
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
//    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/featuredmember.php\">Featured Player</a> &raquo; <font class=\"10px\">$sename </font></p>\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/featuredmember.php\">Player Of The Week</a> &raquo; <font class=\"10px\">$sename </font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Featured Players - $sename</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Featured Member Option Box
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">OPTIONS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";


    // List by season for member


    echo "<p class=\"10px\">Season: ";
    echo "<select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
    echo "<option value=\"\" selected>year</option>\n";

   // $db->Query("SELECT la.season, se.SeasonName FROM featuredmember la INNER JOIN seasons se ON la.season = se.SeasonID WHERE se.SeasonName NOT LIKE '%KO%' GROUP BY la.season ORDER BY se.SeasonName DESC");
       $db->Query("SELECT la.season, se.SeasonName FROM featuredmember la INNER JOIN seasons se ON la.season = se.SeasonID GROUP BY la.season ORDER BY se.SeasonName DESC");
    for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);
        $db->BagAndTag();
        $sen = $db->data['SeasonName'];
        $sid = $db->data['season'];

    echo "    <option value=\"$PHP_SELF?season=$sid&sename=$sen&ccl_mode=2\" class=\"10px\">$sen</option>\n";

    }
    echo "    </select></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // Featured Member Box
    //////////////////////////////////////////////////////////////////////////////////////////
    
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;FEATURED PLAYERS</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo "  <tr class=\"colhead\">\n";
    echo "  <td align=\"left\"><b>DATE</b></td>\n";
    echo "  <td align=\"left\"><b>PLAYER</b></td>\n";
    echo "  <td align=\"left\"><b>TEAM</b></td>\n";


    if (!$db->Exists("SELECT pl.PlayerFName, pl.PlayerLName, te.TeamName, te.TeamAbbrev, fm.FeaturedID, fm.FeaturedDetail, fm.season FROM featuredmember fm INNER JOIN players pl ON fm.FeaturedPlayer = pl.PlayerID INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE fm.season=$season ORDER BY fm.FeaturedID DESC")) {

    echo "<tr class=\"trrow1\">\n";
    echo "  <td align=\"left\">There are no featured players for this season.</td>\n";
    echo "</tr>\n";
    
    } else {

    $db->Query("SELECT pl.PlayerFName, pl.PlayerLName, te.TeamName, te.TeamAbbrev, fm.FeaturedID, fm.FeaturedDetail, fm.season, fm.added FROM featuredmember fm INNER JOIN players pl ON fm.FeaturedPlayer = pl.PlayerID INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE fm.season=$season ORDER BY fm.added DESC");
    for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);

        // setup variables

        $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
        $pln = htmlentities(stripslashes($db->data['PlayerLName']));

        $tna = htmlentities(stripslashes($db->data['TeamName']));
        $tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

        $det = htmlentities(stripslashes($db->data['FeaturedDetail']));
        $id = htmlentities(stripslashes($db->data['FeaturedID']));
        $sn = htmlentities(stripslashes($db->data['SeasonName']));
        $a = sqldate_to_string($db->data['added']);

        if($x % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        // output

        echo "  <td align=\"left\" class=\"10px\">$a</td>\n";
        echo "  <td align=\"left\"><a href=\"$PHP_SELF?season=$season&sename=$sename&fm=$id&ccl_mode=1\">$pfn $pln</a></td>\n";
        echo "  <td align=\"left\" class=\"10px\">$tab</td>\n";
        
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

function show_featuredmember($db,$id,$fm,$season,$sename)
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
                fm.FeaturedID, fm.FeaturedDetail, fm.added
            FROM
                featuredmember fm
            INNER JOIN
                players pl ON fm.FeaturedPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.FeaturedID = $fm
            ORDER BY fm.added DESC;
        ");
        $db->BagAndTag();

        // setup variables

        $pfn = $db->data['PlayerFName'];
        $pln = $db->data['PlayerLName'];
        $pic = $db->data['picture'];
        $pid = $db->data['PlayerID'];

        $tna = $db->data['TeamName'];
        $tab = $db->data['TeamAbbrev'];

        $det = $db->data['FeaturedDetail'];
        $a = sqldate_to_string($db->data['added']);

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/featuredmember.php\">Featured Player</a> &raquo; <font class=\"10px\">Detail</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


//        echo "<b class=\"16px\">Featured Player - $a</b><br><br>\n";
        echo "<b class=\"16px\">Player Of The Week - $a</b><br><br>\n";

        // output story, show the image, if no image show the title

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>$pfn $pln</b> ($tab)</td>\n";
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
//        echo "  <p>$pfn wins a $20 gift certificate to <a href=\"http://www.denverwoodlands.com\" target=\"_new\">Denver Woodlands</a> Kosher Indian Restaurant and Bakery. Congratulations $pfn!</p>\n";
        echo "  <p><a href=\"players.php?players=$pid&ccl_mode=1\"><img src=\"/images/icons/icon_members.gif\" border=\"0\"> view $pfn's profile</a></p>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p>&laquo; <a href=\"$PHP_SELF?season=$season&sename=$sename&ccl_mode=2\">back to featured member listing</a></p>\n";
//        echo "<p>&nbsp;</p><p align=\"center\"><a href=\"http://www.denverwoodlands.com\" target=\"_new\"><img src=\"/images/bannaz/denverwoodlands.gif\" border=\"0\"></a></p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        }
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

if (isset($_GET['ccl_mode'])) {
	switch($_GET['ccl_mode']) {
	case 0:
		show_featuredmember_listing($db,$id, isset($_GET['fm']) ? $_GET['fm'] : '');
		break;
	case 1:
		show_featuredmember($db,isset($_GET['id']) ? $_GET['id'] : '',isset($_GET['fm']) ? $_GET['fm'] : '',isset($_GET['season']) ? $_GET['season'] : '',isset($_GET['sename']) ? $_GET['sename'] : '');
		break;
	case 2:
		show_featuredmember_season($db,isset($_GET['id']) ? $_GET['id'] : '',isset($_GET['fm']) ? $_GET['fm'] : '',isset($_GET['season']) ? $_GET['season'] : '',isset($_GET['sename']) ? $_GET['sename'] : '');
		break;  
	default:
		show_featuredmember_listing($db,isset($_GET['id']) ? $_GET['id'] : '',isset($_GET['fm']) ? $_GET['fm'] : '');
		break;
	}
} else {
	show_featuredmember_listing($db);
}

?>
