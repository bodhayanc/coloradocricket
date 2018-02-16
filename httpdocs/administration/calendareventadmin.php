<?php

//----------------------------------------------------------------------------------------
// Site Control v3.0
//
// (c) Andrew Collington - amnuts@amnuts.com
// (c) Michael Doig      - michael@gmail.com
//----------------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - RECENT ENTRIES FORM
//////////////////////////////////////////////////////////////////////////////////////////

function show_recent_main_menu($db)
{
	global $bluebdr, $action,$SID;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add an event</a></p>\n";

//----------------------------------------------------------------------------------------
// check for empty database
//----------------------------------------------------------------------------------------

	if (!$db->Exists("SELECT * FROM extcal_events")) {
	  echo "<p>There are currently no events in the database.</p>\n";
	  return;
	} else {

//----------------------------------------------------------------------------------------
// search form
//----------------------------------------------------------------------------------------

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" bordercolor=\"$bluebdr\">\n";
	echo " <tr>\n";
	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\">&nbsp;Search Event Archives</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">\n";

	echo "  <form action=\"main.php\">";
	echo "  <input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "  <input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "  <input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "  <br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";
	echo "  </form>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

//----------------------------------------------------------------------------------------
// events by month box
//----------------------------------------------------------------------------------------

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" bordercolor=\"$bluebdr\">\n";
	echo " <tr>\n";
	echo "  <td colspan=\"3\" class=\"whitemain\" bgcolor=\"$bluebdr\">&nbsp;Events by Month</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";
	echo "  <form action=\"main.php\" method=\"get\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "  <input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "  <input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "  <input type=\"hidden\" name=\"do\" value=\"months\">\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <select name=\"theyear\" required msg=\"Please select a year\">\n";
	echo "	<option value=\"\">year</option>\n";
	echo "	<option value=\"\">----</option>\n";
	if ($db->Exists("SELECT yearid AS theyear FROM year")) {
		$db->Query("SELECT yearid AS theyear FROM year ORDER BY yearid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <select name=\"themonth\" required msg=\"Please select a month\">\n";
	echo "	<option value=\"\">Month</option>\n";
	echo "	<option value=\"\">-----</option>\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mn\">$ma</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">	\n";
	echo "  </form>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </td>\n";
	echo " </tr>\n";
	echo "</table><br>\n";

//----------------------------------------------------------------------------------------
// 10 recent events
//----------------------------------------------------------------------------------------

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Recent Entries</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
      	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	//-------------------------------------------------
	// query the database
	//-------------------------------------------------

	$db->Query("SELECT ev.*, ca.* FROM extcal_events ev INNER JOIN extcal_categories ca ON ev.cat = ca.cat_id ORDER BY ev.id DESC LIMIT 20");
	 for ($x=0; $x<$db->rows; $x++) {
	 $db->GetRow($x);

	//-------------------------------------------------
	// setup the variables
	//-------------------------------------------------

	  $ti = htmlentities(stripslashes($db->data['title']));
	  $id = htmlentities(stripslashes($db->data['id']));
	  $da = sqldate_to_string($db->data[start_date]);
	  $ci = htmlentities(stripslashes($db->data[cat_id]));
	  $cn = htmlentities(stripslashes($db->data[cat_name]));
	  $co = htmlentities(stripslashes($db->data[color]));

	//-------------------------------------------------
	// output
	//-------------------------------------------------

	  echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';
	  echo "  <td align=\"left\" valign=\"top\" width=\"6\" bgcolor=\"$co\"><img src=\"/images/spacer.gif\" alt=\"$cn\" width=\"6\"></td>\n";
	  echo "  <td align=\"left\" valign=\"top\">$ti</td>\n";
	  echo "  <td align=\"left\" valign=\"top\">$da</td>\n";
	  echo "  <td align=\"right\" valign=\"top\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a></td>\n";
	  echo " </tr>\n";
	}

	echo "</table>\n";
	
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	}
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - ARCHIVES BY MONTHS FORM
//////////////////////////////////////////////////////////////////////////////////////////

function show_months_main_menu($db,$theyear,$themonth)
{
	global $bluebdr, $action,$SID;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add an event</a></p>\n";

//----------------------------------------------------------------------------------------
// check for empty database
//----------------------------------------------------------------------------------------

	if (!$db->Exists("SELECT * FROM extcal_events")) {
	  echo "<p>There are currently no events in the database.</p>\n";
	  return;
	} else {

//----------------------------------------------------------------------------------------
// search form
//----------------------------------------------------------------------------------------

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" bordercolor=\"$bluebdr\">\n";
	echo " <tr>\n";
	echo "  <td class=\"whitemain\" bgcolor=\"$bluebdr\">&nbsp;Search Event Archives</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">\n";

	echo "  <form action=\"main.php\">";
	echo "  <input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "  <input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "  <input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "  <br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";
	echo "  </form>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

//----------------------------------------------------------------------------------------
// events by month box
//----------------------------------------------------------------------------------------

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" bordercolor=\"$bluebdr\">\n";
	echo " <tr>\n";
	echo "  <td colspan=\"3\" class=\"whitemain\" bgcolor=\"$bluebdr\">&nbsp;Events by Month</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";
	echo "  <form action=\"main.php\" method=\"get\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "  <input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "  <input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "  <input type=\"hidden\" name=\"do\" value=\"months\">\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <select name=\"theyear\" required msg=\"Please select a year\">\n";
	echo "	<option value=\"\">year</option>\n";
	echo "	<option value=\"\">----</option>\n";
	if ($db->Exists("SELECT yearid AS theyear FROM year")) {
		$db->Query("SELECT yearid AS theyear FROM year ORDER BY yearid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <select name=\"themonth\" required msg=\"Please select a month\">\n";
	echo "	<option value=\"\">Month</option>\n";
	echo "	<option value=\"\">-----</option>\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mn\">$ma</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">	\n";
	echo "  </form>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </td>\n";
	echo " </tr>\n";
	echo "</table><br>\n";

//----------------------------------------------------------------------------------------
// month specific events
//----------------------------------------------------------------------------------------

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$themonth $theyear Events</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
      	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	//-------------------------------------------------
	// query the database
	//-------------------------------------------------

	if (!$db->Exists("SELECT ev.*, ca.* FROM extcal_events ev INNER JOIN extcal_categories ca ON ev.cat = ca.cat_id WHERE year(ev.start_date) = $theyear AND date_format(ev.start_date,'%M') = '$themonth' ORDER BY ev.start_date")) {

	  echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';
	  echo "  <td align=\"left\" valign=\"top\" colspan=\"3\">There are no events for $themonth $theyear</td>\n";
	  echo " </tr>\n";

	} else {

	$db->Query("SELECT ev.*, ca.* FROM extcal_events ev INNER JOIN extcal_categories ca ON ev.cat = ca.cat_id WHERE year(ev.start_date) = $theyear AND date_format(ev.start_date,'%M') = '$themonth' ORDER BY ev.start_date");
	 for ($x=0; $x<$db->rows; $x++) {
	 $db->GetRow($x);

	//-------------------------------------------------
	// setup the variables
	//-------------------------------------------------

	  $ti = htmlentities(stripslashes($db->data['title']));
	  $id = htmlentities(stripslashes($db->data['id']));
	  $da = sqldate_to_string($db->data[start_date]);
	  $ci = htmlentities(stripslashes($db->data[cat_id]));
	  $cn = htmlentities(stripslashes($db->data[cat_name]));
	  $co = htmlentities(stripslashes($db->data[color]));


	//-------------------------------------------------
	// output
	//-------------------------------------------------

	  echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';
	  echo "  <td align=\"left\" valign=\"top\" width=\"6\" bgcolor=\"$co\"><img src=\"/images/spacer.gif\" alt=\"$cn\" width=\"6\"></td>\n";
	  echo "  <td align=\"left\" valign=\"top\">$ti</td>\n";
	  echo "  <td align=\"left\" valign=\"top\">$da</td>\n";
	  echo "  <td align=\"right\" valign=\"top\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a></td>\n";
	  echo " </tr>\n";
	  }
	}

	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	}
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - SEARCH RESULTS FORM
//////////////////////////////////////////////////////////////////////////////////////////

function show_search_main_menu($db,$search="",$theyear,$themonth)
{
	global $bluebdr, $action,$SID;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add an event</a></p>\n";

//----------------------------------------------------------------------------------------
// check for empty database
//----------------------------------------------------------------------------------------

	if (!$db->Exists("SELECT * FROM extcal_events")) {
	  echo "<p>There are currently no events in the database.</p>\n";
	  return;
	} else {

//----------------------------------------------------------------------------------------
// search form
//----------------------------------------------------------------------------------------

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" bordercolor=\"$bluebdr\">\n";
	echo " <tr>\n";
	echo "  <td class=\"whitemain\" bgcolor=\"$bluebdr\">&nbsp;Search Event Archives</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">\n";

	echo "  <form action=\"main.php\">";
	echo "  <input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "  <input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "  <input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "  <br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";
	echo "  </form>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

//----------------------------------------------------------------------------------------
// events by month box
//----------------------------------------------------------------------------------------

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" bordercolor=\"$bluebdr\">\n";
	echo " <tr>\n";
	echo "  <td colspan=\"3\" class=\"whitemain\" bgcolor=\"$bluebdr\">&nbsp;Events by Month</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";
	echo "  <form action=\"main.php\" method=\"get\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "  <input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "  <input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "  <input type=\"hidden\" name=\"do\" value=\"months\">\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <select name=\"theyear\" required msg=\"Please select a year\">\n";
	echo "	<option value=\"\">year</option>\n";
	echo "	<option value=\"\">----</option>\n";
	if ($db->Exists("SELECT yearid AS theyear FROM year")) {
		$db->Query("SELECT yearid AS theyear FROM year ORDER BY yearid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>\n";

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <select name=\"themonth\" required msg=\"Please select a month\">\n";
	echo "	<option value=\"\">Month</option>\n";
	echo "	<option value=\"\">-----</option>\n";
	if ($db->Exists("SELECT monthid AS themonth, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mn\">$ma</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  <input type=\"submit\" value=\"go\">	\n";
	echo "  </form>\n";
	echo "  <script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";
	echo "  </td>\n";
	echo " </tr>\n";
	echo "</table><br>\n";

//----------------------------------------------------------------------------------------
// search result events
//----------------------------------------------------------------------------------------

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Events Containing \"$search\"</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
      	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	//-------------------------------------------------
	// query the database
	//-------------------------------------------------

	  $db->Query("SELECT ev.*, ca.* FROM extcal_events ev INNER JOIN extcal_categories ca ON ev.cat = ca.cat_id WHERE ev.title LIKE '%{$search}%' OR ev.description LIKE '%{$search}%' ORDER BY ev.start_date DESC");
	  if ($db->rows) {

	  for ($i=0; $i<$db->rows; $i++) {
	  $db->GetRow($i);

	//-------------------------------------------------
	// setup the variables
	//-------------------------------------------------

	  $ti = htmlentities(stripslashes($db->data['title']));
	  $id = htmlentities(stripslashes($db->data['id']));
	  $da = sqldate_to_string($db->data[start_date]);
	  $ci = htmlentities(stripslashes($db->data[cat_id]));
	  $cn = htmlentities(stripslashes($db->data[cat_name]));
	  $co = htmlentities(stripslashes($db->data[color]));

	//-------------------------------------------------
	// output
	//-------------------------------------------------

	  echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';
	  echo "  <td align=\"left\" valign=\"top\" width=\"6\" bgcolor=\"$co\"><img src=\"/images/spacer.gif\" alt=\"$cn\" width=\"6\"></td>\n";
	  echo "  <td align=\"left\" valign=\"top\">$ti</td>\n";
	  echo "  <td align=\"left\" valign=\"top\">$da</td>\n";
	  echo "  <td align=\"right\" valign=\"top\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a></td>\n";
	  echo " </tr>\n";
	  }

	} else {

	  echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';
	  echo "  <td align=\"left\" valign=\"top\" colspan=\"3\">There are no events matching that query in any way.</td>\n";
	}

	echo "</table>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	}
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - ADD ENTRY FORM
//////////////////////////////////////////////////////////////////////////////////////////

function add_category_form($db)
{
global $bluebdr, $action,$SID;

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add an event</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";


	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" align=\"left\">\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">event title</td>\n";
	echo "  <td class=\"trrow1\"><input type=\"text\" name=\"title\" size=\"35\" maxlength=\"255\" required msg=\"Please enter an event title\"></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">event description</td>\n";
	echo "  <td class=\"trrow1\"><textarea name=\"description\" id=\"myText\" cols=\"50\" rows=\"15\" wrap=\"virtual\" required msg=\"Please enter an event description\"></textarea></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">&nbsp;</td>\n";
	echo "  <td class=\"trrow1\">";

	//-------------------------------------------------
	// Rich Text Format Buttons
	//-------------------------------------------------

	echo "<table name=\"tblForm\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">&nbsp;\n";
	echo "<img type=\"image\" src=\"/images/b_off.gif\" name=\"btnBold\" id=\"btnBold\" onClick=\"add_bold_text();\" srcover=\"/images/b_on.gif\" srcdown=\"/images/b_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/i_off.gif\" name=\"btnItalic\" id=\"btnItalic\" onClick=\"add_italic_text();\" srcover=\"/images/i_on.gif\" srcdown=\"/images/i_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/u_off.gif\" name=\"btnUnderline\" id=\"btnUnderline\" onClick=\"add_underline_text();\" srcover=\"/images/u_on.gif\" srcdown=\"/images/u_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/list_off.gif\" name=\"btnList\" id=\"btnList\" onClick=\"add_list_text();\" srcover=\"/images/list_on.gif\" srcdown=\"/images/list_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/url_off.gif\" name=\"btnUrl\" id=\"btnUrl\" onClick=\"add_url_text();\" srcover=\"/images/url_on.gif\" srcdown=\"/images/url_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/img_off.gif\" name=\"btnImg\" id=\"btnImg\" onClick=\"add_img_text();\" srcover=\"/images/img_on.gif\" srcdown=\"/images/img_down.gif\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo " </tr>\n";

	//-------------------------------------------------
	// get calendar categories
	//-------------------------------------------------

	echo " <tr>\n";
	echo "  <td class=\"trrow1\">category</td>\n";

	echo "  <td class=\"trrow1\">";
	echo "  <select name=\"cat\" required msg=\"Please select an event category\">\n";
	echo "	<option value=\"\">Select Category</option>\n";
	echo "	<option value=\"\">---------------</option>\n";
	if ($db->Exists("SELECT * FROM extcal_categories")) {
		$db->Query("SELECT * FROM extcal_categories ORDER BY cat_name");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			echo "  <option value=\"" . $db->data[cat_id] . "\">" . $db->data[cat_name] . "</option>\n";
		}
	}
	echo "  </select>\n";
	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"trrow1\" rowspan=\"5\">event date</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trbottom\">start time</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";

	//-------------------------------------------------
	// do the day drop down
	//-------------------------------------------------

	echo "  <select name=\"day\" required msg=\"Please select a day\">\n";
	echo "  <option value=\"00\">Day</option>\n";
	echo "  <option value=\"01\" >01</option>\n";
	echo "  <option value=\"02\" >02</option>\n";
	echo "  <option value=\"03\" >03</option>\n";
	echo "  <option value=\"04\" >04</option>\n";
	echo "  <option value=\"05\" >05</option>\n";
	echo "  <option value=\"06\" >06</option>\n";
	echo "  <option value=\"07\" >07</option>\n";
	echo "  <option value=\"08\" >08</option>\n";
	echo "  <option value=\"09\" >09</option>\n";
	echo "  <option value=\"10\" >10</option>\n";
	echo "  <option value=\"11\" >11</option>\n";
	echo "  <option value=\"12\" >12</option>\n";
	echo "  <option value=\"13\" >13</option>\n";
	echo "  <option value=\"14\" >14</option>\n";
	echo "  <option value=\"15\" >15</option>\n";
	echo "  <option value=\"16\" >16</option>\n";
	echo "  <option value=\"17\" >17</option>\n";
	echo "  <option value=\"18\" >18</option>\n";
	echo "  <option value=\"19\" >19</option>\n";
	echo "  <option value=\"20\" >20</option>\n";
	echo "  <option value=\"21\" >21</option>\n";
	echo "  <option value=\"22\" >22</option>\n";
	echo "  <option value=\"23\" >23</option>\n";
	echo "  <option value=\"24\" >24</option>\n";
	echo "  <option value=\"25\" >25</option>\n";
	echo "  <option value=\"26\" >26</option>\n";
	echo "  <option value=\"27\" >27</option>\n";
	echo "  <option value=\"28\" >28</option>\n";
	echo "  <option value=\"29\" >29</option>\n";
	echo "  <option value=\"30\" >30</option>\n";
	echo "  <option value=\"31\" >31</option>\n";
	echo "  </select>&nbsp;\n";

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <select name=\"month\" required msg=\"Please select a month\">\n";
	echo "	<option value=\"\">Month</option>\n";
	echo "	<option value=\"\">-----</option>\n";
	if ($db->Exists("SELECT monthid AS themonth, monthnum, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, monthnum, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$mu = $db->data[monthnum];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mu\">$mn</option>\n";
		}
	}
	echo "  </select>&nbsp;\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <select name=\"year\" required msg=\"Please select a year\">\n";
	echo "	<option value=\"\">year</option>\n";
	echo "	<option value=\"\">----</option>\n";
	if ($db->Exists("SELECT yearid AS theyear FROM year")) {
		$db->Query("SELECT yearid AS theyear FROM year ORDER BY yearid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>&nbsp;<a href=\"javascript:void(0)\" onclick=\"displayCalendarSelectBox(document.forms[0].year,document.forms[0].month,document.forms[0].day,this)\"><img src=\"/images/popup_calendar.gif\" border=\"0\"></a>&nbsp;\n";
	echo "  at\n";

	//-------------------------------------------------
	// do the hour drop down
	//-------------------------------------------------

	echo "  <select name=\"start_time_hour\">\n";
	echo "  <option value=\"1\" >01</option>\n";
	echo "  <option value=\"2\" >02</option>\n";
	echo "  <option value=\"3\" >03</option>\n";
	echo "  <option value=\"4\" >04</option>\n";
	echo "  <option value=\"5\" >05</option>\n";
	echo "  <option value=\"6\" >06</option>\n";
	echo "  <option value=\"7\" >07</option>\n";
	echo "  <option value=\"8\" selected>08</option>\n";
	echo "  <option value=\"9\" >09</option>\n";
	echo "  <option value=\"10\" >10</option>\n";
	echo "  <option value=\"11\" >11</option>\n";
	echo "  <option value=\"12\" >12</option>\n";
	echo "  </select>\n";

	//-------------------------------------------------
	// do the minutes drop down
	//-------------------------------------------------

	echo "  <select name=\"start_time_minute\">\n";
	echo "  <option value=\"0\" selected>00</option>\n";
	echo "  <option value=\"15\" >15</option>\n";
	echo "  <option value=\"30\" >30</option>\n";
	echo "  <option value=\"45\" >45</option>\n";
	echo "  </select>\n";

	//-------------------------------------------------
	// do the AM v PM drop down
	//-------------------------------------------------

	echo "  <select name=\"start_time_ampm\" class=\"listbox\">\n";
	echo "  <option value=\"am\" selected>AM</option>\n";
	echo "  <option value=\"pm\" >PM</option>\n";
	echo "  </select>\n";

	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"trbottom\">duration</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";
	echo "  <input type=\"text\" name=\"end_days\" class=\"textinput\" value=\"0\" size=\"3\">&nbsp;Days&nbsp;&nbsp;\n";
	echo "  <input type=\"text\" name=\"end_hours\" class=\"textinput\" value=\"1\" size=\"3\">&nbsp;Hours&nbsp;&nbsp;\n";
	echo "  <input type=\"text\" name=\"end_minutes\" class=\"textinput\" value=\"0\" size=\"3\">&nbsp;Minutes&nbsp;&nbsp;\n";
	echo "  </td>\n";
	echo " </tr>\n";
	echo "</table>\n";
	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"whitemain\" bgcolor=\"$bluebdr\" height=\"23\">&nbsp;Contact Details</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">\n";

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" align=\"left\">\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">contact info</td>\n";
	echo "  <td class=\"trrow1\"><textarea name=\"contact\" cols=\"50\" rows=\"15\" wrap=\"virtual\"></textarea></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">email</td>\n";
	echo "  <td class=\"trrow1\"><input type=\"text\" name=\"email\" size=\"35\" maxlength=\"255\"></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">url</td>\n";
	echo "  <td class=\"trrow1\"><input type=\"text\" name=\"url\" size=\"35\" maxlength=\"255\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"trrow1\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"add event\"> &nbsp; <input type=\"reset\" value=\"reset form\"></td>\n";
	echo " </tr>\n";
	echo "</table>\n";

	echo "</form>\n";
	echo "<script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - ADD TO DATABASE
//////////////////////////////////////////////////////////////////////////////////////////

function do_add_category($db,$title,$description,$cat,$day,$month,$year,$contact,$email,$url,$start_time_hour,$start_time_minute,$start_time_ampm,$end_days,$end_hours,$end_minutes)
{
global $bluebdr, $action,$SID;

//----------------------------------------------------------------------------------------
// setup variables
//----------------------------------------------------------------------------------------

	$ti  = addslashes(trim($title));
	$de  = addslashes(trim($description));
	$ca  = addslashes(trim($cat));
	$da  = addslashes(trim($day));
	$mo  = addslashes(trim($month));
	$ye  = addslashes(trim($year));
	$co  = addslashes(trim($contact));
	$el  = addslashes(trim($email));
	$ur  = addslashes(trim($url));
	$sh  = addslashes(trim($start_time_hour));
	$sm  = addslashes(trim($start_time_minute));
	$sa  = addslashes(trim($start_time_ampm));
	$ed  = addslashes(trim($end_days));
	$eh  = addslashes(trim($end_hours));
	$em  = addslashes(trim($end_minutes));

	$start_time_hour = extcal_12to24hour($sh, $sa);
	$start_date = date("Y-m-d H:i:s", mktime($start_time_hour, $sm, 0, $mo, $da, $ye));

	if($ed > 0 && !$eh && !$em) {
		$ed--; // to make sure not to jump to the next day, we push the time to 23:59:59
		$total_hours = 23;
		$total_minutes = 59;
		$total_seconds = 59;
	} else {
		$total_hours = $start_time_hour + $eh;
		$total_minutes = $sm + $em;
		$total_seconds = 0;
	}
	$end_date = date("Y-m-d H:i:s", mktime( $total_hours, $total_minutes, $total_seconds, $mo, $da + $ed, $ye));


//----------------------------------------------------------------------------------------
// insert into database
//----------------------------------------------------------------------------------------

	$db->Insert("INSERT INTO extcal_events (title,description,cat,day,month,year,start_date,end_date,contact,email,url,approved) VALUES ('$ti','$de','$ca','$da','$mo','$ye','$start_date','$end_date','$co','$el','$ur',1)");

	if ($db->a_rows != -1) {
	  echo "<p>You have now added a new event.</p>\n";
	  echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another event</a></p>\n";
	  echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to event list</a></p>\n";
	} else {
	  echo "<p>The event could not be added to the database at this time.</p>\n";
	  echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to event list</a></p>\n";
	}
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - DELETE ENTRY FORM
//////////////////////////////////////////////////////////////////////////////////////////

function delete_category_check($db,$id)
{
	global $bluebdr, $action,$SID;

	//-------------------------------------------------
	// query the database
	//-------------------------------------------------

	$title = htmlentities(stripslashes($db->QueryItem("SELECT title FROM extcal_events WHERE id=$id")));

	//-------------------------------------------------
	// output
	//-------------------------------------------------

	echo "<p>Are you sure you wish to delete the event titled:</p>\n";
	echo "<p><b>$title</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a>  | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - DELETE FROM DATABASE
//////////////////////////////////////////////////////////////////////////////////////////

function do_delete_category($db,$id,$doit)
{
	global $bluebdr, $action,$SID;

	//-------------------------------------------------
	// cancel delete
	//-------------------------------------------------

	if (!$doit) echo "<p>You have chosen NOT to delete that event.</p>\n";
	else {

	//-------------------------------------------------
	// do the delete
	//-------------------------------------------------

		$db->Delete("DELETE FROM extcal_events WHERE id=$id");
		echo "<p>You have now deleted that event.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the event listing</a></p>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - EDIT ARTICLE FORM
//////////////////////////////////////////////////////////////////////////////////////////

function edit_category_form($db,$id)
{
	global $bluebdr, $action,$SID;

//----------------------------------------------------------------------------------------
// get all categories
//----------------------------------------------------------------------------------------

	$db->Query("SELECT * FROM extcal_categories ORDER BY cat_id");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$category[$db->data[cat_id]] = $db->data[cat_name];
	}

//----------------------------------------------------------------------------------------
// query database
//----------------------------------------------------------------------------------------

	$db->QueryRow("SELECT * FROM extcal_events WHERE id=$id");

//----------------------------------------------------------------------------------------
// setup the variables
//----------------------------------------------------------------------------------------

	$ti  = htmlentities(stripslashes($db->data['title']));
	$de  = htmlentities(stripslashes($db->data['description']));
	$ca  = htmlentities(stripslashes($db->data[cat]));
	$co  = htmlentities(stripslashes($db->data[contact]));
	$el  = htmlentities(stripslashes($db->data[email]));
	$ur  = htmlentities(stripslashes($db->data['url']));
	$sm  = htmlentities(stripslashes($db->data[start_time_minute]));

	$sd  = htmlentities(stripslashes($db->data[start_date]));
	$ed  = htmlentities(stripslashes($db->data[end_date]));

	$sh = date("g",strtotime($sd));
	$sm = date("i",strtotime($sd));
	$sa = date("a",strtotime($sd));
	$da = date("d",strtotime($sd));
	$mo = date("m",strtotime($sd));
	$ye = date("Y",strtotime($sd));
	$mp = date("F",strtotime($sd));

	$duration_array = datestoduration ($sd,$ed);

	$ed = $duration_array[days];
	$eh = $duration_array[hours];
	$em = $duration_array[minutes];

//----------------------------------------------------------------------------------------
// output
//----------------------------------------------------------------------------------------

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Event</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";


	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\" validate=\"onchange\" invalidColor=\"yellow\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" align=\"left\">\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">event titles</td>\n";
	echo "  <td class=\"trrow1\"><input type=\"text\" name=\"title\" size=\"35\" maxlength=\"255\" value=\"$ti\" required msg=\"Please enter an event title\"></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">event descriptions</td>\n";
	echo "  <td class=\"trrow1\"><textarea name=\"description\" id=\"myText\" cols=\"50\" rows=\"15\" wrap=\"virtual\" required msg=\"Please enter an event description\">$de</textarea></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">&nbsp;</td>\n";
	echo "  <td class=\"trrow1\">";

	//-------------------------------------------------
	// Rich Text Format Buttons
	//-------------------------------------------------

	echo "<table name=\"tblForm\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">&nbsp;\n";
	echo "<img type=\"image\" src=\"/images/b_off.gif\" name=\"btnBold\" id=\"btnBold\" onClick=\"add_bold_text();\" srcover=\"/images/b_on.gif\" srcdown=\"/images/b_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/i_off.gif\" name=\"btnItalic\" id=\"btnItalic\" onClick=\"add_italic_text();\" srcover=\"/images/i_on.gif\" srcdown=\"/images/i_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/u_off.gif\" name=\"btnUnderline\" id=\"btnUnderline\" onClick=\"add_underline_text();\" srcover=\"/images/u_on.gif\" srcdown=\"/images/u_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/list_off.gif\" name=\"btnList\" id=\"btnList\" onClick=\"add_list_text();\" srcover=\"/images/list_on.gif\" srcdown=\"/images/list_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/url_off.gif\" name=\"btnUrl\" id=\"btnUrl\" onClick=\"add_url_text();\" srcover=\"/images/url_on.gif\" srcdown=\"/images/url_down.gif\">\n";
	echo "<img type=\"image\" src=\"/images/img_off.gif\" name=\"btnImg\" id=\"btnImg\" onClick=\"add_img_text();\" srcover=\"/images/img_on.gif\" srcdown=\"/images/img_down.gif\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo " </tr>\n";

	//-------------------------------------------------
	// get calendar categories
	//-------------------------------------------------

	echo " <tr>\n";
	echo "  <td class=\"trrow1\">category</td>\n";

	echo "  <td class=\"trrow1\">";

	echo "  <select name=\"cat\" required msg=\"Please select an event category\">\n";
	echo "  <option value=\"\">Select Category</option>\n";
	echo "  <option value=\"\">---------------</option>\n";
	  for ($i=1; $i<=count($category); $i++) {
	echo "  <option value=\"$i\"" . ($i==$db->data[cat]?" selected":"") . ">" . $category[$i] . "</option>\n";
	  }
	echo "  </select>\n";

	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"trrow1\" rowspan=\"5\">event date</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trbottom\">start time</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";

	//-------------------------------------------------
	// do the day drop down
	//-------------------------------------------------

	echo "  <select name=\"day\">\n";
	echo "  <option value=\"00\">Day</option>\n";
	echo "  <option value=\"$da\" selected>$da</option>\n";
	echo "  <option value=\"01\" >01</option>\n";
	echo "  <option value=\"02\" >02</option>\n";
	echo "  <option value=\"03\" >03</option>\n";
	echo "  <option value=\"04\" >04</option>\n";
	echo "  <option value=\"05\" >05</option>\n";
	echo "  <option value=\"06\" >06</option>\n";
	echo "  <option value=\"07\" >07</option>\n";
	echo "  <option value=\"08\" >08</option>\n";
	echo "  <option value=\"09\" >09</option>\n";
	echo "  <option value=\"10\" >10</option>\n";
	echo "  <option value=\"11\" >11</option>\n";
	echo "  <option value=\"12\" >12</option>\n";
	echo "  <option value=\"13\" >13</option>\n";
	echo "  <option value=\"14\" >14</option>\n";
	echo "  <option value=\"15\" >15</option>\n";
	echo "  <option value=\"16\" >16</option>\n";
	echo "  <option value=\"17\" >17</option>\n";
	echo "  <option value=\"18\" >18</option>\n";
	echo "  <option value=\"19\" >19</option>\n";
	echo "  <option value=\"20\" >20</option>\n";
	echo "  <option value=\"21\" >21</option>\n";
	echo "  <option value=\"22\" >22</option>\n";
	echo "  <option value=\"23\" >23</option>\n";
	echo "  <option value=\"24\" >24</option>\n";
	echo "  <option value=\"25\" >25</option>\n";
	echo "  <option value=\"26\" >26</option>\n";
	echo "  <option value=\"27\" >27</option>\n";
	echo "  <option value=\"28\" >28</option>\n";
	echo "  <option value=\"29\" >29</option>\n";
	echo "  <option value=\"30\" >30</option>\n";
	echo "  <option value=\"31\" >31</option>\n";
	echo "  </select>&nbsp;\n";

	//-------------------------------------------------
	// do the month query and drop down
	//-------------------------------------------------

	echo "  <select name=\"month\" required msg=\"Please select a month\">\n";
	echo "	<option value=\"$mo\" selected>$mp</option>\n";
	echo "	<option value=\"\">-----</option>\n";
	if ($db->Exists("SELECT monthid AS themonth, monthnum, abbrev, title AS monthname FROM month")) {
		$db->Query("SELECT monthid AS themonth, monthnum, abbrev, title AS monthname FROM month ORDER BY monthid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$mi = $db->data['themonth'];
			$mn = $db->data[monthname];
			$mu = $db->data[monthnum];
			$ma = $db->data[abbrev];
			echo "  <option value=\"$mu\">$mn</option>\n";
		}
	}
	echo "  </select>&nbsp;\n";

	//-------------------------------------------------
	// do the year query and drop down
	//-------------------------------------------------

	echo "  <select name=\"year\" required msg=\"Please select a year\">\n";
	echo "	<option value=\"$ye\" selected>$ye</option>\n";
	echo "	<option value=\"\">----</option>\n";
	if ($db->Exists("SELECT yearid AS theyear FROM year")) {
		$db->Query("SELECT yearid AS theyear FROM year ORDER BY yearid");
		for ($g=0; $g<$db->rows; $g++) {
			$db->GetRow($g);
			$ty = $db->data['theyear'];
			echo "  <option value=\"$ty\">$ty</option>\n";
		}
	}
	echo "  </select>&nbsp;&nbsp;\n";
	echo "  </select>&nbsp;<a href=\"javascript:void(0)\" onclick=\"displayCalendarSelectBox(document.forms[0].year,document.forms[0].month,document.forms[0].day,this)\"><img src=\"/images/popup_calendar.gif\" border=\"0\"></a>&nbsp;\n";
	echo "  at\n";

	//-------------------------------------------------
	// do the hour drop down
	//-------------------------------------------------

	echo "  <select name=\"start_time_hour\">\n";
	echo "  <option value=\"$sh\" >$sh</option>\n";
	echo "  <option value=\"1\" >01</option>\n";
	echo "  <option value=\"2\" >02</option>\n";
	echo "  <option value=\"3\" >03</option>\n";
	echo "  <option value=\"4\" >04</option>\n";
	echo "  <option value=\"5\" >05</option>\n";
	echo "  <option value=\"6\" >06</option>\n";
	echo "  <option value=\"7\" >07</option>\n";
	echo "  <option value=\"8\">08</option>\n";
	echo "  <option value=\"9\" >09</option>\n";
	echo "  <option value=\"10\" >10</option>\n";
	echo "  <option value=\"11\" >11</option>\n";
	echo "  <option value=\"12\" >12</option>\n";
	echo "  </select>\n";

	//-------------------------------------------------
	// do the minutes drop down
	//-------------------------------------------------

	echo "  <select name=\"start_time_minute\">\n";
	echo "  <option value=\"$sm\">$sm</option>\n";
	echo "  <option value=\"0\">00</option>\n";
	echo "  <option value=\"15\" >15</option>\n";
	echo "  <option value=\"30\" >30</option>\n";
	echo "  <option value=\"45\" >45</option>\n";
	echo "  </select>\n";

	//-------------------------------------------------
	// do the AM v PM drop down
	//-------------------------------------------------

	echo "  <select name=\"start_time_ampm\" class=\"listbox\">\n";
	echo "  <option value=\"$sa\">$sa</option>\n";
	echo "  <option value=\"am\">AM</option>\n";
	echo "  <option value=\"pm\" >PM</option>\n";
	echo "  </select>\n";

	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"trbottom\">duration</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">";
	echo "  <input type=\"text\" name=\"end_days\" class=\"textinput\" value=\"$ed\" size=\"3\">&nbsp;Days&nbsp;&nbsp;\n";
	echo "  <input type=\"text\" name=\"end_hours\" class=\"textinput\" value=\"$eh\" size=\"3\">&nbsp;Hours&nbsp;&nbsp;\n";
	echo "  <input type=\"text\" name=\"end_minutes\" class=\"textinput\" value=\"$em\" size=\"3\">&nbsp;Minutes&nbsp;&nbsp;\n";
	echo "  </td>\n";
	echo " </tr>\n";
	echo "</table>\n";
	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"whitemain\" bgcolor=\"$bluebdr\" height=\"23\">&nbsp;Contact Details</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">\n";

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" align=\"left\">\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">contact info</td>\n";
	echo "  <td class=\"trrow1\"><textarea name=\"contact\" cols=\"50\" rows=\"15\" wrap=\"virtual\">$co</textarea></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">email</td>\n";
	echo "  <td class=\"trrow1\"><input type=\"text\" name=\"email\" size=\"35\" maxlength=\"255\" value=\"$el\"></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td class=\"trrow1\">url</td>\n";
	echo "  <td class=\"trrow1\"><input type=\"text\" name=\"url\" size=\"35\" maxlength=\"255\" value=\"$ur\"></td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td class=\"trrow1\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"edit event\"> &nbsp; <input type=\"reset\" value=\"reset form\"></td>\n";
	echo " </tr>\n";
	echo "</table>\n";

	echo "</form>\n";
	echo "<script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR EVENT ADMIN - UPDATE DATABASE
//////////////////////////////////////////////////////////////////////////////////////////

function do_update_category($db,$id,$title,$description,$cat,$day,$month,$year,$contact,$email,$url,$start_time_hour,$start_time_minute,$start_time_ampm,$end_days,$end_hours,$end_minutes)
{
	global $bluebdr, $action,$SID;

//----------------------------------------------------------------------------------------
// setup the variables
//----------------------------------------------------------------------------------------

	$ti  = addslashes(trim($title));
	$de  = addslashes(trim($description));
	$ca  = addslashes(trim($cat));
	$da  = addslashes(trim($day));
	$mo  = addslashes(trim($month));
	$ye  = addslashes(trim($year));
	$co  = addslashes(trim($contact));
	$el  = addslashes(trim($email));
	$ur  = addslashes(trim($url));
	$sh  = addslashes(trim($start_time_hour));
	$sm  = addslashes(trim($start_time_minute));
	$sa  = addslashes(trim($start_time_ampm));
	$ed  = addslashes(trim($end_days));
	$eh  = addslashes(trim($end_hours));
	$em  = addslashes(trim($end_minutes));

	$start_time_hour = extcal_12to24hour($sh, $sa);
	$start_date = date("Y-m-d H:i:s", mktime($start_time_hour, $sm, 0, $mo, $da, $ye));

	if($ed > 0 && !$eh && !$em) {
		$ed--; // to make sure not to jump to the next day, we push the time to 23:59:59
		$total_hours = 23;
		$total_minutes = 59;
		$total_seconds = 59;
	} else {
		$total_hours = $start_time_hour + $eh;
		$total_minutes = $sm + $em;
		$total_seconds = 0;
	}
	$end_date = date("Y-m-d H:i:s", mktime( $total_hours, $total_minutes, $total_seconds, $mo, $da + $ed, $ye));

//----------------------------------------------------------------------------------------
// update database
//----------------------------------------------------------------------------------------

	$db->Update("UPDATE extcal_events SET title='$ti',description='$de',cat='$ca',day='$da',month='$mo',year='$ye',start_date='$start_date',end_date='$end_date',contact='$co',email='$el',url='$ur',approved=1 WHERE id=$id");

	echo "<p>You have now updated that event.</p>\n";
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the event listing</a></p>\n";
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $ti some more</a></p>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// MAIN PROGRAM
//////////////////////////////////////////////////////////////////////////////////////////

if (!$USER['flags'][$f_cal_event_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"14px\">Calendar Event Administration</p>\n";

//----------------------------------------------------------------------------------------
// main program switch
//----------------------------------------------------------------------------------------

switch($do) {
case "months":
	show_months_main_menu($db,$theyear,$themonth);
	break;
case "search":
	show_search_main_menu($db,$search,$theyear,$themonth);
	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$title,$description,$cat,$day,$month,$year,$contact,$email,$url,$start_time_hour,$start_time_minute,$start_time_ampm,$end_days,$end_hours,$end_minutes);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$title,$description,$cat,$day,$month,$year,$contact,$email,$url,$start_time_hour,$start_time_minute,$start_time_ampm,$end_days,$end_hours,$end_minutes);
	break;
default:
	show_recent_main_menu($db);
	break;
}

?>
