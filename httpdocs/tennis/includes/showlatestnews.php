<?php

//------------------------------------------------------------------------------
// Latest News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_latest($db,$s=0,$minlimit=3,$maxlimit=5,$len=300)
{
	global $PHP_SELF, $greenbdr;

	if (!$db->Exists("SELECT * FROM news")) {
		echo "<p>There is no news in the database at this time.</p>\n";
		return;
	}

	// output article
	echo "<table width=\"100%\" cellpadding=\"6\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	// More Recent News Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>MORE RECENT NEWS</b></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table class=\"bordergrey\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


	// process features
	if ($db->Exists("SELECT * FROM news")) {
		$db->Query("SELECT * FROM news WHERE IsFeature != 1 AND newstype = 3 ORDER BY id DESC LIMIT $minlimit,$maxlimit");

		// output featured articles
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->DeBagAndTag();

			$t = $db->data['title'];
			$au = $db->data['author'];
			$id = $db->data['id'];
			$pr = $db->data['id'];
			$date = sqldate_to_string($db->data['added']);

		//if($i % 2) {
		//  echo "<tr class=\"trrow1\">\n";
		//} else {
		//  echo "<tr class=\"trrow2\">\n";
		//}

		echo "  <tr class=\"trrow1\">\n";
		echo "    <td width=\"75%\"><a href=\"news.php?news=$pr&ccl_mode=1\">$t</a>\n";
		if($db->data['picture'] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td width=\"25%\" class=\"9px\" align=\"right\">$date</td>\n";
		echo "  </tr>\n";

		}
	} else {
		++$cnt;
	}

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
$db->SelectDB($dbcfg['db']);

show_latest($db,$s,3,5);

?>