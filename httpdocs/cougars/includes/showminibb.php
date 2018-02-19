<?php


//------------------------------------------------------------------------------
// Mini Bulletin Board v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_mini_bb($db)
{
        global $content,$action,$SID,$USER;


        if (!$db->Exists("SELECT * FROM board_posts")) {

 		echo "    <p>There are currently no posts in the database.</p>\n";
                return;

        } else {

			echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border=\"0\">\n";

			$db->Query("SELECT p.post_id, p.topic_id, p.forum_id, p.post_username, pt.post_subject, pu.user_id, pu.username FROM (board_posts p INNER JOIN board_posts_text pt ON p.post_id = pt.post_id) INNER JOIN board_users pu ON p.poster_id = pu.user_id WHERE p.forum_id = 7 ORDER BY p.post_id DESC LIMIT 10");
			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);

				$pid = $db->data['post_id'];
				$tid = $db->data['topic_id'];
				$fid = $db->data['forum_id'];
				$psu = $db->data['post_subject'];
				$uid = $db->data['user_id'];
				$una = $db->data['username'];
				$pun = $db->data['post_username'];



			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			if($pun != "") {
			  $postuser = $pun;
			} else {
			  $postuser = "Anonymous";
			}

			if($una == "Anonymous") {
			  $user = $postuser;
			} else {
			  $user = $una;
			}

			if($psu == "") {
			  $subject = "No Subject";
			} else {
			  $subject = $psu;
			}

				echo "  <td align=\"left\"><a href=\"http://www.coloradocricket.org/board/viewtopic.php?p=$pid#$pid\" target=\"_new\">$subject</a><span class=\"9px\"> - $user</span></td>\n";

				echo "</tr>\n";
				}
			echo "</table>\n";
		}

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_mini_bb($db);


?>
