<?

$PostPage = "post.php"; 		// the page with the form on it
$gbPage = "index.php";  		// the main page
$dgbPage = "guest.php";

$allowHTML = 0;				// To allow HTML in site description 1 = Yes, 0 = No

$notify = 1; 				// Would you like to be notified when a link is added? 1 = yes, 0 = No
$my_email = "webmaster@coloradocricket.org"; 	// Enter your email address
$subject = "Guestbook Signed" ; 	// Enter the subject of the notification email


$db_addr = 'localhost';			// address of MySQL server.
$db_user = 'colora12_cricket';			// Username to access server.
$db_pass = 'cricket';			// Password access server.
$db_name = 'colora12_cricket';			// Name of database to connect to.
$connect = @mysql_connect("$db_addr", "$db_user", "$db_pass");

?>
