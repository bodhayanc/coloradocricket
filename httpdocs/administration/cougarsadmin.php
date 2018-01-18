<?php

//------------------------------------------------------------------------------
// Site Control Cougars Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a cougars article</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM cougars")) {
		echo "<p>There are currently no cougars articles in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Cougars Article List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM cougars ORDER BY id DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			echo "	<td align=\"left\">$t</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
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
	global $content,$action,$SID;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a cougars article</td>\n";
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
	echo "<p>enter the name of the cougars<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the name of the author<br><input type=\"text\" name=\"author\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the press release<br><textarea name=\"article\" cols=\"40\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p>upload a  photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 200 pixels wide x 300 pixels high\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</p>\n";
	echo "<p><input type=\"submit\" value=\"add cougars\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$uid,$title,$author,$article,$picture)
{
	global $content,$action,$SID;

	// setup variables

	$t = addslashes(trim($title));
	$au = addslashes(trim($author));
	$a = addslashes(trim($article));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());
	$pa = eregi_replace("\r","",$photo);

	// check for duplicates

	if ($db->Exists("SELECT * FROM cougars WHERE title='$t'")) {
		echo "<p>That cougars already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO cougars (added,user,title,author,article,picture) VALUES ('$d','$uid','$t','$au','$a','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new cougars article.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another cougars article</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to cougars article list</a></p>\n";
	} else {
		echo "<p>The article could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to cougars article list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID;

	// query the database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM cougars WHERE id=$id")));

	// output

	echo "<p>Are you sure you wish to delete the cougars article titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that cougars article.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM cougars WHERE id=$id");
		echo "<p>You have now deleted that cougars article.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the cougars article listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID;

	// query database

	$db->QueryRow("SELECT * FROM cougars WHERE id=$id");

	// setup variables

	$t  = stripslashes($db->data['title']);
	$th = htmlentities(stripslashes($db->data['title']));
	$au = htmlentities(stripslashes($db->data['author']));
	$a  = htmlentities(stripslashes($db->data['article']));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Cougars Article</td>\n";
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
	echo "<p>enter the name of the cougars<br><input type=\"text\" name=\"title\" size=\"25\"maxlength=\"255\" value=\"$th\"></p>\n";
	echo "<p>enter the name of the author<br><input type=\"text\" name=\"author\" size=\"25\"maxlength=\"255\" value=\"$au\"></p>\n";
	echo "<p>enter the cougars article<br><textarea name=\"article\" cols=\"40\" rows=\"10\" wrap=\"virtual\">$a</textarea></p>\n";
	if ($db->data['picture']) {
		echo "<p>current photo</p>\n";
		echo "<p><img src=\"../uploadphotos/cougars/" . $db->data['picture'] . "\"></p>\n";
		echo "<p>upload a photo (if you want to change the current one)";
	} else {
		echo "<p>upload a photo";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 200 pixels wide x 300 pixels high\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</p>\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit cougars\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$title,$author,$article,$setpic)
{
	global $content,$action,$SID;

	// setup variables

// setup the variables

	$t = addslashes(trim($title));
	$pa = eregi_replace("\r","",$photo);
	$o = addslashes(trim($old));
	$au = addslashes(trim($author));

// prevent the need for using escape sequences with apostrophe's

	$a = eregi_replace("\r","",$article);
	$a = addslashes(trim($a));

	// query database

	$db->Update("UPDATE cougars SET title='$t',author='$au',article='$a'$setpic WHERE id=$id");
		echo "<p>You have now updated that cougars article.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the cougars article listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"../uploadphotos/cougars/$picture")) {
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

if (!$USER[flags][$f_cougars_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"14px\"><b>Site Cougars Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$USER[email],$title,$author,$article,$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$title,$author,$article,$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
