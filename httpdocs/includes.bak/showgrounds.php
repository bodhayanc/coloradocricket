<?php

//------------------------------------------------------------------------------
// Colorado Cricket Grounds  v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_grounds_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM grounds")) {
    $db->QueryRow("SELECT * FROM grounds WHERE GroundActive=1 AND LeagueID = 1 ORDER BY GroundID");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Grounds</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">League Grounds</b><br><br>\n";

    // Grounds Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;GROUNDS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";

        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['GroundID']));
        $na = htmlentities(stripslashes($db->data['GroundName']));
        $di = htmlentities(stripslashes($db->data['GroundDirections']));
		$gl = htmlentities(stripslashes($db->data['GroundLoc']));  // Added this 10-Aug-2015 11:10pm

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?grounds=$id&ccl_mode=1\">$na - $gl</a>";  // Added $gl  10-Aug-2015 11:11pm
        if ($db->data['picture'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">";
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
        echo "There are no grounds in the database\n";
    }
}


function show_full_grounds($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->QueryRow("SELECT * FROM grounds WHERE GroundID=$pr");
    $db->BagAndTag();

    $id = $db->data['GroundID'];
    $na = $db->data['GroundName'];
    $gl = $db->data['GroundLoc'];
    $di = $db->data['GroundDirections'];
    $zi = $db->data['GroundZip'];
    $de = $db->data['description'];
    $pa = $db->data['parking'];
    $cp = $db->data['coveredparking'];
    $sh = $db->data['shelter'];
    $ha = $db->data['handicapped'];
    $ss = $db->data['stadiumseating'];
    $rr = $db->data['restrooms'];
    $cs = $db->data['conveniencestore'];
    $dw = $db->data['drinkingwater'];
    $pt = $db->data['publictransport'];
    $pi = $db->data['picture'];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/grounds.php\">Grounds</a> &raquo; <font class=\"10px\">$na - $gl</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$na Cricket Ground</b><br><br>\n";

    // Ground Description Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">";
        echo "    &nbsp;DESCRIPTION\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";

    if($de != "") {
    if($pi != "") {
    echo "    <td><a href=\"/uploadphotos/grounds/$pi\" onClick=\"return enlarge('/uploadphotos/grounds/$pi',event)\"><img src=\"/uploadphotos/grounds/$pi\" border=\"0\" width=\"150\" align=\"right\"></a><p>$gl</p><p>$de</p></td>\n";
    } else {
    echo "    <td><p>$gl</p><p>$de</p></td>\n";
    }
    } else {
    echo "    <td><p>No description available at this time.</p></td>\n";
    }

    echo "  </tr>\n";
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Ground Statistics Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">";
        echo "    &nbsp;STATISTICS\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // Get the First Game Played There
    if ($db->Exists("SELECT gr.GroundID, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN grounds gr ON ga.ground_id = gr.GroundID LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE GroundID=$pr ORDER BY FirstGame LIMIT 1")) {
    $db->QueryRow("SELECT gr.GroundID, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN grounds gr ON ga.ground_id = gr.GroundID LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE GroundID=$pr ORDER BY FirstGame LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data['game_id'];
    $ffg = $db->data['FirstGame'];
    $ft1 = $db->data['HomeTeam'];
    $ft2 = $db->data['AwayTeam'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>First Game</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">$ft1 vs $ft2 - $ffg </td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
    echo "  </tr>\n";
    
    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>First Game</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }

    // Get the Last Game Played There
    if ($db->Exists("SELECT gr.GroundID, ga.game_date as LastGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN grounds gr ON ga.ground_id = gr.GroundID LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE GroundID=$pr ORDER BY LastGame DESC LIMIT 1")) {  
    $db->QueryRow("SELECT gr.GroundID, ga.game_date as LastGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN grounds gr ON ga.ground_id = gr.GroundID LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE GroundID=$pr ORDER BY LastGame DESC LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data['game_id'];
    $flg = $db->data['LastGame'];
    $ft1 = $db->data['HomeTeam'];
    $ft2 = $db->data['AwayTeam'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Last Game</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">$ft1 vs $ft2 - $flg</td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";       
    echo "  </tr>\n";

    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Last Game</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }
    
    // Get the Number Of Matches Played
    if ($db->Exists("SELECT COUNT(game_id) AS MatchesPlayed FROM scorecard_game_details WHERE ground_id=$pr")) {    
    $db->QueryRow("SELECT COUNT(game_id) AS MatchesPlayed FROM scorecard_game_details WHERE ground_id=$pr");
    $db->BagAndTag();
    
    $cou = $db->data['MatchesPlayed'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\"><b>Matches played</b></td>\n";
    echo "    <td width=\"60%\">$cou</td>\n";
    echo "  </tr>\n";   
    
    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Matches Played</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }
    
    // Get the Highest Score
    if ($db->Exists("SELECT ga.ground_id, ga.game_id, ga.game_date, ba.runs, ba.notout, ba.player_id, pl.PlayerLName, pl.PlayerFName FROM scorecard_game_details ga INNER JOIN scorecard_batting_details ba ON ba.game_id = ga.game_id INNER JOIN players pl ON ba.player_id = pl.PlayerID WHERE ga.ground_id=$pr ORDER BY ba.runs DESC LIMIT 1")) {
    $db->QueryRow("SELECT ga.ground_id, ga.game_id, ga.game_date, ba.runs, ba.notout, ba.player_id, pl.PlayerLName, pl.PlayerFName FROM scorecard_game_details ga INNER JOIN scorecard_batting_details ba ON ba.game_id = ga.game_id INNER JOIN players pl ON ba.player_id = pl.PlayerID WHERE ga.ground_id=$pr ORDER BY ba.runs DESC LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data['game_id'];
    $run = $db->data['runs'];
    $pfn = $db->data['PlayerFName'];
    $pln = $db->data['PlayerLName'];
    $not = $db->data['notout'];
    $dat = $db->data['game_date'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Highest Individual Score</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">$run";
    if($not == "1") echo "*";
    echo "    - $pfn $pln - $dat";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
    echo "  </tr>\n";

    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Highest Individual Score</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }
    
    // Get the Best Wickets
    if ($db->Exists("SELECT g.game_id, g.ground_id, g.game_date, b.player_id, b.runs AS BRuns, b.wickets AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN grounds r ON g.ground_id = r.GroundID WHERE g.ground_id=$pr ORDER BY Wickets DESC, Runs ASC LIMIT 1")) {
    $db->QueryRow("SELECT g.game_id, g.ground_id, g.game_date, b.player_id, b.runs AS BRuns, b.wickets AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id INNER JOIN grounds r ON g.ground_id = r.GroundID WHERE g.ground_id=$pr ORDER BY Wickets DESC, Runs ASC LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data['game_id'];
    $run = $db->data['BRuns'];
    $pfn = $db->data['PlayerFName'];
    $pln = $db->data['PlayerLName'];
    $wic = $db->data['wickets'];
    $dat = $db->data['game_date'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Best Bowling</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">$wic/$run - $pfn $pln - $dat</td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
    echo "  </tr>\n";

    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Best Bowling</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }
    
    // Highest Team Score
    if ($db->Exists("SELECT ga.game_id, ga.ground_id, ga.game_date, tt.team, tt.total, tt.wickets, te.TeamAbbrev FROM scorecard_total_details tt INNER JOIN teams te ON tt.team=te.TeamID INNER JOIN scorecard_game_details ga ON ga.game_id=tt.game_id WHERE ga.ground_id=$pr ORDER BY tt.total DESC LIMIT 1")) {
    $db->QueryRow("SELECT ga.game_id, ga.ground_id, ga.game_date, tt.team, tt.total, tt.wickets, te.TeamAbbrev FROM scorecard_total_details tt INNER JOIN teams te ON tt.team=te.TeamID INNER JOIN scorecard_game_details ga ON ga.game_id=tt.game_id WHERE ga.ground_id=$pr ORDER BY tt.total DESC LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data['game_id'];
    $run = $db->data['total'];
    $tab = $db->data['TeamAbbrev'];
    $wic = $db->data['wickets'];
    $dat = $db->data['game_date'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Highest Team Score</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">$wic/$run - $tab - $dat</td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
    echo "  </tr>\n";

    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Highest Team Score</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }
    
    // Lowest Team Score
    if ($db->Exists("SELECT ga.game_id, ga.ground_id, ga.game_date, tt.team, tt.total, tt.wickets, te.TeamAbbrev FROM scorecard_total_details tt INNER JOIN teams te ON tt.team=te.TeamID INNER JOIN scorecard_game_details ga ON ga.game_id=tt.game_id WHERE ga.ground_id=$pr AND ga.forfeit=0 AND ga.cancelled=0 ORDER BY tt.total LIMIT 1")) {
    $db->QueryRow("SELECT ga.game_id, ga.ground_id, ga.game_date, tt.team, tt.total, tt.wickets, te.TeamAbbrev FROM scorecard_total_details tt INNER JOIN teams te ON tt.team=te.TeamID INNER JOIN scorecard_game_details ga ON ga.game_id=tt.game_id WHERE ga.ground_id=$pr AND ga.forfeit=0 AND ga.cancelled=0 ORDER BY tt.total LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data['game_id'];
    $run = $db->data['total'];
    $tab = $db->data['TeamAbbrev'];
    $wic = $db->data['wickets'];
    $dat = $db->data['game_date'];
    
    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Lowest Team Score</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">$wic/$run - $tab - $dat</td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
    echo "  </tr>\n";   

    } else {

    echo "  <tr>\n";
    echo "    <td width=\"40%\" align=\"left\"><b>Lowest Team Score</b></td>\n";
    echo "    <td width=\"45%\" align=\"left\">n/a </td>\n";
    echo "    <td width=\"15%\" align=\"right\">&nbsp;</td>\n"; 
    echo "  </tr>\n";
    
    }
    
    // More detailed statistics
    
    echo "  <tr>\n";
    echo "    <td width=\"100%\" colspan=\"3\" align=\"left\"><a href=\"$PHP_SELF?grounds=$id&ccl_mode=2\">All Matches Played</a></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td width=\"100%\" colspan=\"3\" align=\"left\"><a href=\"$PHP_SELF?grounds=$id&sort=Runs&sort2=Average&ccl_mode=3\">Highest Run Scorers</a></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "    <td width=\"100%\" colspan=\"3\" align=\"left\"><a href=\"$PHP_SELF?grounds=$id&sort=Average&sort2=Runs&ccl_mode=3\">Highest Batting Averages</a></td>\n";
    echo "  </tr>\n";
    
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";
    
    
    
    // Ground Amenities Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;AMENITIES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    echo "  <tr class=\"trrow1\">\n";
    if($pa != "") {
    echo "    <td width=\"50%\">Parking</td>\n";
    echo "    <td width=\"50%\">$pa</td>\n";
    } else {
    echo "    <td width=\"50%\">Parking</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($cp != "") {
    echo "    <td width=\"50%\">Covered Parking</td>\n";
    echo "    <td width=\"50%\">$cp</td>\n";
    } else {
    echo "    <td width=\"50%\">Covered Parking</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($sh != "") {
    echo "    <td width=\"50%\">Shelter</td>\n";
    echo "    <td width=\"50%\">$sh</td>\n";
    } else {
    echo "    <td width=\"50%\">Shelter</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($ha != "") {
    echo "    <td width=\"50%\">Handicapped Accessible</td>\n";
    echo "    <td width=\"50%\">$ha</td>\n";
    } else {
    echo "    <td width=\"50%\">Handicapped Accessible</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($ss != "") {
    echo "    <td width=\"50%\">Stadium Seating</td>\n";
    echo "    <td width=\"50%\">$ss</td>\n";
    } else {
    echo "    <td width=\"50%\">Stadium Seating</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($rr != "") {
    echo "    <td width=\"50%\">Rest Rooms</td>\n";
    echo "    <td width=\"50%\">$rr</td>\n";
    } else {
    echo "    <td width=\"50%\">Rest Rooms</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($cs != "") {
    echo "    <td width=\"50%\">Convenience Store</td>\n";
    echo "    <td width=\"50%\">$cs</td>\n";
    } else {
    echo "    <td width=\"50%\">Convenience Store</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($dw != "") {
    echo "    <td width=\"50%\">Drinking Water</td>\n";
    echo "    <td width=\"50%\">$dw</td>\n";
    } else {
    echo "    <td width=\"50%\">Drinking Water</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($pt != "") {
    echo "    <td width=\"50%\">Public Transport Accessible</td>\n";
    echo "    <td width=\"50%\">$pt</td>\n";
    } else {
    echo "    <td width=\"50%\">Public Transport Accessible</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";


    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Ground Directions Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;DIRECTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td><p>$di</p></td>\n";

    echo "  </tr>\n";
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Ground Weather Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;WEATHER</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td><p>";
    if ($db->data['GroundZip'] != "0") {
    echo "    <script src='http://voap.weather.com/weather/oap/$zi?template=GENXH&par=1004982138&unit=0&key=dd43509d7e444c1c1f5c322975a6adaf'></script>\n";
    } else {
    echo "";
    }
    echo "    </p></td>\n";

    echo "  </tr>\n";
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";


    // output link back
    $sitevar = "/grounds.php?grounds=$pr&ccl_mode=1";
    echo "<p><a href=\"$PHP_SELF\">&laquo; back to grounds listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}


function show_grounds_games($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->QueryRow("SELECT * FROM grounds WHERE GroundID=$pr");
    $db->BagAndTag();

    $id = $db->data['GroundID'];
    $na = $db->data['GroundName'];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/grounds.php\">Grounds</a> &raquo; <font class=\"10px\">Matches</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Matches at $na</b><br><br>\n";

    // Grounds Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL GAMES PLAYED</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";

        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

        echo "<tr class=\"colhead\">\n";
        echo "  <td align=\"left\"><b>SEASON</b></td>\n";
        echo "  <td align=\"left\"><b>DATE</b></td>\n";
        echo "  <td align=\"left\"><b>GAME</b></td>\n";
        echo "  <td align=\"left\"><b>RESULT</b></td>\n";
        echo "</tr>\n";


        if (!$db->Exists("SELECT * FROM scorecard_game_details WHERE ground_id=$pr AND isactive=0")) {
            echo "<tr class=\"trrow2\">\n";
            echo "  <td align=\"left\"<p>No scorecards for this ground.</p></td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "  <td align=\"left\">&nbsp;</td>\n";
            echo "</tr>\n";
            echo "</table>\n";
            echo "<table border=\"0\" width=\"100%\" cellpadding=\"4\" cellspacing=\"0\">\n";
        } else {
            $db->Query("
            SELECT
              s.*,
              n.SeasonName, 
              a.TeamID AS 'awayid', a.TeamName AS AwayName, a.TeamAbbrev AS 'awayabbrev',
              h.TeamID AS 'homeid', h.TeamName AS HomeName, h.TeamAbbrev AS 'homeabbrev'
            FROM
              scorecard_game_details s
            INNER JOIN
              teams a ON s.awayteam = a.TeamID
            INNER JOIN
              teams h ON s.hometeam = h.TeamID
            INNER JOIN
              seasons n ON s.season=n.SeasonID
            WHERE
              s.ground_id=$pr AND s.isactive=0
            ORDER BY
              s.game_date, s.game_id
            ");

            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $t1 = $db->data['homeabbrev'];
                $t2 = $db->data['awayabbrev'];
                $um = $db->data['umpireabbrev'];
                $t1id = $db->data['homeid'];
                $t2id = $db->data['awayid'];
                $umid = $db->data['umpireid'];
                $d = sqldate_to_string($db->data['game_date']);
                $sc =  $db->data['scorecard'];
                $re = $db->data['result'];
                $id = $db->data['game_id'];
                $wk = $db->data['week'];
                $fo = $db->data['forfeit'];
                $ca = $db->data['cancelled'];
                $sn = $db->data['SeasonName'];
                $si = $db->data['season'];

            if($x % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/scorecard.php?schedule=$si&ccl_mode=1\">$sn</a></td>\n";
                echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
                echo "  <td align=\"left\" class=\"9px\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\">$t2</a> vs <a href=\"/teams.php?teams=$t1id&ccl_mode=1\">$t1</a></td>\n";
                
                if($fo == "0" && $ca == "0") {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                } else if($fo == "1" && $ca == "0") {
                  echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
                } else if($ca == "1" && $fo = "1") {
                  echo "  <td align=\"left\" class=\"9px\">Game cancelled</td>\n";
                } else {
                  echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
                }


                echo "</tr>\n";
                }
        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        
        echo "<p>&laquo; <a href=\"$PHP_SELF?grounds=$pr&ccl_mode=1\">back to $na page</a></p>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
}


function show_grounds_mostruns($db,$s,$id,$pr,$sort,$sort2)
{
        global $dbcfg, $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->Query("SELECT * FROM grounds ORDER BY GroundID");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $grounds[$db->data['GroundID']] = $db->data['GroundName'];
    }

    $db->Query("SELECT * FROM teams ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
                    
    if(!$db->Exists("SELECT g.season, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / (COUNT( s.player_id ) - SUM( s.notout )) AS Average, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id GROUP BY s.player_id")) {
        
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";    
    
    echo "<p>There are no batting statistics in the database for the ground.</p>\n";
    
    echo "<p>&laquo; <a href=\"javascript:history.back()\">back to selection</a></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

        } else {


    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/grounds.php\">Grounds</a> &raquo; <font class=\"10px\">{$grounds[$pr]} statistics</font></p>\n";
    
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    $db->QueryRow("
    SELECT
      MIN(game_date) AS earlydate
    FROM
      scorecard_game_details
    ");
    $db->BagAndTag();
    
    $d = sqldate_to_string($db->data['earlydate']);   
    

      if($sort == "Average") echo "<b class=\"16px\">{$grounds[$pr]} Career Batting - Highest Averages</b><br>From <b>$d</b> to the present.<br>Minimum 3 innings and 50 runs<br><br>\n";
      if($sort == "Runs") echo "<b class=\"16px\">{$grounds[$pr]} Career Batting - Most Runs</b><br>From <b>$d</b> to the present.<br>Minimum 3 innings and 50 runs<br><br>\n"; 

    

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;BATTING STATISTICS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";   
    
// begin batting statistics

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"2%\">&nbsp;</td>\n";
    echo "  <td align=\"left\" width=\"30%\"><b>NAME</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>I</b></td>\n";
    echo "  <td align=\"right\" width=\"6%\"><b>NO</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"right\" width=\"7%\"><b>HS</b></td>\n";
    echo "  <td align=\"right\" width=\"10%\"><b>Ave</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>100</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>50</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>Ct</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>St</b></td>\n";
    echo "  <td align=\"right\" width=\"9%\"><b>TEAM</b></td>\n";
    echo " </tr>\n";

    $db->Query("SELECT g.season, g.ground_id, t.TeamAbbrev, COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, SUM( s.notout ) AS Notouts, COUNT( s.player_id ) - SUM( s.notout ) AS Innings, SUM( s.runs ) / (COUNT( s.player_id ) - SUM( s.notout )) AS Average, s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName FROM scorecard_batting_details s INNER JOIN players p ON s.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON s.game_id = g.game_id INNER JOIN teams t ON p.PlayerTeam = t.TeamID WHERE g.ground_id = $pr GROUP BY s.player_id HAVING (COUNT( s.player_id )) >=3 AND (SUM( s.runs )) >=50 ORDER BY $sort DESC, $sort2 DESC");

    $db->BagAndTag();

    // instantiate new db class
    $subdb =& new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $subdb->SelectDB($dbcfg['db']);

    for ($r=0; $r<$db->rows; $r++) {
    $db->GetRow($r);            

    $playerid = $db->data['player_id'];
    $init = $db->data['PlayerInitial'];
    $fname = $db->data['PlayerFName'];
    $lname = $db->data['PlayerLName'];    
    $scinn = $db->data['Matches'];
    $scrun = $db->data['runs'];
    //$schig = $db->data['HS'];   
    $teama = $db->data['TeamAbbrev'];

    $innings = $db->data['Innings'];

    if($db->data['Notouts'] != 0) {
      $notouts = $db->data['Notouts'];
    } else {
      $notouts = "-";
    }

    if($db->data['Average'] != "") {
      $average = $db->data['Average'];
    } else {
      $average = "-";
    }

    // Get Hundreds
    
    $subdb->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $playerid  AND g.ground_id=$pr AND b.runs >= 100");
    
    if($subdb->data['Hundred'] != "0") {
      $schun = $subdb->data['Hundred'];   
    } else {
      $schun = "-";
    }

    // Get Fifties
    
    $subdb->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $playerid  AND g.ground_id=$pr AND (b.runs BETWEEN 50 AND 99) ");     
    
    if($subdb->data['Fifty'] != "0") {
    $scfif = $subdb->data['Fifty'];   
    } else {
    $scfif = "-";
    }

    // Get Catches
    
    $subdb->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $playerid  AND g.ground_id=$pr AND b.how_out = 4");

      $scctc = $subdb->data['Caught'];    

    // Get Caught and Bowleds

    $subdb->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.bowler = $playerid  AND g.ground_id=$pr AND b.how_out = 5");

      $sccab = $subdb->data['CandB']; 

    if($scctc + $sccab != "0") {
      $sccat = $scctc + $sccab;
    } else {
      $sccat = "-";
    }
    
    // Get Stumpings

    $subdb->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $playerid  AND g.ground_id=$pr AND b.how_out = 10");

    if($subdb->data['Stumped'] != "0") {
      $scstu = $subdb->data['Stumped'];   
    } else {
      $scstu = "-";
    }
    
    // Get Highest Score

    $subdb->QueryRow("SELECT b.notout, MAX(b.runs) AS HS FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $playerid AND g.ground_id=$pr GROUP BY b.notout ORDER BY HS DESC LIMIT 1");      
    
    $schig = $subdb->data['HS'];
    $scnot = $subdb->data['notout'];

    if($r % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }
    
    echo "  <td align=\"left\" width=\"2%\">";
    echo ($r+1);
    echo "  </td>\n";
    echo "  <td align=\"left\" width=\"30%\"><a href=\"players.php?players=$playerid&ccl_mode=1\" class=\"statistics\">";

    if($lname == "" && $fname == "") {
      echo "";
    } elseif($fname != "" && $lname != "") {
      echo "$init $lname";
    } else {
      echo "$fname\n";
    }           

    echo "  </a></td>\n";
    echo "  <td align=\"right\" width=\"6%\">$scinn</td>\n";
    echo "  <td align=\"right\" width=\"6%\">$notouts</td>\n";
    echo "  <td align=\"right\" width=\"10%\">$scrun</td>\n";
    echo "  <td align=\"right\" width=\"7%\">$schig";
    if($scnot == "1") echo "*";
    echo "  </td>\n";
    echo "  <td align=\"right\" width=\"10%\">$average</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$schun</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scfif</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$sccat</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$scstu</td>\n";
    echo "  <td align=\"right\" width=\"9%\">$teama</td>\n";
    echo " </tr>\n";

    }

    echo "</table>\n";

    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";


// begin season selection

    if($option == "byseason") {

    if ($db->Exists("SELECT * FROM seasons")) {
    $db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;SEASON SELECTOR</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellpadding=\"10\" cellspacing=\"1\" border=\"0\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

    echo "<p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
    echo "  <option>select another season</option>\n";
    echo "  <option>=====================</option>\n";
    for ($x=0; $x<$db->rows; $x++) {
        $db->GetRow($x);
        $db->BagAndTag();
        $id = $db->data['SeasonID'];
        $seasonname = $db->data['SeasonName'];
        // output article

      if($sort == "Average") echo "<option value=\"$PHP_SELF?option=byseason&statistics=$seasonname&sort=Average&ccl_mode=3\">" . $db->data['SeasonName'] . "</option>\n";
      if($sort == "Runs") echo "<option value=\"$PHP_SELF?option=byseason&statistics=$seasonname&sort=Runs&ccl_mode=3\">" . $db->data['SeasonName'] . "</option>\n";

    
    }
    
    echo "</select></p>\n";
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

}

} else {
}


    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

        }
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_grounds_listing($db,$s,$id,$grounds);
    break;
case 1:
    show_full_grounds($db,$s,$id,$grounds);
    break;
case 2:
    show_grounds_games($db,$s,$id,$grounds);
    break;
case 3:
    show_grounds_mostruns($db,$s,$id,$grounds,$sort,$sort2);
    break;  
default:
    show_grounds_listing($db,$s,$id,$grounds);
    break;
}


?>
