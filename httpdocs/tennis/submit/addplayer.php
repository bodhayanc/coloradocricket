<?php

//------------------------------------------------------------------------------
// Add Player Version 2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


    require ("../includes/general.config.inc");
    require ("../includes/class.mysql.inc");
    require ("../includes/class.fasttemplate.inc");
    require ("../includes/general.functions.inc");

echo "<link rel=\"stylesheet\" href=\"/includes/css/cricket.css\" type=\"text/css\">\n";



function add_category_form($db)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"80%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a player</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
      echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
      echo "  <tr>\n";
      echo "    <td>\n";

    echo "<form action=\"doaddplayer.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
    echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
    echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
    echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
    echo "<p>enter the players last name<br><input type=\"text\" name=\"PlayerLName\" size=\"40\" maxlength=\"255\"></p>\n";
    echo "<p>enter the players first name<br><input type=\"text\" name=\"PlayerFName\" size=\"40\" maxlength=\"255\"></p>\n";
    echo "<p>enter the players date of birth, place of birth<br><input type=\"text\" name=\"Born\" size=\"40\" maxlength=\"255\"></p>\n";

    echo "<p><select name=\"BattingStyle\">\n";
    echo "  <option value=\"\">Select Batting Style</option>\n";
    echo "  <option value=\"\">--------------------------</option>\n";
    echo "  <option value=\"Right Hand Bat\">Right Hand Bat</option>\n";
    echo "  <option value=\"Left Hand Bat\">Left Hand Bat</option>\n";
    echo "</select></p>\n";

    echo "<p><select name=\"BowlingStyle\">\n";
    echo "  <option value=\"\">Select Bowling Style</option>\n";
    echo "  <option value=\"\">--------------------------</option>\n";
    echo "  <option value=\"Right Arm Fast\">Right Arm Fast</option>\n";
    echo "  <option value=\"Right Arm Fast Medium\">Right Arm Fast Medium</option>\n";
    echo "  <option value=\"Right Arm Medium\">Right Arm Medium</option>\n";
    echo "  <option value=\"Right Arm Slow\">Right Arm Slow</option>\n";
    echo "  <option value=\"Right Arm Off Spin\">Right Arm Off Spin</option>\n";
    echo "  <option value=\"Right Arm Leg Spin\">Right Arm Leg Spin</option>\n";
    echo "  <option value=\"Left Arm Fast\">Left Arm Fast</option>\n";
    echo "  <option value=\"Left Arm Fast Medium\">Left Arm Fast Medium</option>\n";
    echo "  <option value=\"Left Arm Medium\">Left Arm Medium</option>\n";
    echo "  <option value=\"Left Arm Slow\">Left Arm Slow</option>\n";
    echo "  <option value=\"Left Arm Off Spin\">Left Arm Off Spin</option>\n";
    echo "  <option value=\"Left Arm Leg Spin\">Left Arm Leg Spin</option>\n";
    echo "</select></p>\n";


    echo "<p><select name=\"PlayerTeam\">\n";
    echo "  <option value=\"\">Team player belongs to</option>\n";
    echo "  <option value=\"\">--------------------------</option>\n";
    if ($db->Exists("SELECT * FROM tennisteams")) {
        $db->Query("SELECT * FROM tennisteams ORDER BY TeamName");
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            echo "<option value=\"" . $db->data[TeamID] . "\">" . $db->data['teamname'] . "</option>\n";
        }
    }
    echo "</select></p>\n";

    echo "<p>enter the players email address<br><input type=\"text\" name=\"PlayerEmail\" size=\"40\" maxlength=\"255\"></p>\n";
    echo "<p>enter a <b>short</b> profile <i>(300 characters or less)</i><br><textarea name=\"shortprofile\" cols=\"55\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";

    echo "<p>upload a player photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
    echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
    echo "<li>please save images no larger than 380 pixels wide\n";
    echo "<li>only GIF and JPG files only please.</ul></p>\n";

    echo "<p>upload a player action photo<br><input type=\"file\" name=\"userpic1\" size=\"40\"></p>\n";
    echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
    echo "<li>please save images no larger than 380 pixels wide\n";
    echo "<li>only GIF and JPG files only please.</ul></p>\n";

    echo "<p><input type=\"submit\" value=\"add player\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
    echo "</form>\n";


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
$db->SelectDB($dbcfg['db']);

add_category_form($db);

?>
