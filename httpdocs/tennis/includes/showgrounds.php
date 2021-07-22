<?php

//------------------------------------------------------------------------------
// Grounds v1.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------



function show_grounds_listing($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    if ($db->Exists("SELECT * FROM grounds")) {
    $db->QueryRow("SELECT * FROM grounds WHERE GroundActive=1 ORDER BY GroundID");
    $db->BagAndTag();

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <a href=\"/index.php\">Home</a> &raquo; Grounds</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">Tennis Cricket Grounds</b><br><br>\n";

    // Grounds Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;All Grounds</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";

        echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

        echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);
        $id = htmlentities(stripslashes($db->data['GroundID']));
        $na = htmlentities(stripslashes($db->data['GroundName']));
        $di = htmlentities(stripslashes($db->data[GroundDirections]));

        // output article

        if($i % 2) {
          echo "<tr class=\"trrow1\">\n";
        } else {
          echo "<tr class=\"trrow2\">\n";
        }

        echo "    <td width=\"75%\"><a href=\"$PHP_SELF?grounds=$id&ccl_mode=1\">$na</a>";
        if ($db->data['picture'] != "") echo "&nbsp;<img src=\"http://www.coloradocricket.org/images/icons/icon_picture.gif\">";
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
        echo "There are no grounds in the database\n";
    }
}


function show_full_grounds($db,$s,$id,$pr)
{
    global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr;

    $db->QueryRow("SELECT * FROM grounds WHERE GroundID=$pr");
    $db->BagAndTag();

    $id = $db->data['GroundID'];
    $na = $db->data['GroundName'];
    $di = $db->data[GroundDirections];
    $zi = $db->data[GroundZip];
    $de = $db->data[description];
    $pa = $db->data[parking];
    $cp = $db->data[coveredparking];
    $sh = $db->data[shelter];
    $ha = $db->data[handicapped];
    $ss = $db->data[stadiumseating];
    $rr = $db->data[restrooms];
    $cs = $db->data[conveniencestore];
    $dw = $db->data[drinkingwater];
    $pt = $db->data[publictransport];

    echo "<table width=\"100%\" cellpadding=\"10\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";

    echo "<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
    echo "<tr>\n";
    echo "  <td align=\"left\" valign=\"top\">\n";
    echo "  <p><a href=\"/index.php\">Home</a> &raquo; <a href=\"/grounds.php\">Grounds</a> &raquo; $na</p>\n";
    echo "  </td>\n";
    echo "  <td align=\"right\" valign=\"top\">\n";
    require ("includes/navtop.php");
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

    echo "<b class=\"16px\">$na Cricket Ground</b><br><br>\n";

    // Ground Photo Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$na Photo</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    $db->QueryRow("SELECT picture FROM grounds WHERE GroundID=$pr");
    $db->DeBagAndTag();

    for ($i=0; $i<$db->rows; $i++) {
        $db->GetRow($i);

        $pic = $db->data['picture'];


    // output story

        if ($db->data['picture'] != "" ) {

        echo "<tr>\n";
        echo "    <td width=\"100%\" align=\"center\"><img src=\"http://www.coloradocricket.org/uploadphotos/grounds/$pic\"></td>\n";
        echo "  </tr>\n";


        } else {

        echo "<tr>\n";
        echo "    <td width=\"100%\">No ground photo at this time.</td>\n";
        echo "  </tr>\n";

        }

        }

        echo "</table>\n";

        echo "  </td>\n";
        echo "</tr>\n";
        echo "</table><br>\n";

    // Ground Description Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;$na Description</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";

    if($de != "") {
    echo "    <td><p>$de</p></td>\n";
    } else {
    echo "    <td><p>No description available at this time.</p></td>\n";
    }

    echo "  </tr>\n";
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Ground Amenities Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;$na Amenities</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

    echo "  <tr class=\"trrow2\">\n";
    if($pa != "") {
    echo "    <td width=\"50%\">Parking</td>\n";
    echo "    <td width=\"50%\">$pa</td>\n";
    } else {
    echo "    <td width=\"50%\">Parking</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($cp != "") {
    echo "    <td width=\"50%\">Covered Parking</td>\n";
    echo "    <td width=\"50%\">$cp</td>\n";
    } else {
    echo "    <td width=\"50%\">Covered Parking</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($sh != "") {
    echo "    <td width=\"50%\">Shelter</td>\n";
    echo "    <td width=\"50%\">$sh</td>\n";
    } else {
    echo "    <td width=\"50%\">Shelter</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($ha != "") {
    echo "    <td width=\"50%\">Handicapped Accessible</td>\n";
    echo "    <td width=\"50%\">$ha</td>\n";
    } else {
    echo "    <td width=\"50%\">Handicapped Accessible</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($ss != "") {
    echo "    <td width=\"50%\">Stadium Seating</td>\n";
    echo "    <td width=\"50%\">$ss</td>\n";
    } else {
    echo "    <td width=\"50%\">Stadium Seating</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($rr != "") {
    echo "    <td width=\"50%\">Rest Rooms</td>\n";
    echo "    <td width=\"50%\">$rr</td>\n";
    } else {
    echo "    <td width=\"50%\">Rest Rooms</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($cs != "") {
    echo "    <td width=\"50%\">Convenience Store</td>\n";
    echo "    <td width=\"50%\">$cs</td>\n";
    } else {
    echo "    <td width=\"50%\">Convenience Store</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow1\">\n";
    if($dw != "") {
    echo "    <td width=\"50%\">Drinking Water</td>\n";
    echo "    <td width=\"50%\">$dw</td>\n";
    } else {
    echo "    <td width=\"50%\">Drinking Water</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";

    echo "  <tr class=\"trrow2\">\n";
    if($pt != "") {
    echo "    <td width=\"50%\">Public Transport Accessible</td>\n";
    echo "    <td width=\"50%\">$pt</td>\n";
    } else {
    echo "    <td width=\"50%\">Public Transport Accessible</td>\n";
    echo "    <td width=\"50%\">n/a</td>\n";
    }
    echo "  </tr>\n";


    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Ground Directions Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;$na Directions</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td><p>$di</p></td>\n";

    echo "  </tr>\n";
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table><br>\n";

    // Ground Weather Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">&nbsp;$na Weather</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
    echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

    echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
    echo "  <tr>\n";
    echo "    <td><p>";
    if ($db->data[GroundZip] != "0") {
    echo "    <script src='http://voap.weather.com/weather/oap/$zi?template=GENXH&par=1004982138&unit=0&key=dd43509d7e444c1c1f5c322975a6adaf'></script>\n";
    } else {
    echo "";
    }
    echo "    </p></td>\n";

    echo "  </tr>\n";
    echo "  </table>\n";

    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";


    // output link back
    $sitevar = "/grounds.php?grounds=$pr&ccl_mode=1";
    echo "<p><a href=\"$PHP_SELF\">&laquo; back to grounds listing</a></p>\n";

    // finish off
    echo "  </td>\n";
    echo "</tr>\n";
    echo "</table>\n";

}



// main program


// open up db connection now so you don't have to in every other file
$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);

switch($ccl_mode) {
case 0:
    show_grounds_listing($db,$s,$id,$grounds);
    break;
case 1:
    show_full_grounds($db,$s,$id,$grounds);
    break;
default:
    show_grounds_listing($db,$s,$id,$grounds);
    break;
}


?>
