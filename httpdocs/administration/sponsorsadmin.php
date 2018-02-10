<?php

//------------------------------------------------------------------------------
// Site Control News Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a sponsor</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM sponsors")) {
		echo "<p>There are currently no sponsors in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

	
      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Sponsors List</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM sponsors ORDER BY id DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));
			$sa = $db->data[isActive];

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			if($sa != "0") {
			echo "	<td align=\"left\">$t</td>\n";
			} else {
			echo "	<td align=\"left\">$t<b><font color=\"red\"> (not active)</font></b></td>\n";
			}

//			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a //href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";

			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a></td>\n";

			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
		
	}
}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a sponsor</td>\n";
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
	
	
	echo "<p>enter the name of the sponsor<br><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the url of the sponsor<br><input type=\"text\" name=\"url\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the amount promised <i>(For sorting priority)</i><br><input type=\"text\" name=\"promised\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter a short profile<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p>upload an image<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 150 pixels wide\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<p><input type=\"submit\" value=\"add sponsor\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$title,$url,$promised,$article,$picture)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$t = addslashes(trim($title));
	$u = addslashes(trim($url));
	$a = addslashes(trim($article));
	$p = addslashes(trim($promised));
	
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());
	$pa = eregi_replace("\r","",$photo);

	// check for duplicates

	if ($db->Exists("SELECT * FROM sponsors WHERE title='$t'")) {
		echo "<p>That news already exists in the database.</p>\n";
		return;
	}

	// all okay

// 9-Jan-2010 - By default, the inserted sponsor is Active(1)
        $db->Insert("INSERT INTO sponsors (title,url,promised,article,picture,isActive) VALUES ('$t','$u','$p','$a','$picture', 1)");
//	$db->Insert("INSERT INTO sponsors (title,url,promised,article,picture) VALUES ('$t','$u','$p','$a','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new sponsor.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another sponsor</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to sponsor list</a></p>\n";
	} else {
		echo "<p>The article could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to sponsor list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM sponsors WHERE id=$id")));

	// output

	echo "<p>Are you sure you wish to delete the sponsor titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that sponsor.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM sponsors WHERE id=$id");
		echo "<p>You have now deleted that sponsor.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the sponsor listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;



	// query database

	$db->QueryRow("SELECT * FROM sponsors WHERE id=$id");

	// setup variables

	$t  = stripslashes($db->data['title']);
	$th = htmlentities(stripslashes($db->data['title']));
	$u = htmlentities(stripslashes($db->data['url']));
	$a  = htmlentities(stripslashes($db->data['article']));
	$p  = htmlentities(stripslashes($db->data['promised']));
        // 9-Jan-2010
        $act = htmlentities(stripslashes($db->data[isActive]));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Sponsor</td>\n";
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
	
	
	echo "<p>enter the name of the sponsor<br><input type=\"text\" name=\"title\" size=\"50\"maxlength=\"255\" value=\"$th\"></p>\n";

        // 9-Jan-2010
	echo "<p>Active(1) or InActive(0)<br><input type=\"text\" name=\"isactive\" size=\"1\"maxlength=\"1\" value=\"$act\"></p>\n";

	echo "<p>enter the url of the sponsor<br><input type=\"text\" name=\"url\" size=\"50\"maxlength=\"255\" value=\"$u\"></p>\n";
	echo "<p>enter the amount promised <i>(for sorting purposes)</i><br><input type=\"text\" name=\"promised\" size=\"50\"maxlength=\"255\" value=\"$p\"></p>\n";

	echo "<p>enter a short profile<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$a</textarea></p>\n";

	if ($db->data['picture']) {
		echo "<p>current image</p>\n";
		echo "<p><img src=\"../uploadphotos/sponsors/" . $db->data['picture'] . "\"></p>\n";
		echo "<p>upload an image (if you want to change the current one)";
	} else {
		echo "<p>upload an image";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 150 pixels wide\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	
	echo "<p><input type=\"submit\" value=\"edit news\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$title,$url,$promised,$article,$setpic, $isactive)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

// setup the variables

	$t = addslashes(trim($title));
	$pa = eregi_replace("\r","",$photo);
	$o = addslashes(trim($old));
	$u = addslashes(trim($url));
	$p = addslashes(trim($promised));
        $act = addslashes(trim($isactive));


// prevent the need for using escape sequences with apostrophe's

	$a = eregi_replace("\r","",$article);
	$a = addslashes(trim($a));

	// query database

	$db->Update("UPDATE sponsors SET title='$t',url='$u',promised='$p',article='$a', isactive=$act$setpic WHERE id=$id");
		echo "<p>You have now updated that sponsor.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the sponsor listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

//if ($userpic_name != "") {
//	$picture = urldecode($userpic_name);
//	$picture = ereg_replace(" ","_",$picture);
//	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

//	if (!copy($userpic,"../uploadphotos/sponsors/$picture")) {
//		echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
//		unlink($userpic);
//		return;
//	}
//	unlink($userpic);
//	$setpic = ",picture='$picture'";
//} else {
//	$picture = "";
//	$setpic = "";
//}


// do picture stuff here - doesn't like being passed to a function!
if ($_FILES['userpic']['name'] != "") {
// 9-Jan-2010
$uploaddir = "../uploadphotos/sponsors/";
//  $uploaddir = "../uploadphotos/players/";
  $basename = basename($_FILES['userpic']['name']);
  $uploadfile = $uploaddir . $basename;

  if (move_uploaded_file($_FILES['userpic']['tmp_name'], $uploadfile)) {
    $setpic = ",picture='$basename'";
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

if (!$USER[flags][$f_sponsors_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Sponsors Administration</b></p>\n";

switch($do) {
case "menu":
	show_main_menu($db);
	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$title,$url,$promised,$article,$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$title,$url,$promised,$article,$setpic,$isactive);
	break;
default:
	show_main_menu($db);
	break;
}

?>
