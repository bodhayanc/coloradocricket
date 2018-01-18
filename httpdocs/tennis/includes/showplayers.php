<?php

//------------------------------------------------------------------------------
// Players v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_players_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM tennisteams")) {
    $db->QueryRow("SELECT * FROM tennisteams WHERE TeamActive=1 ORDER BY TeamName");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Players</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active Tennis League Players</b><br><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the Player database</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // Alpha Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"players.php?letter=A&ccl_mode=3\">A</a>\n";
    echo "<a href=\"players.php?letter=B&ccl_mode=3\">B</a>\n";
    echo "<a href=\"players.php?letter=C&ccl_mode=3\">C</a>\n";
    echo "<a href=\"players.php?letter=D&ccl_mode=3\">D</a>\n";
    echo "<a href=\"players.php?letter=E&ccl_mode=3\">E</a>\n";
    echo "<a href=\"players.php?letter=F&ccl_mode=3\">F</a>\n";
    echo "<a href=\"players.php?letter=G&ccl_mode=3\">G</a>\n";
    echo "<a href=\"players.php?letter=H&ccl_mode=3\">H</a>\n";
    echo "<a href=\"players.php?letter=I&ccl_mode=3\">I</a>\n";
    echo "<a href=\"players.php?letter=J&ccl_mode=3\">J</a>\n";
    echo "<a href=\"players.php?letter=K&ccl_mode=3\">K</a>\n";
    echo "<a href=\"players.php?letter=L&ccl_mode=3\">L</a>\n";
    echo "<a href=\"players.php?letter=M&ccl_mode=3\">M</a>\n";
    echo "<a href=\"players.php?letter=N&ccl_mode=3\">N</a>\n";
    echo "<a href=\"players.php?letter=O&ccl_mode=3\">O</a>\n";
    echo "<a href=\"players.php?letter=P&ccl_mode=3\">P</a>\n";
    echo "<a href=\"players.php?letter=Q&ccl_mode=3\">Q</a>\n";
    echo "<a href=\"players.php?letter=R&ccl_mode=3\">R</a>\n";
    echo "<a href=\"players.php?letter=S&ccl_mode=3\">S</a>\n";
    echo "<a href=\"players.php?letter=T&ccl_mode=3\">T</a>\n";
    echo "<a href=\"players.php?letter=U&ccl_mode=3\">U</a>\n";
    echo "<a href=\"players.php?letter=V&ccl_mode=3\">V</a>\n";
    echo "<a href=\"players.php?letter=W&ccl_mode=3\">W</a>\n";
    echo "<a href=\"players.php?letter=X&ccl_mode=3\">X</a>\n";
    echo "<a href=\"players.php?letter=Y&ccl_mode=3\">Y</a>\n";
    echo "<a href=\"players.php?letter=Z&ccl_mode=3\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // Teams Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data[TeamID]));
        $na = htmlentities(stripslashes($db->data[TeamName]));
        $di = htmlentities(stripslashes($db->data[TeamDirections]));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"100%\"><a href=\"teams.php?teams=$id&ccl_mode=1\">$na</a>&nbsp;\n";
        echo "    </td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";


    // Random Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;5 Random Players</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    $db->QueryRow("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM tennisplayers pl INNER JOIN tennisteams te ON pl.PlayerTeam = te.TeamID WHERE te.TeamActive = 1 AND pl.isactive = 0 ORDER BY Rand() LIMIT 5");
    $db->BagAndTag();
    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        $id = htmlentities(stripslashes($db->data['PlayerID']));
        $pln = htmlentities(stripslashes($db->data['PlayerLName']));
        $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
        $pte = htmlentities(stripslashes($db->data['TeamAbbrev']));

        // output article

            if($r % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"$PHP_SELF?players=$id&ccl_mode=1\">$pln, $pfn</a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";
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
        echo "There are no teams in the database\n";
    }

}


function show_full_players($db,$s,$id,$pr,$tid)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;


    $db->QueryRow("
    SELECT
      pl.*, te.TeamID, te.TeamName, te.TeamAbbrev, te.TeamColour
    FROM
      tennisplayers pl
    INNER JOIN
      tennisteams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerID = $pr
    ");
    $db->BagAndTag();

    $plid = $db->data['PlayerID'];
    $pln = $db->data['PlayerLName'];
    $pfn = $db->data['PlayerFName'];
    $pem = $db->data[PlayerEmail];
    $bor = $db->data[Born];
    $bat = $db->data[BattingStyle];
    $bow = $db->data[BowlingStyle];
    $spr = $db->data[shortprofile];

    $pic = $db->data['picture'];
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
    echo "  <p><a href=\"/index.php\">Home</a> &raquo; <a href=\"/players.php\">Players</a> &raquo; Player Page</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "<b class=\"16px\">$pfn $pln</b><br><br>\n";

    // Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;PLAYER PROFILE</td>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\" align=\"right\">&nbsp;Tennis ID: $plid</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // output story
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"40%\" valign=\"top\">";

    if($pic != "") {
    echo "<img src=\"http://www.coloradocricket.org/uploadphotos/players/$pic\" align=\"center\" border=\"1\"></td>\n";
    } else {
    echo "<img src=\"http://www.coloradocricket.org/uploadphotos/players/HeadNoMan.jpg\" align=\"center\"></td>\n";
    }
    echo "  <td width=\"60%\">";
    if($pln != "") echo "  <b>Name: </b>$pfn $pln<br>\n";
    if($bor != "") echo "  <b>Born: </b>$bor<br>\n";
    if($pem != "") echo "  <b>Email: </b>$pem<br>\n";
    if($cna != "") echo "  <b>Club: </b><a href=\"/clubs.php?clubs=$cid&ccl_mode=1\">$cna</a><br>\n";
    if($tna != "") echo "  <b>Team: </b><a href=\"/teams.php?teams=$tid&ccl_mode=1\">$tna</a><br><br>\n";

    if($bat != "") echo "  <b>Batting Style: </b>$bat<br>\n";
    if($bow != "") echo "  <b>Bowling Style: </b>$bow<br>\n";

    if($spr != "") echo "  <p>$spr..</p>\n";


    echo "  </td>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Statistics Box

    if ($db->Exists("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS, p.PlayerLName, p.PlayerFName FROM tennis_scorecard_batting_details b INNER JOIN tennisplayers p ON b.player_id = p.PlayerID WHERE b.player_id = $pr GROUP BY p.PlayerLName, p.PlayerFName")) {
    $db->QueryRow("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, MAX( b.runs ) AS HS, p.PlayerLName, p.PlayerFName FROM tennis_scorecard_batting_details b INNER JOIN tennisplayers p ON b.player_id = p.PlayerID WHERE b.player_id = $pr GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scinn = $db->data[Matches];
    $scrun = $db->data['runs'];
    $schig = $db->data[HS]; 
    } else {
    }

    if ($db->Exists("SELECT COUNT(how_out) AS Notout FROM tennis_scorecard_batting_details WHERE player_id = $pr AND (how_out = 2 OR how_out = 8)")) {
    $db->QueryRow("SELECT COUNT(how_out) AS Notout FROM tennis_scorecard_batting_details WHERE player_id = $pr AND (how_out = 2 OR how_out = 8)");
    $db->BagAndTag();
    $scnot = $db->data[Notout];
    $outin = $scinn - $scnot;
    
    if($scrun >= 1 && $outin >= 1) {
    $scavg = Number_Format(Round($scrun / $outin, 2),2);
    } else {
    $scavg = 0;
    }
    
    } else {
    }
    
    
    if ($db->Exists("SELECT COUNT(runs) AS Hundred FROM tennis_scorecard_batting_details WHERE player_id = $pr AND runs >= 100")) { 
    $db->QueryRow("SELECT COUNT(runs) AS Hundred FROM tennis_scorecard_batting_details WHERE player_id = $pr AND runs >= 100");
    $db->BagAndTag();
    $schun = $db->data[Hundred];    
    } else {
    }
    
    if ($db->Exists("SELECT COUNT(runs) AS Fifty FROM tennis_scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) ")) { 
    $db->QueryRow("SELECT COUNT(runs) AS Fifty FROM tennis_scorecard_batting_details WHERE player_id = $pr AND (runs BETWEEN 50 AND 99) ");
    $db->BagAndTag();
    $scfif = $db->data[Fifty];      
    } else {
    }
    
    // Get the caught
    
    if ($db->Exists("SELECT COUNT(assist) AS Caught FROM tennis_scorecard_batting_details WHERE assist = $pr AND how_out = 4")) {   
    $db->QueryRow("SELECT COUNT(assist) AS Caught FROM tennis_scorecard_batting_details WHERE assist = $pr AND how_out = 4");
    $db->BagAndTag();
    $scctc = $db->data[Caught];
    } else {
    }
    
    // now add the c&b
    
    if ($db->Exists("SELECT COUNT(bowler) AS CandB FROM tennis_scorecard_batting_details WHERE bowler = $pr AND how_out = 5")) {    
    $db->QueryRow("SELECT COUNT(bowler) AS CandB FROM tennis_scorecard_batting_details WHERE bowler = $pr AND how_out = 5");
    $db->BagAndTag();
    $sccab = $db->data[CandB];
    } else {
    }
    
    $sccat = $scctc + $sccab;

    if ($db->Exists("SELECT COUNT(assist) AS Stumped FROM tennis_scorecard_batting_details WHERE assist = $pr AND how_out = 10")) { 
    $db->QueryRow("SELECT COUNT(assist) AS Stumped FROM tennis_scorecard_batting_details WHERE assist = $pr AND how_out = 10");
    $db->BagAndTag();
    $scstu = $db->data[Stumped];
    } else {
    }
    


    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM tennis_scorecard_bowling_details b INNER JOIN tennisplayers p ON b.player_id = p.PlayerID WHERE b.player_id = $pr GROUP BY p.PlayerLName, p.PlayerFName")) {  
    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM tennis_scorecard_bowling_details b INNER JOIN tennisplayers p ON b.player_id = p.PlayerID WHERE b.player_id = $pr GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $scmai = $db->data[Maidens];
    $scbru = $db->data[BRuns];
    $scwic = $db->data['wickets'];
    $scove = 

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
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fourwickets FROM tennis_scorecard_bowling_details WHERE player_id = $pr AND wickets = 2")) {  
    $db->QueryRow("SELECT COUNT(wickets) AS fourwickets FROM tennis_scorecard_bowling_details WHERE player_id = $pr AND wickets = 2");
    $db->BagAndTag();
    $scbfo = $db->data[fourwickets];
    } else {
    }

    if ($db->Exists("SELECT COUNT(wickets) AS fivewickets FROM tennis_scorecard_bowling_details WHERE player_id = $pr AND wickets >= 3")) { 
    $db->QueryRow("SELECT COUNT(wickets) AS fivewickets FROM tennis_scorecard_bowling_details WHERE player_id = $pr AND wickets >= 3");
    $db->BagAndTag();
    $scbfi = $db->data[fivewickets];
    } else {
    }
    
    if ($db->Exists("SELECT player_id, wickets, runs FROM tennis_scorecard_bowling_details WHERE player_id = $pr ORDER BY wickets DESC, runs ASC LIMIT 1")) {   
    $db->QueryRow("SELECT player_id, wickets, runs FROM tennis_scorecard_bowling_details WHERE player_id = $pr ORDER BY wickets DESC, runs ASC LIMIT 1");
    $db->BagAndTag();
    $scbbw = $db->data['wickets'];
    $scbbr = $db->data['runs'];   
    
    if($scbru >= 1 && $scwic >= 1) {
    $boavg = Round($scbru / $scwic, 2);
    } else {
    $boavg = 0;
    }
    
    if($scbru >= 1 && $scove >= 0.1) {  
    $boeco = Number_Format(Round($scbru / $scove, 2),2);
    } else {
    $boeco = 0;
    }   
    
    } else {
    }
  
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;STATISTICS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo " <tr class=\"trrow1\">\n";
    echo "  <td width=\"100%\">";
    
    if ($db->Exists("SELECT * FROM tennis_scorecard_batting_details WHERE player_id = $pr")) {
    
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">I</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">NO</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Runs</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">HS</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Ave</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">100</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">50</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">Ct</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">St</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">Batting & Fielding</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">$scinn</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">$scnot</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">$schig</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">$scavg</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">$schun</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">$scfif</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">$sccat</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">$scstu</td>\n";    
    echo " </tr>\n";
    echo "</table><br>\n";
    
    } else {
    
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">I</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">NO</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Runs</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">HS</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Ave</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">100</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">50</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">Ct</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">St</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">Batting & Fielding</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">0</td>\n"; 
    echo " </tr>\n";
    echo "</table><br>\n";
    
    }
    
    if ($db->Exists("SELECT * FROM tennis_scorecard_bowling_details WHERE player_id = $pr")) {
    
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">O</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">M</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">R</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">W</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Ave</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">BBI</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">2w</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">3w</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Eco</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">Bowling</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">$scove</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">$scmai</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">$scbru</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">$scwic</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">$boavg</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">$scbbw-$scbbr</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"4%\">$scbfo</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"4%\">$scbfi</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">$boeco</td>\n";   
    echo " </tr>\n";    
    echo "</table>\n";
    
    } else {
    
    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">&nbsp;</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">O</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">M</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">R</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">W</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Ave</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">BBI</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">2w</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"6%\">3w</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">Eco</td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"scorecard\" align=\"left\" width=\"35%\">Bowling</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"7%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"8%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"5%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"4%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"4%\">0</td>\n";
    echo "  <td class=\"scorecard\" align=\"right\" width=\"10%\">0</td>\n";    
    echo " </tr>\n";    
    echo "</table>\n";
    
    }
    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    // Action Photo Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;ACTION PHOTO</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"100%\">";

    if($pic1 != "") {
    echo "<div align=\"center\"><img src=\"http://www.coloradocricket.org/uploadphotos/players/action/$pic1\" border=\"1\"></div></td>\n";
    } else {
    echo "No action photo available at this time.</td>\n";
    }
    echo "  </td>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // output article
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    // News Features Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;Recent News featuring $pfn $pln</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    // process features
        if ($db->Exists("SELECT * FROM tennisnews WHERE IsFeature != 1 AND article LIKE '%$pfn%' OR article LIKE '%$pln%' ORDER BY id DESC LIMIT 5")) {
        $db->Query("SELECT * FROM tennisnews WHERE IsFeature != 1 AND article LIKE '%$pfn%' OR article LIKE '%$pln%' ORDER BY id DESC LIMIT 5");

        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $t = $db->data['title'];
            $au = $db->data['author'];
            $id = $db->data['id'];
            $pr = $db->data['id'];
            $date = sqldate_to_string($db->data[added]);

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"100%\"><a href=\"news.php?news=$pr&ccl_mode=1\">$t</a>\n";
        if($db->data['picture'] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";

        }

        } else {

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\">There are currently no news articles featuring $pfn $pln</td>\n";
        echo "</tr>\n";


        }


            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            // finish off
            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table><br>\n";


    // output link back
    $sitevar = "/players.php?players=$pr&ccl_mode=1";
    echo "<p><a href=\"$PHP_SELF\">&laquo; back to players listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

function search_players($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

         if (!$db->Exists("SELECT * FROM tennisplayers")) {
                 echo "<p>There are currently no players.</p>\n";
                 return;
         }

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Search Players</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">League Players Containing \"$search\"</b><br><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the player database</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // Alpha Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"players.php?letter=A&ccl_mode=3\">A</a>\n";
    echo "<a href=\"players.php?letter=B&ccl_mode=3\">B</a>\n";
    echo "<a href=\"players.php?letter=C&ccl_mode=3\">C</a>\n";
    echo "<a href=\"players.php?letter=D&ccl_mode=3\">D</a>\n";
    echo "<a href=\"players.php?letter=E&ccl_mode=3\">E</a>\n";
    echo "<a href=\"players.php?letter=F&ccl_mode=3\">F</a>\n";
    echo "<a href=\"players.php?letter=G&ccl_mode=3\">G</a>\n";
    echo "<a href=\"players.php?letter=H&ccl_mode=3\">H</a>\n";
    echo "<a href=\"players.php?letter=I&ccl_mode=3\">I</a>\n";
    echo "<a href=\"players.php?letter=J&ccl_mode=3\">J</a>\n";
    echo "<a href=\"players.php?letter=K&ccl_mode=3\">K</a>\n";
    echo "<a href=\"players.php?letter=L&ccl_mode=3\">L</a>\n";
    echo "<a href=\"players.php?letter=M&ccl_mode=3\">M</a>\n";
    echo "<a href=\"players.php?letter=N&ccl_mode=3\">N</a>\n";
    echo "<a href=\"players.php?letter=O&ccl_mode=3\">O</a>\n";
    echo "<a href=\"players.php?letter=P&ccl_mode=3\">P</a>\n";
    echo "<a href=\"players.php?letter=Q&ccl_mode=3\">Q</a>\n";
    echo "<a href=\"players.php?letter=R&ccl_mode=3\">R</a>\n";
    echo "<a href=\"players.php?letter=S&ccl_mode=3\">S</a>\n";
    echo "<a href=\"players.php?letter=T&ccl_mode=3\">T</a>\n";
    echo "<a href=\"players.php?letter=U&ccl_mode=3\">U</a>\n";
    echo "<a href=\"players.php?letter=V&ccl_mode=3\">V</a>\n";
    echo "<a href=\"players.php?letter=W&ccl_mode=3\">W</a>\n";
    echo "<a href=\"players.php?letter=X&ccl_mode=3\">X</a>\n";
    echo "<a href=\"players.php?letter=Y&ccl_mode=3\">Y</a>\n";
    echo "<a href=\"players.php?letter=Z&ccl_mode=3\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";


    if ($search != "")
    {

        $contains = "PlayerLName LIKE '%{$search}%' OR PlayerFName LIKE '%{$search}%'";

        $db->Query("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM tennisplayers pl INNER JOIN tennisteams te ON pl.PlayerTeam = te.TeamID WHERE $contains ORDER BY pl.PlayerLName");
            if ($db->rows)
            {

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Players Matching \"$search\"</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $id = htmlentities(stripslashes($db->data['PlayerID']));
            $pln = htmlentities(stripslashes($db->data['PlayerLName']));
            $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
            $pte = htmlentities(stripslashes($db->data['TeamAbbrev']));

            if($i % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"$PHP_SELF?players=$id&ccl_mode=1\">$pln, $pfn</a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";

            }

        echo "</table>\n";

    
        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";
        
    // Teams Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    $db->QueryRow("SELECT * FROM tennisteams WHERE TeamActive=1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data[TeamID]));
        $na = htmlentities(stripslashes($db->data[TeamName]));
        $di = htmlentities(stripslashes($db->data[TeamDirections]));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"100%\"><a href=\"teams.php?teams=$id&ccl_mode=1\">$na</a>&nbsp;\n";
        echo "    </td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";      
        
        }
        else
        {

        // Search Box

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">Search the player database</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

        echo "<p>There are no players matching that query in any way.</p>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

    // Teams Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    
    $db->QueryRow("SELECT * FROM tennisteams WHERE TeamActive=1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data[TeamID]));
        $na = htmlentities(stripslashes($db->data[TeamName]));
        $di = htmlentities(stripslashes($db->data[TeamDirections]));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"100%\"><a href=\"teams.php?teams=$id&ccl_mode=1\">$na</a>&nbsp;\n";
        echo "    </td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
    
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        
        }
        }


 }



function show_alpha_listing($db,$s,$id,$pr,$letter)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Players</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active Tennis Leaguers Beginning With \"$letter\"</b><br><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the Player database</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"players.php?letter=A&ccl_mode=3\">A</a>\n";
    echo "<a href=\"players.php?letter=B&ccl_mode=3\">B</a>\n";
    echo "<a href=\"players.php?letter=C&ccl_mode=3\">C</a>\n";
    echo "<a href=\"players.php?letter=D&ccl_mode=3\">D</a>\n";
    echo "<a href=\"players.php?letter=E&ccl_mode=3\">E</a>\n";
    echo "<a href=\"players.php?letter=F&ccl_mode=3\">F</a>\n";
    echo "<a href=\"players.php?letter=G&ccl_mode=3\">G</a>\n";
    echo "<a href=\"players.php?letter=H&ccl_mode=3\">H</a>\n";
    echo "<a href=\"players.php?letter=I&ccl_mode=3\">I</a>\n";
    echo "<a href=\"players.php?letter=J&ccl_mode=3\">J</a>\n";
    echo "<a href=\"players.php?letter=K&ccl_mode=3\">K</a>\n";
    echo "<a href=\"players.php?letter=L&ccl_mode=3\">L</a>\n";
    echo "<a href=\"players.php?letter=M&ccl_mode=3\">M</a>\n";
    echo "<a href=\"players.php?letter=N&ccl_mode=3\">N</a>\n";
    echo "<a href=\"players.php?letter=O&ccl_mode=3\">O</a>\n";
    echo "<a href=\"players.php?letter=P&ccl_mode=3\">P</a>\n";
    echo "<a href=\"players.php?letter=Q&ccl_mode=3\">Q</a>\n";
    echo "<a href=\"players.php?letter=R&ccl_mode=3\">R</a>\n";
    echo "<a href=\"players.php?letter=S&ccl_mode=3\">S</a>\n";
    echo "<a href=\"players.php?letter=T&ccl_mode=3\">T</a>\n";
    echo "<a href=\"players.php?letter=U&ccl_mode=3\">U</a>\n";
    echo "<a href=\"players.php?letter=V&ccl_mode=3\">V</a>\n";
    echo "<a href=\"players.php?letter=W&ccl_mode=3\">W</a>\n";
    echo "<a href=\"players.php?letter=X&ccl_mode=3\">X</a>\n";
    echo "<a href=\"players.php?letter=Y&ccl_mode=3\">Y</a>\n";
    echo "<a href=\"players.php?letter=Z&ccl_mode=3\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";


    // Alpha Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Players Last Names beginning with \"$letter\"</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    if ($db->Exists("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM tennisplayers pl INNER JOIN tennisteams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' AND te.TeamActive = 1 AND pl.isactive = 0 ORDER BY pl.PlayerLName")) {
    $db->QueryRow("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM tennisplayers pl INNER JOIN tennisteams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' AND te.TeamActive = 1 AND pl.isactive = 0 ORDER BY pl.PlayerLName");
    $db->BagAndTag();
    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        $id = htmlentities(stripslashes($db->data['PlayerID']));
        $pln = htmlentities(stripslashes($db->data['PlayerLName']));
        $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
        $pte = htmlentities(stripslashes($db->data['TeamAbbrev']));

        // output article

            if($r % 2) {
              echo "<tr class=\"trrow1\">\n";
            } else {
              echo "<tr class=\"trrow2\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"$PHP_SELF?players=$id&ccl_mode=1\">$pln, $pfn</a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture_action.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";
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
        else
        {
        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\">\n";
        echo "<p>There are no players with a last name beginning with \"$letter\".</p>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";      
        
        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";      
    }       
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_players_listing($db,$s,$id,$players);
    break;
case 1:
    show_full_players($db,$s,$id,$players,$tid);
    break;
case 2:
    search_players($db,$search);
    break;
case 3:
    show_alpha_listing($db,$s,$id,$players,$letter);
    break;
default:
    show_players_listing($db,$s,$id,$players);
    break;
}


?>
