<?php
    require ("../includes/general.config.inc");
    require ("../includes/class.mysql.inc");
    require ("../includes/class.fasttemplate.inc");
    require ("../includes/general.functions.inc");

    $page = index;

?>

<html>
<head>
<title>Colorado Cricket League</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="http://www.coloradocricket.org/includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("includes/header.php"); ?>
<?php include("includes/links.php"); ?>
    </td>
    <td width="520" bgcolor="#FFFFFF" valign="top">

<br><center><a href="http://cougars.coloradocricket.org/" ><img src="http://www.coloradocricket.org/images/bannaz/ccl-cougars.jpg" border="0"></a></center><?php //include("includes/showrandomsponsor.php"); ?>

    <?php include("includes/shownews.php"); ?>

      <table width="97%" border="0" cellspacing="0" cellpadding="2" align="center">
        <tr>
            <td colspan="2" align="left">
                <a href="/news.php">more news articles &raquo;</a>
            </td>
        </tr>

      </table>

    <p>

    <?php include("includes/showlatestnews.php"); ?>
    </p><!--
    

    <p align="center"><a href="http://www.druparel.metlife.com/" target="_new">
    <img src="http://www.coloradocricket.org/images/bannaz/metlife-2.gif" border="0"></a></p><br>

    --></td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>
</body>
</html>