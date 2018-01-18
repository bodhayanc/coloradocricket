<?php

//------------------------------------------------------------------------------
// Video Highlights v1.0
//
// Creator - Rajesh Idate (fccccricketer)
//------------------------------------------------------------------------------

global $video_array, $random;

$video_array = array(
    array("Rohith Ramkumar", "Excellent batting", "videos/anshu4s.swf", "http://www.youtube.com/watch?v=Q63QO_ekEJI"),
    array("Jarrar Jaffari", "Excellent bowling", "videos/anshu4s.swf", "http://www.youtube.com/watch?v=wGxUf4RNwAk")
);

$random = (mt_rand() % count($video_array));

         echo "<table width=\"100%\" height=\"140\" border=\"0\" cellpadding=\"6\" cellspacing=\"0\">\n";
        echo "<tr>\n";
 //       echo "  <td align=\"left\" valigh=\"top\" width=\"50\">&nbsp;</td>\n";
        echo "  <td align=\"left\" valigh=\"top\" class=\"10px\">\n";
        
        echo "<b>Video Highlights:</b><br>\n";

$temp =   $video_array[$random][2];
      
echo "<object classid=\"clsid:D27CDB6E-AE6D-11CF-96B8-444553540000\" id=\"obj2\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0\" border=\"0\" width=\"160\" height=\"120\">\n";
echo "<param name=\"movie\" value= \"$temp\">\n";
echo "<param name=\"quality\" value=\"High\">\n";
echo "<embed src= \"$temp\"\n"; 
echo "pluginspage=\"http://www.macromedia.com/go/getflashplayer\"\n";
echo "type=\"application/x-shockwave-flash\"\n"; 
echo "name=\"obj2\" width=\"160\" height=\"120\"></object>\n"; 


//        echo "  </td>\n";
//        echo "</tr>\n";
//        echo "</table>\n";  
     
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";  
                
?>
