<?php

//------------------------------------------------------------------------------
// Colorado Cricket Umpires v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_umpires_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    // get the teams for grouping

    $db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamAbbrev");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
        
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Umpires</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Certified Umpires</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Umpires Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;UMPIRES LIST</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=1; $i<=count($teams); $i++) {

//    $db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamID");
$db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamAbbrev");
    $db->GetRow($i-1);
    $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    $teams_id[$i] = $db->data['TeamID'];
    

    echo "<tr class=\"colhead\">\n";
    echo "    <td width=\"100%\"><b>" . htmlentities(stripslashes($teams[$db->data['TeamID']])) . "</b></td>\n";
    echo "  </tr>\n";

// 3-Apr-2014 11:53pm Changed the order by from playerfname to playerlname

    if (!$db->Exists("SELECT te.TeamID, te.TeamAbbrev, te.TeamName, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.isumpire=1 AND te.TeamActive=1 AND pl.PlayerTeam=$teams_id[$i] ORDER BY te.TeamAbbrev, pl.PlayerLName")) {

    //echo "<tr class=\"trrow2\">\n";
    //echo "    <td width=\"100%\"><a href=\"/players.php?players=$id&ccl_mode=1\">$pfn $pln</a></td>\n";
    //echo "  </tr>\n";
    //return;

    } else {
    
    $db->QueryRow("SELECT te.TeamID, te.TeamAbbrev, te.TeamName, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.isumpire=1 AND te.TeamActive=1 AND pl.PlayerTeam=$teams_id[$i] ORDER BY te.TeamAbbrev, pl.PlayerLName");
    $db->BagAndTag();

    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        $id = htmlentities(stripslashes($db->data['PlayerID']));
        $pln = htmlentities(stripslashes($db->data['PlayerLName']));
        $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
        $pte = htmlentities(stripslashes($db->data['TeamAbbrev']));
        $ptn = htmlentities(stripslashes($db->data['TeamName']));

    
    if($r % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }

    echo "    <td width=\"100%\"><a href=\"/players.php?players=$id&ccl_mode=1\">$pln, $pfn</a></td>\n";  // Going with lastname, firstname
//    echo "    <td width=\"100%\"><a href=\"/players.php?players=$id&ccl_mode=1\">$pfn $pln</a></td>\n";   // commented 3-Apr-2014 11:52pm
    echo "  </tr>\n";
    }

    }
    }
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_umpires_listing($db);

?>
