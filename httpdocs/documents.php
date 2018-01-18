<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = documents;
?>

<html>
<head>
<title>Colorado Cricket League - League Documents</title>
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
    <td bgcolor="#FFFFFF" valign="top"><br>
    <?php include("includes/showrandomsponsor.php");?>
    <?php include("includes/showdocuments.php"); ?>
    <?php include("includes/showrandomsponsor_bottom.php");?>

    </td>
    <td width="450" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>
