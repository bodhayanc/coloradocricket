<?php

//------------------------------------------------------------------------------
// Players v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------

function show_full_players_stats($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
	
	$db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Player Stats</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln - Statistics</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";
        
        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Player Profile                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;PLAYER PROFILE</td>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"right\">&nbsp;CCL ID: $plid</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"200\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"center\" valign=\"middle\">\n";
    echo "  <div align=\"center\" class=\"photo\">";
    if($pic != "") {
    echo "<img src=\"/uploadphotos/players/$pic\" align=\"center\" border=\"1\"></td>\n";
    } else {
    echo "<img src=\"/uploadphotos/players/HeadNoMan.jpg\" align=\"center\"></td>\n";
    }
    echo "  <td width=\"60%\">";
    echo "  </td>\n";
    echo "  </div>\n";    
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
      

    if($pln != "") echo "  <b>Name: </b>$pfn $pln<br>\n";
    if($bor != "") echo "  <b>Born: </b>$bor<br>\n";
    if($pem != "") echo "  <b>Email: </b>$pem<br>\n";
    if($cna != "") echo "  <b>Club: </b><a href=\"/clubs.php?clubs=$cid&ccl_mode=1\">$cna</a><br>\n";
    if($tna != "") echo "  <b>Team: </b><a href=\"/teams.php?teams=$tid&ccl_mode=1\">$tna</a><br><br>\n";
    if($bat != "") echo "  <b>Batting Style: </b>$bat<br>\n";
    if($bow != "") echo "  <b>Bowling Style: </b>$bow<br>\n";
    if($spr != "") echo "  <p>$spr..</p>\n";
    
    echo "";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";
        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}



function show_breakdown_year($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("SELECT pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName FROM (players pl INNER JOIN clubs cl ON cl.ClubID = pl.PlayerClub) INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerID = $pr");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Player Stats</font></p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Year                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $db->Query("SELECT * FROM seasons ORDER BY SeasonName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BATTING ANALYSIS BY SEASON</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>SEASON</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>100</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>50</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>St</td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($seasons)+1; $i++) {

    if ($db->Exists("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.season=$i GROUP BY p.PlayerLName, p.PlayerFName")) {
    $db->QueryRow("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.season=$i GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scinn = $db->data[Matches];
    $scrun = $db->data[Runs];
    //$schig = $db->data[HS];   
    } else {
    }
    
    // get the highscore

    if ($db->Exists("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.season = $i ORDER BY b.runs DESC")) {
    $db->QueryRow("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.season = $i ORDER BY b.runs DESC");
    $db->BagAndTag();
    $scnos = $db->data[notout];
    $schig = $db->data[HS]; 
    } else {
    }   
	
	// get not out - did not bat is also included
    if ($db->Exists("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND (how_out = 2 or how_out = 1) AND season=$i")) {
    $db->QueryRow("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND (how_out = 2 or how_out = 1) AND season=$i");
    $db->BagAndTag();
    $scnot = $db->data[Notout];
    $outin = $scinn - $scnot;
    
    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = "-";
    }
    } else {
    }
    
    
    if ($db->Exists("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND season=$i")) {  
    $db->QueryRow("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND season=$i");
    $db->BagAndTag();
    $schun = $db->data[Hundred];    
    } else {
    $schun = "-";
    }
    
    if ($db->Exists("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND season=$i")) {   
    $db->QueryRow("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND season=$i");
    $db->BagAndTag();
    $scfif = $db->data[Fifty];      
    } else {
    $scfif = "-";
    }
    
    // Get the caught
    
    if ($db->Exists("SELECT COUNT(assist) AS Caught FROM scorecard_batting_details WHERE assist = $pr AND how_out = 4 AND season=$i")) {    
    $db->QueryRow("SELECT COUNT(assist) AS Caught FROM scorecard_batting_details WHERE assist = $pr AND how_out = 4 AND season=$i");
    $db->BagAndTag();
    $scctc = $db->data[Caught];
    } else {
    $scctc = "-";
    }
    
    // now add the c&b
    
    if ($db->Exists("SELECT COUNT(bowler) AS CandB FROM scorecard_batting_details WHERE bowler = $pr AND how_out = 5 AND season=$i")) { 
    $db->QueryRow("SELECT COUNT(bowler) AS CandB FROM scorecard_batting_details WHERE bowler = $pr AND how_out = 5 AND season=$i");
    $db->BagAndTag();
    $sccab = $db->data[CandB];
    } else {
    $sccab = "-";
    }
    
    $sccat = $scctc + $sccab;

    if ($db->Exists("SELECT COUNT(assist) AS Stumped FROM scorecard_batting_details WHERE assist = $pr AND how_out = 10 AND season=$i")) {  
    $db->QueryRow("SELECT COUNT(assist) AS Stumped FROM scorecard_batting_details WHERE assist = $pr AND how_out = 10 AND season=$i");
    $db->BagAndTag();
    $scstu = $db->data[Stumped];
    } else {
    $scstu = "-";
    }
    
    if ($db->Exists("SELECT * FROM scorecard_batting_details WHERE player_id = $pr AND season=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
            
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($seasons[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scnot</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig";
    if($scnos == '1') echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$schun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scfif</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scstu</td>\n";    
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Bowling Statistics Box by Year                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $seasons[$db->data[SeasonID]] = $db->data[SeasonName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING ANALYSIS BY SEASON</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>SEASON</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BBI</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>4w</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>ECO</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($seasons)+1; $i++) {

    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.season=$i GROUP BY p.PlayerLName, p.PlayerFName")) {   
    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.season=$i GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scmai = $db->data[Maidens];
    $scbru = $db->data[BRuns];
    $scwic = $db->data[Wickets];


    $bnum = $db->data[Balls]; 
    $bovers = Round(($bnum / 6), 2); 
    $bfloor = floor($bovers); 

    if($bovers == $bfloor + 0.17) { 
      $scove = $bfloor + 0.1; 
    } else 
    if($bovers == $bfloor + 0.33) { 
      $scove = $bfloor + 0.2; 
    } else 
    if($bovers == $bfloor + 0.5) { 
      $scove = $bfloor + 0.3;        
    } else 
    if($bovers == $bfloor + 0.67) { 
      $scove = $bfloor + 0.4;        
    } else 
    if($bovers == $bfloor + 0.83) { 
      $scove = $bfloor + 0.5; 
    } else { 
      $scove = $bfloor; 
    }     


    } else {
      $scove = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND season=$i")) {   
    $db->QueryRow("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND season=$i");
    $db->BagAndTag();
    $scbfo = $db->data[fourwickets];
    } else {
    $scbfo = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND season=$i")) {  
    $db->QueryRow("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND season=$i");
    $db->BagAndTag();
    $scbfi = $db->data[fivewickets];
    } else {
    $scbfi = "-";
    }
    
    if ($db->Exists("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND season=$i ORDER BY wickets DESC, runs ASC LIMIT 1")) {    
    $db->QueryRow("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND season=$i ORDER BY wickets DESC, runs ASC LIMIT 1");
    $db->BagAndTag();
    $scbbw = $db->data[wickets];
    $scbbr = $db->data[runs];   
    
    if($scbru >= 1 && $scwic >= 1) {
    $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
    $boavg = "-";
    }
    
    if($scbru >= 1 && $scove >= 0.1) {  
    $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
    $boeco = "-";
    }   
    
    } else {
    }
    
    if ($db->Exists("SELECT * FROM scorecard_bowling_details WHERE player_id = $pr AND season=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($seasons[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfo</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boeco</td>\n";   
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";
        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
}


function show_breakdown_opponent($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Player Stats</font></p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Team                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $db->Query("SELECT * FROM teams");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data[TeamID]] = $db->data[TeamAbbrev];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BATTING ANALYSIS BY OPPONENT</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>OPPONENT</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>100</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>50</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>St</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($teams)+1; $i++) {

    if ($db->Exists("SELECT   
              p.PlayerLName, p.PlayerFName,
              t.TeamAbbrev,
              o.TeamAbbrev,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs              
            FROM            
              scorecard_batting_details b   
            LEFT JOIN           
              players p ON b.player_id = p.PlayerID       
            LEFT JOIN
              teams t ON b.team = t.TeamID            
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            WHERE 
              b.player_id = $pr and opponent=$i  
            GROUP BY    
              p.PlayerLName, p.PlayerFName")) {
  
    $db->QueryRow("SELECT   
              p.PlayerLName, p.PlayerFName,
              t.TeamAbbrev,
              o.TeamAbbrev,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs  
            FROM 
              scorecard_batting_details b   
            LEFT JOIN 
              players p ON b.player_id = p.PlayerID   
            LEFT JOIN
              teams t ON b.team = t.TeamID  
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            WHERE 
              b.player_id = $pr and opponent=$i  
            GROUP BY 
              p.PlayerLName, p.PlayerFName
            ORDER BY
              Runs DESC");

    $db->BagAndTag();
    $scinn = $db->data[Matches];
    $scrun = $db->data[Runs];
    //$schig = $db->data[HS];   
    } else {
    }
    
    // get the highscore

    if ($db->Exists("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.opponent = $i ORDER BY b.runs DESC")) {
    $db->QueryRow("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.opponent = $i ORDER BY b.runs DESC");
    $db->BagAndTag();
    $scnos = $db->data[notout];
    $schig = $db->data[HS]; 
    } else {
    }   

    if ($db->Exists("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND how_out = 2 AND opponent=$i")) {
    $db->QueryRow("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND how_out = 2 AND opponent=$i");
    $db->BagAndTag();
    $scnot = $db->data[Notout];
    $outin = $scinn - $scnot;
    
    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = "-";
    }
    } else {
    }
    
    
    if ($db->Exists("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND opponent=$i")) {    
    $db->QueryRow("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND opponent=$i");
    $db->BagAndTag();
    $schun = $db->data[Hundred];    
    } else {
    $schun = "-";
    }
    
    if ($db->Exists("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND opponent=$i")) { 
    $db->QueryRow("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND opponent=$i");
    $db->BagAndTag();
    $scfif = $db->data[Fifty];      
    } else {
    $scfif = "-";
    }
    
    // Get the caught
    
    if ($db->Exists("SELECT COUNT(assist) AS Caught FROM scorecard_batting_details WHERE assist = $pr AND how_out = 4 AND team=$i")) {  
    $db->QueryRow("SELECT COUNT(assist) AS Caught FROM scorecard_batting_details WHERE assist = $pr AND how_out = 4 AND team=$i");
    $db->BagAndTag();
    $scctc = $db->data[Caught];
    } else {
    $scctc = "-";
    }
    
    // now add the c&b
    
    if ($db->Exists("SELECT COUNT(bowler) AS CandB FROM scorecard_batting_details WHERE bowler = $pr AND how_out = 5 AND team=$i")) {   
    $db->QueryRow("SELECT COUNT(bowler) AS CandB FROM scorecard_batting_details WHERE bowler = $pr AND how_out = 5 AND team=$i");
    $db->BagAndTag();
    $sccab = $db->data[CandB];
    } else {
    $sccab = "-";
    }
    
    $sccat = $scctc + $sccab;

    if ($db->Exists("SELECT COUNT(assist) AS Stumped FROM scorecard_batting_details WHERE assist = $pr AND how_out = 10 AND team=$i")) {    
    $db->QueryRow("SELECT COUNT(assist) AS Stumped FROM scorecard_batting_details WHERE assist = $pr AND how_out = 10 AND team=$i");
    $db->BagAndTag();
    $scstu = $db->data[Stumped];
    } else {
    $scstu = "-";
    }
    
    if ($db->Exists("SELECT * FROM scorecard_batting_details WHERE player_id = $pr AND opponent=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($teams[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scnot</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig";
    if($scnos == '1') echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$schun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scfif</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scstu</td>\n";    
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Bowling Statistics Box by Opponent                                                                                    //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $db->Query("SELECT * FROM teams");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data[TeamID]] = $db->data[TeamAbbrev];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING ANALYSIS BY OPPONENT</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>OPPONENT</td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BBI</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>4w</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>ECO</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($teams)+1; $i++) {

    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.opponent=$i GROUP BY p.PlayerLName, p.PlayerFName")) { 
    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.opponent=$i GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scmai = $db->data[Maidens];
    $scbru = $db->data[BRuns];
    $scwic = $db->data[Wickets];


    $bnum = $db->data[Balls]; 
    $bovers = Round(($bnum / 6), 2); 
    $bfloor = floor($bovers); 

    if($bovers == $bfloor + 0.17) { 
      $scove = $bfloor + 0.1; 
    } else 
    if($bovers == $bfloor + 0.33) { 
      $scove = $bfloor + 0.2; 
    } else 
    if($bovers == $bfloor + 0.5) { 
      $scove = $bfloor + 0.3;        
    } else 
    if($bovers == $bfloor + 0.67) { 
      $scove = $bfloor + 0.4;        
    } else 
    if($bovers == $bfloor + 0.83) { 
      $scove = $bfloor + 0.5; 
    } else { 
      $scove = $bfloor; 
    }     


    } else {
      $scove = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND opponent=$i")) { 
    $db->QueryRow("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND opponent=$i");
    $db->BagAndTag();
    $scbfo = $db->data[fourwickets];
    } else {
    $scbfo = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND opponent=$i")) {    
    $db->QueryRow("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND opponent=$i");
    $db->BagAndTag();
    $scbfi = $db->data[fivewickets];
    } else {
    $scbfi = "-";
    }
    
    if ($db->Exists("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND opponent=$i ORDER BY wickets DESC, runs ASC LIMIT 1")) {  
    $db->QueryRow("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND opponent=$i ORDER BY wickets DESC, runs ASC LIMIT 1");
    $db->BagAndTag();
    $scbbw = $db->data[wickets];
    $scbbr = $db->data[runs];   
    
    if($scbru >= 1 && $scwic >= 1) {
    $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
    $boavg = "-";
    }
    
    if($scbru >= 1 && $scove >= 0.1) {  
    $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
    $boeco = "-";
    }   
    
    } else {
    }
    
    if ($db->Exists("SELECT * FROM scorecard_bowling_details WHERE player_id = $pr AND opponent=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($teams[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfo</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boeco</td>\n";   
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";
        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}   


function show_breakdown_ground($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Player Stats</font></p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Ground                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $db->Query("SELECT * FROM grounds");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $grounds[$db->data[GroundID]] = $db->data[GroundName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BATTING ANALYSIS BY GROUND</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>GROUND</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>100</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>50</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>St</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($grounds)+1; $i++) {

    if ($db->Exists("SELECT   
              p.PlayerLName, p.PlayerFName,
              g.GroundName,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS,
              m.game_id, b.game_id
            FROM            
              scorecard_batting_details b   
            LEFT JOIN
              scorecard_game_details m ON m.game_id = b.game_id
            LEFT JOIN           
              players p ON b.player_id = p.PlayerID       
            LEFT JOIN
              grounds g ON g.GroundID = m.ground_id           
            WHERE 
              b.player_id = $pr AND m.ground_id = $i
            GROUP BY    
              p.PlayerLName, p.PlayerFName")) {
  
    $db->QueryRow("SELECT   
              p.PlayerLName, p.PlayerFName,
              g.GroundName,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS,
              m.game_id, b.game_id
            FROM            
              scorecard_batting_details b   
            LEFT JOIN
              scorecard_game_details m ON m.game_id = b.game_id
            LEFT JOIN           
              players p ON b.player_id = p.PlayerID       
            LEFT JOIN
              grounds g ON g.GroundID = m.ground_id           
            WHERE 
              b.player_id = $pr AND m.ground_id = $i 
            GROUP BY    
              p.PlayerLName, p.PlayerFName");

    $db->BagAndTag();
    $scinn = $db->data[Matches];
    $scrun = $db->data[Runs];
    //$schig = $db->data[HS];   
    } else {
    }

    // get the highscore

    if ($db->Exists("SELECT b.runs AS HS, b.notout FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id = $i ORDER BY b.runs DESC")) {
    $db->QueryRow("SELECT b.runs AS HS, b.notout FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id = $i ORDER BY b.runs DESC");
    $db->BagAndTag();
    $scnos = $db->data[notout];
    $schig = $db->data[HS]; 
    } else {
    }
    
    if ($db->Exists("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.how_out = 2 AND m.ground_id=$i")) {
    $db->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.how_out = 2 AND m.ground_id=$i");
    $db->BagAndTag();
    $scnot = $db->data[Notout];
    $outin = $scinn - $scnot;
    
    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = "-";
    }
    } else {
    }
    
    
    if ($db->Exists("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.runs >= 100 AND m.ground_id=$i")) { 
    $db->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.runs >= 100 AND m.ground_id=$i");
    $db->BagAndTag();
    $schun = $db->data[Hundred];    
    } else {
    $schun = "-";
    }
    
    if ($db->Exists("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND (runs BETWEEN 50 AND 99) AND m.ground_id=$i")) {    
    $db->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND (runs BETWEEN 50 AND 99) AND m.ground_id=$i");
    $db->BagAndTag();
    $scfif = $db->data[Fifty];      
    } else {
    $scfif = "-";
    }
    
    // Get the caught
    
    if ($db->Exists("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.assist = $pr AND how_out = 4 AND m.ground_id=$i")) { 
    $db->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.assist = $pr AND how_out = 4 AND m.ground_id=$i");
    $db->BagAndTag();
    $scctc = $db->data[Caught];
    } else {
    $scctc = "-";
    }
    
    // now add the c&b
    
    if ($db->Exists("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.bowler = $pr AND how_out = 5 AND m.ground_id=$i")) {  
    $db->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.bowler = $pr AND how_out = 5 AND m.ground_id=$i");
    $db->BagAndTag();
    $sccab = $db->data[CandB];
    } else {
    $sccab = "-";
    }
    
    $sccat = $scctc + $sccab;

    if ($db->Exists("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.assist = $pr AND how_out = 10 AND m.ground_id=$i")) {   
    $db->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.assist = $pr AND how_out = 10 AND m.ground_id=$i");
    $db->BagAndTag();
    $scstu = $db->data[Stumped];
    } else {
    $scstu = "-";
    }
    
    if ($db->Exists("SELECT * FROM scorecard_batting_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($grounds[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scnot</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig";
    if($scnos == '1') echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$schun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scfif</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scstu</td>\n";    
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";
    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";  

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Bowling Statistics Box by Ground                                                                                      //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $db->Query("SELECT * FROM grounds");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $grounds[$db->data[GroundID]] = $db->data[GroundName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING ANALYSIS BY GROUND</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>GROUND</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BBI</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>4w</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>ECO</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($grounds)+1; $i++) {

    if ($db->Exists("SELECT SUM(IF(INSTR(b.overs, '.'),((LEFT(b.overs, INSTR(b.overs, '.') - 1) * 6) + RIGHT(b.overs, INSTR(b.overs, '.') - 1)),(b.overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id=$i GROUP BY p.PlayerLName, p.PlayerFName")) {    
    $db->QueryRow("SELECT SUM(IF(INSTR(b.overs, '.'),((LEFT(b.overs, INSTR(b.overs, '.') - 1) * 6) + RIGHT(b.overs, INSTR(b.overs, '.') - 1)),(b.overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id=$i GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scmai = $db->data[Maidens];
    $scbru = $db->data[BRuns];
    $scwic = $db->data[Wickets];


    $bnum = $db->data[Balls]; 
    $bovers = Round(($bnum / 6), 2); 
    $bfloor = floor($bovers); 

    if($bovers == $bfloor + 0.17) { 
      $scove = $bfloor + 0.1; 
    } else 
    if($bovers == $bfloor + 0.33) { 
      $scove = $bfloor + 0.2; 
    } else 
    if($bovers == $bfloor + 0.5) { 
      $scove = $bfloor + 0.3;        
    } else 
    if($bovers == $bfloor + 0.67) { 
      $scove = $bfloor + 0.4;        
    } else 
    if($bovers == $bfloor + 0.83) { 
      $scove = $bfloor + 0.5; 
    } else { 
      $scove = $bfloor; 
    }     


    } else {
      $scove = "-";
    }

    if ($db->Exists("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.wickets = 4 AND m.ground_id=$i")) {  
    $db->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.wickets = 4 AND m.ground_id=$i");
    $db->BagAndTag();
    $scbfo = $db->data[fourwickets];
    } else {
    $scbfo = "-";
    }

    if ($db->Exists("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.wickets >= 5 AND m.ground_id=$i")) { 
    $db->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details  b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND b.wickets >= 5 AND m.ground_id=$i");
    $db->BagAndTag();
    $scbfi = $db->data[fivewickets];
    } else {
    $scbfi = "-";
    }
    
    if ($db->Exists("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id=$i ORDER BY b.wickets DESC, b.runs ASC LIMIT 1")) { 
    $db->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id=$i ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    $db->BagAndTag();
    $scbbw = $db->data[wickets];
    $scbbr = $db->data[runs];   
    
    if($scbru >= 1 && $scwic >= 1) {
    $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
    $boavg = "-";
    }
    
    if($scbru >= 1 && $scove >= 0.1) {  
    $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
    $boeco = "-";
    }   
    
    } else {
    }
    
    if ($db->Exists("SELECT b.* FROM scorecard_bowling_details b LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id WHERE b.player_id = $pr AND m.ground_id=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($grounds[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfo</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boeco</td>\n";   
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";
        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
}   


function show_breakdown_batpos($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Player Stats</font></p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Batting Position                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $db->Query("SELECT * FROM batpositions");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $positions[$db->data[BatPosID]] = $db->data[BatPosName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BATTING ANALYSIS BY BATTING POSITION</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>BATTING POS.</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>100</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>50</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($positions)+1; $i++) {

    if ($db->Exists("SELECT   
              p.PlayerLName, p.PlayerFName,
              t.TeamAbbrev,
              o.TeamAbbrev,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS             
            FROM            
              scorecard_batting_details b   
            LEFT JOIN           
              players p ON b.player_id = p.PlayerID       
            LEFT JOIN
              teams t ON b.team = t.TeamID            
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            WHERE 
              b.player_id = $pr and b.batting_position=$i  
            GROUP BY    
              p.PlayerLName, p.PlayerFName")) {
  
    $db->QueryRow("SELECT   
              p.PlayerLName, p.PlayerFName,
              t.TeamAbbrev,
              o.TeamAbbrev,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS  
            FROM 
              scorecard_batting_details b   
            LEFT JOIN 
              players p ON b.player_id = p.PlayerID   
            LEFT JOIN
              teams t ON b.team = t.TeamID  
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            WHERE 
              b.player_id = $pr and batting_position=$i  
            GROUP BY 
              p.PlayerLName, p.PlayerFName");

    $db->BagAndTag();
    $scinn = $db->data[Matches];
    $scrun = $db->data[Runs];
    //$schig = $db->data[HS];   
    } else {
    }

    if ($db->Exists("SELECT runs AS HS, notout FROM scorecard_batting_details WHERE player_id = $pr AND batting_position = $i ORDER BY runs DESC")) {
    $db->QueryRow("SELECT runs AS HS, notout FROM scorecard_batting_details WHERE player_id = $pr AND batting_position = $i ORDER BY runs DESC");
    $db->BagAndTag();
    $scnos = $db->data[notout];
    $schig = $db->data[HS]; 
    } else {
    }


    if ($db->Exists("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND how_out = 2 AND batting_position=$i")) {
    $db->QueryRow("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND how_out = 2 AND batting_position=$i");
    $db->BagAndTag();
    $scnot = $db->data[Notout];
    $outin = $scinn - $scnot;
    
    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = "-";
    }
    } else {
    }
    
    
    if ($db->Exists("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND batting_position=$i")) {    
    $db->QueryRow("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND batting_position=$i");
    $db->BagAndTag();
    $schun = $db->data[Hundred];    
    } else {
    $schun = "-";
    }
    
    if ($db->Exists("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND batting_position=$i")) { 
    $db->QueryRow("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND batting_position=$i");
    $db->BagAndTag();
    $scfif = $db->data[Fifty];      
    } else {
    $scfif = "-";
    }
    

    
    if ($db->Exists("SELECT * FROM scorecard_batting_details WHERE player_id = $pr AND batting_position=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($positions[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scnot</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig";
    if($scnot == '1') echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$schun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scfif</td>\n";
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Bowling Statistics Box by Bowling Position                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $db->Query("SELECT * FROM batpositions");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $positions[$db->data[BatPosID]] = $db->data[BatPosName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING ANALYSIS BY BOWLING POSITION</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>BOWLING POS.</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BBI</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>4w</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>ECO</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($positions)+1; $i++) {

    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.bowling_position=$i GROUP BY p.PlayerLName, p.PlayerFName")) { 
    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.bowling_position=$i GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scmai = $db->data[Maidens];
    $scbru = $db->data[BRuns];
    $scwic = $db->data[Wickets];


    $bnum = $db->data[Balls]; 
    $bovers = Round(($bnum / 6), 2); 
    $bfloor = floor($bovers); 

    if($bovers == $bfloor + 0.17) { 
      $scove = $bfloor + 0.1; 
    } else 
    if($bovers == $bfloor + 0.33) { 
      $scove = $bfloor + 0.2; 
    } else 
    if($bovers == $bfloor + 0.5) { 
      $scove = $bfloor + 0.3;        
    } else 
    if($bovers == $bfloor + 0.67) { 
      $scove = $bfloor + 0.4;        
    } else 
    if($bovers == $bfloor + 0.83) { 
      $scove = $bfloor + 0.5; 
    } else { 
      $scove = $bfloor; 
    }     


    } else {
      $scove = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND bowling_position=$i")) { 
    $db->QueryRow("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND bowling_position=$i");
    $db->BagAndTag();
    $scbfo = $db->data[fourwickets];
    } else {
    $scbfo = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND bowling_position=$i")) {    
    $db->QueryRow("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND bowling_position=$i");
    $db->BagAndTag();
    $scbfi = $db->data[fivewickets];
    } else {
    $scbfi = "-";
    }
    
    if ($db->Exists("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND bowling_position=$i ORDER BY wickets DESC, runs ASC LIMIT 1")) {  
    $db->QueryRow("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND bowling_position=$i ORDER BY wickets DESC, runs ASC LIMIT 1");
    $db->BagAndTag();
    $scbbw = $db->data[wickets];
    $scbbr = $db->data[runs];   
    
    if($scbru >= 1 && $scwic >= 1) {
    $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
    $boavg = "-";
    }
    
    if($scbru >= 1 && $scove >= 0.1) {  
    $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
    $boeco = "-";
    }   
    
    } else {
    }
    
    if ($db->Exists("SELECT * FROM scorecard_bowling_details WHERE player_id = $pr AND bowling_position=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($positions[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfo</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boeco</td>\n";   
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";

        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
}   


function show_breakdown_innno($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Player Stats</font></p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Innings Number                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $db->Query("SELECT * FROM innings");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $inns[$db->data[InnID]] = $db->data[InnName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BATTING ANALYSIS BY INNINGS NUMBER</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>INNINGS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>100</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>50</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($inns); $i++) {

    if ($db->Exists("SELECT   
              p.PlayerLName, p.PlayerFName,
              t.TeamAbbrev,
              o.TeamAbbrev,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS             
            FROM            
              scorecard_batting_details b   
            LEFT JOIN           
              players p ON b.player_id = p.PlayerID       
            LEFT JOIN
              teams t ON b.team = t.TeamID            
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            WHERE 
              b.player_id = $pr and innings_id=$i  
            GROUP BY    
              p.PlayerLName, p.PlayerFName")) {
  
    $db->QueryRow("SELECT   
              p.PlayerLName, p.PlayerFName,
              t.TeamAbbrev,
              o.TeamAbbrev,
              COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS  
            FROM 
              scorecard_batting_details b   
            LEFT JOIN 
              players p ON b.player_id = p.PlayerID   
            LEFT JOIN
              teams t ON b.team = t.TeamID  
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            WHERE 
              b.player_id = $pr and innings_id=$i  
            GROUP BY 
              p.PlayerLName, p.PlayerFName");

    $db->BagAndTag();
    $scinn = $db->data[Matches];
    $scrun = $db->data[Runs];
    //$schig = $db->data[HS];   
    } else {
    }

    if ($db->Exists("SELECT runs AS HS, notout FROM scorecard_batting_details WHERE player_id = $pr AND innings_id = $i ORDER BY runs DESC")) {
    $db->QueryRow("SELECT runs AS HS, notout FROM scorecard_batting_details WHERE player_id = $pr AND innings_id = $i ORDER BY runs DESC");
    $db->BagAndTag();
    $scnos = $db->data[notout];
    $schig = $db->data[HS]; 
    } else {
    }
    
    if ($db->Exists("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND how_out = 2 AND innings_id=$i")) {
    $db->QueryRow("SELECT COUNT(how_out) AS Notout FROM scorecard_batting_details WHERE player_id = $pr AND how_out = 2 AND innings_id=$i");
    $db->BagAndTag();
    $scnot = $db->data[Notout];
    $outin = $scinn - $scnot;
    
    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = "-";
    }
    } else {
    }
    
    
    if ($db->Exists("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND innings_id=$i")) {  
    $db->QueryRow("SELECT COUNT(runs) AS Hundred FROM scorecard_batting_details WHERE player_id = $pr AND runs >= 100 AND innings_id=$i");
    $db->BagAndTag();
    $schun = $db->data[Hundred];    
    } else {
    $schun = "-";
    }
    
    if ($db->Exists("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND innings_id=$i")) {   
    $db->QueryRow("SELECT COUNT(runs) AS Fifty FROM scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) AND innings_id=$i");
    $db->BagAndTag();
    $scfif = $db->data[Fifty];      
    } else {
    $scfif = "-";
    }
    

    
    if ($db->Exists("SELECT * FROM scorecard_batting_details WHERE player_id = $pr AND innings_id=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($inns[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scnot</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig";
    if($scnos == '1') echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scavg</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$schun</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scfif</td>\n";
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";


    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Bowling Statistics Box by Innings Number                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    $db->Query("SELECT * FROM innings");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $inns[$db->data[InnID]] = $db->data[InnName];
    }
                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;BOWLING ANALYSIS BY INNINGS NUMBER</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"35%\"><b>INNINGS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"right\" width=\"5%\"><b>M</b></td>\n";
    echo "  <td align=\"right\" width=\"8%\"><b>R</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>BBI</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>4w</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>5w</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>ECO</b></td>\n";
    echo " </tr>\n";
    
        for ($i=1; $i<=count($inns); $i++) {

    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.innings_id=$i GROUP BY p.PlayerLName, p.PlayerFName")) {   
    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID WHERE b.player_id = $pr AND b.innings_id=$i GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scmai = $db->data[Maidens];
    $scbru = $db->data[BRuns];
    $scwic = $db->data[Wickets];


    $bnum = $db->data[Balls]; 
    $bovers = Round(($bnum / 6), 2); 
    $bfloor = floor($bovers); 

    if($bovers == $bfloor + 0.17) { 
      $scove = $bfloor + 0.1; 
    } else 
    if($bovers == $bfloor + 0.33) { 
      $scove = $bfloor + 0.2; 
    } else 
    if($bovers == $bfloor + 0.5) { 
      $scove = $bfloor + 0.3;        
    } else 
    if($bovers == $bfloor + 0.67) { 
      $scove = $bfloor + 0.4;        
    } else 
    if($bovers == $bfloor + 0.83) { 
      $scove = $bfloor + 0.5; 
    } else { 
      $scove = $bfloor; 
    }     


    } else {
      $scove = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND innings_id=$i")) {   
    $db->QueryRow("SELECT COUNT(wickets) AS fourwickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets = 4 AND innings_id=$i");
    $db->BagAndTag();
    $scbfo = $db->data[fourwickets];
    } else {
    $scbfo = "-";
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND innings_id=$i")) {  
    $db->QueryRow("SELECT COUNT(wickets) AS fivewickets FROM scorecard_bowling_details WHERE player_id = $pr AND wickets >= 5 AND innings_id=$i");
    $db->BagAndTag();
    $scbfi = $db->data[fivewickets];
    } else {
    $scbfi = "-";
    }
    
    if ($db->Exists("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND innings_id=$i ORDER BY wickets DESC, runs ASC LIMIT 1")) {    
    $db->QueryRow("SELECT player_id, wickets, runs FROM scorecard_bowling_details WHERE player_id = $pr AND innings_id=$i ORDER BY wickets DESC, runs ASC LIMIT 1");
    $db->BagAndTag();
    $scbbw = $db->data[wickets];
    $scbbr = $db->data[runs];   
    
    if($scbru >= 1 && $scwic >= 1) {
    $boavg = Number_Format(Round($scbru / $scwic, 2),2);
    } else {
    $boavg = "-";
    }
    
    if($scbru >= 1 && $scove >= 0.1) {  
    $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
    $boeco = "-";
    }   
    
    } else {
    }
    
    if ($db->Exists("SELECT * FROM scorecard_bowling_details WHERE player_id = $pr AND innings_id=$i")) {
    
    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"35%\">" . htmlentities(stripslashes($inns[$i])) . "</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfo</td>\n";
    echo "  <td align=\"right\" width=\"4%\">$scbfi</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$boeco</td>\n";   
    echo " </tr>\n";
    
    } else {
    }
    }
    
    echo "</table>\n";



    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";

        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
}   



function show_breakdown_batprogress($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
	$dbb = $db;
	$dbb1 = $db;
    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pla = $db->data[PlayerLAbbrev];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Player Stats</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Progression                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                
    echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;CAREER BATTING/BOWLING - INNINGS BY INNINGS PROGRESS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" nowrap><b>DATE</b></td>\n";
    echo "  <td align=\"left\"><b>VS</b></td>\n";
    echo "  <td align=\"left\"><b>GROUND</b></td>\n";
    echo "  <td align=\"left\"><b>HOW DISMISSED</b></td>\n";
    echo "  <td align=\"right\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\"><b>BOWLING</b></td>\n";
    echo " </tr>\n";
    
	$dbb1->QueryRow("select DISTINCT g.game_id, g.game_date from scorecard_game_details g, scorecard_batting_details b where g.game_id=b.game_id and  b.player_id = $pr  
					union
					select DISTINCT g.game_id, g.game_date from scorecard_game_details g, scorecard_bowling_details b where g.game_id=b.game_id and  b.player_id = $pr order by game_date 
				  ");
    
    $dbb1->BagAndTag();
   
    for ($t=0; $t<$dbb1->rows; $t++) {
        $dbb1->GetRow($t);
        $game_id = $dbb1->data[game_id];
         
    if ($db->Exists("SELECT   
              p.PlayerID, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS BatterFInitial,
              m.game_date, m.game_id, 
              t.TeamID AS TeamID, t.TeamName, t.TeamAbbrev, 
              o.TeamID AS OpponentID, o.TeamName AS OpponentName, o.TeamAbbrev AS OpponentAbbrev, 
              h.HowOutID, h.HowOutName, h.HowOutAbbrev, 
              g.GroundID, g.GroundName, 
              a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
              w.PlayerLName AS BowlerLName, w.PlayerFName AS BowlerFName, LEFT(w.PlayerFName,1) AS BowlerFInitial,            
              b.assist, b.bowler, b.runs , b.notout    
            FROM            
              scorecard_batting_details b   
            LEFT JOIN
              scorecard_game_details m ON m.game_id = b.game_id 
            LEFT JOIN
              players a ON a.PlayerID = b.assist
            LEFT JOIN
              players p ON p.PlayerID = b.player_id
            LEFT JOIN
              players w ON w.PlayerID = b.bowler      
            LEFT JOIN
              teams t ON b.team = t.TeamID            
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            LEFT JOIN
              grounds g ON g.GroundID = m.ground_id
            LEFT JOIN
              howout h ON h.HowOutID = b.how_out              
            WHERE 
              b.player_id = $pr and b.game_id = $game_id
       
			order by m.game_date  
            ")) {
  
    		$db->QueryRow("SELECT   
			              p.PlayerID, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev, LEFT(p.PlayerFName,1) AS BatterFInitial,
			              m.game_date, m.game_id, 
			              t.TeamID AS TeamID, t.TeamName, t.TeamAbbrev, 
			              o.TeamID AS OpponentID, o.TeamName AS OpponentName, o.TeamAbbrev AS OpponentAbbrev, 
			              h.HowOutID, h.HowOutName, h.HowOutAbbrev, 
			              g.GroundID, g.GroundName, 
			              a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
			              w.PlayerLName AS BowlerLName, w.PlayerFName AS BowlerFName, LEFT(w.PlayerFName,1) AS BowlerFInitial,            
			              b.assist, b.bowler, b.runs , b.notout    
			            FROM            
			              scorecard_batting_details b   
			            LEFT JOIN
			              scorecard_game_details m ON m.game_id = b.game_id 
			            LEFT JOIN
			              players a ON a.PlayerID = b.assist
			            LEFT JOIN
			              players p ON p.PlayerID = b.player_id
			            LEFT JOIN
			              players w ON w.PlayerID = b.bowler      
			            LEFT JOIN
			              teams t ON b.team = t.TeamID            
			            LEFT JOIN
			              teams o ON b.opponent = o.TeamID  
			            LEFT JOIN
			              grounds g ON g.GroundID = m.ground_id
			            LEFT JOIN
			              howout h ON h.HowOutID = b.how_out              
			            WHERE 
			              b.player_id = $pr and b.game_id = $game_id           
						order by m.game_date ");
			
         } else {
         	
            $db->QueryRow("SELECT 
				            p.PlayerID, p.PlayerFName, p.PlayerLName, p.PlayerLAbbrev, LEFT( p.PlayerFName, 1 ) AS BatterFInitial, 
				            m.game_date, m.game_id, 
				            t.TeamID AS TeamID, t.TeamName, t.TeamAbbrev, 
				            o.TeamID AS OpponentID, o.TeamName AS OpponentName, o.TeamAbbrev AS OpponentAbbrev, 
				            0 AS HowOutID, 'dnb' AS HowOutName, 'dnb' AS HowOutAbbrev, 
				            g.GroundID, g.GroundName, '' AS AssistLName, '' AS AssistFName, '' AS AssistFInitial, '' AS BowlerLName, '' AS BowlerFName, '' AS BowlerFInitial, 0 AS Assist, 0 AS bowler, 0 AS runs, 0 AS notout
							FROM scorecard_bowling_details b
							LEFT JOIN scorecard_game_details m ON m.game_id = b.game_id
							LEFT JOIN players p ON p.PlayerID = b.player_id
							LEFT JOIN teams t ON b.team = t.TeamID
							LEFT JOIN teams o ON b.opponent = o.TeamID
							LEFT JOIN grounds g ON g.GroundID = m.ground_id
							WHERE b.player_id = $pr and b.game_id = $game_id
				           	order by m.game_date");
       }

    $db->BagAndTag();
   
    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
      
        $gid = $db->data[game_id];
        $dte = sqldate_to_string($db->data[game_date]);
        $dat = $db->data[game_date];
        $opp = $db->data[OpponentAbbrev];
        $gro = $db->data[GroundName];
        $out = $db->data[HowOutAbbrev];
        $oid = $db->data[HowOutID];
        $pln = $db->data[PlayerLName];
        $pfn = $db->data[PlayerFName];
        $pin = $db->data[BatterFInitial];
        $bln = $db->data[BowlerLName];
        $bfn = $db->data[BowlerFName];
        $ala = $db->data[AssistLAbbrev];
        $bla = $db->data[BowlerLAbbrev];
        $pla = $db->data[PlayerLAbbrev];
        $bin = $db->data[BowlerFInitial];
        $aln = $db->data[AssistLName];
        $afn = $db->data[AssistFName];
        $ain = $db->data[AssistFInitial];   
        $run = $db->data[runs];
        $not = $db->data[notout];
        


    
    if($r % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" nowrap><a href=\"http://www.coloradocricket.org/scorecardfull.php?game_id=$gid&ccl_mode=4\">$dat</a></td>\n";
    echo "  <td align=\"left\" width=\"15\">$opp</td>\n";
    echo "  <td align=\"left\">$gro</td>\n";
    echo "  <td align=\"left\">";
    

    // If Assist Last Name is blank, just use first name

    // Make caught and bowled (c & part) align right in the assist column
    if($oid == 5) {

    echo "  $out&nbsp;";

    } else {


    // dont display bowled or caught and bowled
    if($oid == 3 || $oid == 5) {
      echo "";
    } else {
      echo "$out ";
    }

    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "" && $ala != "") {
      echo "$ain $ala";
    } elseif($afn != "" && $aln != "" && $ala == "") {
      echo "$ain $aln";   
    } else {
      echo "$afn\n";
    }

    }

    // If Bowler Last Name is blank, just use first name


    // display bowled if it goes with the wicket type
    if($oid == '3' || $oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {
      echo " b ";
    } else {
      echo "";
    }

    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "" && $bla != "") {
      echo "$bin $bla";
    } elseif($bfn != "" && $bln != "" && $bla == "") {
      echo "$bin $bln";   
    } else {
      echo "$bfn\n";
    }

    
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"5%\">$run";
    
    if($db->data[notout] == '1') echo "*";
    
    echo "  </td>\n";
    
    // Start
    
 	$str_query = " SELECT
      s.game_id, s.innings_id, s.bowling_position, s.overs, s.maidens, s.runs, s.wickets, s.noballs, s.wides,
      p.PlayerID AS BowlerID, p.PlayerLName AS BowlerLName, p.PlayerFName AS BowlerFName, LEFT(p.PlayerFName,1) AS BowlerFInitial
    FROM
      scorecard_bowling_details s
    LEFT JOIN
      players p ON p.PlayerID = s.player_id
    WHERE
      s.game_id = $gid AND s.player_id = $pr
    ";
    
    echo "	<td align=\"right\">";
    
    if($dbb->Exists($str_query)){
    	$dbb->Query($str_query);
    	$dbb->BagAndTag();
    	$j = 0;
    	$dbb->GetRow($j);
	    $ov = $dbb->data[overs];
	    $ma = $dbb->data[maidens];
	    $ru = $dbb->data[runs];
	    $wi = $dbb->data[wickets];
   	    $final_bowling = $ov."-".$ma."-".$ru."-".$wi;
    }else {
    	$final_bowling = "<center> - </center>";
    }
    echo $final_bowling."</td>";   
    
   // End
     
    echo " </tr>\n";
    }
}    
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";
        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
}


function show_graph_batprogress($db,$s,$id,$pr,$tid)
{
    //global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Batting Statistics Box by Progression                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

error_reporting(E_ALL ^ E_NOTICE);

include('postgraph.class.php'); 
        
        
        $data = array(1 => 0, 1.2, 2.5, 4.8, 16, 20, 22, 17, 7, 2, 1, 0);
        
        $graph = new PostGraph(550,330);

        $graph->setGraphTitles('$pfn $pln', 'Match #', 'Runs');

        $graph->setYNumberFormat('integer');

        $graph->setYTicks(10);

        $graph->setData($data);

        //$graph->setBackgroundColor(array(255,255,0));

        //$graph->setTextColor(array(144,144,144));

        $graph->setXTextOrientation('horizontal');

        $graph->drawImage();

        $graph->printImage();
    
}   

function show_breakdown_bowlprogress($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour, cl.ClubID, cl.ClubName
    FROM
      (players pl
    INNER JOIN
      clubs cl ON cl.ClubID = pl.PlayerClub)
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data[PlayerID];
    $pln = $db->data[PlayerLName];
    $pfn = $db->data[PlayerFName];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data[picture];
    $pic1 = $db->data[picture1];

    $tid = $db->data[TeamID];
    $tna = $db->data[TeamName];
    $tco = $db->data[TeamColour];

    $cid = $db->data[ClubID];
    $cna = $db->data[ClubName];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Player Stats</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
        echo "<table width=\"500\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\"><b class=\"16px\">$pfn $pln</b></td>\n";
        echo "  </tr>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data[earlydate]);

        echo "  <tr>\n";
        echo "    <td align=\"left\">From <b>$d</b> to the present.</td>\n";
        echo "  </tr>\n";

        echo "</table>\n";
        echo "<br>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CCL Bowling Statistics Box by Progression                                                                                        //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                
        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;CAREER BOWLING - INNINGS BY INNINGS PROGRESS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
 
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo " <tr class=\"trrow1\">\n";
    echo "  <td width=\"100%\">";
    
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\"><b>Date</b></td>\n";
    echo "  <td align=\"left\"><b>vs</b></td>\n";
    echo "  <td align=\"left\"><b>Ground</b></td>\n";
    echo "  <td align=\"left\"><b>How Dismissed</b></td>\n";
    echo "  <td align=\"right\"><b>Stats</b></td>\n";
    echo " </tr>\n";
    

    if ($db->Exists("SELECT * from scorecard_bowling_stats WHERE  
             player_id = $pr")) {
  
    $db->QueryRow("SELECT   
              p.PlayerID, p.PlayerFName, p.PlayerLName, LEFT(p.PlayerFName,1) AS BatterFInitial,
              m.game_date, m.game_id, 
              t.TeamID AS TeamID, t.TeamName, t.TeamAbbrev, 
              o.TeamID AS OpponentID, o.TeamName AS OpponentName, o.TeamAbbrev AS OpponentAbbrev, 
              h.HowOutID, h.HowOutName, h.HowOutAbbrev, 
              g.GroundID, g.GroundName, 
              a.PlayerLName AS AssistLName, a.PlayerFName AS AssistFName, LEFT(a.PlayerFName,1) AS AssistFInitial,
              b.assist, b.bowler, b.runs, b.notout,
              z.wickets AS BWickets, z.runs AS BRuns
            FROM            
              scorecard_bowling_details z   
            LEFT JOIN
              scorecard_batting_details b ON b.game_id = z.game_id
            LEFT JOIN
              scorecard_game_details m ON m.game_id = z.game_id 
            LEFT JOIN
              players a ON a.PlayerID = b.assist
            LEFT JOIN
              players p ON p.PlayerID = b.player_id   
            LEFT JOIN
              teams t ON b.team = t.TeamID            
            LEFT JOIN
              teams o ON b.opponent = o.TeamID  
            LEFT JOIN
              grounds g ON g.GroundID = m.ground_id
            LEFT JOIN
              howout h ON h.HowOutID = b.how_out              
            WHERE 
              z.player_id = $pr
            ORDER BY
              m.game_date");

    $db->BagAndTag();
    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        
        $gid = $db->data[game_id];
        $dte = sqldate_to_string($db->data[game_date]);
        $dat = $db->data[game_date];
        $opp = $db->data[OpponentAbbrev];
        $gro = $db->data[GroundName];
        $out = $db->data[HowOutAbbrev];
        $oid = $db->data[HowOutID];
        $pln = $db->data[PlayerLName];
        $pfn = $db->data[PlayerFName];
        $pin = $db->data[BatterFInitial];
        $bln = $db->data[BowlerLName];
        $bfn = $db->data[BowlerFName];
        $bin = $db->data[BowlerFInitial];
        $aln = $db->data[AssistLName];
        $afn = $db->data[AssistFName];
        $ain = $db->data[AssistFInitial];   
        $run = $db->data[runs];
        $not = $db->data[notout];
        $bwi = $db->data[BWickets];
        $bru = $db->data[BRuns];
        


    
    echo " <tr>\n";
    //echo "  <td align=\"left\" width=\"5%\"><a href=\"http://www.coloradocricket.org/scorecardfull.php?game_id=$gid&ccl_mode=4\">$gid</a></td>\n";
    echo "  <td align=\"left\"><a href=\"http://www.coloradocricket.org/scorecardfull.php?game_id=$gid&ccl_mode=4\">$dat</a></td>\n";
    echo "  <td align=\"left\">$opp</td>\n";
    echo "  <td align=\"left\">$gro</td>\n";
    echo "  <td align=\"left\">";

    // If Assist Last Name is blank, just use first name

    // Make caught and bowled (c & part) align right in the assist column
    if($oid == 5) {

    echo "  $out&nbsp;";

    } else {


    // dont display bowled or caught and bowled
    if($oid == 3 || $oid == 5) {
      echo "";
    } else {
      echo "$out ";
    }

    if($aln == "" && $afn == "") {
      echo "";
    } elseif($afn != "" && $aln != "") {
      echo "$ain $aln";
    } else {
      echo "$afn\n";
    }

    }

    // If Bowler Last Name is blank, just use first name


    // display bowled if it goes with the wicket type
    if($oid == '3' || $oid == '4' || $oid == '5' || $oid == '6' || $oid == '7' || $oid == '10') {
      echo " b ";
    } else {
      echo "";
    }

    if($bln == "" && $bfn == "") {
      echo "";
    } elseif($bfn != "" && $bln != "") {
      echo "$bin $bln";
    } else {
      echo "$bfn\n";
    }

    
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"5%\">$bwi-$bru";
    
    echo "  </td>\n";
    echo " </tr>\n";
    
    }
    } else {
    }
    
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Statistics Selector                                                                                                            //
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;SELECT ANALYSIS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    echo "<table width=\"100%\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Statistics</b></td>\n";
    echo "  <td align=\"left\" valign=\"top\"><b>Graphs</b></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=1\">Performance Breakdown by Year</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=2\">Performance Breakdown by Opponent</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=3\">Performance Breakdown by Ground</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=4\">Performance Breakdown by Batting/Bowling Position</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=5\">Performance Breakdown by Innings Number</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";    
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\"><a href=\"playerstats.php?players=$pr&ccl_mode=6\">Career Batting/Bowling - Innings by Innings Progress</a></td>\n";
    echo "  <td align=\"left\" valign=\"top\">&nbsp;</td>\n";
    echo " </tr>\n";
    echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    echo "<p> <a href=\"players.php?players=$pr&ccl_mode=1\">back to $pfn's profile</a></p>\n";
        
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  
}   



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg[db]);


switch($ccl_mode) {
case 0:
    show_full_players_stats($db,$s,$id,$players,$tid);
    break;
case 1:
    show_breakdown_year($db,$s,$id,$players,$tid);
    break;
case 2:
    show_breakdown_opponent($db,$s,$id,$players,$tid);
    break;  
case 3:
    show_breakdown_ground($db,$s,$id,$players,$tid);
    break;  
case 4:
    show_breakdown_batpos($db,$s,$id,$players,$tid);
    break;  
case 5:
    show_breakdown_innno($db,$s,$id,$players,$tid);
    break;  
case 6:
    show_breakdown_batprogress($db,$s,$id,$players,$tid);
    break;  
case 7:
    show_breakdown_bowlprogress($db,$s,$id,$players,$tid);
    break;      
case 8:
    show_graph_batprogress($db,$s,$id,$players,$tid);
    break;  
default:
    show_full_players_stats($db,$s,$id,$players,$tid);
    break;
}





?>
