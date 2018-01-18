<?php

//------------------------------------------------------------------------------
// Print News Article Archives v 2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------

function show_printable_news($db,$s,$id,$pr)
{
	global $PHP_SELF;

	$db->QueryRow("SELECT * FROM news WHERE id=$pr");
	$db->BagAndTag();

	// setup the variables

	$t = $db->data['title'];
	$id = $db->data['id'];
	$pr = $db->data['id'];
	$ad = sqldate_to_string($db->data[added]);
	$au = $db->data['author'];

	// output story

	echo "<table width=\"100%\" cellpadding=\"6\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	echo "<p>\n";
	echo "<div align=\"left\"><span class=\"14px\">$t</span></div>\n";

	// only show author and added if they were input

	if ($db->data['author'] != "") echo "<p><b>Author:</b> $au<br>\n";
	if ($db->data[added] != "") echo "<b>Submitted:</b> $ad</p>\n";
	if ($db->data['picture'] != "") echo "<img src=\"uploadphotos/news/" . $db->data['picture'] . "\" align=\"right\" style=\"border: 1 solid #393939\">\n";
	echo "<p>" . $db->data['article'] . "</p>\n";

	// output link back

	echo "<p>&laquo; <a href=\"news.php?news=$pr&ccl_mode=1\">back to regular news article</a></p>\n";
	echo "</p>\n";

	echo "</td></tr></table>\n";

}


// main program

// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_printable_news($db,$s,$id,$news);


?>
