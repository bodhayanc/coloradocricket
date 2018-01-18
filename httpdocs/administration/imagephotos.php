<?php

//------------------------------------------------------------------------------
// Site Control Photos Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$USER;

	echo "<p>Manage all the photos within each gallery from this easy to use panel. Simply add/edit/delete photos whenever you desire.</p>\n";
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=dadd\">Add a new photo</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM demophotos")) {
		echo "<p>There are currently no photos in the database.</p>\n";
		return;
	} else {

	// get the gallery details

		$db->Query("SELECT * FROM demogallery ORDER BY id");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$gallery[$db->data[id]] = $db->data[title];
		}

		// output gallery header information

		echo "<table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">\n";
		for ($i=1; $i<=count($gallery); $i++) {
			echo "<tr class=\"trtop\">\n";
			echo "   <td align=\"left\" width=\"5%\"><span class=\"trtopfont\"><b>#</b></span></td>\n";
			echo "	<td align=\"left\" width=\"45%\"><span class=\"trtopfont\"><b>" . htmlentities(stripslashes($gallery[$i])) . " photos</b></span></td>\n";
			echo "	<td align=\"left\" width=\"25%\"><span class=\"trtopfont\"><b>Date</b></span></td>\n";
			echo "	<td align=\"right\" width=\"25%\"><span class=\"trtopfont\">Modify</span></td>\n";
			echo "</tr>\n";

			// check for gallery with no photos

			if (!$db->Exists("SELECT * FROM demophotos WHERE gallery=$i")) {
				echo "<tr class=\"trbottom\">\n";
				echo "	<td width=\"5%\"><p>&nbsp;</p></td>\n";
				echo "	<td width=\"45%\">No photos for this gallery.</td>\n";
				echo "	<td width=\"25%\">&nbsp;</td>\n";
				echo "	<td width=\"25%\">&nbsp;</td>\n";
				echo "</tr>\n";
			} else {

				// query the database

				$db->Query("SELECT * FROM demophotos WHERE gallery=$i ORDER BY setnum");
				for ($x=0; $x<$db->rows; $x++) {
					$db->GetRow($x);

					// setup variables

					$t = htmlentities(stripslashes($db->data[title]));
					$num = htmlentities(stripslashes($db->data[setnum]));
					$d = htmlentities(stripslashes($db->data[date]));

					// output

					echo "<tr class=\"trbottom\">\n";
					echo "   <td align=\"left\" width=\"5%\">$num</td>\n";
					echo "	<td align=\"left\" width=\"45%\">$t</td>\n";
					echo "	<td align=\"left\" width=\"25%\">$d</td>\n";
					echo "	<td align=\"right\" width=\"25%\">";
					echo "<a href=\"main.php?SID=$SID&action=$action&do=dedit&id=" . $db->data[id] . "\">edit</a>";
					echo " | ";
					echo "<a href=\"main.php?SID=$SID&action=$action&do=ddel&id=" . $db->data[id] . "\">delete</a></td>\n";
					echo "</tr>\n";
				}
			}
		}
		echo "</table>\n";
	}
}


function add_photo_form($db)
{
	global $content,$action,$SID;

	echo "<p>Add a photo.</p>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"dadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<p><select name=\"gallery\">\n";
	echo "<option value=\"\">Select a gallery</option>\n";
	echo "<option value=\"\">--------------------------</option>\n";

	// get gallery details

	if ($db->Exists("SELECT * FROM demogallery ORDER BY title")) {
		$db->Query("SELECT * FROM demogallery ORDER BY title");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data[id] . "\">" . $db->data[title] . "</option>\n";
		}
	}

	echo "</select></p>\n";
	echo "<p>upload a photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<ul>\n";
	echo "<li>please make sure images have a unique name (eg. imagename001.jpg)<br>\n";
	echo "<li>please save images no larger than 450 pixels wide for landscape<br>\n";
	echo "<li>please save images no larger than 450 pixels high for portrait<br>\n";
	echo "<li>only GIF and JPG files only please.\n";
	echo "</ul>\n";
	echo "<p>upload a thumbnail<br><input type=\"file\" name=\"userpic1\" size=\"40\"></p>\n";
	echo "<ul>\n";
	echo "<li>please make sure images have a unique name (eg. imagename001_thumb.jpg)<br>\n";
	echo "<li>please save images no larger than 100 pixels wide for landscape<br>\n";
	echo "<li>please save images no larger than 100 pixels high for portrait<br>\n";
	echo "<li>only GIF and JPG files only please.\n";
	echo "</ul>\n";
	echo "<p>enter the photo title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter a short description<br><textarea name=\"description\" cols=\"50\" rows=\"10\" wrap=\"virtual\">$de</textarea></p>\n";
	echo "<p>where was it taken?<br><input type=\"text\" name=\"location\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>date taken?<br><input type=\"text\" name=\"date\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the position #<br><input type=\"text\" name=\"setnum\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p><i>1 would be top, 2 next, 3 next, etc...</i></p>\n";
	echo "<p><input type=\"submit\" value=\"add photo\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

}


function do_add_photo($db,$title,$description,$location,$date,$gallery,$picture,$picture1,$setnum)
{
	global $content,$SID,$action;

	// setup variables

	$a = eregi_replace("\r","",$photo);
	$a = addslashes(trim($a));
	$t = addslashes(trim($title));
	$de = addslashes(trim($description));
	$l = addslashes(trim($location));
	$da = addslashes(trim($date));
        $nd = addslashes(trim($numdate));
	$by = addslashes(trim($author));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());

	// query database

	$db->Insert("INSERT INTO demophotos (gallery,title,description,location,date,picture,picture1,setnum) VALUES ($gallery,'$t','$de','$l','$da','$picture','$picture1',$setnum)");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new photo.</p>\n";
		echo "<p>&raquo;<a href=\"main.php?SID=$SID&action=$action&do=dadd\">add another photo</a></p>\n";
		echo "<p>&laquo;<a href=\"main.php?SID=$SID&action=$action\">return to photo list</a></p>\n";

	} else {
		echo "<p>The photo could not be added to the database at this time.</p>\n";
	}
}


function delete_photo_check($db,$id)
{
	global $content,$SID,$action;

	// query database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM demophotos WHERE id=$id")));
	echo "<p>Are you sure you wish to delete the photo titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=ddel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=ddel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_photo($db,$id,$doit)
{
	global $content,$SID,$action;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that photo.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM demophotos WHERE id=$id");
		echo "<p>You have now deleted that photo.</p>\n";
	}
	echo "<p>&laquo;<a href=\"main.php?SID=$SID&action=$action\">return to photo list</a></p>\n";
}


function edit_photo_form($db,$id)
{
	global $content,$action,$SID;

	// get all galleries

	$db->Query("SELECT * FROM demogallery ORDER BY id");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$gallery[$db->data[id]] = $db->data[title];
	}

	// query the database

	$db->QueryRow("SELECT * FROM demophotos WHERE id=$id");

	// setup variables

	$a = htmlentities(stripslashes($db->data[photo]));
	$t = htmlentities(stripslashes($db->data[title]));
	$de = htmlentities(stripslashes($db->data[description]));
	$l = htmlentities(stripslashes($db->data[location]));
	$da = htmlentities(stripslashes($db->data[date]));
	$nd = htmlentities(stripslashes($db->data[numdate]));
	$num = htmlentities(stripslashes($db->data[setnum]));
	$by = htmlentities(stripslashes($db->data[author]));

	echo "<p>Edit a photo.</p>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"dedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<p>Select the gallery</p>\n";
	echo "<p><select name=\"gallery\">\n";
	echo "<option value=\"\">Select a gallery</option>\n";
	echo "<option value=\"\">--------------------------</option>\n";

	// get galleries

	for ($i=1; $i<=count($gallery); $i++) {
		echo "<option value=\"$i\"" . ($i==$db->data[gallery]?" selected":"") . ">" . $gallery[$i] . "</option>\n";
	}

	echo "</select></p>\n";


		if ($db->data[picture]) {
			echo "<p>current photo</p>\n";

			// point to your photos directory

			echo "<p><img src=\"../uploadphotos/" . $db->data[picture] . "\"></p>\n";
			echo "<p>upload a photo (if you want to change the current one)";
		} else {
			echo "<p>upload a photo";
		}


		echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
		echo "<ul>\n";
		echo "<li>please make sure images have a unique name (eg. imagename001.jpg)<br>\n";
		echo "<li>please save images no larger than 450 pixels wide for landscape<br>\n";
		echo "<li>please save images no larger than 450 pixels high for portrait<br>\n";
		echo "<li>only GIF and JPG files only please.\n";
		echo "</ul>\n";

		if ($db->data[picture1]) {
			echo "<p>current thumbnail</p>\n";

			// point to your photos directory

			echo "<p><img src=\"../uploadphotos/" . $db->data[picture1] . "\"></p>\n";
			echo "<p>upload a thumbnail (if you want to change the current one)";
		} else {
			echo "<p>upload a thumbnail";
		}

		echo "<br><input type=\"file\" name=\"userpic1\" size=\"40\"></p>\n";
		echo "<ul>\n";
		echo "<li>please make sure images have a unique name (eg. imagename001_thumb.jpg)<br>\n";
		echo "<li>please save images no larger than 100 pixels wide for landscape<br>\n";
		echo "<li>please save images no larger than 100 pixels high for portrait<br>\n";
		echo "<li>only GIF and JPG files only please.\n";
		echo "</ul>\n";

		echo "<p>enter the photo title<br><input type=\"text\" name=\"title\" size=\"40\" maxlength=\"255\" value=\"$t\"></p>\n";
		echo "<p>enter a short description<br><textarea name=\"description\" cols=\"50\" rows=\"10\" wrap=\"virtual\">$de</textarea></p>\n";
		echo "<p>where was it taken?<br><input type=\"text\" name=\"location\" size=\"40\" maxlength=\"255\" value=\"$l\"></p>\n";
		echo "<p>date taken?<br><input type=\"text\" name=\"date\" size=\"40\" maxlength=\"255\" value=\"$da\"></p>\n";
		echo "<p>enter the position #<br><input type=\"text\" name=\"setnum\" size=\"40\" maxlength=\"255\" value=\"$num\"></p>\n";
		echo "<p><i>1 would be top left, 2 top right, 3 next left, 4 next right, and so on...</i></p>\n";
		echo "<p><input type=\"submit\" value=\"modify photo\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
		echo "</form>\n";
}


function do_update_photo($db,$id,$title,$description,$location,$date,$gallery,$setpic,$setpic1,$setnum)
{
	global $content,$SID,$action,$userfile;

	// set variables

	$a = eregi_replace("\r","",$photo);
	$a = addslashes(trim($a));
	$t = addslashes(trim($title));
	$de = addslashes(trim($description));
	$l = addslashes(trim($location));
	$da = addslashes(trim($date));
	$nd = addslashes(trim($numdate));
	$g = addslashes(trim($gallery));
	$n = addslashes(trim($setnum));

	// query the database

	$db->Update("UPDATE demophotos SET title='$t',description='$de',location='$l',date='$da',gallery=$g$setpic$setpic1,setnum=$n WHERE id=$id");
	echo "<p>You have now updated that photo.</p>\n";
	echo "<p>&laquo;<a href=\"main.php?SID=$SID&action=$action\">return to photo list</a></p>\n";
	echo "<p>&raquo;<a href=\"main.php?SID=$SID&action=$action&do=dedit&id=$id\">update $t some more</a></p>\n";
}


// main program

echo "<p class=\"16px\"><b>Photo Administration</b></p>\n";

// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

	// put picture in right place

	if (!copy($userpic,"../uploadphotos/$picture")) {
		echo "<p>That picture could not be uploaded at this time - no photo was added to the database.</p>\n";
		unlink($userpic);
		return;
	}
	unlink($userpic);
	$setpic = ",picture='$picture'";
} else {
	$picture = "";
	$setpic = "";
}

// do thumbnail stuff here - doesn't like being passed to a function!

if ($userpic1_name != "") {
	$picture1 = urldecode($userpic1_name);
	$picture1 = ereg_replace(" ","_",$picture1);
	$picture1 = ereg_replace("&","_and_",$picture1);

	// put thumbnail in right place

	if (!copy($userpic1,"../uploadphotos/$picture1")) {
		echo "<p>That thumbnail could not be uploaded at this time - no photo was added to the database.</p>\n";
		unlink($userpic1);
		return;
	}
	unlink($userpic1);
	$setpic1 = ",picture1='$picture1'";
} else {
	$picture1 = "";
	$setpic1 = "";
}

// Main Program

switch($do) {
case "dadd":
	if (!isset($doit)) add_photo_form($db);
	else do_add_photo($db,$title,$description,$location,$date,$gallery,$picture,$picture1,$setnum);
	break;
case "ddel":
	if (!isset($doit)) delete_photo_check($db,$id);
	else do_delete_photo($db,$id,$doit);
	break;
case "dedit":
	if (!isset($doit)) edit_photo_form($db,$id);
	else do_update_photo($db,$id,$title,$description,$location,$date,$gallery,$setpic,$setpic1,$setnum);
	break;
case "dgrid":
	if (!isset($doit)) edit_photo_grid($db);
	else do_update_grid($db,$title,$gallery,$picture,$picture1,$setnum);
	break;

default:
	show_main_menu($db);
	break;
}

?>
