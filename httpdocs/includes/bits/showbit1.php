<?php

//------------------------------------------------------------------------------
// Bit Batting Achievement v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_bitbattingachievement($db)
{
    global $PHP_SELF;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    $query = "SELECT sg.game_date, sg.game_id, pl.PlayerID, pl.PlayerFName, pl.PlayerLName, te.TeamAbbrev, sb.runs, sb.balls, sb.fours, sb.sixes, sb.notout FROM players pl, scorecard_batting_details sb, scorecard_game_details sg, teams te WHERE sg.game_id = sb.game_id AND pl.PlayerID = sb.player_id AND sb.opponent = te.TeamID AND sb.runs >= 50 ORDER BY RAND() LIMIT 1";

    if (!$db->Exists($query)) {
        echo "<p>The batting bit is being edited. Please check back shortly.</p>\n";
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
        $bal = $db->data['balls'];
        $fou = $db->data['fours'];
        $six = $db->data['sixes'];
        $not = $db->data['notout'];
        
        if($run >= "50" && $run < "60") {
          $batdesc = "scored a fantastic half century against $tna"; 
        } else if($run >= "60" && $run < "70") {
          $batdesc = "hammered a brilliant half century against $tna"; 
        } else if($run >= "70" && $run < "80") {
          $batdesc = "flayed the $tna bowlers all over the park";
        } else if($run >= "80" && $run < "90") {
          $batdesc = "was in a rampant mood, hammering the $tna bowlers to all parts";        
        } else if($run >= "90" && $run < "100") {
          $batdesc = "bullied the $tna bowlers, unlucky to reach three figures";
        } else if($run >= "100" && $run < "130") {
          $batdesc = "absolutely destroyed $tna, bringing up a well deserved century";
        } else {
          $batdesc = "produced one of the best innings the league has ever seen. The $tna bowlers were made to look substandard by his magnificent innings";
        }
        
        srand((float) microtime() * 10000000);
        
        if($fou == "1" && $six == "0") {
          $fourquote = array(", reaching the boundary on $fou occasion.", ", slapping $fou boundary.", ", spanking $fou four.",", reaching the boundary once."); 
        } else if($fou == "1" && $six == "1") { 
          $fourquote = array(", finding the boundary once and clearing it once.", ", slapping $fou boundary and $fou six.", ", spanking $fou four and $fou six.",", reaching once and clearing the boundary once."); 
        } else if($fou == "0" && $six == "1") {
          $fourquote = array(", clearing the ropes once with no boundaries.", ", slapping one for the maximum.", ", spanking a huge six and no boundaries.",", clearing the boundary once."); 
        } else if($fou > "1" && $six == "0") {
          $fourquote = array(", finding the boundary on $fou occasions.", ", slapping $fou boundaries.", ", spanking $fou fours.",", peppering the boundary with $fou beautiful shots."); 
        } else if($fou > "1" && $six > "1" && $six < "4") {
          $fourquote = array(", finding the boundary on $fou occasions and clearing it $six times!", ", slapping $fou boundaries and $six sixes!", ", spanking $fou fours and tonking $six sixes!",", peppering the boundary $fou times and bypassing it on $six occasions!"); 
        } else if($fou > "1" && $six > "1" && $six >= "4") {
          $fourquote = array(", finding the boundary on $fou occasions and clearing it an amazing $six times!", ", slapping $fou boundaries and an amazing $six sixes!", ", spanking $fou fours and tonking and amazing $six sixes!",", peppering the boundary $fou times and bypassing it on an amazing $six occasions!"); 
        } else {
          $fourquote = array(".", ".", ".","."); 
        }       
        
        $fourecho = array_rand($fourquote,4);
        
        


        // output story, show the image, if no image show the title

        echo "<table width=\"100%\" height=\"70\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valigh=\"top\" width=\"50\" background=\"/images/battingbit.gif\">&nbsp;</td>\n";
        echo "  <td align=\"left\" valigh=\"top\" class=\"10px\" background=\"/images/battingbitbg.jpg\">\n";
        
        echo "<b>Fun Fact:</b><br>\n";
        
        echo "On $gda, <a href=\"players.php?players=$pid&ccl_mode=1\">$pfn $pln</a> $batdesc, finishing with $run";
        if($bal > 0 ) echo " from $bal balls";
        echo $fourquote[$fourecho[1]];
        echo " [<a href=\"scorecardfull.php?game_id=$gid&ccl_mode=4\">scorecard</a>]";

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

show_bitbattingachievement($db);

?>