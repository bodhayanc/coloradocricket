<?php

//------------------------------------------------------------------------------
// Colorado Cricket Champions v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------

function show_champ($db)
{
    global $PHP_SELF,$bluebdr,$greenbdr,$yellowbdr;

    if (!$db->Exists("SELECT * FROM champions")) {
        echo "<p>The Champions database is being edited. Please check back shortly.</p>\n";
        return;
    } else {


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <font class=\"10px\">You are here:</font> <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Champions</font></p>\n";
        echo "  </td>\n";
        //echo "  <td align=\"right\" valign=\"top\">\n";
        //require ("includes/navtop.php");
        //echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        echo "<b class=\"16px\">Historical Champions</b><br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // League Champions List
    //////////////////////////////////////////////////////////////////////////////////////////
// 8-Oct-2015 10:30pm - Modified SQL to get the ChampTeam2 and ChampTeam3, and used LEFT JOINs
        $db->Query("
            SELECT
                ch.*,
                te.TeamName, te.TeamAbbrev, te.TeamID, 
				te2.TeamName as TeamName2, te2.TeamAbbrev as TeamAbbrev2, te2.TeamID as TeamID2, 
				te3.TeamName as TeamName3, te3.TeamAbbrev as TeamAbbrev3, te3.TeamID as TeamID3,
                se.*
            FROM
                champions ch
            INNER JOIN
                teams te ON ch.ChampTeam = te.TeamID
			LEFT JOIN
                teams te2 ON ch.ChampTeam2 = te2.TeamID
			LEFT JOIN
                teams te3 ON ch.ChampTeam3 = te3.TeamID
			INNER JOIN 
                seasons se ON ch.ChampSeason = se.SeasonID
            WHERE
                se.SeasonName NOT LIKE '%KO%'
            ORDER BY
                se.SeasonName DESC
        ");
        

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;LEAGUE CHAMPIONS LIST</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";      

        echo "<tr class=\"colhead\">\n";            
        echo "  <td align=\"left\" width=\"40%\"><b>SEASON</b></td>\n";
        echo "  <td align=\"left\" width=\"20%\"><b>Champion</b></td>\n";  // 8-Oct-2015 10:30pm
		echo "  <td align=\"left\" width=\"20%\"><b>Runners</b></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><b>3rd</b></td>\n";
        echo "</tr>\n";
        
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            
        // setup variables

        $tna = $db->data['TeamName'];
        $tab = $db->data['TeamAbbrev'];
        $sn = $db->data['SeasonName'];
        $tid = $db->data['TeamID'];
        
		$tna2 = $db->data['TeamName2'];  // 8-Oct-2015 10:30pm
        $tab2 = $db->data['TeamAbbrev2'];
        $tid2 = $db->data['TeamID2'];
		
		$tna3 = $db->data['TeamName3']; // 8-Oct-2015 10:30pm
        $tab3 = $db->data['TeamAbbrev3'];
        $tid3 = $db->data['TeamID3'];
		
        echo '<tr class="trrow', ($x % 2 ? '1' : '2'), '">';
// 1-Mar-2010  - removed the words  League Champions.           
        echo "  <td align=\"left\" width=\"40%\">$sn</td>\n";
//        echo "  <td align=\"left\" width=\"40%\">$sn League Champions</td>\n";
        echo "  <td align=\"left\" width=\"20%\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$tab</a></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><a href=\"/teamdetails.php?teams=$tid2&ccl_mode=1\">$tab2</a></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><a href=\"/teamdetails.php?teams=$tid3&ccl_mode=1\">$tab3</a></td>\n";
        echo "</tr>\n";
        
        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // KO Champions List
    //////////////////////////////////////////////////////////////////////////////////////////
// 8-Oct-2015 10:30pm - Modified SQL to get the ChampTeam2 and ChampTeam3, and used LEFT JOINs

        $db->Query("
            SELECT
                ch.*,
                te.TeamName, te.TeamAbbrev, te.TeamID,
				te2.TeamName as TeamName2, te2.TeamAbbrev as TeamAbbrev2, te2.TeamID as TeamID2, 
				te3.TeamName as TeamName3, te3.TeamAbbrev as TeamAbbrev3, te3.TeamID as TeamID3,				
                se.*
            FROM
                champions ch
            INNER JOIN
                teams te ON ch.ChampTeam = te.TeamID
			LEFT JOIN
                teams te2 ON ch.ChampTeam2 = te2.TeamID
			LEFT JOIN
                teams te3 ON ch.ChampTeam3 = te3.TeamID
            INNER JOIN 
                seasons se ON ch.ChampSeason = se.SeasonID
            WHERE
                se.SeasonName LIKE '%KO%'
            ORDER BY
                se.SeasonName DESC
        ");
        

            echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
            echo "  <tr>\n";
            echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;KNOCKOUT CHAMPIONS LIST</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
        echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";      

        echo "<tr class=\"colhead\">\n";            
        echo "  <td align=\"left\" width=\"40%\"><b>SEASON</b></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><b>Champion</b></td>\n";  // 8-Oct-2015 10:30pm
		echo "  <td align=\"left\" width=\"20%\"><b>Runners</b></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><b>3rd</b></td>\n";
        echo "</tr>\n";

        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            
        // setup variables

        $tna = $db->data['TeamName'];
        $tab = $db->data['TeamAbbrev'];
        $sn = $db->data['SeasonName'];
        $tid = $db->data['TeamID'];
        
		$tna2 = $db->data['TeamName2'];  // 8-Oct-2015 10:30pm
        $tab2 = $db->data['TeamAbbrev2'];
        $tid2 = $db->data['TeamID2'];
		
		$tna3 = $db->data['TeamName3']; // 8-Oct-2015 10:30pm
        $tab3 = $db->data['TeamAbbrev3'];
        $tid3 = $db->data['TeamID3'];
		
        echo '<tr class="trrow', ($x % 2 ? '1' : '2'), '">';
// 1-Mar-2010  - removed the words  League Champions.         
echo "  <td align=\"left\" width=\"40%\">$sn</td>\n";
//        echo "  <td align=\"left\" width=\"40%\">$sn League Champions</td>\n";
        echo "  <td align=\"left\" width=\"20%\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$tab</a></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><a href=\"/teamdetails.php?teams=$tid2&ccl_mode=1\">$tab2</a></td>\n";
		echo "  <td align=\"left\" width=\"20%\"><a href=\"/teamdetails.php?teams=$tid3&ccl_mode=1\">$tab3</a></td>\n";
		
        echo "</tr>\n";
        
        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";
        }
}


$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_champ($db);


?>
