<?php

//------------------------------------------------------------------------------
// Colorado Cricket Sponsors v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------

function show_sponsors_listing($db)
{
    global $PHP_SELF, $bluebdr;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Sponsors</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
        
    echo "<b class=\"16px\">League Sponsors</b><br><br>\n";
        
    if ($db->Exists("SELECT * FROM sponsors")) {
    $db->QueryRow("SELECT * FROM sponsors where isactive = 1 ORDER BY id DESC, isActive DESC");
    $db->BagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $t = htmlentities(stripslashes($db->data['title']));
        $u = htmlentities(stripslashes($db->data['url']));
        $p = htmlentities(stripslashes($db->data[promised]));
        $a = $db->data['article'];
        $pr = htmlentities(stripslashes($db->data['id']));
        $id = $db->data['id'];

	if ($p == 1)
	{
	  // We wanna ignore the sponsor, it is a way to disable.
	  continue;
	}
	//////////////////////////////////////////////////////////////////////////////////////////
	// Sponsors Box
	//////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$t</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "   <tr class=\"trrow1\">\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<br>\n";

    if($db->data['picture'] != "") echo "<p align=\"center\"><a href=\"$u\" target=\"_new\"><img src=\"uploadphotos/sponsors/" . $db->data['picture'] . "\" style=\"border: 1 solid #393939\"></a></p>\n";
    
    
    echo "<p>$a</p>\n";
    echo "<p>Website: <a href=\"$u\" target=\"_new\">$u</a></p>\n"; 

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    }
    
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    } else {
    
    echo "There are no sponsors in the database\n";
    
    }
}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_sponsors_listing($db);


?>
