<?php
	require ("/home/colora12/public_html/includes/general.config.inc");
	require ("/home/colora12/public_html/includes/class.mysql.inc");
	require ("/home/colora12/public_html/includes/class.fasttemplate.inc");
	require ("/home/colora12/public_html/includes/general.functions.inc");

	$page = news;

?>

<html>
<head>
<title>Colorado Cricket League - Article Recommended</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="http://www.coloradocricket.org/includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("/home/colora12/public_html/cougars/includes/header.php"); ?>
<?php include("/home/colora12/public_html/cougars/includes/links.php"); ?>
    </td>
    <td width="520" bgcolor="#FFFFFF" valign="top">


    <?php

	echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";


	echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
	echo "<tr>\n";
	echo "  <td align=\"left\" valign=\"top\">\n";
	echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/news.php\">News</a> &raquo; Email article</p>\n";
	echo "  </td>\n";
	echo "  <td align=\"right\" valign=\"top\">\n";
	require ("/home/colora12/public_html/cougars/includes/navtop.php");
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
	echo ("<p>Thank you for recommending the news article <b>$article</b> to your friend, $friend.</p><p>They should be receiving the email shortly.</p>");

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table>\n";


	echo "<p><a href=\"$articlelink\">&laquo; back to news article</a></p>\n";

	// finish off
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	?>


    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("/home/colora12/public_html/cougars/includes/right.php"); ?>


    </td>
  </tr>

<?php include("/home/colora12/public_html/cougars/includes/footer.php"); ?>

</table>


</body>
</html>



