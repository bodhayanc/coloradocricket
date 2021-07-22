<?php

// Quiz-o-matic '76 By Matt Hughes

if ($myrow = mysql_fetch_array($result))
    {
    echo "<table border=1>\n";
    echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Name</strong></font></td><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Score</strong></font></td>";
    for($i=1 ;$i<=$numberofquestions ; $i++)
        {
        echo "<td width='20' align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>#$i</strong></font></td>";
        }
    echo "</tr>\n";
    do
    {
    printf("<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>%s</font></td><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>%s</font>", $myrow["name"], $myrow["score"]);
    echo "%";
    for($i=1 ;$i<=$numberofquestions ; $i++)
        {
        $whichquestion = "q$i";
        if ($myrow[$whichquestion]==1)
            {
            echo "<td width='20' align='center'><img src='images/checkmark.jpg'></td>";
            } else {
            echo "<td width='20' align='center'><img src='images/xmark.jpg'></td>";
            }
        }
        echo "</tr>\n";
    } while ($myrow = mysql_fetch_array($result));
    echo "</table>\n";
    } else {
    echo "Sorry, no records were found!";
    }

?>