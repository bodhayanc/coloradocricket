<?php

//------------------------------------------------------------------------------
// Site Control History Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add a new featured member</a></p>\n";

	echo "<p>or, please select a season to work with.</p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM seasons ORDER BY SeasonName")) {
		echo "<p>There are currently no seasons in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

		// Schedule Select Box

		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
		echo "  <tr>\n";
		echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Select a season</td>\n";
		echo "  </tr>\n";
		echo "  <tr>\n";
		echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

       		echo "    <p><select name=ccl_mode onChange=\"gotosite(this.options[this.selectedIndex].value)\">\n";
		echo "    <option>select a season</option>\n";
		echo "    <option>===============</option>\n";

// 19-Aug-2009
$db->QueryRow("SELECT * FROM seasons ORDER BY SeasonName DESC");
//	$db->QueryRow("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$db->BagAndTag();

			// output
			$id = $db->data['SeasonID'];
			$season = $db->data['SeasonID'];
			$sename = $db->data['SeasonName'];

			echo "    <option value=\"main.php?SID=$SID&action=$action&do=byseason&season=$season&sename=$sename\">" . $db->data['SeasonName'] . " season</option>\n";

		}

		echo "    </select></p>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table>\n";
		


	}
}


function show_main_menu_season($db,$season,$sename)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a featured member</a></p>\n";

	// check for empty database

	if (!$db->Exists("
			SELECT
				pl.PlayerFName, pl.PlayerLName,
				te.TeamName, te.TeamAbbrev,
				fm.FeaturedID, fm.FeaturedDetail, fm.season
			FROM
				featuredmember fm
			INNER JOIN
				players pl ON fm.FeaturedPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			WHERE
				fm.season=$season
			ORDER BY
				fm.FeaturedID DESC
	")) {

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Members for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";
	  echo "<tr class=\"trrow1\">\n";

		echo "	<td align=\"left\">There are no featured players for this season.</td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";


		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Featured Members for $sename</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("
			SELECT
				pl.PlayerFName, pl.PlayerLName,
				te.TeamName, te.TeamAbbrev,
				fm.FeaturedID, fm.FeaturedDetail, fm.season
			FROM
				featuredmember fm
			INNER JOIN
				players pl ON fm.FeaturedPlayer = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			WHERE
				fm.season=$season
			ORDER BY
				fm.FeaturedID DESC
		");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
			$pln = htmlentities(stripslashes($db->data['PlayerLName']));

			$tna = htmlentities(stripslashes($db->data['TeamName']));
			$tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

			$det = htmlentities(stripslashes($db->data['FeaturedDetail']));
			$id = htmlentities(stripslashes($db->data['FeaturedID']));

			if($x % 2) {
			  echo "<tr class=\"trrow2\">\n";
			} else {
			  echo "<tr class=\"trrow1\">\n";
			}

			// output

			echo "	<td align=\"left\">$id</td>\n";
			echo "	<td align=\"left\">$pfn $pln</td>\n";
			echo "	<td align=\"left\">$tab</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['FeaturedID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['FeaturedID'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";

		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to season selection list</a></p>\n";

	}
}

function select_week($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	$db->QueryRow("SELECT s.SeasonID SeasonID, s.SeasonName SeasonName FROM seasons s, scorecard_game_details g WHERE s.SeasonID = g.season ORDER BY SeasonID DESC LIMIT 1");
	$db->BagAndTag();

	$sid = $db->data['SeasonID'];
	$snm = $db->data['SeasonName'];
	$yr = preg_split("/[\s,]+/", $snm)[0];

	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
	echo "<tr>\n";
	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Week for POTW/Featured member</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";

	echo "<p class=\"10px\">Week: ";
	$db->Query("SELECT week, game_date, WEEKDAY (game_date) as dow, DATE_ADD(game_date, INTERVAL 1 DAY) AS nextday, DATE_ADD(game_date, INTERVAL -1 DAY) AS prevday FROM scorecard_game_details WHERE season IN (SELECT SeasonID FROM seasons WHERE SeasonName LIKE '$yr%') GROUP BY week");
	for ($x=0; $x<$db->rows; $x++) {
		$db->GetRow($x);
		$db->BagAndTag();
		$wk = $db->data['week'];
		$dt = $db->data['game_date'];
		$dow = $db->data['dow'];
		$nxtdt = $db->data['nextday'];
		$prvdt = $db->data['prevday'];
		if($dow == 5) {
			$wkoftxt = "Week of $dt and $nxtdt";
		}
		if($dow == 6) {
			$wkoftxt = "Week of $prvdt and $dt";
		}
	echo "    <a href=\"main.php?SID=$SID&action=$action&do=sadd&week=$wk\" alt=\"$wkoftxt\" title=\"$wkoftxt\">$wk</a> |\n";
	}
	echo "</p>\n";	
	
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}

function add_category_form($db, $week)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	$fmtxt = "[b][u]Honorable mentions[/u][/b]\n\n";
	$subdb = clone $db;
	$featured_players = array();
	$x = 0;
	$db->QueryRow("SELECT s.SeasonID SeasonID, s.SeasonName SeasonName FROM seasons s, scorecard_game_details g WHERE s.SeasonID = g.season ORDER BY SeasonID DESC LIMIT 1");
	$db->BagAndTag();

	$sid = $db->data['SeasonID'];
	$snm = $db->data['SeasonName'];
	$yr = preg_split("/[\s,]+/", $snm)[0];
	
	$P40runcriteria = 30;
	$P40wicketcriteria = 3;
	$T20runcriteria = 30;
	$T20wicketcriteria = 3;
	if ($db->Exists("SELECT g.game_id AS game_id, ht.TeamAbbrev AS HomeTeam, at.TeamAbbrev AS AwayTeam, g.mom AS mom, g.mom2 AS mom2 FROM scorecard_game_details g, 
				teams ht, teams at WHERE g.week = $week AND g.season IN (SELECT SeasonID FROM seasons WHERE SeasonName LIKE '$yr%') AND g.league_id = 1 AND g.cancelled = 0 AND g.cancelledplay = 0 AND g.hometeam = ht.TeamID AND 
				g.awayteam = at.TeamID")) {
		$db->Query("SELECT g.game_id AS game_id, ht.TeamAbbrev AS HomeTeam, at.TeamAbbrev AS AwayTeam, g.mom AS mom, g.mom2 AS mom2 FROM scorecard_game_details g, 
				teams ht, teams at WHERE g.week = $week AND g.season IN (SELECT SeasonID FROM seasons WHERE SeasonName LIKE '$yr%') AND g.league_id = 1 AND g.cancelled = 0 AND g.cancelledplay = 0 AND g.hometeam = ht.TeamID AND 
				g.awayteam = at.TeamID");
		$fmtxt .= "[b]Premier[/b]\n\n";
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$fmtxt .=  "[b]" . $db->data['HomeTeam'] . " vs " . $db->data['AwayTeam'] . "[/b]\n";
			$gid = $db->data['game_id'];
			$mom = (empty($db->data['mom']) ? 0 : $db->data['mom']);
			$mom2 = (empty($db->data['mom2']) ? 0 : $db->data['mom2']);
			if($mom > 0) {
				$subdb->QueryRow("SELECT PlayerFName, PlayerLName from players where PlayerID = $mom");
				$subdb->BagAndTag();
				$fmtxt .=  "[b]Man of the Match: " . $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . "[/b] - ";
				$featured_player = array ("PlayerID" => $mom, "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if($subdb->Exists("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = $gid and player_id = $mom and runs > 10")) {
					$subdb->QueryRow("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = $gid and player_id = $mom and runs > 10");
					$subdb->BagAndTag();
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if($subdb->Exists("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = $gid and player_id = $mom")) {
					$subdb->QueryRow("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = $gid and player_id = $mom");
					$subdb->BagAndTag();
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['runs'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
			if($mom2 > 0) {
				$subdb->QueryRow("SELECT PlayerFName, PlayerLName from players where PlayerID = $mom2");
				$subdb->BagAndTag();
				$fmtxt .=  "[b]Man of the Match: " . $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . "[/b] - ";
				$featured_player = array ("PlayerID" => $mom2, "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if($subdb->Exists("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = $gid and player_id = $mom2 and runs > 10")) {
					$subdb->QueryRow("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = $gid and player_id = $mom2 and runs > 10");
					$subdb->BagAndTag();
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if($subdb->Exists("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = $gid and player_id = $mom2")) {
					$subdb->QueryRow("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = $gid and player_id = $mom2");
					$subdb->BagAndTag();
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['runs'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
            $pIds = "";
			$subdb->Query("SELECT bt.player_id AS player_id, pl.PlayerFName AS PlayerFName, pl.PlayerLName AS PlayerLName, bt.runs AS runs, bt.balls AS balls, bt.sixes AS sixes, 
							bt.fours AS fours, bl.overs as overs, bl.maidens AS maidens, bl.runs AS blruns, bl.wickets AS wickets from scorecard_batting_details bt 
							LEFT JOIN scorecard_bowling_details bl ON bl.player_id = bt.player_id AND bt.game_id = bl.game_id AND bl.wickets > 0 
							INNER JOIN players pl ON pl.PlayerID = bt.player_id where bt.game_id = $gid and bt.player_id != $mom and bt.player_id != $mom2 and bt.runs >= $P40runcriteria");
			for ($j=0; $j<$subdb->rows; $j++) {
				$subdb->GetRow($j);
				$pIds .= $subdb->data['player_id'] . ",";
				$fmtxt .= $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . " - ";
				$featured_player = array ("PlayerID" => $subdb->data['player_id'], "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if ($subdb->data['runs'] > 0) {
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if ($subdb->data['wickets'] > 0) {
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['blruns'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
			$pIds = rtrim($pIds, ',');
			$pIds = empty($pIds) ? 0 : $pIds;
			$subdb->Query("SELECT bl.player_id AS player_id, pl.PlayerFName AS PlayerFName, pl.PlayerLName AS PlayerLName, bt.runs AS runs, bt.balls AS balls, bt.sixes AS sixes, 
							bt.fours AS fours, bl.overs as overs, bl.maidens AS maidens, bl.runs AS blruns, bl.wickets AS wickets from 
							scorecard_bowling_details bl LEFT JOIN scorecard_batting_details bt ON bl.player_id = bt.player_id AND bt.game_id = bl.game_id 
							AND bt.runs > 10 INNER JOIN players pl ON pl.PlayerID = bl.player_id where bl.game_id = $gid  
							and bl.player_id != $mom and bl.player_id != $mom2 and bl.player_id NOT IN ($pIds) and bl.wickets >= $P40wicketcriteria");
			for ($j=0; $j<$subdb->rows; $j++) {
				$subdb->GetRow($j);
				$fmtxt .= $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . " - ";
				$featured_player = array ("PlayerID" => $subdb->data['player_id'], "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if ($subdb->data['runs'] > 0) {
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if ($subdb->data['wickets'] > 0) {
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['blruns'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
			$fmtxt .= "\n";
			$fmtxt .= "See CCL's [link=http://coloradocricket.org/scorecardfull.php?game_id=$gid&ccl_mode=4]" . $db->data['HomeTeam'] . " vs " . $db->data['AwayTeam'] . " Scorecard.[/link]\n\n";
		}
	}
	
	if ($db->Exists("SELECT g.game_id AS game_id, ht.TeamAbbrev AS HomeTeam, at.TeamAbbrev AS AwayTeam, g.mom AS mom, g.mom2 AS mom2 FROM scorecard_game_details g, 
				teams ht, teams at WHERE g.week = $week AND g.season IN (SELECT SeasonID FROM seasons WHERE SeasonName LIKE '$yr%') AND g.league_id = 4 AND g.cancelled = 0 AND g.cancelledplay = 0 AND g.hometeam = ht.TeamID AND 
				g.awayteam = at.TeamID")) {
		$db->Query("SELECT g.game_id AS game_id, ht.TeamAbbrev AS HomeTeam, at.TeamAbbrev AS AwayTeam, g.mom AS mom, g.mom2 AS mom2 FROM scorecard_game_details g, 
				teams ht, teams at WHERE g.week = $week AND g.season IN (SELECT SeasonID FROM seasons WHERE SeasonName LIKE '$yr%') AND g.league_id = 4 AND g.cancelled = 0 AND g.cancelledplay = 0 AND g.hometeam = ht.TeamID AND 
				g.awayteam = at.TeamID");
		$fmtxt .= "[b]Twenty20[/b]\n\n";
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$fmtxt .=  "[b]" . $db->data['HomeTeam'] . " vs " . $db->data['AwayTeam'] . "[/b]\n";
			$gid = $db->data['game_id'];
			$mom = (empty($db->data['mom']) ? 0 : $db->data['mom']);
			$mom2 = (empty($db->data['mom2']) ? 0 : $db->data['mom2']);
			if($mom > 0) {
				$subdb->QueryRow("SELECT PlayerFName, PlayerLName from players where PlayerID = $mom");
				$subdb->BagAndTag();
				$fmtxt .=  "[b]Man of the Match: " . $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . "[/b] - ";
				$featured_player = array ("PlayerID" => $mom, "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if($subdb->Exists("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = $gid and player_id = $mom and runs > 10")) {
					$subdb->QueryRow("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = $gid and player_id = $mom and runs > 10");
					$subdb->BagAndTag();
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if($subdb->Exists("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = $gid and player_id = $mom")) {
					$subdb->QueryRow("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = $gid and player_id = $mom");
					$subdb->BagAndTag();
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['runs'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
			if($mom2 > 0) {
				$subdb->QueryRow("SELECT PlayerFName, PlayerLName from players where PlayerID = $mom2");
				$subdb->BagAndTag();
				$fmtxt .=  "[b]Man of the Match: " . $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . "[/b] - ";
				$featured_player = array ("PlayerID" => $mom2, "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if($subdb->Exists("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = " . $gid . " and player_id = " . $mom2 . " and runs > 10")) {
					$subdb->QueryRow("SELECT runs, balls, sixes, fours from scorecard_batting_details where game_id = " . $gid . " and player_id = " . $mom2 . " and runs > 10");
					$subdb->BagAndTag();
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if($subdb->Exists("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = " . $gid . " and player_id = " . $mom2)) {
					$subdb->QueryRow("SELECT overs, maidens, runs, wickets from scorecard_bowling_details where game_id = " . $gid . " and player_id = " . $mom2);
					$subdb->BagAndTag();
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['runs'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
            $pIds = "";
			$subdb->Query("SELECT bt.player_id AS player_id, pl.PlayerFName AS PlayerFName, pl.PlayerLName AS PlayerLName, bt.runs AS runs, bt.balls AS balls, bt.sixes AS sixes, 
							bt.fours AS fours, bl.overs as overs, bl.maidens AS maidens, bl.runs AS blruns, bl.wickets AS wickets from scorecard_batting_details bt 
							LEFT JOIN scorecard_bowling_details bl ON bl.player_id = bt.player_id AND bt.game_id = bl.game_id AND bl.wickets > 0 
							INNER JOIN players pl ON pl.PlayerID = bt.player_id where bt.game_id = $gid and bt.player_id != $mom and bt.player_id != $mom2 and bt.runs >= $T20runcriteria");
			for ($j=0; $j<$subdb->rows; $j++) {
				$subdb->GetRow($j);
				$pIds .= $subdb->data['player_id'] . ",";
				$fmtxt .= $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . " - ";
				$featured_player = array ("PlayerID" => $subdb->data['player_id'], "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if ($subdb->data['runs'] > 0) {
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if ($subdb->data['wickets'] > 0) {
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['blruns'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
			$pIds = rtrim($pIds, ',');
			$pIds = empty($pIds) ? 0 : $pIds;
			$subdb->Query("SELECT bl.player_id AS player_id, pl.PlayerFName AS PlayerFName, pl.PlayerLName AS PlayerLName, bt.runs AS runs, bt.balls AS balls, bt.sixes AS sixes, 
							bt.fours AS fours, bl.overs as overs, bl.maidens AS maidens, bl.runs AS blruns, bl.wickets AS wickets from 
							scorecard_bowling_details bl LEFT JOIN scorecard_batting_details bt ON bl.player_id = bt.player_id AND bt.game_id = bl.game_id 
							AND bt.runs > 10 INNER JOIN players pl ON pl.PlayerID = bl.player_id where bl.game_id = $gid 
							and bl.player_id != $mom and bl.player_id != $mom2 and bl.player_id NOT IN ($pIds) and bl.wickets >= $T20wicketcriteria");
			for ($j=0; $j<$subdb->rows; $j++) {
				$subdb->GetRow($j);
				$fmtxt .= $subdb->data['PlayerFName'] . " " . $subdb->data['PlayerLName'] . " - ";
				$featured_player = array ("PlayerID" => $subdb->data['player_id'], "PlayerFName" => $subdb->data['PlayerFName'], "PlayerLName" => $subdb->data['PlayerLName']);
				$featured_players[$x++] = $featured_player;
				$and = "";
				if ($subdb->data['runs'] > 0) {
					$fmtxt .=  $subdb->data['runs'] . " of " . $subdb->data['balls'] . " balls (" . $subdb->data['fours'] . "x4 and " . $subdb->data['sixes'] . "x6)";
					$and = " and ";
				}
				if ($subdb->data['wickets'] > 0) {
					$fmtxt .= $and . $subdb->data['overs'] . "-" . $subdb->data['maidens'] . "-" . $subdb->data['blruns'] . "-" . $subdb->data['wickets'];
				}
				$fmtxt .= ".\n";
			}
			$fmtxt .= "\n";
			$fmtxt .= "See CCL's [link=http://coloradocricket.org/scorecardfull.php?game_id=$gid&ccl_mode=4]" . $db->data['HomeTeam'] . " vs " . $db->data['AwayTeam'] . " Scorecard.[/link]\n\n";
		}
	}

	array_multisort (array_column($featured_players, 'PlayerLName'), SORT_ASC, $featured_players);
	$featured_players = array_map("unserialize", array_unique(array_map("serialize", $featured_players)));
	
	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
	echo "<tr>\n";
	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a POTW/Featured member</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	echo "  <tr>\n";
	echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";

	echo "<p class=\"10px\">Week: ";
	$db->Query("SELECT week, game_date, WEEKDAY (game_date) as dow, DATE_ADD(game_date, INTERVAL 1 DAY) AS nextday, DATE_ADD(game_date, INTERVAL -1 DAY) AS prevday FROM scorecard_game_details WHERE season IN (SELECT SeasonID FROM seasons WHERE SeasonName LIKE '$yr%') GROUP BY week");
	for ($x=0; $x<$db->rows; $x++) {
		$db->GetRow($x);
		$db->BagAndTag();
		$wk = $db->data['week'];
		$dt = $db->data['game_date'];
		$dow = $db->data['dow'];
		$nxtdt = $db->data['nextday'];
		$prvdt = $db->data['prevday'];
		if($dow == 5) {
			$wkoftxt = "Week of $dt and $nxtdt";
		}
		if($dow == 6) {
			$wkoftxt = "Week of $prvdt and $dt";
		}
	echo "    <a href=\"main.php?SID=$SID&action=$action&do=sadd&week=$wk\" alt=\"$wkoftxt\" title=\"$wkoftxt\">$wk</a> |\n";
	}
	echo "</p>\n";	
	
	echo "<p>select the season from the drop-down menu<br>\n";
	echo "<select name=\"season\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM seasons WHERE SeasonName LIKE '$yr%'")) {
// 19-Aug-2009
		$db->Query("SELECT * FROM seasons WHERE SeasonName LIKE '$yr%' ORDER BY SeasonName DESC");
//		$db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['SeasonID'] . "\">" . $db->data['SeasonName'] . "</option>\n";
		}
	}

		echo "</select></p>\n";

	echo "<p>select the player from the drop-down menu<br>\n";
	echo "<select name=\"FeaturedPlayer\">\n";
	echo "	<option value=\"\">Select a player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

// 19-Aug-2009;      22-Jun-2017 11:15pm Added PlayerFName too to the Order By
	//if ($db->Exists("SELECT * FROM players where isactive = 0")) {
	//	$db->Query("SELECT * FROM players where isactive = 0 ORDER BY PlayerLName, PlayerFName");
//	if ($db->Exists("SELECT * FROM players")) {
//		$db->Query("SELECT * FROM players ORDER BY PlayerLName");
	foreach ($featured_players as $featured_player) {
		if(!empty($featured_player['PlayerID'])) {
			echo "<option value=\"" . $featured_player['PlayerID'] . "\">" . $featured_player['PlayerLName'] . ", " . $featured_player['PlayerFName'] . "</option>\n";
		}
	}

	echo "</select></p>\n";

	echo "<p>enter the featured member detail<br><textarea name=\"FeaturedDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$fmtxt</textarea></p>\n";
	echo "<p><input type=\"submit\" value=\"add member\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$FeaturedPlayer,$FeaturedDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$fp = addslashes(trim($FeaturedPlayer));
	$fd = addslashes(trim($FeaturedDetail));
	$se = addslashes(trim($season));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());


	// all okay

	$db->Insert("INSERT INTO featuredmember (FeaturedPlayer,FeaturedDetail,added,season) VALUES ('$fp','$fd','$d','$se')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new featured member</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another POTW/featured member</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to POTW/featured member list</a></p>\n";
	} else {
		echo "<p>The member could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to POTW/featured member list</a></p>\n";
	}
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all players
// 19-Aug-2009;            22-Jun-2017 11:15pm Added PlayerFName too to the Order By
	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players where isactive = 0 ORDER BY PlayerLName, PlayerFName");
//	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players where isactive = 0 ORDER BY PlayerLName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$players[$db->data['PlayerID']] = $db->data['PlayerName'];
	}

	// get all seasons
// 19-Aug-2009
	$db->Query("SELECT * FROM seasons ORDER BY SeasonName DESC");
//	$db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
	}

	// query database

	$db->QueryRow("SELECT fm.*, pl.PlayerLName, pl.PlayerFName FROM featuredmember fm INNER JOIN players pl ON fm.FeaturedPlayer=pl.PlayerID WHERE fm.FeaturedID=$id");

	// setup variables

	$fd = htmlentities(stripslashes($db->data['FeaturedDetail']));
	$fp = htmlentities(stripslashes($db->data['FeaturedPlayer']));
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pln = htmlentities(stripslashes($db->data['PlayerLName']));

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit POTW/Featured Member</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"FeaturedPlayer\" value=\"$fp\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

	echo "<p>select the player from the drop-down menu<br>\n";
	echo "<select name=\"FeaturedPlayer\">\n";
	echo "	<option value=\"\">Select Player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($players as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['FeaturedPlayer'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

	echo "<p>select the season from the drop-down menu<br>\n";
	echo "<select name=\"season\">\n";
	echo "	<option value=\"\">Select a season</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($seasons as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['season'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

	echo "<p>enter the featured player detail<br><textarea name=\"FeaturedDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$fd</textarea></p>\n";

	echo "<p><input type=\"submit\" value=\"edit featured member\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$FeaturedPlayer,$FeaturedDetail,$season)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$fp = addslashes(trim($FeaturedPlayer));
	$fd = addslashes(trim($FeaturedDetail));
	$se = addslashes(trim($season));



// query database

	$db->Update("UPDATE featuredmember SET FeaturedPlayer='$fp', FeaturedDetail='$fd',season='$se' WHERE FeaturedID=$id");
		echo "<p>You have now updated that featured member.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the featured member listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update featured member some more</a></p>\n";
}

function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$db->QueryRow("SELECT pl.PlayerFName, pl.PlayerLName FROM featuredmember fm INNER JOIN players pl ON fm.FeaturedPlayer=pl.PlayerID WHERE fm.FeaturedID=$id");
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pln = htmlentities(stripslashes($db->data['PlayerLName']));

	// output

	echo "<p>Are you sure you wish to delete the Featured Member:</p>\n";
	echo "<p><b>$pln, $pfn</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}

function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that Featured Member.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM featuredmember WHERE FeaturedID=$id");
		echo "<p>You have now deleted that Featured Member.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the Featured Member listing</a></p>\n";
}



// main program

if (!$USER['flags'][$f_featuredmember_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Featured Member Administration</b></p>\n";

if(isset($_GET['doit'])) {
	$doit = $_GET['doit'];
} else if(isset($_POST['doit'])) {
	$doit = $_POST['doit'];
}

if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
}
else {
	$do = '';
}

switch($do) {
case "byseason":
	show_main_menu_season($db,$_GET['season'],$_GET['sename']);
	break;
case "sadd":
	if (!isset($doit)) {
		if(!isset($_GET['week'])) {
			select_week($db);
		} else {
			add_category_form($db, $_GET['week']);
		}
	}
	else {
		do_add_category($db,$_POST['FeaturedPlayer'],$_POST['FeaturedDetail'],$_POST['season'],'');
	}
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$_GET['id']);
	else do_delete_category($db,$_GET['id'],$_GET['doit']);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$_GET['id']);
	else do_update_category($db,$_POST['id'],$_POST['FeaturedPlayer'],$_POST['FeaturedDetail'],$_POST['season'],'');
	break;
default:
	show_main_menu($db);
	break;
}

?>
