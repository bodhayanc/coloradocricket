<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = unsubscribe;

?>

<html>
<head>
<title>Colorado Cricket League - Unsubscribe from mailing list</title>
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


    <?php

        // start up the database class

        $db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
        $db->SelectDB($dbcfg['db']);

        // check for the id number
        if (!isset($id) || $id=="") echo "<p>You must supply your id number when unsubscribing from the mailing list.</p>\n";

        // check for email address
        else if (!isset($email) || $email=="") echo "<p>You must supply your email address when unsubscribing from the mailing list.</p>\n";

        // check for combination of both
        else if (!$db->Exists("SELECT id FROM $tbcfg[mlemails] WHERE ID=$id AND email='$email'")) echo "<p>That email and id combination does not exist in the database.</p>\n";

        // else everything is cool
        else {
            $db->Update("UPDATE $tbcfg[mlemails] SET unsubscribed=1 WHERE ID=$id");
            if ($db->a_rows != -1) echo "<p>You have now unsubscribed yourself from the mailing list.</p>\n";
            else echo "<p>You could not be removed from the mailing list at this time.</p>\n";
        }

        ?>

    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>
