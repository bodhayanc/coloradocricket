<?php

//------------------------------------------------------------------------------
// Mailing List Admin v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

echo "<p class=\"16px\">List Administration</p>\n";

// list

if (!isset($do)) {

	echo "&raquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=add\">Add a list</a><br><br>\n";
	if (!$db->Exists("SELECT * FROM $tbcfg[mllists] ORDER BY name")) {
		echo "<p>There are currently no lists in the database.</p>\n";
		return;
	}

	$db->Query("SELECT * FROM $tbcfg[mllists] ORDER BY name");
      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;The list types currently in the database are:</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<p><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">\n";
	echo "<tr class=\"trtop\"><td><span class=\"white\">Name</span></td><td><span class=\"white\">From</span></td><td><span class=\"white\">Archive</span></td><td align=\"right\"><span class=\"white\">Modify</span></td></tr>\n";
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);

		// print details

		echo "<tr class=\"trbottom\">";
		echo "<td valign=\"top\">" . $db->data[name] . "</td>";
		echo "<td valign=\"top\">" . $db->data[email] . "</td>";
		echo "<td valign=\"top\" align=\"center\">" . ($db->data[archive]?"<img src=\"/images/icons/icon_check.gif\">":"<img src=\"/images/icons/icon_cross.gif\">") . "</td>";
		echo "<td align=\"right\" valign=\"top\" nowrap>";
		echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=edit&id=" . $db->data[ID] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a>";
		echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=delete&id=" . $db->data[ID] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a>";
		echo "</td>";
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";	
	return;
}

// add

if ($do == "add") {

	// show initial form

	if (!isset($stage)) {
		show_form($SID,$action,$do,$fields);
		return;
	}

	// clean up

	foreach ($fields as $k => $v) $fields[$k] = trim($v);

	// error checking

	if ($fields[name]=="" || $fields[email]=="") {
		echo "<p>You must supply both a name for the list and an email address messages will be from.</p>\n";
		show_form($SID,$action,$do,$fields);
		return;
	}

	if (!eregi("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}",$fields[email])) {
		echo "<p>That email format is incorrect.  You must have the form someone@somewhere.ext.</p>\n";
		show_form($SID,$action,$do,$fields);
		return;
	}

	// database work

	foreach ($fields as $k => $v) $fields[$k] = addslashes($v);
	$db->Insert("INSERT INTO $tbcfg[mllists] (name,description,email,archive) VALUES ('$fields[name]','$fields[description]','$fields[email]',$fields[archive])");
	if ($db->a_rows != -1) echo "<p>You have now added a new list type.</p><p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=add\">add another list</a></p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
	else echo "<p>The list type could not be added to the database at this time.</p><p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=add\">add another list</a></p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
	return;
}

// edit

if ($do == "edit") {

	// show initial form

	if (!isset($stage)) {
		$db->QueryRow("SELECT * FROM $tbcfg[mllists] WHERE ID=$id");
		show_form($SID,$action,$do,$db->data,$id);
		return;
	}

	// clean up

	foreach ($fields as $k => $v) $fields[$k] = trim($v);

	// error checking

	if ($fields[name]=="" || $fields[email]=="") {
		echo "<p>You must supply both a name for the list and an email address messages will be from.</p>\n";
		show_form($SID,$action,$do,$fields,$id);
		return;
	}

	if (!eregi("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}",$fields[email])) {
		echo "<p>That email format is incorrect.  You must have the form someone@somewhere.ext.</p>\n";
		show_form($SID,$action,$do,$fields,$id);
		return;
	}

	// database work

	foreach ($fields as $k => $v) $fields[$k] = addslashes($v);
	$db->Update("UPDATE $tbcfg[mllists] SET name='$fields[name]',description='$fields[description]',email='$fields[email]',archive=$fields[archive] WHERE ID=$id");
	if ($db->a_rows != -1) echo "<p>You have now modified that list type.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
	else echo "<p>The list type could not be modified at this time.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
	return;
}

// delete

if ($do == "delete") {

	// show initial form

	if (!isset($stage)) {
		$db->QueryRow("SELECT * FROM $tbcfg[mllists] WHERE ID=$id");
		echo "<p>Are you sure you wish to delete the <b>" . $db->data[name] . "</b> list type?</p>\n";
		echo "<p><a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=delete&id=$id&stage=1&ok=1\">YES</a> | ";
		echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_lists&do=delete&id=$id&stage=1&ok=0\">NO</a></p>";
		return;
	}

	if (!$ok) {
		echo "<p>You have chosen not to delete the list type at this time.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
		return;
	}

	$db->Delete("DELETE FROM $tbcfg[mllists] WHERE ID=$id");
	if ($db->a_rows != -1) echo "<p>You have now deleted that list type.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
	else echo "<p>The list type could not be deleted from the database at this time.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">return to list</a></p>\n";
	return;
}

// additional functions

function show_form($SID,$action,$do,$fields,$id=0)
{
	global $PHP_SELF;

	echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"$do\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<input type=\"hidden\" name=\"stage\" value=\"1\">\n";
	echo "<p>Please enter the details of the list type.</p>\n";
	echo "<p><table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";
	echo "<tr class=\"trtop\"><td><span class=\"white\">field</span></td><td><span class=\"white\">value</span></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Name of list</td><td valign=\"middle\"><input type=\"text\" name=\"fields[name]\" style=\"width:300px;\" maxlength=\"100\" value=\"$fields[name]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Description of list</td><td valign=\"middle\"><input type=\"text\" name=\"fields[description]\" style=\"width:300px;\" maxlength=\"255\" value=\"$fields[description]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Email list is sent from</td><td valign=\"middle\"><input type=\"text\" name=\"fields[email]\" style=\"width:300px;\" maxlength=\"128\" value=\"$fields[email]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Messages will be archived</td><td valign=\"middle\"><select name=\"fields[archive]\"><option value=\"1\">Yes</option><option value=\"0\"" . ((!empty($fields) && $fields[archive]==0)?" selected":"") . ">No</option></select></td></tr>\n";
	echo "</table></p>\n";
	echo "<p><input type=\"submit\" value=\"enter details\"> <input type=\"reset\" value=\"clear form\"></p>\n";
	echo "</form>\n";
	echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_lists\">back to lists</a></p>\n";
	return;
}


?>
