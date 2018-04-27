<?php

//------------------------------------------------------------------------------
// Site Control History Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;
        
         // 5-Jan-2010 - uncommented
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add an officer</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM cclofficers")) {
		echo "<p>There are currently no ccl officers in the database.</p>\n";
		return;
	} else {

		// output header, not to be included in for loop

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;CCL Officers</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"2\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

		// query database

		$db->Query("
			SELECT
			    of.cclofficerID, of.season_year, of.start_date, of.end_date , of.status, of.cclofficerTitle, of.cclofficerPlayerID,
				pl.PlayerFName, pl.PlayerLName, pl.PlayerID, pl.PlayerTeam,
				te.TeamName, te.TeamAbbrev, te.TeamID
			FROM
				cclofficers of
			INNER JOIN
				players pl ON of.cclofficerPlayerID = pl.PlayerID
			INNER JOIN
				teams te ON pl.PlayerTeam = te.TeamID
			ORDER BY
				of.start_date DESC, of.cclofficerPlayerid ASC, of.status ASC
		");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables
			$std = $db->data['start_date'];
			$etd = $db->data['end_date'];
			$st = $db->data['status'];
                        $sy = $db->data['season_year'];
			$ot = htmlentities(stripslashes($db->data['cclofficerTitle']));
			$id = htmlentities(stripslashes($db->data['cclofficerID']));

			$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
			$pln = htmlentities(stripslashes($db->data['PlayerLName']));

			$tna = htmlentities(stripslashes($db->data['TeamName']));
			$tab = htmlentities(stripslashes($db->data['TeamAbbrev']));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output
                        echo "	<td align=\"left\">$sy</td>\n";
			echo "	<td align=\"left\">$std</td>\n";
			echo "	<td align=\"left\">$etd</td>\n";
			echo "	<td align=\"left\">$st</td>\n";
			echo "	<td align=\"left\">$ot</td>\n";
			echo "	<td align=\"left\">$pfn $pln</td>\n";
			echo "	<td align=\"left\">$tab</td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
		}
		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
	}
}

function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a ccl officer</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";


	echo "<p>select a player for the position</p>\n";

	echo "<select name=\"cclofficerPlayerID\">\n";
	echo "	<option value=\"\">Select a player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT * FROM players")) {
		$db->Query("SELECT * FROM players ORDER BY PlayerLName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerLName'] . ", " . $db->data['PlayerFName'] . "</option>\n";
		}
	}

	echo "</select>\n";

// Select the Officer Title
echo "<p>select the officer Title</p>\n";
echo "<select name=\"cclofficerTitle\">\n";
	echo "	<option value=\"\">Select the officer Title</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	if ($db->Exists("SELECT distinct cclofficerTitle FROM cclofficers")) {
		$db->Query("SELECT distinct cclofficerTitle FROM cclofficers");
		for ($i=0; $i<$db->rows; $i++) {
                      echo "$db->data['cclofficerTitle']";
			$db->GetRow($i);
			echo "<option value=\"".$db->data['cclofficerTitle']."\">".$db->data['cclofficerTitle']."</option>\n";
//			echo "<option value=\"" . $db->data['PlayerID'] . "\">" . $db->data['PlayerLName'] . ", " . $db->data['PlayerFName'] . "</option>\n";

		}
	}
	echo "</select>\n";


//	echo "<p>enter the ccl officer Title</p><textarea name=\"cclofficerTitle\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";

       echo "<p>Season Year: <input type=\"text\" name=\"season_year\" size=\"4\"></p>\n";
        echo "<p>Start Date: <input type=\"text\" name=\"start_date\" size=\"10\"></p>\n";
        echo "<p>End Date: <input type=\"text\" name=\"end_date\" size=\"10\"></p>\n";
        echo "<p>Status (A-Active, I-Inactive): <textarea name=\"status\" cols=\"1\" rows=\"1\" wrap=\"virtual\"></textarea></p>\n";

	echo "<p>enter the detail</p><textarea name=\"cclofficerDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";
	echo "<p>enter the players views</p><textarea name=\"cclofficerViews\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";

	echo "<p><input type=\"submit\" value=\"add member\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$cclofficerPlayerID,$cclofficerTitle,$cclofficerDetail,$cclofficerViews, $season_year, $start_date, $end_date, $status)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$fp = addslashes(trim($cclofficerPlayerID));
	$fd = addslashes(trim($cclofficerTitle));
	$de = addslashes(trim($cclofficerDetail));
	$vi = addslashes(trim($cclofficerViews));
	$d = date("Y",time()) . "-" . date("m",time()) . "-" . date("j",time());

       $sy = trim($season_year);
        $sdt = trim($start_date);
        $edt = trim($end_date);
        $sta = trim($status);

	// all okay

	$db->Insert("INSERT INTO cclofficers (cclofficerPlayerID,cclofficerTitle,cclofficerDetail,cclofficerViews,season_year, start_date, end_date, status) VALUES ('$fp','$fd','$de','$vi', '$sy', '$sdt', '$edt', '$sta')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new ccl officer</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another ccl officer</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to ccl officer list</a></p>\n";
	} else {
		echo "<p>The member could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to ccl officer list</a></p>\n";
	}
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all players
	$db->Query("SELECT PlayerID, PlayerLName, CONCAT(PlayerLName,', ',PlayerFName) AS PlayerName FROM players ORDER BY PlayerLName");
	for ($p=0; $p<$db->rows; $p++) {
		$db->GetRow($p);
        $db->BagAndTag();
		$players[$db->data['PlayerID']] = $db->data['PlayerName'];
	}

	// query database

	$db->QueryRow("SELECT of.*, pl.PlayerLName, pl.PlayerFName FROM cclofficers of INNER JOIN players pl ON of.cclofficerPlayerID=pl.PlayerID WHERE of.cclofficerID=$id");

	// setup variables

	$ot = htmlentities(stripslashes($db->data['cclofficerTitle']));
	$otid = htmlentities(stripslashes($db->data['cclofficerPlayerID']));
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pln = htmlentities(stripslashes($db->data['PlayerLName']));
	$ofid = htmlentities(stripslashes($db->data['cclofficerID']));
	$det = htmlentities(stripslashes($db->data['cclofficerDetail']));
	$vie = htmlentities(stripslashes($db->data['cclofficerViews']));

$sy = htmlentities(stripslashes($db->data['season_year']));
$sdt= htmlentities(stripslashes($db->data['start_date']));
$edt = htmlentities(stripslashes($db->data['end_date']));
$sta = htmlentities(stripslashes($db->data['status']));


      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit $ot</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$ofid\">\n";

	echo "<p>select the player for the $ot position</p>\n";

	echo "<select name=\"cclofficerPlayerID\">\n";
	echo "	<option value=\"\">Select Player</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

	foreach ($players as $id => $name) {
		echo '<option value="', $id, '"', ($id == $db->data['cclofficerPlayerID'] ? ' selected' : ''), ">$name</option>\n";
	}

	echo "</select></p>\n";

//        echo "<p>Season Year</p><textarea name=\"season_year\" cols=\"4\" rows=\"1\" wrap=\"virtual\">$sy</textarea></p>\t";
        echo "<p>Season Year: <textarea name=\"season_year\" cols=\"4\" rows=\"1\" wrap=\"virtual\">$sy</textarea></p>\n";
        echo "<p>Start Date: <textarea name=\"start_date\" cols=\"10\" rows=\"1\" wrap=\"virtual\">$sdt</textarea></p>\n";
        echo "<p>End Date: <textarea name=\"end_date\" cols=\"10\" rows=\"1\" wrap=\"virtual\">$edt</textarea></p>\n";
        echo "<p>Status (A-Active, I-Inactive): <textarea name=\"status\" cols=\"1\" rows=\"1\" wrap=\"virtual\">$sta</textarea></p>\n";

	echo "<p>enter the detail</p><textarea name=\"cclofficerDetail\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$det</textarea></p>\n";
	echo "<p>enter the players views</p><textarea name=\"cclofficerViews\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$vie</textarea></p>\n";

	echo "<p><input type=\"submit\" value=\"edit ccl officer\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$cclofficerPlayerID,$cclofficerDetail,$cclofficerViews, $season_year, $start_date, $end_date, $status)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$otid = addslashes(trim($cclofficerPlayerID));
	$det = addslashes(trim($cclofficerDetail));
	$vie = addslashes(trim($cclofficerViews));

        $sy = trim($season_year);
        $sdt = trim($start_date);
        $edt = trim($end_date);
        $sta = trim($status);


// query database

	$db->Update("UPDATE cclofficers SET cclofficerPlayerID='$otid',cclofficerDetail='$det',cclofficerViews='$vie', season_year='$sy', start_date='$sdt', end_date='$edt', status='$sta' WHERE cclofficerID=$id");
		echo "<p>You have now updated that ccl officer.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the ccl officer listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update ccl officer some more</a></p>\n";
}

function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$db->QueryRow("SELECT of.*, pl.PlayerLName, pl.PlayerFName FROM cclofficers of INNER JOIN players pl ON of.cclofficerPlayerID=pl.PlayerID WHERE of.cclofficerID=$id");

	// setup variables

	$ot = htmlentities(stripslashes($db->data['cclofficerTitle']));
	$otid = htmlentities(stripslashes($db->data['cclofficerPlayerID']));
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pln = htmlentities(stripslashes($db->data['PlayerLName']));

	// output

	echo "<p>Are you sure you wish to delete the officer $pfn $pln as: <b>$ot</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}

function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete the officer.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM cclofficers WHERE cclofficerID=$id");
		echo "<p>You have now deleted that CCL officer.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the CCL officers listing</a></p>\n";
}


// main program

if (!$USER['flags'][$f_cclofficers_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>CCL Officers Administration</b></p>\n";
if (isset($_GET['do'])) {
	$do = $_GET['do'];
} else if(isset($_POST['do'])) {
	$do = $_POST['do'];
} else {
	$do = "";
}

switch($do) {
case "sadd":
	if (!isset($_POST['doit'])) add_category_form($db);
	else do_add_category($db,$_POST['cclofficerPlayerID'],$_POST['cclofficerTitle'],$_POST['cclofficerDetail'],$_POST['cclofficerViews'], $_POST['season_year'], $_POST['start_date'], $_POST['end_date'], $_POST['status']);
	break;
case "sdel":
	if (!isset($_GET['doit'])) delete_category_check($db,$_GET['id']);
	else do_delete_category($db,$_GET['id'],$_GET['doit']);
	break;
case "sedit":
	if (!isset($_POST['doit'])) edit_category_form($db,$_GET['id']);
	else do_update_category($db,$_POST['id'],$_POST['cclofficerPlayerID'],$_POST['cclofficerDetail'],$_POST['cclofficerViews'], $_POST['season_year'], $_POST['start_date'], $_POST['end_date'], $_POST['status']);
	break;
default:
	show_main_menu($db);
	break;
}

?>
