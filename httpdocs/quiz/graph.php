<?php

// Quiz-o-matic '76 By Matt Hughes 

Header("Content-type: image/jpeg");

include ("variables.inc");
include ("functions.inc");

Open_Database ($server, $user, $password, $database);

$result = mysql_query("SELECT * FROM " . $_GET['testname']);
$numberofquestions = (mysql_num_fields ($result)-3);
$num_rows = mysql_num_rows($result);

$imagewidth = 40+($numberofquestions*20);
$image = ImageCreate($imagewidth, $graphheight);

$white = ImageColorAllocate($image,255,255,255);
$blue = ImageColorAllocate($image,0,0,255);
$black = ImageColorAllocate($image,0,0,0);

for($i=1 ;$i<=$numberofquestions ; $i++)

        {
        $whichquestion = "q$i";
        $xvalue1 = 20 + ($i*20);
        $xvalue2 = $xvalue1 + 10;
        $yvalue2 = $graphheight-25;
        $yvaluetext = $graphheight-15;
        $total = 0;
        imagestring ($image, 2, $xvalue1, $yvaluetext, $i, $black);
        for($j=1 ;$j<=$num_rows ; $j++)
            {
             $sql = "SELECT * FROM " . $_GET['testname'] . " WHERE id = '$j'";
             $result = mysql_query($sql);
             $myrow = mysql_fetch_array($result);
             $total = $total + $myrow[$whichquestion];
             }

        $yvalue1 = $yvalue2-(($total/$num_rows)*$graphheight)+25;
        imagefilledrectangle($image, $xvalue1, $yvalue1, $xvalue2, $yvalue2, $black);

}

imagestring ($image, 2, 10, ($graphheight*.5), "50%", $black);
imagestring ($image, 2, 10, ($graphheight*.75), "25%", $black);
imagestring ($image, 2, 10, ($graphheight*.25), "75%", $black);
imagestring ($image, 2, 10, 10, "100%", $black);
ImageJPEG($image);
ImageDestroy($image);


?>