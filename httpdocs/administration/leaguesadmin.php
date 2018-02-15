<?php

//------------------------------------------------------------------------------
// League Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a league</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM leaguemanagement")) {
		echo "<p>There are currently no leagues in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop


	
      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Leagues List</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM leaguemanagement ORDER BY LeagueName DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data[LeagueName]));
			$id = htmlentities(stripslashes($db->data[LeagueID]));
			$fe = $db->data['IsFeature'];

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			if($fe != "1") {
			echo "	<td align=\"left\">$t</td>\n";
			} else {
			echo "	<td align=\"left\"><b><font color=\"red\">$t</font></b></td>\n";
			}
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[LeagueID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data[LeagueID] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
	}
}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a league</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	
	echo "<p>enter the name of the league<br><input type=\"text\" name=\"LeagueName\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the abbreviation of the league <i>no spaces</i><br><input type=\"text\" name=\"LeagueAbbrev\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p><input type=\"submit\" value=\"add league\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$LeagueName,$LeagueAbbrev)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$t = addslashes(trim($LeagueName));
	$a = addslashes(trim($LeagueAbbrev));
	
	// check for duplicates

	if ($db->Exists("SELECT * FROM leaguemanagement WHERE LeagueName='$t'")) {
		echo "<p>That league already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO leaguemanagement (LeagueName,LeagueAbbrev) VALUES ('$t','$a')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new league.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another league</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to league list</a></p>\n";
	} else {
		echo "<p>The league could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to league list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT LeagueName FROM leaguemanagement WHERE LeagueID=$id")));

	// output

	echo "<p>Are you sure you wish to delete the league titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that league.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM leaguemanagement WHERE LeagueID=$id");
		echo "<p>You have now deleted that league.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the league listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	
	// query database

	$db->QueryRow("SELECT * FROM leaguemanagement WHERE LeagueID=$id");

	// setup variables

	$t  = stripslashes($db->data[LeagueName]);
	$a  = stripslashes($db->data[LeagueAbbrev]);


      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit League</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";


	echo "<p>enter the name of the league<br><input type=\"text\" name=\"LeagueName\" size=\"25\"maxlength=\"255\" value=\"$t\"></p>\n";
	echo "<p>enter the abbrev of the league <i>(no spaces)</i><br><input type=\"text\" name=\"LeagueAbbrev\" size=\"25\"maxlength=\"255\" value=\"$a\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit league\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$LeagueName,$LeagueAbbrev)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

// setup the variables

	$t = addslashes(trim($LeagueName));
	$a = addslashes(trim($LeagueAbbrev));
	
	// query database

	$db->Update("UPDATE leaguemanagement SET LeagueName='$t',LeagueAbbrev='$a' WHERE LeagueID=$id");
		echo "<p>You have now updated that league.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the leagues listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// main program

if (!$USER['flags'][$f_leagues_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Leagues Administration</b></p>\n";

switch($do) {
case "menu":
	show_main_menu($db);
	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$LeagueName,$LeagueAbbrev);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$LeagueName,$LeagueAbbrev);
	break;
default:
	show_main_menu($db);
	break;
}

?>
