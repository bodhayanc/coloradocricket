<?php

//------------------------------------------------------------------------------
// Add Player Version 2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


    require ("../includes/general.config.inc");
    require ("../includes/class.mysql.inc");
    require ("../includes/class.fasttemplate.inc");
    require ("../includes/general.functions.inc");

echo "<link rel=\"stylesheet\" href=\"/includes/css/cricket.css\" type=\"text/css\">\n";


function do_add_category($db,$id,$PlayerLName,$PlayerFName,$PlayerClub,$PlayerTeam,$PlayerEmail,$shortprofile,$IsUmpire,$IsPresident,$IsVicePresident,$IsSecretary,$IsTreasurer,$IsCaptain,$IsViceCaptain,$Born,$BattingStyle,$BowlingStyle,$picture,$picture1)
{
    global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

    // setup variables

    $pln = addslashes(trim($PlayerLName));
    $pfn = addslashes(trim($PlayerFName));
    $pcl = addslashes(trim($PlayerClub));
    $pte = addslashes(trim($PlayerTeam));
    $pem = addslashes(trim($PlayerEmail));
    $spr = addslashes(trim($shortprofile));

    $ump = addslashes(trim($IsUmpire));
    $pre = addslashes(trim($IsPresident));
    $vpr = addslashes(trim($IsVicePresident));
    $sec = addslashes(trim($IsSecretary));
    $tre = addslashes(trim($IsTreasurer));
    $cap = addslashes(trim($IsCaptain));
    $vca = addslashes(trim($IsViceCaptain));

    $bor = addslashes(trim($Born));
    $bat = addslashes(trim($BattingStyle));
    $bow = addslashes(trim($BowlingStyle));


    $pa = eregi_replace("\r","",$photo);

    // all okay

    $db->Insert("INSERT INTO players (PlayerLName,PlayerFName,PlayerClub,PlayerTeam,PlayerEmail,shortprofile,IsUmpire,IsPresident,IsVicePresident,IsSecretary,IsTreasurer,IsCaptain,IsViceCaptain,Born,BattingStyle,BowlingStyle,picture,picture1) VALUES ('$pln','$pfn','$pcl','$pte','$pem','$spr','$ump','$pre','$vpr','$sec','$tre','$cap','$vca','$bor','$bat','$bow','$picture','$picture1')");
    if ($db->a_rows != -1) {
        echo "<p>You have now added a new player</p>\n";
        echo "<p>&raquo; <a href=\"addplayer.php\">add another player</a></p>\n";
        echo "<p>&raquo; <a href=\"javascript:window.close()\">close window</a></p>\n";
    } else {
        echo "<p>The player could not be added to the database at this time.</p>\n";
        echo "<p>&laquo; <a href=\"javascript:window.close()\">close window</a></p>\n";
    }
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

do_add_category($db,$id,$PlayerLName,$PlayerFName,$PlayerClub,$PlayerTeam,$PlayerEmail,$shortprofile,$IsUmpire,$IsPresident,$IsVicePresident,$IsSecretary,$IsTreasurer,$IsCaptain,$IsViceCaptain,$Born,$BattingStyle,$BowlingStyle,$picture,$picture1);

?>
