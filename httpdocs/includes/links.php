<?php
  require 'themes.php';

    echo "<table width=\"180\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"$LEFT_AND_TOP_BK_MAIN_COLOR\">\n";
    echo "<tr>\n";
    echo "<td><img src=\"/images/nav-top.gif\" width=\"180\" height=\"20\"></td>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td>\n";

    echo " <table width=\"180\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" align=\"right\">\n";

    function show_grey_bars($page, $db) {

		$db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Premier%' ORDER BY SeasonID DESC LIMIT 1");
		$db->BagAndTag();

		$sid = $db->data['SeasonID'];
		$snm = $db->data['SeasonName'];
		$yr = preg_split("/[\s,]+/", $snm)[0];
      // Home Page

      // This is an associated array (hash for perl lovers!)
      // This defines all the links on the left side of the main page,
      // if links[0] is "" then that would mean we are dealing with a
      // group of links like NEWS and DOCUMENTS etc.
      // A new entry would be required should we add a new link or
      // a new group of links is added.
      //                  Jarrar; 7-23-2004
      
      // include("Logging.inc");

      $links=array(
	  "index" => array("index.php","Home"),
	  //"shop" => array("shop","Shop"),
	  "sponsors" => array("sponsors.php","Sponsors"),
//	  "cal" => array("calendar","Calendar"),
      "Calendar" => array("calendar.php","Calendar"),

	  "ScheduleAndResults" => array("","SCHEDULE & RESULTS"),
	  "schedule" => array("schedule.php?schedule=$sid&ccl_mode=1","League Schedule"),
	  //"submit-scorecard" => array("submit","Submit a Scorecard"),
	  //"chaukapremierscorecard" => array("cclpremierchaukaiframe.php","Chauka Premier Scorecard"),
	  //"chaukat20scorecard" => array("CCL_T20_chaukaiframe.php","Chauka T20 Scorecard"),
	  "scorecards" => array("scorecard.php?schedule=$sid&ccl_mode=1","League Scorecards"),
//20140315           "crichq-scorecards" => array("/www.crichq.com/plugins/comp_mgmt/organisations/53?width=598&height=400&border=1","CricHQ Scorecards"),
	  "ladder" => array("ladder.php?ladder=$sid&ccl_mode=1","League Standings"),
	  "statistics" => array("statistics.php?statistics=$yr&ccl_mode=1","League Stats"),              
	  "cricclubspremierscorecard" => array("/www.cricclubs.com/ColoradoCricket/listMatches.do?league=73&clubId=5135","CricClubs Premier Scorecard"),
	  "cricclubst20scorecard" => array("/www.cricclubs.com/ColoradoCricket/listMatches.do?league=68&clubId=5135","CricClubs T20 Scorecard"),
	  "cricclubspracticescorecard" => array("/www.cricclubs.com/ColoradoCricket/listMatches.do?league=67&clubId=5135","CricClubs Practice Scorecard"),

	  "NewsAndDocuments" => array("","NEWS & DOCUMENTS"),
	  "news" => array("news.php","News Archives"),
	  "articles" => array("articles.php","Feature Articles"),
	  "yesterday" => array("yesterday.php","Todays Yesterdays"),
	  "documents" => array("documents.php","CCL Documents"),
      "videos" => array("/youtube.com/results?search_query=colorado+cricket","Video Highlights"),
	  "history" => array("history.php","History"),
	  
	  
	  "clubsAndGronds" => array("","CLUBS & GROUNDS"),
	  "clubs" => array("clubs.php","Clubs"),
	  "teams" => array("teams.php","Teams"),
	  "grounds" => array("grounds.php","Grounds"),
	  
	  "PlayersAndOfficers" => array("","PLAYERS & OFFICERS"),
	  "players" => array("players.php","Players"),
	  "usacricketmembers" => array("usacricketmembers.php","USA Cricket Members"),
//	  "featuredplayers" => array("featuredmember.php?season=57&sename=2014&ccl_mode=2","Featured Players"),
	  "featuredplayers" => array("featuredmember.php?season=$sid&sename=$snm&ccl_mode=2","Player Of The Week"),
	  "awardplayers" => array("awards.php?season=$sid&sename=$snm&ccl_mode=2","Player Awards"),
	  // Moved Champions from Archives to here 24-Oct-2014 10:24pm
	  "champions" => array("champions.php","Champions"),
	  "cclofficers" => array("cclofficers.php","CCL Officers"),

	  // Umpiring Link, the 1st one is the Main Link Category
	  // others are actionable links.
	  // 				Jarrar 2-10-2009
	  "Umpiring" => array("","UMPIRING"),
	     "umpires" => array("umpires.php","Certified Umpires"),
              "quiz" => array("quiz/CCL_Umpiring.php","Umpiring Quiz"),
              //"grades" => array("umpire_ratings.php","Umpires Grades"),

// 24-Oct-2014 10:22pm	      "Archives" => array("","ARCHIVES"),
// 24-Oct-2014 10:22pm              "arscorecards" => array("scorecard.php","Scorecards"),    
// 24-Oct-2014 10:22pm              "champions" => array("champions.php","Champions"),    

              "CricketCommunity" => array("","CRICKET COMMUNITY"),
			  // 24-Oct-2014 10:29pm Renamed CCL Cougars to Team Colorado
			   "cclcougars" => array("cougars","Team Colorado"),
// 24-Oct-2014 10:29pm              "cclcougars" => array("/cougars.coloradocricket.org","CCL Cougars"),
// 20141015 - Its going to some porn site              "cclcougars-camp" => array("/www.c3tc.info/","C3 Training Camp"),
              "ccltennis" => array("/tennis.coloradocricket.org","Tennis Cricket"),
//  20140314            "messageboard" => array("/forums.coloradocricket.org","CCL Discussion Forum"),
//  20140314            "chat" => array("chat.php","League Chat Room"),
              //"classifieds" => array("board/adverts.php","League Classifieds"),

              "leagues" => array("","REGIONAL LEAGUES"),
              "ctcl" => array("/www.centraltxcricket.org/","CTCL (Austin)"),
              "hcl" => array("/www.houstoncricket.org/","HCL (Houston)"),
              "ntca" => array("/www.ntcricket.com/","NTCA (Dallas)"),
              "hlcl" => array("/www.heartlandcricketleague.com/","Heartland(Nebraska)"),
              "usaca" => array("/www.usacricket.org/","USA Cricket"),
              "icc" => array("/www.icc-cricket.yahoo.net/","ICC"),
              "mcc" => array("/www.lords.org/mcc/laws-of-cricket/","MCC Cricket Laws"),
          );


	  require 'themes.php';
          while (list($key, $value) = each ($links)) {



              if ($key == $page ) {
              echo "<tr>\n";
                echo "  <td bgcolor=\"#cccccc\" height=\"20\">\n";
              } else {
                // If I dont check for "" here then it adds an annoying
                // blank <td>. Jarrar (7-23-2004)
                if ($value[0] !="") echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              } /* if key == page */

              if ($value[0]=="" or $value[0]=="index") {
                echo "<tr>\n";
                //echo "  <td bgcolor=\"#9E3228\" height=\"20\" class=\"whitemain\">\n";
                echo "  <td bgcolor=\"$LEFT_SECTION_BK_COLOR\" height=\"20\" class=\"whitemain\">\n";
                echo "  &nbsp;$value[1]\n";
                echo "  </td>\n";
                echo "</tr>\n";
              } else if(strpos($value[0], "cricclubs") !== false) {
                echo "  &nbsp;- <a href=\"/$value[0]\" class=\"menu\" target=\"_blank\">$value[1]</a>\n";
                echo "  </td>\n";
                echo "</tr>\n";
              } else {
                echo "  &nbsp;- <a href=\"/$value[0]\" class=\"menu\">$value[1]</a>\n";
                echo "  </td>\n";
                echo "</tr>\n";
              }
          } // while
          } // function
		$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
		$db->SelectDB($dbcfg['db']);

		if (isset($page)) show_grey_bars($page, $db);
        
        echo "</table>\n";
        echo "  </td>\n";
        echo "</tr>\n";
        echo "<tr>\n";
        echo "   <td><img src=\"/images/nav-base.gif\" width=\"180\" height=\"20\"></td>\n";

        echo "</tr>\n";
?>
       <tr>
        <td align="center"><a href="/articles.php?ccl_mode=5"><img src="http://www.coloradocricket.org/images/submitnews.gif" border="0"></a></td>
        </tr>  
  <?php    
      echo "<tr>\n";

      echo "     <td><p>&nbsp;</p><p>&nbsp;</p></td>\n";
  
      echo "</tr>\n";
      echo "</table>\n";
      echo "<br>\n";

?>
