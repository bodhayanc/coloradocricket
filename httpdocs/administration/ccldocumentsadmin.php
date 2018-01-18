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

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a document</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM documents")) {
		echo "<p>There are currently no documents in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;League Documents List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM documents ORDER BY id DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data[title]));
			$id = htmlentities(stripslashes($db->data[id]));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			echo "	<td align=\"left\">$t</td>\n";
//			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[id] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a //href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data[id] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";

			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data[id] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";

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
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr,$pdf_file_name;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a document</td>\n";
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
	echo "<p>enter the document title<br><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the document information<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";

	//echo "<p>upload a PDF<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p>upload a PDF<br><input type=\"file\" name=\"pdf_file_name\" size=\"40\"></p>\n";

	echo "<p><ul><li>please make sure files have a unique name (eg. filename001.pdf)\n";
	echo "<li>please only upload pdfs\n";
	echo "<p><input type=\"submit\" value=\"add documents\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$title,$article,$pdf_file)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr,$pdf_file_name;

	// setup variables

	$t = addslashes(trim($title));
	$a = addslashes(trim($article));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());
	$pa = eregi_replace("\r","",$photo);

	// check for duplicates

	if ($db->Exists("SELECT * FROM documents WHERE title='$t'")) {
		echo "<p>That document already exists in the database.</p>\n";
		return;
	}

	// all okay

	$db->Insert("INSERT INTO documents (title,article,picture) VALUES ('$t','$a','$pdf_file_name')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new document.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another document</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to documents list</a></p>\n";
	} else {
		echo "<p>The document could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to documents list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM documents WHERE id=$id")));

	// output

	echo "<p>Are you sure you wish to delete the document titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that document.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM documents WHERE id=$id");
		echo "<p>You have now deleted that document.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the documents listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr, $pdf_file_name;

	// query database

	$db->QueryRow("SELECT * FROM documents WHERE id=$id");

	// setup variables

	$t  = stripslashes($db->data[title]);
	$th = htmlentities(stripslashes($db->data[title]));
	$a  = htmlentities(stripslashes($db->data[article]));

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
	echo "<p>enter the document title<br><input type=\"text\" name=\"title\" size=\"50\"maxlength=\"255\" value=\"$th\"></p>\n";
	echo "<p>enter the document information<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$a</textarea></p>\n";
	if ($db->data[picture]) {
		echo "<p>current pdf</p>\n";
		echo "<p><a href=\"../uploadphotos/documents/" . $db->data[picture] . "\"><img src=\"../images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp; " . $db->data[picture] . "</a></p>\n";
		echo "<p>upload a pdf (if you want to change the current one)";
	} else {
		echo "<p>upload a pdf";
	}
	echo "<p><ul><li>please make sure files have a unique name (eg. filename001.pdf)\n";
	echo "<li>please only upload pdfs\n";
	echo "<br><input type=\"file\" name=\"pdf_file_name\" size=\"40\"></p>\n";
	echo "<p><input type=\"submit\" value=\"edit documents\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
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
	$pa = eregi_replace("\r","",$photo);
	$o = addslashes(trim($old));

// prevent the need for using escape sequences with apostrophe's

	$a = eregi_replace("\r","",$article);
	$a = addslashes(trim($a));

	// query database

	$db->Update("UPDATE documents SET title='$t',article='$a'$setpic WHERE id=$id");
		echo "<p>You have now updated that document.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the documents listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"../uploadphotos/documents/$picture")) {
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

if (!$USER[flags][$f_ccldocuments_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>CCL Documents Administration</b></p>\n";

switch($do) {
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$title,$article,$pdf_file_name);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$title,$article,$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
