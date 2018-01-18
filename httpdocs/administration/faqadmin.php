<?php

//------------------------------------------------------------------------------
// Site Control FAQ Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_main_menu($db)
{
        global $PHP_SELF,$SID,$content;

        echo "&raquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=addc\">Add a new faq category</a></p>\n";

        // check for empty database

        if (!$db->Exists("SELECT * FROM _faq_category")) {
                echo "<p>There are currently no categories in the FAQ.</p>\n";
                return;
        } else {

        	// query the database

                $db->Query("SELECT * FROM _faq_category ORDER BY cat");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);

                        // setup variables

                        $category[$db->data[cat]] = $db->data['title'];
                }

                // output header

                echo "<table border=\"0\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">\n";

                for ($i=1; $i<=count($category); $i++) {
                        echo "<tr class=\"trtop\">\n";
                        echo " <td align=\"left\"><span class=\"trtopfont\"><b>" . htmlentities($category[$i]) . "</b></span></td>\n";
                        echo " <td align=\"right\"><a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=editc&cat=$i\"><span class=\"trtopfont\">edit</span></a> | <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=delc&cat=$i\"><span class=\"trtopfont\">delete</span></a> | <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=addq&cat=$i\"><span class=\"trtopfont\">add question</span></a></td>\n";
                        echo "</tr>\n";

                        // check for empty questions

                        if (!$db->Exists("SELECT * FROM _faq_questions WHERE cnum=$i")) {
                                echo "<tr class=\"trbottom\">\n";
                                echo " <td colspan=\"2\">There are currently no questions for this category.</td>\n";
                                echo "</tr>\n";
                        } else {

                        // show questions

                                $db->Query("SELECT * FROM _faq_questions WHERE cnum=$i");
                                for ($x=0; $x<$db->rows; $x++) {
                                        $db->GetRow($x);
                                        $q = htmlentities($db->data[question]);
                                        echo "<tr class=\"trbottom\">\n";
                                        echo " <td align=\"left\">" . ($x+1) . ".   $q</td>\n";
                                        echo " <td align=\"right\"><a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=editq&id=" . $db->data['id'] . "\">edit</a> | <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=delq&id=" . $db->data['id'] . "\">delete</a></td>\n";
                                        echo "</tr>\n";

                                }

                        }

                }
                echo "</table>\n";
        }

}


function add_question_form($db,$cat)
{
        global $PHP_SELF,$SID,$content;

        // query database with category title variable

        $cattitle = $db->QueryItem("SELECT title FROM _faq_category WHERE cat=$cat");

        echo "<p>Add a question to the '$cattitle' category.</p>\n";
        echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"faqadmin\">\n";
        echo "<input type=\"hidden\" name=\"do\" value=\"addq\">\n";
        echo "<input type=\"hidden\" name=\"cnum\" value=\"$cat\">\n";
        echo "<p>Enter question title<br><input type=\"text\" name=\"question\" size=\"25\" maxlength=\"255\"></p>\n";
        echo "<p>Enter answer<br><textarea name=\"answer\" rows=\"20\" cols=\"45\" wrap=\"virtual\"></textarea></p>\n";
        echo "<p><input type=\"submit\" value=\"submit question\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
        echo "</form>\n";
}


function do_add_question($db,$cat,$question,$answer="")
{
        global $PHP_SELF,$SID,$content;

        // setup variables

        $q = addslashes(trim($question));
        $a = addslashes(trim($answer));

        // query database

        $db->Insert("INSERT INTO _faq_questions (cnum,question,answer) VALUES ($cat,'$q','$a')");
        $cattitle = $db->QueryItem("SELECT title FROM _faq_category WHERE cat=$cat");
        echo "<p>You have now added a question to the '$cattitle' category.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=addq&cat=$cat\">add another question to this category</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin\">return to the FAQ listing</a></p>\n";
}


function edit_question_form($db,$id)
{
        global $PHP_SELF,$SID,$content;

        // get all the categories

        $db->Query("SELECT * FROM _faq_category ORDER BY cat");
        for ($i=0; $i<$db->rows; $i++) {
                $db->GetRow($i);
                $category[$db->data[cat]] = $db->data['title'];
        }

        // get selected question

        $db->QueryRow("SELECT * FROM _faq_questions WHERE id=$id");

        // setup variables

        $q = htmlentities($db->data[question]);
        $a = htmlentities($db->data[answer]);

        echo "<p>Edit this question.</p>\n";
        echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"faqadmin\">\n";
        echo "<input type=\"hidden\" name=\"do\" value=\"editq\">\n";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
        echo "<p>Select category<br><select name=\"cnum\">";

        for ($i=0; $i<=count($category); $i++) {
                if ($category[$i] != "") echo "<option value=\"$i\"" . ($i==$db->data[cnum]?" selected":"") . ">$category[$i]</option>";
        }

        echo "</select>\n";
        echo "<p>Enter question title<br><input type=\"text\" name=\"question\" size=\"25\" maxlength=\"255\" value=\"$q\"></p>\n";
        echo "<p>Enter answer<br><textarea name=\"answer\" rows=\"20\" cols=\"45\" wrap=\"virtual\">$a</textarea></p>\n";
        echo "<p><input type=\"submit\" value=\"submit question\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
        echo "</form>\n";
}


function do_update_question($db,$id,$cnum,$question,$answer)
{
        global $PHP_SELF,$SID,$content;

	// setup variables

        $q = addslashes(trim($question));
        $a = addslashes(trim($answer));

        // if the category was change then redo the numbering to add to bottom of list

        $db->Update("UPDATE _faq_questions SET cnum=$cnum,question='$q',answer='$a' WHERE id=$id");

        echo "<p>You have updated that question.</p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin\">return to the FAQ listing</a></p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=editq&id=$id\">update $q some more</a></p>\n";
}


function delete_question_check($db,$id)
{
        global $PHP_SELF,$SID,$content;

        // query the database

        $q = $db->QueryItem("SELECT question FROM _faq_questions WHERE id=$id");
        $q = htmlentities(trim($q));

        echo "<p>Are you sure you wish to delete the FAQ question:</p>\n";
        echo "<p><b>$q</b></p>\n";
        echo "<p><a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=delq&id=$id&doit=1\">YES</a> | <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=delq&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_question($db,$id,$doit)
{
        global $PHP_SELF,$SID,$content;

        // cancel delete

        if (!$doit) echo "<p>You have chosen NOT to delete that FAQ question.</p>\n";
        else {

        // do delete

                $db->Delete("DELETE FROM _faq_questions WHERE id=$id");
                echo "<p>You have now deleted that FAQ question.</p>\n";
        }
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin\">return to the FAQ listing</a></p>\n";
}


function delete_category_check($db,$cat)
{
        global $PHP_SELF,$SID,$content;

        // query database

        $t = $db->QueryItem("SELECT title FROM _faq_category WHERE cat=$cat");
        $t = htmlentities($t);

        echo "<p>When you delete a category you will delete all question contained in it.</p>\n";
        echo "<p>Are you sure you wish to delete the category:</p>\n";
        echo "<p><b>$t</b></p>\n";
        echo "<p><a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=delc&cat=$cat&doit=1\">YES</a> | <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=delc&cat=$cat&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$cat,$doit)
{
        global $PHP_SELF,$SID,$content;

	// cancel delete

        if (!$doit) echo "<p>You have chosen NOT to delete that FAQ category.</p>\n";
        else {

        // do delete, deleting category and all questions within

                $db->Delete("DELETE FROM _faq_category WHERE cat=$cat");
                $db->Delete("DELETE FROM _faq_questions WHERE cnum=$cat");
                echo "<p>You have now deleted that FAQ category and all questions in it.</p>\n";
        }
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin\">return to the FAQ listing</a></p>\n";
}


function add_category_form($db,$cat)
{
        global $PHP_SELF,$SID,$content;

        echo "<p>Add a new category.</p>\n";
        echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"faqadmin\">\n";
        echo "<input type=\"hidden\" name=\"do\" value=\"addc\">\n";
        echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
        echo "<p>Enter category title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\"></p>\n";
        echo "<p><input type=\"submit\" value=\"submit category\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
        echo "</form>\n";
}


function do_add_category($db,$title)
{
        global $PHP_SELF,$SID,$content;

        // setup variables and insert statement

        $t = addslashes(trim($title));
        $db->Insert("INSERT INTO _faq_category (title) VALUES ('$t')");

        echo "<p>You have now added the category '" . htmlentities($title) . "'.</p>\n";
        echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=addc\">add another category.</a></p>\n";
        echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin\">return to the FAQ listing</a></p>\n";
}


function edit_category_form($db,$cat)
{
        global $PHP_SELF,$SID,$content;

        // query the database

        $t = $db->QueryItem("SELECT title FROM _faq_category WHERE cat=$cat");
        $t = htmlentities($t);

        echo "<p>Edit this category.</p>\n";
        echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
		echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
        echo "<input type=\"hidden\" name=\"action\" value=\"faqadmin\">\n";
        echo "<input type=\"hidden\" name=\"do\" value=\"editc\">\n";
        echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
        echo "<input type=\"hidden\" name=\"cat\" value=\"$cat\">\n";
        echo "<p>Enter category title<br><input type=\"text\" name=\"title\" size=\"25\" maxlength=\"255\" value=\"$t\"></p>\n";
        echo "<p><input type=\"submit\" value=\"submit category\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
        echo "</form>\n";
}


function do_update_category($db,$cat,$title)
{
        global $PHP_SELF,$SID,$content;

        // query the database

        $t = addslashes(trim($title));
        $db->Update("UPDATE _faq_category SET title='$t' WHERE cat=$cat");
        echo "<p>You have now updated that category.</p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin\">return to the FAQ listing</a></p>\n";
		echo "<p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=faqadmin&do=editc&cat=$cat\">update $title some more</a></p>\n";
}


// main switch


switch($do) {
case "addq":
        if (!isset($cnum)) add_question_form($db,$cat);
        else do_add_question($db,$cnum,$question,$answer);
        break;
case "editq":
        if (!isset($cnum)) edit_question_form($db,$id);
        else do_update_question($db,$id,$cnum,$question,$answer);
        break;
case "delq":
        if (!isset($doit)) delete_question_check($db,$id);
        else do_delete_question($db,$id,$doit);
        break;
case "delc":
        if (!isset($doit)) delete_category_check($db,$cat);
        else do_delete_category($db,$cat,$doit);
        break;
case "addc":
        if (!isset($doit)) add_category_form($db);
        else do_add_category($db,$title);
        break;
case "editc":
        if (!isset($doit)) edit_category_form($db,$cat);
        else do_update_category($db,$cat,$title);
        break;
default:
        show_main_menu($db);
        break;
}


?>
