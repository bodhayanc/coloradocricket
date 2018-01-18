<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = schedule;
?>

<html>
<head>
<title>Colorado Cricket League - CSU Formatted Schedule</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">

<style type="text/css">
body        { background: Green; 
          color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 12px; 
          font-style: normal; 
          font-weight: normal; }
          
a       { font-family: Times New Roman; 
          font-size: 12px; 
          color:#FFFF00; 
          text-decoration: none; 
          font-weight: bold; }
          
a:hover     { font-family: Times New Roman; 
          font-size: 12px; 
          color: #FFFF00; 
          text-decoration: none; 
          font-weight: bold; }
          
th          { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 12px; 
          font-style: normal; 
          font-weight: normal; }
          
td          { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 12px; 
          font-style: normal; 
          font-weight: normal; }
.16px       { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 16px; 
          font-style: normal; 
          font-weight: bold; }
          
.14px       { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 14px; 
          font-style: normal; 
          font-weight: bold; }
        
.12px       { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 12px; 
          font-style: normal; 
          font-weight: normal; }

11px        { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 11px; 
          font-style: normal; 
          font-weight: normal; }
        
.10px       { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 10px; 
          font-style: normal; 
          font-weight: normal; }
        
.9px        { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 9px; 
          font-style: normal; 
          font-weight: normal; }
          
.8px        { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 8px; 
          font-style: normal; 
          font-weight: normal; }
          
.7px        { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 7px; 
          font-style: normal; 
          font-weight: normal; }
          
.6px        { color: #FFFFFF; 
          font-family: Times New Roman; 
          font-size: 6px; 
          font-style: normal; 
          font-weight: normal; }
          
</style>

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

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="Green">

    
<?php include("includes/showcsuschedule.php"); ?>

</body>
</html>
