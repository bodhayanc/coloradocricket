<?php

	require ("general.config.inc");
    require ("class.mysql.inc");
    require ("class.fasttemplate.inc");
    require ("general.functions.inc");
	set_time_limit(0); 
	ignore_user_abort(true);
	ini_set('max_execution_time', 0);
	
//------------------------------------------------------------------------------
// Mini Scorecard v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function read_cricclubs_player_ids($db)
{
	$playerCsvFile = "/home/colorad2/public_ftp/incoming/csvs/coloradocricket_cricclubs_playerid.csv";
	$row = 1;
	if (($handle = fopen($playerCsvFile, "r")) !== FALSE) {
		while (($data = fgetcsv($handle)) !== FALSE) {
			$num = count($data);
			echo "<p> Processing row number $row: <br /></p>\n";
			$row++;
			if($data[0] != '' && $data[0] != NULL && $data[0] != 'NULL' && $data[1] != '' && $data[1] != NULL && $data[1] != 'NULL') {
				echo "<p> Both First and Last name present for $data[0] $data[1] <br /></p>\n";
				$db->Query("SELECT * FROM players WHERE PlayerFName = \"$data[0]\" AND PlayerLName = \"$data[1]\"");
				if($db->rows > 1) {
					echo "<p> More than one player present with First and Last name $data[0] $data[1] <br /></p>\n";
				} else if($db->rows < 1) {
					echo "<p> No player present with First and Last name $data[0] $data[1] <br /></p>\n";
				} else {
					$db->update("UPDATE players set cricclubs_player_id = $data[2] WHERE PlayerFName = \"$data[0]\" AND PlayerLName = \"$data[1]\"");
				}
			} else if($data[0] != '' && $data[0] != NULL && $data[0] != 'NULL' && ($data[1] == '' || $data[1] == NULL || $data[1] == 'NULL')) {
				echo "<p> Only First name present for $data[0] $data[1] <br /></p>\n";
				$db->Query("SELECT * FROM players WHERE PlayerFName = \"$data[0]\" AND (PlayerLName IS NULL OR PlayerLName = \"\")");
				if($db->rows > 1) {
					echo "<p> More than one player present with First and Last name $data[0] $data[1] <br /></p>\n";
				} else if($db->rows < 1) {
					echo "<p> No player present with First and Last name $data[0] $data[1] <br /></p>\n";
				} else {
					$db->update("UPDATE players set cricclubs_player_id = $data[2] WHERE PlayerFName = \"$data[0]\" AND (PlayerLName IS NULL OR PlayerLName = \"\")");
				}
			} else if(($data[0] == '' || $data[0] == NULL || $data[0] == 'NULL') && $data[1] != '' && $data[1] != NULL && $data[1] != 'NULL') {
				echo "<p> Only Last name present for $data[0] $data[1] <br /></p>\n";
				$db->Query("SELECT * FROM players WHERE (PlayerFName IS NULL OR PlayerFName = \"\") AND PlayerLName = \"$data[1]\"");
				if($db->rows > 1) {
					echo "<p> More than one player present with First and Last name $data[0] $data[1] <br /></p>\n";
				} else if($db->rows < 1) {
					echo "<p> No player present with First and Last name $data[0] $data[1] <br /></p>\n";
				} else {
					$db->update("UPDATE players set cricclubs_player_id = $data[2] WHERE (PlayerFName IS NULL OR PlayerFName = \"\") AND PlayerLName = \"$data[1]\"");
				}
			} else {
				echo "<p> Both First and Last name null for $data[0] $data[1] <br /></p>\n";
			}
		}
		fclose($handle);
	}
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

read_cricclubs_player_ids($db);

?>
