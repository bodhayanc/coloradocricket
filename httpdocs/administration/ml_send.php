<?php

//------------------------------------------------------------------------------
// Mailing List Sending Admin v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


echo "<p class=\"14px\">Write and Send a Newsletter</p>\n";

// show email form

if (!isset($do)) {

	// check for newsletter types

	if (!$db->Exists("SELECT * FROM $tbcfg[mllists]")) {
		echo "<p>There are currently no newsletter types in the database.</p>\n";
		return;
	}
	show_form($SID,$action,"preview",$fields);
	return;
}

// preview the email

if ($do == "preview") {

	// clean up

	foreach ($fields as $k => $v) $fields[$k] = htmlentities(stripslashes(trim($v)));

	// error checking

	if ($fields[subject] == "" || $fields[body] == "") {
		echo "<p>You must fully complete the form.</p>\n";
		show_form($SID,$action,$do,$fields);
		return;
	}

	// display details

	$name = $db->QueryItem("SELECT name FROM $tbcfg[mllists] WHERE ID=$fields['id']");
	echo "<p>Please review the details of the newsletter you wish to send to the <b>$name</b> newsletter type.</p>\n";
	echo "<p><table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";
	echo "<tr class=\"trtop\"><td><span class=\"trtopfont\">field</span></td><td><span class=\"trtopfont\">value</span></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Newsletter Type</td><td valign=\"middle\">$name</td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"middle\">Subject</td><td valign=\"middle\">" . $fields[subject] . "</td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"top\">Message</td><td valign=\"middle\">" . nl2br($fields[body]) . "</td></tr>\n";
	echo "</table></p>\n";

	// do table for buttons

	echo "<p><table border=\"0\" cellpadding=\"0\" cellspacing=\"10\" width=\"100%\">\n<tr>";

	// cancel button

	echo "<td align=\"center\"><form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"cancel\">\n";
	echo "<input type=\"hidden\" name=\"fields['id']\" value=\"$fields['id']\">\n";
	echo "<input type=\"hidden\" name=\"fields[subject]\" value=\"$fields[subject]\">\n";
	echo "<input type=\"hidden\" name=\"fields[body]\" value=\"$fields[body]\">\n";
	echo "<input type=\"submit\" value=\"cancel newsletter\"></form></td>\n";

	// edit button

	echo "<td align=\"center\"><form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"edit\">\n";
	echo "<input type=\"hidden\" name=\"fields['id']\" value=\"$fields['id']\">\n";
	echo "<input type=\"hidden\" name=\"fields[subject]\" value=\"$fields[subject]\">\n";
	echo "<input type=\"hidden\" name=\"fields[body]\" value=\"$fields[body]\">\n";
	echo "<input type=\"submit\" value=\"re-edit newsletter\"></form></td>\n";

	// send button

	echo "<td align=\"center\"><form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"send\">\n";
	echo "<input type=\"hidden\" name=\"fields['id']\" value=\"$fields['id']\">\n";
	echo "<input type=\"hidden\" name=\"fields[subject]\" value=\"$fields[subject]\">\n";
	echo "<input type=\"hidden\" name=\"fields[body]\" value=\"$fields[body]\">\n";
	echo "<input type=\"submit\" value=\"send newsletter\"></form></td>\n";

	// finish up button table

	echo "</tr></table></p>\n";
	return;
}

// re-edit the message

if ($do == "edit") {
	foreach ($fields as $k => $v) $fields[$k] = htmlentities(stripslashes($v));
	show_form($SID,$action,"preview",$fields);
	return;
}

// cancel the message

if ($do == "cancel") {
	echo "<p>You have decided to cancel.  The newsletter was not sent..</p>\n";
	return;
}

// send the message

if ($do == "send") {

	// get newsletter type details

	$db->QueryRow("SELECT name,email,archive FROM $tbcfg[mllists] WHERE ID=$fields['id']");
	$fromemail = $db->data[email];
	$addressbook = $db->data[name];
	$archive = $db->data[archive];
	$name = $db->data[name];

	// setup initial email headers

	$headers = "From: $fromemail\n";
	$headers .= "X-Mailer: Daily Newsletter\n";

	// now send to users

	$db->Query("SELECT * FROM $tbcfg[mlemails] WHERE listID=$fields['id'] AND unsubscribed=0");
	$notsent = array();
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);

		// format the subject

		$subject = stripslashes($fields[subject]);
		$subject = ereg_replace("{FNAME}",$db->data[fname],$subject);
		$subject = ereg_replace("{LNAME}",$db->data[lname],$subject);
		$subject = ereg_replace("{NAME}",$db->data[fname] . " " . $db->data[lname],$subject);
		$subject = ereg_replace("{TOEMAIL}",$db->data[email],$subject);
		$subject = ereg_replace("{TODAY}",date("F nS, Y",time()),$subject);

		// format the body

		$body = stripslashes($fields[body]);
		$body = ereg_replace("{FNAME}",$db->data[fname],$body);
		$body = ereg_replace("{LNAME}",$db->data[lname],$body);
		$body = ereg_replace("{NAME}",$db->data[fname] . " " . $db->data[lname],$body);
		$body = ereg_replace("{TOEMAIL}",$db->data[email],$body);
		$body = ereg_replace("{TODAY}",date("F nS, Y",time()),$body);

		// add an unsubscribe link (change this to what you need)

		$body .= "\n\n\n\n\n\n-=-=-=-=-=-=-=-\nThis email is sent from the CCL website. \n\nIf you wish to be removed from the $addressbook Mailing List, click the following link.\n\n";
		$body .= "http://www.coloradocricket.org/unsubscribe.php?id=" . $db->data['id'] . "&email=" . $db->data[email] . "\n\n";
		$body .= "If you do not have access to a web browser but can email, then send an email to $fromemail stating your ";
		$body .= "id number, " . $db->data['id'] . ", and email address, " . $db->data[email] . ", and your wish to be removed from the newsletter type.\n";
		$body .= "-=-=-=-=-=-=-=-\n";

		// tidy it up

		$body = wordwrap($body,75);

		// do email sending

		$sent = @mail($db->data[email],$subject,$body,$headers);
		if (!$sent) $notsent[] = $db->data[email];
	}
	$total = $db->rows - count($notsent);
	echo "<p>You have sent a newsletter to $total " . ($total==1?"person":"people") . " on the $name newsletter type.</p>\n";

	// give finishing message

	if (count($notsent)) {
		echo "<p>Newsletter could not be sent to the following email addresses:</p>\n<p>\n";
		for ($i=0; $i<count($notsent); $i++) {
			$notsent[$i] . "<br>\n";
		}
		echo "</p>\n";
	}

	// store archive

	if ($archive) {

		// format the subject

		$subject = stripslashes($fields[subject]);
		$subject = ereg_replace("{FNAME}","[not shown]",$subject);
		$subject = ereg_replace("{LNAME}","[not shown]",$subject);
		$subject = ereg_replace("{NAME}","[not shown]",$subject);
		$subject = ereg_replace("{TOEMAIL}","[not shown]",$subject);
		$subject = ereg_replace("{TODAY}",date("F nS, Y",time()),$subject);

		// format the body

		$body = stripslashes($fields[body]);
		$body = ereg_replace("{FNAME}","[not shown]",$body);
		$body = ereg_replace("{LNAME}","[not shown]",$body);
		$body = ereg_replace("{NAME}","[not shown]",$body);
		$body = ereg_replace("{TOEMAIL}","[not shown]",$body);
		$body = ereg_replace("{TODAY}",date("F nS, Y",time()),$body);

		// store in the database

		$db->Insert("INSERT INTO $tbcfg[mlarchive] (listID,date,body,subject) VALUES ($fields['id'],NOW(),'$body','$subject')");
		if ($db->a_rows != -1) echo "<p>An archive has been created for this newsletter.</p>\n";
		else echo "<p>An archive could not be created for this newsletter.</p>\n";
	}
}



// additional functions

function show_form($SID,$action,$do,$fields)
{
	global $db,$tbcfg,$PHP_SELF;

	echo "<form action=\"$PHP_SELF\" method=\"post\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"$do\">\n";
	echo "<p>Please enter the details of the newsletter.</p>\n";
	echo "<p>Special place-holders are as follows:</p>\n";
	echo "<p><table border=\"0\" cellpadding=\"0\" cellspacing=\"5\">\n";
	echo "<tr><td>{FNAME}</td><td>Their first name</td></tr>\n";
	echo "<tr><td>{LNAME}</td><td>Their last name</td></tr>\n";
	echo "<tr><td>{NAME}</td><td>Their full name (first last)</td></tr>\n";
	echo "<tr><td>{TOEMAIL}</td><td>Their email address</td></tr>\n";
	echo "<tr><td>{TODAY}</td><td>Todays date (Month DD, YYYY)</td></tr>\n";
	echo "</table></p>\n";
	echo "<p><table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\">\n";
	echo "<tr class=\"trtop\"><td><span class=\"trtopfont\">field</span></td><td><span class=\"trtopfont\">value</span></td></tr>\n";
	$db->Query("SELECT l.ID,l.name FROM $tbcfg[mllists] l LEFT JOIN $tbcfg[mlemails] e ON l.ID=e.listID WHERE e.listID IS NOT NULL GROUP BY l.ID ORDER BY name");
	if ($db->rows==1) {
		$db->GetRow(0);
		echo "<input type=\"hidden\" name=\"fields['id']\" value=\"" . $db->data['id'] . "\">\n";
	} else {
		echo "<tr class=\"trbottom\"><td valign=\"top\">Select List</td><td valign=\"middle\"><select name=\"fields['id']\">";
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['id'] . "\"" . ($db->data['id'] == $fields['id'] ? " selected":"") . ">" . $db->data[name] . "</option>";
		}
		echo "</select></td></tr>\n";
	}
	echo "<tr class=\"trbottom\"><td valign=\"top\" width=\"30%\">Subject of email</td><td valign=\"top\" width=\"70%\"><input type=\"text\" name=\"fields[subject]\" style=\"width:200px;\" maxlength=\"128\" value=\"$fields[subject]\"></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"top\" width=\"30%\">Email message</td><td valign=\"top\" width=\"70%\"><textarea name=\"fields[body]\" cols=\"30\" rows=\"10\" wrap=\"virtual\">$fields[body]</textarea></td></tr>\n";
	echo "</table></p>\n";
	echo "<p><input type=\"submit\" value=\"enter details\"> <input type=\"reset\" value=\"clear form\"></p>\n";
	echo "</form>\n";
	return $form;
}

?>
