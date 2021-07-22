<?php

//------------------------------------------------------------------------------
// Photo Gallery v 2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
//------------------------------------------------------------------------------

// decide how many pics across in the thumbnails
$across = 3;

function show_photos_listing($db,$gallery,$id)
{
	global $content,$action,$SID,$USER;

	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"1\">\n";
	echo "  <tr class=\"trtop\">\n";
	echo "    <td><b>Photo Gallery</a></b></td>\n";
	echo "    <td><b>Count</a></b></td>\n";
	echo "  </tr>\n";

	if (!$db->Exists("SELECT * FROM demogallery")) {
		echo "<p>There are currently no galleries in the database.</p>\n";
		return;
	} else {
		$db->Query("SELECT * FROM demogallery ORDER BY id");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();
			$id = $db->data['id'];
			$gallerycount = $db->QueryItem("SELECT COUNT(*) from demogallery");
		}

		// get the total count

		$db->Query("SELECT * FROM demophotos ORDER BY id");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();
			$gc = $db->data[gallery];
			$total = $db->QueryItem("SELECT COUNT(*) from demophotos WHERE gallery=27");
			$test = $db->Query("select gallery,count(*) from demophotos group by gallery");
			$totalcount = $db->QueryItem("SELECT COUNT(*) from demophotos");
		}

		// get the per gallery count

		$counts = array();
		$outof = $db->QueryItem("SELECT COUNT(*) FROM demophotos");
		$db->Query("SELECT gallery,COUNT(*) as total FROM demophotos GROUP BY gallery");
			for ($i=0; $i<$db->rows; $i++)
			{
				$db->GetRow($i);
				$counts[$db->data['gallery']] = $db->data['total'];
			}

		$db->Query("SELECT * FROM demogallery");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup the variables

			$t = htmlentities(stripslashes($db->data['title']));
			$gallery = htmlentities(stripslashes($db->data['title']));
			$pr = htmlentities(stripslashes($db->data['id']));
			$id = $db->data['id'];
			$yo = htmlentities(stripslashes($db->data['id']));

			echo "  <tr class=\"trbottom\">\n";
			echo "    <td><a href=\"$PHP_SELF?gallery=$gallery&id=$pr&mode=1\">$t</a></td>\n";
			echo "    <td>{$counts[$db->data['id']]} photo",($counts[$db->data['id']]==1?"":"s"),"</td>\n";
			echo "  </tr>\n";

				}
			}

			echo "</table>\n";
			echo "<p><a href=\"index.php\">&lt;&lt; back to homepage</a></p>\n";
}



function show_thumbs($db,$gallery,$id)
{
	global $PHP_SELF,$across;

	if (!$db->Exists("SELECT * FROM demophotos")) {
		echo "<p>No photos in the database.</p>\n";
		return;
	} else {
		$cnt = 0;
		$intr = 0;

        echo "<p class=\"header\">The $gallery Photo Gallery</p>\n";
        echo "<p>Please click a thumbnail to see the larger photo.</p>\n";
		echo "<div align=\"center\">\n";
		echo "<table width=\"500\" cellpadding=\"4\" cellspacing=\"4\" border=\"0\">\n";

		// query the database

		$db->Query("SELECT * FROM demophotos WHERE gallery=$id ORDER BY setnum");
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

			echo "<td valign=\"top\"><div align=\"center\"><a href=\"$PHP_SELF?gallery=$gallery&id=$pr&mode=2\"><img border=\"2\" src=\"uploadphotos/" . $db->data['picture1'] . "\" style=\"border: 1 solid #3C3C3C\"></a>";
			echo "<br>$t<br><font size=\"1\">$da</font></div></td>\n";
			++$cnt;
		}

		if ($intr) echo "</tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "<p><a href=\"photos.php\">&lt;&lt; back to photo galleries</a></p>\n";
	}
}

function show_photos($db,$gallery,$id)
{
	global $PHP_SELF;

	if (!$db->Exists("SELECT * FROM demophotos WHERE id=$id ORDER BY setnum")) {
		echo "<p>No photos in the database for $gallery.</p>\n";
		echo "<a href=\"photos.php\">&lt;&lt; back to photos listing</a>\n";
		return;
	} else {

		echo "<div align=\"left\">\n";
		echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";

		$db->Query("SELECT * FROM demophotos WHERE id=$id");

		echo "<div align=\"left\">\n";
        echo "<p class=\"header\">The $gallery Photo Gallery</p>\n";
		echo "<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n";

		$db->GetRow($i);

		// setup the variables

		$p = htmlentities(stripslashes($db->data['picture']));
		$t = htmlentities(stripslashes($db->data['title']));
		$de = htmlentities(stripslashes($db->data[description]));
		$l = htmlentities(stripslashes($db->data[location]));
		$da = htmlentities(stripslashes($db->data['date']));
		$g = $db->data[gallery];

		// output photos

		echo "  <tr>\n";
		echo "    <td valign=\"top\"><p align=\"center\"><img border=\"2\" src=\"uploadphotos/" . $db->data['picture'] . "\" style=\"border: 2 solid #3c3c3c\"></a></p>\n";
		echo "    <div align=\"center\"><span class=\"newsheader\">$t</span></div></td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "    <td valign=\"top\">\n";
		echo "    $de<br><br>\n";
		echo "    <b>Location: </b>$l<br>\n";
		echo "    <b>Date: </b>$da<br>\n";
		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		echo "</div>\n";
		echo "<p><a href=\"javascript:history.go(-1)\" onMouseOver=\"self.status=document.referrer;return true\">&lt;&lt; back to $gallery</a></p>\n";
	}


}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

//show_photos($db);


switch($mode) {
case 0:
	show_photos_listing($db,$gallery,$id);
	break;
case 1:
	show_thumbs($db,$gallery,$id);
	break;
case 2:
	show_photos($db,$gallery,$id);
	break;
default:
	show_photos_listing($db,$gallery,$id);
	break;
}

?>
