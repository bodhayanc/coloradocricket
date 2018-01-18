<?php

//------------------------------------------------------------------------------
// Bit Batting Achievement v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_bitbowlingachievement($db)
{
    global $PHP_SELF;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    $query = "SELECT sg.game_date, sg.game_id, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, te.TeamAbbrev, sb.runs, sb.wickets, sb.overs FROM players pl, teams te, scorecard_bowling_details sb, scorecard_game_details sg WHERE sb.opponent = te.TeamID AND pl.PlayerID = sb.player_id AND sg.game_id = sb.game_id AND sb.wickets >= 4 ORDER BY RAND() LIMIT 1";
    if (!$db->Exists($query)) {
        echo "<p>The bowling bit is being edited. Please check back shortly.</p>\n";
        return;
    } else {
        $db->QueryRow($query);
        $db->BagAndTag();

        // setup variables

        $pid = $db->data['PlayerID'];
        $pfn = $db->data['PlayerFName'];
        $pln = $db->data['PlayerLName'];  
        $tna = $db->data['TeamAbbrev'];
        $gid = $db->data['game_id'];
        $gda = sqldate_to_string($db->data['game_date']);
        $run = $db->data['runs'];
        $wic = $db->data['wickets'];
        $ove = $db->data['overs'];
        
        if($wic == "4" && $run < "20") {
          $bowldesc = "simply baffled the $tna batsmen, striking $wic times for only $run runs in $ove overs"; 
        } else if ($wic == "4" && $run > "20" && $run < "30") {
          $bowldesc = "had a fantastic day with the ball against $tna, finishing with $wic for $run in $ove overs";           
        } else if ($wic == "4" && $run > "30") {
          $bowldesc = "produced a very handy spell against $tna, finishing with $wic for $run in $ove overs";         
        } else if ($wic == "5" && $run < "20") {
          $bowldesc = "ripped apart the $tna batsmen, who never once looked comfortable, taking $wic for $run in $ove overs"; 
        } else if ($wic == "5" && $run > "20" && $run < "30") {
          $bowldesc = "was devastating against $tna, taking $wic for $run in $ove overs"; 
        } else if ($wic == "5" && $run > "30") {        
          $bowldesc = "bowled beautifully against $tna, finishing with $wic for $run in $ove overs"; 
        } else if ($wic == "6" && $run > "20") {        
          $bowldesc = "was utterly unplayable, $tna batsmen unable to respond to the onslaught, claiming $wic for $run in $ove overs"; 
        } else {
          $bowldesc = "absolutely destroyed $tna, producing one of the best spells ever seen in the league. He sent $wic batsmen packing for only $run runs in $ove overs";
        }

        // output story, show the image, if no image show the title

        echo "<table width=\"100%\" height=\"70\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\" background=\"/images/battingbit.gif\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valigh=\"top\" width=\"50\">&nbsp;</td>\n";
        echo "  <td align=\"left\" valigh=\"top\" class=\"10px\">\n";
        
        echo "<b>Fun Fact:</b><br>\n";
        
        echo "On $gda, <a href=\"players.php?players=$pid&ccl_mode=1\">$pfn $pln</a> $bowldesc. [<a href=\"scorecardfull.php?game_id=$gid&ccl_mode=4\">scorecard</a>]";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";  
        
        }
        
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";  
                
}

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

show_bitbowlingachievement($db);

?>