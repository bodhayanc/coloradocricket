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
  <td width="50%" bgcolor="<?=$yellowbdr?>" class="whitemain" height="23">&nbsp;<?=$yr?> Twenty20 Round 1</td>
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
  <td rowspan="4" width="50%" valign="top" class="main">
   <?php include("showminiscorecard.php"); ?>
   <br>
   <!-- Show Twitter Feed -->
  <?php include("showtwitterfeed.php"); ?>
  </td>
  </td>
 </tr>
 <tr>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
 </tr>
 <tr>
  <td width="50%" bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;PLAYER OF THE WEEK</td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" valign="top" class="main">
  </td>
 </tr>
  <tr>
<!-- Show Featured Player -->
  <td width="50%" valign="top" class="main">
   <?php include("showminifeaturedmember.php"); ?>
  </td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td rowspan="2" width="50%" valign="top" class="main">
  </td>
 </tr>
</table>

<br>


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
