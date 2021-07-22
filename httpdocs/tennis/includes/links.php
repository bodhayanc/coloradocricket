    <table width="150" border="0" cellspacing="0" cellpadding="0" bgcolor="#030979">
        <tr>
          <td><img src="http://www.coloradocricket.org/images/nav-top.gif" width="150" height="20"></td>
        </tr>
        <tr>
          <td>

        <table width="140" border="0" cellspacing="1" cellpadding="0" align="right">

        <?php
        function show_grey_bars($page) {

          // Home Page

          // This is an associated array (hash for perl lovers!)
          // This defines all the links on the left side of the main page,
          // if links[0] is "" then that would mean we are dealing with a
          // group of links like NEWS and DOCUMENTS etc.
          // A new entery would be required should we add a new link or
          // a new group of links is addedd.
          //                  Jarrar; 7-23-2004
          $links=array(
              "index" => array("index.php","Home"),
			  "ctclofficers" => array("ctclofficers.php","CTCL Officers"),
              "newsAndDocs" => array("","News & Articles"),
              "news" => array("news.php","News"),
              "documents" => array("documents.php","Rules & Documents"),
              "schdAndResults" => array("","Schedule & Results"),
              "schedule" => array("schedule.php?schedule=71&ccl_mode=1","Schedule"),
              "submitscorecard" => array("http://tennis.coloradocricket.org/submit","Submit Scorecard"),
              "scorecard" => array("scorecard.php?schedule=71&ccl_mode=1","Scorecards"),
              "chaukascorecard" => array("chaukaiframe.php","Chauka scorecard"),
              "statistics" => array("statistics.php?statistics=2017&ccl_mode=1","Statistics"),
              "ladder" => array("ladder.php?ladder=71&ccl_mode=1","Standings"),
              "clubsAndGrnds" => array("","Teams & Grounds"),
              "players" => array("players.php","Players"),
              "teams" => array("teams.php","Teams"),
	          "grounds" => array("grounds.php","Grounds"),
              "cricketCommunity" => array("","Cricket Community"),
              "messageboard" => array("http://forums.coloradocricket.org","CCL Discussion Forum"),
              "cclcougars" => array("http://cougars.coloradocricket.org","CCL Cougars"),
              "cclindex" => array("http://www.coloradocricket.org","CCL Home"),
          );

          while (list($key, $value) = each ($links)) {



              if ($key == $page ) {
              echo "<tr>\n";
                echo "  <td bgcolor=\"#dddddd\" height=\"20\">\n";
              } else {
                // If I dont check for "" here then it adds an annoying
                // blank <td> and that suxxxxx . Jarrar (7-23-2004)
                if ($value[0] !="") echo "  <td bgcolor=\"#f5f5f5\" height=\"20\">\n";
              } /* if key == page */

              if ($value[0]=="" or $value[0]=="index") {
                echo "<tr>\n";
                echo "  <td bgcolor=\"#DE9C06\" height=\"20\" class=\"whitemain\">\n";
                echo "  &nbsp;$value[1]\n";
                echo "  </td>\n";
                echo "</tr>\n";
              } else {
                echo "  &nbsp;- <a href=\"$value[0]\" class=\"menu\">$value[1]</a>\n";
                echo "  </td>\n";
                echo "</tr>\n";
              }
          } // while
          } // function
        show_grey_bars($page);
    ?>

          </table>

          </td>
        </tr>
        <tr>
          <td><img src="http://www.coloradocricket.org/images/nav-base.gif" width="150" height="20"></td>
        </tr>

        <tr>
          <td align="center">
         &nbsp;
        </td>
 	 </tr>
      </table>
      <br>
