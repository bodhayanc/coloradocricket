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
	$season_start_date = "04/15/2017";
	for($x = 0; $x < count($scorecardFiles); $x++) {
		if(!is_dir($dir.$scorecardFiles[$x]) && pathinfo($scorecardFiles[$x])['extension'] == 'xml') {
			$xmlDoc = new DOMDocument();
			$xmlDoc->load($dir.$scorecardFiles[$x]);
			echo $dir.$scorecardFiles[$x];
			echo "<br><br><br>";
			$matchDetail = $xmlDoc->getElementsByTagName('MatchDetail');
			$cc_game_id = $matchDetail->item(0)->getAttribute('game_id');
			echo "CricClub match id: $cc_game_id<br>";
			$cc_hometeam_id = $matchDetail->item(0)->getAttribute('home_team_id');
			$cc_hometeam = $matchDetail->item(0)->getAttribute('home_team');
			$ccl_hometeam = str_replace(" CC", " Cricket Club", $cc_hometeam);
			if ($db->Exists("SELECT * FROM teams WHERE TeamName = '$ccl_hometeam'")) {
				$db->QueryRow("SELECT * FROM teams WHERE TeamName = '$ccl_hometeam'");
				$db->BagAndTag();

				$ccl_hometeam_id = $db->data['TeamID'];
				$ccl_homeclub_id = $db->data['ClubID'];
			} else {
				echo "Team not found with name: $ccl_hometeam. Please contact the administrator to create the team in coloradocricket.org.<br>";
				exit();
			}
			echo "CricClub hometeam: $cc_hometeam<br>";
			echo "CricClub hometeam id: $cc_hometeam_id<br>";
			echo "CCL hometeam: $ccl_hometeam<br>";
			echo "CCL hometeam id: $ccl_hometeam_id<br>";
			$cc_awayteam_id = $matchDetail->item(0)->getAttribute('away_team_id');
			$cc_awayteam = $matchDetail->item(0)->getAttribute('away_team');
			$ccl_awayteam = str_replace(" CC", " Cricket Club", $cc_awayteam);
			if ($db->Exists("SELECT * FROM teams WHERE TeamName = '$ccl_awayteam'")) {
				$db->QueryRow("SELECT * FROM teams WHERE TeamName = '$ccl_awayteam'");
				$db->BagAndTag();

				$ccl_awayteam_id = $db->data['TeamID'];
				$ccl_awayclub_id = $db->data['ClubID'];
			} else {
				echo "Team not found with name: $ccl_awayteam. Please contact the administrator to create the team in coloradocricket.org.<br>";
				exit();
			}
			echo "CricClub awayteam: $cc_awayteam<br>";
			echo "CricClub awayteam id: $cc_awayteam_id<br>";
			echo "CCL awayteam: $ccl_awayteam<br>";
			echo "CCL awayteam id: $ccl_awayteam_id<br>";
			//$cc_groundname = $matchDetail->item(0)->getAttribute('venue_name');
			//echo "CricClub ground name: $cc_groundname<br>";
			$cc_competition_name = $matchDetail->item(0)->getAttribute('competition_name');
			echo "CricClub competition name: $cc_competition_name<br>";
			if ($db->Exists("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'")) {
				$db->QueryRow("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'");
				$db->BagAndTag();

				$ccl_season_id = $db->data['SeasonID'];
			} else {
				echo "Season not found with name: $cc_competition_name. Let's create the season.<br>";
				$db->Insert("INSERT INTO seasons (SeasonName) VALUES('$cc_competition_name')");
				$db->QueryRow("SELECT * FROM seasons WHERE SeasonName = '$cc_competition_name'");
				$db->BagAndTag();

				$ccl_season_id = $db->data['SeasonID'];
			}
			if(strpos($cc_competition_name, 'Twenty20')) {
				$ccl_league_id = 4;
			} else {
				$ccl_league_id = 1;
			}
			
			echo "CCL season id: $ccl_season_id<br>";
			echo "CCL league id: $ccl_league_id<br>";
			$cc_game_date = $matchDetail->item(0)->getAttribute('game_date');
			echo "CricClub game date: $cc_game_date<br>";
			$cc_toss_team_id = $matchDetail->item(0)->getAttribute('toss_team_id');
			if($cc_toss_team_id == $cc_hometeam_id) {
				$ccl_toss_team_id = $ccl_hometeam_id;
			} else {
				$ccl_toss_team_id = $ccl_awayteam_id;
			}
			echo "CricClub toss team id: $cc_toss_team_id<br>";
			echo "CCL toss team id: $ccl_toss_team_id<br>";
			$cc_result = $matchDetail->item(0)->getAttribute('result');
			echo "CricClub result: $cc_result<br>";
			$cc_game_date_formatted = date_create_from_format('m/d/Y', $cc_game_date);
			$ccl_game_date = $cc_game_date_formatted->format('Y-m-d');
			$season_start_date_formatted = date_create_from_format('m/d/Y', $season_start_date);
			$week = floor(date_diff($cc_game_date_formatted, $season_start_date_formatted)->format('%a') / 7) + 1;
			echo "Week: $week<br>";
			$cc_venue_id = $matchDetail->item(0)->getAttribute('venue_id');
			$ccl_ground_id = map_cc_to_ccl_ground($cc_venue_id);
			echo "ccl_ground_id: $ccl_ground_id<br>";
			$cc_total_overs = $matchDetail->item(0)->getAttribute('total_overs');
			echo "cc_total_overs: $cc_total_overs<br>";
			
			//Parse the XML and get all batting, bowling, extras, etc. from both the innings.
			get_batting_bowling_info($xmlDoc->getElementsByTagName('Innings'), $firstIBatsmen, $firstIBowlers, $firstExtras, $firstFows, $firstTotal, $secondIBatsmen, $secondIBowlers, $secondExtras, $secondFows, $secondTotal, $cc_batting_first_id, $cc_batting_second_id);
			
			foreach ($firstIBatsmen as $firstIBatsman) {
				echo "First Innings Batsman " . $firstIBatsman->getAttribute('id')."<br>";
			}
			foreach ($firstIBowlers as $firstIBowler) {
				echo "First Innings Bowler " . $firstIBowler->getAttribute('id')."<br>";
			}
			foreach ($secondIBatsmen as $secondIBatsman) {
				echo "Second Innings Batsman " . $secondIBatsman->getAttribute('id')."<br>";
			}
			foreach ($secondIBowlers as $secondIBowler) {
				echo "Second Innings Bowler " . $secondIBowler->getAttribute('id')."<br>";
			}
			
			echo "cc_batting_first_id: $cc_batting_first_id<br>";
			echo "cc_batting_second_id: $cc_batting_second_id<br>";
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
					}
					sync_cc_and_ccl_players($hometeamplayers, $ccl_homeclub_id, $ccl_hometeam_id);
				} else {
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
			echo "HOME TEAM:<br>";
			foreach ($hometeamplayers as $player) {
				echo "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."<br>";
			}
			
			echo "AWAY TEAM:<br>";
			foreach ($awayteamplayers as $player) {
				echo "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."<br>";
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

			echo "ccl_batting_first_id: $ccl_batting_first_id<br>";
			echo "ccl_batting_second_id: $ccl_batting_second_id<br>";
			
			update_game_details_table($cc_game_id, $ccl_league_id, $ccl_season_id, $week, $ccl_awayteam_id, $ccl_hometeam_id, $ccl_toss_team_id, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_ground_id, $ccl_game_date, $cc_result, $cc_total_overs, $ccl_game_id);
			
			update_batting_details_table($ccl_game_id, $firstIBatsmen, $secondIBatsmen, $hometeamplayers, $awayteamplayers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_hometeam_id, $ccl_awayteam_id, $ccl_season_id);
			
			update_bowling_details_table($ccl_game_id, $firstIBowlers, $secondIBowlers, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_season_id);
			
			update_extras_details_table($ccl_game_id, $firstExtras, $secondExtras);
			
			update_fow_details_table($ccl_game_id, $firstFows, $secondFows);
			
			update_total_details_table($ccl_game_id, $firstTotal, $secondTotal, $ccl_batting_first_id, $ccl_batting_second_id);
			//rename($dir.$scorecardFiles[$x], $dir."processed/".$scorecardFiles[$x]);
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
		echo "parent team for $child_team is $parent_team<br>";
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
				echo "Reactivating player $ccl_player_last_name, $ccl_player_first_name. Updating CCL Data!!<br>";
				$db->Update("UPDATE players SET isactive = 0 WHERE PlayerID = $ccl_player_id");
			}
			if($ccl_player_first_name != $player->getAttribute('player_first_name') || $ccl_player_last_name != $player->getAttribute('player_last_name')) {
				echo "Mismatch between CricClubs and CCL player name. NOT updating for the time being!!<br>";
				echo "ccl_player_first_name: $ccl_player_first_name<br>";
				echo "ccl_player_last_name: $ccl_player_last_name<br>";
				echo "CricClubs player_first_name: " . $player->getAttribute('player_first_name') . "<br>";
				echo "CricClubs player_last_name: " . $player->getAttribute('player_last_name') . "<br>";
				//$db->Update("UPDATE players SET PlayerLName = '" . $player->getAttribute('player_last_name') . "', PlayerFName = '" . $player->getAttribute('player_first_name') . "' WHERE PlayerID = $ccl_player_id");
			}
			if($ccl_player_team != $ccl_team_id && $ccl_player_team2 != $ccl_team_id) {
				echo "Mismatch between CricClubs and CCL player teams. Updating CCL Data!!<br>";
				echo "ccl_player_team: $ccl_player_team<br>";
				echo "ccl_player_team2: $ccl_player_team2<br>";
				echo "ccl_team_id: $ccl_team_id<br>";
				echo "ccl_parent_team_id: $ccl_parent_team_id<br>";
				
				if($ccl_parent_team_id == $ccl_team_id) {
					$db->Update("UPDATE players SET PlayerTeam = '$ccl_parent_team_id' WHERE PlayerID = $ccl_player_id");
				} else {
					$db->Update("UPDATE players SET PlayerTeam = '$ccl_parent_team_id', PlayerTeam2 = '$ccl_team_id' WHERE PlayerID = $ccl_player_id");
				}
			}
		} else {
			echo "New Player " . $player->getAttribute('player_last_name')."','".$player->getAttribute('player_first_name') . "!!<br>";
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
		//echo "cc_player_id: ". $player->getAttribute('id'). ", ccl_player_id: ". $player->getAttribute('ccl_player_id').", player_name: " . $player->getAttribute('player_name')."<br>";
	}
}

function get_batting_bowling_info($Innings, &$firstIBatsmen, &$firstIBowlers, &$firstExtras, &$firstFows, &$firstTotal, &$secondIBatsmen, &$secondIBowlers, &$secondExtras, &$secondFows, &$secondTotal, &$cc_batting_first_id, &$cc_batting_second_id) {
	
	foreach ($Innings as $Inning) {
		$id = $Inning->getAttribute('id');
		echo "Inning: $id<br>";
		if($id == 1) {
			bat_bowl_extra_fow_total_details($Inning, $firstIBatsmen, $firstIBowlers, $firstExtras, $firstFows, $firstTotal, $cc_batting_first_id);
			echo "leg_byes: " . $firstExtras->getAttribute('leg_byes') . "<br>";
			echo "firstTotal: " . $firstTotal->getAttribute('runs_scored') . "<br>";
		} else {
			bat_bowl_extra_fow_total_details($Inning, $secondIBatsmen, $secondIBowlers, $secondExtras, $secondFows, $secondTotal, $cc_batting_second_id);
			echo "leg_byes: " . $secondExtras->getAttribute('leg_byes') . "<br>";
			echo "secondTotal: " . $secondTotal->getAttribute('runs_scored') . "<br>";
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
			$fow = $childInning;
		}
		if($childInning->nodeName == 'Total') {
			$total = $childInning;
		}
	}
}

function update_game_details_table($cc_game_id, $ccl_league_id, $ccl_season_id, $week, $ccl_awayteam_id, $ccl_hometeam_id, $ccl_toss_team_id, $ccl_batting_first_id, $ccl_batting_second_id, $ccl_ground_id, $ccl_game_date, $result, $cc_total_overs, &$ccl_game_id) {
	global $db;
	$report_link = "https://cricclubs.com/ColoradoCricket/viewScorecard.do?matchId=$cc_game_id";
	if ($db->Exists("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id")) {
		$db->QueryRow("SELECT * FROM scorecard_game_details WHERE cricclubs_game_id = $cc_game_id");
		$db->BagAndTag();

		$ccl_game_id = $db->data['game_id'];
		$db->Update("UPDATE scorecard_game_details SET league_id = '$ccl_league_id', season = '$ccl_season_id', week = '$week', awayteam = '$ccl_awayteam_id', hometeam = '$ccl_hometeam_id', toss_won_id = '$ccl_toss_team_id', batting_first_id = '$ccl_batting_first_id', batting_second_id = '$ccl_batting_second_id', ground_id = '$ccl_ground_id', game_date = '$ccl_game_date',result = '$result', maxovers = '$cc_total_overs', report = '$report_link' WHERE game_id = $ccl_game_id");
		if ($db->a_rows != -1) {
			echo "<p>The scorecard has been updated</p>\n";
		} else {
			echo "<p>The scorecard could not be updated at this time.</p>\n";
		}
	} else {
		$db->Insert("INSERT INTO scorecard_game_details (league_id,season,week,awayteam,hometeam,toss_won_id,batting_first_id, batting_second_id,ground_id,game_date,result,maxovers,isactive,cricclubs_game_id,report) VALUES ('$ccl_league_id','$ccl_season_id','$week','$ccl_awayteam_id','$ccl_hometeam_id','$ccl_toss_team_id','$ccl_batting_first_id','$ccl_batting_second_id','$ccl_ground_id','$ccl_game_date','$result','$cc_total_overs',0,$cc_game_id,'$report_link')");
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
		if($firstIBatsman->getAttribute('bowled_by') != 0 && $firstIBatsman->getAttribute('caught_by') == 0) {
			$how_out = 3;
		} else if ($firstIBatsman->getAttribute('bowled_by') != 0 && $firstIBatsman->getAttribute('caught_by') != 0) {
			echo "Bowled and caught by " . $firstIBatsman->getAttribute('bowled_by') . " " . $firstIBatsman->getAttribute('caught_by');
			$how_out = 4;
		} else if ($firstIBatsman->getAttribute('bowled_by') == 0 && $firstIBatsman->getAttribute('caught_by') == 0) {
			if(strpos($firstIBatsman->getAttribute('how_out'), "not out") !== FALSE) {
				$how_out = 2;
				$notout = 1;
			} else if(strpos($firstIBatsman->getAttribute('how_out'), "run out") !== FALSE) {
				$how_out = 9;
			}
		}
		$runs = $firstIBatsman->getAttribute('runs_scored');
		$balls = $firstIBatsman->getAttribute('balls_faced');
		$assist = get_ccl_player_id($firstIBatsman->getAttribute('caught_by'));
		$bowler = get_ccl_player_id($firstIBatsman->getAttribute('bowled_by'));
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
		if($secondIBatsman->getAttribute('bowled_by') != 0 && $secondIBatsman->getAttribute('caught_by') == 0) {
			$how_out = 3;
		} else if ($secondIBatsman->getAttribute('bowled_by') != 0 && $secondIBatsman->getAttribute('caught_by') != 0) {
			$how_out = 4;
		} else if ($secondIBatsman->getAttribute('bowled_by') == 0 && $secondIBatsman->getAttribute('caught_by') == 0) {
			if(strpos($secondIBatsman->getAttribute('how_out'), 'not out') !== FALSE) {
				$how_out = 2;
				$notout = 1;
			} else if(strpos($secondIBatsman->getAttribute('how_out'), 'run out') !== FALSE) {
				$how_out = 9;
			}
		}
		$runs = $secondIBatsman->getAttribute('runs_scored');
		$balls = $secondIBatsman->getAttribute('balls_faced');
		$assist = get_ccl_player_id($secondIBatsman->getAttribute('caught_by'));
		$bowler = get_ccl_player_id($secondIBatsman->getAttribute('bowled_by'));
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
	$db->Insert("INSERT INTO scorecard_fow_details (game_id, innings_id, fow1, fow2, fow3, fow4, fow5, fow6, fow7, fow8, fow9, fow10) VALUES ('$ccl_game_id','1','777','777','777','777','777','777','777','777','777','777')");
	$db->Insert("INSERT INTO scorecard_fow_details (game_id, innings_id, fow1, fow2, fow3, fow4, fow5, fow6, fow7, fow8, fow9, fow10) VALUES ('$ccl_game_id','2','777','777','777','777','777','777','777','777','777','777')");
}

function get_ccl_player_id ($cc_player_id) {
	global $db;
	if ($db->Exists("SELECT * FROM players WHERE cricclubs_player_id = $cc_player_id")) {
		$db->QueryRow("SELECT * FROM players WHERE cricclubs_player_id = $cc_player_id");
		$db->BagAndTag();

		return $db->data['PlayerID'];
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
