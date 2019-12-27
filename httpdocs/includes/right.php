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
<table width="98%" cellspacing="0" cellpadding="0" align="center">
 <tr>
  <td width="50%" valign="top">
   <table cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;PLAYER OF THE WEEK</td>
    </tr>
    <tr>
     <td valign="top" class="main">
      <?php include("showminifeaturedmember.php"); ?>
     </td>
    </tr>
	<tr>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td bgcolor="<?=$greenbdr?>" class="whitemain" height="23">&nbsp;NEXT GAMES</td>
	</tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showminischedule.php"); ?>
     </td>
    </tr>
	<tr>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td bgcolor="<?=$greenbdr?>" class="whitemain" height="23">&nbsp;LATEST RESULTS</td>
	</tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showminiscorecard.php"); ?>
     </td>
    </tr>
	<tr>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td bgcolor="<?=$greenbdr?>" class="whitemain" height="23">&nbsp;UPCOMING EVENTS</td>
	</tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showminicalendar.php"); ?>
     </td>
    </tr>
  </table>
  </td>
  <td bgcolor="#D0C7C0" width="2px" height="23">&nbsp;</td>
  <td width="50%" valign="top">
   <table cellspacing="0" cellpadding="0" align="center">
    <tr>
     <td bgcolor="<?=$yellowbdr?>" class="whitemain" height="23">&nbsp;<?=$yr?> Twenty20</td>
    </tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showminiladdertwenty.php"); ?>
     </td>
    </tr>
	<tr>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td bgcolor="<?=$yellowbdr?>" class="whitemain" height="23">&nbsp;<?=$yr?> Premier League</td>
    </tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showminiladder.php"); ?>
     </td>
    </tr>
	<tr>
     <td>&nbsp;</td>
    </tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showtwitterfeed.php"); ?>
     </td>
    </tr>
	<tr>
     <td>&nbsp;</td>
    </tr>
    <tr>
     <td bgcolor="<?=$redbdr?>" class="whitemain" height="23">&nbsp;ITEMS FOR SALE</td>
    </tr>
	<tr>
     <td valign="top" class="main">
      <?php include("showminiads.php"); ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
