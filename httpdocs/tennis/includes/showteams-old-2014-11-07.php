<?php

//------------------------------------------------------------------------------
// Teams v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_teams_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC LIMIT 1");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $season = $db->data['SeasonID'];
    }
    
    $db->Query("SELECT * FROM tennisgroups ORDER BY GroupName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $groups[$db->data[GroupID]] = $db->data[GroupName];
    }

    if ($db->Exists("SELECT * FROM tennisteams where teamactive = 1")) {
    
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Teams</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Tennis League Teams</b><br><br>\n";

    // Teams Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        
    for ($i=1; $i<=count($groups); $i++) {
        
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;" . htmlentities(stripslashes($groups[$i])) . "</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

// 21-June-2010 Replaced $i with 7
    $db->Query("SELECT * FROM tennisteams WHERE TeamGroup=7 ORDER BY TeamActive DESC, TeamName");
//    $db->Query("SELECT * FROM tennisteams WHERE TeamGroup=$i AND TeamActive=1 ORDER BY TeamName");
//$db->Query("SELECT * FROM tennisteams WHERE TeamGroup=$i ORDER BY TeamName");
    $db->BagAndTag();
    if($db->rows != 0) {
	    for ($x=0; $x<$db->rows; $x++) {
	        $db->GetRow($x);
	        $id = htmlentities(stripslashes($db->data[TeamID]));
	        $na = htmlentities(stripslashes($db->data['teamname']));
//	        $di = htmlentities(stripslashes($db->data[TeamDirections]));  commented 29-Jul-2014 12:09am
	        $di = htmlentities(stripslashes($db->data[TeamDesc]));
	        $co = htmlentities(stripslashes($db->data[TeamColour]));
                $st = $db->data[TeamActive];
	        $pi = $db->data['picture'];
	
//              if ($st=1) { $sta = 'Active';}
               if ($st==0) { $sta = 'Inactive';}   // checking using two equal signs 28-Oct-2014 12:11am
               else
               { $sta = 'Active';}



	        // output article
	
	        if($x % 2) {
	          echo "<tr class=\"trrow1\">\n";
	        } else {
	          echo "<tr class=\"trrow2\">\n";
	        }
	
	        echo "    <td align=\"left\" width=\"5\" bgcolor=\"$co\">&nbsp;</td>\n";
	        echo "    <td align=\"left\"><a href=\"$PHP_SELF?teams=$id&ccl_mode=1\">$na ($sta)</a>&nbsp;\n";
	        if($pi != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">";
	        echo "    <td align=\"right\"><a href=\"statistics.php?statistics=&team=$id&ccl_mode=2\">Career Stats</a> | <a href=\"/schedule.php?schedule=$season&team=$id&ccl_mode=2\">Schedule</a> | <a href=\"$PHP_SELF?teams=$id&ccl_mode=1#players\">Players</a>\n";
	        echo "  </tr>\n";
	    }
	  
    }
    else {
    	echo "<tr><td colspan=3>There are no active teams in the group.</td></tr>";
    }
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        
        }
        
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no teams in the database\n";
    }
}


function show_full_teams($db,$s,$id,$pr)
{
    global $PHP_SELF;

    $db->QueryRow("SELECT * FROM tennisteams WHERE TeamID=$pr");
    $db->BagAndTag();

    $id = $db->data[TeamID];
    $na = $db->data['teamname'];
    $ca = $db->data['TeamAbbrev'];
    $ur = $db->data[TeamURL];
    $co = $db->data[TeamColour];
    $td = $db->data[TeamDesc];  // Added 29-Jul-2014 12:21am 

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <p><a href=\"/index.php\">Home</a> &raquo; <a href=\"/teams.php\">Teams</a> &raquo; Team Page</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$na</b><br><br>\n";

    // Team Photo Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;$na Team Photo</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    $db->QueryRow("SELECT picture FROM tennisteams WHERE TeamID=$pr");
    $db->DeBagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        $pic = $db->data['picture'];

    // output story

        if ($db->data['picture'] != "" ) {

        echo "<tr>\n";
        echo "    <td width=\"100%\" align=\"center\"><img src=\"http://www.coloradocricket.org/uploadphotos/teams/$pic\" width=445></td>\n";
         echo "<p>$td</p>\n";
//        echo "    <td width=\"100%\" align=\"right\">$td</td>\n";
//        echo $td;  // 29-July-2014 12:22am
        echo "  </tr>\n";


        } else {

        echo "<tr>\n";
        echo "    <td width=\"100%\">No team photo at this time.</td>\n";
//        echo $td;  // 29-July-2014 12:22am
 echo "<p>$td</p>\n";
        echo "  </tr>\n";

        }

        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";


    // Team Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;$na Active Players</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    if ($db->Exists("SELECT pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail FROM tennisplayers pl INNER JOIN tennisteams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerTeam = $pr ORDER BY pl.PlayerLName")) {
    $db->QueryRow("
    SELECT
      pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.picture, pl.picture1, pl.isactive
    FROM
      tennisplayers pl
    INNER JOIN
      tennisteams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerTeam = $pr
    ORDER BY
      pl.isactive ASC, pl.PlayerLName
    ");
	// 29-Oct-2014 10:29pm - Removed from SQL - AND pl.isactive = 0; and Added ORDER BY p1.isactive ASC
    $db->DeBagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        $fn = $db->data['PlayerFName'];
        $ln = $db->data['PlayerLName'];
        $em = $db->data[PlayerEmail];
        $pi = $db->data['PlayerID'];
        $pc = $db->data['picture'];
        $pa = $db->data[picture1];
		$ps = $db->data[isactive];  // 29-Oct-2014 10:26pm player status

    // output story

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"100%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$ln, $fn</a>&nbsp;";
        if($pc != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">&nbsp;";
        // if($pa != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">";
		if($pa != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">&nbsp;"; // 29-Oct-2014 10:26pm Added &nbsp;
		if($ps == 1) echo "(InActive)"; // 29-Oct-2014 10:26pm player status
        echo "    </td>\n";
        echo "  </tr>\n";

        }

        } else {

        echo "<tr class=\"trrow2\">\n";
        echo "    <td width=\"100%\">No players at this time</td>\n";
        echo "  </tr>\n";

        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // Alpha Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing - All $ca Players</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"teams.php?teams=$id&letter=A&ccl_mode=2\">A</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=B&ccl_mode=2\">B</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=C&ccl_mode=2\">C</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=D&ccl_mode=2\">D</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=E&ccl_mode=2\">E</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=F&ccl_mode=2\">F</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=G&ccl_mode=2\">G</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=H&ccl_mode=2\">H</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=I&ccl_mode=2\">I</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=J&ccl_mode=2\">J</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=K&ccl_mode=2\">K</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=L&ccl_mode=2\">L</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=M&ccl_mode=2\">M</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=N&ccl_mode=2\">N</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=O&ccl_mode=2\">O</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=P&ccl_mode=2\">P</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=Q&ccl_mode=2\">Q</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=R&ccl_mode=2\">R</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=S&ccl_mode=2\">S</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=T&ccl_mode=2\">T</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=U&ccl_mode=2\">U</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=V&ccl_mode=2\">V</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=W&ccl_mode=2\">W</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=X&ccl_mode=2\">X</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=Y&ccl_mode=2\">Y</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=Z&ccl_mode=2\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";
    

    // output link back
    $sitevar = "/teams.php?teams=$pr&ccl_mode=1";
    echo "<p>&laquo; <a href=\"teams.php\">back to teams listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

function show_alpha_listing($db,$s,$id,$pr,$letter)
{
    global $PHP_SELF;

    $db->QueryRow("SELECT * FROM tennisteams WHERE TeamID=$pr");
    $db->BagAndTag();

    $id = $db->data[TeamID];
    $na = $db->data['teamname'];
    $ca = $db->data['TeamAbbrev'];
    $ur = $db->data[TeamURL];
    $co = $db->data[TeamColour];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <p><a href=\"/index.php\">Home</a> &raquo; <a href=\"/teams.php\">Teams</a> &raquo; Team Page</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$na</b><br><br>\n";


    // Team Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;$na Players beginning with \"$letter\"</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    if ($db->Exists("SELECT pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub, pl.isactive FROM tennisplayers pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' AND pl.PlayerTeam = $pr ORDER BY pl.PlayerLName")) {
    $db->QueryRow("
    SELECT
      pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub, pl.picture, pl.picture1, pl.isactive
    FROM
      tennisplayers pl
    INNER JOIN
      tennisteams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerLName LIKE '{$letter}%' AND pl.PlayerTeam = $pr
    ORDER BY
      pl.PlayerLName
    ");
    $db->DeBagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        $fn = $db->data['PlayerFName'];
        $ln = $db->data['PlayerLName'];
        $em = $db->data[PlayerEmail];
        $pi = $db->data['PlayerID'];
        $pc = $db->data['picture'];
        $pa = $db->data[picture1];
        $ia = $db->data[isactive];

    // output story

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"80%\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$ln, $fn</a>&nbsp;";
        if($pc != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">&nbsp;";
        if($pa != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">";
        echo "    </td>\n";
        
        echo "    <td width=\"20%\" align=\"right\">";
        if($ia == 1) echo "not active";
        echo "    </td>\n"; 
        
        echo "  </tr>\n";

        }

        } else {

        echo "<tr class=\"trrow2\">\n";
        echo "    <td width=\"100%\">No players at this time</td>\n";
        echo "  </tr>\n";

        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // Alpha Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing - All $ca Players</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"teams.php?teams=$id&letter=A&ccl_mode=2\">A</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=B&ccl_mode=2\">B</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=C&ccl_mode=2\">C</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=D&ccl_mode=2\">D</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=E&ccl_mode=2\">E</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=F&ccl_mode=2\">F</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=G&ccl_mode=2\">G</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=H&ccl_mode=2\">H</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=I&ccl_mode=2\">I</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=J&ccl_mode=2\">J</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=K&ccl_mode=2\">K</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=L&ccl_mode=2\">L</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=M&ccl_mode=2\">M</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=N&ccl_mode=2\">N</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=O&ccl_mode=2\">O</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=P&ccl_mode=2\">P</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=Q&ccl_mode=2\">Q</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=R&ccl_mode=2\">R</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=S&ccl_mode=2\">S</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=T&ccl_mode=2\">T</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=U&ccl_mode=2\">U</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=V&ccl_mode=2\">V</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=W&ccl_mode=2\">W</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=X&ccl_mode=2\">X</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=Y&ccl_mode=2\">Y</a>\n";
    echo "<a href=\"teams.php?teams=$id&letter=Z&ccl_mode=2\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";
    

    // output link back
    $sitevar = "/teams.php?teams=$pr&ccl_mode=1";
    echo "<p>&laquo; <a href=\"teams.php\">back to teams listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_teams_listing($db,$s,$id,$teams);
    break;
case 1:
    show_full_teams($db,$s,$id,$teams);
    break;
case 2:
    show_alpha_listing($db,$s,$id,$teams,$letter);
    break;  
default:
    show_teams_listing($db,$s,$id,$teams);
    break;
}


?>
