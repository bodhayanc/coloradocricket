<?php

//------------------------------------------------------------------------------
// Items v1.0
//
// (c) Kervyn Dimney      - kervyn@yahoo.com
//------------------------------------------------------------------------------



function show_items_listing($db)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;
// 21-Apr-2017  11:20pm Changed isactive from being 0 to 1 to indicate ACTIVE. Changed the data too in the table.
    if ($db->Exists("SELECT * FROM board_ads_adverts WHERE isactive = 1 ORDER BY added_on DESC")) {
    // 3-Dec-2009 - Showing Active Items
    $db->QueryRow("SELECT * FROM board_ads_adverts WHERE isactive = 1 ORDER BY added_on DESC");

    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <font class=\"10px\">Items For Sale</font></p>\n";
    echo "  </td>\n";
     echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Active Items For Sale</b><br><br>\n";

    // Items Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td width=\"80%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Item</td>\n";
        echo "    <td width=\"20%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Price</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['id']));
        $na = htmlentities(stripslashes($db->data['title']));
        $price = htmlentities(stripslashes($db->data['price']));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"80%\"><a href=\"$PHP_SELF?id=$id&ccl_mode=1\">$na</a>&nbsp;\n";
        echo "    <td width=\"20%\">&nbsp;$price\n";
        echo "    </td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no items in the database\n";
    }
}
 function show_full_items($db,$id){
global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

// 21-Apr-2017  11:20pm Changed isactive from being 0 to 1 to indicate ACTIVE. Changed the data too in the table.
    if ($db->Exists("SELECT * FROM board_ads_adverts WHERE id = $id and isactive = 1 ORDER BY added_on DESC")) {
    // 3-Dec-2009 - Showing Active Items
    $db->QueryRow("SELECT * FROM board_ads_adverts WHERE id = $id and isactive = 1 ORDER BY added_on DESC");

    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
//    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/items.php\">Items</a> &raquo; <font class=\"10px\">Items For Sale</font></p>\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; <a href=\"/items.php\">Items</a> &raquo; </p>\n";
    echo "  </td>\n";
     echo "</tr>\n";
    echo "</table>\n";

//    echo "<b class=\"16px\">Active Items For Sale</b><br><br>\n";

    // Items Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td width=\"25%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Item</td>\n";
        echo "    <td width=\"75%\" bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;Item Details</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
            $iid = $db->data['id'];
            $itit = $db->data['title'];
            $ipic = $db->data['picture1'];
            $ipri = $db->data['price'];
            $idet = $db->data[item_details];

            $ina = $db->data[contact_name];
            $iph = $db->data[contact_phone];
            $iem = $db->data[contact_email];
            $iad = $db->data[added_on];

            $iac = $db->data[isactive];
            $iap = $db->data[isapproved];

           // output article

        if($i % 2) {
          echo "<tr class=\"trrow2\">\n";
        } else {
          echo "<tr class=\"trrow1\">\n";
        }

        echo "    <td width=\"25%\">&nbsp;\n";
        echo "    <td width=\"75%\"><b>$itit</b><br><br>$idet<br><br>Price:$$ipri<br><br><b>Contact Details:</b><br>$ina<br>$iph<br>$iem<br>\n";
        echo "    </td>\n";
        echo "  </tr>\n";
    }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

        // finish off
        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table>\n";

    } else {
        echo "There are no items in the database\n";
    }
}
// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_items_listing($db);
    break;
case 1:
    show_full_items($db,$id);
    break;
default:
    show_items_listing($db);
    break;
}


?>
