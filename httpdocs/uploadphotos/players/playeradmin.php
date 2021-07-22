<?php

//------------------------------------------------------------------------------
// Site Control History Administration v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


function show_main_menu($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a player</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM players")) {
		echo "<p>There are currently no players in the database.</p>\n";
		return;
	} else {

	// Search Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the Player database</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
	// Alpha Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=A\">A</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=B\">B</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=C\">C</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=D\">D</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=E\">E</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=F\">F</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=G\">G</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=H\">H</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=I\">I</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=J\">J</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=K\">K</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=L\">L</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=M\">M</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=N\">N</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=O\">O</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=P\">P</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Q\">Q</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=R\">R</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=S\">S</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=T\">T</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=U\">U</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=V\">V</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=W\">W</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=X\">X</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Y\">Y</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Z\">Z</a>\n";
	

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	// Teams Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 AND LeagueID=1 ORDER BY TeamName");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$id = htmlentities(stripslashes($db->data['TeamID']));
		$na = htmlentities(stripslashes($db->data['TeamName']));
		$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
		$di = htmlentities(stripslashes($db->data['TeamDirections']));

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td width=\"100%\"><a href=\"main.php?SID=$SID&action=$action&do=byteam&team=$id&teamname=$na\">$na</a>&nbsp;\n";
		echo "    </td>\n";
		echo "  </tr>\n";
	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}

}



function show_search_menu($db,$search="")
{
         global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

         if (!$db->Exists("SELECT * FROM players")) {
                 echo "<p>There are currently no players.</p>\n";
                 return;
         }

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a player</a></p>\n";

	// Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the player database</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	// Alpha Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=A\">A</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=B\">B</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=C\">C</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=D\">D</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=E\">E</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=F\">F</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=G\">G</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=H\">H</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=I\">I</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=J\">J</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=K\">K</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=L\">L</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=M\">M</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=N\">N</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=O\">O</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=P\">P</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Q\">Q</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=R\">R</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=S\">S</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=T\">T</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=U\">U</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=V\">V</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=W\">W</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=X\">X</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Y\">Y</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Z\">Z</a>\n";
	

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";		


	if ($search != "")
	{

	    $contains = "PlayerLName LIKE '%{$search}%' OR PlayerFName LIKE '%{$search}%'";

		$db->Query("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE $contains ORDER BY pl.PlayerLName");
			if ($db->rows)
			{

        		echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
        		echo "  <tr>\n";
        		echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Players Matching \"$search\"</td>\n";
        		echo "  </tr>\n";
        		echo "  <tr>\n";
			echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

			echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

			for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			$id = htmlentities(stripslashes($db->data['PlayerID']));
			$pln = htmlentities(stripslashes($db->data['PlayerLName']));
			$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
			$pte = htmlentities(stripslashes($db->data['TeamAbbrev']));

			if($i % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}

		echo "    <td align=\"left\">$pln, $pfn <span class=\"9px\">($pte)</span>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
		if ($db->data['picture1'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture_action.gif\">\n";
		echo "    </td>\n";
		echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['PlayerID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['PlayerID'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
		echo "  </tr>\n";

			}

		echo "</table>\n";

		// finish off
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
		
	// Teams Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 ORDER BY TeamName");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$id = htmlentities(stripslashes($db->data['TeamID']));
		$na = htmlentities(stripslashes($db->data['TeamName']));
		$di = htmlentities(stripslashes($db->data['TeamDirections']));

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td width=\"100%\"><a href=\"main.php?SID=$SID&action=$action&do=byteam&team=$id\">$na</a>&nbsp;\n";
		echo "    </td>\n";
		echo "  </tr>\n";
	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";		
		
		}
		else
		{

		// Search Box

        	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
        	echo "  <tr>\n";
        	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Search the player database</td>\n";
        	echo "  </tr>\n";
        	echo "  <tr>\n";
		echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

		echo "<p>There are no players matching that query in any way.</p>\n";

		echo "    </td>\n";
		echo "  </tr>\n";
		echo "</table><br>\n";
		
	// Teams Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 ORDER BY TeamName");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$id = htmlentities(stripslashes($db->data['TeamID']));
		$na = htmlentities(stripslashes($db->data['TeamName']));
		$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
		$di = htmlentities(stripslashes($db->data['TeamDirections']));

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td width=\"100%\"><a href=\"main.php?SID=$SID&action=$action&do=byteam&team=$id&teamname=$na\">$na</a>&nbsp;\n";
		echo "    </td>\n";
		echo "  </tr>\n";
	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";		

		}
        }


 }


function show_byletter_menu($db,$letter)
{
         global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;
         
	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a player</a></p>\n";

	// Search Box

        echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
        echo "  <tr>\n";
        echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the player database</td>\n";
        echo "  </tr>\n";
        echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";

	// Alpha Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=A\">A</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=B\">B</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=C\">C</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=D\">D</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=E\">E</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=F\">F</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=G\">G</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=H\">H</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=I\">I</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=J\">J</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=K\">K</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=L\">L</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=M\">M</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=N\">N</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=O\">O</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=P\">P</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Q\">Q</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=R\">R</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=S\">S</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=T\">T</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=U\">U</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=V\">V</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=W\">W</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=X\">X</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Y\">Y</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Z\">Z</a>\n";
	

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";


	// Alpha Players Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;Players Last Names beginning with \"$letter\"</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";


	if ($db->Exists("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' ORDER BY pl.PlayerLName")) {
	$db->QueryRow("SELECT te.TeamID, te.TeamAbbrev, pl.* FROM players pl INNER JOIN teams te ON pl.PlayerTeam = te.TeamID WHERE pl.PlayerLName LIKE '{$letter}%' ORDER BY pl.PlayerLName");
	$db->BagAndTag();
	for ($r=0; $r<$db->rows; $r++) {
		$db->GetRow($r);
		$id = htmlentities(stripslashes($db->data['PlayerID']));
		$pln = htmlentities(stripslashes($db->data['PlayerLName']));
		$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
		$pte = htmlentities(stripslashes($db->data['TeamAbbrev']));
		$ia = htmlentities(stripslashes($db->data[isactive]));

		// output article

			if($r % 2) {
			  echo "<tr class=\"trrow1\">\n";
			} else {
			  echo "<tr class=\"trrow2\">\n";
			}

		echo "    <td align=\"left\">$pln, $pfn <span class=\"9px\">($pte)</span>&nbsp;\n";
		if ($db->data['picture'] != "") echo "<img src=\"/images/icons/icon_picture.gif\">\n";
		if ($db->data['picture1'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture_action.gif\">\n";
		if ($db->data[isactive] != 0) echo "&nbsp;<font color=\"red\"><b>Inactive</b></font>\n";
		echo "    </td>\n";
		echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['PlayerID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['PlayerID'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
		echo "  </tr>\n";
	}

		echo "</table>\n";



		// finish off
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
	// Teams Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 ORDER BY TeamName");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$id = htmlentities(stripslashes($db->data['TeamID']));
		$na = htmlentities(stripslashes($db->data['TeamName']));
		$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
		$di = htmlentities(stripslashes($db->data['TeamDirections']));

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td width=\"100%\"><a href=\"main.php?SID=$SID&action=$action&do=byteam&team=$id&teamname=$na\">$na</a>&nbsp;\n";
		echo "    </td>\n";
		echo "  </tr>\n";
	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";		
		

		}
		else
		{
		echo "<tr class=\"trrow1\">\n";
		echo "    <td width=\"100%\">\n";
		echo "<p>There are no players with a last name beginning with \"$letter\".</p>\n";

	
		
		// finish off
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";		
	}		
}


function show_byteam_menu($db,$team,$teamname)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">Add a player</a></p>\n";

	// check for empty database

	if (!$db->Exists("SELECT * FROM players")) {
		echo "<p>There are currently no players in the database.</p>\n";
		return;
	} else {

	// Search Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">Search the Player database</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<form action=\"$PHP_SELF\">";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"search\">\n";
	echo "<br><p>Enter first or last name &nbsp;<input type=\"text\" name=\"search\" value=\"$search\" size=\"20\"> <input type=\"submit\" value=\"Search\"></form></p>\n";

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
	// Alpha Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\" bordercolor=\"$greenbdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$greenbdr\" class=\"whitemain\" height=\"23\">Alphabetical Player Listing</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\">\n";

	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=A\">A</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=B\">B</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=C\">C</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=D\">D</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=E\">E</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=F\">F</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=G\">G</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=H\">H</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=I\">I</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=J\">J</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=K\">K</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=L\">L</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=M\">M</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=N\">N</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=O\">O</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=P\">P</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Q\">Q</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=R\">R</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=S\">S</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=T\">T</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=U\">U</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=V\">V</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=W\">W</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=X\">X</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Y\">Y</a>\n";
	echo "<a href=\"main.php?SID=$SID&action=$action&do=byletter&letter=Z\">Z</a>\n";
	

	echo "    </td>\n";
	echo "  </tr>\n";
	echo "</table><br>\n";
	
      	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$yellowbdr\" align=\"center\">\n";
      	echo "<tr>\n";
      	echo "  <td bgcolor=\"$yellowbdr\" class=\"whitemain\" height=\"23\">&nbsp;$teamname Players Listing</td>\n";
      	echo "</tr>\n";
      	echo "<tr>\n";
	  	echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  	echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

		// query database

		if (!$db->Exists("SELECT * FROM players where PlayerTeam=$team")) {

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			echo "	<td align=\"left\" colspan=\"2\">No players for this team</td>\n";
			echo "</tr>\n";

		} else {

		$db->Query("SELECT * FROM players WHERE PlayerTeam=$team ORDER BY isactive, PlayerLName");
		for ($x=0; $x<$db->rows; $x++) {
			$db->GetRow($x);

			// setup variables

			$pln = htmlentities(stripslashes($db->data['PlayerLName']));
			$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
			$ia = htmlentities(stripslashes($db->data[isactive]));
			
			$ip = htmlentities(stripslashes($db->data[IsPresident]));
			$iv = htmlentities(stripslashes($db->data[IsVicePresident]));
			$is = htmlentities(stripslashes($db->data[IsSecretary]));
			$it = htmlentities(stripslashes($db->data[IsTreasurer]));
			$ic = htmlentities(stripslashes($db->data[IsCaptain]));
			$vc = htmlentities(stripslashes($db->data[IsViceCaptain]));

			if($x % 2) {
			  echo "<tr bgcolor=\"#F5F6F6\">\n";
			} else {
			  echo "<tr bgcolor=\"#EEEFEF\">\n";
			}

			// output

			echo "	<td align=\"left\">$pln, $pfn";
			if ($db->data['picture'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture.gif\">";
			if ($db->data['picture1'] != "") echo "&nbsp;<img src=\"/images/icons/icon_picture_action.gif\">";
			if ($db->data[isactive] != 0) echo "&nbsp;<font color=\"red\"><b>Inactive</b></font>\n";
			
			if ($db->data[IsPresident] != 0) echo "&nbsp;<font color=\"green\"><b>President</b></font>\n";
			if ($db->data[IsVicePresident] != 0) echo "&nbsp;<font color=\"green\"><b>Vice President</b></font>\n";
			if ($db->data[IsSecretary] != 0) echo "&nbsp;<font color=\"green\"><b>Secretary</b></font>\n";
			if ($db->data[IsTreasurer] != 0) echo "&nbsp;<font color=\"green\"><b>Treasurer</b></font>\n";
			if ($db->data[IsCaptain] != 0) echo "&nbsp;<font color=\"green\"><b>Captain</b></font>\n";
			if ($db->data[IsViceCaptain] != 0) echo "&nbsp;<font color=\"green\"><b>Vice Captain</b></font>\n";
						
			echo "  </td>\n";
			echo "	<td align=\"right\"><a href=\"main.php?SID=$SID&action=$action&do=sedit&id=" . $db->data['PlayerID'] . "\"><img src=\"/images/icons/icon_edit.gif\" border=\"0\" alt=\"Edit\"></a><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=" . $db->data['PlayerID'] . "\"><img src=\"/images/icons/icon_delete.gif\" border=\"0\" alt=\"Delete\"></a></td>\n";
			echo "</tr>\n";
			}
		}
		echo "</table>\n";
		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table><br>\n";
		
	// Teams Box

    	echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
    	echo "  <tr>\n";
    	echo "    <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Select Team for Player List</td>\n";
    	echo "  </tr>\n";
    	echo "  <tr>\n";
	echo "  <td class=\"trrow1\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	echo "  <table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";

	$db->QueryRow("SELECT * FROM teams WHERE TeamActive=1 ORDER BY TeamName");
	$db->BagAndTag();
	
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
		$id = htmlentities(stripslashes($db->data['TeamID']));
		$na = htmlentities(stripslashes($db->data['TeamName']));
		$ta = htmlentities(stripslashes($db->data['TeamAbbrev']));
		$di = htmlentities(stripslashes($db->data['TeamDirections']));

		// output article

		if($i % 2) {
		  echo "<tr class=\"trrow1\">\n";
		} else {
		  echo "<tr class=\"trrow2\">\n";
		}

		echo "    <td width=\"100%\"><a href=\"main.php?SID=$SID&action=$action&do=byteam&team=$id&teamname=$na\">$na</a>&nbsp;\n";
		echo "    </td>\n";
		echo "  </tr>\n";
	}

		echo "</table>\n";

		echo "  </td>\n";
		echo "</tr>\n";
		echo "</table>\n";
		
	}


}


function add_category_form($db)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Add a player</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sadd\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<p>enter the players last name<br><input type=\"text\" name=\"PlayerLName\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the players first name<br><input type=\"text\" name=\"PlayerFName\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter the players date of birth, place of birth<br><input type=\"text\" name=\"Born\" size=\"40\" maxlength=\"255\"></p>\n";

	echo "<p><select name=\"BattingStyle\">\n";
	echo "	<option value=\"\">Select Batting Style</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	echo "  <option value=\"Right Hand Bat\">Right Hand Bat</option>\n";
	echo "  <option value=\"Left Hand Bat\">Left Hand Bat</option>\n";
	echo "</select></p>\n";

	echo "<p><select name=\"BowlingStyle\">\n";
	echo "	<option value=\"\">Select Bowling Style</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	echo "  <option value=\"Right Arm Fast\">Right Arm Fast</option>\n";
	echo "  <option value=\"Right Arm Fast Medium\">Right Arm Fast Medium</option>\n";
	echo "  <option value=\"Right Arm Medium\">Right Arm Medium</option>\n";
	echo "  <option value=\"Right Arm Slow\">Right Arm Slow</option>\n";
	echo "  <option value=\"Right Arm Off Spin\">Right Arm Off Spin</option>\n";
	echo "  <option value=\"Right Arm Leg Spin\">Right Arm Leg Spin</option>\n";
	echo "  <option value=\"Left Arm Fast\">Left Arm Fast</option>\n";
	echo "  <option value=\"Left Arm Fast Medium\">Left Arm Fast Medium</option>\n";
	echo "  <option value=\"Left Arm Medium\">Left Arm Medium</option>\n";
	echo "  <option value=\"Left Arm Slow\">Left Arm Slow</option>\n";
	echo "  <option value=\"Left Arm Off Spin\">Left Arm Off Spin</option>\n";
	echo "  <option value=\"Left Arm Leg Spin\">Left Arm Leg Spin</option>\n";
	echo "</select></p>\n";

	echo "<p><select name=\"PlayerClub\">\n";
	echo "	<option value=\"\">Club player belongs to</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM clubs")) {
		$db->Query("SELECT * FROM clubs ORDER BY ClubName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['ClubID'] . "\">" . $db->data['ClubName'] . "</option>\n";
		}
	}
	echo "</select></p>\n";

	echo "<p><select name=\"PlayerTeam\">\n";
	echo "	<option value=\"\">Team player belongs to</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	if ($db->Exists("SELECT * FROM teams")) {
		$db->Query("SELECT * FROM teams ORDER BY TeamName");
		for ($i=0; $i<$db->rows; $i++) {
			$db->GetRow($i);
			echo "<option value=\"" . $db->data['TeamID'] . "\">" . $db->data['TeamName'] . "</option>\n";
		}
	}
	echo "</select></p>\n";

	echo "<p>enter the players email address<br><input type=\"text\" name=\"PlayerEmail\" size=\"40\" maxlength=\"255\"></p>\n";
	echo "<p>enter a <b>short</b> profile <i>(300 characters or less)</i><br><textarea name=\"shortprofile\" cols=\"70\" rows=\"15\" wrap=\"virtual\"></textarea></p>\n";

	echo "<input type=\"checkbox\" name=\"IsUmpire\" value=\"1\">Umpire?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsPresident\" value=\"1\">President?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsVicePresident\" value=\"1\">Vice President?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsSecretary\" value=\"1\">Secretary?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsTreasurer\" value=\"1\">Treasurer?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsCaptain\" value=\"1\">Captain?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsViceCaptain\" value=\"1\">Vice Captain?<br>\n";


	echo "<p>upload a player photo<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 380 pixels wide\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";

	echo "<p>upload a player action photo<br><input type=\"file\" name=\"userpic1\" size=\"40\"></p>\n";
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images no larger than 380 pixels wide\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";

	echo "<p><input type=\"submit\" value=\"add player\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";


	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

}


function do_add_category($db,$id,$PlayerLName,$PlayerFName,$PlayerClub,$PlayerTeam,$PlayerEmail,$shortprofile,$IsUmpire,$IsPresident,$IsVicePresident,$IsSecretary,$IsTreasurer,$IsCaptain,$IsViceCaptain,$Born,$BattingStyle,$BowlingStyle,$picture,$picture1)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// setup variables

	$pln = addslashes(trim($PlayerLName));
	$pfn = addslashes(trim($PlayerFName));
	$pcl = addslashes(trim($PlayerClub));
	$pte = addslashes(trim($PlayerTeam));
	$pem = addslashes(trim($PlayerEmail));
	$spr = addslashes(trim($shortprofile));

	$ump = addslashes(trim($IsUmpire));
	$pre = addslashes(trim($IsPresident));
	$vpr = addslashes(trim($IsVicePresident));
	$sec = addslashes(trim($IsSecretary));
	$tre = addslashes(trim($IsTreasurer));
	$cap = addslashes(trim($IsCaptain));
	$vca = addslashes(trim($IsViceCaptain));

	$bor = addslashes(trim($Born));
	$bat = addslashes(trim($BattingStyle));
	$bow = addslashes(trim($BowlingStyle));


	$pa = eregi_replace("\r","",$photo);

	// all okay

	$db->Insert("INSERT INTO players (PlayerLName,PlayerFName,PlayerClub,PlayerTeam,PlayerEmail,shortprofile,IsUmpire,IsPresident,IsVicePresident,IsSecretary,IsTreasurer,IsCaptain,IsViceCaptain,Born,BattingStyle,BowlingStyle,picture,picture1) VALUES ('$pln','$pfn','$pcl','$pte','$pem','$spr','$ump','$pre','$vpr','$sec','$tre','$cap','$vca','$bor','$bat','$bow','$picture','$picture1')");
	if ($db->a_rows != -1) {
		echo "<p>You have now added a new player</p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sadd\">add another player</a></p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to players list</a></p>\n";
	} else {
		echo "<p>The player could not be added to the database at this time.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to players list</a></p>\n";
	}
}


function delete_category_check($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// query the database with title variable

	$player = htmlentities(stripslashes($db->QueryItem("SELECT PlayerLName FROM players WHERE PlayerID=$id")));

	// output

	echo "<p>Are you sure you wish to delete the player</p>\n";
	echo "<p><b>$player</b></p>\n";
	echo "<p><a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=1\">YES</a> | <a href=\"main.php?SID=$SID&action=$action&do=sdel&id=$id&doit=0\">NO</a></p>\n";
}


function do_delete_category($db,$id,$doit)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// cancel delete

	if (!$doit) echo "<p>You have chosen NOT to delete that player.</p>\n";
	else {

	// do delete

		$db->Delete("DELETE FROM players WHERE PlayerID=$id");
		echo "<p>You have now deleted that player.</p>\n";
	}
	echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the players listing</a></p>\n";
}


function edit_category_form($db,$id)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

	// get all teams
	$db->Query("SELECT * FROM teams ORDER BY TeamName");
	for ($i=0; $i<$db->rows; $i++) {
		$db->GetRow($i);
        $db->BagAndTag();
		$teams[$db->data['TeamID']] = $db->data['TeamName'];
	}

	// get all clubs
	$db->Query("SELECT * FROM clubs ORDER BY ClubName");
	for ($c=0; $c<$db->rows; $c++) {
		$db->GetRow($c);
        $db->BagAndTag();
		$clubs[$db->data['ClubID']] = $db->data['ClubName'];
	}

	// query database

	$db->QueryRow("SELECT * FROM players WHERE PlayerID=$id");

	// setup variables

	$pln = htmlentities(stripslashes($db->data['PlayerLName']));
	$pfn = htmlentities(stripslashes($db->data['PlayerFName']));
	$pem = htmlentities(stripslashes($db->data['PlayerEmail']));
	$spr = htmlentities(stripslashes($db->data['shortprofile']));

	$ump = $db->data[IsUmpire];
	$pre = $db->data[IsPresident];
	$vpr = $db->data[IsVicePresident];
	$sec = $db->data[IsSecretary];
	$tre = $db->data[IsTreasurer];
	$cap = $db->data[IsCaptain];
	$vca = $db->data[IsViceCaptain];

	$bor = $db->data['Born'];
	$bat = $db->data['BattingStyle'];
	$bow = $db->data['BowlingStyle'];
	
	$ip = stripslashes($db->data[isactive]);
	$ipyes = 'yes';
	$ipno = 'no';
	if ($db->data[isactive] ==0) $ip1 = $ipyes;
	if ($db->data[isactive] ==1) $ip1 = $ipno;	

      echo "<table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\" bordercolor=\"$bluebdr\" align=\"center\">\n";
      echo "<tr>\n";
      echo "  <td bgcolor=\"$bluebdr\" class=\"whitemain\" height=\"23\">&nbsp;Edit Player</td>\n";
      echo "</tr>\n";
      echo "<tr>\n";
	  echo "<td bgcolor=\"#FFFFFF\" valign=\"top\" bordercolor=\"#FFFFFF\" class=\"main\" colspan=\"2\">\n";

	  echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"3\">\n";
	  echo "  <tr>\n";
	  echo "    <td>\n";

	echo "<form action=\"main.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	echo "<input type=\"hidden\" name=\"SID\" value=\"$SID\">\n";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">\n";
	echo "<input type=\"hidden\" name=\"do\" value=\"sedit\">\n";
	echo "<input type=\"hidden\" name=\"doit\" value=\"1\">\n";
	echo "<input type=\"hidden\" name=\"old\" value=\"$t\">\n";
	echo "<input type=\"hidden\" name=\"id\" value=\"$id\">\n";
	
	echo "<p>enter the players first name<br><input type=\"text\" name=\"PlayerFName\" size=\"40\" maxlength=\"255\" value=\"$pfn\"></p>\n";
	echo "<p>enter the players last name<br><input type=\"text\" name=\"PlayerLName\" size=\"40\" maxlength=\"255\" value=\"$pln\"></p>\n";
	echo "<p>enter the players date of birth, place of birth<br><input type=\"text\" name=\"Born\" size=\"40\" maxlength=\"255\" value=\"$bor\"></p>\n";

	echo "<p>Is this player an active player?</p>\n";
	echo "<select name=\"isactive\">\n";
	echo "	<option value=\"$ip\">$ip1</option>\n";
	echo "	<option value=\"\">========or choose=======</option>\n";
	echo "	<option value=\"0\">yes</option>\n";
	echo "	<option value=\"1\">no</option>\n";
	echo "</select>\n";


	echo "<p><select name=\"BattingStyle\">\n";
	echo "	<option value=\"$bat\">$bat</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	echo "  <option value=\"Right Hand Bat\">Right Hand Bat</option>\n";
	echo "  <option value=\"Left Hand Bat\">Left Hand Bat</option>\n";
	echo "</select></p>\n";

	echo "<p><select name=\"BowlingStyle\">\n";
	echo "	<option value=\"$bow\">$bow</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";
	echo "  <option value=\"Right Arm Fast\">Right Arm Fast</option>\n";
	echo "  <option value=\"Right Arm Fast Medium\">Right Arm Fast Medium</option>\n";
	echo "  <option value=\"Right Arm Medium\">Right Arm Medium</option>\n";
	echo "  <option value=\"Right Arm Slow\">Right Arm Slow</option>\n";
	echo "  <option value=\"Right Arm Off Spin\">Right Arm Off Spin</option>\n";
	echo "  <option value=\"Right Arm Leg Spin\">Right Arm Leg Spin</option>\n";
	echo "  <option value=\"Left Arm Fast\">Left Arm Fast</option>\n";
	echo "  <option value=\"Left Arm Fast Medium\">Left Arm Fast Medium</option>\n";
	echo "  <option value=\"Left Arm Medium\">Left Arm Medium</option>\n";
	echo "  <option value=\"Left Arm Slow\">Left Arm Slow</option>\n";
	echo "  <option value=\"Left Arm Off Spin\">Left Arm Off Spin</option>\n";
	echo "  <option value=\"Left Arm Leg Spin\">Left Arm Leg Spin</option>\n";
	echo "</select></p>\n";

	echo "<p>select players club:<br>\n";
	echo "<select name=\"PlayerClub\">\n";
	echo "	<option value=\"\">Select Club</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

		for ($c=1; $c<=count($clubs); $c++) {
			echo "<option value=\"$c\"" . ($c==$db->data[PlayerClub]?" selected":"") . ">" . $clubs[$c] . "</option>\n";
		}

	echo "</select></p>\n";

	echo "<p>select players team:<br>\n";
	echo "<select name=\"PlayerTeam\">\n";
	echo "	<option value=\"\">Select Team</option>\n";
	echo "	<option value=\"\">--------------------------</option>\n";

		for ($i=1; $i<=count($teams); $i++) {
			echo "<option value=\"$i\"" . ($i==$db->data[PlayerTeam]?" selected":"") . ">" . $teams[$i] . "</option>\n";
		}

	echo "</select></p>\n";

	echo "<p>enter the players email<br><input type=\"text\" name=\"PlayerEmail\" size=\"40\" maxlength=\"255\" value=\"$pem\"></p>\n";
	echo "<p>enter a <b>short</b> profile <i>(300 characters or less)</i><br><textarea name=\"shortprofile\" cols=\"70\" rows=\"15\" wrap=\"virtual\">$spr</textarea></p>\n";

	echo "<input type=\"checkbox\" name=\"IsUmpire\" value=\"1\"" . ($ump==1?" checked":"") . "> Is this player an umpire?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsPresident\" value=\"1\"" . ($pre==1?" checked":"") . "> Is this player president?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsVicePresident\" value=\"1\"" . ($vpr==1?" checked":"") . "> Is this player vice president?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsSecretary\" value=\"1\"" . ($sec==1?" checked":"") . "> Is this player secretary?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsTreasurer\" value=\"1\"" . ($tre==1?" checked":"") . "> Is this player treasurer?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsCaptain\" value=\"1\"" . ($cap==1?" checked":"") . "> Is this player captain?<br>\n";
	echo "<input type=\"checkbox\" name=\"IsViceCaptain\" value=\"1\"" . ($vca==1?" checked":"") . "> Is this player vice captain?<br>\n";

	if ($db->data['picture']) {
		echo "<p>current player photo</p>\n";
		echo "<p><img src=\"../uploadphotos/players/" . $db->data['picture'] . "\"></p>\n";
		echo "<p>upload a player photo (if you want to change the current one)";
	} else {
		echo "<p>upload a player photo";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images at 150 pixels wide and as a portrait\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<br><input type=\"file\" name=\"userpic\" size=\"40\"></p>\n";

	if ($db->data['picture1']) {
		echo "<p>current player action photo</p>\n";
		echo "<p><img src=\"../uploadphotos/players/action/" . $db->data['picture1'] . "\"></p>\n";
		echo "<p>upload a player action photo (if you want to change the current one)";
	} else {
		echo "<p>upload a player action photo";
	}
	echo "<p><ul><li>please make sure images have a unique name (eg. imagename001.jpg)\n";
	echo "<li>please save images at 380 pixels wide and as a landscape\n";
	echo "<li>only GIF and JPG files only please.</ul></p>\n";
	echo "<br><input type=\"file\" name=\"userpic1\" size=\"40\"></p>\n";


	echo "<p><input type=\"submit\" value=\"edit players\"> &nbsp; <input type=\"reset\" value=\"reset form\"></p>\n";
	echo "</form>\n";
	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";

	echo "  </td>\n";
	echo "</tr>\n";
	echo "</table>\n";
}


function do_update_category($db,$id,$PlayerLName,$PlayerFName,$PlayerClub,$PlayerTeam,$PlayerEmail,$shortprofile,$IsUmpire,$IsPresident,$IsVicePresident,$IsSecretary,$IsTreasurer,$IsCaptain,$IsViceCaptain,$Born,$BattingStyle,$BowlingStyle,$isactive,$setpic,$setpic1)
{
	global $content,$action,$SID,$bluebdr,$greenbdr,$yellowbdr;

// setup the variables

	$pln = addslashes(trim($PlayerLName));
	$pfn = addslashes(trim($PlayerFName));
	$pcl = addslashes(trim($PlayerClub));
	$pte = addslashes(trim($PlayerTeam));
	$pem = addslashes(trim($PlayerEmail));
	$spr = addslashes(trim($shortprofile));

	$ump = addslashes(trim($IsUmpire));
	$pre = addslashes(trim($IsPresident));
	$vpr = addslashes(trim($IsVicePresident));
	$sec = addslashes(trim($IsSecretary));
	$tre = addslashes(trim($IsTreasurer));
	$cap = addslashes(trim($IsCaptain));
	$vca = addslashes(trim($IsViceCaptain));

	$bor = addslashes(trim($Born));
	$bat = addslashes(trim($BattingStyle));
	$bow = addslashes(trim($BowlingStyle));
	
	$ip = addslashes(trim($isactive));

	$pa = eregi_replace("\r","",$photo);

	// query database

	$db->Update("UPDATE players SET PlayerLName='$pln',PlayerFName='$pfn',PlayerClub='$pcl',PlayerTeam='$pte',PlayerEmail='$pem',shortprofile='$spr',IsUmpire='$ump',IsPresident='$pre',IsVicePresident='$vpr',IsSecretary='$sec',IsTreasurer='$tre',IsCaptain='$cap',IsViceCaptain='$vca',Born='$bor',BattingStyle='$bat',BowlingStyle='$bow',isactive='$ip'$setpic$setpic1 WHERE PlayerID=$id");
		echo "<p>You have now updated that player.</p>\n";
		echo "<p>&laquo; <a href=\"main.php?SID=$SID&action=$action\">return to the players listing</a></p>\n";
		echo "<p>&raquo; <a href=\"main.php?SID=$SID&action=$action&do=sedit&id=$id\">update $pln some more</a></p>\n";
}


// do picture stuff here - doesn't like being passed to a function!
if ($_FILES['userpic']['name'] != "") {
  $uploaddir = "../uploadphotos/players/";
  $basename = basename($_FILES['userpic']['name']);
  $uploadfile = $uploaddir . $basename;

  if (move_uploaded_file($_FILES['userpic']['tmp_name'], $uploadfile)) {
    $setpic = ",picture='$basename'";
  } else {
    echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
  }
}
else
{
  $picture = "";
  $setpic = "";
}

// do picture stuff here - doesn't like being passed to a function!
if ($_FILES['userpic1']['name'] != "") {
  $uploaddir1 = "../uploadphotos/players/action/";
  $basename1 = basename($_FILES['userpic1']['name']);
  $uploadfile1 = $uploaddir1 . $basename1;

  if (move_uploaded_file($_FILES['userpic1']['tmp_name'], $uploadfile1)) {
    $setpic1 = ",picture1='$basename1'";
  } else {
    echo "<p>That photo could not be uploaded at this time - no photo was added to the database.</p>\n";
  }
}
else
{
  $picture1 = "";
  $setpic1 = "";
}

// main program

if (!$USER[flags][$f_player_admin]) {
	header("Location: main.php?SID=$SID");
	exit;
}

echo "<p class=\"16px\"><b>Player Administration</b></p>\n";

switch($do) {
case "search":
	show_search_menu($db,$search);
	break;
case "byteam":
	show_byteam_menu($db,$team,$teamname);
	break;
case "byletter":
	show_byletter_menu($db,$letter);
	break;
case "sadd":
	if (!isset($doit)) add_category_form($db);
	else do_add_category($db,$id,$PlayerLName,$PlayerFName,$PlayerClub,$PlayerTeam,$PlayerEmail,$shortprofile,$IsUmpire,$IsPresident,$IsVicePresident,$IsSecretary,$IsTreasurer,$IsCaptain,$IsViceCaptain,$Born,$BattingStyle,$BowlingStyle,$picture,$picture1);
	break;
case "sdel":
	if (!isset($doit)) delete_category_check($db,$id);
	else do_delete_category($db,$id,$doit);
	break;
case "sedit":
	if (!isset($doit)) edit_category_form($db,$id);
	else do_update_category($db,$id,$PlayerLName,$PlayerFName,$PlayerClub,$PlayerTeam,$PlayerEmail,$shortprofile,$IsUmpire,$IsPresident,$IsVicePresident,$IsSecretary,$IsTreasurer,$IsCaptain,$IsViceCaptain,$Born,$BattingStyle,$BowlingStyle,$isactive,$setpic,$setpic1);
	break;
default:
	show_main_menu($db);
	break;
}

?>
