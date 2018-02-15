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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a ground</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM grounds")) {
		echo "<p>There are currently no grounds in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Ground List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM grounds WHERE LeagueID = 1 ORDER BY GroundName");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$gn = htmlentities(stripslashes($db->data[GroundName]));
			$ga = htmlentities(stripslashes($db->data[GroundActive]));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			if($ga != "0") {
			echo "	<td align=\"left\">$gn";
			} else {
			echo "	<td align=\"left\">$gn <b><font color=\"red\">(not active)</font></b>";
			}
			if ($db->data['picture'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">";
			echo "  </td>\n";
//			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[GroundID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a //href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data[GroundID] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";

			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[GroundID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";

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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a ground</td>\n";
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
	echo "<p>enter the ground name<br><input type=\"text\" name=\"GroundName\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the ground abbreviation<br><input type=\"text\" name=\"Groundabbrev\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the city the ground is located<br><input type=\"text\" name=\"GroundLoc\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the ground zip code<br><input type=\"text\" name=\"GroundZip\" size=\"50\" maxlength=\"5\"></p>\n";
	echo "<p>enter the ground directions<br><textarea name=\"GroundDirections\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";

	echo "<p>enter the ground description<br><textarea name=\"description\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";

	echo "<p>\n";
	echo "<select name=\"parking\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Parking?</p>\n";

	echo "<p>\n";
	echo "<select name=\"coveredparking\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Covered Parking?</p>\n";

	echo "<p>\n";
	echo "<select name=\"shelter\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Shelter</p>\n";

	echo "<p>\n";
	echo "<select name=\"handicapped\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Handicapped Accessible?</p>\n";

	echo "<p>\n";
	echo "<select name=\"stadiumseating\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Stadium Seating?</p>\n";

	echo "<p>\n";
	echo "<select name=\"restrooms\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Restrooms?</p>\n";

	echo "<p>\n";
	echo "<select name=\"conveniencestore\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Convenience Store?</p>\n";

	echo "<p>\n";
	echo "<select name=\"drinkingwater\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Drinking water?</p>\n";

	echo "<p>\n";
	echo "<select name=\"publictransport\">\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Public transport?</p>\n";

	echo "<input type=\"checkbox\" name=\"GroundActive\" value=\"1\"> is this ground active?</p>\n";
	echo "<p>upload a ground photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 380 pixels wide\n";
	echo "<li>only GIF and JPG files only please.</p>\n";
	echo "<p><input type=\"submit\" value=\"add ground\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";


	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$GroundName,$GroundAbbrev,$GroundLoc,$GroundDirections,$GroundZip,$description,$parking,$coveredparking,$shelter,$handicapped,$stadiumseating,$restrooms,$conveniencestore,$drinkingwater,$publictransport,$GroundActive,$picture)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$gn = addslashes(trim($GroundName));
	$ga = addslashes(trim($GroundAbbrev));
	$gl = addslashes(trim($GroundLoc));
	$gz = addslashes(trim($GroundZip));
	$gd = addslashes(trim($GroundDirections));
	$cc = addslashes(trim($GroundColour));
	$ga = addslashes(trim($GroundActive));
	$de = addslashes(trim($description));
	$pa = addslashes(trim($parking));
	$cp = addslashes(trim($coveredparking));
	$sh = addslashes(trim($shelter));
	$ha = addslashes(trim($handicapped));
	$ss = addslashes(trim($stadiumseating));
	$rr = addslashes(trim($restrooms));
	$cs = addslashes(trim($conveniencestore));
	$dw = addslashes(trim($drinkingwater));
	$pt = addslashes(trim($publictransport));
	$ph = eregi_replace("\r","",$photo);


	// check for duplicates

	if ($db->Exists("SELECT * FROM grounds WHERE GroundName='$gn'")) {
		echo "<p>That grounds already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO grounds (LeagueID,GroundName,GroundAbbrev,GroundLoc,GroundDirections,GroundZip,description,parking,coveredparking,shelter,handicapped,stadiumseating,restrooms,conveniencestore,drinkingwater,publictransport,GroundActive,picture) VALUES (1,'$gn','$ga','$gl','$gd','$gz','$de','$pa','$cp','$sh','$ha','$ss','$rr','$cs','$dw','$pt','$ga','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new ground</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another ground</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to grounds list</a></p>\n";
	} else {
		echo "<p>The ground could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to grounds list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$ground = htmlentities(stripslashes($db->QueryItem("SELECT GroundName FROM grounds WHERE GroundID=$id")));

	// output

	echo "<p>Are you sure you wish to delete the ground</p>\n";
	echo "<p><b>$ground</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that ground.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM grounds WHERE GroundID=$id");
		echo "<p>You have now deleted that ground.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the grounds listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query database

	$db->QueryRow("SELECT * FROM grounds WHERE GroundID=$id");

	// setup variables

	$gn = htmlentities(stripslashes($db->data[GroundName]));
	$gb = htmlentities(stripslashes($db->data[GroundAbbrev]));
	$gl = htmlentities(stripslashes($db->data[GroundLoc]));
	$gd = htmlentities(stripslashes($db->data[GroundDirections]));
	$gz = htmlentities(stripslashes($db->data[GroundZip]));
	$ga = htmlentities(stripslashes($db->data[GroundActive]));
	$de = htmlentities(stripslashes($db->data[description]));
	$pa = htmlentities(stripslashes($db->data[parking]));
	$cp = htmlentities(stripslashes($db->data[coveredparking]));
	$sh = htmlentities(stripslashes($db->data[shelter]));
	$ha = htmlentities(stripslashes($db->data[handicapped]));
	$ss = htmlentities(stripslashes($db->data[stadiumseating]));
	$rr = htmlentities(stripslashes($db->data[restrooms]));
	$cs = htmlentities(stripslashes($db->data[conveniencestore]));
	$dw = htmlentities(stripslashes($db->data[drinkingwater]));
	$pt = htmlentities(stripslashes($db->data[publictransport]));


      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Ground</td>\n";
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
	echo "<p>enter the ground name<br><input type=\"text\" name=\"GroundName\" size=\"50\" maxlength=\"255\" value=\"$gn\"></p>\n";
	echo "<p>enter the ground abbreviation<br><input type=\"text\" name=\"GroundAbbrev\" size=\"50\" maxlength=\"255\" value=\"$gb\"></p>\n";
	echo "<p>enter the city the ground is located in<br><input type=\"text\" name=\"GroundLoc\" size=\"50\" maxlength=\"255\" value=\"$gl\"></p>\n";
	echo "<p>enter the ground zip<br><input type=\"text\" name=\"GroundZip\" size=\"50\" maxlength=\"255\" value=\"$gz\"></p>\n";
	echo "<p>enter the ground directions<br><textarea name=\"GroundDirections\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$gd</textarea></p>\n";
	echo "<p>enter the ground description<br><textarea name=\"description\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$de</textarea></p>\n";

	echo "<p>\n";
	echo "<select name=\"parking\">\n";
	echo "	<option value=\"$pa\">$pa</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Parking?</p>\n";

	echo "<p>\n";
	echo "<select name=\"coveredparking\">\n";
	echo "	<option value=\"$cp\">$cp</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Covered Parking?</p>\n";

	echo "<p>\n";
	echo "<select name=\"shelter\">\n";
	echo "	<option value=\"$sh\">$sh</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Shelter</p>\n";

	echo "<p>\n";
	echo "<select name=\"handicapped\">\n";
	echo "	<option value=\"$ha\">$ha</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Handicapped Accessible?</p>\n";

	echo "<p>\n";
	echo "<select name=\"stadiumseating\">\n";
	echo "	<option value=\"$ss\">$ss</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Stadium Seating?</p>\n";

	echo "<p>\n";
	echo "<select name=\"restrooms\">\n";
	echo "	<option value=\"$rr\">$rr</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Restrooms?</p>\n";

	echo "<p>\n";
	echo "<select name=\"conveniencestore\">\n";
	echo "	<option value=\"$cs\">$cs</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Convenience Store?</p>\n";

	echo "<p>\n";
	echo "<select name=\"drinkingwater\">\n";
	echo "	<option value=\"$dw\">$dw</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Drinking water?</p>\n";

	echo "<p>\n";
	echo "<select name=\"publictransport\">\n";
	echo "	<option value=\"$pt\">$pt</option>\n";
	echo "	<option value=\"\">==or choose==</option>\n";
	echo "	<option value=\"Yes\">yes</option>\n";
	echo "	<option value=\"No\">no</option>\n";
	echo "	<option value=\"Limited\">limited</option>\n";
	echo "</select>&nbsp;Public transport?</p>\n";



	echo "<input type=\"checkbox\" name=\"GroundActive\" value=\"1\"" . ($ga==1?" checked":"") . "> is this ground active?</p>\n";

	if ($db->data['picture']) {
		echo "<p>current ground photo</p>\n";
		echo "<p><img src=\"../uploadphotos/grounds/" . $db->data['picture'] . "\"></p>\n";
		echo "<p>upload a ground photo (if you want to change the current one)";
	} else {
		echo "<p>upload a ground photo";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 380 pixels wide\n";
	echo "<li>only GIF and JPG files only please.</p>\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit grounds\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$GroundName,$GroundAbbrev,$GroundLoc,$GroundDirections,$GroundZip,$description,$parking,$coveredparking,$shelter,$handicapped,$stadiumseating,$restrooms,$conveniencestore,$drinkingwater,$publictransport,$GroundActive,$setpic)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$gn = addslashes(trim($GroundName));
	$gb = addslashes(trim($GroundAbbrev));
	$gl = addslashes(trim($GroundLoc));
	$gz = addslashes(trim($GroundZip));
	$gd = addslashes(trim($GroundDirections));
	$ga = addslashes(trim($GroundActive));
	$de = addslashes(trim($description));
	$pa = addslashes(trim($parking));
	$cp = addslashes(trim($coveredparking));
	$sh = addslashes(trim($shelter));
	$ha = addslashes(trim($handicapped));
	$ss = addslashes(trim($stadiumseating));
	$rr = addslashes(trim($restrooms));
	$cs = addslashes(trim($conveniencestore));
	$dw = addslashes(trim($drinkingwater));
	$pt = addslashes(trim($publictransport));
	$ph = eregi_replace("\r","",$photo);

	// query database

	$db->Update("UPDATE grounds SET LeagueID=1,GroundName='$gn',GroundAbbrev='$gb',GroundLoc='$gl',GroundDirections='$gd',GroundZip='$gz',description='$de',parking='$pa',coveredparking='$cp',shelter='$sh',handicapped='$ha',stadiumseating='$ss',restrooms='$rr',conveniencestore='$cs',drinkingwater='$dw',publictransport='$pt',GroundActive='$ga'$setpic WHERE GroundID=$id");
		echo "<p>You have now updated that ground.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the grounds listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $gn some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"../uploadphotos/grounds/$picture")) {
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

if (!$USER['flags'][$f_grounds_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Grounds Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$GroundName,$GroundAbbrev,$GroundLoc,$GroundDirections,$GroundZip,$description,$parking,$coveredparking,$shelter,$handicapped,$stadiumseating,$restrooms,$conveniencestore,$drinkingwater,$publictransport,GroundActive,$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$GroundName,$GroundAbbrev,$GroundLoc,$GroundDirections,$GroundZip,$description,$parking,$coveredparking,$shelter,$handicapped,$stadiumseating,$restrooms,$conveniencestore,$drinkingwater,$publictransport,$GroundActive,$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
