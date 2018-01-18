<?php

//------------------------------------------------------------------------------
// Statistics v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_statistics_main($db,$statistics,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Records</font></p>\n";
        echo "    <p>There are currently no records in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
                }


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Records</font></p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">Records</b><br>Including Knock-Out Championship<br><br>\n";

        
    //////////////////////////////////////////////////////////////////////////////////////////
    // Records Selection Box
    //////////////////////////////////////////////////////////////////////////////////////////
        
        
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;RECORDS OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "<br><ul>\n";
        
        echo "<li><a href=\"$PHP_SELF?ccl_mode=resultstatistics\">Results Statistics</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=teamrecords\">Team Records</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=battingrecords\">Batting Records</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=bowlingrecords\">Bowling Records</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=allroundrecords\">All-Round Records</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=fieldingrecords\">Fielding Records</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=individualrecords\">Individual Records</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=miscellaneousrecords\">Miscellaneous Records</a></li>\n";
        
        echo "</ul>\n"; 

        echo "<ul>\n";

        // Show by team statistics

        if ($db->Exists("SELECT * FROM teams")) {
        $db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 ORDER BY TeamName");
            for ($x=0; $x<$db->rows; $x++) {
                $db->GetRow($x);
                $db->BagAndTag();
                $id = $db->data[TeamID];
                $ab = $db->data['teamname'];

        echo "<li><a href=\"$PHP_SELF?statistics=$statistics&team=$id&ccl_mode=2\">$ab</a></li>\n";
        }
    }

        echo "</ul>\n";

        echo "</td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        }
}


function show_resultstatistics($db,$statistics,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Records</font></p>\n";
        echo "    <p>There are currently no records in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
                }


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/records.php\">Records</a> &raquo; <font class=\"10px\">Results Statistics</font></p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">Results Statistics</b><br>Including Knock-Out Championship<br><br>\n";

        
    //////////////////////////////////////////////////////////////////////////////////////////
    // Statistics Secections Box
    //////////////////////////////////////////////////////////////////////////////////////////
        
        
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;RECORDS OPTIONS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "<br><ul>\n";
        
        echo "<li><a href=\"$PHP_SELF?ccl_mode=teamresults\">Team Results</a></li>\n";
        echo "<li><a href=\"$PHP_SELF?ccl_mode=highestwinmargins\">Highest Win Margins</a></li>\n";
        
        echo "</ul>\n"; 

        echo "</td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

        }
}




function show_teamresults($db,$statistics,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $dbcfg, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Statistics</font></p>\n";
        echo "    <p>There are currently no statisticsd games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
                }


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/records.php\">Records</a> &raquo; <a href=\"/records.php?ccl_mode=resultstatistics\">Result Statistics</a> &raquo; <font class=\"10px\">Team Results</font></p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">Team Results</b><br>Including Knock-Out Championship<br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Detailed Record
    //////////////////////////////////////////////////////////////////////////////////////////

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL COMPLETED MATCHES</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\"><b>TEAM</b></td>\n";
            echo "  <td align=\"center\"><b>MATCHES</b></td>\n";
            echo "  <td align=\"center\"><b>WON</b></td>\n";
            echo "  <td align=\"center\"><b>LOST</b></td>\n";
            echo "  <td align=\"center\"><b>TIED</b></td>\n";
            echo "  <td align=\"center\"><b>NR</b></td>\n";
            echo "  <td align=\"center\"><b>WIN %</b></td>\n";
            echo "</tr>\n";


        $db->Query("SELECT te.TeamID, te.TeamAbbrev, re.*, SUM((re.won) / (re.played - re.nr)) AS perc FROM teams te INNER JOIN results re ON te.TeamID=re.team_id GROUP BY TeamAbbrev ORDER BY Perc DESC");
        $db->BagAndTag();   
        
            for ($r=0; $r<$db->rows; $r++) {
            $db->GetRow($r);

            $tid = $db->data[TeamID];
            $tea = $db->data['TeamAbbrev'];
            $pla = $db->data['played'];
            $won = $db->data['won'];
            $los = $db->data['lost'];
            $tie = $db->data['tied'];
            $nor = $db->data[nr];
            $per = Round($db->data[perc]*100,0);
        
            echo '<tr class="trrow', ($r % 2 ? '2' : '1'), '">';

            echo "  <td align=\"left\"><a href=\"/teams.php?teams=$tid&ccl_mode=1\">$tea</a></td>\n";
            echo "  <td align=\"center\">$pla</td>\n";
            echo "  <td align=\"center\">$won</td>\n";
            echo "  <td align=\"center\">$los</td>\n";
            echo "  <td align=\"center\">$tie</td>\n";
            echo "  <td align=\"center\">$nor</td>\n";
            echo "  <td align=\"center\">$per%</td>\n";
            echo "</tr>\n";

            }
        }
                echo "</table>\n";
                
                echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
                echo "<p>&laquo; <a href=\"/records.php?ccl_mode=resultstatistics\">Back to Result Statistics</a></p>\n";
        
                echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";      
        
}


function show_highestwinmargins($db,$statistics,$id,$pr,$team,$week,$game_id)
{
        global $PHP_SELF, $dbcfg, $bluebdr, $greenbdr, $yellowbdr;

        if (!$db->Exists("SELECT * FROM scorecard_game_details")) {

        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";
        echo "    <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Statistics</font></p>\n";
        echo "    <p>There are currently no statisticsd games in the database.</p>\n";
        echo "    <p>&laquo; <a href=\"/index.php\">back to homepage</a></p>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";

                return;

        } else {

                $db->Query("SELECT * FROM seasons ORDER BY SeasonID");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
                }

                $db->Query("SELECT * FROM teams ORDER BY TeamName");
                for ($i=0; $i<$db->rows; $i++) {
                        $db->GetRow($i);
                        $teams[$db->data[TeamID]] = $db->data['TeamAbbrev'];
                }


        echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
        echo "  <tr>\n";
        echo "    <td align=\"left\" valign=\"top\">\n";

        echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
        echo "<tr>\n";
        echo "  <td align=\"left\" valign=\"top\">\n";
        echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/records.php\">Records</a> &raquo; <a href=\"/records.php?ccl_mode=resultstatistics\">Result Statistics</a> &raquo; <font class=\"10px\">High Win Margins</font></p>\n";
        echo "  </td>\n";
        echo "  <td align=\"right\" valign=\"top\">\n";
        require ("navtop.php");
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";


        echo "<b class=\"16px\">Highest Win Margins</b><br>Including Knock-Out Championship<br><br>\n";

    //////////////////////////////////////////////////////////////////////////////////////////
    // Detailed Record
    //////////////////////////////////////////////////////////////////////////////////////////

                echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
                echo "  <tr>\n";
                echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL COMPLETED MATCHES</td>\n";
                echo "  </tr>\n";
                echo "  <tr>\n";
            echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

            echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";

            echo "<tr class=\"colhead\">\n";
            echo "  <td align=\"left\"><b>TEAM</b></td>\n";
            echo "  <td align=\"center\"><b>MATCHES</b></td>\n";
            echo "  <td align=\"center\"><b>WON</b></td>\n";
            echo "  <td align=\"center\"><b>LOST</b></td>\n";
            echo "  <td align=\"center\"><b>TIED</b></td>\n";
            echo "  <td align=\"center\"><b>NR</b></td>\n";
            echo "  <td align=\"center\"><b>WIN %</b></td>\n";
            echo "</tr>\n";


        $db->Query("SELECT te.TeamID, te.TeamAbbrev, re.*, SUM((re.won) / (re.played - re.nr)) AS perc FROM teams te INNER JOIN results re ON te.TeamID=re.team_id GROUP BY TeamAbbrev ORDER BY Perc DESC");
        $db->BagAndTag();   
        
            for ($r=0; $r<$db->rows; $r++) {
            $db->GetRow($r);

            $tid = $db->data[TeamID];
            $tea = $db->data['TeamAbbrev'];
            $pla = $db->data['played'];
            $won = $db->data['won'];
            $los = $db->data['lost'];
            $tie = $db->data['tied'];
            $nor = $db->data[nr];
            $per = Round($db->data[perc]*100,0);
        
            echo '<tr class="trrow', ($r % 2 ? '2' : '1'), '">';

            echo "  <td align=\"left\"><a href=\"/teams.php?teams=$tid&ccl_mode=1\">$tea</a></td>\n";
            echo "  <td align=\"center\">$pla</td>\n";
            echo "  <td align=\"center\">$won</td>\n";
            echo "  <td align=\"center\">$los</td>\n";
            echo "  <td align=\"center\">$tie</td>\n";
            echo "  <td align=\"center\">$nor</td>\n";
            echo "  <td align=\"center\">$per%</td>\n";
            echo "</tr>\n";

            }
        }
                echo "</table>\n";
                
                echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table><br>\n";
        
                echo "<p>&laquo; <a href=\"/records.php?ccl_mode=resultstatistics\">Back to Result Statistics</a></p>\n";
        
                echo "    </td>\n";
        echo "  </tr>\n";
        echo "</table>\n";      
        
}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);



switch($ccl_mode) {
case "0":
    show_statistics_main($db,$statistics,$id,$pr,$team,$week,$game_id);
    break;
    
case "resultstatistics":
    show_resultstatistics($db,$statistics,$id,$pr,$team,$week,$game_id);
    break;  

case "teamresults":
    show_teamresults($db,$statistics,$id,$pr,$team,$week,$game_id);
    break;  

case "highestwinmargins":
    show_highestwinmargins($db,$statistics,$id,$pr,$team,$week,$game_id);
    break;  
    
default:
    show_statistics_main($db,$statistics,$id,$pr,$team,$week,$game_id);
    break;
}

?>