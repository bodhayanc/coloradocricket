<?php
	require ("../includes/general.config.inc");
	require ("../includes/class.mysql.inc");
	require ("../includes/class.fasttemplate.inc");
	require ("../includes/general.functions.inc");
?>

<!-- // The main header file which contains all database site settings attributes -->
<?php include("../includes/header.php"); ?>
<p>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td width="100%"><span class="header">Site Control Administration Help</span></td>
  </tr>
</table>
</p>

<table border="0" width="100%" cellpadding="2" cellspacing="2">
<tr>
<td>




<?php if ($show == "login") { ?>

<p><span class="newsheader">Login Screen</span></p>
<p>To login simply enter your email address and the password assigned to you, or the password
you entered previously. and then click on the 'enter admin area' button.</p>

<p>&nbsp</p>


<?php } else if ($show == "settingsadmin") { ?>

<p><span class="newsheader">Site Settings</span></p>
<p>Manage the layout of the site quickly and easily with the site settings admin. Easily choose colours
from web safe paint palette's. Choose what font types or sizes you want to use. You'll love this addition to
Site Control!</p>

<p>&nbsp</p>


<?php } else if ($show == "gbadmin") { ?>

<p><span class="newsheader">Guestbook Administration</span></p>
<p>Easily manage unwanted user postings to your website. Locate the username who posted the message to
your guestbook and either edit the post or delete it altogether.</p>

<p>&nbsp</p>





<?php } else if ($show == "generalhtmladmin") { ?>

<p><span class="newsheader">HTML Administration</span></p>
<p>Easily manage the text that appears on your site. Simply edit html of any item you wish to change, type in
your changes and "voila" instant HTML changes on your front end site!</p>

<p>&nbsp</p>





<?php } else if ($show == "faqadmin") { ?>

<p><span class="newsheader">FAQ Administration</span></p>
<p>Manage the FAQ categories and then add questions to the category. Be sure to create the categories first and
then add the questions.</p>

<p>&nbsp</p>





<?php } else if ($show == "polladmin") { ?>

<p><span class="newsheader">Poll Administration</span></p>
<p>Easily manage the daily/weekly poll. Simply click create new poll, add a question and type in one right answer
and a bunch of wrong answers.</p>

<p>&nbsp</p>





<?php } else if ($show == "imagegallery") { ?>

<p><span class="newsheader">Image Gallery Administration</span></p>
<p>Easily manage the names of the photo galleries. Create the gallery first, and then add photos to the gallery
in photo admin.</p>

<p>&nbsp</p>




<?php } else if ($show == "imagephotos") { ?>

<p><span class="newsheader">Image Photo Administration</span></p>
<p>Easily manage the photo's added to each gallery. Make sure that you upload photo's to the dimensions specified.</p>

<p>&nbsp</p>





<?php } else if ($show == "ml_lists") { ?>

<p><span class="newsheader">Mailing List Administration</span></p>
<p>Easily manage the mailing lists.</p>

<p>&nbsp</p>





<?php } else if ($show == "ml_emails") { ?>

<p><span class="newsheader">Mailing List Email Administration</span></p>
<p>Easily manage the email addresses within the mailing lists.</p>

<p>&nbsp</p>





<?php } else if ($show == "ml_send") { ?>

<p><span class="newsheader">Mailing List Send Administration</span></p>
<p>Easily type and send newsletters.</p>

<p>&nbsp</p>





<?php } else if ($show == "ml_archive") { ?>

<p><span class="newsheader">Mailing List Archive Administration</span></p>
<p>Easily manage the mailing list archives.</p>

<p>&nbsp</p>





<?php } else if ($show == "newsadmin") { ?>

<p><span class="newsheader">News Article Administration</span></p>
<p>The News Article Administration Panel is where you add new articles, edit your own articles or delete articles.
(If you have a high security level)</p>

<p>Click "Add a new article" to post a new article to the site. The form is very straight forward, simply fill in all
the information and click the "add article" button.</p>
<p>&nbsp;</p>





<?php } else if ($show == "useradmin") { ?>

<p><span class="newsheader">User Administration</span></p>
<p>This module should be rarely used. It allows you to add users to gain access to Site Control.
You would only grant trusted people access. Site Control gives you the ability to add a user and limit
them to certain modules. For example:</p>

<p>You have a friend who is posting news articles to the site. You can grant that friend access to only
the news articles, while "hiding" everything else. </p>
<p>&nbsp</p>





<?php } else if ($show == "cpasswd") { ?>

<p><span class="newsheader">Change Password</span></p>
<p>Enter in a new alphanumeric password, and enter it again for verification.</p>
<p>&nbsp</p>




<?php } else if ($show == "") { ?>

<p><span class="newsheader">Main Menu</span></p>
<p>Once logged in, you will be welcomed with a personalized message along with a generic welcome message.
This message invites the user to click the "Help" button in any screen to receive information about that
particular Site Control section.</p>

<p>The right hand column contains links to various modules of Site Control. These modules are controlled by the
User Security options found within "User Admin". If you are planning on having other users login to the site,
you can grant access to limited portions of the site, or as much as you want. More about this later.</p>

<p>Get familiar with the links in the right hand column.</p>
<p>&nbsp</p>





<?php } else { ?>

<p>There is no help available for this section.</p>
<p>&nbsp</p>




<?php } ?>
</td>
</tr>
</table>

<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td width="100%">
      <p><a href="javascript:close()"><span class="newsheader">close window</span></a></td>
  </tr>
</table>
</body>
</html>
