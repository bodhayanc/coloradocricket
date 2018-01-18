<?php
    require ("../includes/general.config.inc");
    require ("class.mysql.inc");
    require ("class.fasttemplate.inc");
    require ("general.functions.inc");

    $page = quiz;

?>

<html>
<head>
<title>Colorado Cricket League - Umpiring Quiz</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="/includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("header.php"); ?>
<?php include("links.php"); ?>
    </td>
    <td width="420" bgcolor="#FFFFFF" valign="top">

    <table width="100%" cellpadding="10" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">
      <a href="/index.php">Home</a> &raquo; Umpiring Quiz</p>
      </td>
      <td align="right" valign="top">
    <?php
    require ("navtop.php");
    ?>
      </td>
    </tr>
    </table>


    <b class="16px">Umpiring Quiz</b><br><br>
 
        <?php include("CCL_Umpiring.htm"); ?>
        
        </td>
       </tr>
      </table>


    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("right.php"); ?>


    </td>
  </tr>

<?php include("footer.php"); ?>

</table>


</body>
</html>
