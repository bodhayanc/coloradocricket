<?php

//------------------------------------------------------------------------------
// Site Control Administration v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------

require "../includes/class.fasttemplate.inc";
require "../includes/general.config.inc";
require "../includes/class.mysql.inc";
require "../includes/general.functions.inc";
?>


<html>
<head>
<title>Colorado Cricket League - Administration</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="../includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("../includes/header.php"); ?>
<?php include("../includes/links.php"); ?>

    </td>
    <td width="420" bgcolor="#FFFFFF" valign="top">

    <table width="100%" cellpadding="10" cellspacing="0" border="0">
    <tr>
      <td align="left" valign="top">

    <a href="/index.php">Home</a> &gt; Admin Login</p>

    <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="<?=$bluebdr?>" align="center">
      <tr>
        <td bgcolor="<?=$bluebdr?>" class="whitemain" height="23">&nbsp;CCL Administration Login</td>
      </tr>
      <tr>
      <td bgcolor="#FFFFFF" valign="top" bordercolor="#FFFFFF" class="main" colspan="2">

      <table width="100%" cellspacing="0" cellpadding="3">
        <tr>
          <td>

    <?php
	if(isset($_GET['again'])) {
		  if ($_GET['again']==1) echo "<p>username/password not found.</p>\n";
		  elseif ($_GET['again']==2) echo "<p>You have successfully logged out.</p>\n";
		  elseif ($_GET['again']==3) echo "<p>Your session has expired. Please login again.<br>Remember to logout after each session!</p>\n";
		if ($_GET['again']) echo "<br>\n";
	}
    ?>

  <form action="login.php" method="post">
  <p>Please login to CCL Administration.</p>

      <table border="0" cellspacing="1" cellpadding="1"  width="60%">
        <tr class="trbottom">
          <td>Username</td><td><input type="text" name="form_email" size="20" maxlength="64"></td>
        </tr>
        <tr class="trbottom">
          <td>Password</td><td><input type="password" name="form_pword" size="20" maxlength="64"></td>
        </tr>
        <tr class="trbottom">
          <td colspan="2"><input type="submit" value="enter admin area"></td>
        </tr>
      </table>

      </form>


        </table>

          </td>
        </tr>
        </table>

          </td>
        </tr>
        </table>

    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("../includes/right.php"); ?>


    </td>
  </tr>

<?php include("../includes/footer.php"); ?>

</table>
</body>
</html>