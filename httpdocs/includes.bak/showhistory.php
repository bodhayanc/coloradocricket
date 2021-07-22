<?php

//------------------------------------------------------------------------------
// Colorado Cricket History v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_history_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    if ($db->Exists("SELECT * FROM history")) {
    $db->QueryRow("SELECT * FROM history ORDER BY id");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">History</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Colorado Cricket History</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH ARCHIVES</td>\n";
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
    // History Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL ARTICLES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=0; $i<$db->rows; $i++) {
    $db->GetRow($i);
    $t = htmlentities(stripslashes($db->data['title']));
    $pr = htmlentities(stripslashes($db->data['id']));
    $fi = $db->data['picture'];
    $a = $db->data['added'];
    $id = $db->data['id'];

    // output article

    if($i % 2) {
      echo "<tr class=\"trrow2\">\n";
    } else {
      echo "<tr class=\"trrow1\">\n";
    }

    echo "    <td><a href=\"$PHP_SELF?history=$pr&ccl_mode=1\">$t</a></td>\n";
    echo "    <td align=\"right\">";
    if ($db->data['picture'] != "") echo "<a href=\"/uploadphotos/history/$fi\"><img src=\"/images/icons/icon_pdf_sm.gif\" border=\"0\"></a>\n";
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

    } else {
        echo "There are no history articles in the database\n";
    }
}


function show_history($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    $db->QueryRow("SELECT * FROM history WHERE id=$pr");
    $db->BagAndTag();

    $t = $db->data['title'];
    $a = $db->data['article'];
    $fi = $db->data['picture'];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/history.php\">History</a> &raquo; <font class=\"10px\">Article</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    if ($db->data['picture'] != "") echo "<a href=\"/uploadphotos/history/$fi\"><img src=\"/images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp;download this file</a>\n";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";

    // output story
    echo "<div align=\"left\" class=\"14px\"><b>$t</b></div>\n";

    echo "<p>$a</p>\n";

    // output link back
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
    if ($db->data['picture'] != "") echo "<a href=\"/uploadphotos/history/$fi\"><img src=\"/images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp;download this file</a>\n";
    echo "<p>&laquo; <a href=\"$PHP_SELF\">back to history listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

function search_history($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

         if (!$db->Exists("SELECT * FROM history")) {
                 echo "<p>There are currently no history.</p>\n";
                 return;
         }

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/history.php\">History</a> &raquo; <font class=\"10px\">Search</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">History Search: $search</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH ARCHIVES</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // History Box
    //////////////////////////////////////////////////////////////////////////////////////////
    
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;HISTORY ARTICLES</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    if ($search != "")
    {

        $contains = "article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

        $db->Query("SELECT * FROM history WHERE $contains ORDER BY id DESC");
            if ($db->rows)
            {

            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $a = sqldate_to_string($db->data['added']);
            $t = $db->data['title'];
            $id = $db->data['id'];

            if($i % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td><a href=\"$PHP_SELF?history=$id&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<a href=\"/uploadphotos/history/$fi\"><img src=\"/images/icons/icon_pdf_sm.gif\" border=\"0\"></a>\n";
        echo "    </td>\n";
        echo "  </tr>\n";


            }


        }
        else
        {
        echo "<tr class=\"trrow1\">\n";
        echo "    <td>\n";
        echo "<p>There are no history articles matching that query in any way.</p>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

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
    show_history_listing($db,$s,$id,$history);
    break;
case 1:
    show_history($db,$s,$id,$history);
    break;
case 2:
    search_history($db,$search);
    break;
default:
    show_history_listing($db,$s,$id,$history);
    break;
}


?>
