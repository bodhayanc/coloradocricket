<?php

//------------------------------------------------------------------------------
// Clubs v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_clubs_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM clubs")) {
    // 1-Dec-2009 - Showing All clubs
    $db->QueryRow("SELECT * FROM clubs WHERE LeagueID = 1 ORDER BY ClubActive DESC, ClubName ASC");
    //$db->QueryRow("SELECT * FROM clubs WHERE ClubActive=1 AND LeagueID = 1 ORDER BY ClubName");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Clubs</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active League Clubs</b><br><br>\n";

    // Clubs Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['ClubID']));
        $na = htmlentities(stripslashes($db->data['ClubName']));
        $cs = htmlentities(stripslashes($db->data['ClubActive']));
        $clublogo =  htmlentities(stripslashes($db->data['clublogo']));
       
	if($cs == "1"){
        // output article

	  	if( ($i % 3) == 0 && $i != 0) {
	        echo '</tr><tr  class="trrow2" align="center">';	
        }
		if( ($i % 3) == 0 && $i == 0) {
        	echo '<tr class="trrow2">';	
        }

         if($clublogo == ""){
        	$img_url = "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"center\" border=0>";
        }else {
        	$img_url = "<img border=0 src=\"http://coloradocricket.org/uploadphotos/teams/$clublogo\" width=\"100\">";
        }
        echo "    <td width=\"33%\" align=\"center\"><a href=\"$PHP_SELF?clubs=$id&ccl_mode=1\">$img_url<br>$na</a>&nbsp;\n";
        echo "    </td>\n";
    }
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";
        
         echo "<b class=\"16px\">Inactive League Clubs</b><br><br>\n";

    // Clubs Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	$j = 0;
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['ClubID']));
        $na = htmlentities(stripslashes($db->data['ClubName']));
        $cs = htmlentities(stripslashes($db->data['ClubActive']));
        $clublogo =  htmlentities(stripslashes($db->data['clublogo']));
        if($cs == "0"){
        // output article
		
        if( ($j % 3) == 0 && $j != 0) {
	        echo '</tr><tr  class="trrow2" align="center">';	
        }
		if( ($j % 3) == 0 && $j == 0) {
        	echo '<tr class="trrow2">';	
        }
		$j = $j + 1;
        if($clublogo == ""){
        	$img_url = "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"center\" border=0>";
        }else {
        	$img_url = "<img border=0 src=\"http://coloradocricket.org/uploadphotos/teams/$clublogo\" width=\"100\">";
        }
        echo "    <td width=\"33%\" align=center><a href=\"$PHP_SELF?clubs=$id&ccl_mode=1\">$img_url<br>$na</a>&nbsp;\n";
        echo "    </td>\n";
        }
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
        echo "There are no clubs in the database\n";
    }
}


function show_full_clubs($db,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    //$db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, cl.ClubColour, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, pl.PlayerID, pl.PlayerFName, pl.PlayerLName FROM (clubs cl INNER JOIN players pl ON cl.ClubID = pl.PlayerClub) INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE cl.ClubID = $pr");
    $db->QueryRow("SELECT cl.clublogo, cl.ClubID, cl.ClubName, cl.ClubURL, cl.ClubColour, gr.GroundName, gr.GroundID FROM clubs cl INNER JOIN grounds gr ON cl.GroundID = gr.GroundID WHERE cl.ClubID = $pr");
    $db->BagAndTag();

    $cid = $db->data['ClubID'];
    $cna = $db->data['ClubName'];
    $cur = $db->data['ClubURL'];
    $cco = $db->data['ClubColour'];

    $gri = $db->data['GroundID'];
    $grn = $db->data['GroundName'];
    $clublogo = $db->data['clublogo'];


    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/clubs.php\">Clubs</a> &raquo; <font class=\"10px\">Club Page</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$cna</b><br><br>\n";

    // Clubs Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$cco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$cco\" class=\"whitemain\" height=\"23\">&nbsp;$cna</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">\n";
	echo "<tr class=\"trrow1\">\n";
	echo "    <td width=\"75%\" valign=top>";
    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"4\">\n";

    // output story

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">Website: </td>\n";
    if ($cur != "") {
    echo "    <td width=\"70%\"><a href=\"$cur\" target=\"_new\">$cur</a></td>\n";
    } else {
    echo "    <td width=\"70%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    // output home ground

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Home ground: </td>\n";
    if ($gri != "") {
    echo "    <td width=\"70%\"><a href=\"/grounds.php?grounds=$gri&ccl_mode=1\">$grn</a></td>\n";
    } else {
    echo "    <td width=\"70%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    // president of the club

    if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsPresident = 1")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsPresident = 1");
    $db->DeBagAndTag();

    $fn = $db->data['PlayerFName'];
    $ln = $db->data['PlayerLName'];
    $em = $db->data['PlayerEmail'];
    $pi = $db->data['PlayerID'];

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">President: </td>\n";
    echo "    <td width=\"70%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$fn $ln</a></td>\n";
    echo "  </tr>\n";

    } else {

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">President: </td>\n";
    echo "    <td width=\"70%\">n/a</td>\n";
    echo "  </tr>\n";

    }

    // vice president of the club

    if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsVicePresident = 1")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsVicePresident = 1");
    $db->DeBagAndTag();

    $fn = $db->data['PlayerFName'];
    $ln = $db->data['PlayerLName'];
    $em = $db->data['PlayerEmail'];
    $pi = $db->data['PlayerID'];

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Vice President: </td>\n";
    echo "    <td width=\"70%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$fn $ln</a></td>\n";
    echo "  </tr>\n";

    } else {

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Vice President: </td>\n";
    echo "    <td width=\"70%\">n/a</td>\n";
    echo "  </tr>\n";

    }

    // secretary of the club

    if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsSecretary = 1")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsSecretary = 1");
    $db->DeBagAndTag();

    $fn = $db->data['PlayerFName'];
    $ln = $db->data['PlayerLName'];
    $em = $db->data['PlayerEmail'];
    $pi = $db->data['PlayerID'];

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">Secretary: </td>\n";
    echo "    <td width=\"70%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$fn $ln</a></td>\n";
    echo "  </tr>\n";

    } else {

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">Secretary: </td>\n";
    echo "    <td width=\"70%\">n/a</td>\n";
    echo "  </tr>\n";

    }

    // treasurer of the club

    if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsTreasurer = 1")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsTreasurer = 1");
    $db->DeBagAndTag();

    $fn = $db->data['PlayerFName'];
    $ln = $db->data['PlayerLName'];
    $em = $db->data['PlayerEmail'];
    $pi = $db->data['PlayerID'];

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Treasurer: </td>\n";
    echo "    <td width=\"70%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$fn $ln</a></td>\n";
    echo "  </tr>\n";

    } else {

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Treasurer: </td>\n";
    echo "    <td width=\"70%\">n/a</td>\n";
    echo "  </tr>\n";

    }

    // captain of the club

    if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsCaptain = 1")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsCaptain = 1");
    $db->DeBagAndTag();

    $fn = $db->data['PlayerFName'];
    $ln = $db->data['PlayerLName'];
    $em = $db->data['PlayerEmail'];
    $pi = $db->data['PlayerID'];

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">Captain: </td>\n";
    echo "    <td width=\"70%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$fn $ln</a></td>\n";
    echo "  </tr>\n";

    } else {

    echo "<tr class=\"trrow1\">\n";
    echo "    <td width=\"30%\">Captain: </td>\n";
    echo "    <td width=\"70%\">n/a</td>\n";
    echo "  </tr>\n";

    }

    // vice captain of the club

    if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsViceCaptain = 1")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, pl.PlayerID, pl.IsPresident, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub FROM players pl INNER JOIN clubs cl ON pl.PlayerClub = cl.ClubID WHERE pl.PlayerClub = $pr AND pl.IsViceCaptain = 1");
    $db->DeBagAndTag();

    $fn = $db->data['PlayerFName'];
    $ln = $db->data['PlayerLName'];
    $em = $db->data['PlayerEmail'];
    $pi = $db->data['PlayerID'];

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Vice Captain: </td>\n";
    echo "    <td width=\"70%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$fn $ln</a></td>\n";
    echo "  </tr>\n";

    } else {

    echo "<tr class=\"trrow2\">\n";
    echo "    <td width=\"30%\">Vice Captain: </td>\n";
    echo "    <td width=\"70%\">n/a</td>\n";
    echo "  </tr>\n";

    }

    echo "</table>\n";
	echo "  </td>\n";
	echo "<td width=\"25%\" valign=middle>";
	
	if ($clublogo != "" ) {
		list($width, $height, $type, $attr) = getimagesize("http://coloradocricket.org/uploadphotos/teams/$clublogo");
		if($width >= 150) {
	    	$width = "150";
	    }
	   	echo "<a href=\"/uploadphotos/teams/$clublogo\" onClick=\"return enlarge('/uploadphotos/teams/$clublogo',event)\"><img src=\"/uploadphotos/teams/$clublogo\" width=$width align=\"right\" border=\"1\"></a>\n";
		
    } else {
    echo "";
    }
	
	echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

	if ($db->Exists("SELECT cl.ClubID, cl.ClubName, te.TeamID, te.TeamName, te.TeamAbbrev, te.picture FROM clubs cl INNER JOIN teams te ON cl.ClubID = te.ClubID WHERE cl.ClubID = $pr")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, te.TeamID, te.TeamName, te.TeamAbbrev, te.picture FROM clubs cl INNER JOIN teams te ON cl.ClubID = te.ClubID WHERE cl.ClubID = $pr");
    //$db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, cl.ClubColour, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, pl.PlayerID, pl.PlayerFName, pl.PlayerLName FROM (clubs cl INNER JOIN players pl ON cl.ClubID = pl.PlayerClub) INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE cl.ClubID = $pr");
    $db->BagAndTag();


    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$cco\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"#$cco\" class=\"whitemain\" height=\"23\">&nbsp;TEAMS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

    $cid = $db->data['ClubID'];
    $cna = $db->data['ClubName'];
    
    $tid = $db->data['TeamID'];
    $tna = $db->data['TeamName'];
    $tab = $db->data['TeamAbbrev'];
    $pic = $db->data['picture'];

    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }

    echo "    <td width=\"100%\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$tna</a> <i class=\"9px\">($tab)</i>&nbsp;";
    if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_picture.gif\">";
    echo "    </td>\n";
    echo "  </tr>\n";

    }

    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
	}
    // Clubs Box

	if ($db->Exists("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, cl.ClubColour, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, pl.PlayerID, pl.PlayerFName, pl.PlayerLName FROM (clubs cl INNER JOIN players pl ON cl.ClubID = pl.PlayerClub) INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE cl.ClubID = $pr")) {
    $db->QueryRow("SELECT cl.ClubID, cl.ClubName, cl.ClubURL, cl.ClubColour, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, pl.PlayerID, pl.PlayerFName, pl.PlayerLName FROM (clubs cl INNER JOIN players pl ON cl.ClubID = pl.PlayerClub) INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE cl.ClubID = $pr");
    $db->BagAndTag();

    $cid = $db->data['ClubID'];
    $cna = $db->data['ClubName'];
    $cur = $db->data['ClubURL'];
    $cco = $db->data['ClubColour'];

    $tid = $db->data['TeamID'];
    $tna = $db->data['TeamName'];
    $tab = $db->data['TeamAbbrev'];
    $tco = $db->data['TeamColour'];

    $pid = $db->data['PlayerID'];
    $pfn = $db->data['PlayerFName'];
    $pln = $db->data['PlayerLName'];

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$cco\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"#$cco\" class=\"whitemain\" height=\"23\">&nbsp;NEWS FEATURING $cna</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#$cco\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
    echo "  <table class=\"borderblack\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    // process features
        $db->Query("SELECT * FROM news WHERE IsFeature != 1 AND article LIKE '%$tab%' OR title LIKE '%$tab%' ORDER BY id DESC LIMIT 15");

        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $t = $db->data['title'];
            $au = $db->data['author'];
            $id = $db->data['id'];
            $pr = $db->data['id'];
            $date = sqldate_to_string($db->data['added']);

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"news.php?news=$pr&ccl_mode=1\">$t</a>\n";
        if($db->data['picture'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"25%\" align=\"right\" class=\"9px\">$date</td>\n";
        echo "  </tr>\n";

        }


        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
	}

    // output link back
    //$sitevar = "/clubs.php?clubs=$pr&ccl_mode=1";
    echo "<p>&laquo; <a href=\"/clubs.php\">back to clubs listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

if(isset($_GET['ccl_mode'])) {
	switch($_GET['ccl_mode']) {
	case 0:
		show_clubs_listing($db);
		break;
	case 1:
		show_full_clubs($db,$_GET['clubs']);
		break;
	default:
		show_clubs_listing($db);
		break;
	}
} else {
	show_clubs_listing($db);
}


?>
