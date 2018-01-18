<?php


//------------------------------------------------------------------------------
// News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_short_news($db,$s=0,$limit=3,$len=300)
{
    global $PHP_SELF;

    if (!$db->Exists("SELECT * FROM news")) {
        echo "<p>There is no news in the database at this time.</p>\n";
        return;
    }

    // output article
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  &nbsp;\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("../cougars/includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    // process features
    if ($db->Exists("SELECT * FROM news")) {
        $db->Query("SELECT * FROM news WHERE IsFeature = 1 or newstype = 3 ORDER BY id DESC LIMIT $limit");

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

            if ($db->data[picture] != "") echo "<a href=\"news.php?news=$pr&ccl_mode=1\"><img width=\"80\" src=\"http://www.coloradocricket.org/uploadphotos/news/" . $db->data[picture] . "\" align=\"right\" style=\"border: 1 solid #393939\"></a>\n";

            echo "  <span class=\"newstitle\">$t</span><br>\n";
            echo "  <span class=\"newsdate\">&raquo;$date<br></span>";
            echo "  <br>$a";

            // add the 'more' link
            echo "  <br><br>";
            if($di != 0) echo "  <a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss</a> | ";
            echo "  <a href=\"news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
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

show_short_news($db,$s,5);

?>
