<?php


//------------------------------------------------------------------------------
// News v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


function show_today_yesterday_news($db,$date,$len=250)
{
  global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

  $today = date('Y-m-d');
  $todayname = strftime ("%B", strtotime("+0 day"));
  $todaynum = strftime ("%d", strtotime("+0 day"));
  $tomorrow = strftime ("%Y-%m-%d", strtotime("+1 day"));
  $yesterday = strftime ("%Y-%m-%d", strtotime("-1 day"));
  $tday = strftime ("%d", strtotime("+1 day"));
  $yday = strftime ("%d", strtotime("-1 day")); 
  $tname = strftime ("%B", strtotime("+1 day"));
  $yname = strftime ("%B", strtotime("-1 day"));
    
  echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
  echo " <tr>\n";
  echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font> <a href=\"index.php\">Home</a> &raquo; <font class=\"10px\">Today's Yesterdays</font>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    
    echo "<p><b class=\"16px\">All Today's Yesterdays</b><br>\n";
    echo "<a href=\"$PHP_SELF?date=$yesterday&ccl_mode=1\">$yname $yday</a> | <a href=\"$PHP_SELF?date=$tomorrow&ccl_mode=1\">$tname $tday</a><br><br>\n";
    echo "  <hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"left\">\n";

    //////////////////////////////////////////////////////////////////////////////////////////      
    // YESTERDAYS TODAYS NEWS
    //////////////////////////////////////////////////////////////////////////////////////////      

    if ($db->Exists("SELECT * FROM news WHERE DATE_FORMAT( added, '%m-%d') = DATE_FORMAT(now() , '%m-%d') AND DATE_FORMAT(added, '%Y') NOT LIKE DATE_FORMAT(now(),'%Y')")) {
    $db->Query("SELECT * FROM news WHERE DATE_FORMAT( added, '%m-%d') = DATE_FORMAT(now() , '%m-%d') AND DATE_FORMAT(added, '%Y') NOT LIKE DATE_FORMAT(now(),'%Y')");

      for ($i=0; $i<$db->rows; $i++) {
      $db->GetRow($i);
      $db->DeBagAndTag();

      // get short version of the article
      $story = "";
      if ($story != "" && strlen($story)>$len) {
        $story = substr($db->data[article],0,$len);
      while($story[strlen($story)-1] != " ") {
      $story = substr($story,0,-1);
    }
        $story = substr($story,0,-1);
      } else {
        $story = substr($db->data[article],0,$len);
      }

      // Set the variables
      $story .= "...";
      $a = $story;
      $t = $db->data[title];
      $au = $db->data[author];
      $id = $db->data[id];
      $pr = $db->data[id];
      $di = $db->data[DiscussID];
      $date = sqldate_to_string($db->data[added]);
      $vw = $db->data[views];

      if ($db->data[picture] != "") echo "<a href=\"news.php?news=$pr&ccl_mode=1\"><img width=\"80\" src=\"uploadphotos/news/" . $db->data[picture] . "\" align=\"right\" style=\"border: 1 solid #393939\"></a>\n";
      echo "  <span class=\"newstitle\">$t</span><br>\n";
      echo "  <span class=\"newsauthor\">by $au<br></span>";
      echo "  <span class=\"newsdate\">&raquo;$date (viewed $vw times)<br></span>";
      echo "  <br>$a";

      // add the 'more' or 'discuss' or subitem links
      echo "  <br><br>";

      if($di != 0) echo "  <a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss</a> | ";

      echo "  <a href=\"news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
      echo "  <hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"left\">\n\n";

      }
      } else {
      ++$cnt;
      
      echo "<p>There are no articles on $todayname $todaynum in CCL history.</p>\n"; 
      }

  // finish off
  echo "  </td>\n";
  echo "</tr>\n";
  echo "</table>\n";
            

    //////////////////////////////////////////////////////////////////////////////////////////      
    // YESTERDAYS TODAYS SCORECARDS
    //////////////////////////////////////////////////////////////////////////////////////////      
    
    $seasons = $teams = array();
    
    // output header

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    if (!$db->Exists('SELECT game_id FROM scorecard_game_details LIMIT 1')) {
        echo "<p>There are currently no scheduled games in the database.</p>\n";
        return;

    } else {

        $db->Query('SELECT * FROM seasons ORDER BY SeasonID');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
        }

        $db->Query('SELECT * FROM teams ORDER BY TeamName');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
        }

    $db->Query("
    SELECT
      s.*,
      a.TeamID AS AwayID, a.TeamName AS AwayName, a.TeamAbbrev AS AwayAbbrev,
      h.TeamID AS HomeID, h.TeamName AS HomeName, h.TeamAbbrev AS HomeAbbrev
    FROM
      scorecard_game_details s
    INNER JOIN
      teams a ON s.awayteam = a.TeamID
    INNER JOIN
      teams h ON s.hometeam = h.TeamID
    WHERE
      s.isactive=0 AND
      DATE_FORMAT( game_date, '%m-%d') = DATE_FORMAT(now() , '%m-%d') AND DATE_FORMAT(game_date, '%Y') NOT LIKE DATE_FORMAT(now(),'%Y')   
    ORDER BY
      s.week DESC, s.game_date DESC, s.game_id DESC
    ");
    
    //    DATE_FORMAT( game_date, '%m-%d') = DATE_FORMAT( DATE_SUB(now( ), INTERVAL 1 DAY) , '%m-%d') AND DATE_FORMAT(game_date, '%Y') NOT LIKE DATE_FORMAT(now(),'%Y')   


    if (!$db->rows) {

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL TODAY'S YESTERDAYS SCORECARDS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"4\" class=\"tablehead\">\n";
      echo " <tr class=\"colhead\">\n";
      echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
      echo " </tr>\n";
      echo " <tr class=\"trrow1\">\n";
      echo "  <td align=\"left\" class=\"9px\" colspan=\"3\">No Games occurred on $todayname $todaynum in CCL history.</td>\n";
      echo " </tr>\n";
      echo "</table>\n";

    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
        

    } else {

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL TODAY'S YESTERDAYS SCORECARDS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"4\" class=\"tablehead\">\n";
      echo " <tr class=\"colhead\">\n";
      echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
      echo " </tr>\n";
    
      for ($x = 0; $x < $db->rows; $x++) {
      $db->GetRow($x);

      // Setup the variables
      $t1 = $db->data[HomeAbbrev];
      $t2 = $db->data[AwayAbbrev];
      $um = $db->data[UmpireAbbrev];
      $t1id = $db->data[HomeID];
      $t2id = $db->data[AwayID];
      $umid = $db->data[UmpireID];
      $d = sqldate_to_string($db->data[game_date]);
      $sc =  $db->data[scorecard];
      $re = $db->data[result];
      $id = $db->data[game_id];
      $wk = $db->data[week];
      $fo = $db->data[forfeit];
      $ca = $db->data[cancelled];
                

      echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';

      echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
      echo "  <td align=\"left\" class=\"9px\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\" class=\"statistics\">$t2</a> at <a href=\"/teams.php?teams=$t1id&ccl_mode=1\" class=\"statistics\">$t1</a></td>\n";

      if($fo == "0" && $ca == "0") {
        echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
      } else if($fo == "1" && $ca == "0") {
        echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
      } else if($ca == "1" && $fo = "1") {
        echo "  <td align=\"left\" class=\"9px\">Game cancelled</td>\n";
      } else {
        echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
      }

      echo " </tr>\n";
      }
      echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}
}

  // finish off

  echo "  </td>\n";
  echo "</tr>\n";
  echo "</table>\n";
}








function show_other_yesterday_news($db,$date,$len=250)
{
  global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

  $today = $date;   
  
  $yesterday = strftime('%Y-%m-%d', strtotime('-1 day',strtotime($_GET['date'])));
  $tomorrow  = strftime('%Y-%m-%d', strtotime('+1 day',strtotime($_GET['date'])));
  
  $tday = strftime ('%d', strtotime('+1 day',strtotime($_GET['date'])));
  $yday = strftime ('%d', strtotime('-1 day',strtotime($_GET['date'])));    
  $tmon = strftime ('%m', strtotime('+1 day',strtotime($_GET['date'])));
  $ymon = strftime ('%m', strtotime('-1 day',strtotime($_GET['date'])));      
  $tyea = strftime ('%Y', strtotime('+1 day',strtotime($_GET['date'])));
  $yyea = strftime ('%Y', strtotime('-1 day',strtotime($_GET['date'])));    
  $tname = strftime ('%B', strtotime('+1 day',strtotime($_GET['date'])));
  $yname = strftime ('%B', strtotime('-1 day',strtotime($_GET['date'])));
  
  $todayname = strftime ("%B", strtotime("+0 day",strtotime($_GET['date'])));
  $todaynum = strftime ("%d", strtotime("+0 day",strtotime($_GET['date'])));

  
  echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
  echo " <tr>\n";
  echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo " <tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <font class=\"10px\">You are here: </font> <a href=\"index.php\">Home</a> &raquo; <a href=\"yesterday.php\">Today's Yesterdays</a> &raquo; <font class=\"10px\">$today</font>\n";
    echo "  </td>\n";
    //echo "  <td align=\"right\" valign=\"top\">\n";
    //require ("navtop.php");
    //echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
    
    echo "<p><b class=\"16px\">All Today's Yesterdays</b><br>\n";
    echo "<a href=\"$PHP_SELF?date=$yesterday&ccl_mode=1\">$yname $yday</a> | <a href=\"$PHP_SELF?date=$tomorrow&ccl_mode=1\">$tname $tday</a><br><br>\n";
    echo "  <hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"left\">\n\n";
    //////////////////////////////////////////////////////////////////////////////////////////      
    // YESTERDAYS TODAYS NEWS
    //////////////////////////////////////////////////////////////////////////////////////////      

    if ($db->Exists("SELECT * FROM news WHERE DATE_FORMAT( added, '%m-%d') = DATE_FORMAT('$date' , '%m-%d') AND DATE_FORMAT(added, '%Y') NOT LIKE DATE_FORMAT('$date','%Y')")) {
    $db->Query("SELECT * FROM news WHERE DATE_FORMAT( added, '%m-%d') = DATE_FORMAT('$date' , '%m-%d') AND DATE_FORMAT(added, '%Y') NOT LIKE DATE_FORMAT('$date','%Y')");

      for ($i=0; $i<$db->rows; $i++) {
      $db->GetRow($i);
      $db->DeBagAndTag();

      // get short version of the article
      $story = "";
      if ($story != "" && strlen($story)>$len) {
        $story = substr($db->data[article],0,$len);
      while($story[strlen($story)-1] != " ") {
      $story = substr($story,0,-1);
    }
        $story = substr($story,0,-1);
      } else {
        $story = substr($db->data[article],0,$len);
      }

      // Set the variables
      $story .= "...";
      $a = $story;
      $t = $db->data[title];
      $au = $db->data[author];
      $id = $db->data[id];
      $pr = $db->data[id];
      $di = $db->data[DiscussID];
      $date = sqldate_to_string($db->data[added]);
      $vw = $db->data[views];


      if ($db->data[picture] != "") echo "<a href=\"news.php?news=$pr&ccl_mode=1\"><img width=\"80\" src=\"uploadphotos/news/" . $db->data[picture] . "\" align=\"right\" style=\"border: 1 solid #393939\"></a>\n";
      echo "  <span class=\"newstitle\">$t</span><br>\n";
      echo "  <span class=\"newsauthor\">by $au<br></span>";
      echo "  <span class=\"newsdate\">&raquo;$date (viewed $vw times)<br></span>";
      echo "  <br>$a";

      // add the 'more' or 'discuss' or subitem links
      echo "  <br><br>";

      if($di != 0) echo "  <a href=\"http://www.coloradocricket.org/board/viewtopic.php?t=$di\" target=\"_new\">Discuss</a> | ";

      echo "  <a href=\"news.php?news=$pr&ccl_mode=1\">Full Story &raquo;</a>";
      echo "  <hr color=\"#FCDC00\" width=\"100%\" size=\"1\" align=\"left\">\n\n";

      }
      } else {
      ++$cnt;
      echo "<p>There are no articles on $todayname $todaynum in CCL history.</p>\n"; 
      }

  // finish off
  echo "  </td>\n";
  echo "</tr>\n";
  echo "</table>\n";
            

    //////////////////////////////////////////////////////////////////////////////////////////      
    // YESTERDAYS TODAYS SCORECARDS
    //////////////////////////////////////////////////////////////////////////////////////////      
    
    $seasons = $teams = array();
    
    // output header

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    if (!$db->Exists('SELECT game_id FROM scorecard_game_details LIMIT 1')) {
        echo "<p>There are currently no scheduled games in the database.</p>\n";
        return;

    } else {

        $db->Query('SELECT * FROM seasons ORDER BY SeasonID');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $seasons[$db->data['SeasonID']] = $db->data['SeasonName'];
        }

        $db->Query('SELECT * FROM teams ORDER BY TeamName');
        for ($i = 0; $i < $db->rows; $i++) {
            $db->GetRow($i);
            $teams[$db->data['TeamID']] = $db->data['TeamAbbrev'];
        }

    $db->Query("
    SELECT
      s.*,
      a.TeamID AS AwayID, a.TeamName AS AwayName, a.TeamAbbrev AS AwayAbbrev,
      h.TeamID AS HomeID, h.TeamName AS HomeName, h.TeamAbbrev AS HomeAbbrev
    FROM
      scorecard_game_details s
    INNER JOIN
      teams a ON s.awayteam = a.TeamID
    INNER JOIN
      teams h ON s.hometeam = h.TeamID
    WHERE
      s.isactive=0 AND
      DATE_FORMAT( game_date, '%m-%d') = DATE_FORMAT('$date' , '%m-%d') AND DATE_FORMAT(game_date, '%Y') NOT LIKE DATE_FORMAT('$date','%Y')   
    ORDER BY
      s.week DESC, s.game_date DESC, s.game_id DESC
    ");
    
    if (!$db->rows) {

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL TODAY'S YESTERDAYS SCORECARDS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"4\" class=\"tablehead\">\n";
      echo " <tr class=\"colhead\">\n";
      echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
      echo " </tr>\n";
      echo " <tr class=\"trrow1\">\n";
      echo "  <td align=\"left\" class=\"9px\" colspan=\"3\">No Games occurred today in history.</td>\n";
      echo " </tr>\n";
      echo "</table>\n";

    echo "  </td>\n";
    echo " </tr>\n";
    echo "</table>\n";
        

    } else {

    echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    echo "  <tr>\n";
    echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;ALL TODAY'S YESTERDAYS SCORECARDS</td>\n";
    echo "  </tr>\n";
    echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

      echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"4\" class=\"tablehead\">\n";
      echo " <tr class=\"colhead\">\n";
      echo "  <td align=\"left\" class=\"9px\"><b>DATE</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>GAME</b></td>\n";
      echo "  <td align=\"left\" class=\"9px\"><b>RESULT</b></td>\n";
      echo " </tr>\n";
    
      for ($x = 0; $x < $db->rows; $x++) {
      $db->GetRow($x);

      // Setup the variables
      $t1 = $db->data[HomeAbbrev];
      $t2 = $db->data[AwayAbbrev];
      $um = $db->data[UmpireAbbrev];
      $t1id = $db->data[HomeID];
      $t2id = $db->data[AwayID];
      $umid = $db->data[UmpireID];
      $d = sqldate_to_string($db->data[game_date]);
      $sc =  $db->data[scorecard];
      $re = $db->data[result];
      $id = $db->data[game_id];
      $wk = $db->data[week];
      $fo = $db->data[forfeit];
      $ca = $db->data[cancelled];
                

      echo ' <tr class="trrow', ($x % 2 ? '2' : '1'), '">';

      echo "  <td align=\"left\" class=\"9px\">$d</td>\n";
      echo "  <td align=\"left\" class=\"9px\"><a href=\"/teams.php?teams=$t2id&ccl_mode=1\" class=\"statistics\">$t2</a> at <a href=\"/teams.php?teams=$t1id&ccl_mode=1\" class=\"statistics\">$t1</a></td>\n";

      if($fo == "0" && $ca == "0") {
        echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
      } else if($fo == "1" && $ca == "0") {
        echo "  <td align=\"left\" class=\"9px\">Forfeit</td>\n";
      } else if($ca == "1" && $fo = "1") {
        echo "  <td align=\"left\" class=\"9px\">Game cancelled</td>\n";
      } else {
        echo "  <td align=\"left\" class=\"9px\"><a href=\"scorecardfull.php?game_id=$id&ccl_mode=4\">$re</a></td>\n";
      }

      echo " </tr>\n";
      }
      echo "</table>\n";


    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";
}
}

  // finish off

  echo "  </td>\n";
  echo "</tr>\n";
  echo "</table>\n";
}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg[db]);

switch($ccl_mode) {
case 0:
    show_today_yesterday_news($db,$date);
    break;
case 1:
    show_other_yesterday_news($db,$date);
    break;
default:
    show_today_yesterday_news($db,$date);
    break;
}

?>