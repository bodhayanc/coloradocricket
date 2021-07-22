<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = chat;

?>

<html>
<head>
<title>Colorado Cricket League - Chat Room</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="/includes/css/cricket.css" type="text/css">
<link rel="stylesheet" type="text/css" href="/includes/javascript/popup_calendar.css" media="screen">
<script language="JavaScript" src="/includes/javascript/openwindow.js"></script>
<script language="JavaScript" src="/includes/javascript/picker.js"></script>
<script language="JavaScript" src="/includes/javascript/richtext.js"></script>
<script type="text/javascript" src="/includes/javascript/oodomimagerollover.js"></script>
<script type="text/javascript" src="/includes/javascript/popup_calendar.js"></script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("includes/header.php"); ?>
<?php include("includes/links.php"); ?>
    </td>
    <td width="520" bgcolor="#FFFFFF" valign="top">

    <table width="100%" cellpadding="10" cellspacing="0" border="0">
      <tr>
        <td align="left" valign="top">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
      <a href="/index.php">Home</a> &raquo; <font class="10px">Chat</font></p>
      </td>
      <td align="right" valign="top">
    <?php require ("includes/navtop.php"); ?>
      </td>
    </tr>
    </table>

    <b class="16px">Colorado Cricket Chat</b><br><br>

    <table width="100%" border="1" cellspacing="0" cellpadding="3" bordercolor="$bluebdr" align="center">
      <tr>
        <td bgcolor="$bluebdr" class="whitemain" height="23">CHAT</td>
      </tr>
      <tr>
      <td class="trrow1" valign="top" bordercolor="#FFFFFF" class="main">

    <?php include("includes/showrandomsponsor.php");?>

    <!-- Begin Basic ParaChat v6.0 Code -->
    <iframe src='http://chat.parachat.com/chat/login.html?room=Colorado_Cricket&width=450&height=400&bg=FFFFFF' framespacing='0' frameborder='no' scrolling='no' width='450' height='400'>
    <p>You do not have iframes enabled. <a href="http://direct.parachat.com/iframe.html">More Info</a> </p></iframe>
    <!-- End Basic ParaChat v6.0 Code -->

<br>
    <?php include("includes/showrandomsponsor_bottom.php");?>
        </td>
      </tr>
    </table><br>
        </td>
      </tr>
    </table><br>    
    


    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>
