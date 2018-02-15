<?php

//------------------------------------------------------------------------------
// Site Control User Article Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a news article</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM news WHERE IsFeature != 0")) {
		echo "<p>There are currently no news articles in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;User Featured Article List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM news WHERE IsFeature != 0 ORDER BY id DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));
			$fe = $db->data['IsPending'];

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			if($fe != "1") {
				echo "	<td align=\"left\">$t</td>\n";
			} else {
				//echo "<p>deleting that news article. ID= $id</p>\n";
				echo "	<td align=\"left\"><font color=\"red\">$t (awaiting approval)</font></td>\n";
			//	$db->Delete("DELETE FROM news WHERE id=" . $db->data['id']);
			//	$db->Query("SELECT * FROM news WHERE IsFeature != 0 ORDER BY id DESC");
			}
//			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a //href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";

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
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a news article</td>\n";
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
	
	echo "<p><select name=\"IsFeature\">\n";
	echo "<option value=\"\">Is this a feature?</option>\n";
	echo "<option value=\"\">------------------</option>\n";
	echo "<option value=\"0\">No</option>\n";
	echo "<option value=\"1\">Yes</option>\n";
	echo "</select></p>\n";

	echo "<p>does this news item have a discussion?<br><select name=\"DiscussID\">\n";
	echo "	<option value=\"\">Select Forum Topic</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT max(p.post_id) as PostID, p.topic_id AS TopicID, t.post_id, t.post_subject FROM board_posts p INNER JOIN board_posts_text t ON p.post_id = t.post_id WHERE t.post_subject <> '' GROUP BY p.topic_id ORDER BY p.topic_id DESC")) {
		$db->Query("SELECT max(p.post_id) as PostID, p.topic_id AS TopicID, t.post_id, t.post_subject FROM board_posts p INNER JOIN board_posts_text t ON p.post_id = t.post_id WHERE t.post_subject <> '' GROUP BY p.topic_id ORDER BY p.topic_id DESC");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			echo "<option value=\"" . $db->data[TopicID] . "\">" . $db->data[post_subject] . "</option>\n";
		}
	}

	echo "</select></p>\n";
	
	echo "<p>enter the name of the news<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the name of the author<br><input type=\"text\" name=\"author\" size=\"25\" maxlength=\"255\"></p>\n";
	echo "<p>enter the feature article<br><textarea name=\"article\" cols=\"55\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p>upload a  photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 200 pixels wide x 300 pixels high\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<p>enter a short description of the photo <i>(255 chars max)</i><br><textarea name=\"picdesc\" cols=\"55\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"add news\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$uid,$title,$author,$article,$IsFeature,$DiscussID,$picdesc,$picture)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$t = addslashes(trim($title));
	$au = addslashes(trim($author));
	$a = addslashes(trim($article));
	$if = addslashes(trim($IsFeature));
	$di = addslashes(trim($DiscussID));
	$pd = addslashes(trim($picdesc));	
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());
	
	// check for duplicates

	if ($db->Exists("SELECT * FROM news WHERE title='$t'")) {
		echo "<p>That news already exists in the database.</p>\n";
		return;
	}

	// all okay

// 23-Aug-2017 1:49pm Kervyn - Setting the default to be "news-200.jpg"
if ($picture == "") {
 $picture = "news-200.jpg";
}
	$db->Insert("INSERT INTO news (added,user,title,author,article,IsFeature,DiscussID,picdesc,picture) VALUES ('$d','$uid','$t','$au','$a','$if','$di','$pd','$picture')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new news article.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another news article</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to news article list</a></p>\n";
	} else {
		echo "<p>The article could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to news article list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM news WHERE id=$id")));

	// output

	echo "<p>Are you sure you wish to delete the news article titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that news article.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM news WHERE id=$id");
		echo "<p>You have now deleted that news article. ID= $id</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the news article listing</a></p>\n";
                     //Added this so that it would directly go back to the main page.
                     show_main_menu($db); 
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// Commented - Narayan for both Article Admin as well as New Admin.
	// get all created topics
	//$db->Query("SELECT max(p.post_id) AS PostID, p.topic_id AS TopicID, //t.post_id, t.post_subject FROM board_posts p INNER JOIN board_posts_text t ON //p.post_id = t.post_id WHERE t.post_subject <> '' GROUP BY p.topic_id ORDER BY //p.topic_id DESC");
	//for ($g=0; $g<$db->rows; $g++) {
		//$db->GetRow($g);
        //$db->BagAndTag();
		//$topics[$db->data[TopicID]] = $db->data[post_subject];
	//}


	// query database

	$db->QueryRow("SELECT * FROM news WHERE id=$id");

	// setup variables

	$t  = stripslashes($db->data['title']);
	$th = htmlentities(stripslashes($db->data['title']));
	$au = htmlentities(stripslashes($db->data['author']));
	$a  = htmlentities(stripslashes($db->data['article']));
	$pd  = htmlentities(stripslashes($db->data['picdesc']));

	$is = stripslashes($db->data['IsFeature']);
	$isyes = 'yes';
	$isno = 'no';
	if ($db->data['IsFeature'] ==1) $is1 = $isyes;
	if ($db->data['IsFeature'] ==0) $is1 = $isno;

	$ip = stripslashes($db->data['IsPending']);
	$ipyes = 'yes';
	$ipno = 'no';
	if ($db->data['IsPending'] ==0) $ip1 = $ipyes;
	if ($db->data['IsPending'] ==1) $ip1 = $ipno;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit User Featured Article</td>\n";
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

		echo "<p>Approve this feature article?</p>\n";
		echo "<select name=\"IsPending\">\n";
		echo "	<option value=\"$ip\">$ip1</option>\n";
		echo "	<option value=\"\">========or choose=======</option>\n";
		echo "	<option value=\"0\">yes</option>\n";
		echo "	<option value=\"1\">no</option>\n";
		echo "</select>\n";

		echo "<p>Is this item a feature article?</p>\n";
		echo "<select name=\"IsFeature\">\n";
		echo "	<option value=\"$is\">$is1</option>\n";
		echo "	<option value=\"\">========or choose=======</option>\n";
		echo "	<option value=\"1\">yes</option>\n";
		echo "	<option value=\"0\">no</option>\n";
		echo "</select>\n";


	echo "<p>does this news item have a discussion?<br>\n";
	echo "<select name=\"DiscussID\">\n";
	echo "	<option value=\"\">Select Forum Topic</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($topics as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['DiscussID'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";
	
	
	echo "<p>enter the name of the news<br><input type=\"text\" name=\"title\" size=\"50\"maxlength=\"255\" value=\"$th\"></p>\n";
	echo "<p>enter the name of the author<br><input type=\"text\" name=\"author\" size=\"50\"maxlength=\"255\" value=\"$au\"></p>\n";
	echo "<p>enter the news article<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$a</textarea></p>\n";
	if ($db->data['picture']) {
		echo "<p>current photo</p>\n";
		echo "<p><img src=\"../uploadphotos/news/" . $db->data['picture'] . "\"></p>\n";
		echo "<p>upload a photo (if you want to change the current one)";
	} else {
		echo "<p>upload a photo";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 200 pixels wide x 300 pixels high\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<p>enter a short description of the photo <i>(255 chars max)</i><br><textarea name=\"picdesc\" cols=\"55\" rows=\"10\" wrap=\"virtual\">$pd</textarea></p>\n";
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


function do_update_category($db,$id,$title,$author,$article,$IsFeature,$IsPending,$DiscussID,$picdesc,$setpic)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

// setup the variables

	$t = addslashes(trim($title));
	$au = addslashes(trim($author));
	$if = addslashes(trim($IsFeature));
	$ip = addslashes(trim($IsPending));
	$di = addslashes(trim($DiscussID));
	$pd = addslashes(trim($picdesc));	

// prevent the need for using escape sequences with apostrophe's

	$a = addslashes(trim($article));

	// query database

	$db->Update("UPDATE news SET title='$t',author='$au',article='$a',IsFeature='$if',IsPending='$ip',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
		echo "<p>You have now updated that news article.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the news article listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if (isset($_FILES['userpic']) && $_FILES['userpic']['name'] != "") {
	
	$uploaddir = "../uploadphotos/news/";
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

if (!$USER['flags'][$f_news_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Featured Article Administration</b></p>\n";

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
	else do_add_category($db,$USER['email'],$_POST['title'],$_POST['author'],$_POST['article'],$_POST['IsFeature'],0,$_POST['picdesc'],$picture);
	break;
case "sdel":
    //Per Kervyn's request -- taking off the confirmation option
	// Bodha adding it back 02/15/2018
	if (!isset($doit)) delete_category_check($db,$_GET['id']);
	else do_delete_category($db,$_GET['id'],$doit);
    break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$_GET['id']);
	else do_update_category($db,$_POST['id'],$_POST['title'],$_POST['author'],$_POST['article'],$_POST['IsFeature'],$_POST['IsPending'],0,$_POST['picdesc'],$setpic);
	break;
default:
	show_main_menu($db);
	break;
}

?>
