<?php

//------------------------------------------------------------------------------
// Mailing List Archive Admin v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


echo "<p class=\"14px\">Mailing List Archives</p>\n";

// list

if (!isset($do)) {
	if (!$db->Exists("SELECT * FROM $tbcfg[mlarchive]")) {
		echo "<p>There are currently no list types archives in the database.</p>\n";
		return;
	}
	if (!$db->Exists("SELECT * FROM $tbcfg[mllists]")) {
		echo "<p>There are currently no list types in the database.</p>\n";
		return;
	}

	// get the list types

	$db->Query("SELECT ID,name FROM $tbcfg[mllists] ORDER BY name");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$lists[$db->data['id']] = $db->data[name];
	}

	// now go for the display

	echo "<p>The list types archives currently in the database are:</p>\n";
	foreach ($lists as $k => $v) {

		// get archives - if any

		if (!$db->Exists("SELECT * FROM $tbcfg[mlarchive] WHERE listID=$k ORDER BY date DESC")) $cnt=0;
		else {
			$db->Query("SELECT * FROM $tbcfg[mlarchive] WHERE listID=$k ORDER BY date DESC");
			$cnt = $db->rows;
		}

		// print details

      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\" align=\"left\">&nbsp;'$v' list type</td>\n";
      	echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\" align=\"right\">";
    
    	if ($cnt) echo "<a href=\"$PHP_SELF?SID=$SID&action=$action&do=delall&list=$k\"><span class=\"white\">delete all</span></a></td></tr>\n";
    	else echo "&nbsp;</td></tr>\n";
    	    	
      	echo "<tr>\n";
	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "<p><table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\">\n";
	
	if (!$cnt) echo "<tr><td valign=\"top\" colspan=\"3\">There are currently no archived messages for this list type.</td></tr>\n";

		else {
			for ($i=0; $i<$cnt; $i++) {
				$db->GetRow($i);
				echo "<tr class=\"trbottom\">";
				echo "<td valign=\"top\"><a href=\"$PHP_SELF?SID=$SID&action=ml_archive&do=view&id=" . $db->data['id'] . "\">" . htmlentities($db->data[subject]) . "</a></td>";
				echo "<td valign=\"top\" align=\"right\">" . $db->data[date] . "</td>";
				echo "<td valign=\"top\" align=\"right\"><a href=\"$PHP_SELF?SID=$SID&action=ml_archive&do=delete&id=" . $db->data['id'] . "\">delete</a></td>";
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

// view

if ($do == "view") {
	if (!isset($id) || $id=="") {
		echo "<p>You must supply the id of the archive you want to view.</p>\n";
		return;
	}
	if (!$db->Exists("SELECT * FROM $tbcfg[mlarchive] WHERE ID=$id")) {
		echo "<p>That archive no longer exists.  It must have been deleted.</p>\n";
		return;
	}
	$db->QueryRow("SELECT a.*,l.name FROM $tbcfg[mlarchive] a LEFT JOIN $tbcfg[mllists] l ON a.listID=l.ID WHERE a.ID=$id");
	echo "<p>The archive store for the " . $db->data['id'] . " list type is as follows:</p>\n";
	echo "<p><table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">\n";
	echo "<tr class=\"trtop\"><td><span class=\"white\">field</span></td><td><span class=\"white\">value</span></td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"top\">Subject</td><td valign=\"top\">" . htmlentities($db->data[subject]) . "</td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"top\">Date</td><td valign=\"top\">" . $db->data[date] . "</td></tr>\n";
	echo "<tr class=\"trbottom\"><td valign=\"top\">Message</td><td valign=\"top\">" . nl2br(htmlentities($db->data[body])) . "</td></tr>\n";
	echo "</table></p>\n";
	echo "<p>&laquo; <a href=\"$PHP_SELF?SID=$SID&action=$action\">return to the archive list</a></p>\n";
	return;
}

// delete

if ($do == "delete") {

	// show initial form

	if (!isset($stage)) {
		$db->QueryRow("SELECT a.*,l.name FROM $tbcfg[mlarchive] a LEFT JOIN $tbcfg[mllists] l ON a.listID=l.ID WHERE a.ID=$id");
		echo "<p>Are you sure you wish to delete the archived message from the <b>" . $db->data[name] . "</b> list type, titled <b>" . htmlentities($db->data[subject]) . "</b> (sent on " . $db->data[date] . ")?</p>\n";
		echo "<p><a href=\"$PHP_SELF?SID=$SID&action=ml_archive&do=delete&id=$id&stage=1&ok=1\">YES</a> | ";
		echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_archive&do=delete&id=$id&stage=1&ok=0\">NO</a></p>";
		return;
	}
	if (!$ok) {
		echo "<p>You have chosen not to delete that archived message at this time.</p>\n";
		echo "<p><a href=\"$PHP_SELF?SID=$SID&action=$action\">&laquo; return to the archive list</a></p>\n";
		return;
	}
	$db->Delete("DELETE FROM $tbcfg[mlarchive] WHERE ID=$id");
	if ($db->a_rows != -1) echo "<p>You have now deleted that archived message.</p>\n";
	else echo "<p>That archived message could not be deleted from the database at this time.</p>\n";
	echo "<p><a href=\"$PHP_SELF?SID=$SID&action=$action\">&laquo; return to the archive list</a></p>\n";
	return;
}

// delete all messages

if ($do == "delall") {

	// show initial form

	if (!isset($stage)) {
		$db->QueryRow("SELECT name FROM $tbcfg[mllists] WHERE ID=$list");
		echo "<p>Are you sure you wish to delete <b>all</b> the archived messages from the <b>" . $db->data[name] . "</b> list type?</p>\n";
		echo "<p><a href=\"$PHP_SELF?SID=$SID&action=ml_archive&do=delall&list=$list&stage=1&ok=1\">YES</a> | ";
		echo "<a href=\"$PHP_SELF?SID=$SID&action=ml_archive&do=delall&list=$list&stage=1&ok=0\">NO</a></p>";
		return;
	}
	if (!$ok) {
		echo "<p>You have chosen not to delete all the archived messages for that list type.</p>\n";
		echo "<p><a href=\"$PHP_SELF?SID=$SID&action=$action\">&laquo; return to the archive list</a></p>\n";
		return;
	}
	$db->Delete("DELETE FROM $tbcfg[mlarchive] WHERE listID=$list");
	if ($db->a_rows != -1) echo "<p>You have now deleted all the archived messages for that list type.</p>\n";
	else echo "<p>The archived messages could not be deleted from the database at this time.</p>\n";
	echo "<p><a href=\"$PHP_SELF?SID=$SID&action=$action\">&laquo; return to the archive list</a></p>\n";
	return;
}

?>
