<?php
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);
$db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Premier%' ORDER BY SeasonID DESC LIMIT 1");
$db->BagAndTag();

$sid = $db->data['SeasonID'];
$snm = $db->data['SeasonName'];
?>
  <tr bgcolor="000033" valign="middle">
    <td colspan="3" height="20">
      <div align="center">

	  <span class="whitemain">[<span class="footer"><a href="/index.php" class="footer">HOME</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/news.php" class="footer">NEWS</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/schedule.php?schedule=<?=$sid?>&ccl_mode=1" class="footer">SCHEDULE</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/scorecard.php?schedule=<?=$sid?>&ccl_mode=1" class="footer">SCORECARDS</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/clubs.php" class="footer">CLUBS</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/grounds.php" class="footer">GROUNDS</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/players.php" class="footer">PLAYERS</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/history.php" class="footer">HISTORY</a></span>] - </span>
	  <span class="whitemain">[<span class="footer"><a href="/cclofficers.php" class="footer" target="_new">CONTACT US</a></span>]</span>
		</div>
    </td>
  </tr>
  
  <tr bgcolor="#E0D9D9">
    <td colspan="3" class="main" height="20">
      <div align="right">
	  <a href="#top">back to top</a>
	  <br>
	  Copyright &copy; Michael Doig. Licensed to Colorado Cricket League (CCL) 2004.<br>
	  Designed by <a href="mailto: kervyn@yahoo.com">Michael Doig</a>.
	  Maintained by <a href="http://coloradocricket.org/players.php?players=117&ccl_mode=1">Kervyn Dimney</a> and <a href="http://coloradocricket.org/players.php?players=1367&ccl_mode=1">Bodhayan Chakraborty</a>.
	  </div>
    </td>
  </tr>
