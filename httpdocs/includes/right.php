<!-- Show Mini Points Table -->
<?php
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);
$db->QueryRow("SELECT * FROM seasons WHERE SeasonName LIKE '%Premier%' ORDER BY SeasonID DESC LIMIT 1");
$db->BagAndTag();

$sid = $db->data['SeasonID'];
$snm = $db->data['SeasonName'];
$yr = preg_split("/[\s,]+/", $snm)[0];
?>
<table width="95%" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td width="50%" bgcolor="<?=$yellowbdr?>" class="whitemain" height="23">&nbsp;<?=$yr?> Premier League</td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" bgcolor="<?=$yellowbdr?>" class="whitemain" height="23">&nbsp;<?=$yr?> Twenty20</td>
 </tr>
 <tr>
  <td width="50%" valign="top">
   <?php include("showminiladder.php"); ?>
  </td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" valign="top">
   <?php include("showminiladdertwenty.php"); ?>
  </td>
 </tr>
</table>

<br>

<!-- Show Mini Schedule -->

<table width="95%" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td width="50%" bgcolor="<?=$greenbdr?>" class="whitemain" height="23">&nbsp;NEXT GAMES</td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" bgcolor="<?=$greenbdr?>" class="whitemain" height="23">&nbsp;LATEST RESULTS</td>
 </tr>
 <tr>
  <td width="50%" valign="top" class="main">
   <?php include("showminischedule.php"); ?>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" valign="top" class="main">
   <?php include("showminiscorecard.php"); ?>
  </td>
  </td>
 </tr>
</table>

<br>


<table width="95%" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td width="50%" bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;PLAYER OF THE WEEK</td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td rowspan="2" width="50%" valign="top" class="main">
<!-- Show Twitter Feed -->
	<?php include("showtwitterfeed.php"); ?>
  </td>
 </tr>
 <tr>
<!-- Show Featured Player -->
  <td width="50%" valign="top" class="main">
   <?php include("showminifeaturedmember.php"); ?>
  </td>
</table>

<br>

<!-- Show Mini Forum -->
<!--
<table width="95%" border="1" cellspacing="0" cellpadding="0" bordercolor="<?=$redbdr?>" align="center">
 <tr>
  <td bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;IN THE FORUM</td>
 </tr>
 <tr>
  <td bgcolor="#FFFFFF" valign="top" bordercolor="#FFFFFF" class="main">
    <?php include("showminibb.php"); ?>
    <center>
    <a href="/smf">
    <img src="/images/smf-babylonking-mirco-2.gif" alt="Simple Machines Forum" border="0" >
    </a>
    </center>
  </td>
 </tr>
</table>

<br>
-->

<table width="95%" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td width="50%" bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;UPCOMING EVENTS</td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;ITEMS FOR SALE</td>
 </tr>
 <tr>
<!-- Show Events Calendar --> 
  <td width="50%" bgcolor="#FFFFFF" valign="top" bordercolor="#FFFFFF" class="main">
   <?php include("showminicalendar.php"); ?>
  </td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
<!-- Show Classified Ads -->
  <td width="50%" bgcolor="#FFFFFF" valign="top" bordercolor="#FFFFFF" class="main">
   <?php include("showminiads.php"); ?>
  </td>
 </tr>
</table>

<!--  
<table width="95%" border="0" cellspacing="0" cellpadding="0"  align="center">
 <tr>
  <td align="center">
   <script type="text/javascript">
   
	google_ad_client = "pub-3830536346386264";	/* 180x150, created 12/9/09 */
	google_ad_slot = "7871625547";
	google_ad_width = 180;
	google_ad_height = 150;

	</script>
	<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
  
  </td>
 </tr>
</table>
-->
<br>
