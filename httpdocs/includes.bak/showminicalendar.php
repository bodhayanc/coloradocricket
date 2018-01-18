<?php

//------------------------------------------------------------------------------
// Site Control Calendar v3.0
//
// (c) Andrew Collington - amnuts@amnuts.com
// (c) Michael Doig      - michael@gmail.com
//------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////////////////////////		
//  CALENDAR - EVENT LISTING
//////////////////////////////////////////////////////////////////////////////////////////		

function show_short_calendar($db)
{
global $PHP_SELF;

	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"1\" bordercolor=\"#ECECEC\">\n";

	//-------------------------------------------------
	// query the database
	//-------------------------------------------------
	
	if (!$db->Exists("SELECT  * FROM extcal_events WHERE start_date >= NOW() ORDER BY start_date LIMIT 5")) {

	echo "  <tr class=\"trrow1\">\n";
	echo "    <td colspan=\"2\">No events at this time.</td>\n";
	echo "  </tr>\n";
	
	} else {
	
	$db->QueryRow("SELECT DATE_FORMAT(start_date, '%b %e') as formatted_date, title, id, start_date FROM extcal_events WHERE start_date >= NOW() ORDER BY start_date LIMIT 5");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		
	//-------------------------------------------------
	// variables
	//-------------------------------------------------
			
	  $t = htmlentities(stripslashes($db->data['title']));
	  $id = htmlentities(stripslashes($db->data['id']));
	  $da = sqldate_to_string($db->data[start_date]);
	  $fd = $db->data[formatted_date];

	//-------------------------------------------------
	// output article
	//-------------------------------------------------

	echo '  <tr class="trrow', ($i % 2 ? '2' : '1'), '">';
	echo "    <td class=\"9px\" width=\"30%\">$fd</td>\n";

       // 6-Dec-09
       // echo "    <td class=\"9px\" width=\"70%\"><a href='Javascript: //' onClick=\"MM_openBrWindow('/calendar/cal_popup.php?
      // mode=view&id=$id','eventview','toolbar=no,status=no,resizable=no,scrollbars=yes',550,300,false)\">$t</td>\n";

      echo "  <td align=\"left\"><a href=\"calendar.php?&id=$id&ccl_mode=1\" target=\"_new\">$t</a><span class=\"9px\"></td>\n";

//echo "    <td class=\"9px\" width=\"70%\"><a href='Javascript: //' onClick=\"MM_openBrWindow('/calendar.php?&id=$id&ccl_mode=1' , 'eventview', //'toolbar=no,status=no,resizable=no,scrollbars=yes',550,300,false)\">$t</td>\n";

	echo "  </tr>\n";
	}
	
	}

	echo "<tr class=\"trrow1\">\n";
	echo "  <td align=\"left\" colspan=\"2\"><img src=\"/images/icons/icon_arrows.gif\"><a href=\"/calendar.php\">Go to calendar</a></td>\n";
	echo "</tr>\n";

	echo "</table>\n";

}



//////////////////////////////////////////////////////////////////////////////////////////		
// MAIN PROGRAM
//////////////////////////////////////////////////////////////////////////////////////////		

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_short_calendar($db);

?>
