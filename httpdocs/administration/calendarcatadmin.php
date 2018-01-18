<?php

//----------------------------------------------------------------------------------------
// Site Control v3.0
//
// (c) Andrew Collington - amnuts@amnuts.com
// (c) Michael Doig      - michael@gmail.com
//----------------------------------------------------------------------------------------

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - MAIN FORM
//////////////////////////////////////////////////////////////////////////////////////////

function show_main_menu($db)
{
    global $bluebdr,$action,$SID;

    echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a category</a></p>\n";

//----------------------------------------------------------------------------------------
// check for empty database
//----------------------------------------------------------------------------------------

    if (!$db->Exists("SELECT * FROM extcal_categories")) {
      echo "<p>There are currently no categories in the database.</p>\n";
      return;
    } else {

//----------------------------------------------------------------------------------------
// all entries
//----------------------------------------------------------------------------------------

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Categories</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";
    
    //-------------------------------------------------
    // query the database
    //-------------------------------------------------

    $db->Query("SELECT * FROM extcal_categories ORDER BY cat_id DESC");
     for ($x=0; $x<$db->rows; $x++) {
     $db->GetRow($x);

    //-------------------------------------------------
    // setup the variables
    //-------------------------------------------------

      $ti = htmlentities(stripslashes($db->data[cat_name]));
      $id = htmlentities(stripslashes($db->data[cat_id]));
      $co = htmlentities(stripslashes($db->data[color]));

    //-------------------------------------------------
    // output
    //-------------------------------------------------

      echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';
      echo "  <td align=\"left\" valign=\"top\" width=\"6\" bgcolor=\"$co\"><img src=\"/images/spacer.gif\" alt=\"$ti\" width=\"6\"></td>\n";
      echo "  <td align=\"left\" valign=\"top\">$ti</td>\n";

//      echo "  <td align=\"right\" valign=\"top\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&cat_id=" . $db->data[cat_id] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&cat_id=" . $db->data[cat_id] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a></td>\n";

      echo "  <td align=\"right\" valign=\"top\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&cat_id=" . $db->data[cat_id] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a></td>\n";
      echo " </tr>\n";
    }

    echo "</table>\n";
    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

    }
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - ADD ENTRY FORM
//////////////////////////////////////////////////////////////////////////////////////////

function add_category_form($db)
{
global $bluebdr,$action,$SID;

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a category</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

    echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"addcat\" validate=\"onchange\" invalidColor=\"yellow\">\n";
    echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
    echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
    echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\">category name</td>\n";
    echo "  <td class=\"trrow1\"><input type=\"text\" name=\"cat_name\" size=\"35\" maxlength=\"255\" required msg=\"Please enter a category name\"></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\">category description</td>\n";
    echo "  <td class=\"trrow1\"><textarea name=\"description\" cols=\"50\" rows=\"15\" wrap=\"virtual\" required msg=\"Please enter a category description\"></textarea></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\">category color</td>\n";
    echo "  <td class=\"trrow1\">";
    echo "   <table border=\"0\"><tr>";
    echo "    <td align=\"left\"><input type=\"text\" name=\"color\" size=\"35\"maxlength=\"255\" required msg=\"Please enter a category color\"></td>";
    echo "    <td align=\"left\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"18\" height=\"17\"><tr><td align=\"left\" id=\"rollover\" width=\"18\" height=\"17\"><a href=\"javascript:TCP.popup(document.forms['addcat'].elements['color'],'', document.getElementById('rollover'), 'bg')\"><img src=\"../images/icons/icon-colorpicker.gif\" border=\"0\"></a></td></tr></table></td><td align=\'left\"> Pick a color!</td>\n";
    echo "   </tr></table>\n";
    echo "  </td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"add entry\"> &nbsp; <input type=\"reset\" value=\"reset form\"></td>\n";
    echo " </tr>\n";
    echo "</table>\n";

    echo "</form>\n";
    echo "<script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - ADD TO DATABASE
//////////////////////////////////////////////////////////////////////////////////////////

function do_add_category($db,$cat_name,$description,$color)
{
global $bluebdr,$action,$SID;

//----------------------------------------------------------------------------------------
// setup variables
//----------------------------------------------------------------------------------------

    $ti  = addslashes(trim($cat_name));
    $de  = addslashes(trim($description));
    $co  = addslashes(trim($color));

//----------------------------------------------------------------------------------------
// check for duplicate entries
//----------------------------------------------------------------------------------------

    if ($db->Exists("SELECT * FROM extcal_categories WHERE cat_name='$ti'")) {
      echo "<p>That category already exists in the database.</p>\n";
      return;
    }

//----------------------------------------------------------------------------------------
// all okay, insert into database
//----------------------------------------------------------------------------------------

    $db->Insert("INSERT INTO extcal_categories (cat_name,description,color,options,enabled) VALUES ('$ti','$de','$co',2,1)");

    if ($db->a_rows != -1) {
      echo "<p>You have now added a new category.</p>\n";
      echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another category</a></p>\n";
      echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to category list</a></p>\n";
    } else {
      echo "<p>The category could not be added to the database at this time.</p>\n";
      echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to category list</a></p>\n";
    }
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - DELETE ENTRY FORM
//////////////////////////////////////////////////////////////////////////////////////////

function delete_category_check($db,$cat_id)
{
    global $bluebdr,$action,$SID;

    //-------------------------------------------------
    // query the database
    //-------------------------------------------------

    $title = htmlentities(stripslashes($db->QueryItem("SELECT cat_name FROM extcal_categories WHERE cat_id=$cat_id")));

    //-------------------------------------------------
    // output
    //-------------------------------------------------

    echo "<p>Are you sure you wish to delete the category titled:</p>\n";
    echo "<p><b>$title</b></p>\n";
    echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&cat_id=$cat_id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&cat_id=$cat_id&doit=0\">NO</a></p>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - DELETE FROM DATABASE
//////////////////////////////////////////////////////////////////////////////////////////

function do_delete_category($db,$cat_id,$doit)
{
    global $bluebdr,$action,$SID;

    //-------------------------------------------------
    // cancel delete
    //-------------------------------------------------

    if (!$doit) echo "<p>You have chosen NOT to delete that category.</p>\n";
    else {

    //-------------------------------------------------
    // do the delete
    //-------------------------------------------------

        $db->Delete("DELETE FROM extcal_categories WHERE cat_id=$cat_id");
        echo "<p>You have now deleted that category.</p>\n";
    }
    echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the category listing</a></p>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - EDIT ARTICLE FORM
//////////////////////////////////////////////////////////////////////////////////////////

function edit_category_form($db,$cat_id)
{
    global $bluebdr,$action,$SID;

//----------------------------------------------------------------------------------------
// query database
//----------------------------------------------------------------------------------------

    $db->QueryRow("SELECT * FROM extcal_categories WHERE cat_id=$cat_id");

//----------------------------------------------------------------------------------------
// setup the variables
//----------------------------------------------------------------------------------------

    $ti = htmlentities(stripslashes($db->data[cat_name]));
    $de  = htmlentities(stripslashes($db->data[description]));
    $co  = htmlentities(stripslashes($db->data[color]));

//----------------------------------------------------------------------------------------
// output
//----------------------------------------------------------------------------------------

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "<tr>\n";
        echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit category</td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";


    echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\" name=\"editcat\" validate=\"onchange\" invalidColor=\"yellow\">\n";
    echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
    echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
    echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
    echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
    echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
    echo "<input type=\"hidden\" name=\"cat_id\" value=\"$cat_id\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"1\" align=\"left\">\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\">category name</td>\n";
    echo "  <td class=\"trrow1\"><input type=\"text\" name=\"cat_name\" size=\"35\" maxlength=\"255\" value=\"$ti\" required msg=\"Please enter a category name\"></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\">category description</td>\n";
    echo "  <td class=\"trrow1\"><textarea name=\"description\" cols=\"50\" rows=\"15\" wrap=\"virtual\" required msg=\"Please enter a category description\">$de</textarea></td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\">category color</td>\n";
    echo "  <td class=\"trrow1\">";
    echo "   <table border=\"0\"><tr>";
    echo "    <td align=\"left\"><input type=\"text\" name=\"color\" size=\"35\"maxlength=\"255\" value=\"$co\" required msg=\"Please enter a category color\"></td>";
    echo "    <td align=\"left\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"18\" height=\"17\"><tr><td align=\"left\" id=\"rollover\" width=\"18\" height=\"17\" bgcolor=\"$co\"><a href=\"javascript:TCP.popup(document.forms['editcat'].elements['color'],'', document.getElementById('rollover'), 'bg')\"><img src=\"../images/icons/icon-colorpicker.gif\" border=\"0\"></a></td></tr></table></td><td align=\'left\"> Pick a color!</td>\n";
    echo "   </tr></table>\n";
    echo "  </td>\n";
    echo " </tr>\n";
    echo " <tr>\n";
    echo "  <td class=\"trrow1\" colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"edit category\"> &nbsp; <input type=\"reset\" value=\"reset form\"></td>\n";
    echo " </tr>\n";
    echo "</table>\n";

    echo "</form>\n";
    echo "<script src=\"/includes/javascript/validation.js\" language=\"JScript\"></SCRIPT>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}

//////////////////////////////////////////////////////////////////////////////////////////
// CALENDAR CATEGORY ADMIN - UPDATE DATABASE
//////////////////////////////////////////////////////////////////////////////////////////

function do_update_category($db,$cat_id,$cat_name,$description,$color)
{
    global $bluebdr,$action,$SID;

//----------------------------------------------------------------------------------------
// setup the variables
//----------------------------------------------------------------------------------------

    $ti = addslashes(trim($cat_name));
    $co = addslashes(trim($color));

//----------------------------------------------------------------------------------------
// prevent the need for using escape sequences with apostrophe's
//----------------------------------------------------------------------------------------

    $a = eregi_replace("\r","",$description);
    $a = addslashes(trim($a));

//----------------------------------------------------------------------------------------
// update database
//----------------------------------------------------------------------------------------

    $db->Update("UPDATE extcal_categories SET cat_name='$ti',description='$a',color='$co' WHERE cat_id=$cat_id");

    echo "<p>You have now updated that category.</p>\n";
    echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the category listing</a></p>\n";
    echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&cat_id=$cat_id\">update $ti some more</a></p>\n";
}



//////////////////////////////////////////////////////////////////////////////////////////
// MAIN PROGRAM
//////////////////////////////////////////////////////////////////////////////////////////

if (!$USER[flags][$f_cal_cat_admin]) {
    header("Location: main.php?SID=$SID");
    exit;
}

echo "<p class=\"14px\"><b>Calendar Category Administration</b></p>\n";
echo "<p>Manage event calendar categories.</p>\n";

//----------------------------------------------------------------------------------------
// main program switch
//----------------------------------------------------------------------------------------

switch($do) {
case "menu":
    show_main_menu($db);
    break;
case "sadd":
    if (!isset($doit)) add_category_form($db);
    else do_add_category($db,$cat_name,$description,$color);
    break;
case "sdel":
    if (!isset($doit)) delete_category_check($db,$cat_id);
    else do_delete_category($db,$cat_id,$doit);
    break;
case "sedit":
    if (!isset($doit)) edit_category_form($db,$cat_id);
    else do_update_category($db,$cat_id,$cat_name,$description,$color);
    break;
default:
    show_main_menu($db);
    break;
}

?>
