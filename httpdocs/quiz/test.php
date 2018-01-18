<?php
    require ("includes/general.config.inc");
    require ("includes/class.mysql.inc");
    require ("includes/class.fasttemplate.inc");
    require ("includes/general.functions.inc");

    $page = CCLQuiz;

?>

<html>
<head>
<title>Colorado Cricket League - Umpiring Quiz</title>
<meta name="description" content="Home of the Colorado Cricket League.">
<meta name="keywords" content="colorado, cricket">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="../includes/css/cricket.css" type="text/css">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<?php include("includes/header.php"); ?>
<?php include("includes/links.php"); ?>
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
    require ("includes/navtop.php");
    ?>
      </td>
    </tr>
    </table>

    <b class="16px">Umpiring Quiz Results</b><br><br>
    
<?php

// Quiz-o-matic '76 By Matt Hughes     

// Include files
include ("variables.inc");
include ("functions.inc");



$db = Open_Database ($server, $user, $password, $database);

if (!Table_Exists($_POST['testname']))
        {
        Create_Table(($_POST['testname']), $answerarray);
        }


Create_Response_Array ($responses, $answerarray);

Create_CorrectAnswer_Array ($responses, $answerarray, $correctanswers);

Populate_Database ($correctanswers, ($_POST['testname']), $name);

Display_Results($correctanswers, $responses, $answerarray, $display_answers, $show_results);

 mysql_close($db);

?>
        
        </td>
       </tr>
      </table>


    </td>
    <td width="180" bgcolor="#D0C7C0" valign="top">

    <?php include("includes/right.php"); ?>


    </td>
  </tr>

<?php include("includes/footer.php"); ?>

</table>


</body>
</html>




