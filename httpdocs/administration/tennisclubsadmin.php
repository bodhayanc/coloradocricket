<?php

//------------------------------------------------------------------------------
// Clubs Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a club</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM clubs")) {
		echo "<p>There are currently no clubs in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Club List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database, select only league clubs

		$db->Query("SELECT * FROM clubs WHERE LeagueID = 3 ORDER BY ClubName");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$cn = htmlentities(stripslashes($db->data['ClubName']));
			$ca = htmlentities(stripslashes($db->data['ClubActive']));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			if($ca != "0") {
			echo "	<td align=\"left\">$cn</td>\n";
			} else {
			echo "	<td align=\"left\">$cn <b><font color=\"red\">(not active)</font></b></td>\n";
			}
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['ClubID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['ClubID'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a club</td>\n";
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
	echo "<p>enter the club name<br><input type=\"text\" name=\"ClubName\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the club website address<br><input type=\"text\" name=\"ClubURL\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the club color<br><input type=\"text\" name=\"ClubColour\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<input type=\"checkbox\" name=\"ClubActive\" value=\"1\"> is this club active?</p>\n";

	echo "<p>select cricket ground this team belongs to<br><select name=\"GroundID\">\n";
	echo "	<option value=\"\">Select Cricket Ground</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM grounds")) {
		$db->Query("SELECT * FROM grounds ORDER BY GroundID");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			echo "<option value=\"" . $db->data['GroundID'] . "\">" . $db->data['GroundName'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

	echo "<p><input type=\"submit\" value=\"add club\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";


	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$ClubName,$ClubURL,$ClubColour,$ClubActive,$GroundID)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$cn = addslashes(trim($ClubName));
	$ur = addslashes(trim($ClubURL));
	$cc = addslashes(trim($ClubColour));
	$ca = addslashes(trim($ClubActive));
	$gr = addslashes(trim($GroundID));

	// check for duplicates

	if ($db->Exists("SELECT * FROM clubs WHERE LeagueID = 3 AND ClubName='$cn'")) {
		echo "<p>That club already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO clubs (ClubName,ClubURL,ClubColour,ClubActive,GroundID,LeagueID) VALUES ('$cn','$ur','$cc','$ca','$gr',3)");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new club</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another club</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to clubs list</a></p>\n";
	} else {
		echo "<p>The club could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to clubs list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$club = htmlentities(stripslashes($db->QueryItem("SELECT ClubName FROM clubs WHERE ClubID=$id")));

	// output

	echo "<p>Are you sure you wish to delete the club</p>\n";
	echo "<p><b>$club</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that club.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM clubs WHERE ClubID=$id");
		echo "<p>You have now deleted that club.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the clubs listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all grounds
	$db->Query("SELECT * FROM grounds ORDER BY GroundID");
	for ($g=0; $g<$db->rows; $g++) {
		$db->GetRow($g);
        $db->BagAndTag();
		$grounds[$db->data['GroundID']] = $db->data['GroundName'];
	}

	// query database

	$db->QueryRow("SELECT * FROM clubs WHERE ClubID=$id");

	// setup variables

	$cn = htmlentities(stripslashes($db->data['ClubName']));
	$ur = htmlentities(stripslashes($db->data['ClubURL']));
	$cc = htmlentities(stripslashes($db->data['ClubColour']));
	$ca = htmlentities(stripslashes($db->data['ClubActive']));

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Club</td>\n";
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
	echo "<p>enter the club name<br><input type=\"text\" name=\"ClubName\" size=\"40\" maxlength=\"255\" value=\"$cn\"></p>\n";
	echo "<p>enter the club website<br><input type=\"text\" name=\"ClubURL\" size=\"40\" maxlength=\"255\" value=\"$ur\"></p>\n";
	echo "<p>enter the club color<br><input type=\"text\" name=\"ClubColour\" size=\"40\" maxlength=\"255\" value=\"$cc\"></p>\n";
	echo "<input type=\"checkbox\" name=\"ClubActive\" value=\"1\"" . ($ca==1?" checked":"") . "> is this club active?</p>\n";

	echo "<p>select cricket ground this team belongs to<br>\n";
	echo "<select name=\"GroundID\">\n";
	echo "	<option value=\"\">Select Cricket Ground</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($grounds as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['GroundID'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

	echo "<p><input type=\"submit\" value=\"edit clubs\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$ClubName,$ClubURL,$ClubColour,$ClubActive,$GroundID)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$cn = addslashes(trim($ClubName));
	$ur = addslashes(trim($ClubURL));
	$cc = addslashes(trim($ClubColour));
	$ca = addslashes(trim($ClubActive));
	$gr = addslashes(trim($GroundID));

	// query database

	$db->Update("UPDATE clubs SET ClubName='$cn',ClubURL='$ur',ClubColour='$cc',ClubActive='$ca',GroundID='$gr', LeagueID=3 WHERE ClubID=$id");
		echo "<p>You have now updated that club.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the clubs listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $cn some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"../uploadphotos/clubs/$picture")) {
		echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
		unlink($userpic);
		return;
	}
	unlink($userpic);
	$setpic = ",picture='$picture'";
} else {
	$picture = "";
	$setpic = "";
}

// main program

if (!$USER['flags'][$f_clubs_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Tennis Clubs Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$ClubName,$ClubURL,$ClubColour,$ClubActive,$GroundID);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$ClubName,$ClubURL,$ClubColour,$ClubActive,$GroundID);
	break;
default:
	show_main_menu($db);
	break;
}

?>
