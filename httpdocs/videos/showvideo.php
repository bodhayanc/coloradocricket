<?php

//------------------------------------------------------------------------------
// Video Highlights v1.0
//
// Creator - Rajesh Idate (fccccricketer)
//------------------------------------------------------------------------------

global $video_array, $random;

$video_array = array(
    array("Rohith Ramkumar", "June 4, 2011: Rohith Ramkumar won the featured Player of the Week award as well as the Player of the Match award for his blistering knock of 134 runs in 86 balls that was studded with 6 fours and 13 sixes!!! This was Rohith's second century of the 2011 season, and  fifth century in his CCL career. He was dismissed rather freakishly by a direct throw from Andrew Dunbar in his bowling follow-through. Rohith then captured 3 FCCC wickets. Cricket is such a great leveler that even this great all round performance could not win him this game. He more than made up for this setback by continuing to score heavily with 465 runs in the season, thus topping the CCCC batting charts and leading the team to the premier league championship.", "videos/H 6-4-11 Rohith Ramkumar.swf", "http://www.youtube.com/watch?v=CIhBRttUHtQ"),
    array("Hamayun Zaheer", "June 11, 2011: CSCC captain Hamayun Zaheer led from the front with his excellent spell capturing 4 FCCC wickets (8-0-34-4) and then producing a much needed cameo in the middle by scoring 31 runs in 32 balls with 3 fours, thus helping CSCC  win this hard-fought game. Hamayun led and motivated his team both as captain and player with his ideas and contribution. He was then deservedly named the Player of the Match to cap it all.", "videos/H 6-11-11 Hamayun Zaheer.swf", "http://www.youtube.com/watch?v=HlyHMqRqATc"),
    array("Kit Diasabeygunawardena", "June 11, 2011: During the first game on the new mat with uneven bounce at the Memorial Park, Kit Diasabeygunawardena from FCCC walked into bat with 5 wickets down for just 45 runs and the CSCC bowlers on fire. What Kit then produced was a spectacular counter assault in his inimitable fashion - swinging his bat on almost every ball and connecting cleanly with amazing regularity. He hit the CSCC bowlers all over the park at will, collecting 57 runs in just 30 balls with 1 four and 6 towering sixes. This gem of an innings enabled FCCC set a competitive total to defend that looked impossible when he walked in to bat.", "videos/H 6-11-11 Kit Diasabeygunawardena.swf", "http://www.youtube.com/watch?v=WTNzGahnAMU"),  
    array("Rajesh Rathod", "Aug 28, 2010: A bold expression of intent from Rajesh Rathod. Coming in at 8 down with 75 runs to win, he injected the much-needed urgency in the CCB chase of an imposing FCCC total with quick ones and twos. He then suddenly savaged a delivery from Dipal Patel and sent it screaming over the long-on boundary for a breathtaking six. That shot announced his ambition. He was gunning for a victory. And in Aditya Kasukurt's able company, he achieved a memorable heist for CCB in its very first season. Both shared the "Player of the Match" award. Rajesh scored 45 unbeaten runs off 48 balls with 2 boundaries and that six.", "videos/H 8-28-10 Rajesh Rathod.swf", "http://www.youtube.com/watch?v=VOmmpnxtYXE"),
);

$random = (mt_rand() % count($video_array));

//        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
//        echo "<tr>\n";
//        echo "  <td align=\"left\" valigh=\"top\">\n";

         echo "<table align=\"center\" width=\"98%\" height=\"160\" border=\"2\" cellpadding=\"3\" cellspacing=\"0\" style=\"background-color: rgb(0,219,214);\">\n";
        echo "<tr>\n";
 //       echo "  <td align=\"left\" valigh=\"top\" width=\"50\">&nbsp;</td>\n";
        echo "  <td align=\"center\" valigh=\"top\" width=\"200\" border=\"0\" class=\"10px\">\n";
        
        echo "<b>Video Highlights:</b><br><br>\n";

$temp =   $video_array[$random][2];
      
echo "<object classid=\"clsid:D27CDB6E-AE6D-11CF-96B8-444553540000\" id=\"obj2\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\" border=\"0\" width=\"200\" height=\"150\">\n";
echo "<param name=\"movie\" value= \"$temp\">\n";
echo "<param name=\"quality\" value=\"High\">\n";
echo "<embed src= \"$temp\"\n"; 
echo "pluginspage=\"http://www.macromedia.com/go/getflashplayer\"\n";
echo "type=\"application/x-shockwave-flash\"\n"; 
echo "name=\"obj2\" width=\"200\" height=\"150\"></object>\n"; 

$temp2 =   $video_array[$random][1];
$temp3 =   $video_array[$random][3];

echo "  </td>\n";
echo "  <td align=\"left\" valigh=\"top\" border=\"0\" class=\"10px\">\n";
echo "$temp2 <br><br><b>[<a target=\"_blank\" href=\"$temp3\">Complete Highlights</a>]</b>\n";

//        echo "  </td>\n";
//        echo "</tr>\n";
//        echo "</table>\n";  
     
        echo "</tr>\n";
        echo "</table>\n";  
                
?>
