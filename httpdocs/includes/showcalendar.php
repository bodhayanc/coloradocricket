<?php

//------------------------------------------------------------------------------
// Items v1.0
//
// (c) Kervyn Dimney      - kervyn@yahoo.com
//------------------------------------------------------------------------------



function show_calendar_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM extcal_events WHERE approved = 1 ORDER BY start_date DESC")) {
    // 6-Dec-2009 - Showing Active Calendar Listings
    $db->QueryRow("SELECT * FROM extcal_events WHERE approved = 1 ORDER BY start_date DESC");

    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Calendar Events</font></p>\n";
    echo "  </td>\n";
     echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active Calendar Events</b><br><br>\n";

    // Items Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td width=\"70%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Event</td>\n";
        echo "    <td width=\"30%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;DateTime</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['id']));
        $na = htmlentities(stripslashes($db->data['title']));
        $sd = htmlentities(stripslashes($db->data['start_date']));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"70%\"><a href=\"$PHP_SELF?id=$id&ccl_mode=1\">$na</a>&nbsp;\n";
        echo "    <td width=\"30%\">$sd\n";
        echo "    </td>\n";
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
        echo "There are no calendar events in the database\n";
    }
}
 function show_calendar_items($db,$id){
global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM extcal_events WHERE id = $id and approved = 1 ORDER BY start_date DESC")) {
    // 6-Dec-2009 - Showing Active Calendar Items
    $db->QueryRow("SELECT * FROM extcal_events WHERE id = $id and approved = 1 ORDER BY start_date DESC");

    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/calendar.php\">Calendar Events</a> &raquo;</p>\n";
    echo "  </td>\n";
     echo "</tr>\n";
    echo "</table>\n";

//    echo "<b class=\"16px\">Calendar Events</b><br><br>\n";

    // Calendar Events Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
//        echo "    <td width=\"25%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Event</td>\n";
        echo "    <td width=\"100%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Event Details</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
            $iid = $db->data['id'];
            $itit = $db->data['title'];
            $ipic = $db->data['picture'];
            $idet = $db->data['description'];

            $ina = $db->data[contact];
            $iurl= $db->data['url'];
            $iem = $db->data[email];
            $isd = $db->data['start_date'];
	    $ied = $db->data[end_date];

            $iap = $db->data[approved];

           // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

//        echo "    <td width=\"25%\">&nbsp;\n";
        echo "    <td width=\"100%\">Event: $itit<br><br>Event Details: $idet<br><br><br><b>Contact Details </b><br>Name: $ina<br><br>Email: <a HREF=mailto:$iem>$iem</a><br>URL: <a HREF=$iurl>$iurl</a><br><br><b>Start Date: </b>$isd&nbsp&nbsp&nbsp&nbsp&nbsp<b>End Date: </b>$ied\n";
        echo "    </td>\n";
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
        echo "There are no calendar events in the database\n";
    }
}
// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

if(isset($ccl_mode)) {
	switch($ccl_mode) {
	case 0:
		show_calendar_listing($db);
		break;
	case 1:
		show_calendar_items($db,$id);
		break;
	default:
		show_calendar_listing($db);
		break;
	}
} else {
	show_calendar_listing($db);
}

?>
