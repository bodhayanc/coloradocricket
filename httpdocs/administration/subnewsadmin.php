<?php

//------------------------------------------------------------------------------
// Site Control News Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_recent_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a news article</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM news WHERE IsFeature != 0")) {
		echo "<p>There are currently no news articles in the database.</p>\n";
		return;
	} else {

	// output header, not to be included in for loop

	// Search Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search The News Archives</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"main.php\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;10 Most Recent News Article List</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM news WHERE IsFeature != 1 ORDER BY id DESC LIMIT 10");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));
			$fe = $db->data['IsFeature'];

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
			echo "	<td align=\"left\"><b><font color=\"red\">$t</font></b></td>\n";
			}
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";

	// News by Month Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">News Articles by Month</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	$yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

		for ($y=0; $y<$db->rows; $y++) {
			$db->GetRow($y);
			$ty = $db->data['theyear'];
			$tm = $db->data['themonth'];
			$mi = $db->data['monthid'];

			echo "$ty&nbsp;&nbsp;";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=1&monthname=January\">Jan</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
			echo "<br>";

		}

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=menu\">view all news articles</a></p>\n";
		
	}
}


function show_months_main_menu($db,$theyear,$themonth,$monthname)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a news article</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM news WHERE IsFeature != 0")) {
		echo "<p>There are currently no news articles in the database.</p>\n";
		return;
	} else {

	// output header, not to be included in for loop

	// Search Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search The News Archives</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"main.php\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;$monthname $theyear News Article List</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM news WHERE IsFeature != 1 AND year(added) = $theyear AND month(added) = $themonth ORDER BY id DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));
			$fe = $db->data['IsFeature'];

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
			echo "	<td align=\"left\"><b><font color=\"red\">$t</font></b></td>\n";
			}
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";
	
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";

	// News by Month Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">News Articles by Month</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	$yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

		for ($y=0; $y<$db->rows; $y++) {
			$db->GetRow($y);
			$ty = $db->data['theyear'];
			$tm = $db->data['themonth'];
			$mi = $db->data['monthid'];

			echo "$ty&nbsp;&nbsp;";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=1&monthname=January\">Jan</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
			echo "<br>";

		}

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=menu\">view all news articles</a></p>\n";
		
	}
}


function show_search_main_menu($db,$search="",$theyear,$themonth,$monthname)
{
         global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

         if (!$db->Exists("SELECT * FROM news")) {
                 echo "<p>There are currently no news.</p>\n";
                 return;
         }

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a news article</a></p>\n";

	// Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the news archives</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
	echo "  <tr>\n";
	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;News Articles Containing \"$search\"</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


	if ($search != "")
	{

	    $contains = "article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

		$db->Query("SELECT * FROM news WHERE $contains ORDER BY id DESC");
			if ($db->rows)
			{


			for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$a = sqldate_to_string($db->data['added']);
			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));
			$fe = $db->data['IsFeature'];
			
			if($i % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			if($fe != "1") {
			echo "	<td align=\"left\">$t</td>\n";
			} else {
			echo "	<td align=\"left\"><b><font color=\"red\">$t</font></b></td>\n";
			}
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";

			}
		}
		else
		{
		echo "<tr class=\"trrow1\">\n";
		echo " <td>\n";
		echo "<p>There are no news articles matching that query in any way.</p>\n";

		echo "  </td>\n";
		echo "</tr>\n";

		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";

	// News by Month Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">Archives by month</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	$yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

		for ($y=0; $y<$db->rows; $y++) {
			$db->GetRow($y);
			$ty = $db->data['theyear'];
			$tm = $db->data['themonth'];
			$mi = $db->data['monthid'];

			echo "$ty&nbsp;&nbsp;";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=1&monthname=January\">Jan</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
			echo "<br>";

		}

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table><br>\n";

		// echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">complete news archives &raquo;</a></p>\n";
        }


 }

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

	// Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the news archives</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;News Article List</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("SELECT * FROM news WHERE IsFeature != 1 ORDER BY id DESC");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$t = htmlentities(stripslashes($db->data['title']));
			$id = htmlentities(stripslashes($db->data['id']));
			$fe = $db->data['IsFeature'];

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
			echo "	<td align=\"left\"><b><font color=\"red\">$t</font></b></td>\n";
			}
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
	// News by Month Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">Archives by month</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	$yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

		for ($y=0; $y<$db->rows; $y++) {
			$db->GetRow($y);
			$ty = $db->data['theyear'];
			$tm = $db->data['themonth'];
			$mi = $db->data['monthid'];

			echo "$ty&nbsp;&nbsp;";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=1&monthname=January\">Jan</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
			echo "<a href=\"main.php?SID=$SID&action=$action&do=months&theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
			echo "<br>";

		}

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table><br>\n";
		
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
	
	echo "<p>Is this a user featured article?</p>\n";
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
	
	echo "<p>Select an expiration date if this is an important article.</p>\n";
	echo "<p><select name=\"featureexpire\">\n";
	echo "<option value=\"\">Select expiration date</option>\n";
	echo "<option value=\"\">------------------</option>\n";
	echo "<option value=\"7\">1 Week from today</option>\n";
	echo "<option value=\"14\">2 Weeks from today</option>\n";
	echo "<option value=\"21\">3 Weeks from today</option>\n";
	echo "<option value=\"28\">4 Weeks from today</option>\n";	
	echo "</select></p>\n";	
	
	echo "<p>enter the name of the news<br><input type=\"text\" name=\"title\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the name of the author<br><input type=\"text\" name=\"author\" size=\"50\" maxlength=\"255\"></p>\n";
	echo "<p>enter the news article<br><textarea name=\"article\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p>upload a  photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 150 pixels wide\n";
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


function do_add_category($db,$uid,$featureexpire,$title,$author,$article,$IsFeature,$DiscussID,$picdesc,$picture)
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
	$pa = eregi_replace("\r","",$photo);
	$ex = addslashes(trim($featureexpire));

	// check for duplicates

	if ($db->Exists("SELECT * FROM news WHERE title='$t'")) {
		echo "<p>That news already exists in the database.</p>\n";
		return;
	}

	// all okay

	if($ex == "7")  { $db->Insert("INSERT INTO news (added,featureexpire,user,title,author,article,IsFeature,DiscussID,picdesc,picture) VALUES ('$d',DATE_ADD(CURDATE(),INTERVAL 7 DAY),'$uid','$t','$au','$a','$if','$di','$pd','$picture')");
	} else if($ex == "14") { $db->Insert("INSERT INTO news (added,featureexpire,user,title,author,article,IsFeature,DiscussID,picdesc,picture) VALUES ('$d',DATE_ADD(CURDATE(),INTERVAL 14 DAY),'$uid','$t','$au','$a','$if','$di','$pd','$picture')");
	} else if($ex == "21") { $db->Insert("INSERT INTO news (added,featureexpire,user,title,author,article,IsFeature,DiscussID,picdesc,picture) VALUES ('$d',DATE_ADD(CURDATE(),INTERVAL 21 DAY),'$uid','$t','$au','$a','$if','$di','$pd','$picture')");
	} else if($ex == "28") { $db->Insert("INSERT INTO news (added,featureexpire,user,title,author,article,IsFeature,DiscussID,picdesc,picture) VALUES ('$d',DATE_ADD(CURDATE(),INTERVAL 28 DAY),'$uid','$t','$au','$a','$if','$di','$pd','$picture')");
	} else {  $db->Insert("INSERT INTO news (added,featureexpire,user,title,author,article,IsFeature,DiscussID,picdesc,picture) VALUES ('$d',CURDATE(),'$uid','$t','$au','$a','$if','$di','$pd','$picture')");
	}
	
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
		echo "<p>You have now deleted that news article.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the news article listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;


	// get all created topics
	$db->Query("SELECT max(p.post_id) AS PostID, p.topic_id AS TopicID, t.post_id, t.post_subject FROM board_posts p INNER JOIN board_posts_text t ON p.post_id = t.post_id WHERE t.post_subject <> '' GROUP BY p.topic_id ORDER BY p.topic_id DESC");
	for ($g=0; $g<$db->rows; $g++) {
		$db->GetRow($g);
        $db->BagAndTag();
		$topics[$db->data[TopicID]] = $db->data[post_subject];
	}

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
	


      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit News Article</td>\n";
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
	
	echo "<p>Select an expiration date if this is an important article.</p>\n";
	echo "<p><select name=\"featureexpire\">\n";
	echo "<option value=\"\">Select expiration date</option>\n";
	echo "<option value=\"0\">Remove expiration date</option>\n";
	echo "<option value=\"7\">1 Week from today</option>\n";
	echo "<option value=\"14\">2 Weeks from today</option>\n";
	echo "<option value=\"21\">3 Weeks from today</option>\n";
	echo "<option value=\"28\">4 Weeks from today</option>\n";	
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
	echo "<li>please save images no larger than 150 pixels wide\n";
	echo "<li>portrait images only please! avoid landscape\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p>enter a short description of the photo <i>(255 chars max)</i><br><textarea name=\"picdesc\" cols=\"55\" rows=\"10\" wrap=\"virtual\">$pd</textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"edit news\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$featureexpire,$title,$author,$article,$IsFeature,$DiscussID,$picdesc,$setpic)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

// setup the variables

	$t = addslashes(trim($title));
	$pa = eregi_replace("\r","",$photo);
	$o = addslashes(trim($old));
	$au = addslashes(trim($author));
	$if = addslashes(trim($IsFeature));
	$di = addslashes(trim($DiscussID));
	$pd = addslashes(trim($picdesc));
	$ex = addslashes(trim($featureexpire));

// prevent the need for using escape sequences with apostrophe's

	$a = eregi_replace("\r","",$article);
	$a = addslashes(trim($a));

	// query database

	if($ex == "7")  { $db->Update("UPDATE news SET featureexpire=DATE_ADD(CURDATE(),INTERVAL 7 DAY),title='$t',author='$au',article='$a',IsFeature='$if',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
	} else if($ex == "14")  { $db->Update("UPDATE news SET featureexpire=DATE_ADD(CURDATE(),INTERVAL 14 DAY),title='$t',author='$au',article='$a',IsFeature='$if',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
	} else if($ex == "21")  { $db->Update("UPDATE news SET featureexpire=DATE_ADD(CURDATE(),INTERVAL 21 DAY),title='$t',author='$au',article='$a',IsFeature='$if',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
	} else if($ex == "28")  { $db->Update("UPDATE news SET featureexpire=DATE_ADD(CURDATE(),INTERVAL 28 DAY),title='$t',author='$au',article='$a',IsFeature='$if',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
	} else if($ex == "0")   { $db->Update("UPDATE news SET featureexpire=DATE_SUB(CURDATE(),INTERVAL 28 DAY),title='$t',author='$au',article='$a',IsFeature='$if',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
	} else { $db->Update("UPDATE news SET featureexpire=CURDATE(),title='$t',author='$au',article='$a',IsFeature='$if',DiscussID='$di',picdesc='$pd'$setpic WHERE id=$id");
	}
	
	echo "<p>You have now updated that news article.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the news article listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $t some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!

if ($userpic_name != "") {
	$picture = urldecode($userpic_name);
	$picture = ereg_replace(" ","_",$picture);
	$picture = ereg_replace("&","_and_",$picture);

// put picture in right place

	if (!copy($userpic,"../uploadphotos/news/$picture")) {
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

if (!$USER['flags'][$f_news_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Site News Administration</b></p>\n";

switch($do) {
case "menu":
	show_main_menu($db);
	break;
case "months":
	show_months_main_menu($db,$theyear,$themonth,$monthname);
	break;
case "search":
	show_search_main_menu($db,$search,$theyear,$themonth,$monthname);
	break;	
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$USER['email'],$featureexpire,$title,$author,$article,$IsFeature,$DiscussID,$picdesc,$picture);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$featureexpire,$title,$author,$article,$IsFeature,$DiscussID,$picdesc,$setpic);
	break;
default:
	show_recent_main_menu($db);
	break;
}

?>
