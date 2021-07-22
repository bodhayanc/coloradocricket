<?php

// page views sent to the following flat file

$logfile = "includes/visits.txt";
$length = "8";

$filer = fopen("$logfile","r");
 $pageview = fgets($filer,$length);
 echo("$pageview");
fclose($filer);

$filew = fopen("$logfile","w");
 fwrite($filew,$pageview + 1);
fclose($filew);

?>
