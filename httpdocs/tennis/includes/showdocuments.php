<?php

//------------------------------------------------------------------------------
// Documents v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_documents_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    if ($db->Exists("SELECT * FROM tennisdocuments")) {
    $db->QueryRow("SELECT * FROM tennisdocuments ORDER BY id");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Documents</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Tennis League Documents</b><br><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"><b>SEARCH DOCUMENTS ARCHIVES</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<form action=\"$PHP_SELF\">";
    echo "<input type=\"hidden\" name=\"ccl_mode\" value=\"2\">";
    echo "<br><p>Enter keyword &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";

    // Documents Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>ALL LEAGUE DOCUMENTS</b></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $t = htmlentities(stripslashes($db->data['title']));
        $pr = htmlentities(stripslashes($db->data['id']));
        $fi = $db->data['picture'];
        $id = $db->data['id'];

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td><a href=\"$PHP_SELF?documents=$pr&ccl_mode=1\">$t</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<a href=\"http://www.coloradocricket.org/uploadphotos/documents/$fi\"><img src=\"http://www.coloradocricket.org/images/icons/icon_pdf_sm.gif\" border=\"0\"></a>\n";
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
        echo "There are no documents in the database\n";
    }
}


function show_documents($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    $db->QueryRow("SELECT * FROM tennisdocuments WHERE id=$pr");
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
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/documents.php\">Documents</a> &raquo; Article</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    if ($db->data['picture'] != "") echo "<a href=\"http://www.coloradocricket.org/uploadphotos/documents/$fi\"><img src=\"http://www.coloradocricket.org/images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp;download this file</a>\n";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";

    // output story
    echo "<div align=\"left\" class=\"14px\"><b>$t</b></div>\n";

    echo "<p>$a</p>\n";

    // output link back
    $sitevar = "http://www.slorugby.com/documentsarchives.php?documents=$pr&ccl_mode=1";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
    if ($db->data['picture'] != "") echo "<a href=\"http://www.coloradocricket.org/uploadphotos/documents/$fi\"><img src=\"http://www.coloradocricket.org/images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp;download this file</a>\n";
    echo "<p>&laquo; <a href=\"$PHP_SELF\">back to documents listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

function search_documents($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

         if (!$db->Exists("SELECT * FROM tennisdocuments")) {
                 echo "<p>There are currently no documents.</p>\n";
                 return;
         }

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/documents.php\">League Documents</a> &raquo; Search</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Documents Search: $search</b><br><br>\n";

    // Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\"><b>SEARCH DOCUMENTS ARCHIVES</b></td>\n";
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
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;<b>LEAGUE DOCUMENTS CONTAINING \"$search\"</b></td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";


    if ($search != "")
    {

        $contains = "article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

        $db->Query("SELECT * FROM tennisdocuments WHERE $contains ORDER BY id DESC");
            if ($db->rows)
            {


            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            $a = sqldate_to_string($db->data[added]);

            if($i % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td><a href=\"$PHP_SELF?documents={$db->data['id']}&ccl_mode=1\">{$db->data['title']}</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"http://www.coloradocricket.org/images/icons/icon_pdf_sm.gif\">\n";
        echo "    </td>\n";
        echo "  </tr>\n";

            }

        }
        else
        {
        echo "<tr class=\"trrow1\">\n";
        echo "<td>\n";
        echo "<p>There are no documents articles matching that query in any way.</p>\n";
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
    show_documents_listing($db,$s,$id,$documents);
    break;
case 1:
    show_documents($db,$s,$id,$documents);
    break;
case 2:
    search_documents($db,$search);
    break;
default:
    show_documents_listing($db,$s,$id,$documents);
    break;
}


?>
