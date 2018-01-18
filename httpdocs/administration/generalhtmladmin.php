<?php

//------------------------------------------------------------------------------
// Site Control HTML Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// If you wish to turn on HTML adding. I prefer to not do this since it requires a new file be
	// created in includes/generalhtml/* for the HTML item added.
	// echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a new html</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM generalhtml")) {
		echo "<p>There are currently no html in the database.</p>\n";
		return;
	} else {

			// output header, not to be included in for loop

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">\n";
			echo "<tr class=\"trtop\">\n";
			echo "   <td align=\"left\" valign=\"top\"><span class=\"trtopfont\"><b>ID</b></span></td>\n";
			echo "	<td align=\"left\" valign=\"top\"><span class=\"trtopfont\"><b>HTML Title</b></span></td>\n";
			echo "	<td align=\"right\" valign=\"top\"><span class=\"trtopfont\">Modify</span></td>\n";
			echo "</tr>\n";

		// query the database

		$db->Query("SELECT * FROM generalhtml ORDER BY id");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup the variables

			$t  = htmlentities(stripslashes($db->data[title]));
			$id = htmlentities(stripslashes($db->data[id]));

			// output

			echo "<tr class=\"trbottom\">\n";
			echo "   <td align=\"left\" valign=\"top\">$id.</td>\n";
			echo "	<td align=\"left\" valign=\"top\">$t</td>\n";
			echo "	<td align=\"right\" valign=\"top\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[id] . "\">edit html</a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>Add a new html. <a href=\"htmlhelp.php\" target=\"_new\">Click here for html formatting help.</a></p>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<p>enter the title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>upload a file<i>(not required)</i><br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p>enter the info<br><textarea name=\"generalhtml\" cols=\"60\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"add html\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

}


function do_add_category($db,$title,$generalhtml,$picture)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup the variables

	$g = eregi_replace("\r","",$generalhtml);
	$g = addslashes(trim($g));
	$t = addslashes(trim($title));

	// check for duplicates

	if ($db->Exists("SELECT * FROM generalhtml WHERE title='$t'")) {
		echo "<p>That html already exists in the database.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to html list</a></p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO generalhtml (title,generalhtml,picture) VALUES ('$t','$g','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new html.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another html item</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to html list</a></p>\n";
	} else {
		echo "<p>The html could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to html list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM generalhtml WHERE id=$id")));
	echo "<p>Are you sure you wish to delete the html titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that html.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM generalhtml WHERE id=$id");
		echo "<p>You have now deleted that html.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the html listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database

	$db->QueryRow("SELECT * FROM generalhtml WHERE id=$id");

	// setup the variables

	$t  = stripslashes($db->data[title]);
	$th = htmlentities(stripslashes($db->data[title]));
	$gh = htmlentities(stripslashes($db->data[generalhtml]));

	echo "<p>Edit the html. <a href=\"htmlhelp.php\" target=\"_new\">Click here for html formatting help.</a></p>\n";
	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<p>enter the title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\" value=\"$th\"></p>\n";
	if ($db->data[picture]) {
		echo "<p>current file</p>\n";
		echo "<p>" . $db->data[picture] . "</p>\n";
		echo "<p>upload a file (if you want to change the current one)";
	} else {
		echo "<p>upload a file <i>(not required)</i>";
	}
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";

	echo "<p>enter the html info<br><textarea name=\"generalhtml\" cols=\"60\" rows=\"15\" wrap=\"virtual\">$gh</textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"edit html\"></p>\n";
	echo "</form>\n";
}


function do_update_category($db,$id,$title,$old,$generalhtml,$setpic)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup the variables

	$t = addslashes(trim($title));
	$o = addslashes(trim($old));
	$pa = eregi_replace("\r","",$photo);
	$g = eregi_replace("\r","",$generalhtml);
	$g = addslashes(trim($g));

	// check for duplicates

	if ($o != $t) {
		if ($db->Exists("SELECT * FROM generalhtml WHERE title='$t'")) {
			echo "<p>That html already exists in the database.</p>\n";
			echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">go back to html listing</a></p>\n";
			return;
		}
	}
	// all okay,
	$db->Update("UPDATE generalhtml SET title='$t',generalhtml='$g'$setpic WHERE id=$id");
	if ($db->a_rows != -1) {
		echo "<p>You have now updated that html. </p><p>You should now see the html on the homepage.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">go back to html listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">edit $t some more</a></p>\n";
	} else {
		echo "<p>That html could not be changed at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">go back to html listing</a></p>\n";
	}
}

// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"../uploadphotos/generalhtml/$picture")) {
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

if (!$USER[flags][$f_generalhtml_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"header\"><b>General HTML Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$title,$generalhtml,$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$title,$old,$generalhtml,$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
