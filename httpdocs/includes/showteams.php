<?php

//------------------------------------------------------------------------------
// Teams v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_teams_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
    
    // Find the current season

    $db->Query("SELECT * FROM seasons WHERE SeasonName NOT LIKE '%KO%' ORDER BY SeasonName DESC LIMIT 1");
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $season = $db->data['SeasonID'];
    }
    

    if ($db->Exists("SELECT * FROM teams")) {
    // 2-Dec-2009
      $db->QueryRow("SELECT * FROM teams WHERE LeagueID = 1 ORDER BY TeamActive DESC, TeamName ASC");
      //$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID = 1 ORDER BY TeamName");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Teams</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active League Teams</b><br><br>\n";

    // Teams Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL TEAMS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['TeamID']));
        $na = htmlentities(stripslashes($db->data['TeamName']));
        $ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
        $ts = htmlentities(stripslashes($db->data['TeamActive']));
        if($ts == "1") {
        	$ts_status = "Active";
        }
        else {
        	$ts_status = "<font color='red'>Inactive</font>";
        }
        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=60% align=\"left\"><a href=\"teamdetails.php?teams=$id&ccl_mode=1\">$na</a> <font class=\"10px\">($ta)</a>\n";
        // 3-Dec-2009
//        echo "    </td>\n";
        echo "    <td width=10%>$ts_status</td>";
//        echo "  </tr>\n";

        echo "    <td width=30% align=\"left\"><a href=\"statistics.php?statistics=&team=$id&ccl_mode=2\">Career Stats</a> | <a href=\"/schedule.php?schedule=$season&team=$id&ccl_mode=2\">Schedule</a> <br><a href=\"teamdetails.php?teams=$id&ccl_mode=1#players\">Players</a>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no teams in the database\n";
    }
}


function show_full_teams($db,$pr)
{
    global $PHP_SELF;

    $db->QueryRow("SELECT * FROM teams WHERE TeamID=$pr");
    $db->BagAndTag();

    $id = $db->data['TeamID'];
    $na = $db->data['TeamName'];
    $ca = $db->data['TeamAbbrev'];
    $ur = $db->data['TeamURL'];
    $co = $db->data['TeamColour'];
    $td = $db->data['TeamDesc'];
	if (($co == null) || ($co == "")) {
		$co = "000000";
	}
    
    
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/teams.php\">Teams</a> &raquo; <font class=\"10px\">Team Page</font></p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$na ($ca)</b><br><br>\n";

    //-------------------------------------------------
    // Team Photo Box and Records BOX
    //-------------------------------------------------

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;THE TEAM</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "<tr class=\"trrow1\">\n";
    echo "<td width=\"100%\" align=\"left\">\n";

    
    $db->QueryRow("SELECT picture, teamlogo FROM teams WHERE TeamID=$pr");
    $db->BagAndTag();

    $pic = $db->data['picture'];
    $teamlogo = $db->data['teamlogo'];

    if ($pic != "" ) {
   	$str_team_pic =  "<a href=\"/uploadphotos/teams/$pic\" onClick=\"return enlarge('/uploadphotos/teams/$pic',event)\"><img src=\"/uploadphotos/teams/$pic\" width=\"150\" align=\"right\" border=\"1\"></a>\n";
    } else {
    $str_team_pic = "";
    }
    
   
    
	if ($teamlogo != "" ) {
		list($width, $height, $type, $attr) = getimagesize("http://coloradocricket.org/uploadphotos/teams/$teamlogo");
		if($width >= 150) {
	    	$width = "150";
	    }
	   	echo "<a href=\"/uploadphotos/teams/$teamlogo\" onClick=\"return enlarge('/uploadphotos/teams/$teamlogo',event)\"><img src=\"/uploadphotos/teams/$teamlogo\" width=$width align=\"right\" border=\"1\"></a>\n";
		
    } else {
    echo "";
    }
    
    if ($db->Exists("SELECT ch.*, te.TeamName, te.TeamAbbrev, se.* FROM champions ch INNER JOIN teams te ON ch.ChampTeam = te.TeamID INNER JOIN  seasons se ON ch.ChampSeason = se.SeasonID WHERE ch.ChampTeam=$pr ORDER BY se.SeasonName DESC")) {
    $db->QueryRow("SELECT ch.*, te.TeamName, te.TeamAbbrev, se.* FROM champions ch INNER JOIN teams te ON ch.ChampTeam = te.TeamID INNER JOIN  seasons se ON ch.ChampSeason = se.SeasonID WHERE ch.ChampTeam=$pr ORDER BY se.SeasonName DESC");
    $db->BagAndTag();
        
    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
            
    $tna = $db->data['TeamName'];
    $tab = $db->data['TeamAbbrev'];
    $sn = $db->data['SeasonName'];

        
    echo "$sn League Champions<br>\n";
    
    }
    
    } else {
    
    echo "";
    }
        
    echo "<p>$td</p>\n";
    
    echo " </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    //------------------------------------------------- 
    // Team News Box
    //-------------------------------------------------
    
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;IN THE NEWS</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#$co\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";
    echo "  <table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";


        $db->Query("SELECT * FROM news WHERE IsFeature != 1 AND (article LIKE '%$ca%' OR article LIKE 'na') OR (title LIKE '%$ca%' OR title LIKE 'na') ORDER BY added DESC LIMIT 5");

        // output featured articles
        for ($n=0; $n<$db->rows; $n++) {
            $db->GetRow($n);
            $db->DeBagAndTag();

            $t = $db->data['title'];
            $au = $db->data['author'];
            $newsid = $db->data['id'];
            $date = sqldate_to_string($db->data['added']);

        if($n % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"news.php?news=$newsid&ccl_mode=1\">$t</a>\n";
        if($db->data['picture'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">\n";
        echo "    </td>\n";
        echo "    <td width=\"25%\" align=\"right\" class=\"9px\">$date</td>\n";
        echo "  </tr>\n";

        }


        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";
        

    //------------------------------------------------- 
    // Team Statistics Box
    //-------------------------------------------------
    
    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td colspan=2 bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;CAREER STATISTICS</td>\n";
    echo "  </tr>\n";
    echo "  <tr class=\"trrow1\">\n";
    echo "  <td valign=\"top\" bordercolor=\"#FFFFFF\" >\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "  <td width=60%  valign=\"top\" bordercolor=\"#FFFFFF\" >\n";

    echo "<br>\n";
    echo "<ul>\n";
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&sort=Average&sort2=Runs&ccl_mode=3\">Career Batting: Highest Averages</li>\n";
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&sort=Runs&sort2=Average&ccl_mode=3\">Career Batting: Most Runs</li>\n";
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&ccl_mode=4\">Career Batting: Highest Innings Scores</li>\n";
    echo "</ul>\n";
    echo "<ul>\n";          
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&sort=Average&direction=asc&ccl_mode=5\">Career Bowling: Best Averages</li>\n";
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&sort=Wickets&direction=desc&ccl_mode=5\">Career Bowling: Most Wickets</li>\n";
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&ccl_mode=6\">Career Bowling: Best Innings Performances</li>\n";
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&sort=Balls&direction=desc&ccl_mode=5\">Career Bowling: Workhorses</li>\n";
    echo "</ul>\n";             
    echo "<ul>\n";          
    echo "<li><a href=\"statistics.php?option=teamcareer&team=$id&ccl_mode=7\">Career All-Round: 250 Runs & 25 Wickets</li>\n";
    echo "</ul>\n"; 
    echo "</td>\n";
    echo "<td width=40% valign=\"top\"><br>$str_team_pic</td>\n";
    echo "</tr>\n";
    echo "</table>"; 
    echo "</td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";  

    //-------------------------------------------------
    // Team Players Box
    //-------------------------------------------------
    
    echo "<a name=\"#players\"></a>\n";
   		$str_mode = $_GET['ccl_mode'];
		$str_teams = $_GET['teams'];
		$sel = "";
		$sel1 = "";
		$sel2 = "";
		if (isset($_GET['status'])) {
			if($_GET['status'] == "2") {
				$sel = "selected";
			}
			else if($_GET['status'] == "0" || $_GET['status'] == "") {
				$sel1 = "selected";
			}
			else if($_GET['status'] == "1") {
				$sel2 = "selected";
			}
		} else {
			$sel1 = "selected";
		}
		
		$str_drop = "PLAYER STATUS: <select id=\"status\" name=\"status\" onchange=\"changeURL($str_mode,$str_teams); \">";
		$str_drop .= "<option value='2' $sel>All</option>";
		$str_drop .= "<option value='0' $sel1>Active</option>";
		$str_drop .= "<option value='1' $sel2>Inactive</option>";
		$str_drop .= "<\select>";
		
		
        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td width=60% bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;ACTIVE PLAYERS</td>\n";
        echo "    <td width=40% bgcolor=\"#$co\" class=\"whitemain\"  height=\"23\" align=right><form name='player' id='player' method=get><input id=teams name=teams type=hidden value=\"$str_teams\"><input name=ccl_mode id=ccl_mode type=hidden value=\"$str_mode\">$str_drop</form></td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\"  colspan=\"2\">\n";

    echo "<section width=\"100%\">\n";
	$active_status = "0";
	if (isset($_GET['status'])) {
		if ($_GET['status'] == '' OR $_GET['status'] == '0') {
			$active_status = "0";
		}
		else if ($_GET['status'] == '1'){
			$active_status = "1";
		}
		else if ($_GET['status'] == '2'){
			$active_status = "0,1";
		}
    }
    if ($db->Exists("
    SELECT
      pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub, pl.picture, pl.picture1, MAX(sg.game_date) AS lastdate
    FROM
      players pl
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    LEFT JOIN
      scorecard_batting_details sb
    ON
      pl.PlayerID = sb.player_id
    LEFT JOIN 
      scorecard_game_details sg
    ON 
      sb.game_id = sg.game_id
    WHERE
      pl.PlayerTeam = $pr AND pl.isactive in ($active_status)
    GROUP BY
      pl.PlayerID, pl.PlayerLName, pl.PlayerFName
    ORDER BY
      pl.PlayerLName    
    ")) {
    
    $db->QueryRow("
    SELECT
      pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub, pl.picture, pl.picture1, MAX(sg.game_date) AS lastdate
    FROM
      players pl
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    LEFT JOIN
      scorecard_batting_details sb
    ON
      pl.PlayerID = sb.player_id
    LEFT JOIN 
      scorecard_game_details sg
    ON 
      sb.game_id = sg.game_id
    WHERE
      pl.PlayerTeam = $pr AND pl.isactive in ($active_status)
    GROUP BY
      pl.PlayerID, pl.PlayerLName, pl.PlayerFName
    ORDER BY
      pl.PlayerLName
    ");
    
    $db->DeBagAndTag();

    

    // output story


       
		for ($s=0; $s<$db->rows; $s++) {
	        $db->GetRow($s);
	
	        $fn = $db->data['PlayerFName'];
	        $ln = $db->data['PlayerLName'];
	        $em = $db->data['PlayerEmail'];
	        $pi = $db->data['PlayerID'];
	        $pc = $db->data['picture'];
	        $pa = $db->data['picture1'];
	        $gd = $db->data['lastdate'];
	        if($pc == ''){
	        	$pc = "HeadNoMan.jpg";
	        }
	        
	        
	        
        		echo "<div style=\"display: inline-block;width: auto;height: 100px;border: 1px solid #DCDCDC;background-color: #ECECEC;min-width: 85px; margin: 1px;\" align=center valign=top><center><a  href=\"/players.php?players=$pi&ccl_mode=1\"><img alt='$ln, $fn' align=center width=50 height=67 border=1 src=\"/uploadphotos/players/$pc\"><br>$ln,<br>$fn</a></center></div>";
        	
		}
        
        } else {

        echo "    <div>No players at this time</div>\n";
        
        }

        echo "</section>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    //-------------------------------------------------
    // Alpha Box
    //-------------------------------------------------

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">ALPHABETICAL PLAYER LISTING</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"teamdetails.php?teams=$id&letter=A&ccl_mode=2&status=$active_status\">A</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=B&ccl_mode=2&status=$active_status\">B</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=C&ccl_mode=2&status=$active_status\">C</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=D&ccl_mode=2&status=$active_status\">D</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=E&ccl_mode=2&status=$active_status\">E</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=F&ccl_mode=2&status=$active_status\">F</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=G&ccl_mode=2&status=$active_status\">G</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=H&ccl_mode=2&status=$active_status\">H</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=I&ccl_mode=2&status=$active_status\">I</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=J&ccl_mode=2&status=$active_status\">J</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=K&ccl_mode=2&status=$active_status\">K</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=L&ccl_mode=2&status=$active_status\">L</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=M&ccl_mode=2&status=$active_status\">M</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=N&ccl_mode=2&status=$active_status\">N</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=O&ccl_mode=2&status=$active_status\">O</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=P&ccl_mode=2&status=$active_status\">P</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=Q&ccl_mode=2&status=$active_status\">Q</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=R&ccl_mode=2&status=$active_status\">R</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=S&ccl_mode=2&status=$active_status\">S</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=T&ccl_mode=2&status=$active_status\">T</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=U&ccl_mode=2&status=$active_status\">U</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=V&ccl_mode=2&status=$active_status\">V</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=W&ccl_mode=2&status=$active_status\">W</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=X&ccl_mode=2&status=$active_status\">X</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=Y&ccl_mode=2&status=$active_status\">Y</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=Z&ccl_mode=2&status=$active_status\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";
    

    // output link back
    $sitevar = "/teamdetails.php?teams=$pr&ccl_mode=1";
    echo "<p>&laquo; <a href=\"teamdetails.php\">back to teams listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "</td>\n";

  include("rightteams.php");
    
}

function show_alpha_listing($db,$s,$id,$pr,$letter)
{
    global $PHP_SELF;

    $db->QueryRow("SELECT * FROM teams WHERE TeamID=$pr");
    $db->BagAndTag();

    $id = $db->data['TeamID'];
    $na = $db->data['TeamName'];
    $ca = $db->data['TeamAbbrev'];
    $ur = $db->data['TeamURL'];
    $co = $db->data['TeamColour'];
	if (($co == null) || ($co == "")) {
		$co = "000000";
	}
    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <p><a href=\"/index.php\">Home</a> &raquo; <a href=\"/teams.php\">Teams</a> &raquo; Team Page</p>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$na</b><br><br>\n";


    // Team Players Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">&nbsp;PLAYERS BEGINNING WITH ' $letter '</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" class=\"tablehead\">\n";
    echo "<tr class=\"colhead\">\n";
    echo "  <td align=\"left\"><b>ID</b></td>\n";
    echo "  <td align=\"left\"><b>Player Name</b></td>\n";
    echo "  <td align=\"right\"><b>Status</b></td>\n";
    echo "</tr>\n";
    
    if ($db->Exists("SELECT pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub, pl.isactive FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' AND pl.PlayerTeam = $pr ORDER BY pl.PlayerLName")) {
    $db->QueryRow("
    SELECT
      pl.PlayerID, pl.PlayerFName, pl.PlayerLName, pl.PlayerEmail, pl.PlayerClub, pl.picture, pl.picture1, pl.isactive
    FROM
      players pl
    INNER JOIN
      teams te
    ON
      pl.PlayerTeam = te.TeamID
    WHERE
      pl.PlayerLName LIKE '{$letter}%' AND pl.PlayerTeam = $pr
    ORDER BY
      pl.PlayerLName
    ");
    $db->DeBagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        $fn = $db->data['PlayerFName'];
        $ln = $db->data['PlayerLName'];
        $em = $db->data['PlayerEmail'];
        $pi = $db->data['PlayerID'];
        $pc = $db->data['picture'];
        $pa = $db->data['picture1'];
        $ia = $db->data['isactive'];

    // output story

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td align=\"left\">$pi</td>\n";
        echo "    <td align=\"left\"><a href=\"/players.php?players=$pi&ccl_mode=1\">$ln, $fn</a>&nbsp;";
        if($pc != "") echo "<img src=\"/images/icons/icon_picture.gif\">&nbsp;";
        if($pa != "") echo "<img src=\"/images/icons/icon_picture_action.gif\">";
        echo "    </td>\n";
        
        echo "    <td align=\"right\">";
        if($ia == 1) { 
        echo "<font color=\"red\">not active</font>";
        } else {
        echo "active";
        }
        echo "    </td>\n"; 
        
        echo "  </tr>\n";

        }

        } else {

        echo "<tr class=\"trrow2\">\n";
        echo "    <td width=\"100%\">No players at this time</td>\n";
        echo "  </tr>\n";

        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // Alpha Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"#$co\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"#$co\" class=\"whitemain\" height=\"23\">ALPHABETICAL PLAYER LISTING</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    echo "<a href=\"teamdetails.php?teams=$id&letter=A&ccl_mode=2&status=$active_status\">A</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=B&ccl_mode=2&status=$active_status\">B</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=C&ccl_mode=2&status=$active_status\">C</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=D&ccl_mode=2&status=$active_status\">D</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=E&ccl_mode=2&status=$active_status\">E</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=F&ccl_mode=2&status=$active_status\">F</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=G&ccl_mode=2&status=$active_status\">G</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=H&ccl_mode=2&status=$active_status\">H</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=I&ccl_mode=2&status=$active_status\">I</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=J&ccl_mode=2&status=$active_status\">J</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=K&ccl_mode=2&status=$active_status\">K</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=L&ccl_mode=2&status=$active_status\">L</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=M&ccl_mode=2&status=$active_status\">M</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=N&ccl_mode=2&status=$active_status\">N</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=O&ccl_mode=2&status=$active_status\">O</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=P&ccl_mode=2&status=$active_status\">P</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=Q&ccl_mode=2&status=$active_status\">Q</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=R&ccl_mode=2&status=$active_status\">R</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=S&ccl_mode=2&status=$active_status\">S</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=T&ccl_mode=2&status=$active_status\">T</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=U&ccl_mode=2&status=$active_status\">U</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=V&ccl_mode=2&status=$active_status\">V</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=W&ccl_mode=2&status=$active_status\">W</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=X&ccl_mode=2&status=$active_status\">X</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=Y&ccl_mode=2&status=$active_status\">Y</a>\n";
    echo "<a href=\"teamdetails.php?teams=$id&letter=Z&ccl_mode=2&status=$active_status\">Z</a>\n";
    

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table><br>\n";
    

    // output link back
    $sitevar = "/teams.php?teams=$pr&ccl_mode=1";
    echo "<p>&laquo; <a href=\"$PHP_SELF?teams=$pr&ccl_mode=1\">back to $na main</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    
      include("rightteams.php");


}

// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

if (isset($_GET['ccl_mode'])) {
	switch($_GET['ccl_mode']) {
	case 0:
		show_teams_listing($db);
		break;
	case 1:
		show_full_teams($db,$_GET['teams']);
		break;
	case 2:
		show_alpha_listing($db,$s,$id,$teams,$letter);
		break;  
	default:
		show_teams_listing($db,$s,$id,$teams);
		break;
	}
} else {
	show_teams_listing($db);
}


?>
<script language="javascript">
function changeURL(mode, team) {
	status = document.getElementById('status').value;
	document.location.href = "teamdetails.php?teams=" + team + "&ccl_mode=" + mode + "&status=" + status;
}

</script>
