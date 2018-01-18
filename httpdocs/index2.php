<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = index;

?>

<html>
<head>
<title>Colorado Cricket League</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="/includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("includes/header.php"); ?>
<?php include("includes/links.php"); ?>
    </td>
    <td width="520" bgcolor="#FFFFFF" valign="top">

    <?php include("includes/shownews.php"); ?>

    
    <?php include("includes/showlatestnews.php"); ?>
    <!--<?php include("includes/showcougarsnews.php"); ?>-->
    <?php include("includes/showarticles.php"); ?>
    <?php include("includes/showcricinfo.php"); ?>


    <p align="center" class="8px"><?php include("includes/visits.inc"); ?> hits.</p>
    <?php include("includes/showrandomsponsor.php"); ?>



    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>
