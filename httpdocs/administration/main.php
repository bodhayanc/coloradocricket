<?php

//------------------------------------------------------------------------------
// Site Control v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

require "../includes/class.mysql.inc";
require "../includes/general.config.inc";
require "../includes/general.functions.inc";
require "../includes/class.fasttemplate.inc";

session_start();

// if the person hasn't logged on yet
$USER=$_SESSION['userdata'];

if ($USER[email] == "" || $SID == "") {
    header("Location: http://$pathcfg[urlroot]/$pathcfg[adir]/index.php?again=3");
    exit;
}

$content = "";
$menu = "";
$jscript = "";
?>


<html>
<head>
<title>Colorado Cricket League - Administration</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="/includes/css/cricket.css" type="text/css">
<link rel="stylesheet" type="text/css" href="/includes/javascript/popup_calendar.css" media="screen">

<script language="JavaScript" src="/includes/javascript/openwindow.js"></script>
<script language="JavaScript" src="/includes/javascript/picker.js"></script>
<script language="JavaScript" src="/includes/javascript/richtext.js"></script>
<script type="text/javascript" src="/includes/javascript/oodomimagerollover.js"></script>
<SCRIPT type="text/javascript" src="/includes/javascript/popup_calendar.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("../includes/header.php"); ?>

    <table width="180" border="0" cellspacing="0" cellpadding="0" bgcolor="#030979">
        <tr>
          <td><img src="/images/nav-top.gif" width="150" height="20"></td>
        </tr>
        <tr>
          <td>

          <table width="180" border="0" cellspacing="1" cellpadding="0" align="right">

            <?php

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MAIN SCREEN
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

              echo "<tr>\n";

              if($page == "main") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"main.php?SID=$SID\" class=\"menu\">Main Screen</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// GLOBAL SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            if (($USER[flags][$f_user_admin]) ||  ($USER[flags][$f_news_admin]) || ($USER[flags][$f_history_admin])) {

              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;GLOBAL SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

            echo "";

            }

            // User Administration

            if ($USER[flags][$f_user_admin]) {

              echo "<tr>\n";

              if($action == "useradmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=useradmin\" class=\"menu\">User Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // News Administration

            if ($USER[flags][$f_news_admin]) {

              echo "<tr>\n";

              if($action == "newsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=newsadmin\" class=\"menu\">News Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Featured Article Administration

            if ($USER[flags][$f_news_admin]) {

              echo "<tr>\n";

              if($action == "articleadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=articleadmin\" class=\"menu\">Article Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // History Administration

            if ($USER[flags][$f_history_admin]) {

              echo "<tr>\n";

              if($action == "historyadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=historyadmin\" class=\"menu\">History Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // CCL Documents Administration

            if ($USER[flags][$f_ccldocuments_admin]) {

              echo "<tr>\n";

              if($action == "ccldocumentsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=ccldocumentsadmin\" class=\"menu\">Documents Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Sponsors Administration

            if ($USER[flags][$f_sponsors_admin]) {

              echo "<tr>\n";

              if($action == "sponsorsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=sponsorsadmin\" class=\"menu\">Sponsors Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }           

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// LEAGUE SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            if (($USER[flags][$f_clubs_admin]) ||  ($USER[flags][$f_teams_admin]) || ($USER[flags][$f_grounds_admin])) {

              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;LEAGUE SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

            echo "";

            }

            // Clubs Administration

            if ($USER[flags][$f_clubs_admin]) {

              echo "<tr>\n";

              if($action == "clubsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=clubsadmin\" class=\"menu\">Clubs Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Teams Administration

            if ($USER[flags][$f_teams_admin]) {

              echo "<tr>\n";

              if($action == "teamsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=teamsadmin\" class=\"menu\">Teams Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Grounds Administration

            if ($USER[flags][$f_grounds_admin]) {

              echo "<tr>\n";

              if($action == "groundsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=groundsadmin\" class=\"menu\">Grounds Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }


            // Schedule Administration

            if ($USER[flags][$f_schedule_admin]) {

              echo "<tr>\n";

              if($action == "scheduleadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=scheduleadmin\" class=\"menu\">Schedule Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Points Table Administration

            if ($USER[flags][$f_ladder_admin]) {

              echo "<tr>\n";

              if($action == "ladderadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=ladderadmin\" class=\"menu\">Standings Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Champions Administration

            if ($USER[flags][$f_champions_admin]) {

              echo "<tr>\n";

              if($action == "championsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=championsadmin\" class=\"menu\">Champions Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }           

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PLAYER SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            if (($USER[flags][$f_featuredmember_admin]) || ($USER[flags][$f_player_admin])) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;PLAYER SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }

            // Player Administration

            if ($USER[flags][$f_player_admin]) {

              echo "<tr>\n";

              if($action == "playeradmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=playeradmin\" class=\"menu\">Players Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Featured Member Administration

            if ($USER[flags][$f_featuredmember_admin]) {

              echo "<tr>\n";

              if($action == "featuredmemberadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=featuredmemberadmin\" class=\"menu\">Featured Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // CCL Officers Administration

            if ($USER[flags][$f_cclofficers_admin]) {

              echo "<tr>\n";

              if($action == "cclofficersadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cclofficersadmin\" class=\"menu\">CCL Officers Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Awards Administration

            if ($USER[flags][$f_awards_admin]) {

              echo "<tr>\n";

              if($action == "awardsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=awardsadmin\" class=\"menu\">Player Awards Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// COUGARS SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            if (($USER[flags][$f_cougarsnews_admin]) || ($USER[flags][$f_cougarsschedule_admin]) || ($USER[flags][$f_cougarsplayers_admin])) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#025A43\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;COUGARS SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }


            // Cougars News Administration

            if ($USER[flags][$f_cougarsnews_admin]) {

              echo "<tr>\n";

              if($action == "cougarsnewsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cougarsnewsadmin\" class=\"menu\">News Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Cougars Schedule Administration

            if ($USER[flags][$f_cougarsschedule_admin]) {

              echo "<tr>\n";

              if($action == "cougarsscheduleadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cougarsscheduleadmin\" class=\"menu\">Schedule Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Cougars Players Administration

            if ($USER[flags][$f_cougarsplayers_admin]) {

              echo "<tr>\n";

              if($action == "cougarsplayersadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cougarsplayersadmin\" class=\"menu\">Players Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }           

            // Cougars Clubs Administration

            if ($USER[flags][$f_cougarsclubs_admin]) {

              echo "<tr>\n";

              if($action == "cougarsclubsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cougarsclubsadmin\" class=\"menu\">Clubs Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Cougars Teams Administration

            if ($USER[flags][$f_cougarsteams_admin]) {

              echo "<tr>\n";

              if($action == "cougarsteamsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cougarsteamsadmin\" class=\"menu\">Teams Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Cougars Grounds Administration

            if ($USER[flags][$f_cougarsgrounds_admin]) {

              echo "<tr>\n";

              if($action == "cougarsgroundsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=cougarsgroundsadmin\" class=\"menu\">Grounds Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }           
            

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// TENNIS CRICKET SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            if (($USER[flags][$f_tennisdocuments_admin]) || ($USER[flags][$f_tennisnews_admin]) || ($USER[flags][$f_tennisschedule_admin]) || ($USER[flags][$f_tennisplayers_admin]) || ($USER[flags][$f_tennisteams_admin])) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#DE9C06\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;TENNIS SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }


            // Tennis News Administration

            if ($USER[flags][$f_tennisnews_admin]) {

              echo "<tr>\n";

              if($action == "tennisnewsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisnewsadmin\" class=\"menu\">News Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Tennis Documents Administration

            if ($USER[flags][$f_tennisdocuments_admin]) {

              echo "<tr>\n";

              if($action == "tennisdocumentsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisdocumentsadmin\" class=\"menu\">Documents Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }


            // Tennis Schedule Administration

            if ($USER[flags][$f_tennisschedule_admin]) {

              echo "<tr>\n";

              if($action == "tennisscheduleadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisscheduleadmin\" class=\"menu\">Schedule Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Tennis Players Administration

            if ($USER[flags][$f_tennisplayers_admin]) {

              echo "<tr>\n";

              if($action == "tennisplayersadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisplayersadmin\" class=\"menu\">Players Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Tennis Clubs Administration

            if ($USER[flags][$f_tennisclubs_admin]) {

              echo "<tr>\n";

              if($action == "tennisclubsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisclubsadmin\" class=\"menu\">Clubs Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Tennis Teams Administration

            if ($USER[flags][$f_tennisteams_admin]) {

              echo "<tr>\n";

              if($action == "tennisteamsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisteamsadmin\" class=\"menu\">Teams Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
            // Tennis Points Table Administration

            if ($USER[flags][$f_tennisladder_admin]) {

              echo "<tr>\n";

              if($action == "tennisladderadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisladderadmin\" class=\"menu\">Standings Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }


            
            // Tennis Groups Administration

            if ($USER[flags][$f_tennisgroups_admin]) {

              echo "<tr>\n";

              if($action == "tennisgroupsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=tennisgroupsadmin\" class=\"menu\">Groups Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MISCELLANOUS TABLE SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	    $execute_it_always=false;

            if (($execute_it_always) || ($USER[flags][$f_seasons_admin])) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;MISCELLANOUS SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }

            // Seasons Administration

            if ($execute_it_always || (($USER[flags][$f_seasons_admin]) || ($USER[flags][$f_leagues_admin]))) {

              echo "<tr>\n";

              if($action == "seasonsadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=seasonsadmin\" class=\"menu\">Seasons Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // League Management Administration

            if ($$execute_it_always || $USER[flags][$f_leagues_admin]) {

              echo "<tr>\n";

              if($action == "leaguesadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=leaguesadmin\" class=\"menu\">League Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR TABLE SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



            if ($USER[flags][$f_cal_event_admin]) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;CALENDAR SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }

            // Category Administration

            if ($USER[flags][$f_cal_cat_admin]) {

              echo "<tr>\n";

              if($action == "calendarcatadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=calendarcatadmin\" class=\"menu\">Category Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }           

            // Event Administration

            if ($USER[flags][$f_cal_event_admin]) {

              echo "<tr>\n";

              if($action == "calendareventadmin") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=calendareventadmin\" class=\"menu\">Event Admin</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }
            
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PHOTO GALLERY SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*

            if (($USER[flags][$f_image_gallery]) || ($USER[flags][$f_image_photos])) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;PHOTO GALLERIES\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }

            // Gallery Administration

            if ($USER[flags][$f_image_gallery]) {

              echo "<tr>\n";

              if($action == "imagegallery") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=imagegallery\">GALLERY ADMIN</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Photo Administration

            if ($USER[flags][$f_image_photos]) {

              echo "<tr>\n";

              if($action == "imagephotos") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=imagephotos\">PHOTO ADMIN</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

*/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// MAILING LIST SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*

            if (($USER[flags][$f_ml_lists]) || ($USER[flags][$f_ml_emails]) || ($USER[flags][$f_ml_archive]) || ($USER[flags][$f_ml_send])) {
              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;MAILING LIST\n";
              echo "  </td>\n";
              echo "</tr>\n";

              } else {

              echo "";

              }

            // Mailing Lists Administration

            if ($USER[flags][$f_ml_lists]) {

              echo "<tr>\n";

              if($action == "ml_lists") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">LIST ADMIN</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Mailing Emails Administration

            if ($USER[flags][$f_ml_emails]) {

              echo "<tr>\n";

              if($action == "ml_emails") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">EMAIL ADMIN</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }


            // Mailing Send Administration

            if ($USER[flags][$f_ml_send]) {

              echo "<tr>\n";

              if($action == "ml_send") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=ml_send\">SEND EMAIL</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

            // Mailing Archives Administration

            if ($USER[flags][$f_ml_archive]) {

              echo "<tr>\n";

              if($action == "ml_archive") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"$PHP_SELF?SID=$SID&action=ml_archive\">ARCHIVE ADMIN</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            } else {

              echo "";

            }

*/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// PERSONAL SETTINGS
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



              echo "<tr>\n";
              echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
              echo "  &nbsp;USER SETTINGS\n";
              echo "  </td>\n";
              echo "</tr>\n";

            // Help Screen

              echo "<tr>\n";

              if($page == "viewhelp") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"javascript:popUp('viewhelp.php?show=$action');\" class=\"menu\">Help</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            // Change Password

              echo "<tr>\n";

              if($action == "cpasswd") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"main.php?SID=$SID&action=cpasswd\" class=\"menu\">Change Password</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            // Logout

              echo "<tr>\n";

              if($page == "logout") {
              echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
              echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              }

              echo "  &nbsp;- <a href=\"logout.php?SID=$SID\" class=\"menu\">Logout</a>\n";
              echo "  </td>\n";
              echo "</tr>\n";

            ?>

          </table>

          </td>
        </tr>
        <tr>
          <td><img src="/images/nav-base.gif" width="150" height="20"></td>
        </tr>
      </table>
      <br>

    </td>
    <td bgcolor="#FFFFFF" valign="top">

    <table width="100%" cellpadding="10" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">

          <?php
          // open up db connection now so you don't have to in every other file

          $db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
          $db->SelectDB($dbcfg[db]);

          // process the action

          if (!isset($action) || $action == "") {

            echo "<p>Logged in as: <b>" . $USER[email] . "</b>.<br>\n";
            echo "Last logged in at: <b>" . $USER[laston] . "</b>.</p>\n";

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "<tr>\n";
            echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Welcome to the Administration Panel</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
            echo "  <tr>\n";
            echo "    <td>\n";

            echo "<p>" . $USER[fname] . ", welcome to the  Admin Panel. If you are a new user, please read on below.</p>\n";
            echo "<p>Otherwise, please make your selection from the menu on the left.</p>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table><br>\n";



            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "<tr>\n";
            echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;New Users please read</td>\n";
            echo "</tr>\n";
            echo "<tr>\n";
            echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
            echo "  <tr>\n";
            echo "    <td>\n";

            echo "<p>Please make your selections from the menu on the left. The selections are based on user security level. Once you are within an administration section you may click help to get help on that particular area of the site control administration panel.</p>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table><br>\n";

        } else include $action . ".php";

?>



          </td>
        </tr>
        </table>

    </td>

    <td width="450" bgcolor="#D0C7C0" valign="top">

    <?php include("../includes/right.php"); ?>


    </td>

  </tr>

<?php include("../includes/footer.php"); ?>

</table>







</body>
</html>
