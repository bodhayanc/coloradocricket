<?php

//------------------------------------------------------------------------------
// Newsletter Email Management v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

echo "<p class=\"14px\">E-Mail Administration</p>\n";

// show list types

if (!isset($do)) {

	// check for list types

	if (!$db->Exists("SELECT * FROM $tbcfg[mllists] ORDER BY name")) {
		echo "<p>There are currently no list types in the database.</p>\n";
		return;
	}

	// collect lists into an array

	$db->Query("SELECT * FROM $tbcfg[mllists] ORDER BY name");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$lists[$db->data['id']] = $db->data[name];
	}

	// go through array and get all emails for that list

	foreach ($lists as $k => $v) {

		// get count for display

		$count = $db->QueryItem("SELECT COUNT(*) FROM $tbcfg[mlemails] WHERE listID=$k");
		if ($count>1) $cnt = " <span class=\"white\"> - $count emails</span>";
		else if ($count==1) $cnt = " <span class=\"white\"> - 1 email</span>";
		else $cnt = "";

		// show table

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;'$v' list type $cnt</td>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\" align=\"right\">&nbsp;<span class=\"white\">add email address</span></a></td>\n";
      	echo "</tr>\n";      	
      	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<p><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">\n";

		if (!$db->Exists("SELECT * FROM $tbcfg[mlemails] WHERE listID=$k")) {
			echo "<tr><td valign=\"top\" colspan=\"4\">No email addresses are set for this list type</td></tr>\n";
		} else {
			$db->Query("SELECT * FROM $tbcfg[mlemails] WHERE listID=$k ORDER BY lname,email");
			for ($e=0; $e<$db->rows; $e++) {
				$db->GetRow($e);
				echo "<tr class=\"trbottom\">";
				if ($db->data[fname]=="" && $db->data[lname]=="") echo "<td valign=\"top\">&nbsp;</td>\n";
				else echo "<td valign=\"top\">" . $db->data[lname] . (($db->data[fname]!="" && $db->data[lname]!="")?", ":"") . $db->data[fname] . "</td>";
				echo "<td valign=\"top\" align=\"center\">" . $db->data[email] . "</td>";
				echo "<td valign=\"top\" align=\"center\">" . ($db->data[unsubscribed]?"unsubscribed":"&nbsp;") . "</td>";
				echo "<td valign=\"top\" align=\"right\" nowrap>";
				echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_emails&do=edit&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a>";
				echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_emails&do=delete&id=" . $db->data['id'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a>";
				echo "</td>";
				echo "</tr>\n";
			}
		}
	echo "</table>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	}
	return;
}

// add

if ($do == "add") {

	// show initial form

	if (!isset($stage)) {
		show_form($SID,$action,$do,$fields,$list);
		return;
	}

	// clean up

	foreach ($fields as $k => $v) $fields[$k] = trim($v);
	// error checking
	if ($fields[email]=="") {
		echo "<p>You must supply the email address of the person you wish to add.</p>\n";
		show_form($SID,$action,$do,$fields,$list);
		return;
	}

	if (!eregi("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}",$fields[email])) {
		echo "<p>That email format is incorrect.  You must have the form someone@somewhere.ext.</p>\n";
		show_form($SID,$action,$do,$fields,$list);
		return;
	}

	// database work

	foreach ($fields as $k => $v) $fields[$k] = addslashes($v);
	$db->Insert("INSERT INTO $tbcfg[mlemails] (listID,fname,lname,email,unsubscribed,htmlemail,date) VALUES ($list,'$fields[fname]','$fields[lname]','$fields[email]',$fields[unsubscribed],$fields[htmlemail],NOW())");
	if ($db->a_rows != -1) echo "<p>You have now added an email address to that list type.</p><p>&raquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails&do=add&list=$list\">add another</a></p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
	else echo "<p>The email address could not be added to the database at this time.</p>\n";
	return;
}

// edit

if ($do == "edit") {

	// show initial form

	if (!isset($stage)) {
		$db->QueryRow("SELECT * FROM $tbcfg[mlemails] WHERE ID=$id");
		show_form($SID,$action,$do,$db->data,$list,$id);
		return;
	}

	// clean up

	foreach ($fields as $k => $v) $fields[$k] = trim($v);

	// error checking

	if ($fields[email]=="") {
		echo "<p>You must supply the email address of the person you wish to add.</p>\n";
		show_form($SID,$action,$do,$fields,$list,$id);
		return;
	}

	if (!eregi("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}",$fields[email])) {
		echo "<p>That email format is incorrect.  You must have the form someone@somewhere.ext.</p>\n";
		show_form($SID,$action,$do,$fields,$list,$id);
		return;
	}

	// database work

	foreach ($fields as $k => $v) $fields[$k] = addslashes($v);
	$db->Update("UPDATE $tbcfg[mlemails] SET listID=$fields[listID],fname='$fields[fname]',lname='$fields[lname]',email='$fields[email]',unsubscribed=$fields[unsubscribed],htmlemail=$fields[htmlemail] WHERE ID=$id");
	if ($db->a_rows != -1) echo "<p>You have now modified that email address.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
	else echo "<p>The email address could not be modified at this time.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
	return;
}

// delete

if ($do == "delete") {

	// show initial form

	if (!isset($stage)) {
		$db->QueryRow("SELECT e.email,l.name FROM $tbcfg[mlemails] e,$tbcfg[mllists] l WHERE e.ID=$id AND e.listID=l.ID");
		echo "<p>Are you sure you wish to delete the email <b>" . $db->data[email] . "</b> from the " . $db->data[name] . " list type?</p>\n";
		echo "<p><a href=\"$PHP_SELF?SID=$SID&action=ml_emails&do=delete&id=$id&stage=1&ok=1\">YES</a> | ";
		echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_emails&do=delete&id=$id&stage=1&ok=0\">NO</a></p>";
		return;
	}

	if (!$ok) {
		echo "<p>You have chosen not to delete that email address at this time.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
		return;
	}

	$db->Delete("DELETE FROM $tbcfg[mlemails] WHERE ID=$id");
	if ($db->a_rows != -1) echo "<p>You have now deleted that email address.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
	else echo "<p>That email address could not be deleted from the database at this time.</p><p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
	return;
}


// additional functions

function show_form($SID,$action,$do,$fields,$list=0,$id=0)
{
	global $db,$tbcfg,$PHP_SELF;

	echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"$do\">\n";
	echo "<input type=\"hidden\" name=\"list\" value=\"$list\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	echo "<input type=\"hidden\" name=\"stage\" value=\"1\">\n";
	echo "<p>Please enter the details of the email address.</p>\n";
	echo "<p><table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";

	// add this bit if they are editing - id has been passed

	if ($id) {
		$db->Query("SELECT ID,name FROM $tbcfg[mllists] ORDER BY name");
		if ($db->rows==1) {
			$db->GetRow(0);
			echo "<input type=\"hidden\" name=\"fields[listID]\" value=\"" . $db->data['id'] . "\">\n";
		} else {
			echo "<tr class=\"trtop\"><td valign=\"middle\">Mailing list</td><td valign=\"middle\"><select name=\"fields[listID]\">";
			for ($i=0; $i<$db->rows; $i++) {
				$db->GetRow($i);
				echo "<option value=\"" . $db->data['id'] . "\"" . ($db->data['id'] == $fields[listID] ? " selected":"") . ">" . $db->data[name] . "</option>";
			}
			echo "</select></td></tr>\n";
		}
	}

	// rest of the details

	echo "<tr class=\"trbottom\"><td valign=\"middle\">Person's first name</td><td valign=\"middle\"><input type=\"text\" name=\"fields[fname]\" style=\"width:300px;\" maxlength=\"64\" value=\"$fields[fname]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Person's last name</td><td valign=\"middle\"><input type=\"text\" name=\"fields[lname]\" style=\"width:300px;\" maxlength=\"64\" value=\"$fields[lname]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Person's email address</td><td valign=\"middle\"><input type=\"text\" name=\"fields[email]\" style=\"width:300px;\" maxlength=\"128\" value=\"$fields[email]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Can receive HTML formatted emails</td><td valign=\"middle\"><select name=\"fields[htmlemail]\"><option value=\"0\">No</option><option value=\"1\"" . ((!empty($fields) && $fields[htmlemail]==1)?" selected":"") . ">Yes</option></select></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Is subscribed</td><td valign=\"middle\"><select name=\"fields[unsubscribed]\"><option value=\"0\">Yes</option><option value=\"1\"" . ((!empty($fields) && $fields[unsubscribed]==1)?" selected":"") . ">No</option></select></td></tr>\n";
	echo "</table></p>\n";
	echo "<p><input type=\"submit\" value=\"enter details\"> <input type=\"reset\" value=\"clear form\"></p>\n";
	echo "</form>\n";
	echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=ml_emails\">go back to email list</a></p>\n";
	return;
}

?>
