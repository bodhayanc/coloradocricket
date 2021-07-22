Quiz-o-matic '76 v. 1.0  
by: Matt Hughes
flashlighbrown@hotmail.com

Setting up your Quiz

The Quiz-o-matic '76 is very easy to set up, all you'll need to do is follow the steps below.  The only technical things you'll have to deal with are a little HTML work, FTP and creating a MySql database on your server (something that your ISP should be able to help you with).


1. Write the test

This is simple enough, get a piece of paper and write out your questions.  The example i've provided uses multiple choice questions with radio buttons.  For online purposes this is probably the best way to go, but the Quiz-o-matic '76 will also work with text box answers.  (The problem with straight text answers is that the Quiz-o-matic '76 doesn't currently include any sophisticated AI functions  that will recognize "A Christie" as being the same as "Agatha C."  If you feel like writing that yourself, please send me a copy!)

2. Turn the test into HTML

Best bet is to use my example tests as a template, but here's a short explanation.

The HTML test page is one big form that posts to test.php, the main program.  You shouldn't have to change any of the form properties unless (for some strange reason) you change the name of the program, but hey, don't do that.

There are two hidden fields at the beginning of the form, the first one (testname) passes the name of the test.  This is easy enough, just change the value of this field to whatever you want this test to be called.  Since the database uses this value to create a table in it's likeness, the value MUST BE ONLY ONE WORD, NO SPACES.  In fact i'm not even sure if you can use wierd characters.  Just to be safe, split the words up with an underscore and don't get too creative.  (my example: value='test_example_01').  The second hidden field is the name of the answer file.  You'll be introduced to the answer file a bit later, but for now, just know that the value of this field is THE NAME OF THE ANSWER FILE!

The third field is the test taker's name.  No big deal, just leave it as is.

Next comes the questions.  Each one includes the question itself (of course) followed by the possible answers (in my example i've listed them next to radio buttons).  Each radio button for each question comes with a 'name' and a 'value'.  The name must be "q" and a number, depending on which question the button's related to.  So for all the answers to question number 5, all the radio buttons have the name "q5".  The value of each radio button is the text of the answer they represent.  So for a possible answer "Red", the value will be "Red".  For "Nebraska" it'll be "Nebraska".

There you go, the test is written.  Save the file as "YourTestName.htm" and grab a drink.

3. Make the answer file

You now have to create a text file with all the answers to your test, each one on it's own line.  The only thing you really have to make DOUBLE sure of is that each answer matches the answers you made in the value fields of your questions in your HTML file (see above) exactly, including case.  Once you've done this, make sure each one is on it's own line, save the file and make sure the answer file field in your HTML file (see above) matches this name.  I named mine "answers01.txt", pretty easy to remember.  

4. Create the MySql database

Although creating a MySql database may seem a bit intimidating to the novice, it's really quite simple (plus, the Quiz-o-Matic will automatically take care of most of the hard bits).  Your first source of info for this should be your ISP.  Many (if not all) ISP's will provide a very simple online utility to create a MySql database for you, or at the very least, give you enough documentation to guide you to your goal.  Remember, all you have to do is create the database, you don't have to create tables or anything like that.  Once you've successfully made the database you'll have a database server name, database name, username and password.  If you haven't aquired this info yet, than you probably haven't set up the database properly. 

5. Adjust the variables.inc file

The file named variables.inc contains a few variable declarations that the Quiz-o-matic '76 uses to personalize your quiz.  All YOU have to do is set the values of 4 of them (the first 4) which deal with your database info; database server name, database name, username and password.  There are a few after that that you MAY change if you want, just read the comments, but please, don't change any of these until you've got the whole thing working first.
If you're running the program locally (on your own computer), then 95% of the time the $server variable can just be set to 'localhost' with the username being 'root', or 'user' or something like that.  Often you can just leave the password blank.
If you're running the program from a hosting server and you're still confused then you should check with your ISP's documentation (or even a real person) as to what these settings should be.  A lot of the time they're the same as above.

6. Upload the whole shebang

The last step is to upload all the files to your server (including the little directory called "images" which holds the check-mark and x-mark graphics).  Best bet is to make a directory on your server called "Test01" or something a little more interesting and upload everything into that.  You'll need to make a page with a link to your test (the name of your HTML file) but assuming everything was set up properly that's about all you'll need.

As the webmaster, you'll need access to the page that displays the collected test results.  All you'll need to accomplish this is to point your browser to the "results.php" file.  You can do this manually (by typing the path in the address bar) or creating a webpage with the link on it.

The Quiz-o-Matic allows you to set up any number of tests, all in the same directory and all using the same program.  Just make different HTML quiz files with matching "answer" files and the Quiz-o-matic '76 will take care of all of them.  Each time you run a new test for the first time, the Quiz-o-Matic creates a new table in your database to handle it.

Common Problems

The most common problem is when an extra line is inadvertently added to the end of the answers01.txt file.  This will cause problems with grading, usually ending up with a quiz that only counts 1 question right, no matter what answers are entered.  Make sure that there are NO extra lines (carriage returns) under the last listed answer.  To make double sure, put your cursor at the end of the last word in the file and click delete a bunch of times.

Right now there's no easy way to manage the quiz tables in your database.  For example, if you create a quiz and run it, but them want to edit the questions in your quiz later, you'll have to either a) manually delete the table in your database (which will require some very basic MySql work on your part, it's not hard, but still you may not want to delve into this) or b) save the edited quiz with a NEW name and a NEW answer file.  This second one will fix the problem, but you'll still have to live with the legacy of the original quiz, as it will be listed on your results page.

That's it.  Hopefully you'll have no problems, but if you do (or if, god forbid, you find something WRONG with this program) feel free to email me and i'll do my best to get back to you as soon as possible.


 

