<?php

//------------------------------------------------------------------------------
// Add Featured Article v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function do_add_article($db,$title,$author,$article,$IsFeature,$IsPending,$picture)
{
	global $PHP_SELF,$content,$action,$SID;

	// make sure info is present and correct

	if ($title == "" || $article == "") {
		echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
		echo "<p>You must complete the title and the article. Please try again.</p>\n";
		return;
	}
	include("securimage.php");
  	$img = new Securimage();
  	$valid = $img->check($_POST['code']);

  	if($valid == false) {
    	echo "<p>Sorry, the code you entered was invalid.  Please try again.</p>";
    	return;
  	}

	// setup variables

	$t = addslashes(trim($title));
	$au = addslashes(trim($author));
	$a = addslashes(trim($article));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());
	$pa = eregi_replace("\r","",$photo);


	// all okay

	$db->Insert("INSERT INTO news (added,user,title,author,article,IsFeature,IsPending,picture) VALUES ('$d','Web Submission','$t','$au','$a',1,1,'$picture')");
	if ($db->a_rows != -1) {
		echo "<p class=\"12pt\"><b>Thanks!</b></p>\n";
		echo "<p>You have now added a pending article. If the article is approved it will appear on the site shortly.</p>\n";
	} else {
		echo "<p class=\"12pt\"><b>Uh oh!</b></p>\n";
		echo "<p>The article could not be added to the database at this time. This is probably due to an error with the database, please try again.</p>\n";
	}
}

// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"uploadphotos/news/$picture")) {
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


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

do_add_article($db,$title,$author,$article,$IsFeature,$IsPending,$picture);

?>