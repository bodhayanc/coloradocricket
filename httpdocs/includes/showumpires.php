<?php

//------------------------------------------------------------------------------
// Colorado Cricket Umpires v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------


function show_umpires_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    // get the teams for grouping

    $db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamAbbrev");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    }
    $sel = "";
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	if (isset($_GET['status'])) {
		$status = $_GET['status'];
	} else {
		$status = 0;
	}
	if($status == "2") {
		$sel = "selected";
	}
	else if($status == "0") {
		$sel1 = "selected";
	}
	else if($status == "1") {
		$sel2 = "selected";
	}
	else if($status == "3") {
		$sel3 = "selected";
	}
	
	
	$str_drop = "<select id=\"status\" name=\"status\" onchange=\"changeURL(); \">";
	$str_drop .= "<option value='2' $sel>All</option>";
	$str_drop .= "<option value='0' $sel1>Active</option>";
	$str_drop .= "<option value='1' $sel2>Inactive</option>";
	$str_drop .= "<option value='3' $sel3>Level 1</option>";
	$str_drop .= "<\select>";
	
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Umpires</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("includes/navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
    echo "  <td width=\"70%\" align=\"left\" valign=\"top\"><b class=\"16px\">Certified Umpires</b></td>\n";
	echo "  <td width=\"30%\" align=\"right\"><b class=\"16px\">UMPIRING STATUS: </b>$str_drop</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
	
    //////////////////////////////////////////////////////////////////////////////////////////
    // Umpires Box
    //////////////////////////////////////////////////////////////////////////////////////////

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td width=\"60%\" bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;PLAYER NAME</td>\n";
		echo "    <td width=\"20%\" bgcolor=\"$bluebdr\" class=\"whitemain\" align=\"center\" height=\"23\">&nbsp;CERTIFIED UMPIRE</td>\n";
		echo "    <td width=\"20%\" bgcolor=\"$bluebdr\" class=\"whitemain\" align=\"center\" height=\"23\">&nbsp;LEVEL 1 UMPIRE</td>\n";
		echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"3\">\n";

    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

    for ($i=1; $i<=count($teams); $i++) {

//    $db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamID");
$db->Query("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamAbbrev");
    $db->GetRow($i-1);
    $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
    $teams_id[$i] = $db->data['TeamID'];
    

    echo "<tr class=\"colhead\">\n";
    echo "    <td colspan=\"3\" width=\"100%\"><b>" . htmlentities(stripslashes($teams[$db->data['TeamID']])) . "</b></td>\n";
    echo "  </tr>\n";

// 3-Apr-2014 11:53pm Changed the order by from playerfname to playerlname

	if($status == 0) {
		$query = "SELECT te.TeamID, te.TeamAbbrev, te.TeamName, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.isumpire=1 AND pl.isactive=0 AND te.TeamActive=1 AND pl.PlayerTeam=$teams_id[$i] ORDER BY te.TeamAbbrev, pl.PlayerLName";
	} else if($status == "2") {
		$query = "SELECT te.TeamID, te.TeamAbbrev, te.TeamName, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.isactive=0 AND te.TeamActive=1 AND pl.PlayerTeam=$teams_id[$i] ORDER BY te.TeamAbbrev, pl.PlayerLName";
	}
	else if($status == "1") {
		$query = "SELECT te.TeamID, te.TeamAbbrev, te.TeamName, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.isumpire=0 AND pl.isactive=0 AND te.TeamActive=1 AND pl.PlayerTeam=$teams_id[$i] ORDER BY te.TeamAbbrev, pl.PlayerLName";
	}
	else if($status == "3") {
		$query = "SELECT te.TeamID, te.TeamAbbrev, te.TeamName, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.isumpire=1 AND pl.IsL1Umpire=1 AND pl.isactive=0 AND te.TeamActive=1 AND pl.PlayerTeam=$teams_id[$i] ORDER BY te.TeamAbbrev, pl.PlayerLName";
	}
    if (!$db->Exists($query)) {

    //echo "<tr class=\"trrow2\">\n";
    //echo "    <td width=\"100%\"><a href=\"/players.php?players=$id&ccl_mode=1\">$pfn $pln</a></td>\n";
    //echo "  </tr>\n";
    //return;

    } else {
    
    $db->QueryRow($query);
    $db->BagAndTag();

    for ($r=0; $r<$db->rows; $r++) {
        $db->GetRow($r);
        $id = htmlentities(stripslashes($db->data['PlayerID']));
        $pln = htmlentities(stripslashes($db->data['PlayerLName']));
        $pfn = htmlentities(stripslashes($db->data['PlayerFName']));
        $pte = htmlentities(stripslashes($db->data['TeamAbbrev']));
        $ptn = htmlentities(stripslashes($db->data['TeamName']));
        $ump = htmlentities(stripslashes($db->data['IsUmpire']));
        $l1ump = htmlentities(stripslashes($db->data['IsL1Umpire']));

    if($ump == 1) {
		$umpText = "Yes";
	} else {
		$umpText = "No";
	}
    if($l1ump == 1) {
		$l1UmpText = "Yes";
	} else {
		$l1UmpText = "No";
	}
    if($r % 2) {
      echo "<tr class=\"trrow1\">\n";
    } else {
      echo "<tr class=\"trrow2\">\n";
    }

    echo "    <td width=\"60%\"><a href=\"/players.php?players=$id&ccl_mode=1\">$pln, $pfn</a></td>\n";  // Going with lastname, firstname
    echo "    <td width=\"20%\" align=\"center\">$umpText</td>\n";  
    echo "    <td width=\"20%\" align=\"center\">$l1UmpText</td>\n";  
//    echo "    <td width=\"100%\"><a href=\"/players.php?players=$id&ccl_mode=1\">$pfn $pln</a></td>\n";   // commented 3-Apr-2014 11:52pm
    echo "  </tr>\n";
    }

    }
    }
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";  

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_umpires_listing($db);

?>
<script language="javascript">
function changeURL() {
	status = document.getElementById('status').value;
	document.location.href = "umpires.php?status=" + status;
}

</script>