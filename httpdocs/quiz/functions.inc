<?php

// Quiz-o-matic '76 By Matt Hughes

function Create_Table($testname, $answerarray)
        {
echo($answerarray);
        $sql = "CREATE TABLE $testname (  id SMALLINT(5) NOT NULL AUTO_INCREMENT,  name varchar(30), score int,  PRIMARY KEY (id), UNIQUE id (id))";
        $result = mysql_query($sql) or die("Cant create table: ".mysql_error());
        $numberofquestions = count($answerarray);
        for($i=1 ;$i<=$numberofquestions ; $i++) {

                $newfieldname="q$i";
                $sql = "alter table $testname add column $newfieldname tinyint(1)";
                $result = mysql_query($sql) or die("Cant update table: ".mysql_error());
                }
        }


function Open_Database ($server, $user, $password, $database)
        {

        $db = mysql_connect($server,$user,$password) or die("Cant connect to database: ".mysql_error());

        mysql_select_db($database) or die("Cant select database: ".mysql_error());

        return $db;
        }



function Table_Exists($table_name)
         {
         $Table = mysql_query("show tables like '" .
                  $table_name . "'");

         if(mysql_fetch_row($Table) === false)
            return(false);

         return(true);
         }


function Create_Response_Array (&$responses, $answerarray)
        {
        $numberofquestions = count($answerarray);
        for($i=1 ;$i<=$numberofquestions ; $i++){
                $j = $i-1;
                $responses[$j] = $_POST["q$i"];

                }
        }

function Create_CorrectAnswer_Array ($responses, $answerarray, &$correctanswers)
        {
        $numberofquestions = count($answerarray);
        for($i=0 ;$i<=$numberofquestions-1 ; $i++){

                if (rtrim($responses[$i])==rtrim($answerarray[$i])){
                        $correctanswers[$i]=1;

                        } else {

                        $correctanswers[$i]=0;

                        }

                }

        }

function Display_Results($correctanswers, $responses, $answerarray, $display_answers, $show_results){

        $numberofquestions = count($correctanswers);

        $score = Get_Score($correctanswers);
        echo "<table width='100%' border='0'><tr><td bgcolor='#E1F5FA'><font size='3' face='Verdana, Arial, Helvetica, sans-serif'><strong>Your Test Results</strong></font></td></tr>";
        echo "<tr><td><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>Score = $score%</font></td></tr></table><br>";

        if ($display_answers) {
        echo "<table width='100%' border='1' bordercolor='#999999' bgcolor='#FFFFFF'>";
        } else {
         echo "<table width = '10%' border='1' bordercolor='#999999' bgcolor='#FFFFFF'>";
         }
        for($i=1 ;$i<=$numberofquestions ; $i++)
                {
                $j=$i-1;
                if ($correctanswers[$j]) {
                if ($display_answers) {
                        echo "<tr><td width='5%'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>#$i</font></td><td width='6%' align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><img src='images/checkmark.jpg'></font></td><td width='89%'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>$answerarray[$j]</strong></font></td></tr>";
                        } else {
                        echo "<tr><td width='5%'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>#$i</font></td><td width='6%' align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><img src='images/checkmark.jpg'></font></td></tr>";
                        }
                } else {
                if ($display_answers) {
                        echo "<tr><td width='5%'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>#$i</font></td><td width='6%' align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><img src='images/xmark.jpg' width='15' height='14'></font></td><td width='89%' bgcolor='#FDE2E2'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><strong>Correct Answer = $answerarray[$j]</strong></font></td></tr>";
                        } else {
                         echo "<tr><td width='5%'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>#$i</font></td><td width='6%' align='center'><font size='2' face='Verdana, Arial, Helvetica, sans-serif'><img src='images/xmark.jpg' width='15' height='14'></font></td></tr>";
                         }
                }
        }

        echo "</table><br>";
        if ($show_results) {
        echo "<br><a href='results.php'><strong><font size='2' face='Verdana, Arial, Helvetica, sans-serif'>View all test results</font></strong></a><br>";
                         }
}

function Get_Score ($correctanswers)
        {
        $numbercorrect = (array_count_values ($correctanswers));

        return round(($numbercorrect[1]/count($correctanswers))*100, 0);
        }



function Populate_Database ($correctanswers, $testname, $name)
        {

        $score = Get_Score($correctanswers);
        $sql = "insert into $testname (name,score) values ('$name','$score')";
        $result = mysql_query($sql);
        $id = mysql_insert_id();
        $numberofquestions=count($correctanswers);
        for($i=1 ;$i<=$numberofquestions ; $i++)
                {

                        $fieldname="q$i";
                        $j = $i-1;
                        $sql = "UPDATE $testname SET $fieldname='$correctanswers[$j]' WHERE id =$id";
                        $result = mysql_query($sql);

                }

}

?>