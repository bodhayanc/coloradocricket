<?php
//header ("Content-type: image/png");
//$im = imagecreate (300, 300);
//$white = imagecolorallocate ($im,255,255,255);
//$blue = imagecolorallocate ($im,0,0,255);
//$black = imagecolorallocate ($im,0,0,0);
//imagestring ($im, 5, 0, 0, "I Love GDLib!!!", $blue);
//imagefilledrectangle ($im,50,50,100,100,$blue);
//imagerectangle ($im,50,50,100,100,$black);
//imagepng($im);
header ("Content-type: image/png");
$im = imagecreatefrompng ("graphtemp.png");
$red = imagecolorallocate ($im, 255, 0, 0);
$black = imagecolorallocate ($im, 0, 0, 0);
mysql_connect("localhost", "colora12", "ccl/2004");
mysql_query("USE colora12_cricket");
$optionsquery = mysql_query("SELECT PlayerID,PlayerLName * FROM Players WHERE PlayerID=113");
$numoptions = mysql_num_rows($optionsquery);
$pollquery = mysql_query("SELECT * FROM scorecard_batting_details");
$numvotes = mysql_num_rows($pollquery);
$xval = 30;
$barwidth = floor(300/$numoptions);
for ($i=0;$i<=($numoptions-1);$i++) 
{
    $voteoption = mysql_result($optionsquery,$i,'PlayerLName');
    $votevalue = mysql_result($optionsquery,$i,'PlayerID');
    $currentnumquery = mysql_query("SELECT * FROM scorecard_batting_details WHERE player_id='$votevalue'");
    $currentnum = mysql_num_rows($currentnumquery);
    $per = floor(($currentnum/$numvotes)*184);
    $rper = floor(($currentnum/$numvotes)*100);
    imagefilledrectangle ($im, $xval, (200-$per), ($xval+$barwidth), 200, $red);
    imagerectangle ($im, $xval, (200-$per), ($xval+$barwidth), 200, $black);
    $xval+=($barwidth+10);
}
imagepng($im);
?>