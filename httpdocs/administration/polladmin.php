<?php

//------------------------------------------------------------------------------
// Poll Administration v2.0
//
// (c) Andy Collington - andy@amnuts.com
// (c) Michael Doig    - mdoig@coastart.net
// For Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------


require "../poll/pollconfig.php";

	$connection = mysql_connect($dbhost, $dbusername, $dbpassword);
	$content = mysql_db_query($dbname, "SELECT * FROM pollcontent");
	$ContentRow = mysql_fetch_row($content);
	$Question = $ContentRow[2];

	// number of selections

	$num = 0;

	// Get current values of fields

	for ($x=3; $x<=22; $x++)
	{
		if (trim($ContentRow[$x]) != "")
		{
			$Selection[$x-3] = $ContentRow[$x];
			$num++;
		}
	}

	// form actions

	if ($Submit == "Create new poll")
	{
		$Create = true;
		$Status = "inactive";
		$num = 20;
		$Question = "";

		// Set everything to null

		for ($x=0; $x<=$num; $x++)
		{
			$Selection[$x] = "";
		}

		$DeleteContent = mysql_db_query($dbname, "DELETE FROM pollcontent");
		$DeleteResults = mysql_db_query($dbname, "UPDATE pollresult SET Hits=0");
	}
	elseif ($Submit == "Disable Poll and Modify")
	{
		$Modify = true;
		$Status = "inactive";
		$UpdateStatus = mysql_db_query($dbname, "UPDATE pollcontent SET Status='inactive'");
	}
	elseif ($Submit == "Save Settings")
	{
		$Saved = true;
		$Status = "active";

		if ($num==0)
		{
			$NewPoll = mysql_db_query($dbname, "INSERT INTO pollcontent VALUES ('','Poll','$PollQuestion',
							'$Selected[0]','$Selected[1]','$Selected[2]','$Selected[3]','$Selected[4]',
							'$Selected[5]','$Selected[6]','$Selected[7]','$Selected[8]','$Selected[9]',
							'$Selected[10]','$Selected[11]','$Selected[12]','$Selected[13]','$Selected[14]',
							'$Selected[15]','$Selected[16]','$Selected[17]','$Selected[18]','$Selected[19]',
							'active')");

			// Get new value for number of selection

			$Question = $PollQuestion;
			for ($x=0; $x<=19; $x++)
			{
				if ($Selected[$x] != "")
				{
					$Selection[$x] = $Selected[$x];
					$num++;
				}
			}
		}
		else
		{
		$db = mysql_select_db ($dbname);
		$UpdateStatus = mysql_query("UPDATE pollcontent SET Question='$PollQuestion', Selection1='$Selected[0]',
							Selection2='$Selected[1]', Selection3='$Selected[2]', Selection4='$Selected[3]',
							Selection5='$Selected[4]', Selection6='$Selected[5]', Selection7='$Selected[6]',
							Selection8='$Selected[7]', Selection9='$Selected[8]', Selection10='$Selected[9]',
							Selection11='$Selected[10]', Selection12='$Selected[11]', Selection13='$Selected[12]',
							Selection14='$Selected[13]', Selection15='$Selected[14]', Selection16='$Selected[15]',
							Selection17='$Selected[16]', Selection18='$Selected[17]', Selection19='$Selected[18]',
							Selection20='$Selected[19]', Status='active'");

		// Update values in variable

		$Question = $PollQuestion;

			for ($x=0; $x<=$num-1; $x++)
			{
				$Selection[$x] = $Selected[$x];
			}

		}
	}

	$Target = getenv("PHP_SELF");

	echo "<p class=\"header\"><b>Poll Administration</b></p>\n";
	echo "<form name=\"Content\" method=\"post\" action=\"$Target\">\n";

 	echo " <table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" align=\"center\">\n";
 	echo "   <tr class=\"trtop\">\n";
 	echo "     <td colspan=\"2\"> \n";
 	echo "       <b><span class=\"trtopfont\">Todays Poll</span></b>\n";
 	echo "     </td>\n";
 	echo "   </tr>\n";


	// After saving settings

	echo "<tr class=\"trbottom\">";
   	echo "  <td colspan=\"2\">";

	if ($Saved)
	{
		echo "<i><b>Content settings were successfully saved.<b><i>";
	}
	elseif ($Create)
	{
		echo "<i><b>Previous poll was deleted. Please input new poll content and save settings to activate.<b><i>";
	}
	elseif ($Modify)
	{
		echo "<i><b>Poll is now INACTIVE. Save settings to activate.<b><i>";
	}

	echo "  </td>";
    	echo "</tr>";


	echo "<tr class=\"trbottom\">\n";
     	echo " <td colspan=\"2\"> \n";
     	echo "   <input type=\"submit\" name=\"Submit\" value=\"Create new poll\">\n";
    	//echo "    <input type=\"submit\" name=\"Submit\" value=\"Disable Poll and Modify\">\n";
    	echo "  </td>\n";
   	echo " </tr>\n";


	// Print question

	echo "<tr class=\"trbottom\">\n";
	echo "  <td width=\"25%\"><br>Poll Question</td>\n";
	echo "  <td width=\"75%\"><br><input type=\"text\" name=\"PollQuestion\" size=\"30\" maxlength=\"60\" value=\"$Question\"></td>\n";
	echo "</tr>\n";

	echo "<tr class=\"trbottom\">\n";
	echo "  <td colspan=\"2\">&nbsp;</td>\n";
	echo "</tr>\n";


	// Print selections

	for ($x=0; $x<=$num-1; $x++)
	{
		$count = $x+1;

		echo "<tr class=\"trbottom\">\n";
		echo "  <td width=\"25%\" height=\"24\">Selection $count</td>\n";
		echo "  <td width=\"75%\" height=\"24\"><input type=\"text\" name=\"Selected[$x]\" size=\"30\" maxlength=\"60\" value=\"$Selection[$x]\"></td>\n";
		echo "</tr>\n";
	}


	echo "    <tr class=\"trbottom\"> \n";
	echo "      <td colspan=\"2\"> \n";
	echo "		<input type=\"submit\" name=\"Submit\" value=\"Save Settings\">\n";
	echo "          	<input type=\"reset\" name=\"Reset\" value=\"Reset\">\n";
	echo "      </td>\n";
	echo "    </tr>\n";
	echo "    <tr class=\"trbottom\">\n";
	echo "      <td colspan=\"2\">\n";
	echo "      </td>\n";
	echo "    </tr>\n";
	echo "  </table>\n";
	echo "</form>\n";


?>
