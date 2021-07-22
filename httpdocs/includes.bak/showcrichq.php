<?php

//------------------------------------------------------------------------------
// Show CricHQ Feed
// Developed By : Naveen Kaje (naveen.kaje@gmail.com)
//
//------------------------------------------------------------------------------


function show_crichq_feed()
{
  global $PHP_SELF, $bluebdr, $greenbdr, $yellowbdr, $redbdr, $blackbdr;
  // output article
  echo "<table width=\"100%\" cellpadding=\"6\" cellspacing=\"0\" border=\"0\">\n";
  echo "<tr>\n";
  echo "  <td align=\"left\" valign=\"top\">\n";

  // Border

   echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$greenbdr\" align=\"center\">\n";
   echo "  <tr>\n";
   echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Scores and Standings from CricHQ</td>\n";
   echo "  </tr>\n";
   echo "  <tr>\n";
   echo "  <td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

   echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

   echo "<iframe width=\"505\" border=\"0\" style=\"border:none;\" height=\"158\" src=\"http://www.crichq.com/plugins/comp_mgmt/organisations/53?width=505&height=158&border=1\"></iframe>";

   echo "</table>\n";

   echo "  </td>\n";
   echo "</tr>\n";
   echo "</table>\n";

   // finish off
   echo "  </td>\n";
   echo "</tr>\n";
   echo "</table>\n";
}

show_crichq_feed();

?>

