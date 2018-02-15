<?php

//------------------------------------------------------------------------------
// Site Control Image Gallery Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID;

	echo "<p>Manage the names of the galleries.</p>\n";
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a new gallery</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM demogallery")) {
		echo "<p>There are currently no galleries in the database.</p>\n";
		return;
	} else {

			// output header, not to be included in for loop

			echo "<table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">\n";
			echo "<tr class=\"trtop\">\n";
			echo "   <td align=\"left\"><span class=\"trtopfont\"><b>Gallery Title</b></span></td>\n";
			echo "   <td align=\"left\"><span class=\"trtopfont\"><b>Gallery Date</b></span></td>\n";
			echo "   <td align=\"right\"><span class=\"trtopfont\">Modify</span></td>\n";
			echo "</tr>\n";

		// query the database

		$db->Query("SELECT * FROM demogallery ORDER BY my");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup the variables

			$t = htmlentities(stripslashes($db->data['title']));
			$my = htmlentities(stripslashes($db->data[my]));

			// output

			echo "<tr class=\"trbottom\">\n";
			echo "	<td align=\"left\">$t</td>\n";
			echo "	<td align=\"left\">$my</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\">edit</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\">delete</a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	}
}


function add_category_form($db)
{
	global $content,$action,$SID;

	echo "<p>Add a new gallery.</p>\n";
	echo "<form action=\"main.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<p>enter the name of the gallery<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the numerical date<i>(yyyy-mm-dd)</i> for sorting purposes<br><input type=\"text\" name=\"my\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p><input type=\"submit\" value=\"add gallery\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
}


function do_add_category($db,$title,$my)
{
	global $content,$action,$SID;

	// setup variables

	$t = addslashes(trim($title));
	$my = addslashes(trim($my));

	// check for duplicates

	if ($db->Exists("SELECT * FROM demogallery WHERE title='$t'")) {
		echo "<p>That gallery already exists in the database.</p>\n";
		return;
	}

	// all okay query database

	$db->Insert("INSERT INTO demogallery (title, my) VALUES ('$t','$my')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new gallery. Now <a href=\"main.php?SID=$SID&action=$imagephotos\">add some photo's</a> to the gallery.</p>\n";
		echo "<p>&gt&gt; <a href=\"main.php?SID=$SID&action=imagephotos&do=dadd\">add photo's to the gallery</a></p>\n";
		echo "<p>&lt&lt; <a href=\"main.php?SID=$SID&action=$action\">return to gallery list</a></p>\n";

	} else {
		echo "<p>The gallery could not be added to the database at this time.</p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID;

	// query database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM demogallery WHERE id=$id")));
	echo "<p>Are you sure you wish to delete the gallery titled:</p>\n";
	echo "<p><b>$title</b> <i>(Go to <a href=\"main.php?SID=$SID&action=imagephotos\">photoadmin</a> and make sure no photo's exist in this gallery)</i></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that gallery.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM demogallery WHERE id=$id");
		echo "<p>You have now deleted that gallery.</p>\n";
	}
	echo "<p>&lt&lt; <a href=\"main.php?SID=$SID&action=$action\">return to gallery list</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID;

	// query the database for details

	$db->QueryRow("SELECT * FROM demogallery WHERE id=$id");

	// setup variables

	$t  = stripslashes($db->data['title']);
	$my  = stripslashes($db->data[my]);
	$th = htmlentities(stripslashes($db->data['title']));

	echo "<p>Edit the gallery.</p>\n";
	echo "<form action=\"main.php\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<p>enter the name of the gallery<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\" value=\"$th\"></p>\n";
	echo "<p>enter the numerical date<i>(yyyy-mm-dd)</i> for sorting purposes<br><input type=\"text\" name=\"my\" size=\"25\" maxlength=\"255\" value=\"$my\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit gallery\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
}


function do_update_category($db,$id,$title,$my,$old)
{
	global $content,$action,$SID;

	// setup variables

	$t = addslashes(trim($title));
	$my = addslashes(trim($my));
	$o = addslashes(trim($old));

	// check for duplicates

	if (strtolower($o) != strtolower($t)) {
		if ($db->Exists("SELECT * FROM demogallery WHERE title='$t'")) {
			echo "<p>That gallery already exists in the database.</p>\n";
			echo "<p>&lt&lt; <a href=\"main.php?SID=$SID&action=$action\">return to gallery list</a></p>\n";
			return;
		}
	}

	// do update

	$db->Update("UPDATE demogallery SET title='$t',my='$my' WHERE id=$id");
	if ($db->a_rows != -1) {
		echo "<p>You have now updated that gallery.</p>\n";
		echo "<p>&lt&lt; <a href=\"main.php?SID=$SID&action=$action\">return to gallery list</a></p>\n";
		echo "<p>&gt&gt; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
	} else {
		echo "<p>That gallery could not be changed at this time.</p>\n";
		echo "<p>&lt&lt; <a href=\"main.php?SID=$SID&action=$action\">return to gallery list</a></p>\n";
	}
}


// main program

if (!$USER['flags'][$f_image_gallery]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Image Gallery Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$title,$my);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$title,$my,$old);
	break;
default:
	show_main_menu($db);
	break;
}

?>
