<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = schedule;
?>

<html>
<head>
<title>Colorado Cricket League - Schedule</title>
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
<script language="javascript">
  function gotosite(site)
  {
  if(site !="")
    {
    self.location=site;
    }
  }
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



    <?php include("includes/showprintschedule.php"); ?>


</body>
</html>
