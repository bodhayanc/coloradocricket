<?php

//------------------------------------------------------------------------------
// Show Featured Articles
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_top10_articles_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM news")) {
    $db->QueryRow("SELECT * FROM news WHERE IsFeature != 0 AND IsPending != 1 ORDER BY added DESC LIMIT 10 ");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Featured Articles</a></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Featured Articles</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////      

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH FEATURED ARTICLES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
	$search = isset($_GET['search']) ? $_GET['search'] : '';
	echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // 10 Recent User Articles Box
    //////////////////////////////////////////////////////////////////////////////////////////      

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;RECENT FEATURED ARTICLES</td>\n";
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
        $vw = $db->data['views'];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
        echo "    <td align=\"right\" class=\"9px\">$a</td>\n";     
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">featured articles archives</a> &raquo;</p>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no user articles in the database\n";
    }
}


function show_full_articles_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM news")) {
    $db->QueryRow("SELECT * FROM news WHERE IsFeature != 0 AND IsPending != 1 ORDER BY added DESC");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Featured Articles</p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Featured Articles</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////      

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH ARTICLES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // 10 Recent User Articles Box
    //////////////////////////////////////////////////////////////////////////////////////////      

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL FEATURED ARTICLES</td>\n";
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
        $vw = $db->data['views'];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1  \">\n";
        }

        echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
        echo "    <td align=\"right\" class=\"9px\">$a</td>\n";     
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
        echo "There are no user articles in the database\n";
    }
}


function show_full_articles($db,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->QueryRow("SELECT * FROM news WHERE id=$pr AND IsFeature != 0");
    $db->BagAndTag();

    $a = sqldate_to_string($db->data['added']);
    $t = $db->data['title'];
    $di = $db->data['DiscussID'];
    $pd = $db->data['picdesc'];
	$vw = $db->data['views'];

	$db->Update("UPDATE news SET views=$vw+1 WHERE id=$pr");
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
//    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"$PHP_SELF\">Featured Articles</a> &raquo; <font //class=\"10px\">Article</font></p>\n";
   echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <a href=\"articles.php\">Featured Articles</a> &raquo; <font class=\"10px\">Article</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "<b class=\"16px\">$t</b><br>\n";
    
    echo "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    if ($db->data['author'] != "") echo "<i>By " . $db->data['author'] . "</i><br>\n";
    echo "<i>$a</i>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"bottom\">\n";  
    echo "<img src=\"images/icons/icon_email.gif\">&nbsp;<a href=\"$PHP_SELF?news=$pr&ccl_mode=3\">Email article</a>&nbsp;&nbsp;<img src=\"images/icons/icon_print.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/printnews.php?news=$pr&ccl_mode=1\">Print article</a>&nbsp;&nbsp;";
    if($di != 0) echo "<img src=\"images/icons/icon_members.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss article</a>";
    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";

    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";

    
    if ($db->data['picture'] != "") { 
      echo "<table width=\"200\" cellpadding=\"5\" cellspacing=\"0\" border=\"0\" align=\"right\">\n";
      echo " <tr>\n";
      echo "  <td align=\"center\" valign=\"middle\">\n";
      echo "  <div align=\"center\" class=\"photo\"><img src=\"uploadphotos/news/" . $db->data['picture'] . "\" style=\"border: 1 solid #393939\">\n";
      if($pd != "" ) echo "<br><br><div align=\"left\">$pd</div>";
      echo "  </div>\n";      
      echo "  </td>\n";
      echo " </tr>\n";
      echo "</table>\n";
    } else {
      echo "";
    }
    
    echo "<p>" . $db->data['article'] . "</p>\n"; 
    
    echo "<p>This article has been viewed <b>$vw</b> times!</p>\n";

    $sitevar = "http://www.coloradocricket.org/newsarchives.php?news=$pr&ccl_mode=1";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
    echo "<div align=\"right\"><img src=\"images/icons/icon_email.gif\">&nbsp;<a href=\"$PHP_SELF?news=$pr&ccl_mode=3\">Email article</a>&nbsp;&nbsp;<img src=\"images/icons/icon_print.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/printnews.php?news=$pr&ccl_mode=1\">Print article</a>&nbsp;&nbsp;";
    if($di != 0) echo "<img src=\"images/icons/icon_members.gif\">&nbsp;<a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss article</a>";
    echo "</div>\n";

    echo "<br>\n";
    
    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}


function search_articles($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

         if (!$db->Exists("SELECT * FROM news")) {
                 echo "<p>There are currently no user articles.</p>\n";
                 return;
         }

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/articles.php\">Featured Articles</a> &raquo; <font class=\"10px\">Search</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Articles Search: $search</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////      

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH ARTICLES</td>\n";
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
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;FEATURED ARTICLES</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    if ($search != "")
    {

        $contains = "article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

        $db->Query("SELECT * FROM news WHERE IsFeature != 0 AND IsPending != 1 AND $contains ORDER BY id DESC");
            if ($db->rows)
            {

            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $a = sqldate_to_string($db->data['added']);
            $t = $db->data['title'];
            $pr = $db->data['id'];
            $vw = $db->data['views'];

            if($i % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td align=\"left\" width=\"65%\"><a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td align=\"right\" class=\"9px\">$vw views</td>\n";
        echo "    <td align=\"right\" class=\"9px\">$a</td>\n";     
        echo "  </tr>\n";

            }
        }
        else
        {
        echo "<tr class=\"trrow1\">\n";
        echo "    <td>\n";
        echo "<p>There are no user articles articles matching that query in any way.</p>\n";

        echo "  </td>\n";
        echo "</tr>\n";

        }
        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<p><a href=\"$PHP_SELF?ccl_mode=4\">featured articles archives</a> &raquo;</p>\n";

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
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/articles.php\">Featured Articles</a> &raquo; <font class=\"10px\">Email article</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Email Featured Article</b><br><br>\n";

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Article: $news</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"sendemailuserarticles.php\" method=\"post\">";
    echo "<input type=\"hidden\" name=\"article\" value=\"$news\">";
    echo "<input type=\"hidden\" name=\"articlelink\" value=\"http://www.coloradocricket.org/articles.php?news=$pr&ccl_mode=1\">";
    echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">\n";
    echo "<tr>\n";
    echo "<td width=\"40%\" align=\"right\">Friends Email :</td>\n";
    echo "<td width=\"60%\"><input type=\"text\" name=\"friend\" size=\"30\" maxlength=\"128\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"40%\" align=\"right\">Your Name:</td>\n";
    echo "<td width=\"60%\"><input type=\"text\" name=\"yourname\" size=\"30\" maxlength=\"128\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td width=\"40%\" align=\"right\">Your Email:</td>\n";
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


    echo "<p>&laquo; <a href=\"$PHP_SELF?news=$pr&ccl_mode=1\">back to the article</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    }

}



function add_article($db)
{
    global $content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Submit Article</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a user feature article</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
    echo "<td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

    echo "<p><font color=\"red\"><b>You cannot use HTML tags!</b></font><br>\n";
    echo "<a href=\"htmlhelp.php\" target=\"_new\">Click here for html formatting help.</a>\n";


    echo "<form action=\"/addarticles.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
    echo "<p>enter the article title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
    echo "<p>enter your name<br><input type=\"text\" name=\"author\" size=\"25\" maxlength=\"255\"></p>\n";
    echo "<p>enter the article detail<br><textarea name=\"article\" cols=\"40\" rows=\"10\" wrap=\"virtual\"></textarea></p>\n";
    echo "<p>upload a  photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
   
    echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
    echo "<li>please save images no larger than 200 pixels wide x 300 pixels high\n";
    echo "<li>portrait images only please! avoid landscape\n";
    echo "<li>only GIF and JPG files only please.</ul></p>\n";
    echo "<p><img src=\"securimage_show.php?sid=".md5(uniqid(time()))."\"></p>";
	echo "<p>please enter above code correctly to submit this article<br><input type=\"text\" name=\"code\" size=24/></p>";
    echo "<p><input type=\"submit\" value=\"add article\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
    echo "</form>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

function do_add_article($db,$title,$author,$article,$picture)
{
    global $PHP_SELF,$content,$action,$SID, $bluebdr, $greenbdr, $yellowbdr;

    // make sure info is present and correct

    if ($title == "" || $article == "") {
        echo "<p class=\"12pt\"><b>Missing information</b></p>\n";
        echo "<p>You must complete the title and the article. Please try again.</p>\n";
        return;
    }

    // setup variables

    $ti = addslashes(trim($title));
    $au = addslashes(trim($author));
    $ar = addslashes(trim($article));


    // all okay

    $db->Insert("INSERT INTO news (listID,fname,lname,email,unsubscribed,htmlemail,date) VALUES (12,'$fname','','$email',0,1,NOW())");
    if ($db->a_rows != -1) {
        echo "<p class=\"12pt\"><b>Thanks!</b></p>\n";
        echo "<p>You have now subscribed to the our mailing list with the email <b>$em</b>. We'll be emailing you when we have new photos and new stories to share soon.</p>\n";
    } else {
        echo "<p class=\"12pt\"><b>Uh oh!</b></p>\n";
        echo "<p>The email address <b>$em</b> could not be added to the database at this time. This is probably due to an error with the database, please try again.</p>\n";
    }
}


// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

if (isset($_GET['ccl_mode'])) {
	switch($_GET['ccl_mode']) {
	case 0:
		show_top10_articles_listing($db);
		break;
	case 1:
		show_full_articles($db,$_GET['news']);
		break;
	case 2:
		search_articles($db,$_GET['search']);
		break;
	case 3:
		show_email($db,$s,$id,$news);
		break;
	case 4:
		show_full_articles_listing($db);
		break;
	case 5:
		 add_article($db);
		break;
	default:
		show_top10_articles_listing($db);
		break;
	}
} else {
	show_top10_articles_listing($db);
}


?>
