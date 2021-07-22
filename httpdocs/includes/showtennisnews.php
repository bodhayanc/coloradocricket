<?php

//------------------------------------------------------------------------------
// Latest News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_tennis($db,$limit=5,$len=300)
{
	global $PHP_SELF, $greenbdr, $bluebdr, $yellowbdr, $tennisbdr;

	if (!$db->Exists("SELECT * FROM news")) {
		echo "<p>There is no news in the database at this time.</p>\n";
		return;
	}

	// output article
	echo "<table width=\"100%\" cellpadding=\"6\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";

	// More Recent News Box

    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td background=\"images/tenniscricketnews.gif\" bgcolor=\"$tennisbdr\" class=\"whitemain\" height=\"23\">&nbsp;</td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$tennisbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table class=\"bordergrey\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


	// process features
	if ($db->Exists("SELECT * FROM news WHERE IsFeature != 1 AND newstype = 3")) {
		$db->Query("SELECT * FROM news WHERE IsFeature != 1 AND newstype = 3 ORDER BY id DESC LIMIT $limit");

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
		
		echo "<tr class=\"trrow1\">\n";
		echo "    <td width=\"75%\"><a href=\"http://tennis.coloradocricket.org/news.php?news=$pr&ccl_mode=1\">$t</a>\n";
		if($db->data['picture'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">\n";
		echo "    </td>\n";
		echo "    <td width=\"25%\" align=\"right\" class=\"9px\">$date</td>\n";
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

show_tennis($db,5,5);

?>