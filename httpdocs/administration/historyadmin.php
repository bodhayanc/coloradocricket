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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a history article</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM history")) {
		echo "<p>There are currently no history articles in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;History Article List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM history ORDER BY id DESC");
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
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a //href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";

//echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";

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
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a history article</td>\n";
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
	echo "<p>enter the article title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the article information<br><textarea name=\"article\" cols=\"55\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p>upload a PDF<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure files have a unique name (eg. filename001.pdf)\n";
	echo "<li>please only upload pdfs\n";
	echo "<p><input type=\"submit\" value=\"add history\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$title,$article,$picture)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$t = addslashes(trim($title));
	$a = addslashes(trim($article));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());
	
	// check for duplicates

	if ($db->Exists("SELECT * FROM history WHERE title='$t'")) {
		echo "<p>That history already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO history (title,article,picture) VALUES ('$t','$a','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new history article.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another history article</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to history article list</a></p>\n";
	} else {
		echo "<p>The article could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to history article list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM history WHERE id=$id")));

	// output

	echo "<p>Are you sure you wish to delete the history article titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that history article.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM history WHERE id=$id");
		echo "<p>You have now deleted that history article.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the history article listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query database

	$db->QueryRow("SELECT * FROM history WHERE id=$id");

	// setup variables

	$t  = stripslashes($db->data['title']);
	$th = htmlentities(stripslashes($db->data['title']), ENT_SUBSTITUTE, 'cp1252');
	$a  = htmlentities(stripslashes($db->data['article']), ENT_SUBSTITUTE, 'cp1252');

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit History Article</td>\n";
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
	echo "<p>enter the article title<br><input type=\"text\" name=\"title\" size=\"50\"maxlength=\"255\" value=\"$th\"></p>\n";
	echo "<p>enter the article information<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$a</textarea></p>\n";
	if ($db->data['picture']) {
		echo "<p>current pdf</p>\n";
		echo "<p><a href=\"../uploadphotos/history/" . $db->data['picture'] . "\"><img src=\"../images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp; " . $db->data['picture'] . "</a></p>\n";
		echo "<p>upload a pdf (if you want to change the current one)";
	} else {
		echo "<p>upload a pdf";
	}
	echo "<p><ul><li>please make sure files have a unique name (eg. filename001.pdf)\n";
	echo "<li>please only upload pdfs\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit history\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$title,$article,$setpic)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

// setup the variables

	$t = addslashes(trim($title));
	
// prevent the need for using escape sequences with apostrophe's

	$a = addslashes(trim($article));
	
	// query database

	$db->Update("UPDATE history SET title='$t',article='$a'$setpic WHERE id=$id");
		echo "<p>You have now updated that history article.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the history article listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if (isset($_FILES['userpic']) && $_FILES['userpic']['name'] != "") {
	
	$uploaddir = "../uploadphotos/history/";
	$basename = basename($_FILES['userpic']['name']);
	$uploadfile = $uploaddir . $basename;
	// put picture in right place
	if (move_uploaded_file($_FILES['userpic']['tmp_name'], $uploadfile)) {
		$setpic = "";
		$picture=$basename;
	} else {
		echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
	}
	
	$setpic = ",picture='$picture'";
} else {
	$picture = "";
	$setpic = "";
}

// main program

if (!$USER['flags'][$f_history_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Site History Administration</b></p>\n";

if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
}
else {
	$do = '';
}

if(isset($_GET['doit'])) {
	$doit = $_GET['doit'];
} else if(isset($_POST['doit'])) {
	$doit = $_POST['doit'];
}

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$_POST['title'],$_POST['article'],$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$_GET['id']);
	else do_delete_category($db,$_GET['id'],$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$_GET['id']);
	else do_update_category($db,$_POST['id'],$_POST['title'],$_POST['article'],$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
