<?php


//------------------------------------------------------------------------------
// Mini Bulletin Board v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_mini_ads($db)
{
        global $content,$action,$SID,$USER;


        if (!$db->Exists("SELECT * FROM board_ads_adverts")) {

 		echo "    <p>There are currently no items for sale.</p>\n";
                return;

        } else {

			echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"1\" border-right=\"1\" bordercolor=\"#9E3228\" class=\"tablehead\">\n";
// 21-Apr-2017  11:20pm Changed isactive from being 0 to 1 to indicate ACTIVE. Changed the data too in the table.
			$db->Query("SELECT id,title,price FROM board_ads_adverts where isactive = 1 ORDER BY id DESC LIMIT 5");
			
			
			for ($x=0; $x<$db->rows; $x++) {
				$db->GetRow($x);

				$pid = $db->data['id'];
				$tit = $db->data['title'];
				$pri = $db->data[price];

				


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

			if($tit == "") {
			  $subject = "No Title";
			} else {
			  $subject = $tit;
			}
                                echo "  <td align=\"left\"><a href=\"items.php?&id=$pid&ccl_mode=1\" target=\"_new\">$tit</a><span class=\"9px\"> - $ $pri";
				//echo "  <td align=\"left\"><a href=\"/board/ads_item.php?id=$pid\" target=\"_new\">$tit</a><span class=\"9px\"> - $ $pri";
				echo "  </span></td>\n";

				echo "</tr>\n";
				}
				
				echo "<tr class=\"trrow2\">\n";
                                echo "  <td align=\"left\"><img src=\"/images/icons/icon_arrows.gif\"><a href=\"items.php\" target=\"_new\">Go to classifieds</a></td>\n";
				//echo "  <td align=\"left\"><img src=\"/images/icons/icon_arrows.gif\"><a href=\"/board/adverts.php\" target=\"_new\">Go to classifieds</a></td>\n";
				echo "</tr>\n";
				
			echo "</table>\n";
		}

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);


show_mini_ads($db);


?>
