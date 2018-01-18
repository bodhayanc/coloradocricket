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

    if ($db->Exists("SELECT * FROM teams")) {
//    $db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 ORDER BY TeamName");
       $db->QueryRow("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamActive DESC, TeamName ASC");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Players</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active League Players</b><br><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH PLAYERS</td>\n";
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
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">ALPHABETICAL PLAYER LISTING</td>\n";
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
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;TEAMS LIST</td>\n";
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
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
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
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;RANDOM PLAYERS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    $db->QueryRow("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE te.TeamActive = 1 AND te.LeagueID = 1 AND pl.isactive = 0 ORDER BY Rand() LIMIT 5");
    $db->BagAndTag();
    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        $id = htmlentities(stripslashes($db->data[PlayerID]));
        $pln = htmlentities(stripslashes($db->data[PlayerLName]));
        $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
        $pte = htmlentities(stripslashes($db->data[TeamAbbrev]));

        // output article

            if($r % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"$PHP_SELF?players=$id&ccl_mode=1\">$pln, $pfn</a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data[picture] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture_action.gif\">\n";
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

	$db_bat = $db;
	$db_bowl = $db;
	
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
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/players.php\">Players</a> &raquo; Player Page</p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "<b class=\"16px\">$pfn $pln</b><br><br>\n";

    // Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
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

    echo "<table width=\"200\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"right\">\n";
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
    if($pla != "") echo "  <b>Abbrev: </b>$pfn $pla<br>\n";
    if($bor != "") echo "  <b>Born: </b>$bor<br>\n";
    if($pem != "") echo "  <b>Email: </b>$pem<br>\n";
    if($tna != "") echo "  <b>Team: </b><a href=\"/teams.php?teams=$tid&ccl_mode=1\">$tna</a><br>\n";
    if($bat != "") echo "  <b>Batting Style: </b>$bat<br>\n";
    if($bow != "") echo "  <b>Bowling Style: </b>$bow<br>\n";   
    
    if($spr != "") echo "  <p>$spr..</p>\n";
    
    echo "";

    echo "<p align=\"left\"><b>Statsguru</b> <a href=\"playerstats.php?players=$pr&ccl_mode=0\">Complete stats</a></p>";    

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

//-------------------------------------------------
// CCL Statistics Box
//-------------------------------------------------
    
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE STATISTICS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" class=\"tablehead\">\n";
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"30%\"><b>Batting & Fielding<b></td>\n";
    echo "  <td align=\"center\" width=\"7%\"><b>I<b></td>\n";
    echo "  <td align=\"center\" width=\"7%\"><b>NO<b></td>\n";
    echo "  <td align=\"center\" width=\"10%\"><b>RUNS<b></td>\n";
    echo "  <td align=\"center\" width=\"7%\"><b>HS<b></td>\n";
    echo "  <td align=\"center\" width=\"10%\"><b>AVE<b></td>\n";
    echo "  <td align=\"center\" width=\"8%\"><b>100<b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>50<b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>Ct<b></td>\n";
    echo "  <td align=\"center\" width=\"10%\"><b>St<b></td>\n";
    echo " </tr>\n";
    
    
    for($i=1; $i<=5; $i++) {
    	
    	
    	// reset all counts
		$scinn = 0;
	    $scrun = 0;   
	    $scnos = 0;
	    $schig = 0;
    	$scnot = 0;
	    $outin = 0;
	    $scavg = 0;
	    $schun = 0;
	    $scfif = 0;
	    $scctc = 0;
	    $sccab = 0;
		$sccat = 0;
		$cosccat = 0;
		$scstu = 0;
	    
	    
    	if($i == 1) {
    		$str_league = "(g.league_id = 1)";
    		$str_league_type = "Premier";
    	}
    	else if($i == 2) {
    		$str_league = "(g.league_id = 4)";
    		$str_league_type = "Twenty20";
    	}
    	else if($i == 3) {
    		$str_league = "(g.league_id = 1 OR g.league_id=4)";
    		$str_league_type = "<b>League Total</b>";
    	}
    	else if($i == 4) {
    		$str_league = "(g.league_id = 2)";
    		$str_league_type = "Cougars";
    	}
    	else if($i == 5) {
    		$str_league = "(g.league_id = 2 OR g.league_id = 1 OR g.league_id=4)";
    		$str_league_type = "<b>Grand Total</b>";
    	}
    
	    // Get League Matches and Runs
		
	 	 if ($db->Exists("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league GROUP BY p.PlayerLName, p.PlayerFName")) {
	    $db->QueryRow("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league GROUP BY p.PlayerLName, p.PlayerFName");
	    $db->BagAndTag();
	    $scinn = $db->data[Matches];
	    $scrun = $db->data[Runs];   
	    } else {
	    }
    	
    	
    	// Get League High Score
	
	    if ($db->Exists("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league ORDER BY b.runs DESC LIMIT 1")) {
	    $db->QueryRow("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league ORDER BY b.runs DESC LIMIT 1");
	    $db->BagAndTag();
	    $scnos = $db->data[notout];
	    $schig = $db->data[HS]; 
	    } else {
	    }   
	    
		// Get League Notouts
	    
	    if ($db->Exists("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND (b.how_out = 2 OR b.how_out = 1)")) {
	    $db->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND (b.how_out = 2 OR b.how_out = 1)");
	    $db->BagAndTag();
	    $scnot = $db->data[Notout];
	    $outin = $scinn - $scnot;
	    
	    if($scrun >= 1 && $outin >= 1) {
	    $scavg = Number_Format(Round($scrun / $outin, 2),2);
	    } else {
	    $scavg = "0";
	    }
	    
	    } else {
	    }
	    
	    
	    
	    // Get League Hundreds
	    
	    if ($db->Exists("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND b.runs >= 100")) {   
	    $db->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND b.runs >= 100");
	    $db->BagAndTag();
	    $schun = $db->data[Hundred];    
	    } else {
	    $schun = "0";
	    }
	
	    // Get League Fifties
	    
	    if ($db->Exists("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND (b.runs BETWEEN 50 AND 99) ")) {   
	    $db->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND (b.runs BETWEEN 50 AND 99) ");
	    $db->BagAndTag();
	    $scfif = $db->data[Fifty];      
	    } else {
	    $scfif = "0";
	    }
	
	   
	    
	    // Get League Caught
	    
	    if ($db->Exists("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND $str_league AND b.how_out = 4")) { 
	    $db->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND $str_league AND b.how_out = 4");
	    $db->BagAndTag();
	    $scctc = $db->data[Caught];
	    } else {
	    $scctc = "0";
	    }
	
	    
	    // Get League c&b
	    
	    if ($db->Exists("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.bowler = $pr AND $str_league AND b.how_out = 5")) {  
	    $db->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.bowler = $pr AND $str_league AND b.how_out = 5");
	    $db->BagAndTag();
	    $sccab = $db->data[CandB];
	    } else {
	    $sccab = "0";
	    }
	    
	    $sccat = $scctc + $sccab;
	
	    
	    // Get League Stumped
	    
	    if ($db->Exists("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND $str_league AND b.how_out = 10")) {    
	    $db->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND $str_league AND b.how_out = 10");
	    $db->BagAndTag();
	    $scstu = $db->data[Stumped];
	    } else {
	    $scstu = "0";
	    }
	    
	    
	    // Show League Batting Stats
	    
	    if ($db->Exists("SELECT * FROM scorecard_batting_details WHERE player_id = $pr")) {
	    	if($i == 3 || $i == 5){
	    		
	    		echo " <tr class=\"trrow1\">\n";
			    echo "  <td align=\"left\" width=\"30%\">$str_league_type</td>\n";
			    echo "  <td align=\"center\" width=\"7%\"><b>$scinn</td>\n";
			    echo "  <td align=\"center\" width=\"7%\"><b>$scnot</td>\n";
			    echo "  <td align=\"center\" width=\"10%\"><b>$scrun</td>\n";
			    echo "  <td align=\"center\" width=\"7%\"><b>$schig";
			    if($scnos == '1') echo "*";
			    echo "  </td>\n";
			    echo "  <td align=\"center\" width=\"10%\"><b>$scavg</td>\n";
			    echo "  <td align=\"center\" width=\"8%\"><b>$schun</td>\n";
			    echo "  <td align=\"center\" width=\"6%\"><b>$scfif</td>\n";
			    echo "  <td align=\"center\" width=\"5%\"><b>$sccat</td>\n";
			    echo "  <td align=\"center\" width=\"10%\"><b>$scstu</td>\n";  
		   	 	echo " </tr>\n";
		   	 	
			   
	    	} else {
			    echo " <tr class=\"trrow1\">\n";
			    echo "  <td align=\"left\" width=\"30%\">$str_league_type</td>\n";
			    echo "  <td align=\"center\" width=\"7%\">$scinn</td>\n";
			    echo "  <td align=\"center\" width=\"7%\">$scnot</td>\n";
			    echo "  <td align=\"center\" width=\"10%\">$scrun</td>\n";
			    echo "  <td align=\"center\" width=\"7%\">$schig";
			    if($scnos == '1') echo "*";
			    echo "  </td>\n";
			    echo "  <td align=\"center\" width=\"10%\">$scavg</td>\n";
			    echo "  <td align=\"center\" width=\"8%\">$schun</td>\n";
			    echo "  <td align=\"center\" width=\"6%\">$scfif</td>\n";
			    echo "  <td align=\"center\" width=\"5%\">$sccat</td>\n";
			    echo "  <td align=\"center\" width=\"10%\">$scstu</td>\n";  
			    echo " </tr>\n";
		    }
	    } else {
	    
	    echo " <tr class=\"trrow1\">\n";
	    echo "  <td align=\"left\" width=\"100%\" colspan=\"10\">No statistics at this time.</td>\n";   
	    echo " </tr>\n";
	    
	    }
}
    
	// Show Cougars Batting Stats
	// Get Cougars Matches and Runs
	/*
    if ($db->Exists("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 GROUP BY p.PlayerLName, p.PlayerFName")) {
    $db->QueryRow("SELECT COUNT( b.player_id ) AS Matches, SUM( b.runs ) AS Runs, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $coscinn = $db->data[Matches];
    $coscrun = $db->data[Runs]; 
    } else {
    }
    
    // Get Cougars High Score

    if ($db->Exists("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 ORDER BY b.runs DESC LIMIT 1")) {
    $db->QueryRow("SELECT b.runs AS HS, b.notout, p.PlayerLName, p.PlayerFName FROM scorecard_batting_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 ORDER BY b.runs DESC LIMIT 1");
    $db->BagAndTag();
    $coscnos = $db->data[notout];
    $coschig = $db->data[HS];   
    } else {
    }     
    
    // Get Cougars Notouts
    
    if ($db->Exists("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.how_out = 2")) {
    $db->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.how_out = 2");
    $db->BagAndTag();
    $coscnot = $db->data[Notout];
    $cooutin = $coscinn - $coscnot;
    
    if($coscrun >= 1 && $cooutin >= 1) {
    $coscavg = Number_Format(Round($coscrun / $cooutin, 2),2);
    } else {
    $coscavg = "0";
    }
    
    } else {
    }   
    
 	// Get Cougar Fifties
    
    if ($db->Exists("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND (b.runs BETWEEN 50 AND 99) ")) {   
    $db->QueryRow("SELECT COUNT(b.runs) AS Fifty FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND (b.runs BETWEEN 50 AND 99) ");
    $db->BagAndTag();
    $coscfif = $db->data[Fifty];        
    } else {
    $coscfif = "0";
    }
    
    // Get Cougar Hundreds
    
    if ($db->Exists("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.runs >= 100")) {   
    $db->QueryRow("SELECT COUNT(b.runs) AS Hundred FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.runs >= 100");
    $db->BagAndTag();
    $coschun = $db->data[Hundred];  
    } else {
    $coschun = "0";
    }  
    
	// Get Cougars Caught
    
    if ($db->Exists("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND g.league_id = 2 AND b.how_out = 4")) { 
    $db->QueryRow("SELECT COUNT(b.assist) AS Caught FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND g.league_id = 2 AND b.how_out = 4");
    $db->BagAndTag();
    $coscctc = $db->data[Caught];
    } else {
    $coscctc = "0";
    }

    
    // Get Cougars c&b
    
    if ($db->Exists("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.bowler = $pr AND g.league_id = 2 AND b.how_out = 5")) {  
    $db->QueryRow("SELECT COUNT(b.bowler) AS CandB FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.bowler = $pr AND g.league_id = 2 AND b.how_out = 5");
    $db->BagAndTag();
    $cosccab = $db->data[CandB];
    } else {
    $cosccab = "0";
    }
    
 	// Get Cougars Stumped
    
    if ($db->Exists("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND g.league_id = 2 AND b.how_out = 10")) {    
    $db->QueryRow("SELECT COUNT(b.assist) AS Stumped FROM scorecard_batting_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.assist = $pr AND g.league_id = 2 AND b.how_out = 10");
    $db->BagAndTag();
    $coscstu = $db->data[Stumped];
    } else {
    $coscstu = "0";
    }
    
    // Get Cougars Maidens, Runs, Wickets
    
    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 GROUP BY p.PlayerLName, p.PlayerFName")) {  
    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 GROUP BY p.PlayerLName, p.PlayerFName");
    $db->BagAndTag();
    $coscmai = $db->data[Maidens];
    $coscbru = $db->data[BRuns];
    $coscwic = $db->data[Wickets];

    // Get Cougars Overs, Balls
    
    $cobnum = $db->data[Balls]; 
    $cobovers = Round(($cobnum / 6), 2); 
    $cobfloor = floor($cobovers); 

    if($cobovers == $cobfloor + 0.17) { 
      $coscove = $cobfloor + 0.1; 
    } else 
    if($cobovers == $cobfloor + 0.33) { 
      $coscove = $cobfloor + 0.2; 
    } else 
    if($cobovers == $cobfloor + 0.5) { 
      $coscove = $cobfloor + 0.3;        
    } else 
    if($cobovers == $cobfloor + 0.67) { 
      $coscove = $cobfloor + 0.4;        
    } else 
    if($cobovers == $cobfloor + 0.83) { 
      $coscove = $cobfloor + 0.5; 
    } else { 
      $coscove = $cobfloor; 
    }     

    } else {
      $coscove = "0";
    }
    
	// Get Cougars 4 Wickets
    
    if ($db->Exists("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.wickets = 4")) {    
    $db->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.wickets = 4");
    $db->BagAndTag();
    $coscbfo = $db->data[fourwickets];
    } else {
    $coscbfo = "0";
    }
    
    // Get Cougars 5 Wickets
    
    if ($db->Exists("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.wickets >= 5")) {   
    $db->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 AND b.wickets >= 5");
    $db->BagAndTag();
    $coscbfi = $db->data[fivewickets];
    } else {
    $coscbfi = "0";
    }   
    
  
    

    // Get Cougars Best Bowling
    
    if ($db->Exists("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 ORDER BY b.wickets DESC, b.runs ASC LIMIT 1")) {   
    $db->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND g.league_id = 2 ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
    $db->BagAndTag();
    $coscbbw = $db->data[wickets];
    $coscbbr = $db->data[runs]; 
    
    if($coscbru >= 1 && $coscwic >= 1) {
    $coboavg = Number_Format(Round($coscbru / $coscwic, 2),2);
    } else {
    $coboavg = "0";
    }
    
    if($coscbru >= 1 && $coscove >= 0.1) {  
    $coboeco = Number_Format(Round($coscbru / $coscove, 2),2);
    } else {
    $coboeco = "0";
    }   
    
    } else {
    }
    
	$cosccat = $coscctc + $cosccab;

    Commented because used looping to generate cougars above
    if ($db->Exists("SELECT * FROM scorecard_batting_details WHERE player_id = $pr")) {
    
    echo " <tr class=\"trrow2\">\n";
    echo "  <td align=\"left\" width=\"30%\">Cougars</td>\n";
    echo "  <td align=\"center\" width=\"7%\">$coscinn</td>\n";
    echo "  <td align=\"center\" width=\"7%\">$coscnot</td>\n";
    echo "  <td align=\"center\" width=\"10%\">$coscrun</td>\n";
    echo "  <td align=\"center\" width=\"7%\">$coschig";
    if($coscnos == '1') echo "*";
    echo "  </td>\n";
    echo "  <td align=\"center\" width=\"10%\">$coscavg</td>\n";
    echo "  <td align=\"center\" width=\"8%\">$coschun</td>\n";
    echo "  <td align=\"center\" width=\"6%\">$coscfif</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$cosccat</td>\n";
    echo "  <td align=\"center\" width=\"10%\">$coscstu</td>\n";    
    echo " </tr>\n";

    } else {
    
    echo " <tr class=\"trrow1\">\n";
    echo "  <td align=\"left\" width=\"100%\" colspan=\"10\">No statistics at this time.</td>\n";   
    echo " </tr>\n";
    
    }
    */
    
    echo " <tr class=\"colhead\">\n";
    echo "  <td align=\"left\" width=\"30%\"><b>Bowling</b></td>\n";
    echo "  <td align=\"center\" width=\"7%\"><b>O</b></td>\n";
    echo "  <td align=\"center\" width=\"7%\"><b>M</b></td>\n";
    echo "  <td align=\"center\" width=\"10%\"><b>RUNS</b></td>\n";
    echo "  <td align=\"center\" width=\"7%\"><b>W</b></td>\n";
    echo "  <td align=\"center\" width=\"10%\"><b>AVE</b></td>\n";
    echo "  <td align=\"center\" width=\"8%\"><b>BBI</b></td>\n";
    echo "  <td align=\"center\" width=\"6%\"><b>4w</b></td>\n";
    echo "  <td align=\"center\" width=\"5%\"><b>5w</b></td>\n";
    echo "  <td align=\"center\" width=\"10%\"><b>ECO</b></td>\n";
    echo " </tr>\n";
    
	for($i=1; $i<=5; $i++) {
    	
    	if($i == 1) {
    		$str_league = "(g.league_id = 1)";
    		$str_league_type = "Premier";
    	}
    	else if($i == 2) {
    		$str_league = "(g.league_id = 4)";
    		$str_league_type = "Twenty20";
    	}
    	else if($i == 3) {
    		$str_league = "(g.league_id = 1 OR g.league_id=4)";
    		$str_league_type = "League Total";
    	}
		else if($i == 4) {
    		$str_league = "(g.league_id = 2)";
    		$str_league_type = "Cougars";
    	}
    	else if($i == 5) {
    		$str_league = "(g.league_id = 2 OR g.league_id = 1 OR g.league_id=4)";
    		$str_league_type = "<b>Grand Total</b>";
    	}
    	
		// reset all counts
		$scmai = 0;
		$scbbw = 0;
	    $scbru = 0;
	    $scwic = 0;
		$bnum = 0; 
	    $bovers = 0; 
	    $bfloor = 0;
		$scove = 0;
		$scbfo = 0;
		$scbfi = 0;
		$scbbr = 0;
		$boavg = 0;
		$boeco = 0;
		
	    // Get League Maidens, Runs, Wickets
	    
	    if ($db->Exists("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league GROUP BY p.PlayerLName, p.PlayerFName")) {  
	    $db->QueryRow("SELECT SUM(IF(INSTR(overs, '.'),((LEFT(overs, INSTR(overs, '.') - 1) * 6) + RIGHT(overs, INSTR(overs, '.') - 1)),(overs * 6))) AS Balls, SUM( b.maidens ) AS Maidens, SUM( b.runs ) AS BRuns, SUM( b.wickets ) AS Wickets, p.PlayerLName, p.PlayerFName FROM scorecard_bowling_details b INNER JOIN players p ON b.player_id = p.PlayerID INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league GROUP BY p.PlayerLName, p.PlayerFName");
	    $db->BagAndTag();
	    $scmai = $db->data[Maidens];
	    $scbru = $db->data[BRuns];
	    $scwic = $db->data[Wickets];
	    echo $db->data[overs];
	
	    // Get League Overs, Balls
	    
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
	      $scove = "0";
	    }
	    
	        
	    // Get League 4 Wickets
	    
	    if ($db->Exists("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND b.wickets = 4")) {    
	    $db->QueryRow("SELECT COUNT(b.wickets) AS fourwickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND b.wickets = 4");
	    $db->BagAndTag();
	    $scbfo = $db->data[fourwickets];
	    } else {
	    $scbfo = "0";
	    }
	
	        
	    // Get League 5 Wickets
	    
	    if ($db->Exists("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND b.wickets >= 5")) {   
	    $db->QueryRow("SELECT COUNT(b.wickets) AS fivewickets FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league AND b.wickets >= 5");
	    $db->BagAndTag();
	    $scbfi = $db->data[fivewickets];
	    } else {
	    $scbfi = "0";
	    }
	    
	    // Get League Best Bowling
	    
	    if ($db->Exists("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league ORDER BY b.wickets DESC, b.runs ASC LIMIT 1")) {   
	    $db->QueryRow("SELECT b.player_id, b.wickets, b.runs FROM scorecard_bowling_details b INNER JOIN scorecard_game_details g ON b.game_id = g.game_id WHERE b.player_id = $pr AND $str_league ORDER BY b.wickets DESC, b.runs ASC LIMIT 1");
	    $db->BagAndTag();
	    $scbbw = $db->data[wickets];
	    $scbbr = $db->data[runs];   
	   
	    
	    if($scbru >= 1 && $scwic >= 1) {
	    $boavg = Number_Format(Round($scbru / $scwic, 2),2);
	    } else {
	    $boavg = "0";
	    }
	    
	    if($scbru >= 1 && $scove >= 1) {  
	    $boeco = Number_Format(Round($scbru / $scove, 2),2);
	    } 
	    else if($scbru >= 1 && $scove < 1) {
	    $boeco = Number_Format(Round($scbru * $bovers, 2),2);	
	    }
	    else {
	    $boeco = "0";
	    }   
	    
	    } else {
	    }
	    
	    // Show League Bowling Stats
	    if ($db->Exists("SELECT * FROM scorecard_bowling_details WHERE player_id = $pr")) {
		    if($i == 3 || $i ==5 ){
		    	
		    	echo " <tr class=\"trrow1\">\n";
			    echo "  <td align=\"left\" width=\"30%\"><b>$str_league_type</td>\n";
			    echo "  <td align=\"center\" width=\"7%\"><b>$scove</td>\n";
			    echo "  <td align=\"center\" width=\"7%\"><b>$scmai</td>\n";
			    echo "  <td align=\"center\" width=\"10%\"><b>$scbru</td>\n";
			    echo "  <td align=\"center\" width=\"7%\"><b>$scwic</td>\n";
			    echo "  <td align=\"center\" width=\"10%\"><b>$boavg</td>\n";
			    echo "  <td align=\"center\" width=\"8%\"><b>$scbbw-$scbbr</td>\n";
			    echo "  <td align=\"center\" width=\"6%\"><b>$scbfo</td>\n";
			    echo "  <td align=\"center\" width=\"5%\"><b>$scbfi</td>\n";
			    echo "  <td align=\"center\" width=\"10%\"><b>$boeco</td>\n";  
			    echo " </tr>\n"; 
			    
			   
		    } else {
			    echo " <tr class=\"trrow1\">\n";
			    echo "  <td align=\"left\" width=\"30%\">$str_league_type</td>\n";
			    echo "  <td align=\"center\" width=\"7%\">$scove</td>\n";
			    echo "  <td align=\"center\" width=\"7%\">$scmai</td>\n";
			    echo "  <td align=\"center\" width=\"10%\">$scbru</td>\n";
			    echo "  <td align=\"center\" width=\"7%\">$scwic</td>\n";
			    echo "  <td align=\"center\" width=\"10%\">$boavg</td>\n";
			    echo "  <td align=\"center\" width=\"8%\">$scbbw-$scbbr</td>\n";
			    echo "  <td align=\"center\" width=\"6%\">$scbfo</td>\n";
			    echo "  <td align=\"center\" width=\"5%\">$scbfi</td>\n";
			    echo "  <td align=\"center\" width=\"10%\">$boeco</td>\n";  
			    echo " </tr>\n";    
		    }
	    } else {
	    
	    echo " <tr class=\"trrow1\">\n";
	    echo "  <td align=\"left\" width=\"100%\" colspan=\"10\">No statistics at this time.</td>\n";   
	    echo " </tr>\n";
	    
	    }
	}
	
 	// Show Cougars Bowling Stats
    /*
    if ($db->Exists("SELECT * FROM scorecard_bowling_details WHERE player_id = $pr")) {
    
    echo " <tr class=\"trrow2\">\n";
    echo "  <td align=\"left\" width=\"30%\">Cougars</td>\n";
    echo "  <td align=\"center\" width=\"7%\">$coscove</td>\n";
    echo "  <td align=\"center\" width=\"7%\">$coscmai</td>\n";
    echo "  <td align=\"center\" width=\"10%\">$coscbru</td>\n";
    echo "  <td align=\"center\" width=\"7%\">$coscwic</td>\n";
    echo "  <td align=\"center\" width=\"10%\">$coboavg</td>\n";
    echo "  <td align=\"center\" width=\"8%\">$coscbbw-$coscbbr</td>\n";
    echo "  <td align=\"center\" width=\"6%\">$coscbfo</td>\n";
    echo "  <td align=\"center\" width=\"5%\">$coscbfi</td>\n";
    echo "  <td align=\"center\" width=\"10%\">$coboeco</td>\n";    
    echo " </tr>\n";    

    } else {
    
    echo " <tr class=\"trrow1\">\n";
    echo "  <td align=\"left\" width=\"100%\" colspan=\"10\">No statistics at this time.</td>\n";   
    echo " </tr>\n";
    
    }
    */
    
    echo "</table>\n";
    
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Career Stats Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;CAREER STATISTICS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "<tr class=\"trrow1\">\n";
    echo "  <td width=\"100%\">";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // Get the First League Game
    
    if ($db_bat->Exists("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1")) {
    $db_bat->QueryRow("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1");
    $db_bat->BagAndTag();
    $game_date_bat = $db_bat->data[FirstGame];
    $gid_bat = $db_bat->data[game_id];
   
    }
    
	if ($db_bat->Exists("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_bowling_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1")) {
    $db_bowl->QueryRow("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_bowling_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1");
    $db_bowl->BagAndTag();
    $game_date_bowl = $db_bowl->data[FirstGame];
    $gid_bowl = $db_bowl->data[game_id];
    
    }
    
    if($game_date_bat > $game_date_bowl) {
    	$gid = $gid_bowl;
    }
    else{
    	$gid = $gid_bat;
    }
    
	if($game_date_bat == '') {
    	$gid = $gid_bowl;
    }
	if($game_date_bowl == '') { 
    	$gid = $gid_bat;
    }
    
   
    if($game_date_bat < $game_date_bowl || $game_date_bowl == ''){
	    if ($db->Exists("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE ga.game_id= $gid AND b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1")) {
	    $db->QueryRow("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE ga.game_id= $gid AND b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1");
	    $db->BagAndTag();
	    
	    $fid = $db->data[game_id];
	    $ffg = sqldate_to_string($db->data[FirstGame]);
	    $ft1 = $db->data[HomeTeam];
	    $ft2 = $db->data[AwayTeam];
	    
	    echo "  <tr>\n";
	    echo "    <td width=\"30%\" align=\"left\"><b>League Debut</b></td>\n";
	    echo "    <td width=\"55%\" align=\"left\">$ft1 vs $ft2 - $ffg </td>\n";
	    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
	    echo "  </tr>\n";
	    } else {
	    echo "This player has yet to play an official CCL game.";
	    }
    }
    else if($game_date_bowl < $game_date_bat || $game_date_bat == ''){
     if ($db->Exists("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_bowling_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE ga.game_id= $gid AND b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1")) {
	    $db->QueryRow("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_bowling_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE ga.game_id= $gid AND b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY FirstGame LIMIT 1");
	    $db->BagAndTag();
	    
	    $fid = $db->data[game_id];
	    $ffg = sqldate_to_string($db->data[FirstGame]);
	    $ft1 = $db->data[HomeTeam];
	    $ft2 = $db->data[AwayTeam];
	    
	    echo "  <tr>\n";
	    echo "    <td width=\"30%\" align=\"left\"><b>League Debut</b></td>\n";
	    echo "    <td width=\"55%\" align=\"left\">$ft1 vs $ft2 - $ffg </td>\n";
	    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
	    echo "  </tr>\n";
	    } else {
	    echo "This player has yet to play an official CCL game.";
	    }
    }

    
    // Get the Last League Game
    
    if ($db->Exists("SELECT b.player_id, ga.game_date as LastGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY LastGame DESC LIMIT 1")) {
    $db->QueryRow("SELECT b.player_id, ga.game_date as LastGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND (ga.league_id = 1 OR ga.league_id=4) ORDER BY LastGame DESC LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data[game_id];
    $flg = sqldate_to_string($db->data[LastGame]);
    $ft1 = $db->data[HomeTeam];
    $ft2 = $db->data[AwayTeam];
    
    echo "  <tr>\n";
    echo "    <td width=\"30%\" align=\"left\"><b>Last League Game</b></td>\n";
    echo "    <td width=\"55%\" align=\"left\">$ft1 vs $ft2 - $flg</td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";       
    echo "  </tr>\n";
    
    } else {
    echo "";
    }

    // Get the First Cougars Game
    
    if ($db->Exists("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND ga.league_id=2 ORDER BY FirstGame LIMIT 1")) {
    $db->QueryRow("SELECT b.player_id, ga.game_date as FirstGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND ga.league_id=2 ORDER BY FirstGame LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data[game_id];
    $ffg = sqldate_to_string($db->data[FirstGame]);
    $ft1 = $db->data[HomeTeam];
    $ft2 = $db->data[AwayTeam];
    
    echo "  <tr>\n";
    echo "    <td width=\"30%\" align=\"left\"><b>Cougars Debut</b></td>\n";
    echo "    <td width=\"55%\" align=\"left\">$ft1 vs $ft2 - $ffg </td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";   
    echo "  </tr>\n";
    } else {
    echo "";
    }


    // Get the Last Cougar Game
    
    if ($db->Exists("SELECT b.player_id, ga.game_date as LastGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND ga.league_id=2 ORDER BY LastGame DESC LIMIT 1")) {
    $db->QueryRow("SELECT b.player_id, ga.game_date as LastGame, ga.game_id, ga.hometeam, ga.awayteam, t1.TeamAbbrev AS AwayTeam, t2.TeamAbbrev AS HomeTeam FROM scorecard_game_details ga LEFT JOIN scorecard_batting_details b ON b.game_id = ga.game_id LEFT JOIN teams t1 ON ga.awayteam = t1.TeamID LEFT JOIN teams t2 ON ga.hometeam = t2.TeamID WHERE b.player_id=$pr AND ga.league_id=2 ORDER BY LastGame DESC LIMIT 1");
    $db->BagAndTag();
    
    $fid = $db->data[game_id];
    $flg = sqldate_to_string($db->data[LastGame]);
    $ft1 = $db->data[HomeTeam];
    $ft2 = $db->data[AwayTeam];
    
    echo "  <tr>\n";
    echo "    <td width=\"30%\" align=\"left\"><b>Last Cougars Game</b></td>\n";
    echo "    <td width=\"55%\" align=\"left\">$ft1 vs $ft2 - $flg</td>\n";
    echo "    <td width=\"15%\" align=\"right\"><a href=\"/scorecardfull.php?game_id=$fid&ccl_mode=4\">scorecard</a></td>\n";       
    echo "  </tr>\n";
    
    } else {
    echo "";
    }
    
    echo "</table>\n";
    
    echo "  </td>\n";


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
    echo "<div align=\"center\"><img src=\"/uploadphotos/players/action/$pic1\" border=\"1\"></div></td>\n";
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
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;Recent Articles featuring $pfn $pln</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    // process features
        if ($db->Exists("SELECT * FROM news WHERE IsFeature != 1 AND IsPending !=1 AND (article LIKE '%$pfn%' OR article LIKE '%$pln%' ) ORDER BY id DESC LIMIT 5")) {
        $db->Query("SELECT * FROM news WHERE IsFeature != 1 AND IsPending !=1 AND (article LIKE '%$pfn%' OR article LIKE '%$pln%' ) ORDER BY id DESC LIMIT 5");

        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $t = $db->data[title];
            $au = $db->data[author];
            $id = $db->data[id];
            $pr = $db->data[id];
            $date = sqldate_to_string($db->data[added]);

        //if($i % 2) {
        //  echo "<tr class=\"trrow1\">\n";
        //} else {
        //  echo "<tr class=\"trrow2\">\n";
        //}

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><a href=\"news.php?news=$pr&ccl_mode=1\">$t</a>\n";
        if($db->data[picture] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";

        }

        } else {

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\">There are currently no news articles featuring $pfn $pln</td>\n";
        echo "</tr>\n";


        }
        
        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\" align=\"left\"><br><a href=\"http://www.coloradocricket.org/news.php?ccl_mode=2&search=$pln\">View the full list of related articles</a></td>\n";
        echo "</tr>\n";     


        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";


    // output article
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    // Achievements Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$tco\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$tco\" class=\"whitemain\" height=\"23\">&nbsp;Recognitions of $pfn $pln</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    // do they hold office?
    
        if ($db->Exists("SELECT * FROM cclofficers WHERE cclofficerPlayerID=$plid")) {
        $db->Query("
            SELECT 
              cl.*, pl.picture, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail 
            FROM 
              players pl 
            INNER JOIN 
              cclofficers cl ON pl.PlayerID = cl.cclofficerPlayerID 
            WHERE 
              cl.cclofficerPlayerID=$plid               
        ");

        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
            $pln = htmlentities(stripslashes($db->data[PlayerLName]));
            $pid = htmlentities(stripslashes($db->data[PlayerID]));

            $tit = htmlentities(stripslashes($db->data[cclofficerTitle]));
            $tid = htmlentities(stripslashes($db->data[cclofficerID]));

        //if($i % 2) {
        //  echo "<tr class=\"trrow1\">\n";
        //} else {
        //  echo "<tr class=\"trrow2\">\n";
        //}
        
        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><a href=\"/cclofficers.php?offid=$tid&ccl_mode=2\">Colorado Cricket League $tit</a></td>\n";
        echo "  </tr>\n";

        }

        } else {
        echo "";
        }

    // have they won an award
    
        if ($db->Exists("SELECT * FROM awards WHERE AwardPlayer=$plid")) {
        $db->Query("
            SELECT
                pl.PlayerFName, pl.PlayerLName,
                te.TeamName, te.TeamAbbrev,
                fm.*, fm.AwardID AS plaward,
                at.*, se.*
            FROM
                awards fm
            INNER JOIN
                players pl ON fm.AwardPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            INNER JOIN
                awardtypes at ON fm.AwardTitle = at.AwardID
            INNER JOIN
                seasons se ON fm.season = se.SeasonID
            WHERE
                fm.AwardPlayer = $plid
            ORDER BY
                at.AwardName ASC
        ");



        // output featured articles
        
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
            $pln = htmlentities(stripslashes($db->data[PlayerLName]));

            $tna = htmlentities(stripslashes($db->data[TeamName]));
            $tab = htmlentities(stripslashes($db->data[TeamAbbrev]));

            $det = htmlentities(stripslashes($db->data[AwardDetail]));
            $awn = htmlentities(stripslashes($db->data[AwardName]));
            $id = htmlentities(stripslashes($db->data[plaward]));
            $sn = htmlentities(stripslashes($db->data[SeasonName]));
            $a = sqldate_to_string($db->data[added]);

        //if($i % 2) {
        //  echo "<tr class=\"trrow1\">\n";
        //} else {
        //  echo "<tr class=\"trrow2\">\n";
        //}

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><a href=\"/awards.php?aw=$id&ccl_mode=1\">$sn $awn</a></td>\n";
        echo "  </tr>\n";

        }

        } else {

        echo "";
        }       

    // have they represented Colorado Cougars
    
        if ($db->Exists("SELECT * FROM cougarsplayers WHERE CougarPlayer=$plid")) {
        $db->Query("
            SELECT
                pl.PlayerFName, pl.PlayerLName,
                te.TeamName, te.TeamAbbrev,
                cp.*, 
                se.*
            FROM
                cougarsplayers cp
            INNER JOIN
                players pl ON cp.CougarPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            INNER JOIN
                seasons se ON cp.season = se.SeasonID
            WHERE
                cp.CougarPlayer = $plid
            ORDER BY
                cp.season ASC
        ");



        // output featured articles
        
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
            $pln = htmlentities(stripslashes($db->data[PlayerLName]));

            $tna = htmlentities(stripslashes($db->data[TeamName]));
            $tab = htmlentities(stripslashes($db->data[TeamAbbrev]));

            $sn = htmlentities(stripslashes($db->data[SeasonName]));
            $a = sqldate_to_string($db->data[added]);
            
            $cpid = htmlentities(stripslashes($db->data[CougarID]));

        //if($i % 2) {
        //  echo "<tr class=\"trrow1\">\n";
        //} else {
        //  echo "<tr class=\"trrow2\">\n";
        //}

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><a href=\"http://cougars.coloradocricket.org/players.php?fm=$cpid&ccl_mode=1\">$sn Colorado Cougar</a></td>\n";
        echo "  </tr>\n";

        }

        } else {

        echo "";
        }   
        
    // have they been a featured member
    
        if ($db->Exists("SELECT * FROM featuredmember WHERE FeaturedPlayer=$plid")) {
        $db->Query("
            SELECT
                pl.PlayerFName, pl.PlayerLName,
                te.TeamName, te.TeamAbbrev,
                fm.FeaturedID, fm.FeaturedDetail, fm.FeaturedPlayer, fm.season, fm.added
            FROM
                featuredmember fm
            INNER JOIN
                players pl ON fm.FeaturedPlayer = pl.PlayerID
            INNER JOIN
                teams te ON pl.PlayerTeam = te.TeamID
            WHERE
                fm.FeaturedPlayer=$plid
            ORDER BY
                fm.FeaturedID DESC
        ");



        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
            $pln = htmlentities(stripslashes($db->data[PlayerLName]));
            $pid = htmlentities(stripslashes($db->data[FeaturedPlayer]));

            $tna = htmlentities(stripslashes($db->data[TeamName]));
            $tab = htmlentities(stripslashes($db->data[TeamAbbrev]));

            $det = htmlentities(stripslashes($db->data[FeaturedDetail]));
            $id = htmlentities(stripslashes($db->data[FeaturedID]));
            $sn = htmlentities(stripslashes($db->data[SeasonName]));
            $a = sqldate_to_string($db->data[added]);

        //if($i % 2) {
        //  echo "<tr class=\"trrow1\">\n";
        //} else {
        //  echo "<tr class=\"trrow2\">\n";
        //}

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><a href=\"/featuredmember.php?fm=$id&ccl_mode=1\">Featured Player on $a</a></td>\n";
        echo "  </tr>\n";

        }

        } else {

        echo "<tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\">There are currently no recognitions featuring $pfn $pln</td>\n";
        echo "</tr>\n";


        }


            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            // finish off
            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";


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

         if (!$db->Exists("SELECT * FROM players")) {
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
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
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

        $db->Query("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE $contains AND te.LeagueID = 1 ORDER BY pl.PlayerLName");
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
            $id = htmlentities(stripslashes($db->data[PlayerID]));
            $pln = htmlentities(stripslashes($db->data[PlayerLName]));
            $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
            $pte = htmlentities(stripslashes($db->data[TeamAbbrev]));

            if($i % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"$PHP_SELF?players=$id&ccl_mode=1\">$pln, $pfn</a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data[picture] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture_action.gif\">\n";
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

    $db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 ORDER BY TeamName");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data[TeamID]));
        $na = htmlentities(stripslashes($db->data[TeamName]));
        $di = htmlentities(stripslashes($db->data[TeamDirections]));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
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
    
    $db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 ORDER BY TeamName");
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

        echo "    <td width=\"100%\"><a href=\"teams.php?teams=$id&ccl_mode=1\"><b>$na</b></a>&nbsp;\n";
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
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active Players Beginning With \"$letter\"</b><br><br>\n";

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


    if ($db->Exists("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' AND te.TeamActive = 1 AND pl.isactive = 0 ORDER BY pl.PlayerLName")) {
    $db->QueryRow("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' AND te.TeamActive = 1 AND pl.isactive = 0 ORDER BY pl.PlayerLName");
    $db->BagAndTag();
    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        $id = htmlentities(stripslashes($db->data[PlayerID]));
        $pln = htmlentities(stripslashes($db->data[PlayerLName]));
        $pfn = htmlentities(stripslashes($db->data[PlayerFName]));
        $pte = htmlentities(stripslashes($db->data[TeamAbbrev]));

        // output article

            if($r % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td width=\"100%\"><a href=\"$PHP_SELF?players=$id&ccl_mode=1\">$pln, $pfn</a> <span class=\"9px\">($pte)</span>&nbsp;\n";
        if ($db->data[picture] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
        if ($db->data[picture1] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture_action.gif\">\n";
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
$db->SelectDB($dbcfg[db]);

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
