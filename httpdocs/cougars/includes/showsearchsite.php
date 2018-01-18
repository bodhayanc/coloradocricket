<?php

//------------------------------------------------------------------------------
// Search Site v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_search_site($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Search Site</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the site</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"1\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // Finish off
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
}



function search_site($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Search Site</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    // Search Site Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the site</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"1\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    if ($search != "")
    {

        // Search Players Box

        $playercontains = "PlayerFName LIKE '%{$search}%' OR PlayerLName LIKE '%{$search}%'";

        $db->Query("SELECT cp.*, te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID INNER JOIN cougarsplayers cp ON pl.PlayerID = cp.CougarPlayer WHERE $playercontains ORDER BY pl.PlayerLName");
            if ($db->rows)
            {

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Players Matching \"$search\"</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $id = htmlentities(stripslashes($db->data['PlayerID']));
            $pln = htmlentities(stripslashes($db->data['PlayerLName']));
            $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
            $pte = htmlentities(stripslashes($db->data['TeamAbbrev']));

            if($i % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"http://www.coloradocricket.org/players.php?players=$id&ccl_mode=1\"><b>$pln, $pfn</b></a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";


            }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        } else {

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Players Matching \"$search\"</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "   <tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><p>There are no players matching that query in any way.</p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        }

        // Search News Box

        $newscontains = "IsFeature != 1 AND article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

        $db->Query("SELECT * FROM cougarsnews WHERE $newscontains ORDER BY id DESC");
            if ($db->rows)
            {

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;News Articles Matching \"$search\"</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $a = sqldate_to_string($db->data[added]);
            $t = sqldate_to_string($db->data['title']);
            $id = sqldate_to_string($db->data['id']);

            if($i % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

        echo "    <td width=\"80%\"><a href=\"news.php?news=$id&ccl_mode=1\"><b>$t</b></a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"20%\"><span class=\"9px\">$a</span></td>\n";
        echo "  </tr>\n";

            }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        } else {

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;News Articles Matching \"$search\"</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "   <tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><p>There are no news articles matching that query in any way.</p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        }

        // Search Featured Articles Box

        $featuredcontains = "(article LIKE '%{$search}%' OR title LIKE '%{$search}%') AND IsFeature != 0 AND IsPending != 1";

        $db->Query("SELECT * FROM cougarsnews WHERE $featuredcontains ORDER BY id DESC");
            if ($db->rows)
            {

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Articles Matching \"$search\"</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $a = sqldate_to_string($db->data[added]);
            $t = sqldate_to_string($db->data['title']);
            $id = sqldate_to_string($db->data['id']);

            if($i % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

        echo "    <td width=\"80%\"><a href=\"articles.php?news=$id&ccl_mode=1\"><b>$t</b></a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"20%\"><span class=\"9px\">$a</span></td>\n";
        echo "  </tr>\n";

            }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        } else {

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Articles Matching \"$search\"</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
        echo "   <tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><p>There are no featured articles matching that query in any way.</p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        }

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        }


 }



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_search_site($db);
    break;
case 1:
    search_site($db,$search);
    break;
default:
    show_search_site($db);
    break;
}


?>
