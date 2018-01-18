<?php

// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg[login],$dbcfg[pword],$dbcfg[server]);
$db->SelectDB($dbcfg[db]);

$subdb =& new mysql_class($dbcfg['login'], $dbcfg['pword'], $dbcfg['server']);
$subdb->SelectDB($dbcfg['db']);

$db->Query("
		SELECT 
		  g.season, n.SeasonName, 
		  t.TeamAbbrev, 
		  COUNT( s.player_id ) AS Matches, SUM( s.runs ) AS Runs, MAX( s.runs ) AS HS, 
		  s.player_id, p.PlayerID, LEFT(p.PlayerFName,1) AS PlayerInitial, p.PlayerFName, p.PlayerLName 
		FROM 
		  scorecard_batting_details s
		INNER JOIN
		  players p 
		ON 
		  s.player_id = p.PlayerID
		INNER JOIN 
		  scorecard_game_details g
		ON
		  s.game_id = g.game_id
		INNER JOIN
		  teams t
		ON 
		  p.PlayerTeam = t.TeamID
		INNER JOIN
		  seasons n 
		ON
		  g.season = n.SeasonID		  
		WHERE 
		  n.SeasonName LIKE '%{$statistics}%'
		GROUP BY 
		  s.player_id
		ORDER BY
		  Runs DESC");

$rows = array();
for ($r = 0; $r < $db->rows; $r++) {
    $db->GetRow($r);
    $row = $db->data;
    $subdb->QueryRow("SELECT COUNT(b.how_out) AS Notout FROM scorecard_batting_details b INNER JOIN seasons s ON b.season = s.SeasonID WHERE b.how_out = 2 AND b.player_id = {$row['player_id']} AND s.SeasonName LIKE '%{$statistics}%'");
    if ($subdb->data['Notout'] != 0) {
        $outin = $row['Matches'] - $subdb->data['Notout'];
        if ($row['Runs'] >= 1 && $outin >= 1) {
            $scavg = round($row['Runs'] / $outin, 2);
        } else {
            $scavg = -1;
        }
    } else {
        $scavg = -1;
    }
    $row['scavg'] = $scavg;
    $rows[] = $row;
}
$rows = array_column_sort($rows, 'scavg', SORT_NUMERIC, SORT_ASC);

print_r($rows);



/**
* array_column_sort 
* 
* $new_array = array_column_sort($array [, 'col1' [, SORT_FLAG [, SORT_FLAG]]]...);
* 
* original code credited to Ichier (www.ichier.de) here:
* http://uk.php.net/manual/en/function.array-multisort.php
*
* prefixing array indices with "_" idea credit to Steve at mg-rover.org, also here:
* http://uk.php.net/manual/en/function.array-multisort.php
* 
*/
function array_column_sort() 
{
	$args = func_get_args();
	$array = array_shift($args);
	// make a temporary copy of array for which will fix the 
	// keys to be strings, so that array_multisort() doesn't
	// destroy them
	$array_mod = array();
	foreach ($array as $key => $value) {
		$array_mod['_' . $key] = $value;
	} 
	$i = 0;
	$multi_sort_line = "return array_multisort( ";
	foreach ($args as $arg)  {
		$i++;
		if (is_string($arg)) {
			foreach ($array_mod as $row_key => $row) {
				$sort_array[$i][] = $row[$arg];
			}
		} else {
			$sort_array[$i] = $arg;
		}
		$multi_sort_line .= "\$sort_array[" . $i . "], ";
	}
	$multi_sort_line .= "\$array_mod );";
	eval($multi_sort_line);
	// now copy $array_mod back into $array, stripping off the "_"
	// that we added earlier.
	$array = array();
	foreach ($array_mod as $key => $value) {
		$array[ substr($key, 1) ] = $value;
	}
	return $array;
}

?>