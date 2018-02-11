<?php

//------------------------------------------------------------------------------
// Site Control History Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a team</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM teams")) {
		echo "<p>There are currently no teams in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Team List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamName");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$tn = htmlentities(stripslashes($db->data['TeamName']));
			$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
			$tc = htmlentities(stripslashes($db->data['TeamActive']));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$ta</td>\n";
			if($tc != "0") {
			echo "	<td align=\"left\">$tn";
			} else {
			echo "	<td align=\"left\">$tn <b><font color=\"red\">(not active)</font></b>";
			}
			if ($db->data['picture'] != "" ) echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">&nbsp;";
			echo "</td>\n";
//			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['TeamID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a //href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['TeamID'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";

			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['TeamID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";

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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a team</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";

	echo "<p>select club this team belongs to<br><select name=\"ClubID\">\n";
	echo "	<option value=\"\">Select Parent Club</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM clubs")) {
		$db->Query("SELECT * FROM clubs WHERE LeagueID = 1 ORDER BY ClubName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['ClubID'] . "\">" . $db->data['ClubName'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<p>enter the team name<br><input type=\"text\" name=\"TeamName\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the team abbreviation<br><input type=\"text\" name=\"TeamAbbrev\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the team website address<br><input type=\"text\" name=\"TeamURL\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the team color<br><input type=\"text\" name=\"TeamColour\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<input type=\"checkbox\" name=\"TeamActive\" value=\"1\"> is this team active?</p>\n";
	echo "<p>enter the team description<br><textarea name=\"TeamDesc\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";	
	echo "<p>upload a team photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 380 pixels wide\n";
	echo "<li>only GIF and JPG files only please.</p>\n";
	echo "<p><input type=\"submit\" value=\"add team\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";


	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$ClubID,$TeamName,$TeamAbbrev,$TeamURL,$TeamColour,$TeamActive,$TeamDesc,$picture)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$cl = addslashes(trim($ClubID));
	$tn = addslashes(trim($TeamName));
	$ta = addslashes(trim($TeamAbbrev));
	$ur = addslashes(trim($TeamURL));
	$tc = addslashes(trim($TeamColour));
	$tv = addslashes(trim($TeamActive));
	$td = addslashes(trim($TeamDesc));

	$pa = eregi_replace("\r","",$photo);


	// check for duplicates

	if ($db->Exists("SELECT * FROM teams WHERE TeamName='$tn'")) {
		echo "<p>That teams already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO teams (ClubID,LeagueID,TeamName,TeamAbbrev,TeamURL,TeamColour,TeamActive,TeamDesc,picture) VALUES ('$cl',1,'$tn','$ta','$ur','$tc','$tv','$td','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new team</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another team</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to teams list</a></p>\n";
	} else {
		echo "<p>The team could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to teams list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$team = htmlentities(stripslashes($db->QueryItem("SELECT TeamName FROM teams WHERE TeamID=$id")));

	// output

	echo "<p>Are you sure you wish to delete the team</p>\n";
	echo "<p><b>$team</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that team.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM teams WHERE TeamID=$id");
		echo "<p>You have now deleted that team.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the teams listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all clubs
	$db->Query("SELECT * FROM clubs WHERE LeagueID = 1 ORDER BY ClubName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
        $db->BagAndTag();
		$clubs[$db->data['ClubID']] = $db->data['ClubName'];
	}

	// query database

	$db->QueryRow("SELECT * FROM teams WHERE LeagueID = 1 AND TeamID=$id");

	// setup variables

	$tn = htmlentities(stripslashes($db->data['TeamName']));
	$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
	$ur = htmlentities(stripslashes($db->data['TeamURL']));
	$tc = htmlentities(stripslashes($db->data['TeamColour']));
	$tv = htmlentities(stripslashes($db->data['TeamActive']));
	$td = htmlentities(stripslashes($db->data['TeamDesc']));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Team</td>\n";
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

	echo "<p>select club this team belongs to<br>\n";
	echo "<select name=\"ClubID\">\n";
	echo "	<option value=\"\">Select Parent Club</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($clubs as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['ClubID'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

	echo "<p>enter the team name<br><input type=\"text\" name=\"TeamName\" size=\"40\" maxlength=\"255\" value=\"$tn\"></p>\n";
	echo "<p>enter the team abbreviation<br><input type=\"text\" name=\"TeamAbbrev\" size=\"40\" maxlength=\"255\" value=\"$ta\"></p>\n";
	echo "<p>enter the team website<br><input type=\"text\" name=\"TeamURL\" size=\"40\" maxlength=\"255\" value=\"$ur\"></p>\n";
	echo "<p>enter the team color<br><input type=\"text\" name=\"TeamColour\" size=\"40\" maxlength=\"255\" value=\"$tc\"></p>\n";
	echo "<input type=\"checkbox\" name=\"TeamActive\" value=\"1\"" . ($tv==1?" checked":"") . "> is this team active?</p>\n";
	echo "<p>enter the team description<br><textarea name=\"TeamDesc\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$td</textarea></p>\n";
	if ($db->data['picture']) {
		echo "<p>current team photo</p>\n";
		echo "<p><img src=\"../uploadphotos/teams/" . $db->data['picture'] . "\"></p>\n";
		echo "<p>upload a team photo (if you want to change the current one)";
	} else {
		echo "<p>upload a team photo";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 380 pixels wide\n";
	echo "<li>only GIF and JPG files only please.</p>\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit teams\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$ClubID,$TeamName,$TeamAbbrev,$TeamURL,$TeamColour,$TeamActive,$TeamDesc,$setpic)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$cl = addslashes(trim($ClubID));
	$tn = addslashes(trim($TeamName));
	$ta = addslashes(trim($TeamAbbrev));
	$ur = addslashes(trim($TeamURL));
	$tc = addslashes(trim($TeamColour));
	$tv = addslashes(trim($TeamActive));
	$td = addslashes(trim($TeamDesc));
	$pa = eregi_replace("\r","",$photo);

// query database

	$db->Update("UPDATE teams SET ClubID='$cl',LeagueID=1,TeamName='$tn',TeamAbbrev='$ta',TeamURL='$ur',TeamColour='$tc',TeamActive='$tv',TeamDesc='$td'$setpic WHERE TeamID=$id");
		echo "<p>You have now updated that teams.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the teams listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $tn some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!
if ($_FILES['userpic']['name'] != "") {
	
  $uploaddir1 = "../uploadphotos/teams/";
  $basename1 = basename($_FILES['userpic']['name']);
  $uploadfile1 = $uploaddir1 . $basename1;
  
  if (move_uploaded_file($_FILES['userpic']['tmp_name'], $uploadfile1)) {
    $setpic = ",picture='$basename1'";
  } else {
    echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
  }
}
else
{
  $picture = "";
  $setpic = "";
}

// main program

if (!$USER[flags][$f_teams_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Teams Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$ClubID,$TeamName,$TeamAbbrev,$TeamURL,$TeamColour,$TeamActive,$TeamDesc,$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$ClubID,$TeamName,$TeamAbbrev,$TeamURL,$TeamColour,$TeamActive,$TeamDesc,$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
