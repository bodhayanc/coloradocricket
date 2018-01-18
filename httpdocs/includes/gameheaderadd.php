<?php

function add_article($db)
{
    global $content,$action,$SID;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Submit Game Header</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
    echo "<tr>\n";
    echo "  <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;Add a user feature article</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

    echo "<form action=\"/addgameheader.php\" method=\"post\" enctype=\"multipart/form-data\">\n";

    echo "<p>enter the date<br><input type=\"text\" name=\"GameDate\" size=\"25\" maxlength=\"255\"></p>\n";
    echo "<p>enter your name<br><input type=\"text\" name=\"author\" size=\"25\" maxlength=\"255\"></p>\n";
    echo "<p>enter the article detail<br><textarea name=\"article\" cols=\"40\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";

    echo "<p><input type=\"submit\" value=\"add game header\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
    echo "</form>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg[db]);

add_article($db);

?>