<?php

	require ("general.config.inc");
    require ("class.mysql.inc");
    require ("class.fasttemplate.inc");
    require ("general.functions.inc");
	
//------------------------------------------------------------------------------
// readscorecard 1.0
//
// (c) Bodhayan Chakraborty - bodhayanc@gmail.com
//------------------------------------------------------------------------------


function read_scorecard($db)
{
	$dir = "/home/colorad2/public_ftp/incoming/scorecards/";
	$processed_dir = "/home/colorad2/public_ftp/incoming/processed/";
	$scorecardFiles = scandir($dir);
	$season_start_date = "04/07/2018";
	global $email_content;
	global $email_subject;
	for($x = 0; $x < count($scorecardFiles); $x++) {
		if(!is_dir($dir.$scorecardFiles[$x]) && pathinfo($scorecardFiles[$x])['extension'] == 'xml') {
			$renamed_filename = $scorecardFiles[$x] . "." . time();
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($dir.$scorecardFiles[$x]);
			$email_subject .= $scorecardFiles[$x] . " ";
			$email_content .= $dir.$scorecardFiles[$x] . "<br>";
			$matchDetail = $xmlDoc->getElementsByTagName('MatchDetail');
			$cc_game_id = $matchDetail->item(0)->getAttribute('game_id');
			$email_content .= "CricClub match id: $cc_game_id<br>";
			$cc_hometeam_id = $matchDetail->item(0)->getAttribute('home_team_id');
			$cc_hometeam = $matchDetail->item(0)->getAttribute('home_team');
			$ccl_hometeam = str_replace(" CC", " Cricket Club", $cc_hometeam);
			if ($db->Exists("SELECT * FROM teams WHERE TeamName = '$ccl_hometeam'")) {
				$db->QueryRow("SELECT * FROM teams WHERE TeamName = '$ccl_hometeam'");
				$db->BagAndTag();

				$ccl_hometeam_id = $db->data['TeamID'];
				$ccl_hometeam_abbrev = $db->data['TeamAbbrev'];
				$ccl_homeclub_id = $db->data['ClubID'];
			} else {
				$email_content .= "Team not found with name: $ccl_hometeam. Please contact the administrator to create the team in coloradocricket.org.<br>";
				rename($dir.$scorecardFiles[$x], $processed_dir.$renamed_filename);
				return;
			}
			$email_content .= "CricClub hometeam: $cc_hometeam<br>";
			$email_content .= "CricClub hometeam id: $cc_hometeam_id<br>";
			$email_content .= "CCL hometeam: $ccl_hometeam<br>";
			$email_content .= "CCL hometeam id: $ccl_hometeam_id<br>";
			$cc_awayteam_id = $matchDetail->item(0)->getAttribute('away_team_id');
			$cc_awayteam = $matchDetail->item(0)->getAttribute('away_team');
			$ccl_awayteam = str_replace(" CC", " Cricket Club", $cc_awayteam);
			if ($db->Exists("SELECT * FROM teams WHERE TeamName = '$ccl_awayteam'")) {
				$db->QueryRow("SELECT * FROM teams WHERE TeamName = '$ccl_awayteam'");
				$db->BagAndTag();

				$ccl_awayteam_id = $db->data['TeamID'];
				$ccl_awayteam_abbrev = $db->data['TeamAbbrev'];
				$ccl_awayclub_id = $db->data['ClubID'];
			} else {
				$email_content .= "Team not found with name: $ccl_awayteam. Please contact the administrator to create the team in coloradocricket.org.<br>";
				rename($dir.$scorecardFiles[$x], $processed_dir.$renamed_filename);
				return;
			}
			$email_content .= "CricClub awayteam: $cc_awayteam<br>";
			$email_content .= "CricClub awayteam id: $cc_awayteam_id<br>";
			$email_content .= "CCL awayteam: $ccl_awayteam<br>";
			$email_content .= "CCL awayteam id: $ccl_awayteam_id<br>";
			
			//$cc_groundname = $matchDetail->item(0)->getAttribute('venue_name');
			//$email_content .= "CricClub ground name: $cc_groundname<br>";
			$cc_competition_name = $matchDetail->item(0)->getAttribute('competition_name');
			$email_content .= "CricClub competition name: $cc_competition_name<br>";
			if ($db->Exists("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'")) {
				$db->QueryRow("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'");
				$db->BagAndTag();

				$ccl_season_id = $db->data['SeasonID'];
			} else {
				$email_content .= "Season not found with name: $cc_competition_name. Please contact the administrator to create the season in coloradocricket.org.<br>";
				rename($dir.$scorecardFiles[$x], $processed_dir.$renamed_filename);
				return;
				//$db->Insert("INSERT INTO seasons (SeasonName) VALUES('$cc_competition_name')");
				//$db->QueryRow("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'");
				//$db->BagAndTag();

				//$ccl_season_id = $db->data['SeasonID'];
			}
			if(strpos($cc_competition_name, 'Twenty20') !== FALSE) {
				$ccl_league_id = 4;
			} else {
				$ccl_league_id = 1;
			}
			
			$email_content .= "CCL season id: $ccl_season_id<br>";
			$email_content .= "CCL league id: $ccl_league_id<br>";
			$cc_game_date = $matchDetail->item(0)->getAttribute('game_date');
			$email_content .= "CricClub game date: $cc_game_date<br>";
			
			$cc_game_date_formatted = date_create_from_format('m/d/Y', $cc_game_date);
			$ccl_game_date = $cc_game_date_formatted->format('Y-m-d');
			$season_start_date_formatted = date_create_from_format('m/d/Y', $season_start_date);
			$week = floor(date_diff($cc_game_date_formatted, $season_start_date_formatted)->format('%a') / 7) + 1;
			$email_content .= "Week: $week<br>";
			$cc_venue_id = $matchDetail->item(0)->getAttribute('venue_id');
			$ccl_ground_id = map_cc_to_ccl_ground($cc_venue_id);
			$email_content .= "ccl_ground_id: $ccl_ground_id<br>";
			$cc_total_overs = $matchDetail->item(0)->getAttribute('total_overs');
			$email_content .= "cc_total_overs: $cc_total_overs<br>";
			
			//Find correct home or away teams from the schedule table. The CricClubs information may be wrong.
			if ($db->Exists("SELECT ht.TeamID AS HomeTeamID, ht.TeamAbbrev AS HomeTeamAbbrev, ht.ClubID AS HomeClubID, at.TeamID AS AwayTeamID, at.TeamAbbrev AS AwayTeamAbbrev, at.ClubID AS AwayClubID FROM schedule sch 
				INNER JOIN teams ht ON sch.hometeam = ht.TeamID 
				INNER JOIN teams at ON sch.awayteam = at.TeamID 
				WHERE (awayteam = $ccl_hometeam_id OR awayteam = $ccl_awayteam_id) 
				AND (hometeam = $ccl_hometeam_id OR hometeam = $ccl_awayteam_id) 
				AND date = '$ccl_game_date'")) {
				$db->QueryRow("SELECT ht.TeamID AS HomeTeamID, ht.TeamAbbrev AS HomeTeamAbbrev, ht.ClubID AS HomeClubID, at.TeamID AS AwayTeamID, at.TeamAbbrev AS AwayTeamAbbrev, at.ClubID AS AwayClubID FROM schedule sch 
				INNER JOIN teams ht ON sch.hometeam = ht.TeamID 
				INNER JOIN teams at ON sch.awayteam = at.TeamID 
				WHERE (awayteam = $ccl_hometeam_id OR awayteam = $ccl_awayteam_id) 
				AND (hometeam = $ccl_hometeam_id OR hometeam = $ccl_awayteam_id) 
				AND date = '$ccl_game_date'");
				$db->BagAndTag();

				if($ccl_hometeam_id != $db->data['HomeTeamID']) {
					$ccl_hometeam_id = $db->data['HomeTeamID'];
					$ccl_hometeam_abbrev = $db->data['HomeTeamAbbrev'];
					$ccl_homeclub_id = $db->data['HomeClubID'];
					$ccl_awayteam_id = $db->data['AwayTeamID'];
					$ccl_awayteam_abbrev = $db->data['AwayTeamAbbrev'];
					$ccl_awayclub_id = $db->data['AwayClubID'];
					$temp_id = $cc_hometeam_id;
					$cc_hometeam_id = $cc_awayteam_id;
					$cc_awayteam_id = $temp_id;
					$temp_team = $cc_hometeam;
					$cc_hometeam = $cc_awayteam;
					$cc_awayteam = $temp_team;
					$email_content .= "cc_hometeam_id: $cc_hometeam_id<br>";
					$email_content .= "cc_awayteam_id: $cc_awayteam_id<br>";
				}
			} else {
				$email_content .= "Home and away team informations are not matching or the date is not matching with the CCL schedule. Please contact the CCL administrator.<br>";
				rename($dir.$scorecardFiles[$x], $processed_dir.$renamed_filename);
				return;
			}
			$cc_toss_team_id = $matchDetail->item(0)->getAttribute('toss_team_id');
			if($cc_toss_team_id == $cc_hometeam_id) {
				$ccl_toss_team_id = $ccl_hometeam_id;
			} else {
				$ccl_toss_team_id = $ccl_awayteam_id;
			}
			$email_content .= "CricClub toss team id: $cc_toss_team_id<br>";
			$email_content .= "CCL toss team id: $ccl_toss_team_id<br>";
			$cc_result = $matchDetail->item(0)->getAttribute('result');
			$cc_winner_id = $matchDetail->item(0)->getAttribute('winner');
			$result_won_id = 0;
			$ccl_result = "";
			$email_content .= "CricClub result: $cc_result<br>";
			if($cc_winner_id == $cc_hometeam_id) {
				$result_won_id = $ccl_hometeam_id;
				$result_won_abbrev = $ccl_hometeam_abbrev;
			} else if($cc_winner_id == $cc_awayteam_id) {
				$result_won_id = $ccl_awayteam_id;
				$result_won_abbrev = $ccl_awayteam_abbrev;
			}
			if($result_won_id != 0) {
				$run_won = trim(get_string_between($cc_result, "won by", "Run"));
				$email_content .= "run_won: $run_won<br>";
				if($run_won == "") {
					$wicket_won = trim(get_string_between($cc_result, "won by", "Wkt"));
					if($wicket_won > 1) {
						$ccl_result = $result_won_abbrev . " won by " . $wicket_won . " wickets";
					} else {
						$ccl_result = $result_won_abbrev . " won by " . $wicket_won . " wicket";
					}
				} else if ($run_won > 1) {
					$ccl_result = $result_won_abbrev . " won by " . $run_won . " runs";
				} else {
					$ccl_result = $result_won_abbrev . " won by " . $run_won . " run";
				}
			}
			
			$cc_mom = $matchDetail->item(0)->getAttribute('man_of_the_match');
			$cc_ump1 = $matchDetail->item(0)->getAttribute('umpire1');
			$cc_ump2 = $matchDetail->item(0)->getAttribute('umpire2');
			$email_content .= "cc_mom: $cc_mom<br>";
			$email_content .= "cc_ump1: $cc_ump1<br>";
			$email_content .= "cc_ump2: $cc_ump2<br>";
			$ump1_split =  explode(" ", $cc_ump1);
			if(count($ump1_split) > 2) {
				$cc_ump1_ln = $ump1_split[2];
				$cc_ump1_fn = $ump1_split[0] . " " . $ump1_split[1];
			} else if(count($ump1_split) > 1) {
				$cc_ump1_ln = $ump1_split[1];
				$cc_ump1_fn = $ump1_split[0];
			} else {
				$cc_ump1_ln = "";
				$cc_ump1_fn = $ump1_split[0];
			}
			$ump2_split =  explode(" ", $cc_ump2);
			if(count($ump2_split) > 2) {
				$cc_ump2_ln = $ump2_split[2];
				$cc_ump2_fn = $ump2_split[0] . " " . $ump2_split[1];
			} else if(count($ump2_split) > 1) {
				$cc_ump2_ln = $ump2_split[1];
				$cc_ump2_fn = $ump2_split[0];
			} else {
				$cc_ump2_ln = "";
				$cc_ump2_fn = $ump2_split[0];
			}
			$email_content .= "ump1 = $cc_ump1_fn $cc_ump1_ln<br>";
			$email_content .= "ump2 = $cc_ump2_fn $cc_ump2_ln<br>";
			$ccl_ump1_id = find_player_id_by_name($cc_ump1_fn, $cc_ump1_ln);
			$ccl_ump2_id = find_player_id_by_name($cc_ump2_fn, $cc_ump2_ln);
			//Parse the XML and get all batting, bowling, extras, etc. from both the innings.
			unset($firstIBatsmen, $firstIBowlers, $firstFows, $secondIBatsmen, $secondIBowlers, $secondFows, $hometeamplayers, $awayteamplayers);
			get_batting_bowling_info($xmlDoc->getElementsByTagName('Innings'), $firstIBatsmen, $firstIBowlers, $firstExtras, $firstFows, $firstTotal, $secondIBatsmen, $secondIBowlers, $secondExtras, $secondFows, $secondTotal, $cc_batting_first_id, $cc_batting_second_id);
			
			foreach ($firstIBatsmen as $firstIBatsman) {
				$email_content .= "First Innings Batsman " . $firstIBatsman->getAttribute('id')."<br>";
			}
			foreach ($firstIBowlers as $firstIBowler) {
				$email_content .= "First Innings Bowler " . $firstIBowler->getAttribute('id')."<br>";
			}
			foreach ($secondIBatsmen as $secondIBatsman) {
				$email_content .= "Second Innings Batsman " . $secondIBatsman->getAttribute('id')."<br>";
			}
			foreach ($secondIBowlers as $secondIBowler) {
				$email_content .= "Second Innings Bowler " . $secondIBowler->getAttribute('id')."<br>";
			}
			
			$teams = $xmlDoc->getElementsByTagName('Team');
			foreach ($teams as $team) {
				$email_content .= "cc_hometeam_id: $cc_hometeam_id<br>";
				$email_content .= "cc_awayteam_id: $cc_awayteam_id<br>";
				$email_content .= "team_id: " . $team->getAttribute('team_id') . "<br>";
			
				if($team->getAttribute('team_id') == $cc_hometeam_id) {
					$cc_home_captain = $team->getAttribute('captain');
					$cc_home_vcaptain = $team->getAttribute('vice_captain');
					$cc_home_wk = $team->getAttribute('wicket_keeper');
					foreach($team->childNodes as $teamChild) {
						if($teamChild->nodeName == 'Player') {
							if(!isset($hometeamplayers)) {
								$hometeamplayers = array($teamChild);
							} else {
								array_push($hometeamplayers, $teamChild);
							}
						}
					}
					sync_cc_and_ccl_players($hometeamplayers, $ccl_homeclub_id, $ccl_hometeam_id);
				} else if($team->getAttribute('team_id') == $cc_awayteam_id) {
					$cc_away_captain = $team->getAttribute('captain');
					$cc_away_vcaptain = $team->getAttribute('vice_captain');
					$cc_away_wk = $team->getAttribute('wicket_keeper');
					foreach($team->childNodes as $teamChild) {
						if($teamChild->nodeName == 'Player') {
							if(!isset($awayteamplayers)) {
								$awayteamplayers = array($teamChild);
							} else {
								array_push($awayteamplayers, $teamChild);
							}
						}
					}
					sync_cc_and_ccl_players($awayteamplayers, $ccl_awayclub_id, $ccl_awayteam_id);
				}
			}
			$email_content .= "HOME TEAM:<br>";
			foreach ($hometeamplayers as $player) {
				$email_content .= "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."<br>";
			}
			
			$email_content .= "AWAY TEAM:<br>";
			foreach ($awayteamplayers as $player) {
				$email_content .= "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."<br>";
			}
			
			$email_content .= "cc_batting_first_id: $cc_batting_first_id<br>";
			$email_content .= "cc_hometeam_id: $cc_hometeam_id<br>";
			$email_content .= "cc_batting_second_id: $cc_batting_second_id<br>";
			$email_content .= "cc_awayteam_id: $cc_awayteam_id<br>";
			$email_content .= "ccl_hometeam_id: $ccl_hometeam_id<br>";
			$email_content .= "ccl_awayteam_id: $ccl_awayteam_id<br>";
			if($cc_batting_first_id == $cc_hometeam_id) {
				$ccl_batting_first_id = $ccl_hometeam_id;
			} else {
				$ccl_batting_first_id = $ccl_awayteam_id;
			}
			if($cc_batting_second_id == $cc_hometeam_id) {
				$ccl_batting_second_id = $ccl_hometeam_id;
			} else {
				$ccl_batting_second_id = $ccl_awayteam_id;
			}

			$email_content .= "ccl_batting_first_id: $ccl_batting_first_id<br>";
			$email_content .= "ccl_batting_second_id: $ccl_batting_second_id<br>";
			
			$ccl_mom = get_ccl_player_id($cc_mom);
			$email_content .= "ccl_mom: $ccl_mom<br>";
			$ccl_home_captain = get_ccl_player_id($cc_home_captain);
			$email_content .= "ccl_home_captain: $ccl_home_captain<br>";
			$ccl_home_vcaptain = get_ccl_player_id($cc_home_vcaptain);
			$email_content .= "ccl_home_vcaptain: $ccl_home_vcaptain<br>";
			$ccl_home_wk = get_ccl_player_id($cc_home_wk);
			$email_content .= "ccl_home_wk: $ccl_home_wk<br>";
			$ccl_away_captain = get_ccl_player_id($cc_away_captain);
			$email_content .= "ccl_away_captain: $ccl_away_captain<br>";
			$ccl_away_vcaptain = get_ccl_player_id($cc_away_vcaptain);
			$email_content .= "ccl_away_vcaptain: $ccl_away_vcaptain<br>";
			$ccl_away_wk = get_ccl_player_id($cc_away_wk);
			$email_content .= "ccl_away_wk: $ccl_away_wk<br>";
			
			
			update_game_details_table($cc_game_id, $ccl_league_id, $ccl_season_id, $week, $ccl_awayteam_id, $ccl_hometeam_id, $ccl_toss_team_id, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_ground_id, $ccl_game_date, $ccl_result, $result_won_id, $cc_total_overs, $ccl_mom, $ccl_ump1_id, $ccl_ump2_id, $ccl_home_captain, $ccl_home_vcaptain, $ccl_home_wk, $ccl_away_captain, $ccl_away_vcaptain, $ccl_away_wk, $ccl_game_id);
			
			update_batting_details_table($ccl_game_id, $firstIBatsmen, $secondIBatsmen, $hometeamplayers, $awayteamplayers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_hometeam_id, $ccl_awayteam_id, $ccl_season_id);
			
			update_bowling_details_table($ccl_game_id, $firstIBowlers, $secondIBowlers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_season_id);
			
			update_extras_details_table($ccl_game_id, $firstExtras, $secondExtras);
			
			update_fow_details_table($ccl_game_id, $firstFows, $secondFows);
			
			update_total_details_table($ccl_game_id, $firstTotal, $secondTotal, $ccl_batting_first_id, $ccl_batting_second_id);
			rename($dir.$scorecardFiles[$x], $processed_dir.$renamed_filename);
			$email_content .= "<p>Scorecard import complete for: " . $scorecardFiles[$x] . " and renamed to $renamed_filename</p>";
		}
	}
}

//This function checks all the players whether that player exists in CCL DB or not. If it finds the player, it syncs the first name, last name and email from CricClubs.
//If it doesn't find the player, it adds the player as a new player in CCL DB.
function sync_cc_and_ccl_players($players, $ccl_club_id, $ccl_team_id) {
	global $db;
	global $email_content;
	$parent_child_team_map = array(62 => 5, 70 => 5, 66 => 8, 73 => 8, 71 => 54, 72 => 54);
	$ccl_parent_team_id = $ccl_team_id;
	foreach ($parent_child_team_map as $child_team=>$parent_team) {
		$email_content .= "parent team for $child_team is $parent_team<br>";
		if($ccl_team_id == $child_team) {
			$ccl_parent_team_id = $parent_team;
		}
	}	
	foreach ($players as $player) {
		if ($db->Exists("SELECT * FROM players WHERE cricclubs_player_id = " . $player->getAttribute('id'))) {
			$db->QueryRow("SELECT * FROM players WHERE cricclubs_player_id = " . $player->getAttribute('id'));
			$db->BagAndTag();

			$ccl_player_id = $db->data['PlayerID'];
			$ccl_player_first_name = $db->data['PlayerFName'];
			$ccl_player_last_name = $db->data['PlayerLName'];
			$ccl_player_team = $db->data['PlayerTeam'];
			$ccl_player_team2 = $db->data['PlayerTeam2'];
			$isactive = $db->data['isactive'];
			if($isactive == 1) {
				$email_content .= "Reactivating player $ccl_player_first_name $ccl_player_last_name. Updating CCL Data!!<br>";
				$db->Update("UPDATE players SET isactive = 0 WHERE PlayerID = $ccl_player_id");
			}
			if($ccl_player_first_name != $player->getAttribute('player_first_name') || $ccl_player_last_name != $player->getAttribute('player_last_name')) {
				$email_content .= "Mismatch between CricClubs and CCL player name. NOT updating for the time being!!<br>";
				$email_content .= "ccl_player_first_name: $ccl_player_first_name<br>";
				$email_content .= "ccl_player_last_name: $ccl_player_last_name<br>";
				$email_content .= "CricClubs player_first_name: " . $player->getAttribute('player_first_name') . "<br>";
				$email_content .= "CricClubs player_last_name: " . $player->getAttribute('player_last_name') . "<br>";
				//$db->Update("UPDATE players SET PlayerLName = '" . $player->getAttribute('player_last_name') . "', PlayerFName = '" . $player->getAttribute('player_first_name') . "' WHERE PlayerID = $ccl_player_id");
			}
			if($ccl_player_team != $ccl_team_id && $ccl_player_team2 != $ccl_team_id) {
				$email_content .= "Mismatch between CricClubs and CCL player teams for player " . $player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name') . " Updating CCL Data!!<br>";
				$email_content .= "ccl_player_team: $ccl_player_team<br>";
				$email_content .= "ccl_player_team2: $ccl_player_team2<br>";
				$email_content .= "ccl_team_id: $ccl_team_id<br>";
				$email_content .= "ccl_parent_team_id: $ccl_parent_team_id<br>";
				
				if($ccl_parent_team_id == $ccl_team_id) {
					$db->Update("UPDATE players SET PlayerTeam = '$ccl_parent_team_id' WHERE PlayerID = $ccl_player_id");
				} else {
					$db->Update("UPDATE players SET PlayerTeam = '$ccl_parent_team_id', PlayerTeam2 = '$ccl_team_id' WHERE PlayerID = $ccl_player_id");
				}
			}
		} else {
			$email_content .= "New Player " . $player->getAttribute('player_first_name') . " " . $player->getAttribute('player_last_name') . "!!<br>";
			if($ccl_parent_team_id != $ccl_team_id) {
				$db->Insert("INSERT INTO players (PlayerLName,PlayerFName,PlayerClub,PlayerTeam,PlayerTeam2,PlayerEmail,cricclubs_player_id) VALUES ('".$player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name')."','$ccl_club_id','$ccl_parent_team_id','$ccl_team_id','".$player->getAttribute('player_email_id')."','".$player->getAttribute('id')."')");
			} else {
				$db->Insert("INSERT INTO players (PlayerLName,PlayerFName,PlayerClub,PlayerTeam,PlayerEmail,cricclubs_player_id) VALUES ('".$player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name')."','$ccl_club_id','$ccl_team_id','".$player->getAttribute('player_email_id')."','".$player->getAttribute('id')."')");
			}
			if ($db->a_rows != -1) {
				$email_content .= "<p>A new player has been added</p><br>";
			} else {
				$email_content .= "<p>The player could not be added to the database at this time.</p><br>";
			}
			$db->QueryRow("SELECT * FROM players WHERE cricclubs_player_id = " . $player->getAttribute('id'));
			$ccl_player_id = $db->data['PlayerID'];
		}
		$player->setAttribute('ccl_player_id', $ccl_player_id);
		//$email_content .= "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."<br>";
	}
}

function get_batting_bowling_info($Innings, &$firstIBatsmen, &$firstIBowlers, &$firstExtras, &$firstFows, &$firstTotal, &$secondIBatsmen, &$secondIBowlers, &$secondExtras, &$secondFows, &$secondTotal, &$cc_batting_first_id, &$cc_batting_second_id) {
	global $email_content;
	
	foreach ($Innings as $Inning) {
		$id = $Inning->getAttribute('id');
		$email_content .= "Inning: $id<br>";
		if($id == 1) {
			bat_bowl_extra_fow_total_details($Inning, $firstIBatsmen, $firstIBowlers, $firstExtras, $firstFows, $firstTotal, $cc_batting_first_id);
			$email_content .= "leg_byes: " . $firstExtras->getAttribute('leg_byes') . "<br>";
			$email_content .= "firstTotal: " . $firstTotal->getAttribute('runs_scored') . "<br>";
		} else {
			bat_bowl_extra_fow_total_details($Inning, $secondIBatsmen, $secondIBowlers, $secondExtras, $secondFows, $secondTotal, $cc_batting_second_id);
			$email_content .= "leg_byes: " . $secondExtras->getAttribute('leg_byes') . "<br>";
			$email_content .= "secondTotal: " . $secondTotal->getAttribute('runs_scored') . "<br>";
		}
	}
}

function bat_bowl_extra_fow_total_details($Inning, &$batsmen, &$bowlers, &$extras, &$fows, &$total, &$batting_team_id) {
	$batting_team_id = $Inning->getAttribute('batting_team_id');
	foreach($Inning->childNodes as $childInning) {
		if($childInning->nodeName == 'Batsmen') {
			foreach($childInning->childNodes as $childBatsmen) {
				if($childBatsmen->nodeName == 'Batsman') {
					if(!isset($batsmen)) {
						$batsmen = array($childBatsmen);
					} else {
						array_push($batsmen, $childBatsmen);
					}
				}
			}
		}
		if($childInning->nodeName == 'Bowlers') {
			foreach($childInning->childNodes as $childBowlers) {
				if($childBowlers->nodeName == 'Bowler') {
					if(!isset($bowlers)) {
						$bowlers = array($childBowlers);
					} else {
						array_push($bowlers, $childBowlers);
					}
				}
			}
		}
		if($childInning->nodeName == 'Extras') {
			$extras = $childInning;
		}
		if($childInning->nodeName == 'FallofWickets') {
			foreach($childInning->childNodes as $childFows) {
				if($childFows->nodeName == 'FallofWicket') {
					if(!isset($fows)) {
						$fows = array($childFows);
					} else {
						array_push($fows, $childFows);
					}
				}
			}
		}
		if($childInning->nodeName == 'Total') {
			$total = $childInning;
		}
	}
}

function update_game_details_table($cc_game_id, $ccl_league_id, $ccl_season_id, $week, $ccl_awayteam_id, $ccl_hometeam_id, $ccl_toss_team_id, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_ground_id, $ccl_game_date, $result, $result_won_id, $cc_total_overs, $ccl_mom, $ccl_ump1_id, $ccl_ump2_id, $ccl_home_captain, $ccl_home_vcaptain, $ccl_home_wk, $ccl_away_captain, $ccl_away_vcaptain, $ccl_away_wk, &$ccl_game_id) {
	global $db;
	global $email_content;
	
	$report_link = "https://www.cricclubs.com/ColoradoCricket/viewScorecard.do?matchId=$cc_game_id";
	if ($db->Exists("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id")) {
		$db->QueryRow("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id");
		$db->BagAndTag();

		$ccl_game_id = $db->data['game_id'];
		$db->Update("UPDATE scorecard_game_details SET league_id = '$ccl_league_id', season = '$ccl_season_id', week = '$week', awayteam = '$ccl_awayteam_id', hometeam = '$ccl_hometeam_id', toss_won_id = '$ccl_toss_team_id', batting_first_id = '$ccl_batting_first_id', batting_second_id = '$ccl_batting_second_id', ground_id = '$ccl_ground_id', game_date = '$ccl_game_date',result = '$result', result_won_id = '$result_won_id', maxovers = '$cc_total_overs', mom = '$ccl_mom', umpire1 = '$ccl_ump1_id', umpire2 = '$ccl_ump2_id', report = '$report_link', HOMETEAM_CAPTAIN = '$ccl_home_captain', HOMETEAM_VCAPTAIN = '$ccl_home_vcaptain', HOMETEAM_WK = '$ccl_home_wk', AWAYTEAM_CAPTAIN = '$ccl_away_captain', AWAYTEAM_VCAPTAIN = '$ccl_away_vcaptain', AWAYTEAM_WK = '$ccl_away_wk' WHERE game_id = $ccl_game_id");
		if ($db->a_rows != -1) {
			$email_content .= "<p>The scorecard has been updated</p><br>";
		} else {
			$email_content .= "<p>The scorecard could not be updated at this time.</p><br>";
		}
	} else {
		$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,toss_won_id,batting_first_id, batting_second_id,ground_id,game_date,result,result_won_id,maxovers,mom,umpire1,umpire2,HOMETEAM_CAPTAIN, HOMETEAM_VCAPTAIN, HOMETEAM_WK, AWAYTEAM_CAPTAIN, AWAYTEAM_VCAPTAIN, AWAYTEAM_WK,isactive,cricclubs_game_id,report) VALUES ('$ccl_league_id','$ccl_season_id','$week','$ccl_awayteam_id','$ccl_hometeam_id','$ccl_toss_team_id','$ccl_batting_first_id','$ccl_batting_second_id','$ccl_ground_id','$ccl_game_date','$result','$result_won_id','$cc_total_overs','$ccl_mom','$ccl_ump1_id','$ccl_ump2_id', '$ccl_home_captain', '$ccl_home_vcaptain', '$ccl_home_wk', '$ccl_away_captain', '$ccl_away_vcaptain', '$ccl_away_wk',0,$cc_game_id,'$report_link')");
		if ($db->a_rows != -1) {
			$email_content .= "<p>A new scorecard has been added</p><br>";
		} else {
			$email_content .= "<p>The scorecard could not be added to the database at this time.</p><br>";
		}
		$db->QueryRow("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id");
		$db->BagAndTag();
		$ccl_game_id = $db->data['game_id'];
	}
}

function update_batting_details_table($ccl_game_id, $firstIBatsmen, $secondIBatsmen, $hometeamplayers, $awayteamplayers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_hometeam_id, $ccl_awayteam_id, $ccl_season_id) {
	global $db, $email_content;
	$db->Delete("DELETE FROM scorecard_batting_details WHERE game_id = $ccl_game_id");
	update_batting_by_innings($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $firstIBatsmen, 1);
	update_batting_by_innings($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $secondIBatsmen, 2);
	if($ccl_batting_first_id == $ccl_hometeam_id) {
		update_did_not_bat($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $hometeamplayers, $firstIBatsmen, 1);
	}
	if($ccl_batting_first_id == $ccl_awayteam_id) {
		update_did_not_bat($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $awayteamplayers, $firstIBatsmen, 1);
	}
	if($ccl_batting_second_id == $ccl_hometeam_id) {
		update_did_not_bat($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $hometeamplayers, $secondIBatsmen, 2);
	}
	if($ccl_batting_second_id == $ccl_awayteam_id) {
		update_did_not_bat($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $awayteamplayers, $secondIBatsmen, 2);
	}
}

function update_batting_by_innings($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $batsmen, $innings_id) {
	global $db, $email_content;
	foreach($batsmen as $batsman) {
		$player_id = get_ccl_player_id($batsman->getAttribute('id'));
		$batpos = $batsman->getAttribute('order');
		$how_out = $bowler = $assist = $assist2 = $notout = 0;
		$wicket_taker1 = get_ccl_player_id($batsman->getAttribute('wicket_taker1'));
		$wicket_taker2 = get_ccl_player_id($batsman->getAttribute('wicket_taker2'));
		$outtype = $batsman->getAttribute('out_type');
		$email_content .= "Out type: $outtype wicket_taker1: $wicket_taker1 and wicket_taker2: $wicket_taker2";
			
		switch ($outtype) {
		case ""://Not Out
			$how_out = 2;
			$notout = 1;
			break;
		case "b"://Bowled
			$how_out = 3;
			$bowler = $wicket_taker1;
			break;
		case "ct"://Caught
			if($wicket_taker1 == $wicket_taker2) {//Caught and Bowled
				$how_out = 5;
				$bowler = $wicket_taker1;
			} else {//Caught
				$how_out = 4;
				$bowler = $wicket_taker1;
				$assist = $wicket_taker2;
			}
			break;
		case "hw"://Hit wicket
			$how_out = 6;
			$bowler = $wicket_taker1;
			break;
		case "lbw"://LBW
			$how_out = 7;
			$bowler = $wicket_taker1;
			break;
		case "rt"://Retired Hurt
			$how_out = 8;
			break;
		case "ro"://Run Out
			$how_out = 9;
			if($wicket_taker1 != "" && $wicket_taker2 != "") {
				$assist = $wicket_taker1;
				$assist2 = $wicket_taker2;
			} else if ($wicket_taker1 != ""){
				$assist = $wicket_taker1;
			} else{
				$assist = $wicket_taker2;
			}
			break;
		case "st"://Stumped
			$how_out = 10;
			$bowler = $wicket_taker1;
			$assist = $wicket_taker2;
			break;
		case "rto"://Retired Out
			$how_out = 16;
			$bowler = $wicket_taker1;
			break;
		case "ctw"://caught Behind
			$how_out = 17;
			$bowler = $wicket_taker1;
			$assist = $wicket_taker2;
			break;
		default:
			$how_out = 1;
			break;
		}
		
		$runs = $batsman->getAttribute('runs_scored');
		$balls = $batsman->getAttribute('balls_faced');
		$fours = $batsman->getAttribute('fours_scored');
		$sixes = $batsman->getAttribute('sixes_scored');
		if($innings_id == 1) {
			$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, assist2, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '1','$player_id','$batpos','$how_out','$runs','$assist','$assist2','$bowler','$balls','$fours','$sixes','$notout','$ccl_batting_first_id','$ccl_batting_second_id')");
		} else {
			$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, assist2, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '2','$player_id','$batpos','$how_out','$runs','$assist','$assist2','$bowler','$balls','$fours','$sixes','$notout','$ccl_batting_second_id','$ccl_batting_first_id')");
		}
		if ($db->a_rows != -1) {
			$email_content .= "A batting row has been added<br>";
		} else {
			$email_content .= "<p>Batting record could not be added to the database at this time for ". $batsman->getAttribute('id') . "</p><br>";
		}
	}

}

function update_did_not_bat($ccl_game_id, $ccl_season_id, $ccl_batting_first_id, $ccl_batting_second_id, $players, $batsmen, $innings_id) {
	global $db, $email_content;
	$batpos = count($batsmen) + 1;
	foreach($players as $player) {
		if($batpos < 12) {
			$did_not_bat = true;
			foreach($batsmen as $batsman) {
				if($player->getAttribute('id') == $batsman->getAttribute('id')) {
					$did_not_bat = false;
				}
			}
			if($did_not_bat) {
				$player_id = get_ccl_player_id($player->getAttribute('id'));
				if($innings_id == 1) {
					$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, assist2, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '1','$player_id','$batpos','1','0','0','0','0','0','0','0','0','$ccl_batting_first_id','$ccl_batting_second_id')");
				} else {
					$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, assist2, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '2','$player_id','$batpos','1','0','0','0','0','0','0','0','0','$ccl_batting_second_id','$ccl_batting_first_id')");
				}
				if ($db->a_rows != -1) {
					$email_content .= "A batting row has been added<br>";
				} else {
					$email_content .= "<p>Batting record could not be added to the database at this time for ". $player->getAttribute('id') . "</p><br>";
				}
				$batpos++;
			}
		}
	}
}

function update_bowling_details_table($ccl_game_id, $firstIBowlers, $secondIBowlers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_season_id) {
	global $db;
	global $email_content;
	$db->Delete("DELETE FROM scorecard_bowling_details WHERE game_id = $ccl_game_id");
	foreach($firstIBowlers as $firstIBowler) {
		$player_id = get_ccl_player_id($firstIBowler->getAttribute('id'));
		$bowlpos = $firstIBowler->getAttribute('order');
		$overs = $firstIBowler->getAttribute('overs_bowled');
		$maidens = $firstIBowler->getAttribute('maidens_bowled');
		$runs = $firstIBowler->getAttribute('runs_conceded');
		$wickets = $firstIBowler->getAttribute('wickets_taken');
		$no_balls = $firstIBowler->getAttribute('no_balls');
		$wides = $firstIBowler->getAttribute('wides');
		$wickets = $firstIBowler->getAttribute('wickets_taken');
		$wickets = $firstIBowler->getAttribute('wickets_taken');
		$db->Insert("INSERT INTO scorecard_bowling_details (game_id, season, innings_id, player_id, bowling_position, overs, maidens, runs, wickets, noballs, wides, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '1', '$player_id', '$bowlpos', '$overs', '$maidens','$runs','$wickets','$no_balls','$wides','$ccl_batting_second_id','$ccl_batting_first_id')");
		if ($db->a_rows != -1) {
			$email_content .= "A bowling row has been added<br>";
		} else {
			$email_content .= "<p>Bowling record could not be added to the database at this time for ". $secondIBatsman->getAttribute('id') . "</p><br>";
		}
	}
	foreach($secondIBowlers as $secondIBowler) {
		$player_id = get_ccl_player_id($secondIBowler->getAttribute('id'));
		$bowlpos = $secondIBowler->getAttribute('order');
		$overs = $secondIBowler->getAttribute('overs_bowled');
		$maidens = $secondIBowler->getAttribute('maidens_bowled');
		$runs = $secondIBowler->getAttribute('runs_conceded');
		$wickets = $secondIBowler->getAttribute('wickets_taken');
		$no_balls = $secondIBowler->getAttribute('no_balls');
		$wides = $secondIBowler->getAttribute('wides');
		$wickets = $secondIBowler->getAttribute('wickets_taken');
		$wickets = $secondIBowler->getAttribute('wickets_taken');
		$db->Insert("INSERT INTO scorecard_bowling_details (game_id, season, innings_id, player_id, bowling_position, overs, maidens, runs, wickets, noballs, wides, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '2', '$player_id', '$bowlpos', '$overs', '$maidens','$runs','$wickets','$no_balls','$wides','$ccl_batting_first_id','$ccl_batting_second_id')");
		if ($db->a_rows != -1) {
			$email_content .= "A bowling row has been added<br>";
		} else {
			$email_content .= "<p>Bowling record could not be added to the database at this time for ". $secondIBatsman->getAttribute('id') . "</p><br>";
		}
	}
}

function update_extras_details_table($ccl_game_id, $firstExtras, $secondExtras) {
	global $db;
	$db->Delete("DELETE FROM scorecard_extras_details WHERE game_id = $ccl_game_id");
	$byes = $firstExtras->getAttribute('byes');
	$leg_byes = $firstExtras->getAttribute('leg_byes');
	$no_balls = $firstExtras->getAttribute('no_balls');
	$wides = $firstExtras->getAttribute('wides');
	$total = $firstExtras->getAttribute('total_extras');
	$db->Insert("INSERT INTO scorecard_extras_details (game_id, innings_id, legbyes, byes, wides, noballs, total) VALUES ('$ccl_game_id','1','$leg_byes','$byes','$wides','$no_balls','$total')");
	
	$byes = $secondExtras->getAttribute('byes');
	$leg_byes = $secondExtras->getAttribute('leg_byes');
	$no_balls = $secondExtras->getAttribute('no_balls');
	$wides = $secondExtras->getAttribute('wides');
	$total = $secondExtras->getAttribute('total_extras');
	$db->Insert("INSERT INTO scorecard_extras_details (game_id, innings_id, legbyes, byes, wides, noballs, total) VALUES ('$ccl_game_id','2','$leg_byes','$byes','$wides','$no_balls','$total')");
}

function update_total_details_table($ccl_game_id, $firstTotal, $secondTotal, $ccl_batting_first_id, $ccl_batting_second_id) {
	global $db;
	$db->Delete("DELETE FROM scorecard_total_details WHERE game_id = $ccl_game_id");
	$overs = $firstTotal->getAttribute('overs');
	$total = $firstTotal->getAttribute('runs_scored');
	$wickets = $firstTotal->getAttribute('wickets');
	$db->Insert("INSERT INTO scorecard_total_details (game_id, innings_id, team, wickets, total, overs) VALUES ('$ccl_game_id','1','$ccl_batting_first_id','$wickets','$total','$overs')");
	$overs = $secondTotal->getAttribute('overs');
	$total = $secondTotal->getAttribute('runs_scored');
	$wickets = $secondTotal->getAttribute('wickets');
	$db->Insert("INSERT INTO scorecard_total_details (game_id, innings_id, team, wickets, total, overs) VALUES ('$ccl_game_id','2','$ccl_batting_second_id','$wickets','$total','$overs')");
	
}

function update_fow_details_table($ccl_game_id, $firstFows, $secondFows) {
	global $db;
	$db->Delete("DELETE FROM scorecard_fow_details WHERE game_id = $ccl_game_id");
	$fow1 = $fow2 = $fow3 = $fow4 = $fow5 = $fow6 = $fow7 = $fow8 = $fow9 = $fow10 = '777';
	foreach($firstFows as $firstFow) {
		if($firstFow->getAttribute('order') == "1") {
			$fow1 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "2") {
			$fow2 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "3") {
			$fow3 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "4") {
			$fow4 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "5") {
			$fow5 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "6") {
			$fow6 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "7") {
			$fow7 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "8") {
			$fow8 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "9") {
			$fow9 = $firstFow->getAttribute('runs');
		}
		if($firstFow->getAttribute('order') == "10") {
			$fow10 = $firstFow->getAttribute('runs');
		}
	}
	
	$db->Insert("INSERT INTO scorecard_fow_details (game_id, innings_id, fow1, fow2, fow3, fow4, fow5, fow6, fow7, fow8, fow9, fow10) VALUES ('$ccl_game_id','1',$fow1,$fow2,$fow3,$fow4,$fow5,$fow6,$fow7,$fow8,$fow9,$fow10)");
	
	$fow1 = $fow2 = $fow3 = $fow4 = $fow5 = $fow6 = $fow7 = $fow8 = $fow9 = $fow10 = '777';
	foreach($secondFows as $secondFow) {
		if($secondFow->getAttribute('order') == "1") {
			$fow1 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "2") {
			$fow2 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "3") {
			$fow3 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "4") {
			$fow4 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "5") {
			$fow5 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "6") {
			$fow6 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "7") {
			$fow7 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "8") {
			$fow8 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "9") {
			$fow9 = $secondFow->getAttribute('runs');
		}
		if($secondFow->getAttribute('order') == "10") {
			$fow10 = $secondFow->getAttribute('runs');
		}
	}

	$db->Insert("INSERT INTO scorecard_fow_details (game_id, innings_id, fow1, fow2, fow3, fow4, fow5, fow6, fow7, fow8, fow9, fow10) VALUES ('$ccl_game_id','2',$fow1,$fow2,$fow3,$fow4,$fow5,$fow6,$fow7,$fow8,$fow9,$fow10)");
}

function get_ccl_player_id ($cc_player_id) {
	global $db;
	if($cc_player_id == null || $cc_player_id == "" || $cc_player_id == 0) {
		return 0;
	}
	if ($db->Exists("SELECT * FROM players WHERE cricclubs_player_id = $cc_player_id")) {
		$db->QueryRow("SELECT * FROM players WHERE cricclubs_player_id = $cc_player_id");
		$db->BagAndTag();

		return $db->data['PlayerID'];
	} else {
		return 0;
	}
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function find_player_id_by_name($fn, $ln) {
	global $db;
	global $email_content;
	$email_content .= "fn = $fn ln = $ln<br>";
	if ($db->Exists("SELECT * FROM players WHERE PlayerFName = '$fn' AND PlayerLName = '$ln'")) {
		$db->QueryRow("SELECT * FROM players WHERE PlayerFName = '$fn' AND PlayerLName = '$ln'");
		$db->BagAndTag();

		return $db->data['PlayerID'];
	} else {
		return 0;
	}
}

function map_cc_to_ccl_ground($cc_venue_id) {
	$ccl_ground_id = 0;
	switch ($cc_venue_id) {
	case 1:
		$ccl_ground_id = 2;
		break;
	case 2:
		$ccl_ground_id = 3;
		break;
	case 3:
		$ccl_ground_id = 4;
		break;
	case 4:
		$ccl_ground_id = 5;
		break;
	case 5:
		$ccl_ground_id = 7;
		break;
	case 6:
		$ccl_ground_id = 12;
		break;
	case 7:
		$ccl_ground_id = 14;
		break;
	case 8:
		$ccl_ground_id = 15;
		break;
	case 9:
		$ccl_ground_id = 21;
		break;
	case 10:
		$ccl_ground_id = 22;
		break;
	default:
		$ccl_ground_id = 0;
		break;
	}
	return $ccl_ground_id;
}

// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

$email_content = "";
$email_subject = "Scorecard import for ";
read_scorecard($db);

if ($email_content != "") {
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

	// More headers
	$headers .= 'From: <root@tx4.fcomet.com>' . "\r\n";
	$headers .= 'Cc: bodhayanc@gmail.com' . "\r\n";

	$to = "colorad2@tx4.fcomet.com";
	$email_body = "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\" \"http://www.w3.org/TR/REC-html40/loose.dtd\">\r\n";
	$email_body .= "	<html><head>\r\n";
	$email_body .= "		<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">\r\n";
	$email_body .= "		<title></title>\r\n";
	$email_body .= "	</head>\r\n";
	$email_body .= "	<body style=\"font-family:Arial;font-size:14px\">\r\n";
	$email_body .= "		<p>$email_content</p>\r\n";
	$email_body .= "	</body></html>\r\n";
	$email_subject = trim($email_subject);
	mail($to,$email_subject,$email_body,$headers);
}
?>
