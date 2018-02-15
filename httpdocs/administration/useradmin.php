<?php

//------------------------------------------------------------------------------
// Site Control User Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

$page = useradmin;

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">Add a new user</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM admin")) {
		echo "<p>There are currently no users in the database.</p>\n";
		return;
	} else {

		// output header, not to be in the for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;User List</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query the database

		$db->Query("SELECT * FROM admin WHERE email<>'$USER['email']' ORDER BY lname,fname");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup the variables

			$fn = $db->data[fname];
			$ln = $db->data[lname];
			$em = $db->data[email];

			// output

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			echo "	<td align=\"left\">$em</td>\n";
			echo "   <td align=\"left\">$fn $ln</td>\n";
			echo "	<td align=\"right\">";

			// setup a GOD user who can't be edited, should be your username

			if($db->data[email] == 'mdoig1') echo "";
			else echo "<a href=\"main.php?SID=$SID&action=$action&do=uedit&id=" . $db->data[email] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\"></a>\n";

			// setup a GOD user who can't be deleted, should be your username

			if($db->data[email] == 'mdoig1') echo "";
			else echo "<a href=\"main.php?SID=$SID&action=$action&do=udel&id=" . $db->data[email] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\"></a></td>\n";

			echo "</tr>\n";
		}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}


function add_article_form($db)
{
	global $content,$action,$SID,$fcnt,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>Add a user to the database.</p>\n";

	// check all checkboxes javascript

	for ($i=0; $i<$fcnt; $i++) $f[] = "f$i";
	$fstr = "'" . join("','",$f) . "'";
	echo "<SCRIPT LANGUAGE=\"JavaScript\">\n<!--\nvar onoff=1;\n\nvar fs = new Array ($fstr);\n\n";
	echo "function CheckAll() {\n";
	echo "   for (var i=0;i<" . $fcnt . ";i++) {\n";
	echo "           eval (\"document.form.\"+fs[i]+\".checked = \" + onoff);\n";
	echo "   }\n     onoff = 1-onoff;\n}\n\n";
	echo "function InvertAll() {\n";
	echo "   for (var i=0;i<" . $fcnt . ";i++) {\n";
	echo "           eval (\"document.form.\"+fs[i]+\".checked = 1-document.form.\"+fs[i]+\".checked\");\n";
	echo "   }\n}\n//-->\n</SCRIPT>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "<tr>\n";
    echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;User Information</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

	echo "	<form action=\"main.php\" method=\"post\" name=\"form\">\n";
	echo "	<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "	<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "	<input type=\"hidden\" name=\"do\" value=\"uadd\">\n";
	echo "	<input type=\"hidden\" name=\"doit\" value=\"1\">\n";

	// output header, not to be in the for loop

	echo "<table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">\n";
	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\"><span class=\"trtopfont\"><b>Field Description</b></span></td>\n";
	echo "    <td align=\"left\" valign=\"top\"><span class=\"trtopfont\"><b>Field Information</b></span></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">users username</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"text\" name=\"email\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">users first name</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"text\" name=\"fname\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">users last name</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"text\" name=\"lname\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">set password</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"password\" name=\"p[]\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">verify password</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"password\" name=\"p[]\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";

	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><br>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "<tr>\n";
    echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;User Access</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

	echo "    <p><a href=\"javascript:CheckAll()\">(un)check all</a> / <a href=\"javascript:InvertAll()\">invert all</a></p>\n";
	echo "	  <p><b>global settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f0\" value=\"1\"> has access to user administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f14\" value=\"1\"> has access to schedule administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f21\" value=\"1\"> has access to points table administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f32\" value=\"1\"> has access to sponsors administration<br>\n";
	
	echo "	  <p><b>league settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f16\" value=\"1\"> has access to clubs administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f17\" value=\"1\"> has access to teams administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f18\" value=\"1\"> has access to grounds administration<br>\n";
	
	echo "	  <p><b>statistics settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f37\" value=\"1\"> has access to champions administration<br>\n";
	
	echo "	  <p><b>player settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f19\" value=\"1\"> has access to featured member administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f22\" value=\"1\"> has access to CCL officers administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f20\" value=\"1\"> has access to player administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f35\" value=\"1\"> has access to awards administration<br>\n";
	
	//echo "	  <p><b>faq admin</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f10\" value=\"1\"> has access to faq administration<br>\n";
	//echo "	  <p><b>poll admin</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f13\" value=\"1\"> has access to poll administration<br>\n";
	//echo "	  <p><b>photo admin</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f11\" value=\"1\"> has access to gallery administration<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f12\" value=\"1\"> has access to photo administration<br>\n";
	
	echo "	  <p><b>news settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f1\" value=\"1\"> has access to news administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f15\" value=\"1\"> has access to history administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f5\" value=\"1\"> has access to ccl documents administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f2\" value=\"1\"> has access to html administration<br>\n";
	
	echo "	  <p><b>cougars settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f23\" value=\"1\"> has access to cougars news administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f24\" value=\"1\"> has access to cougars schedule administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f25\" value=\"1\"> has access to cougars players administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f42\" value=\"1\"> has access to cougars clubs administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f31\" value=\"1\"> has access to cougars teams administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f44\" value=\"1\"> has access to cougars grounds administration<br>\n";
	
	echo "	  <p><b>tennis cricket settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f26\" value=\"1\"> has access to tennis news administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f27\" value=\"1\"> has access to tennis schedule administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f43\" value=\"1\"> has access to tennis clubs administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f28\" value=\"1\"> has access to tennis teams administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f29\" value=\"1\"> has access to tennis players administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f30\" value=\"1\"> has access to tennis documents administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f33\" value=\"1\"> has access to tennis points table administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f34\" value=\"1\"> has access to tennis groups administration<br>\n";
	
	//echo "	  <p><b>mailing lists</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f6\" value=\"1\"> has access to mailing lists<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f7\" value=\"1\"> has access to mailing list emails<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f8\" value=\"1\"> has access to mailing list arhives<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f9\" value=\"1\"> has access to send emails to mailing list<br><br>\n";
	
	echo "	  <p><b>miscellaneous table settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f38\" value=\"1\"> has access to seasons administration<br>\n";	
	echo "	  <input type=\"checkbox\" name=\"f41\" value=\"1\"> has access to leagues administration<br>\n";	
	
	echo "	  <p><b>calendar settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f39\" value=\"1\"> has access to calendar cat administration<br>\n";	
	echo "	  <input type=\"checkbox\" name=\"f40\" value=\"1\"> has access to calendar event administration<br>\n";	

	echo "    <p><input type=\"submit\" value=\"add user\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_user($db,$email,$fname,$lname,$p,$flags)
{
	global $content,$SID,$action,$bluebdr,$greenbdr,$yellowbdr;

	// make sure info is present and correct

	if ($fname == "" || $lname == "" || $email == "" || $p[0] == "" || $p[1] == "") {
		echo "<p>You must complete the user's first and last name, their email address and the password.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">try again</a></p>\n";
		return;
	}

	// make sure passwords match

	if ($p[0] != $p[1]) {
		echo "<p>The passwords you entered do not match.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">try again</a></p>\n";
		return;
	}

	// check for duplicate entries

	if ($db->Exists("SELECT email FROM admin WHERE email='$email'")) {
		echo "<p>A user with that email address already exists in the database.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">try again</a></p>\n";
		return;
	}

	// setup the variables

	$fname = addslashes(trim($fname));
	$lname = addslashes(trim($lname));
	$email = trim($email);
	$encpass = crypt($p[0],$p[0][0] . $p[0][1]);

	// query database

	$db->Insert("INSERT INTO admin (email,fname,lname,pword,flags,added) VALUES ('$email','$fname','$lname','$encpass','$flags',NOW())");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new user.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">add another user</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to user list</a></p>\n";
	} else {
		echo "<p>The user could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to user list</a></p>\n";
	}
}


function delete_user_check($db,$id)
{
	global $content,$SID,$action,$bluebdr,$greenbdr,$yellowbdr;

	// query the database

	$db->QueryRow("SELECT fname,lname,email FROM admin WHERE email='$id'");

	// setup variables

	$fname = $db->data[fname];
	$lname = $db->data[lname];
	$email = $db->data[email];

	// output

	echo "<p>Are you sure you wish to delete the user:</p>\n";
	echo "<p><b>$fname $lname, $email</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=udel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=udel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_user($db,$id,$doit)
{
	global $content,$SID,$action,$bluebdr,$greenbdr,$yellowbdr;

	// decided not to delete

	if (!$doit) echo "<p>You have chosen NOT to delete that user.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM admin WHERE email='$id'");
		echo "<p>You have now deleted that user.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the user listing</a></p>\n";
}


function edit_user_form($db,$id)
{
	global $content,$action,$SID,$fcnt,$bluebdr,$greenbdr,$yellowbdr;

	// query the database

	$db->QueryRow("SELECT * FROM admin WHERE email='$id'");

	// setup variables

	$fname = $db->data[fname];
	$lname = $db->data[lname];
	$email = $db->data[email];
	$flags = split("\^",$db->data[flags]);

	echo "<p>Edit the user.</p>\n";
	for ($i=0; $i<$fcnt; $i++) $f[] = "f$i";
	$fstr = "'" . join("','",$f) . "'";

	// check all checkboxes javascript

	echo "<SCRIPT LANGUAGE=\"JavaScript\">\n<!--\nvar onoff=1;\n\nvar fs = new Array ($fstr);\n\n";
	echo "function CheckAll() {\n";
	echo "   for (var i=0;i<" . $fcnt . ";i++) {\n";
	echo "           eval (\"document.form.\"+fs[i]+\".checked = \" + onoff);\n";
	echo "   }\n     onoff = 1-onoff;\n}\n\n";
	echo "function InvertAll() {\n";
	echo "   for (var i=0;i<" . $fcnt . ";i++) {\n";
	echo "           eval (\"document.form.\"+fs[i]+\".checked = 1-document.form.\"+fs[i]+\".checked\");\n";
	echo "   }\n}\n//-->\n</SCRIPT>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "<tr>\n";
    echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;User Information</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" name=\"form\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"uedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";

	// output header, not to be in the for loop

	echo "<table border=\"0\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">\n";
	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\"><span class=\"trtopfont\"><b>Field Description</b></span></td>\n";
	echo "    <td align=\"left\" valign=\"top\"><span class=\"trtopfont\"><b>Field Information</b></span></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">users username</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"text\" name=\"email\" size=\"25\" maxlength=\"255\" value=\"$email\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">users first name</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"text\" name=\"fname\" size=\"25\" maxlength=\"255\" value=\"$fname\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">users last name</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"text\" name=\"lname\" size=\"25\" maxlength=\"255\" value=\"$lname\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">set new password</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"password\" name=\"p[]\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";

	echo "  <tr class=\"trbottom\">\n";
	echo "    <td align=\"left\" valign=\"top\">verify new password</td>\n";
	echo "    <td align=\"left\" valign=\"top\"><input type=\"password\" name=\"p[]\" size=\"25\" maxlength=\"255\"></td>\n";
	echo "  </tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table><br>\n";

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "<tr>\n";
    echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;User Access</td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td>\n";

	echo "    <p><a href=\"javascript:CheckAll()\">(un)check all</a> / <a href=\"javascript:InvertAll()\">invert all</a></p>\n";

	echo "	  <p><b>global settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f0\" value=\"1\"" . ($flags[0]==1?" checked":"") . "> has access to user administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f14\" value=\"1\"" . ($flags[14]==1?" checked":"") . "> has access to schedule administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f21\" value=\"1\"" . ($flags[21]==1?" checked":"") . "> has access to points table administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f32\" value=\"1\"" . ($flags[32]==1?" checked":"") . "> has access to sponsors administration<br>\n";

	echo "	  <p><b>league settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f16\" value=\"1\"" . ($flags[16]==1?" checked":"") . "> has access to clubs administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f17\" value=\"1\"" . ($flags[17]==1?" checked":"") . "> has access to teams administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f18\" value=\"1\"" . ($flags[18]==1?" checked":"") . "> has access to grounds administration<br>\n";

	echo "	  <p><b>statistics settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f37\" value=\"1\"" . ($flags[37]==1?" checked":"") . "> has access to champions administration<br>\n";

	echo "	  <p><b>player settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f19\" value=\"1\"" . ($flags[19]==1?" checked":"") . "> has access to featured member administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f22\" value=\"1\"" . ($flags[22]==1?" checked":"") . "> has access to CCL officers administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f20\" value=\"1\"" . ($flags[20]==1?" checked":"") . "> has access to player administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f35\" value=\"1\"" . ($flags[35]==1?" checked":"") . "> has access to awards administration<br>\n";

	//echo "	  <p><b>faq admin</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f10\" value=\"1\"" . ($flags[10]==1?" checked":"") . "> has access to faq administration<br>\n";
	//echo "	  <p><b>poll admin</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f13\" value=\"1\"" . ($flags[13]==1?" checked":"") . "> has access to poll administration<br>\n";
	//echo "	  <p><b>photo admin</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f11\" value=\"1\"" . ($flags[11]==1?" checked":"") . "> has access to gallery administration<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f12\" value=\"1\"" . ($flags[12]==1?" checked":"") . "> has access to photo administration<br>\n";

	echo "	  <p><b>news settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f1\" value=\"1\"" . ($flags[1]==1?" checked":"") . "> has access to news administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f15\" value=\"1\"" . ($flags[15]==1?" checked":"") . "> has access to history administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f5\" value=\"1\"" . ($flags[5]==1?" checked":"") . "> has access to ccl documents administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f2\" value=\"1\"" . ($flags[2]==1?" checked":"") . "> has access to html administration<br>\n";

	echo "	  <p><b>cougars settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f23\" value=\"1\"" . ($flags[23]==1?" checked":"") . "> has access to cougars news administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f24\" value=\"1\"" . ($flags[24]==1?" checked":"") . "> has access to cougars schedule administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f25\" value=\"1\"" . ($flags[25]==1?" checked":"") . "> has access to cougars players administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f42\" value=\"1\"" . ($flags[42]==1?" checked":"") . "> has access to cougars clubs administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f31\" value=\"1\"" . ($flags[31]==1?" checked":"") . "> has access to cougars teams administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f44\" value=\"1\"" . ($flags[44]==1?" checked":"") . "> has access to cougars grounds administration<br>\n";

	echo "	  <p><b>tennis cricket settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f26\" value=\"1\"" . ($flags[26]==1?" checked":"") . "> has access to tennis news administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f27\" value=\"1\"" . ($flags[27]==1?" checked":"") . "> has access to tennis schedule administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f43\" value=\"1\"" . ($flags[43]==1?" checked":"") . "> has access to tennis clubs administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f28\" value=\"1\"" . ($flags[28]==1?" checked":"") . "> has access to tennis teams administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f29\" value=\"1\"" . ($flags[29]==1?" checked":"") . "> has access to tennis players administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f30\" value=\"1\"" . ($flags[30]==1?" checked":"") . "> has access to tennis documents administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f33\" value=\"1\"" . ($flags[33]==1?" checked":"") . "> has access to tennis points table administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f34\" value=\"1\"" . ($flags[34]==1?" checked":"") . "> has access to tennis groups administration<br>\n";

	//echo "	  <p><b>mailing lists</b></p>\n";
	//echo "	  <input type=\"checkbox\" name=\"f6\" value=\"1\"" . ($flags[6]==1?" checked":"") . "> has access to mailing lists<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f7\" value=\"1\"" . ($flags[6]==1?" checked":"") . "> has access to mailing list emails<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f8\" value=\"1\"" . ($flags[8]==1?" checked":"") . "> has access to mailing list arhives<br>\n";
	//echo "	  <input type=\"checkbox\" name=\"f9\" value=\"1\"" . ($flags[9]==1?" checked":"") . "> has access to send emails to mailing list<br><br>\n";

	echo "	  <p><b>miscellaneous table settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f38\" value=\"1\"" . ($flags[38]==1?" checked":"") . "> has access to seasons administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f41\" value=\"1\"" . ($flags[41]==1?" checked":"") . "> has access to leagues administration<br>\n";

	echo "	  <p><b>miscellaneous table settings</b></p>\n";
	echo "	  <input type=\"checkbox\" name=\"f39\" value=\"1\"" . ($flags[39]==1?" checked":"") . "> has access to calendar cat administration<br>\n";
	echo "	  <input type=\"checkbox\" name=\"f40\" value=\"1\"" . ($flags[40]==1?" checked":"") . "> has access to calendar event administration<br>\n";

	echo "  <p><input type=\"submit\" value=\"modify user\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";

	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_user($db,$id,$email,$fname,$lname,$p,$flags)
{
	global $content,$SID,$action,$bluebdr,$greenbdr,$yellowbdr;

	// make sure info is present and correct

	if ($fname == "" || $lname == "" || $email == "") {
		echo "<p>You must complete the user's first and last name and their email address.</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">try again</a></p>\n";
		return;
	}

	// passwords didn't match

	if ($p[0] != "") {
		if ($p[0] != $p[1]) {
			echo "<p>The passwords you entered do not match.</p>\n";
			echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">try again</a></p>\n";
			return;
		}
		$encpass = ",pword='" . crypt($p[0],$p[0][0] . $p[0][1]) . "'";
	} else {
		$encpass = "";
	}

	// check for duplicates

	if ($id != $email) {
		if ($db->Exists("SELECT email FROM admin WHERE email='$email'")) {
			echo "<p>A user with that email address already exists in the database.</p>\n";
			echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uadd\">try again</a></p>\n";
			return;
		}
	}

	// setup variables

	$fname = addslashes(trim($fname));
	$lname = addslashes(trim($lname));
	$email = trim($email);

	// query database

	$db->Update("UPDATE admin SET email='$email',fname='$fname',lname='$lname',flags='$flags'$encpass WHERE email='$id'");
	if ($db->a_rows != -1) {
		echo "<p>You have now modified that user.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the user listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=uedit&id=$email\">update $fname $lname some more</a></p>\n";
	} else {
		echo "<p>The user could not be modified at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the user listing</a></p>\n";
	}
}


// main program

if (!$USER['flags'][$f_user_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>User Administration</b></p>\n";

// setup flags

$fcnt = 45; // this number needs to be 1 greater than the maximum flag number setup in includes/general.config.inc

for ($i=0; $i<$fcnt; $i++) {
	$foo = "f$i";
	if ($$foo=="") $flags[]="0";
	else $flags[]="1";
}
$flags = join("^",$flags);

// do the main switch

switch($do) {
case "uadd":
	if (!isset($doit)) add_article_form($db);
	else do_add_user($db,$email,$fname,$lname,$p,$flags);
	break;
case "udel":
	if (!isset($doit)) delete_user_check($db,$id);
	else do_delete_user($db,$id,$doit);
	break;
case "uedit":
	if (!isset($doit)) edit_user_form($db,$id);
	else do_update_user($db,$id,$email,$fname,$lname,$p,$flags);
	break;
default:
	show_main_menu($db);
	break;
}

?>
