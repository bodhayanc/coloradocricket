<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = articles;

?>

<html>
<head>
<title>Colorado Cricket League - Article Recommended</title>
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


    <?php

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";


    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/articles.php\">User Articles</a> &raquo; <font class=\"10px\">Email article</font></p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Article: $article</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

    mail("$friend" , "$article" , "$yourname recommends that you read the following article:\n\n$article\n$articlelink\n\nTo read the article, please click the above link to go directly to the news article.\n\nKind Regards,\nColorado Cricket League\nhttp://www.coloradocricket.org","From: $yourmail\n");
    echo ("<p>Thank you for recommending the featured article <b>$article</b> to your friend, $friend.</p><p>They should be receiving the email shortly.</p>");

    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";


    echo "<p><a href=\"$articlelink\">&laquo; back to user featured article</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

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



