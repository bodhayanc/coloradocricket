<?php

//------------------------------------------------------------------------------
// Colorado Cricket Documents v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_documents_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    if ($db->Exists("SELECT * FROM documents")) {
        // 1-Dec-2009 - Kervyn - Using the rank_sort
    //$db->QueryRow("SELECT * FROM documents ORDER BY id");
      $db->QueryRow("SELECT * FROM documents where isactive = 0 ORDER BY rank_sort ASC");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Documents</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">League Documents</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH DOCUMENTS</td>\n";
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
    // Documents Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL DOCUMENTS</td>\n";
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

        echo "    <td><a href=\"$PHP_SELF?documents=$pr&ccl_mode=1\">$t</a></td>\n";
        echo "    <td align=\"right\">";
	
	// echo "<p> document: $fi </p>";
	// KLUDGE:
	// This kludge was put in b/c I (Jarrar) can not yet figure why i supload broken.
	//  CCL_Umpiring_Grades-March2009.xls
	if (strcmp($t, "2009AGM") == 0 )
	{
		echo "<a href=\"/uploadphotos/documents/2009_CCL_AGM.pdf\"><img src=\"/images/icons/icon_pdf_sm.gif\" border=\"0\"></a>\n";
	} else if (strcmp($t, "Umpires grades") == 0)
	{
		echo "<a href=\"/uploadphotos/documents/CCL_Umpiring_Grades-March2009.xls\"><img src=\"/images/icons/ccl-xcel-small.gif\" border=\"0\"></a>\n";

	}else	if ($db->data['picture'] != "") {
		echo "<a href=\"/uploadphotos/documents/$fi\"><img src=\"/images/icons/icon_pdf_sm.gif\" border=\"0\"></a>\n";
	} else {
		echo "<p>No document found.</p>";
	}

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


function show_documents($db,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

    $db->QueryRow("SELECT * FROM documents WHERE id=$pr");
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
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/documents.php\">Documents</a> &raquo; <font class=\"10px\">Article</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp;<a href=\"/uploadphotos/documents/$fi\">download this file</a>\n";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";

    // output story
    echo "<div align=\"left\" class=\"14px\"><b>$t</b></div>\n";

    echo "<p>$a</p>\n";

    // output link back
    $sitevar = "http://www.slorugby.com/documentsarchives.php?documents=$pr&ccl_mode=1";
    echo "<hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"center\">\n\n";
    

    if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_pdf_lg.gif\" border=\"0\">&nbsp;<a href=\"/uploadphotos/documents/$fi\">download this file</a>\n";
    echo "<p>&laquo; <a href=\"$PHP_SELF\">back to documents listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}

function search_documents($db,$search="")
{
         global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;

         if (!$db->Exists("SELECT * FROM documents")) {
                 echo "<p>There are currently no documents.</p>\n";
                 return;
         }

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font><a href=\"/index.php\">Home</a> &raquo; <a href=\"/documents.php\">CCL Documents</a> &raquo; <font class=\"10px\">Search</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Documents Search: $search</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Search Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">SEARCH DOCUMENTS</td>\n";
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
    // Documents Box
    //////////////////////////////////////////////////////////////////////////////////////////

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;DOCUMENTS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";


    if ($search != "")
    {

        $contains = "article LIKE '%{$search}%' OR title LIKE '%{$search}%'";

        $db->Query("SELECT * FROM documents WHERE $contains ORDER BY id DESC");
            if ($db->rows)
            {


            for ($i=0; $i<$db->rows; $i++) {
            $db->GetRow($i);
            
            if($i % 2) {
              echo "<tr class=\"trrow2\">\n";
            } else {
              echo "<tr class=\"trrow1\">\n";
            }

        echo "    <td><a href=\"$PHP_SELF?documents={$db->data['id']}&ccl_mode=1\">{$db->data['title']}</a>&nbsp;\n";
        if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_pdf_sm.gif\">\n";
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

if(isset($_GET['ccl_mode'])) {
	switch($_GET['ccl_mode']) {
	case 0:
		show_documents_listing($db);
		break;
	case 1:
		show_documents($db,$_GET['documents']);
		break;
	case 2:
		search_documents($db,$_GET['search']);
		break;
	default:
		show_documents_listing($db);
		break;
	}
} else {
	show_documents_listing($db);
}


?>
