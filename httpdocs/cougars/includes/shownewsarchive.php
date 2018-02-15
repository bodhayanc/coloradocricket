<?php

//------------------------------------------------------------------------------
// Colorado Cricket News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------

function show_top20_news_listing($db,$s,$id,$pr,$mn)
{
	global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";


	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">News</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<b class=\"16px\">Cougars News Article Archives</b><br><br>\n";

	//////////////////////////////////////////////////////////////////////////////////////////
	// Search News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td colspan=\"2\"bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH OPTIONS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
	
	echo "  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "    <tr>\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionyear.gif\"><br>\n";
	echo "  <select name=\"theyear\">\n";

	if ($db->Exists("SELECT year(`added`) AS theyear FROM news GROUP BY theyear")) {
		$db->Query("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionmonth.gif\"><br>\n";
	echo "  <select name=\"themonth\">\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mi\">$mn</option>\n";
		}
		
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "    </td>\n";

	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"5\">\n";	
	echo "  </form>\n";

	//-------------------------------------------------
	// do the league management query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptiontype.gif\"><br>\n";
	echo "  <select name=\"type\">\n";
	
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$li = $db->data[LeagueAbbrev];
			$ln = $db->data['LeagueName'];
			echo "  <option value=\"$li\">$ln</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"7\">";
	echo "  </form>\n";

	//-------------------------------------------------
	// do the search all news box
	//-------------------------------------------------

	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <form action=\"$PHP_SELF\" validate=\"onchange\" invalidColor=\"yellow\">";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionsearch.gif\"><br>\n";	
	echo "  <input type=\"text\" name=\"search\" value=\"$search\" size=\"20\" required msg=\"Please enter a search phrase\"> <input type=\"submit\" class=\"go\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </form>\n";
	
	echo "      </tr>\n";
	echo "    </table>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	//////////////////////////////////////////////////////////////////////////////////////////
	// Popular News Box
	//////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;MOST POPULAR COUGARS NEWS</td>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\" align=\"right\">&nbsp;<a class=\"whitemain\" href=\"news.php?ccl_mode=6\">View All</a></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

	$db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND views <> '0' AND newstype=2 ORDER BY views DESC LIMIT 10");
	$db->BagAndTag();

	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";
	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
	//////////////////////////////////////////////////////////////////////////////////////////		
	// 25 Recent News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;RECENT COUGARS NEWS ARTICLES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
	
	if ($db->Exists("SELECT * FROM news WHERE newstype=2")) {
	$db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND newstype=2 ORDER BY added DESC LIMIT 35");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";

	}

	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><br>\n";


	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	} else {
	echo "There are no news in the database\n";
	}
}



function show_popular_news_listing($db,$s,$id,$pr)
{
	global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; <font class=\"10px\">Most Popular News</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<b class=\"16px\">Most Popular News Articles</b><br><br>\n";

	//////////////////////////////////////////////////////////////////////////////////////////
	// Search News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td colspan=\"2\"bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH OPTIONS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
	
	echo "  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "    <tr>\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionyear.gif\"><br>\n";
	echo "  <select name=\"theyear\">\n";

	if ($db->Exists("SELECT year(`added`) AS theyear FROM news GROUP BY theyear")) {
		$db->Query("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionmonth.gif\"><br>\n";
	echo "  <select name=\"themonth\">\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mi\">$mn</option>\n";
		}
		
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "    </td>\n";

	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"5\">\n";	
	echo "  </form>\n";

	//-------------------------------------------------
	// do the league management query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptiontype.gif\"><br>\n";
	echo "  <select name=\"type\">\n";
	
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$li = $db->data[LeagueAbbrev];
			$ln = $db->data['LeagueName'];
			echo "  <option value=\"$li\">$ln</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"7\">";
	echo "  </form>\n";

	//-------------------------------------------------
	// do the search all news box
	//-------------------------------------------------

	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <form action=\"$PHP_SELF\" validate=\"onchange\" invalidColor=\"yellow\">";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionsearch.gif\"><br>\n";	
	echo "  <input type=\"text\" name=\"search\" value=\"$search\" size=\"20\" required msg=\"Please enter a search phrase\"> <input type=\"submit\" class=\"go\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </form>\n";
	
	echo "      </tr>\n";
	echo "    </table>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

		
	//////////////////////////////////////////////////////////////////////////////////////////		
	// Popular News Box
	//////////////////////////////////////////////////////////////////////////////////////////		

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;MOST POPULAR COUGARS NEWS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

	if ($db->Exists("SELECT * FROM news WHERE newstype=2")) {
	$db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND views <> '0' AND newstype=2 ORDER BY views DESC LIMIT 50");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";

	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";

	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	} else {
	echo "There are no news in the database\n";
	}
}


function show_monthly_news_listing($db,$s,$id,$pr,$theyear,$themonth,$monthname)
{
	global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

	if($themonth == 1) { $monthname = 'January'; } else 
	if($themonth == 2) { $monthname = 'February'; } else 
	if($themonth == 3) { $monthname = 'March'; } else 
	if($themonth == 4) { $monthname = 'April'; } else 
	if($themonth == 5) { $monthname = 'May'; } else 
	if($themonth == 6) { $monthname = 'June'; } else 
	if($themonth == 7) { $monthname = 'July'; } else 
	if($themonth == 8) { $monthname = 'August'; } else 
	if($themonth == 9) { $monthname = 'September'; } else 
	if($themonth == 10) { $monthname = 'October'; } else 
	if($themonth == 11) { $monthname = 'November'; } else 
	if($themonth == 12) { $monthname = 'December'; } else { $monthname = ''; }
	
	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";


	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; <font class=\"10px\">$monthname $theyear</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<b class=\"16px\">$monthname $theyear News Articles</b><br><br>\n";	
	
	//////////////////////////////////////////////////////////////////////////////////////////
	// Search News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td colspan=\"2\"bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH OPTIONS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
	
	echo "  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "    <tr>\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionyear.gif\"><br>\n";
	echo "  <select name=\"theyear\">\n";

	if ($db->Exists("SELECT year(`added`) AS theyear FROM news GROUP BY theyear")) {
		$db->Query("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionmonth.gif\"><br>\n";
	echo "  <select name=\"themonth\">\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mi\">$mn</option>\n";
		}
		
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "    </td>\n";

	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"5\">\n";	
	echo "  </form>\n";

	//-------------------------------------------------
	// do the league management query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptiontype.gif\"><br>\n";
	echo "  <select name=\"type\">\n";
	
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$li = $db->data[LeagueAbbrev];
			$ln = $db->data['LeagueName'];
			echo "  <option value=\"$li\">$ln</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"7\">";
	echo "  </form>\n";

	//-------------------------------------------------
	// do the search all news box
	//-------------------------------------------------

	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <form action=\"$PHP_SELF\" validate=\"onchange\" invalidColor=\"yellow\">";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionsearch.gif\"><br>\n";	
	echo "  <input type=\"text\" name=\"search\" value=\"$search\" size=\"20\" required msg=\"Please enter a search phrase\"> <input type=\"submit\" class=\"go\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </form>\n";
	
	echo "      </tr>\n";
	echo "    </table>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
		
	//////////////////////////////////////////////////////////////////////////////////////////		
	// Monthly News Box
	//////////////////////////////////////////////////////////////////////////////////////////		

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;COUGARS NEWS ARTICLES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

	if ($db->Exists("SELECT * FROM news WHERE year(added) = $theyear and month(added) = $themonth")) {
	$db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND newstype=2 AND year(added) = $theyear AND month(added) = $themonth ORDER BY id DESC");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";

	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";


	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	} else {



	echo "<p>There are no articles for $monthname $theyear.</p>\n";

	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><br>\n";


	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	}
}


function show_full_news_listing($db,$s,$id,$pr)
{
	global $PHP_SELF;

	if ($db->Exists("SELECT * FROM news WHERE newstype=2")) {
	$db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND newstype=2 ORDER BY id DESC");
	$db->BagAndTag();

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">News</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	//////////////////////////////////////////////////////////////////////////////////////////
	// Search News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td colspan=\"2\"bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH OPTIONS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
	
	echo "  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "    <tr>\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionyear.gif\"><br>\n";
	echo "  <select name=\"theyear\">\n";

	if ($db->Exists("SELECT year(`added`) AS theyear FROM news GROUP BY theyear")) {
		$db->Query("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionmonth.gif\"><br>\n";
	echo "  <select name=\"themonth\">\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mi\">$mn</option>\n";
		}
		
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "    </td>\n";

	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"5\">\n";	
	echo "  </form>\n";

	//-------------------------------------------------
	// do the league management query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptiontype.gif\"><br>\n";
	echo "  <select name=\"type\">\n";
	
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$li = $db->data[LeagueAbbrev];
			$ln = $db->data['LeagueName'];
			echo "  <option value=\"$li\">$ln</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"7\">";
	echo "  </form>\n";

	//-------------------------------------------------
	// do the search all news box
	//-------------------------------------------------

	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <form action=\"$PHP_SELF\" validate=\"onchange\" invalidColor=\"yellow\">";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionsearch.gif\"><br>\n";	
	echo "  <input type=\"text\" name=\"search\" value=\"$search\" size=\"20\" required msg=\"Please enter a search phrase\"> <input type=\"submit\" class=\"go\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </form>\n";
	
	echo "      </tr>\n";
	echo "    </table>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	//////////////////////////////////////////////////////////////////////////////////////////		
	// Popular News Box
	//////////////////////////////////////////////////////////////////////////////////////////		

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;MOST POPULAR COUGARS NEWS</td>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\" align=\"right\">&nbsp;<a class=\"whitemain\" href=\"news.php?ccl_mode=6\">View All</a></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

	if ($db->Exists("SELECT * FROM news WHERE IsFeature != 1 AND views <> '0' AND newstype=2 ORDER BY views DESC LIMIT 5")) {
	$db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND views <> '0' AND newstype=2 ORDER BY views DESC LIMIT 5");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";

	}
}
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><br>\n";
		
		
	//////////////////////////////////////////////////////////////////////////////////////////		
	// 10 Recent News Box
	//////////////////////////////////////////////////////////////////////////////////////////		

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;MORE RECENT COUGARS NEWS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";

	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		// finish off
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

	} else {
	echo "There are no news in the database\n";
	}
}


function show_full_news($db,$s,$id,$pr)
{
	global $PHP_SELF, $greenbdr;

	$db->QueryRow("SELECT * FROM news WHERE id=$pr");
	$db->BagAndTag();

	$a = sqldate_to_string($db->data['added']);
	$t = $db->data['title'];
	$vw = $db->data['views'];
	$di = $db->data['DiscussID'];
	$pd = $db->data['picdesc'];
		
	$db->Update("UPDATE news SET views=$vw+1 WHERE id=$pr");

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"$PHP_SELF\">News</a> &raquo; <font class=\"10px\">News article</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	//echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
	
	echo "<b class=\"16px\">$t</b><br>\n";
	
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo " <tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	if ($db->data['author'] != "") echo "<i>By " . $db->data['author'] . "</i><br>\n";
	echo "<i>$a</i>\n";
	echo "  </td>\n";
	echo "  <td align=\"right\" valign=\"bottom\">\n";	
	echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_email.gif\">&nbsp;<a href=\"$PHP_SELF?news=$pr&ccl_mode=3\">Email article</a>&nbsp;&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_print.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/printnews.php?news=$pr&ccl_mode=1\">Print article</a>&nbsp;&nbsp;";
	if($di != 0) echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_members.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss article</a>";
	echo "  </td>\n";
	echo " </tr>\n";
	echo "</table>\n";

	echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";

	
	if ($db->data['picture'] != "") { 
	  echo "<table width=\"200\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"right\">\n";
	  echo " <tr>\n";
	  echo "  <td align=\"center\" valign=\"middle\">\n";
	  echo "  <div align=\"center\" class=\"photo\"><img src=\"uploadphotos/news/" . $db->data['picture'] . "\" style=\"border: 1 solid #393939\">\n";
	  if($pd != "" ) echo "<br><br><div align=\"left\">$pd</div>";
	  echo "  </div>\n";	  
	  echo "  </td>\n";
	  echo " </tr>\n";
	  echo "</table>\n";
	} else {
	  echo "";
	}
	
	echo "<p>" . $db->data['article'] . "</p>\n";	
	
	echo "<p>This article has been viewed <b>$vw</b> times!</p>\n";

	$sitevar = "http://www.coloradocricket.org/newsarchives.php?news=$pr&ccl_mode=1";
	echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
	echo "<div align=\"right\"><img src=\"http://www.coloradocricket.org/images/icons/icon_email.gif\">&nbsp;<a href=\"$PHP_SELF?news=$pr&ccl_mode=3\">Email article</a>&nbsp;&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_print.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/printnews.php?news=$pr&ccl_mode=1\">Print article</a>&nbsp;&nbsp;";
	if($di != 0) echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_members.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss article</a>";
	echo "</div>\n";

	echo "<br>\n";

	// output article
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	//////////////////////////////////////////////////////////////////////////////////////////
	// More Recent News Box
	//////////////////////////////////////////////////////////////////////////////////////////

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;LATEST NEWS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
	echo "  <table class=\"bordergrey\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->Query("SELECT * FROM news WHERE IsFeature != 1 ORDER BY id DESC LIMIT 5");

	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$db->DeBagAndTag();

		$t = $db->data['title'];
		$au = $db->data['author'];
		$id = $db->data['id'];
		$pr = $db->data['id'];
		$date = sqldate_to_string($db->data['added']);

	if($i % 2) {
	  echo "<tr class=\"trrow2\">\n";
	} else {
	  echo "<tr class=\"trrow1\">\n";
	}


	echo "<tr class=\"trrow1\">\n";
	echo "    <td width=\"100%\" width=\"65%\"><a href=\"news.php?news=$pr&ccl_mode=1\">$t</a>\n";
	if($db->data['picture'] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
	echo "    </td>\n";
	echo "  </tr>\n";

	}


	echo "<tr class=\"trrow1\">\n";
	echo "    <td width=\"100%\" colspan=\"2\" align=\"center\"><a href=\"/news.php\"><b>More News</b></a> | <a href=\"/news.php?ccl_mode=6\"><b>Popular News</b></a></td>\n";
	echo "  </tr>\n";		


		echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><BR>\n";
	

	echo "<center><script type=\"text/javascript\"><!--\n";
	echo "google_ad_client = \"pub-0747469988718903\";\n";
	echo "google_alternate_color = \"ffffff\";\n";
	echo "google_ad_width = 468;\n";
	echo "google_ad_height = 60;\n";
	echo "google_ad_format = \"468x60_as\";\n";
	echo "google_ad_type = \"text_image\";\n";
	echo "google_ad_channel =\"\";\n";
	echo "google_color_border = \"CCCCCC\";\n";
	echo "google_color_bg = \"FFFFFF\";\n";
	echo "google_color_link = \"000000\";\n";
	echo "google_color_url = \"666666\";\n";
	echo "google_color_text = \"333333\";\n";
	echo "//--></script>\n";
	echo "<script type=\"text/javascript\"\n";
	echo "  src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">\n";
	echo "</script></center>\n";


echo "  </td>\n";
echo "</tr>\n";
echo "</table>\n";


}


function search_news($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

         if (!$db->Exists("SELECT * FROM news")) {
                 echo "<p>There are currently no news.</p>\n";
                 return;
         }

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; <font class=\"10px\">Search News</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<b class=\"16px\">News Containing \"$search\"</b><br><br>\n";	

	//////////////////////////////////////////////////////////////////////////////////////////
	// Search News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td colspan=\"2\"bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH OPTIONS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
	
	echo "  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "    <tr>\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionyear.gif\"><br>\n";
	echo "  <select name=\"theyear\">\n";

	if ($db->Exists("SELECT year(`added`) AS theyear FROM news GROUP BY theyear")) {
		$db->Query("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionmonth.gif\"><br>\n";
	echo "  <select name=\"themonth\">\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mi\">$mn</option>\n";
		}
		
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "    </td>\n";

	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"5\">\n";	
	echo "  </form>\n";

	//-------------------------------------------------
	// do the league management query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptiontype.gif\"><br>\n";
	echo "  <select name=\"type\">\n";
	
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$li = $db->data[LeagueAbbrev];
			$ln = $db->data['LeagueName'];
			echo "  <option value=\"$li\">$ln</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"7\">";
	echo "  </form>\n";

	//-------------------------------------------------
	// do the search all news box
	//-------------------------------------------------

	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <form action=\"$PHP_SELF\" validate=\"onchange\" invalidColor=\"yellow\">";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionsearch.gif\"><br>\n";	
	echo "  <input type=\"text\" name=\"search\" value=\"$search\" size=\"20\" required msg=\"Please enter a search phrase\"> <input type=\"submit\" class=\"go\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </form>\n";
	
	echo "      </tr>\n";
	echo "    </table>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
	echo "  <tr>\n";
	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;NEWS ARTICLES</td>\n";
	echo "  </tr>\n";
	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";


	if ($search != "")
	{

	    $contains = "article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

		$db->Query("SELECT * FROM news WHERE $contains ORDER BY id DESC");
			if ($db->rows)
			{


			for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$a = sqldate_to_string($db->data['added']);
			$vw = $db->data['views'];
			$pr = $db->data['id'];
			$t =$db->data['title'];

			if($i % 2) {
			  echo "<tr class=\"trrow2\">\n";
			} else {
			  echo "<tr class=\"trrow1\">\n";
			}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";


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


	// echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">complete news archives &raquo;</a></p>\n";

	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
        }


 }


 function show_email($db,$s,$id,$pr)
 {
 	global $PHP_SELF, $greenbdr;


 	if ($db->Exists("SELECT * FROM news")) {
 	$db->QueryRow("SELECT * FROM news WHERE id=$pr");
 	$db->BagAndTag();

 	$news = $db->data['title'];
 	$pr = $db->data['id'];

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";


	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; <font class=\"10px\">Email article</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<b class=\"16px\">Email Article</b><br><br>\n";

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Article: $news</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"sendemailnews.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"article\" value=\"$news\">";
	echo "<input type=\"hidden\" name=\"articlelink\" value=\"http://www.coloradocricket.org/news.php?news=$pr&ccl_mode=1\">";
	echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\">Friends Email :</td>\n";
	echo "<td width=\"60%\"><input type=\"text\" name=\"friend\" size=\"30\" maxlength=\"128\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\">Your Name:</td>\n";
	echo "<td width=\"60%\"><input type=\"text\" name=\"yourname\" size=\"30\" maxlength=\"128\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"40%\" align=\"right\">Your Email:</td>\n";
	echo "<td width=\"60%\"><input type=\"text\" name=\"yourmail\" size=\"30\" maxlength=\"32\"></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td width=\"40%\">&nbsp;</td>\n";
	echo "<td width=\"60%\"><input type=\"submit\" name=\"Submit\" value=\"Submit\">&nbsp;<input type=\"reset\" name=\"reset\" value=\"Reset\"></td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>";
	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";


	echo "<p>&laquo; <a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">back to the article</a></p>\n";

	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

 	}

}


function show_type_news_listing($db,$s,$id,$pr,$type)
{
	global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; <font class=\"10px\">$type News</font></p>\n";
	echo "  </td>\n";
	//echo "  <td align=\"right\" valign=\"top\">\n";
	//require ("/home/colora12/public_html/includes/navtop.php");
	//echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "<b class=\"16px\">$type Article Archives</b><br><br>\n";

	//////////////////////////////////////////////////////////////////////////////////////////
	// Search News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td colspan=\"2\"bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH OPTIONS</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";
	
	echo "  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">\n";
	echo "    <tr>\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionyear.gif\"><br>\n";
	echo "  <select name=\"theyear\">\n";

	if ($db->Exists("SELECT year(`added`) AS theyear FROM news GROUP BY theyear")) {
		$db->Query("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionmonth.gif\"><br>\n";
	echo "  <select name=\"themonth\">\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mi\">$mn</option>\n";
		}
		
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "    </td>\n";

	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"5\">\n";	
	echo "  </form>\n";

	//-------------------------------------------------
	// do the league management query and drop down
	//-------------------------------------------------

	echo "  <form action=\"$PHP_SELF\">\n";
	echo "  <td align=\"left\" valign=\"center\">\n";	
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptiontype.gif\"><br>\n";
	echo "  <select name=\"type\">\n";
	
	if ($db->Exists("SELECT * FROM leaguemanagement")) {
		$db->Query("SELECT * FROM leaguemanagement");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$li = $db->data[LeagueAbbrev];
			$ln = $db->data['LeagueName'];
			echo "  <option value=\"$li\">$ln</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"7\">";
	echo "  </form>\n";

	//-------------------------------------------------
	// do the search all news box
	//-------------------------------------------------

	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <form action=\"$PHP_SELF\" validate=\"onchange\" invalidColor=\"yellow\">";
	echo "  <input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	echo "  <td align=\"left\" valign=\"center\">\n";
	echo "  <img src=\"http://www.coloradocricket.org/images/newsoptionsearch.gif\"><br>\n";	
	echo "  <input type=\"text\" name=\"search\" value=\"$search\" size=\"20\" required msg=\"Please enter a search phrase\"> <input type=\"submit\" class=\"go\" value=\"go\">\n";
	echo "  </td>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </form>\n";
	
	echo "      </tr>\n";
	echo "    </table>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
		
		
	//////////////////////////////////////////////////////////////////////////////////////////		
	// Type News Box
	//////////////////////////////////////////////////////////////////////////////////////////
	
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;NEWS ARTICLES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

	if ($db->Exists("SELECT * FROM news")) {
	$db->QueryRow("SELECT n.*,l.* FROM news n INNER JOIN leaguemanagement l ON n.newstype = l.LeagueID WHERE n.IsFeature != 1 AND l.LeagueAbbrev = '$type' ORDER BY n.id DESC");
	$db->BagAndTag();
	

	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$t = htmlentities(stripslashes($db->data['title']));
		$pr = htmlentities(stripslashes($db->data['id']));
		$a = sqldate_to_string($db->data['added']);
		$id = $db->data['id'];
		$vw = $db->data['views'];

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow2\">\n";
		} else {
		  echo "<tr class=\"trrow1\">\n";
		}

		echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
		echo "    <td align=\"right\" class=\"9px\">$a</td>\n";		
		echo "  </tr>\n";

	}

	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><br>\n";

		// finish off
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

	} else {
	echo "There are no news in the database\n";
	}
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
	show_top20_news_listing($db,$s,$id,$news,$mn);
	break;
case 1:
	show_full_news($db,$s,$id,$news);
	break;
case 2:
	search_news($db,$search);
	break;
case 3:
	show_email($db,$s,$id,$news);
	break;
case 4:
	show_full_news_listing($db,$s,$id,$news);
	break;
case 5:
	show_monthly_news_listing($db,$s,$id,$news,$theyear,$themonth,$monthname);
	break;
case 6:
	show_popular_news_listing($db,$s,$id,$news);
	break;
case 7:
	show_type_news_listing($db,$s,$id,$news,$type);
	break;	
default:
	show_top20_news_listing($db,$s,$id,$news);
	break;
}


?>
