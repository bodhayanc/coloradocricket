<?php
//------------------------------------------------------------------------------
// Colorado Cricket Officers v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_cclofficers_listing($db,$id,$offid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
	$db_year = $db;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">CCL Officers</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">League Officers</b><br><br>\n";
    $str_drop = "Season Year: <select id=\"season_year\" name=\"season_year\" onchange=\"javascript:document.season.submit(); \">";
    $db_year->QueryRow("SELECT distinct season_year from cclofficers order by season_year desc");
    $db_year->BagAndTag();

    for ($k=0; $k<$db_year->rows; $k++) {
    	$sel = '';
        $db_year->GetRow($k);
        $year = $db_year->data[season_year];
        if($k == 0){
        $maxyear =  $db_year->data[season_year];
        }
        if($year == $_GET['season_year']) {
        	$sel = "selected";
        }
        $str_drop .= "<option value='$year' $sel>$year</option>";
    }
    $str_drop .= "<\select>";
    //////////////////////////////////////////////////////////////////////////////////////////
    // CCL Officers Box
    //////////////////////////////////////////////////////////////////////////////////////////
	if($_GET['season_year'] != '') {
    	$maxyear = $_GET['season_year'];
    }
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td width=70% bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$maxyear CCL OFFICERS </td>\n";
        echo "    <td width=30% align=right bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"> <form name='season' id='season'>$str_drop</form></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
   /* echo "<tr class=\"colhead\">\n";
    echo "    <td><b>POSITION</b></td>\n";
    echo "    <td><b>NAME</b></td>\n";
    echo "    <td><b>TEAM</b></td>\n";
    echo "  </tr>\n";*/
    
    if ($db->Exists("SELECT cl.cclofficerID, cl.cclofficerTitle, cl.cclofficerViews, cl.cclofficerPlayerID, cl.cclofficerDetail, te.TeamAbbrev, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail FROM players pl INNER JOIN cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID INNER JOIN teams te ON pl.PlayerTeam=te.TeamID where  season_year = '$maxyear' order by cl.start_date, cl.end_date")) {
    $db->QueryRow("SELECT cl.cclofficerID, cl.cclofficerTitle, cl.cclofficerViews, cl.cclofficerPlayerID, cl.cclofficerDetail, te.TeamAbbrev, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.AIM, pl.YAHOO, pl.MSN, pl.ICQ FROM players pl INNER JOIN cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID INNER JOIN teams te ON pl.PlayerTeam=te.TeamID where season_year = '$maxyear'  order by cl.start_date DESC, cl.cclofficerID ASC, cl.end_date");
    $db->BagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        $id = $db->data[cclofficerID];
        $ti = $db->data[cclofficerTitle];
        $pid = $db->data[cclofficerPlayerID];
        $fna = $db->data['PlayerFName'];
        $lna = $db->data['PlayerLName'];
        $pem = $db->data['PlayerEmail'];
        $tab = $db->data['TeamAbbrev'];
        $pc = $db->data['picture'];
        $detail = $db->data[cclofficerDetail];
        $cclofficerViews = $db->data[cclofficerViews];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }
        
		echo "    <td width=85 height=80 align=center valign=top><center><a  href=\"$PHP_SELF?offid=$id&ccl_mode=1\"><img alt='$lna, $fna' align=center width=50 height=67 border=1 src=\"http://www.coloradocricket.org/uploadphotos/players/$pc\"><br>$lna,$fna<br><b>$ti</b></a></center></td>\n";
        echo "    <td valign=top><b>Team: $tab</b> | <a href='players.php?players=$pid&ccl_mode=1'><b>$fna's Profile</b></a> <br><br>". substr($detail,0,1000) ." <br><br><b>$fna's Views:</b> $cclofficerViews<br><br></td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    } else {
        echo "There are no CCL Officers in the database\n";
    }
}


function show_cclofficers_detail($db,$id,$offid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT cl.cclofficerID, cl.cclofficerTitle, cl.cclofficerPlayerID, cl.cclofficerDetail, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail FROM players pl INNER JOIN cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID WHERE cl.cclofficerID=$offid")) {
    $db->QueryRow("SELECT cl.cclofficerID, cl.cclofficerTitle, cl.cclofficerPlayerID, cl.cclofficerDetail, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.AIM, pl.YAHOO, pl.MSN, pl.ICQ FROM players pl INNER JOIN cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID WHERE cl.cclofficerID=$offid");
    $db->BagAndTag();


    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"$PHP_SELF\">CCL Officers</a> &raquo; <font class=\"10px\">Positions</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // CCL Officers Positions Box
    //////////////////////////////////////////////////////////////////////////////////////////

    for ($d=0; $d<$db->rows; $d++) {
        $db->GetRow($d);
        $id = $db->data[cclofficerID];
        $ti = $db->data[cclofficerTitle];
        $de = $db->data[cclofficerDetail];
        $vi = $db->data[cclofficerViews];
        $fna = $db->data['PlayerFName'];
        $lna = $db->data['PlayerLName'];
        $pem = $db->data['PlayerEmail'];
        $pic = $db->data['picture'];

    echo "<b class=\"16px\">$ti</b><br><br>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;RESPONSIBILITIES</td>\n";


        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr class=\"trrow1\">\n";
    echo "    <td width=\"100%\">";
	echo "<center><img alt='$lna, $fna' align=center width=100  border=1 src=\"/uploadphotos/players/$pic\"><br>$lna,$fna<br><b>$ti</b>";
    echo "</center><br><br>    $de</td>\n";
    echo "  </tr>\n";

    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    echo "<p>&laquo; <a href=\"$PHP_SELF\">return to ccl officer list</a></p>\n";

    }

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    } else {
        echo "There are no CCL Officers in the database\n";
    }
}



function show_cclofficers_views($db,$id,$offid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT cl.*, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail FROM players pl INNER JOIN cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID WHERE cl.cclofficerID=$offid")) {
    $db->QueryRow("SELECT cl.*, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.AIM, pl.YAHOO, pl.MSN, pl.ICQ FROM players pl INNER JOIN cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID WHERE cl.cclofficerID=$offid");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"$PHP_SELF\">CCL Officers</a> &raquo; <font class=\"10px\">Views</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";


    //////////////////////////////////////////////////////////////////////////////////////////
    // CCL Officers Positions Box
    //////////////////////////////////////////////////////////////////////////////////////////

    for ($d=0; $d<$db->rows; $d++) {
        $db->GetRow($d);
        $db->BagAndTag();
        $id = $db->data[cclofficerID];
        $ti = $db->data[cclofficerTitle];
        $vie = $db->data[cclofficerViews];
        $pid = $db->data['PlayerID'];
        $fna = $db->data['PlayerFName'];
        $lna = $db->data['PlayerLName'];
        $pem = $db->data['PlayerEmail'];
        $pic = $db->data['picture'];


    echo "<b class=\"16px\">$ti - $fna $lna</b><br><br>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;MESSAGE</td>\n";


        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr class=\"trrow1\">\n";
    echo "    <td width=\"100%\">";


    echo "<table width=\"100\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"center\" valign=\"middle\">\n";
    echo "  <div align=\"center\" class=\"photo\"><img src=\"/uploadphotos/players/$pic\" style=\"border: 1 solid #000000\" width=\"100\"></div>\n";
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";

    if($vie!="") {  
    echo "$vie\n";
    } else {
    echo "<p>No views listed at this time sorry.</p>\n";
    }
    
    echo "  <p><a href=\"players.php?players=$pid&ccl_mode=1\"><img src=\"/images/icons/icon_members.gif\" border=\"0\"> view $fna's profile</a></p>\n";
    
    echo "    </td>\n";
    echo "  </tr>\n";

    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "<p>&laquo; <a href=\"$PHP_SELF\">return to ccl officer list</a></p>\n";


    }

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    } else {
        echo "There are no CCL Officers in the database\n";
    }
}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_cclofficers_listing($db,$id,$cclofficers);
    break;
case 1:
    show_cclofficers_detail($db,$id,$offid);
    break;
case 2:
    show_cclofficers_views($db,$id,$offid);
    break;
default:
    show_cclofficers_listing($db,$id,$cclofficers);
    break;
}




?>
