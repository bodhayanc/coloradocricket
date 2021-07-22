<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = index;

?>

<html>
<head>
<meta name="google-site-verification" content="SEu_gd4Ib0oNIsfk_b5XZdUbVFiJvQGAjNBHsF_yKUs" />
<title>COLORADO    CRICKET    LEAGUE</title>
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

<br>

<?php 
      include("includes/showrandomsponsor.php");
?>

    <?php include("includes/shownews.php"); ?>

    <?php include("includes/showvideo.php"); ?>

    <?php 
    srand((double)microtime()*1000000); 
    $num = rand(1,2);
    include ('includes/bits/showbit'.$num.'.php');
    ?>

    <?php include("includes/showtennisnews.php"); ?>
    <?php include("includes/showcougarsnews.php"); ?>
    <?php include("includes/showarticles.php"); ?>
    <?php include("includes/showcrichq.php"); ?>
    <?php include("includes/showrandomsponsor_bottom.php");?>
    <p align="center" class="8px"><?php include("includes/visits.inc"); ?> hits.</p>
    
    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>
 </tr>

<?php include("includes/footer.php"); ?>

</table>
</body>
</html>
