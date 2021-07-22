<?php

// Quiz-o-matic '76 By Matt Hughes 

// These variables MUST be set by you:
// Name of your MySql server
$server = "localhost";
// Database username
$user = "colora12_cricket";
// Database password
$password = "cricket	";
// Name of the database
$database = "colora12_cricket";

// These variables MAY be set by you:
// Do you want the correct answers displayed to the test taker after taking test?  1 for yes 0 for no.
$display_answers = 1;
// Do you want a link to the results page displayed for the test taker after taking test? 1 for yes 0 for no.
$show_results = 0;
// Height of the graph in pixels
$graphheight = 300;

// Don't change these
$answerarray = file($_POST['answerfile'] );
$numberofquestions=count($answerarray);
$responses = array();
$correctanswers = array();
$numbercorrect = 0;


?>