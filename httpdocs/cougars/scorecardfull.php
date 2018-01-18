<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = scorecardfull;
?>

<html>
<head>
<title>Colorado Cricket League - Scorecards</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="http://www.coloradocricket.org/includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("includes/header.php"); ?>
<?php include("includes/links.php"); ?>

    </td>
    <td width="600" bgcolor="#FFFFFF" valign="top">

    <?php include("includes/showscorecard.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>
