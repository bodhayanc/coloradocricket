<?php
    require ("../includes/general.config.inc");
    require ("../includes/class.mysql.inc");
    require ("../includes/class.fasttemplate.inc");
    require ("../includes/general.functions.inc");

    $page = chaukascorecard;
?>

<html>
<head>
<title>Colorado Cricket League - Scorecards</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="http://coloradocricket.org/includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("includes/header.php"); ?>
<?php include("includes/links.php"); ?>

    </td>
    <td width="520" bgcolor="#FFFFFF" valign="top">

	<iframe id="schedule_1337" src="http://chauka.in/index.php/embed/tournaments/schedule/1337/?embed_code=3e633cd63d2b402146a4703a0dadf37e9de76c3fd7b811f5bfcbc40510e26b432361b969e17ffe7e4e33567674d01f8dd50fb1ea2a26ec8aad8a6558f4120ba6" frameborder="0" style="border:none; overflow:scroll;width:750px;height:700px;" allowTransparency="true"></iframe>

    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>
