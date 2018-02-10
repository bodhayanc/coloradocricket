<?php

//------------------------------------------------------------------------------
// General.Config.Inc v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


// Site variables
        $copyright = "copyright &copy; <a href=\"http://www.coastart.net\">Michael Doig</a> 2004.\n";
        $siteurl   = "http://localhost";
        $sitepath  = "/home/colora12/public_html/";
        $limit     = 10;


// database global values
$dbcfg = array (
                'server'  => "localhost",
                'login'   => "root",
                'pword'   => "password1$",
                'db'      => "colora12_cricket"
        );

// database table values
$tbcfg = array (
                'admin'   => "admin",
                'events'  => "events",
                'stories' => "stories",
                'mlarchive' => "mailinglist_archive",
                'mllists'   => "mailinglist_lists",
                'mlemails'  => "mailinglist_emails"
        );

// path config values
$pathcfg = array (
                'urlroot' => "localhost",
                'froot'   => "/home/colora12/public_html/",
                'aroot'   => "/home/colora12/public_html/administration",
                'iroot'   => "/home/colora12/public_html/includes",
                'adir'    => "administration"
        );

// default template settings
$tpl_defaults = array (
                "ACTION"        => "",
                "CONTENT"       => "",
                "TITLE"         => "Administration",
                "MENU"          => "<b>main menu</b>",
                "SID"           => "",
                "DATE"          => date("l, F jS, Y" ,time())
        );


// general config options
$gencfg = array (
                'webmaster'       => "michael@mike250.com",
                'passlen'         => 4
        );

$full_month_name = array(
                "January","Februrary","March","April","May","June","July",
                "August","September","October","November","December"
        );

$full_day_name = array(
                "Sunday","Monday","Tuesday","Wednesday",
                "Thursday","Friday","Saturday"
        );

$short_month_name = array(
                "Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"
        );

$short_day_name = array(
                "Sun","Mon","Tue","Wed","Thu","Fri","Sat"
        );


// flags (add here when you add modules)
$f_user_admin = 0;
$f_news_admin = 1;
$f_generalhtml_admin = 2;;
$f_edit_news = 3;
$f_delete_news = 4;
$f_ccldocuments_admin = 5;
$f_ml_lists = 6;
$f_ml_emails = 7;
$f_ml_archive = 8;
$f_ml_send = 9;
$f_faq_admin = 10;
$f_image_gallery = 11;
$f_image_photos = 12;
$f_poll_admin = 13;
$f_schedule_admin = 14;
$f_history_admin = 15;
$f_clubs_admin = 16;
$f_teams_admin = 17;
$f_grounds_admin = 18;
$f_featuredmember_admin = 19;
$f_player_admin = 20;
$f_ladder_admin = 21;
$f_cclofficers_admin = 22;
$f_cougarsnews_admin = 23;
$f_cougarsschedule_admin = 24;
$f_cougarsplayers_admin = 25;
$f_tennisnews_admin = 26;
$f_tennisschedule_admin = 27;
$f_tennisteams_admin = 28;
$f_tennisplayers_admin = 29;
$f_tennisdocuments_admin = 30;
$f_cougarsteams_admin = 31;
$f_sponsors_admin = 32;
$f_tennisladder_admin = 33;
$f_tennisgroups_admin = 34;
$f_awards_admin = 35;
$f_subnews_admin = 36;
$f_champions_admin = 37;
$f_seasons_admin = 38;
$f_cal_cat_admin = 39;
$f_cal_event_admin = 40;
$f_leagues_admin = 41;
$f_cougarsclubs_admin = 42;
$f_tennisclubs_admin = 43;
$f_cougarsgrounds_admin = 44;

// page colours (for a future release perhaps)

//$bluebdr   = "#025094";
$bluebdr   = "#00417E";
$bluebg    = "#BBCCDD";
$greenbdr  = "#025A43";
$greenbg   = "#99BBAA";
$yellowbdr = "#DE9C06";
$yellowbg  = "#FFDD99";
$redbdr    = "#9E3228"; 
$blackbdr  = "#000000"; 
$tennisbdr = "#83C815";
$cougarbdr = "#030979";


$page_colours = array (
        "#A2E8A0" => "green",
        "#F3D38D" => "orange",
        "#F38F8D" => "red",
        "#F38DF0" => "purple",
        "#BCBAB7" => "grey"
);

// states (just in case you want states in a form sometime)
$states = array (
        "AL" => "Alabama",
        "AK" => "Alaska",
        "AZ" => "Arizona",
        "AR" => "Arkansas",
        "CA" => "California",
        "CO" => "Colorado",
        "CT" => "Connecticut", 
        "DE" => "Delaware",
        "DC" => "Dist. of Columbia", 
        "FL" => "Florida",
        "GA" => "Georgia",
        "GU" => "Guam",
        "HI" => "Hawaii",
        "ID" => "Idaho",
        "IL" => "Illinois",
        "IN" => "Indiana",
        "IA" => "Iowa",
        "KS" => "Kansas",
        "KY" => "Kentucky",
        "LA" => "Louisiana",
        "ME" => "Maine",
        "MD" => "Maryland",
        "MA" => "Massachusetts",
        "MI" => "Michigan",
        "MN" => "Minnesota",
        "MS" => "Mississippi",
        "MO" => "Missouri",
        "MT" => "Montana",
        "NE" => "Nebraska",
        "NV" => "Nevada",
        "NH" => "New Hampshire",
        "NJ" => "New Jersey",
        "NM" => "New Mexico",
        "NY" => "New York",
        "NC" => "North Carolina",
        "ND" => "North Dakota",
        "OH" => "Ohio",
        "OK" => "Oklahoma",
        "OR" => "Oregon",
        "PA" => "Pennsylvania",
        "PR" => "Puerto Rico",
        "RI" => "Rhode Island",
        "SC" => "South Carolina",
        "SD" => "South Dakota",
        "TN" => "Tennessee",
        "TX" => "Texas",
        "UT" => "Utah",
        "VT" => "Vermont",
        "VA" => "Virginia",
        "VI" => "Virgin Islands",
        "WA" => "Washington",
        "WV" => "West Virginia",
        "WI" => "Wisconsin",
        "WY" => "Wyoming"
);


srand((double)microtime()*1000000);

set_include_path(get_include_path() . PATH_SEPARATOR . "D:\\Inetpub\\vhosts\\coloradocricket.org\\httpdocs\\includes");
?>
