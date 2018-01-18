<html>
<head>
  <title>Test Results</title>
</head>
<body>

<?php

// Quiz-o-matic '76 By Matt Hughes 

include ("variables.inc");
include ("functions.inc");

$db = Open_Database ($server, $user, $password, $database);

if (!$testname)
     {
     $result3 = mysql_list_tables($database);
     //echo "<table width='75%' bordercolor='#999999'>";
     //echo "<tr><td bgcolor='#CCCCCC'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Please select the test you'd like to see results for:</font></td></tr>";
     while ($row = mysql_fetch_row($result3))
            {
   //         printf("<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><a href=\"%s?testname=%s\">%s</a></font></td></tr>", $PHP_SELF, $row[0], $row[0]);
   //         printf("<a href=\"%s?testname=%s\">%s</a><br>\n", $PHP_SELF, $row[0], $row[0]);
            }
   //         echo "</table>";
            }else{
            if (!Table_Exists($testname))
                 {
   //              echo "Not a valid test file";
                 }else {
                 $result3 = mysql_list_tables($database);
   //              echo "<table width='75%' bordercolor='#999999'>";
   //              echo "<tr><td bgcolor='#CCCCCC'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Please select the test you'd like to see results for:</font></td></tr>";

                 while ($row = mysql_fetch_row($result3))
                        {
   //                     printf("<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><a href=\"%s?testname=%s\">%s</a></font></td></tr>", $PHP_SELF, $row[0], $row[0]);
                       }
   //              echo "</table>";
                 $result = mysql_query("SELECT * FROM $testname");
                 $numberofquestions = (mysql_num_fields ($result)-3);
                 $num_rows = mysql_num_rows($result);
                 for($i=1 ;$i<=$num_rows ; $i++)
                     {
                     $result2 = mysql_query("SELECT score FROM $testname WHERE id=$i");
                     $score = mysql_fetch_array($result2);
                     $scoretotal = $scoretotal + $score[score];
                     }
                 $average = round(($scoretotal/$num_rows),2);
                 echo "<br><br>";
                 echo "<table width='75%' border='0'>";
                 echo "<tr><td bgcolor='#00CCFF'><font size='3' face='Verdana, Arial, Helvetica, sans-serif'><strong>Results for: $testname</strong></font></td></tr>";
                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Tests Taken = $num_rows</font></td></tr>";
                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Average Score = $average%</font></td></tr>";
                 echo "</table><br>";

                 echo "<table width='75%' border='0'>";
                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Percent of each question answered correctly</strong></font></td></tr>";
                 echo "<tr><td><img src='graph.php?testname=$testname'></td></tr>";
                 echo "</table><br>";

                 echo "<table width='75%' border='0'>";
                 echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Complete Results</strong></font></td></tr>";
                 echo "</table>";
                 include ("chart.inc");
                 }
            }

 mysql_close($db);

?>

</body>

</html>

