<?php

//------------------------------------------------------------------------------
// Colorado Cricket Champions v3.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - mike250@gmail.com
//------------------------------------------------------------------------------

function show_champ($db,$s,$id,$aw,$season,$sename)
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

        $db->Query("
            SELECT
                ch.*,
                te.TeamName, te.TeamAbbrev, te.TeamID, 
                se.*
            FROM
                champions ch
            INNER JOIN
                teams te ON ch.ChampTeam = te.TeamID
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
        echo "  <td align=\"left\" width=\"60%\"><b>TEAM</b></td>\n";
        echo "</tr>\n";
        
        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            
        // setup variables

        $tna = $db->data['teamname'];
        $tab = $db->data['TeamAbbrev'];
        $sn = $db->data['SeasonName'];
        $tid = $db->data[TeamID];
        
        echo '<tr class="trrow', ($x % 2 ? '1' : '2'), '">';
// 1-Mar-2010  - removed the words  League Champions.           
        echo "  <td align=\"left\" width=\"40%\">$sn</td>\n";
//        echo "  <td align=\"left\" width=\"40%\">$sn League Champions</td>\n";
        echo "  <td align=\"left\" width=\"60%\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$tna</a></td>\n";
        echo "</tr>\n";
        
        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";
        
    //////////////////////////////////////////////////////////////////////////////////////////
    // KO Champions List
    //////////////////////////////////////////////////////////////////////////////////////////

        $db->Query("
            SELECT
                ch.*,
                te.TeamName, te.TeamAbbrev, te.TeamID, 
                se.*
            FROM
                champions ch
            INNER JOIN
                teams te ON ch.ChampTeam = te.TeamID
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
        echo "  <td align=\"left\" width=\"60%\"><b>TEAM</b></td>\n";
        echo "</tr>\n";

        for ($x=0; $x<$db->rows; $x++) {
            $db->GetRow($x);
            
        // setup variables

        $tna = $db->data['teamname'];
        $tab = $db->data['TeamAbbrev'];
        $sn = $db->data['SeasonName'];
        $tid = $db->data[TeamID];
        
        echo '<tr class="trrow', ($x % 2 ? '1' : '2'), '">';
// 1-Mar-2010  - removed the words  League Champions.         
echo "  <td align=\"left\" width=\"40%\">$sn</td>\n";
//        echo "  <td align=\"left\" width=\"40%\">$sn League Champions</td>\n";
        echo "  <td align=\"left\" width=\"60%\"><a href=\"/teamdetails.php?teams=$tid&ccl_mode=1\">$tna</a></td>\n";
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


show_champ($db,$s,$id,$aw,$season,$sename);


?>
