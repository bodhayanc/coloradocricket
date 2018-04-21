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
	$scorecardFiles = scandir($dir);
	$season_start_date = "04/07/2018";
	for($x = 0; $x < count($scorecardFiles); $x++) {
		if(!is_dir($dir.$scorecardFiles[$x]) && pathinfo($scorecardFiles[$x])['extension'] == 'xml') {
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($dir.$scorecardFiles[$x]);
			echo $dir.$scorecardFiles[$x];
			echo "\n\n\n";
			$matchDetail = $xmlDoc->getElementsByTagName('MatchDetail');
			$cc_game_id = $matchDetail->item(0)->getAttribute('game_id');
			echo "CricClub match id: $cc_game_id\n";
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
				echo "Team not found with name: $ccl_hometeam. Please contact the administrator to create the team in coloradocricket.org.\n";
				rename($dir.$scorecardFiles[$x], $dir."processed/".$scorecardFiles[$x]);
				exit();
			}
			echo "CricClub hometeam: $cc_hometeam\n";
			echo "CricClub hometeam id: $cc_hometeam_id\n";
			echo "CCL hometeam: $ccl_hometeam\n";
			echo "CCL hometeam id: $ccl_hometeam_id\n";
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
				echo "Team not found with name: $ccl_awayteam. Please contact the administrator to create the team in coloradocricket.org.\n";
				rename($dir.$scorecardFiles[$x], $dir."processed/".$scorecardFiles[$x]);
				exit();
			}
			echo "CricClub awayteam: $cc_awayteam\n";
			echo "CricClub awayteam id: $cc_awayteam_id\n";
			echo "CCL awayteam: $ccl_awayteam\n";
			echo "CCL awayteam id: $ccl_awayteam_id\n";
			//$cc_groundname = $matchDetail->item(0)->getAttribute('venue_name');
			//echo "CricClub ground name: $cc_groundname\n";
			$cc_competition_name = $matchDetail->item(0)->getAttribute('competition_name');
			echo "CricClub competition name: $cc_competition_name\n";
			if ($db->Exists("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'")) {
				$db->QueryRow("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'");
				$db->BagAndTag();

				$ccl_season_id = $db->data['SeasonID'];
			} else {
				echo "Season not found with name: $cc_competition_name. Let's create the season.\n";
				$db->Insert("INSERT INTO seasons (SeasonName) VALUES('$cc_competition_name')");
				$db->QueryRow("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'");
				$db->BagAndTag();

				$ccl_season_id = $db->data['SeasonID'];
			}
			if(strpos($cc_competition_name, 'Twenty20') !== FALSE) {
				$ccl_league_id = 4;
			} else {
				$ccl_league_id = 1;
			}
			
			echo "CCL season id: $ccl_season_id\n";
			echo "CCL league id: $ccl_league_id\n";
			$cc_game_date = $matchDetail->item(0)->getAttribute('game_date');
			echo "CricClub game date: $cc_game_date\n";
			$cc_toss_team_id = $matchDetail->item(0)->getAttribute('toss_team_id');
			if($cc_toss_team_id == $cc_hometeam_id) {
				$ccl_toss_team_id = $ccl_hometeam_id;
			} else {
				$ccl_toss_team_id = $ccl_awayteam_id;
			}
			echo "CricClub toss team id: $cc_toss_team_id\n";
			echo "CCL toss team id: $ccl_toss_team_id\n";
			$cc_result = $matchDetail->item(0)->getAttribute('result');
			$result_won_id = 0;
			$ccl_result = "";
			echo "CricClub result: $cc_result\n";
			if(strpos($cc_result, $cc_hometeam) !== FALSE) {
				$result_won_id = $ccl_hometeam_id;
				$result_won_abbrev = $ccl_hometeam_abbrev;
			} else if(strpos($cc_result, $cc_awayteam) !== FALSE) {
				$result_won_id = $ccl_awayteam_id;
				$result_won_abbrev = $ccl_awayteam_abbrev;
			}
			if($result_won_id != 0) {
				$run_won = trim(get_string_between($cc_result, "won by", "Run"));
				echo "run_won: $run_won\n";
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
			
			$cc_game_date_formatted = date_create_from_format('m/d/Y', $cc_game_date);
			$ccl_game_date = $cc_game_date_formatted->format('Y-m-d');
			$season_start_date_formatted = date_create_from_format('m/d/Y', $season_start_date);
			$week = floor(date_diff($cc_game_date_formatted, $season_start_date_formatted)->format('%a') / 7) + 1;
			echo "Week: $week\n";
			$cc_venue_id = $matchDetail->item(0)->getAttribute('venue_id');
			$ccl_ground_id = map_cc_to_ccl_ground($cc_venue_id);
			echo "ccl_ground_id: $ccl_ground_id\n";
			$cc_total_overs = $matchDetail->item(0)->getAttribute('total_overs');
			echo "cc_total_overs: $cc_total_overs\n";
			$cc_mom = 0;
			foreach($matchDetail->item(0)->childNodes as $matchDetailChild) {
				if($matchDetailChild->nodeName == 'man_of_the_match') {
					$cc_mom = $matchDetailChild->nodeValue;
				}
				if($matchDetailChild->nodeName == 'umpire1') {
					$cc_ump1 = $matchDetailChild->nodeValue;
				}
				if($matchDetailChild->nodeName == 'umpire2') {
					$cc_ump2 = $matchDetailChild->nodeValue;
				}
			}
			echo "cc_mom: $cc_mom\n";
			echo "cc_ump1: $cc_ump1\n";
			echo "cc_ump2: $cc_ump2\n";
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
			echo "ump1 = $cc_ump1_fn $cc_ump1_ln\n";
			echo "ump2 = $cc_ump2_fn $cc_ump2_ln\n";
			$ccl_ump1_id = find_player_id_by_name($cc_ump1_fn, $cc_ump1_ln);
			$ccl_ump2_id = find_player_id_by_name($cc_ump2_fn, $cc_ump2_ln);
			//Parse the XML and get all batting, bowling, extras, etc. from both the innings.
			unset($firstIBatsmen, $firstIBowlers, $firstFows, $secondIBatsmen, $secondIBowlers, $secondFows, $hometeamplayers, $awayteamplayers);
			get_batting_bowling_info($xmlDoc->getElementsByTagName('Innings'), $firstIBatsmen, $firstIBowlers, $firstExtras, $firstFows, $firstTotal, $secondIBatsmen, $secondIBowlers, $secondExtras, $secondFows, $secondTotal, $cc_batting_first_id, $cc_batting_second_id);
			
			foreach ($firstIBatsmen as $firstIBatsman) {
				echo "First Innings Batsman " . $firstIBatsman->getAttribute('id')."\n";
			}
			foreach ($firstIBowlers as $firstIBowler) {
				echo "First Innings Bowler " . $firstIBowler->getAttribute('id')."\n";
			}
			foreach ($secondIBatsmen as $secondIBatsman) {
				echo "Second Innings Batsman " . $secondIBatsman->getAttribute('id')."\n";
			}
			foreach ($secondIBowlers as $secondIBowler) {
				echo "Second Innings Bowler " . $secondIBowler->getAttribute('id')."\n";
			}
			
			echo "cc_batting_first_id: $cc_batting_first_id\n";
			echo "cc_batting_second_id: $cc_batting_second_id\n";
			$teams = $xmlDoc->getElementsByTagName('Team');
			foreach ($teams as $team) {
				if($team->getAttribute('home_or_away') == 'home') {
					foreach($team->childNodes as $teamChild) {
						if($teamChild->nodeName == 'Player') {
							if(!isset($hometeamplayers)) {
								$hometeamplayers = array($teamChild);
							} else {
								array_push($hometeamplayers, $teamChild);
							}
						}
						if($teamChild->nodeName == 'captain') {
							$cc_home_captain = $teamChild->nodeValue;
						}
						if($teamChild->nodeName == 'vice_captain') {
							$cc_home_vcaptain = $teamChild->nodeValue;
						}
						if($teamChild->nodeName == 'wicket_keeper') {
							$cc_home_wk = $teamChild->nodeValue;
						}
					}
					sync_cc_and_ccl_players($hometeamplayers, $ccl_homeclub_id, $ccl_hometeam_id);
				} else if($team->getAttribute('home_or_away') == 'away') {
					foreach($team->childNodes as $teamChild) {
						if($teamChild->nodeName == 'Player') {
							if(!isset($awayteamplayers)) {
								$awayteamplayers = array($teamChild);
							} else {
								array_push($awayteamplayers, $teamChild);
							}
						}
						if($teamChild->nodeName == 'captain') {
							$cc_away_captain = $teamChild->nodeValue;
						}
						if($teamChild->nodeName == 'vice_captain') {
							$cc_away_vcaptain = $teamChild->nodeValue;
						}
						if($teamChild->nodeName == 'wicket_keeper') {
							$cc_away_wk = $teamChild->nodeValue;
						}
					}
					sync_cc_and_ccl_players($awayteamplayers, $ccl_awayclub_id, $ccl_awayteam_id);
				}
			}
			echo "HOME TEAM:\n";
			foreach ($hometeamplayers as $player) {
				echo "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."\n";
			}
			
			echo "AWAY TEAM:\n";
			foreach ($awayteamplayers as $player) {
				echo "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."\n";
			}
			
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

			echo "ccl_batting_first_id: $ccl_batting_first_id\n";
			echo "ccl_batting_second_id: $ccl_batting_second_id\n";
			
			$ccl_mom = get_ccl_player_id($cc_mom);
			echo "ccl_mom: $ccl_mom\n";
			$ccl_home_captain = get_ccl_player_id($cc_home_captain);
			echo "ccl_home_captain: $ccl_home_captain\n";
			$ccl_home_vcaptain = get_ccl_player_id($cc_home_vcaptain);
			echo "ccl_home_vcaptain: $ccl_home_vcaptain\n";
			$ccl_home_wk = get_ccl_player_id($cc_home_wk);
			echo "ccl_home_wk: $ccl_home_wk\n";
			$ccl_away_captain = get_ccl_player_id($cc_away_captain);
			echo "ccl_away_captain: $ccl_away_captain\n";
			$ccl_away_vcaptain = get_ccl_player_id($cc_away_vcaptain);
			echo "ccl_away_vcaptain: $ccl_away_vcaptain\n";
			$ccl_away_wk = get_ccl_player_id($cc_away_wk);
			echo "ccl_away_wk: $ccl_away_wk\n";
			
			
			update_game_details_table($cc_game_id, $ccl_league_id, $ccl_season_id, $week, $ccl_awayteam_id, $ccl_hometeam_id, $ccl_toss_team_id, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_ground_id, $ccl_game_date, $ccl_result, $result_won_id, $cc_total_overs, $ccl_mom, $ccl_ump1_id, $ccl_ump2_id, $ccl_home_captain, $ccl_home_vcaptain, $ccl_home_wk, $ccl_away_captain, $ccl_away_vcaptain, $ccl_away_wk, $ccl_game_id);
			
			update_batting_details_table($ccl_game_id, $firstIBatsmen, $secondIBatsmen, $hometeamplayers, $awayteamplayers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_hometeam_id, $ccl_awayteam_id, $ccl_season_id);
			
			update_bowling_details_table($ccl_game_id, $firstIBowlers, $secondIBowlers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_season_id);
			
			update_extras_details_table($ccl_game_id, $firstExtras, $secondExtras);
			
			update_fow_details_table($ccl_game_id, $firstFows, $secondFows);
			
			update_total_details_table($ccl_game_id, $firstTotal, $secondTotal, $ccl_batting_first_id, $ccl_batting_second_id);
			rename($dir.$scorecardFiles[$x], $dir."processed/".$scorecardFiles[$x] . "." . time());
		}
	}
}

//This function checks all the players whether that player exists in CCL DB or not. If it finds the player, it syncs the first name, last name and email from CricClubs.
//If it doesn't find the player, it adds the player as a new player in CCL DB.
function sync_cc_and_ccl_players($players, $ccl_club_id, $ccl_team_id) {
	global $db;
	$parent_child_team_map = array(62 => 5, 70 => 5, 66 => 8, 73 => 8, 71 => 54, 72 => 54);
	$ccl_parent_team_id = $ccl_team_id;
	foreach ($parent_child_team_map as $child_team=>$parent_team) {
		echo "parent team for $child_team is $parent_team\n";
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
				echo "Reactivating player $ccl_player_last_name, $ccl_player_first_name. Updating CCL Data!!\n";
				$db->Update("UPDATE players SET isactive = 0 WHERE PlayerID = $ccl_player_id");
			}
			if($ccl_player_first_name != $player->getAttribute('player_first_name') || $ccl_player_last_name != $player->getAttribute('player_last_name')) {
				echo "Mismatch between CricClubs and CCL player name. NOT updating for the time being!!\n";
				echo "ccl_player_first_name: $ccl_player_first_name\n";
				echo "ccl_player_last_name: $ccl_player_last_name\n";
				echo "CricClubs player_first_name: " . $player->getAttribute('player_first_name') . "\n";
				echo "CricClubs player_last_name: " . $player->getAttribute('player_last_name') . "\n";
				//$db->Update("UPDATE players SET PlayerLName = '" . $player->getAttribute('player_last_name') . "', PlayerFName = '" . $player->getAttribute('player_first_name') . "' WHERE PlayerID = $ccl_player_id");
			}
			if($ccl_player_team != $ccl_team_id && $ccl_player_team2 != $ccl_team_id) {
				echo "Mismatch between CricClubs and CCL player teams. Updating CCL Data!!\n";
				echo "ccl_player_team: $ccl_player_team\n";
				echo "ccl_player_team2: $ccl_player_team2\n";
				echo "ccl_team_id: $ccl_team_id\n";
				echo "ccl_parent_team_id: $ccl_parent_team_id\n";
				
				if($ccl_parent_team_id == $ccl_team_id) {
					$db->Update("UPDATE players SET PlayerTeam = '$ccl_parent_team_id' WHERE PlayerID = $ccl_player_id");
				} else {
					$db->Update("UPDATE players SET PlayerTeam = '$ccl_parent_team_id', PlayerTeam2 = '$ccl_team_id' WHERE PlayerID = $ccl_player_id");
				}
			}
		} else {
			echo "New Player " . $player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name') . "!!\n";
			if($ccl_parent_team_id != $ccl_team_id) {
				$db->Insert("INSERT INTO players (PlayerLName,PlayerFName,PlayerClub,PlayerTeam,PlayerTeam2,PlayerEmail,cricclubs_player_id) VALUES ('".$player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name')."','$ccl_club_id','$ccl_parent_team_id','$ccl_team_id','".$player->getAttribute('player_email_id')."','".$player->getAttribute('id')."')");
			} else {
				$db->Insert("INSERT INTO players (PlayerLName,PlayerFName,PlayerClub,PlayerTeam,PlayerEmail,cricclubs_player_id) VALUES ('".$player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name')."','$ccl_club_id','$ccl_team_id','".$player->getAttribute('player_email_id')."','".$player->getAttribute('id')."')");
			}
			if ($db->a_rows != -1) {
				echo "<p>A new player has been added</p>\n";
			} else {
				echo "<p>The player could not be added to the database at this time.</p>\n";
			}
			$db->QueryRow("SELECT * FROM players WHERE cricclubs_player_id = " . $player->getAttribute('id'));
			$ccl_player_id = $db->data['PlayerID'];
		}
		$player->setAttribute('ccl_player_id', $ccl_player_id);
		//echo "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."\n";
	}
}

function get_batting_bowling_info($Innings, &$firstIBatsmen, &$firstIBowlers, &$firstExtras, &$firstFows, &$firstTotal, &$secondIBatsmen, &$secondIBowlers, &$secondExtras, &$secondFows, &$secondTotal, &$cc_batting_first_id, &$cc_batting_second_id) {
	
	foreach ($Innings as $Inning) {
		$id = $Inning->getAttribute('id');
		echo "Inning: $id\n";
		if($id == 1) {
			bat_bowl_extra_fow_total_details($Inning, $firstIBatsmen, $firstIBowlers, $firstExtras, $firstFows, $firstTotal, $cc_batting_first_id);
			echo "leg_byes: " . $firstExtras->getAttribute('leg_byes') . "\n";
			echo "firstTotal: " . $firstTotal->getAttribute('runs_scored') . "\n";
		} else {
			bat_bowl_extra_fow_total_details($Inning, $secondIBatsmen, $secondIBowlers, $secondExtras, $secondFows, $secondTotal, $cc_batting_second_id);
			echo "leg_byes: " . $secondExtras->getAttribute('leg_byes') . "\n";
			echo "secondTotal: " . $secondTotal->getAttribute('runs_scored') . "\n";
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
	$report_link = "https://www.cricclubs.com/ColoradoCricket/viewScorecard.do?matchId=$cc_game_id";
	if ($db->Exists("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id")) {
		$db->QueryRow("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id");
		$db->BagAndTag();

		$ccl_game_id = $db->data['game_id'];
		$db->Update("UPDATE scorecard_game_details SET league_id = '$ccl_league_id', season = '$ccl_season_id', week = '$week', awayteam = '$ccl_awayteam_id', hometeam = '$ccl_hometeam_id', toss_won_id = '$ccl_toss_team_id', batting_first_id = '$ccl_batting_first_id', batting_second_id = '$ccl_batting_second_id', ground_id = '$ccl_ground_id', game_date = '$ccl_game_date',result = '$result', result_won_id = '$result_won_id', maxovers = '$cc_total_overs', mom = '$ccl_mom', umpire1 = '$ccl_ump1_id', umpire2 = '$ccl_ump2_id', report = '$report_link', HOMETEAM_CAPTAIN = '$ccl_home_captain', HOMETEAM_VCAPTAIN = '$ccl_home_vcaptain', HOMETEAM_WK = '$ccl_home_wk', AWAYTEAM_CAPTAIN = '$ccl_away_captain', AWAYTEAM_VCAPTAIN = '$ccl_away_vcaptain', AWAYTEAM_WK = '$ccl_away_wk' WHERE game_id = $ccl_game_id");
		if ($db->a_rows != -1) {
			echo "<p>The scorecard has been updated</p>\n";
		} else {
			echo "<p>The scorecard could not be updated at this time.</p>\n";
		}
	} else {
		$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,toss_won_id,batting_first_id, batting_second_id,ground_id,game_date,result,result_won_id,maxovers,mom,umpire1,umpire2,HOMETEAM_CAPTAIN, HOMETEAM_VCAPTAIN, HOMETEAM_WK, AWAYTEAM_CAPTAIN, AWAYTEAM_VCAPTAIN, AWAYTEAM_WK,isactive,cricclubs_game_id,report) VALUES ('$ccl_league_id','$ccl_season_id','$week','$ccl_awayteam_id','$ccl_hometeam_id','$ccl_toss_team_id','$ccl_batting_first_id','$ccl_batting_second_id','$ccl_ground_id','$ccl_game_date','$result','$result_won_id','$cc_total_overs','$ccl_mom','$ccl_ump1_id','$ccl_ump2_id', '$ccl_home_captain', '$ccl_home_vcaptain', '$ccl_home_wk', '$ccl_away_captain', '$ccl_away_vcaptain', '$ccl_away_wk',0,$cc_game_id,'$report_link')");
		if ($db->a_rows != -1) {
			echo "<p>A new scorecard has been added</p>\n";
		} else {
			echo "<p>The scorecard could not be added to the database at this time.</p>\n";
		}
		$db->QueryRow("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id");
		$db->BagAndTag();
		$ccl_game_id = $db->data['game_id'];
	}
}

function update_batting_details_table($ccl_game_id, $firstIBatsmen, $secondIBatsmen, $hometeamplayers, $awayteamplayers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_hometeam_id, $ccl_awayteam_id, $ccl_season_id) {
	global $db;
	$db->Delete("DELETE FROM scorecard_batting_details WHERE game_id = $ccl_game_id");
	foreach($firstIBatsmen as $firstIBatsman) {
		$player_id = get_ccl_player_id($firstIBatsman->getAttribute('id'));
		$batpos = $firstIBatsman->getAttribute('order');
		$notout = 0;
		$how_out = 0;
		$assist = get_ccl_player_id($firstIBatsman->getAttribute('caught_by'));
		$bowler = get_ccl_player_id($firstIBatsman->getAttribute('bowled_by'));
		$howout = $firstIBatsman->getAttribute('how_out');
		if($bowler != 0 && $assist == 0) {
			if(strpos($howout, "lbw") !== FALSE) {
				$how_out = 7;
			} else if(strpos($howout, "hit wicket") !== FALSE) {
				$how_out = 6;
			} else if(strpos($howout, "Stumped") !== FALSE) {
				$how_out = 10;
			} else {
				$how_out = 3;
			}
		} else if ($bowler != 0 && $assist != 0) {
			echo "Bowled and caught by " . $bowler . " " . $assist;
			if($bowler == $assist) {
				$how_out = 5;
				$assist = 0;
			} else {
				$how_out = 4;
			}
		} else if ($bowler == 0 && $assist == 0) {
			if(strpos($howout, "not out") !== FALSE) {
				$how_out = 2;
				$notout = 1;
			} else if(strpos($howout, "Retired Hurt") !== FALSE) {
				$how_out = 8;
			} else if(strpos($howout, "run out") !== FALSE) {
				$how_out = 9;
			}
		}
		$runs = $firstIBatsman->getAttribute('runs_scored');
		$balls = $firstIBatsman->getAttribute('balls_faced');
		$fours = $firstIBatsman->getAttribute('fours_scored');
		$sixes = $firstIBatsman->getAttribute('sixes_scored');
		$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '1','$player_id','$batpos','$how_out','$runs','$assist','$bowler','$balls','$fours','$sixes','$notout','$ccl_batting_first_id','$ccl_batting_second_id')");
		if ($db->a_rows != -1) {
			echo "<p>A batting row has been added</p>\n";
		} else {
			echo "<p>Batting record could not be added to the database at this time for ". $firstIBatsman->getAttribute('id') . "</p>\n";
		}
	}
	if($ccl_batting_first_id == $ccl_hometeam_id) {
		$batpos = count($firstIBatsmen) + 1;
		foreach($hometeamplayers as $hometeamplayer) {
			if($batpos < 12) {
				$did_not_bat = true;
				foreach($firstIBatsmen as $firstIBatsman) {
					if($hometeamplayer->getAttribute('id') == $firstIBatsman->getAttribute('id')) {
						$did_not_bat = false;
					}
				}
				if($did_not_bat) {
					$player_id = get_ccl_player_id($hometeamplayer->getAttribute('id'));
					$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '1','$player_id','$batpos','1','0','0','0','0','0','0','0','$ccl_batting_first_id','$ccl_batting_second_id')");
					if ($db->a_rows != -1) {
						echo "<p>A batting row has been added</p>\n";
					} else {
						echo "<p>Batting record could not be added to the database at this time for ". $hometeamplayer->getAttribute('id') . "</p>\n";
					}
					$batpos++;
				}
			}
		}
	}
	if($ccl_batting_first_id == $ccl_awayteam_id) {
		$batpos = count($firstIBatsmen) + 1;
		foreach($awayteamplayers as $awayteamplayer) {
			if($batpos < 12) {
				$did_not_bat = true;
				foreach($firstIBatsmen as $firstIBatsman) {
					if($awayteamplayer->getAttribute('id') == $firstIBatsman->getAttribute('id')) {
						$did_not_bat = false;
					}
				}
				if($did_not_bat) {
					$player_id = get_ccl_player_id($awayteamplayer->getAttribute('id'));
					$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '1','$player_id','$batpos','1','0','0','0','0','0','0','0','$ccl_batting_first_id','$ccl_batting_second_id')");
					if ($db->a_rows != -1) {
						echo "<p>A batting row has been added</p>\n";
					} else {
						echo "<p>Batting record could not be added to the database at this time for ". $awayteamplayer->getAttribute('id') . "</p>\n";
					}
					$batpos++;
				}
			}
		}
	}
	if($ccl_batting_second_id == $ccl_hometeam_id) {
		$batpos = count($secondIBatsmen) + 1;
		foreach($hometeamplayers as $hometeamplayer) {
			if($batpos < 12) {
				$did_not_bat = true;
				foreach($secondIBatsmen as $secondIBatsman) {
					if($hometeamplayer->getAttribute('id') == $secondIBatsman->getAttribute('id')) {
						$did_not_bat = false;
					}
				}
				if($did_not_bat) {
					$player_id = get_ccl_player_id($hometeamplayer->getAttribute('id'));
					$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '2','$player_id','$batpos','1','0','0','0','0','0','0','0','$ccl_batting_second_id','$ccl_batting_first_id')");
					if ($db->a_rows != -1) {
						echo "<p>A batting row has been added</p>\n";
					} else {
						echo "<p>Batting record could not be added to the database at this time for ". $hometeamplayer->getAttribute('id') . "</p>\n";
					}
					$batpos++;
				}
			}
		}
	}
	if($ccl_batting_second_id == $ccl_awayteam_id) {
		$batpos = count($secondIBatsmen) + 1;
		foreach($awayteamplayers as $awayteamplayer) {
			if($batpos < 12) {
				$did_not_bat = true;
				foreach($secondIBatsmen as $secondIBatsman) {
					if($awayteamplayer->getAttribute('id') == $secondIBatsman->getAttribute('id')) {
						$did_not_bat = false;
					}
				}
				if($did_not_bat) {
					$player_id = get_ccl_player_id($awayteamplayer->getAttribute('id'));
					$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '2','$player_id','$batpos','1','0','0','0','0','0','0','0','$ccl_batting_second_id','$ccl_batting_first_id')");
					if ($db->a_rows != -1) {
						echo "<p>A batting row has been added</p>\n";
					} else {
						echo "<p>Batting record could not be added to the database at this time for ". $awayteamplayer->getAttribute('id') . "</p>\n";
					}
					$batpos++;
				}
			}
		}
	}
	foreach($secondIBatsmen as $secondIBatsman) {
		$player_id = get_ccl_player_id($secondIBatsman->getAttribute('id'));
		$batpos = $secondIBatsman->getAttribute('order');
		$notout = 0;
		$assist = get_ccl_player_id($secondIBatsman->getAttribute('caught_by'));
		$bowler = get_ccl_player_id($secondIBatsman->getAttribute('bowled_by'));
		$howout = $secondIBatsman->getAttribute('how_out');
		if($bowler != 0 && $assist == 0) {
			if(strpos($howout, "lbw") !== FALSE) {
				$how_out = 7;
			} else if(strpos($howout, "hit wicket") !== FALSE) {
				$how_out = 6;
			} else if(strpos($howout, "Stumped") !== FALSE) {
				$how_out = 10;
			} else {
				$how_out = 3;
			}
		} else if ($bowler != 0 && $assist != 0) {
			echo "Bowled and caught by " . $bowler . " " . $assist;
			if($bowler == $assist) {
				$how_out = 5;
				$assist = 0;
			} else {
				$how_out = 4;
			}
		} else if ($bowler == 0 && $assist == 0) {
			if(strpos($howout, "not out") !== FALSE) {
				$how_out = 2;
				$notout = 1;
			} else if(strpos($howout, "Retired Hurt") !== FALSE) {
				$how_out = 8;
			} else if(strpos($howout, "run out") !== FALSE) {
				$how_out = 9;
			}
		}
		$runs = $secondIBatsman->getAttribute('runs_scored');
		$balls = $secondIBatsman->getAttribute('balls_faced');
		$fours = $secondIBatsman->getAttribute('fours_scored');
		$sixes = $secondIBatsman->getAttribute('sixes_scored');
		$db->Insert("INSERT INTO scorecard_batting_details (game_id, season, innings_id, player_id, batting_position, how_out, runs, assist, bowler, balls, fours, sixes, notout, team, opponent) VALUES ('$ccl_game_id', '$ccl_season_id', '2','$player_id','$batpos','$how_out','$runs','$assist','$bowler','$balls','$fours','$sixes','$notout','$ccl_batting_second_id','$ccl_batting_first_id')");
		if ($db->a_rows != -1) {
			echo "<p>A batting row has been added</p>\n";
		} else {
			echo "<p>Batting record could not be added to the database at this time for ". $secondIBatsman->getAttribute('id') . "</p>\n";
		}
	}
}

function update_bowling_details_table($ccl_game_id, $firstIBowlers, $secondIBowlers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_season_id) {
	global $db;
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
			echo "<p>A bowling row has been added</p>\n";
		} else {
			echo "<p>Bowling record could not be added to the database at this time for ". $secondIBatsman->getAttribute('id') . "</p>\n";
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
			echo "<p>A bowling row has been added</p>\n";
		} else {
			echo "<p>Bowling record could not be added to the database at this time for ". $secondIBatsman->getAttribute('id') . "</p>\n";
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
	if($cc_player_id == 0) {
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
	echo "fn = $fn ln = $ln\n";
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

read_scorecard($db);

?>
