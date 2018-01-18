<?php

//------------------------------------------------------------------------------
// Show Featured Articles v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_cricinfo($db)
{
	global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

	if (!$db->Exists("SELECT * FROM news")) {
		echo "<p>There is no news in the database at this time.</p>\n";
		return;
	}

	// output article
	echo "<table width=\"100%\" cellpadding=\"6\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	// Clubs Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;CRICINFO LIVE SCORES</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
    	echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "<tr class=\"trrow1\">\n";
	echo "    <td width=\"100%\">";
		
	echo "<script language=\"JavaScript\" src=\"http://www.globalsyndication.com//rss2js/feed2js.php?src=http%3A%2F%2Fwww.cricinfo.com%2Fhomepage%2Flivescores.xml&chan=n&num=0&desc=0&date=n&targ=y&html=n\" type=\"text/javascript\"></script>\n";
	echo "<noscript>\n";
	echo "<a href=\"http://www.globalsyndication.com//rss2js/feed2js.php?src=http%3A%2F%2Fwww.cricinfo.com%2Fhomepage%2Flivescores.xml&chan=n&num=0&desc=0&date=n&targ=y&html=y\">View RSS feed</a>\n";
	echo "</noscript>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg[db]);

show_cricinfo($db);

?>