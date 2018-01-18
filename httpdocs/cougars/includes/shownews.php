<?php


//------------------------------------------------------------------------------
// News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_short_news($db,$s=0,$limit=4,$len=250)
{
    global $PHP_SELF;

    if (!$db->Exists("SELECT * FROM news WHERE newstype=2")) {
        echo "<p>There is no news in the database at this time.</p>\n";
        return;
    }

    // output article
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    //echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    //echo "<tr>\n";
    //echo "  <td align=\"left\" valign=\"top\">\n";
    //echo "  &nbsp;\n";
    //echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
    //echo "</tr>\n";
    //echo "</table>\n";

    // process features
    if ($db->Exists("SELECT * FROM news WHERE newstype=2")) {
        //$db->Query("SELECT * FROM news WHERE IsPending = 0 AND MasterID = '' ORDER BY featureexpire DESC, id DESC LIMIT $limit");
        $db->Query("SELECT n.*,s.id AS SubID,s.MasterID,s.SubTitle FROM news n LEFT JOIN news s ON n.id = s.MasterID WHERE n.IsPending = 0 AND n.newstype=2 ORDER BY n.added DESC, n.id DESC LIMIT $limit");

        // output featured articles
        for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $db->DeBagAndTag();

            // get short version of story
            $story = "";
            if ($story != "" && strlen($story)>$len) {
                $story = substr($db->data[article],0,$len);
                while($story[strlen($story)-1] != " ") {
                    $story = substr($story,0,-1);
                }
                $story = substr($story,0,-1);
            } else {
                $story = substr($db->data[article],0,$len);
            }

            $story .= "...";
            $a = $story;
            $t = $db->data[title];
            $au = $db->data[author];
            $id = $db->data[id];
            $pr = $db->data[id];
            $di = $db->data[DiscussID];
            $date = sqldate_to_string($db->data[added]);
            $vw = $db->data[views];
            
            $sid = $db->data[SubID];
            $smi = $db->data[MasterID];
            $sst = $db->data[SubTitle];
            
            $nty = $db->data[newstype];
            
            if ($db->data[picture] != "") echo "<img width=\"80\" src=\"http://www.coloradocricket.org/uploadphotos/news/" . $db->data[picture] . "\" align=\"right\" style=\"border: 1 solid #393939\">\n";

            echo "  <span class=\"newstitle\">$t</span><br>\n";

            if($nty == 1) {
              echo "  <span class=\"newsauthor\">League news by $au<br></span>";
            } else if($nty == 2) {
              echo "  <span class=\"newsauthor\">Cougars news by $au<br></span>";
            } else if($nty == 3) {
              echo "  <span class=\"newsauthor\">Tennis news by $au<br></span>";
            } else {
              echo "  <span class=\"newsauthor\">by $au<br></span>";
            }

            echo "  <span class=\"newsdate\">&raquo;$date (viewed $vw times)<br></span>";
            echo "  <br>$a";

            // add the 'more' or 'discuss' or subitem links
            echo "  <br><br>";
            
            if($smi != "") echo " <a href=\"news.php?news=$sid&ccl_mode=1\">$sst</a> | ";
            if($di != 0) echo "  <a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss</a> | ";
            
            if($nty == 1) {
              echo "  <a href=\"news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
            } else if($nty == 2) {
              echo "  <a href=\"http://cougars.coloradocricket.org/news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
            } else if($nty == 3) {
              echo "  <a href=\"http://tennis.coloradocricket.org/news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
            } else {
              echo "  <a href=\"news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
            }
            
            echo "  <hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"left\">\n\n";

        }
    } else {
        ++$cnt;
    }
            // finish off
            echo "  </td>\n";
            echo "</tr>\n";
            echo "</table>\n";
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg[db]);

show_short_news($db,$s,4);

?>