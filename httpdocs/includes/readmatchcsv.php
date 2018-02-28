<?php

	require ("general.config.inc");
    require ("class.mysql.inc");
    require ("class.fasttemplate.inc");
    require ("general.functions.inc");
	
//------------------------------------------------------------------------------
// Mini Scorecard v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function read_cricclubs_match_ids($db)
{
	$gameCsvFile = "/home/colorad2/public_ftp/incoming/csvs/coloradocricket_matchid_gameid.csv";
	$row = 1;

	if (($handle = fopen($gameCsvFile, "r")) !== FALSE) {
		while (($data = fgetcsv($handle)) !== FALSE) {
			$num = count($data);
			echo "<p> Processing row number $row: <br /></p>\n";
			$row++;
			if($data[0] != '' && $data[0] != NULL && $data[0] != 'NULL' && $data[1] != '' && $data[1] != NULL && $data[1] != 'NULL') {
				echo "<p> Both CCL and CricClubs game ids are present for $data[0] $data[1] <br /></p>\n";
				$db->Query("SELECT * FROM scorecard_game_details WHERE game_id = '$data[0]'");
				if($db->rows > 1) {
					echo "<p> More than one game present with game id $data[0] <br /></p>\n";
				} else if($db->rows < 1) {
					echo "<p> No game present with game id $data[0] <br /></p>\n";
				} else {
					$db->update("UPDATE scorecard_game_details set cricclubs_game_id = $data[1] WHERE game_id = $data[0]");
				}
			} else if($data[0] != '' && $data[0] != NULL && $data[0] != 'NULL' && ($data[1] == '' || $data[1] == NULL || $data[1] == 'NULL')) {
				echo "<p> Only CCL game id present for $data[0] $data[1] <br /></p>\n";
			} else if(($data[0] == '' || $data[0] == NULL || $data[0] == 'NULL') && $data[1] != '' && $data[1] != NULL && $data[1] != 'NULL') {
				echo "<p> Only Cricclubs game id present for $data[0] $data[1] <br /></p>\n";
			} else {
				echo "<p> Both CCL and CricClubs game ids are null for $data[0] $data[1] <br /></p>\n";
			}
		}
		fclose($handle);
	}
}


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

read_cricclubs_match_ids($db);

?>
