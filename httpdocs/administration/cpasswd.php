<?php

//------------------------------------------------------------------------------
// Site Control Password Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


if (!$doit) {

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;Change Password</td>\n";
      	echo "</tr>\n";      	
      	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo"<form method=\"post\" action=\"$PHP_SELF\">\n";
	echo"<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo"<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo"<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo"<p>Enter your password<p>\n";
	echo"<input type=\"password\" name=\"p[]\" size=\"30\" maxlength=\"125\">\n";
	echo"<p>Verify your password<p>\n";
	echo"<input type=\"password\" name=\"p[]\" size=\"30\" maxlength=\"125\">\n";
	echo"<p><input type=\"submit\" value=\"change password\"></p>\n";
	echo"</form>\n";
	
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}
else {
	$ok_form=1;
	if ($p[0]=="" || $p[1]=="") {
		echo"<p>You must enter both passwords.</p>\n";
		return;
	}
	else if (strlen($p[0]) < $gencfg[passlen]) {
		echo"<p>Your password is too short.</p>\n";
		return;
	}
	else if ($p[0] != $p[1]) {
		echo"<p>Your passwords do not match.</p>\n";
		return;
	}
	if ($ok_form) {
		$encpass = crypt($p[0],$p[0][0].$p[0][1]);
		$db->Update("UPDATE $tbcfg[admin] SET pword='$encpass' WHERE email='$USER['email']'");
		if ($db->a_rows != -1) echo"<p>Your password has now been changed.</p>\n";
		else echo"<p>Your password could not be changed at this time.</p>\n";
	}
}

?>
