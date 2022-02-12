<?php

//------------------------------------------------------------------------------
// Site Control History Administration v2.0
//
// (c) Bodhayan Chakraborty - bodhayanc@gmail.com
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_main_menu($db)
{
	global $PHP_SELF,$content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// Upload USA Cricket dump file

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Upload USA Cricket Registration Data to CCL Website</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"do\" value=\"upload\">\n";
	echo "<br><p>Select the USA Cricket CSV Dump file to be uploaded &nbsp;<input type=\"file\" id=\"usafile\" name=\"filename\" accept=\".csv\"><input type=\"submit\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

}



function upload_file($db)
{
         global $PHP_SELF,$content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;
	if (isset($_FILES['usafile']) && $_FILES['usafile']['name'] != "") {
	  	$uploaddir = "../uploadphotos/players/";
  		$basename = basename($_FILES['usafile']['name']);
	  	$uploadfile = $uploaddir . $basename;

		if (move_uploaded_file($_FILES['usafile']['tmp_name'], $uploadfile)) {
    			$setpic = ",picture='$basename'";
			$picture=$basename;
	  	} else {
    			echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
  		}
	}
}



// main program

if (!$USER['flags'][$f_player_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>USA Cricket Administration</b></p>\n";

if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
}
else {
	$do = '';
}

if(isset($_GET['doit'])) {
	$doit = $_GET['doit'];
} else if(isset($_POST['doit'])) {
	$doit = $_POST['doit'];
}

switch($do) {
case "upload":
	upload_file($db);
	break;
default:
	show_main_menu($db);
	break;
}

?>
