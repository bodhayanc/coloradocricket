<?php

//------------------------------------------------------------------------------
// Frequently Asked Questions v 2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


// takes: cat
// if no cat then show main list

function show_category_list($db)
{
        global $PHP_SELF;

        // check for empty database

        if (!$db->Exists("SELECT * FROM demo_faq_category")) {
                echo "<p>There are currently no categories in the FAQ.</p>\n";
                return;
        } else {

                // do the search form

                echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\">\n";
                echo "<tr class=\"trtop\">\n";
                echo "<td><p><b>Search the FAQ<b></p></td>\n";
                echo "</tr>\n";
                echo "</table>\n";
                echo "<p><form action=\"$PHP_SELF\">Enter keyword &nbsp; <input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

                // do the list

                $db->Query("SELECT * FROM demo_faq_category ORDER BY cat");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $category[$db->data[cat]] = $db->data[title];
                }

                for ($i=1; $i<=count($category); $i++) {
                echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\">\n";
                echo "<tr class=\"trtop\">\n";
                echo "<td>&nbsp;<b>$category[$i]</b></td>\n";
                echo "</tr>\n";
                echo "</table><br>\n";

                        if (!$db->Exists("SELECT * FROM demo_faq_questions WHERE cnum=$i")) {
                                echo "         <dl><dd><span class=\"faqquestion\">There are currently no questions for this category.</span></dd></dl><br>\n";
                        } else {
                                $db->Query("SELECT * FROM demo_faq_questions WHERE cnum=$i");
                                echo " <ol>\n";
                                for ($x=0; $x<$db->rows; $x++) {
                                        $db->GetRow($x);
					$db->BagAndTag();
                                        $q = $db->data[question];
                                        echo "         <li class=\"faqquestion\"> <a href=\"$PHP_SELF?cat=$i#" . ($x+1) . "\" class=\"faqquestion\">$q</a></li><br>\n";
                                }
                                echo " </ol>\n";
                        }
                }
        }
}


function show_question_list($db,$cat)
{
        global $PHP_SELF;

        // get current category title

        $cattitle = $db->QueryItem("SELECT title FROM demo_faq_category WHERE cat=$cat");
                echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">\n";
                echo "<tr class=\"trtop\">\n";
                echo "<td><p><b><span class=\"12pt\">$cattitle</span></b></p></td>\n";
                echo "</tr>\n";
                echo "</table><br>\n";


        // get 'back to' if needs be

        if ($cat>1) {
                $cattitle = $db->QueryItem("SELECT title FROM demo_faq_category WHERE cat=$cat-1");
                echo "<span class=\"stdtext\">Backward to <a href=\"$PHP_SELF?cat=" . ($cat-1) . "\"><b>$cattitle</b></a></span><br>\n";
        }

        // get 'forward to' if needs be

        $db->Query("SELECT cat FROM demo_faq_category");
        if ($cat<$db->rows) {
                $cattitle = $db->QueryItem("SELECT title FROM demo_faq_category WHERE cat=$cat+1");
                echo "<span class=\"stdtext\">Forward to <a href=\"$PHP_SELF?cat=" . ($cat+1) . "\"><b>$cattitle</b></a></span><br>\n";
        }

        echo "\n<hr width=\"100%\" size=\"1\" color=\"#484747\" noshade>\n\n";

        // print out the questions

        if (!$db->Exists("SELECT * FROM demo_faq_questions WHERE cnum=$cat")) {
                echo "<p>There are currently no questions in this category.</p>\n";
                echo "<p>[ <a href=\"$PHP_SELF\">contents</a> ]</p>\n";
        } else {
                $db->Query("SELECT * FROM demo_faq_questions WHERE cnum=$cat");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
			$db->BagAndTag();
                        $q = $db->data[question];
                        $a = $db->data[answer];
                        echo "<p><a name=\"" . ($i+1) . "\" class=\"newsheader\"><b>" . ($i+1) . ".  $q</b></a><p>\n";
                        if ($db->data[answer] != "") echo "<p>$a</p>\n";
                        else echo "<p>No answer has been given to this question.</p>\n";
                        echo "<p class=\"small\">[ <a href=\"$PHP_SELF\"><b>contents</b></a> | <a href=\"$PHP_SELF?cat=$cat\"><b>top</b></a> ]</p>\n";
                        if ($i < ($db->rows-1)) echo "<p><table width=\"100%\" border=\"0\" cellpadding=\"0\"><tr><td><hr width=\"100%\" size=\"1\" color=\"#484747\" noshade></td></tr></table></p>\n";
                }
        }
}


function search_category_list($db,$search)
{
        global $PHP_SELF;

        // check for empty database

        if (!$db->Exists("SELECT * FROM demo_faq_category")) {
                echo "<p>There are currently no categories in the FAQ.</p>\n";
                return;
        } else {

                // do the search form

                echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\">\n";
                echo "<tr class=\"trtop\">\n";
                echo "<td><p><b>Search the FAQ again</b></p></td>\n";
                echo "</tr>\n";
                echo "</table>\n";
                echo "<p><form action=\"$PHP_SELF\">Enter keyword &nbsp; <input type=\"text\" name=\"search\" size=\"10\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

                // do the list

                $db->Query("SELECT * FROM demo_faq_category ORDER BY cat");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $category[$db->data[cat]] = $db->data[title];
                }

                for ($i=1; $i<=count($category); $i++) {
                echo "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"1\">\n";
                echo "<tr class=\"trtop\">\n";
                echo "<td>&nbsp;<b>$category[$i]</b></td>\n";
                echo "</tr>\n";
                echo "</table><br>\n";

                        if ($db->Exists("SELECT * FROM demo_faq_questions WHERE cnum=$i AND answer LIKE '%$search%'")) {
                                $db->Query("SELECT * FROM demo_faq_questions WHERE cnum=$i");
                                echo " <ol>\n";
                                for ($x=0; $x<$db->rows; $x++) {
                                        $db->GetRow($x);
                                        if (eregi($search,$db->data[answer])) {
                                                $q = htmlentities($db->data[question]);
                                                echo "         <li class=\"faqquestion\"> <a href=\"$PHP_SELF?cat=$i#" . ($x+1) . "\" class=\"faqquestion\">$q</a></li><br>\n";
                                        }
                                }
                                echo " </ol>\n";
                        }
                }
        }
        echo "<p><a href=\"$PHP_SELF\"><b>Show all</b></a></p>\n";
}

$db = new mysql_class($dbcfg[login],$dbcfg[pword],$dbcfg[server]);
$db->SelectDB($dbcfg[db]);

if (isset($search) && $search != "") search_category_list($db,$search);
else {
        if (!isset($cat)) show_category_list($db);
        else show_question_list($db,$cat);
}

?>
