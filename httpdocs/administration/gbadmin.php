<?php

//------------------------------------------------------------------------------
// Site Control Guestbook Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_long_guestbook_listing($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// check to see if database exists

	if (!$db->Exists("SELECT * FROM guestbook")) {
		echo "<p>There are currently no entries in the guestbook database.</p>\n";
		return;
	} else {

		// disabled long listing, uncomment to enable.
		// echo "<p><a href=\"$PHP_SELF?SID=$SID&action=$action&do=short\">short listing</a> | <a href=\"$PHP_SELF?SID=$SID&action=$action&do=long\">long listing</a></p>\n";

		// query the database

		$db->Query("SELECT * FROM guestbook ORDER BY date DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$n = htmlentities(stripslashes($db->data[name]));
			$e = htmlentities(stripslashes($db->data[email]));
			$c = htmlentities(stripslashes($db->data[comment]));

			// output

			echo "<p><table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
			echo "<tr class=\"trtop\">\n";
			echo "	<td align=\"left\" class=\"trtopfont\"><b>$n</b></td>\n";
			echo "	<td align=\"right\"><a href=\"mailto:$e\"><span class=\"trtopfont\">$e</span></a></td>\n";
			echo "</tr>\n";
			echo "<tr class=\"trbottom\">\n";
			echo "	<td align=\"left\" colspan=\"2\">$c</td>\n";
			echo "</tr>\n";
			echo "<tr class=\"trtop\">\n";
			echo "	<td align=\"left\" class=\"trtopfont\">posted on " . $db->data['date'] . "</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=del&id=" . $db->data['id'] . "\"><span class=\"trtopfont\">delete</span></a></td>\n";
			echo "</tr>\n";
			echo "</table></p>\n";
		}
	}
}

function show_short_listing($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// check to see if database exists

	if (!$db->Exists("SELECT * FROM guestbook")) {
		echo "<p>There are currently no entries in the guestbook database.</p>\n";
		return;
	} else {

			// disabled long listing, uncomment to enable.
			// echo "<p><a href=\"$PHP_SELF?SID=$SID&action=$action&do=short\">short listing</a> | <a href=\"$PHP_SELF?SID=$SID&action=$action&do=long\">long listing</a></p>\n";

			// output header information not to be in the for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Guestbook Signing List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query the database

		$db->Query("SELECT * FROM guestbook ORDER BY date DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup the variables

			$n = htmlentities(stripslashes($db->data[name]));
			$e = htmlentities(stripslashes($db->data[email]));
			$c = htmlentities(stripslashes($db->data[comment]));

			// output

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			echo "	<td align=\"left\">$n</td>\n";
			echo "	<td align=\"left\">$e</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=edit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=del&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
			echo "</table></p>\n";
			echo "  </td>\n";
			echo "</tr>\n";
			echo "</table>\n";
	}
}


function delete_guestbook_check($db,$id)
{
	global $content,$SID,$action;

	// query the database

	$db->QueryRow("SELECT name,email,comment FROM guestbook WHERE id=$id");

	// setup variables

	$n = htmlentities(stripslashes($db->data[name]));
	$e = htmlentities(stripslashes($db->data[email]));
	$c = htmlentities(stripslashes($db->data[comment]));

	// output

	echo "<p>Are you sure you wish to delete the guestbook entry by:</p>\n";
	echo "<p><b>$n, $e</b></p>\n";
	echo "<p>which reads:</p>\n";
	echo "<p><b>$c</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=del&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=del&id=$id&doit=0\">NO</a></p>\n";
}



function do_delete_guestbook($db,$id,$doit)
{
	global $content,$SID,$action;

	// decided not to delete

	if (!$doit) echo "<p>You have chosen NOT to delete that guestbook entry.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM guestbook WHERE id=$id");
		echo "<p>You have now deleted that guestbook entry.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the guestbook listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database

	$db->QueryRow("SELECT * FROM guestbook WHERE id=$id");

	// setup the variables

	$na = htmlentities(stripslashes($db->data[name]));
	$da = htmlentities(stripslashes($db->data['date']));
	$em = htmlentities(stripslashes($db->data[email]));
	$co = htmlentities(stripslashes($db->data[comment]));

		// the form

		echo "<p>Edit the guestbook entry.</p>\n";

		echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
		echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
		echo "<input type=\"hidden\" name=\"do\" value=\"edit\">\n";
		echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
		echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

		echo "<p>enter your name<br><input type=\"text\" name=\"name\" size=\"25\" maxlength=\"255\" value=\"$na\"></p>\n";
		echo "<p>enter your email<br><input type=\"text\" name=\"email\" size=\"25\" maxlength=\"255\" value=\"$em\"></p>\n";
		echo "<p>enter your comments<br><textarea name=\"comment\" cols=\"40\" rows=\"10\" wrap=\"virtual\">$co</textarea></p>\n";
		echo "<p><input type=\"submit\" value=\"edit entry\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
		echo "</form>\n";

}


function do_update_category($db,$id,$name,$email,$comment)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// make sure info is present and correct

	if ($name == "" || $comment == "") {
		echo "<p>You must at least complete the user's name and comments.</p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=edit&id=$id\">go back and try again</a></p>\n";
		return;
	}

	// setup the variables

	$na = addslashes(trim($name));
	$da = addslashes(trim($date));
	$em = addslashes(trim($email));
	$co = addslashes(trim($comment));
	$si = addslashes(trim($site));
	$pa = eregi_replace("\r","",$photo);
	$o = addslashes(trim($old));

	// update the database

	$db->Update("UPDATE guestbook SET name='$na',email='$em',comment='$co' WHERE id=$id");

		echo "<p>You have now updated that guestbook entry.</p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to guestbook entries</a></p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=$action&do=edit&id=$id\">update entry by $na some more</a></p>\n";
}



// main program

if (!$USER['flags'][$f_guestbook_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"14px\"><b>Guestbook Administration</b></p>";


switch($do) {
case "del":
	if (!isset($doit)) delete_guestbook_check($db,$id);
	else do_delete_guestbook($db,$id,$doit);
	break;
case "long":
	show_long_guestbook_listing($db);
	break;
case "short":
	show_short_listing($db);
	break;
case "edit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$name,$email,$comment);
	break;
default:
	show_short_listing($db);
	break;
}

?>
