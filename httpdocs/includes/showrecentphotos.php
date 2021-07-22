<?php

//------------------------------------------------------------------------------
// Photo Gallery (Recent Photos) v 2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
//------------------------------------------------------------------------------

// how many pics accross in the thumbnails
$across = 1;

// limit the number of returned photos to 3, increase if needed

function show_recent_photos($db,$gallery,$id,$limit=3)
{
	global $PHP_SELF,$across;


	$db->Query("SELECT * FROM demogallery ORDER BY id");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$gallery = $db->data['title'];
		$title = $db->data['title'];
	}

	if (!$db->Exists("SELECT * FROM demophotos")) {
		echo "<p>No photos in the database.</p>\n";
		return;
	} else {


		$cnt = 0;
		$intr = 0;

		echo "<table width=\"100\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";

		// query the database

		$db->Query("SELECT * FROM demophotos ORDER BY id DESC LIMIT $limit");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);

			// setup the variables

			$p = htmlentities(stripslashes($db->data['picture1']));
			$t = htmlentities(stripslashes($db->data['title']));
			$pr = htmlentities(stripslashes($db->data['id']));
			$da = htmlentities(stripslashes($db->data['date']));

			if ($cnt==$across) {
				echo "</tr>\n";
				$intr = 0;
				$cnt = 0;
			}
			if (!$intr) {
				echo "<tr>\n";
				$intr = 1;
			}

			echo "<td valign=\"middle\"><div align=\"center\">\n";
			echo "<p><a href=\"photos.php?id=$pr&mode=2\"><img border=\"2\" src=\"uploadphotos/" . $db->data['picture1'] . "\" style=\"border: 1 solid #3c3c3c\"></a>";
			echo "<br>$t<br><font size=\"-2\">$da</font></div></td>\n";
			++$cnt;
		}
		if ($intr) echo "</tr>\n";
		echo "</table>\n";
	}
}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_recent_photos($db,$gallery,$id,3);


?>
