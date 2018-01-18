<?php

//------------------------------------------------------------------------------
// News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_top20_news_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM news")) {
    $db->QueryRow("SELECT * FROM news WHERE IsFeature = 1 OR newstype = 3 ORDER BY id DESC LIMIT 20");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; News</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "<p class=\"16px\">Tennis Cricket News</p>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"><b>SEARCH THE NEWS ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // 25 Recent News Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>RECENT NEWS ARTICLES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $t = htmlentities(stripslashes($db->data['title']));
        $pr = htmlentities(stripslashes($db->data['id']));
        $a = sqldate_to_string($db->data['added']);
        $id = $db->data['id'];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"25%\" class=\"9px\" align=\"right\">$a</td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // News by Month Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">MONTHLY ARCHIVES</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    $yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

        for ($y=0; $y<$db->rows; $y++) {
            $db->GetRow($y);
            $ty = $db->data[theyear];
            $tm = $db->data[themonth];
            $mi = $db->data[monthid];

            echo "$ty&nbsp;&nbsp;";

            echo "<a href=\"news.php?theyear=$ty&themonth=1&monthname=January&ccl_mode=5\">Jan</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
            echo "<br>";

        }

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

        // echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">complete news archives &raquo;</a></p>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no news in the database\n";
    }
}


function show_monthly_news_listing($db,$s,$id,$pr,$theyear,$themonth,$monthname)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM news WHERE year(added) = $theyear and month(added) = $themonth")) {
    $db->QueryRow("SELECT * FROM news WHERE IsFeature != 1 AND newstype = 3 AND year(added) = $theyear AND month(added) = $themonth ORDER BY id DESC");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; $monthname $theyear</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">$monthname $theyear Tennis News</p>\n"; 
    
    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"><b>SEARCH THE NEWS ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // 25 Recent News Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>NEWS ARTICLES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $t = htmlentities(stripslashes($db->data['title']));
        $pr = htmlentities(stripslashes($db->data['id']));
        $a = sqldate_to_string($db->data['added']);
        $id = $db->data['id'];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"25%\" class=\"9px\" align=\"right\">$a</td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // News by Month Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\"><b>MONTHY ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    $yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

        for ($y=0; $y<$db->rows; $y++) {
            $db->GetRow($y);
            $ty = $db->data[theyear];
            $tm = $db->data[themonth];
            $mi = $db->data[monthid];

            echo "$ty&nbsp;&nbsp;";

            echo "<a href=\"news.php?theyear=$ty&themonth=1&monthname=January&ccl_mode=5\">Jan</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
            echo "<br>";

        }

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

        // echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">complete news archives &raquo;</a></p>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; $monthname $theyear</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">$monthname $theyear Tennis News</p>\n"; 

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"><b>SEARCH THE NEWS ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // 25 Recent News Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>NEWS ARTICLES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<p>There are no articles for $monthname $theyear.</p>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // News by Month Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\"><b>MONTHLY ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    $yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

        for ($y=0; $y<$db->rows; $y++) {
            $db->GetRow($y);
            $ty = $db->data[theyear];
            $tm = $db->data[themonth];
            $mi = $db->data[monthid];

            echo "$ty&nbsp;&nbsp;";

            echo "<a href=\"news.php?theyear=$ty&themonth=1&monthname=January&ccl_mode=5\">Jan</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
            echo "<br>";

        }

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

        // echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">complete news archives &raquo;</a></p>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
    }
}


function show_full_news_listing($db,$s,$id,$pr)
{
    global $PHP_SELF;

    if ($db->Exists("SELECT * FROM news")) {
    $db->QueryRow("SELECT * FROM news WHERE IsFeature = 1 OR newstype = 3 ORDER BY id DESC");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; News</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "<p class=\"16px\">Tennis News</p>\n"; 
    

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#000033\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">Search the news archives</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // 10 Recent News Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#000033\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#000033\" class=\"whitemain\" height=\"23\">&nbsp;<b>ALL NEWS ARTICLES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $t = htmlentities(stripslashes($db->data['title']));
        $pr = htmlentities(stripslashes($db->data['id']));
        $a = sqldate_to_string($db->data['added']);
        $id = $db->data['id'];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"25%\" class=\"9px\" align=\"right\">$a</td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no news in the database\n";
    }
}


function show_full_news($db,$s,$id,$pr)
{
    global $PHP_SELF, $greenbdr;

    $db->QueryRow("SELECT * FROM news WHERE id=$pr");
    $db->BagAndTag();

    $a = sqldate_to_string($db->data['added']);
    $t = $db->data['title'];
    $di = $db->data['DiscussID'];
    $pd = $db->data[picdesc];
    $vw = $db->data['views'];

    $db->Update("UPDATE news SET views=$vw+1 WHERE id=$pr");

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    // output icons
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"$PHP_SELF\">News</a> &raquo; News article</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    //echo "<img src=\"images/icons/icon_email.gif\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=3\">Email article</a>&nbsp;&nbsp;<img src=\"images/icons/icon_print.gif\"><a href=\"http://www.coloradocricket.org/printnews.php?news=$pr&ccl_mode=1\">Print article</a>&nbsp;&nbsp;<img src=\"images/icons/icon_youremail.gif\"><a href=\"javascript:subscribeWindow()\">Sign up for email updates</a>\n";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";

    // output story
    echo "<b class=\"14px\">$t</b><br>\n";

    if ($db->data['author'] != "") echo "<p><b>Author:</b> " . $db->data['author'] . "<br>\n";
    echo "<b>Date submitted:</b> $a</p>\n";

    if ($db->data['picture'] != "") { 
      echo "<table width=\"200\" cellpadding=\"4\" cellspacing=\"0\" border=\"0\" align=\"right\">\n";
      echo " <tr>\n";
      echo "  <td align=\"center\" valign=\"middle\">\n";
      echo "  <div align=\"center\" class=\"photo\"><img src=\"http://www.coloradocricket.org/uploadphotos/news/" . $db->data['picture'] . "\" style=\"border: 1 solid #393939\">\n";
      if($pd != "" ) echo "<br><br><div align=\"left\">$pd</div>";
      echo "  </div>\n";
      echo "  </td>\n";
      echo " </tr>\n";
      echo "</table>\n";
    } else {
      echo "";
    }

    echo "<p>" . $db->data['article'] . "</p>\n";

    // output link back
    $sitevar = "http://www.coloradocricket.org/newsarchives.php?news=$pr&ccl_mode=1";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
    echo "<div align=\"left\"><b><img src=\"http://www.coloradocricket.org/images/icons/icon_email.gif\">&nbsp;<a href=\"$PHP_SELF?news=$pr&ccl_mode=3\">Email article</a>&nbsp;&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_print.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/printnews.php?news=$pr&ccl_mode=1\">Print article</a>&nbsp;&nbsp;";
    if($di != 0) echo "<b><img src=\"http://www.coloradocricket.org/images/icons/icon_members.gif\"><a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\"> Discuss article</a>";
    echo "</b></div>\n";
    
    echo "<br>\n";

    // output article
    echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    // Clubs Box

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>LATEST NEWS</b></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
    echo "  <table class=\"bordergrey\" width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


    // process features
        $db->Query("SELECT * FROM news WHERE IsFeature != 1 AND newstype = 3 ORDER BY id DESC LIMIT 5");

        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            $t = $db->data['title'];
            $au = $db->data['author'];
            $id = $db->data['id'];
            $pr = $db->data['id'];
            $date = sqldate_to_string($db->data['added']);

        //if($i % 2) {
        //  echo "<tr class=\"trrow1\">\n";
        //} else {
        //  echo "<tr class=\"trrow2\">\n";
        //}

        echo "  <tr class=\"trrow1\">\n";
        echo "    <td width=\"100%\"><a href=\"news.php?news=$pr&ccl_mode=1\">$t</a>\n";
        if($db->data['picture'] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";

        }


            echo "</table>\n";

            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

            // finish off
            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";

    echo "<p><a href=\"$PHP_SELF\">&laquo; back to news listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";


}


function search_news($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

         if (!$db->Exists("SELECT * FROM news")) {
                 echo "<p>There are currently no news.</p>\n";
                 return;
         }

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; Search News</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">Tennis News Search: $search</p>\n"; 

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"><b>SEARCH THE NEWS ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>NEWS ARTICLES CONTAINING \"$search\"</b></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";


    if ($search != "")
    {

        $contains = "(article LIKE '%{$search}%' OR title LIKE '%{$search}%')";

        $db->Query("SELECT * FROM news WHERE $contains AND newstype = 3 ORDER BY id DESC");
            if ($db->rows)
            {


            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $a = sqldate_to_string($db->data['added']);

            if($i % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?news={$db->data['id']}&ccl_mode=1\">{$db->data['title']}</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"25%\" class=\"9px\" align=\"right\">$a</td>\n";
        echo "  </tr>\n";

            }


        }
        else
        {
        echo "<tr class=\"trrow1\">\n";
        echo " <td>\n";
        echo "<p>There are no news articles matching that query in any way.</p>\n";

        echo "  </td>\n";
        echo "</tr>\n";

        }
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // News by Month Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\"><b>MONTHLY ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    $yearrow = $db->QueryRow("SELECT year(`added`) AS theyear FROM news GROUP BY theyear");

        for ($y=0; $y<$db->rows; $y++) {
            $db->GetRow($y);
            $ty = $db->data[theyear];
            $tm = $db->data[themonth];
            $mi = $db->data[monthid];

            echo "$ty&nbsp;&nbsp;";

            echo "<a href=\"news.php?theyear=$ty&themonth=1&monthname=January&ccl_mode=5\">Jan</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=2&monthname=February&ccl_mode=5\">Feb</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=3&monthname=March&ccl_mode=5\">Mar</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=4&monthname=April&ccl_mode=5\">Apr</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=5&monthname=May&ccl_mode=5\">May</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=6&monthname=June&ccl_mode=5\">Jun</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=7&monthname=July&ccl_mode=5\">Jul</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=8&monthname=August&ccl_mode=5\">Aug</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=9&monthname=September&ccl_mode=5\">Sep</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=10&monthname=October&ccl_mode=5\">Oct</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=11&monthname=November&ccl_mode=5\">Nov</a>|";
            echo "<a href=\"news.php?theyear=$ty&themonth=12&monthname=December&ccl_mode=5\">Dec</a>";
            echo "<br>";

        }

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";

        // echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">complete news archives &raquo;</a></p>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        }


 }


 function show_email($db,$s,$id,$pr)
 {
    global $PHP_SELF, $greenbdr;


    if ($db->Exists("SELECT * FROM news")) {
    $db->QueryRow("SELECT * FROM news WHERE id=$pr");
    $db->BagAndTag();

    $news = $db->data['title'];
    $pr = $db->data['id'];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo;Email article</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<p class=\"16px\">Email Article</p>\n";   
    
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Article: $news</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"sendemailnews.php\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"article\" value=\"$news\">";
    echo "<input type=\"hidden\" name=\"articlelink\" value=\"http://tennis.coloradocricket.org/news.php?news=$pr&ccl_mode=1\">";
    echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">\n";
    echo "<tr>\n";
    echo "<td width=\"40%\" align=\"right\">FriendsEmail :</td>\n";
    echo "<td width=\"60%\"><input type=\"text\" name=\"friend\" size=\"30\" maxlength=\"128\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"40%\" align=\"right\">Your Name:</td>\n";
    echo "<td width=\"60%\"><input type=\"text\" name=\"yourname\" size=\"30\" maxlength=\"128\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"40%\" align=\"right\">YourEmail:</td>\n";
    echo "<td width=\"60%\"><input type=\"text\" name=\"yourmail\" size=\"30\" maxlength=\"32\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"40%\">&nbsp;</td>\n";
    echo "<td width=\"60%\"><input type=\"submit\" name=\"Submit\" value=\"Submit\">&nbsp;<input type=\"reset\" name=\"reset\" value=\"Reset\"></td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";


    echo "<p><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">&laquo; back to the article</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    }

}




// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_top20_news_listing($db,$s,$id,$news);
    break;
case 1:
    show_full_news($db,$s,$id,$news);
    break;
case 2:
    search_news($db,$search);
    break;
case 3:
    show_email($db,$s,$id,$news);
    break;
case 4:
    show_full_news_listing($db,$s,$id,$news);
    break;
case 5:
    show_monthly_news_listing($db,$s,$id,$news,$theyear,$themonth,$monthname);
    break;
default:
    show_top20_news_listing($db,$s,$id,$news);
    break;
}


?>
