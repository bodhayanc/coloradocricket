<?php 
/*
ExtCalendar v2
Copyright (C) 2003-2004 Mohamed Moujami (Simo)

This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; either version 2 of the License, or 
(at your option) any later version. 
This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
GNU General Public License for more details. 

You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

Date Started : 21/08/2002
Date Last Updated : 02/10/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : Installation file

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

// Report all errors except E_NOTICE
error_reporting (E_ALL ^ E_NOTICE);

// Process SQL files
require_once('include/sql_parse.php');
require_once('include/dblib.php');

// Install version info
define('RELEASE_NAME','2.0 Beta 1');
define('RELEASE_VERSION','200.25');
define('RELEASE_TYPE','beta');

$DB_DEBUG = false;

// Unescape special characters if enabled  by default.
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value)
	{
		$char_array = array('"' => '&quot;', '<' => '&lt;', '>' => '&gt;');

		$value = is_array($value) ?array_map('stripslashes_deep', $value) : strtr(stripslashes($value), $char_array);
		return $value;
	}
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

// ---------------------------- TEST PREREQUIRED --------------------------- //
function test_fs()
{
    global $errors, $DFLT; 
    // include must be writable to create config file
    if (! is_dir($DFLT['cfg_d'])) {
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;A subdirectory called '{$DFLT['cfg_d']}' should normally exist in the directory where you uploaded ExtCalendar. The installer can't find this directory. Check that you have uploaded all ExtCalendar files to your server.<br />";
    } elseif (! is_writable($DFLT['cfg_d'])) {
        if(!@chmod($DFLT['cfg_d'], 0777)) {
	        $errors .= "<span style='color:red'>&middot;</span>&nbsp;The '{$DFLT['cfg_d']}' directory (located in the directory where you uploaded ExtCalendar) should be writable in order to save your configuration. Use your FTP program to change its mode to 777.<br />";
    		}
    } 
    // uploads must be writable to upload pictures
    if (! is_dir($DFLT['upl_d'])) {
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;A subdirectory called '{$DFLT['upl_d']}' should normally exist in the directory where you uploaded ExtCalendar. The installer can't find this directory. Check that you have uploaded all ExtCalendar files to your server.<br />";
    } elseif (! is_writable($DFLT['upl_d'])) {
        if(!@chmod($DFLT['upl_d'], 0777)) {
        	$errors .= "<span style='color:red'>&middot;</span>&nbsp;The '{$DFLT['upl_d']}' directory (located in the directory where you uploaded ExtCalendar) should be writable in order to allow pictures upload. Use your FTP program to change its mode to 777.<br />";
        }
    } 
} 
// ----------------------------- TEST FUNCTIONS ---------------------------- //
function test_table_exists($database, $tableName)
{
   $tables = array();
   $tablesResult = mysql_list_tables($database);
   while ($row = db_fetch_row($tablesResult)) $tables[] = $row[0];
   return(in_array($tableName, $tables));
} 

function test_sql_connection()
{
    global $errors;

    if (! $connect_id = @mysql_connect($_POST['dbserver'], $_POST['dbuser'], $_POST['dbpass'])) {
        $errors .= "<span style='color:red'>&middot;</span> Could not create a DB connection, please check the SQL values entered<br /><br />Database error was : " . mysql_error() . "<br />";
    } elseif (! mysql_select_db($_POST['dbname'], $connect_id)) {
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;The database system could not locate a database called '{$_POST['dbname']}' please check the value entered for this<br />";
    }
} 

function test_admin_login()
{
    global $errors;

    if ($_POST['admin_username'] == '' || $_POST['admin_password'] == '')
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;It is required to provide a 'username' and a 'password' for the admin.<br />";
    if (!preg_match('/\A\w*\Z/', $_POST['admin_username']) || !preg_match('/\A\w*\Z/', $_POST['admin_password']))
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;Admin username and password must only contain alphanumeric characters.<br />";
} 

// Test is safe_mode is misconfigured
function test_silly_safe_mode()
{
    global $DFLT;

    $test_file = "{$DFLT['upl_d']}/dummy/dummy.txt";
    @mkdir(dirname($test_file), 0755);
    $fd = @fopen($test_file, 'w');
    if (!$fd) {
        @rmdir(dirname($test_file));
        return true;
    } 
    fclose($fd);
    @unlink($test_file);
    @rmdir(dirname($test_file));
} 

// ------------------------- HTML OUTPUT FUNCTIONS ------------------------- //
function html_header()
{

    ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>ExtCalendar 2 - Installation</title>
<link type="text/css" rel="stylesheet" href="installer.css">
</head>
<body>
 <div align="center">
 	<div style="width:600px;">
<?php
} 

function html_logo()
{

    ?>
        <div align="center"><img src="themes/default/images/banner.png" vspace="10"></div>

<?php
} 

function display_license()
{
    global $DFLT;

		$cal_name = "ExtCalendar";

	  if (file_exists("LICENSE")) {
	      $license_file = "LICENSE";
	  } else echo "<strong>ExtCalendar critical error</strong>:<br />Unable to load the file '".$license_file."' !";
	  $license_txt = fread(fopen($license_file, 'r'), filesize($license_file));

		echo <<<EOT
		 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
			<tr>
			  <form action="install.php" method="post">
			  <td class="tableh1" colspan="2"><h2>Welcome to ExtCalendar 2 installation</h2>
			  </td>
			</tr>
			<tr>
			  <td class="tableb" colspan="2"> 
					$cal_name is a free open source calendar application, distributed under the GPL license. All your rights and obligations
				  are described in the GPL license, printed below. If you want to read this text later on, you can find another copy
				  in the $cal_name directory.			  
        </td>
      </tr>
			<tr>
				<td class='tableb' colspan='2' align='center'>
					<div style='width:100%; background-color: #FFFFFF; font-family: "Lucida Console", "Courier New", Courier, monospace; border:1px #A8B0B0 solid;padding:12px;text-align:left;height:270px;overflow:auto'>
						<pre style='font-family: "Lucida Console", "Courier New", Courier, monospace;'>$license_txt</pre>
					</div>
				</td>
			</tr>
       <tr>
        <td colspan="2" align="center" class="tablec"><br />
                <input type="submit" class="button" value="Proceed >"><br /><br />
        </td>
       </tr>
      </table>
EOT;

} 

function html_installer_locked()
{
    global $DFLT;

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
            <form action="index.php">
        <td class="tableh1" colspan="2"><h2>The installer is locked</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"><img src="images/errormessage.gif" align="absmiddle">&nbsp;<strong>Errors</strong>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2">The installer has already been run successfuly once and is now locked.<br /><br />If you want to run the installer again, you first need to delete the '<?php echo $DFLT['lck_f'] ?>' file that was created in the directory where you put ExtCalendar. You can do this with any FTP program.
        </td>
       </tr>
       <tr>
        <td colspan="2" align="center" class="tablec"><br />
                <input type="submit" class="button" value="Go to the main page"><br /><br />
        </td>
       </tr>
      </table>
<?php
} 

function html_prereq_errors($error_msg)
{

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
            <form action="install.php">
        <td class="tableh1" colspan="2"><h2>Welcome to ExtCalendar installation</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"><img src="images/errormessage.gif" align="absmiddle">&nbsp;<strong>Errors</strong></td>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"> Before you continue with ExtCalendar installation, there are some problems that need to be fixed.<br /><br /><?php echo $error_msg ?><br />Once you are done, hit the "Try again" button.<br />
        </td>
       </tr>
       <tr>
        <td colspan="2" align="center" class="tablec"><br />
                <input type="submit" class="button" value="Try again !"><br /><br />
        </td>
       </tr>
      </table>
<?php
} 

function html_input_config($error_msg = '')
{
	global $timezone, $detect_fs, $detect_url;
    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
            <form action="install.php" method="post">
        <td class="tableh1" colspan="2"><h2>Welcome to ExtCalendar installation</h2>
        </td>
       </tr>
<?php
    if ($error_msg) {

        ?>
       <tr>
        <td class="tableb" colspan="2"><img src="images/errormessage.gif" align="absmiddle">&nbsp;<strong>Errors</strong>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"> The following errors were encountered and need to be corrected first:<br /><br /><div class="atomic"><?php echo $error_msg ?></div><br />
        </td>
       </tr>
<?php
    } 

    ?>
       <tr>
        <td class="tableh2" colspan="2"><b>Your admin account</b>
        </td>
       </tr>
       <tr>
        <td class="tablec" colspan="2"><div class="atomic" style="float: left;"><img src='themes/default/images/icon-info.gif' align='absmiddle' hspace="2"></div><div class="atomic" style="float: left;">This section requires information to create your administration account. Use only alphanumeric characters. Enter the data carefully !</div>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Username</b>
        </td>
        <td width="60%" class="tableb">
                <input type='text' class='textinput' name='admin_username' value='<?php echo $_POST['admin_username'] ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Password</b>
        </td>
        <td width="60%" class="tableb">
                <input type='text' class='textinput' name='admin_password' value='<?php echo $_POST['admin_password'] ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Email</b>
        </td>
        <td width="60%" class="tableb">
                <input type='text' class='textinput' name='admin_email' value='<?php echo $_POST['admin_email'] ?>'>
        </td>
       </tr>
       <tr>
        <td class="tableh2" colspan="2"><b>Your Database configuration</b>
        </td>
       </tr>
       <tr>
        <td class="tablec" colspan="2"><div class="atomic" style="float: left;"><img src='themes/default/images/icon-info.gif' align='absmiddle' hspace="2"></div><div class="atomic" style="float: left;">This section requires information on how to access your MySQL database. If you don't know how to fill them, check with your webhost support.</div>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Database System</b><br />
        </td>
        <td width="60%" class="tableb" valign="top">
          <select name='dbsystem' class='listbox'>
          	<option value='mysql' <? echo ($_POST['dbsystem'] == 'mysql')?'selected':'';?>>MySQL</option>
          </select>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Database Host</b>
        </td>
        <td width="60%" class="tableb" valign="top">
                <input type='text' class='textinput' name='dbserver' value='<?php echo ($_POST['dbserver'] ? $_POST['dbserver'] : 'localhost') ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Database Name</b>
        </td>
        <td width="60%" class="tableb">
                <input type='text' class='textinput' name='dbname' value='<?php echo $_POST['dbname'] ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Database Username</b>
        </td>
        <td width="60%" class="tableb">
                <input type='text' class='textinput' name='dbuser' value='<?php echo $_POST['dbuser'] ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Database Password</b>
        </td>
        <td width="60%" class="tableb">
                <input type='text' class='textinput' name='dbpass' value='<?php echo $_POST['dbpass'] ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Database table prefix</b><br />(default value is OK)
        </td>
        <td width="60%" class="tableb" valign="top">
                <input type='text' class='textinput' name='table_prefix' value='<?php echo ($_POST['table_prefix'] ? $_POST['table_prefix'] : 'extcal_') ?>'>
        </td>
       </tr>
       <tr>
        <td class="tableh2" colspan="2"><b>Your File System configuration</b>
        </td>
       </tr>
       <tr>
        <td class="tablec" colspan="2"><div class="atomic" style="float: left;"><img src='themes/default/images/icon-info.gif' align='absmiddle' hspace="2"></div><div class="atomic" style="float: left;">This section requires information used to define paths for the calendar application. If you don't know how to fill them, check with your webhost support.</div>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>File System Path</b>
        </td>
        <td width="60%" class="tableb" valign="top">
                <input type='text' class='textinput' name='fs_path' value='<?php echo ($_POST['fs_path'] ? $_POST['fs_path'] : $detect_fs) ?>'>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Calendar URL</b>
        </td>
        <td width="60%" class="tableb" valign="top">
                <input type='text' class='textinput' name='url_path' value='<?php echo ($_POST['url_path'] ? $_POST['url_path'] : $detect_url) ?>'>
        </td>
       </tr>
       <tr>
        <td class="tableh2" colspan="2"><b>Calendar settings</b>
        </td>
       </tr>
       <tr>
        <td class="tablec" colspan="2"><div class="atomic" style="float: left;"><img src='themes/default/images/icon-info.gif' align='absmiddle' hspace="2"></div><div class="atomic" style="float: left;">This section gather some necessary information for the calendar to function correctly.</div>
        </td>
       </tr>
       <tr>
        <td width="40%" class="tableb"><b>Time Zone Offset</b>
        </td>
        <td width="60%" class="tableb" valign="top">
			 
	<select name="timezone" class="listbox">
		<option value="-12" <? echo ($timezone == '-12')?"selected":"";?>>(GMT -12:00) Eniwetok, Kwajalein</option>
		<option value="-11" <? echo ($timezone == '-11')?"selected":"";?>>(GMT -11:00) Midway Island, Samoa</option>
		<option value="-10" <? echo ($timezone == '-10')?"selected":"";?>>(GMT -10:00) Hawaii</option>
		<option value="-9" <? echo ($timezone == '-9')?"selected":"";?>>(GMT -9:00) Alaska</option>
		<option value="-8" <? echo ($timezone == '-8')?"selected":"";?>>(GMT -8:00) Pacific Time (US & Canada)</option>
		<option value="-7" <? echo ($timezone == '-7')?"selected":"";?>>(GMT -7:00) Mountain Time (US & Canada)</option>
		<option value="-6" <? echo ($timezone == '-6')?"selected":"";?>>(GMT -6:00) Central Time (US & Canada), Mexico City</option>
		<option value="-5" <? echo ($timezone == '-5')?"selected":"";?>>(GMT -5:00) Eastern Time (US & Canada), Bogota, New York</option>
		<option value="-4" <? echo ($timezone == '-4')?"selected":"";?>>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
		<option value="-3.5" <? echo ($timezone == '-3.5')?"selected":"";?>>(GMT -3:30) Newfoundland</option>
		<option value="-3" <? echo ($timezone == '-3')?"selected":"";?>>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
		<option value="-2" <? echo ($timezone == '-2')?"selected":"";?>>(GMT -2:00) Mid-Atlantic</option>
		<option value="-1" <? echo ($timezone == '-1')?"selected":"";?>>(GMT -1:00) Azores, Cape Verde Islands</option>
		<option value="0" <? echo ($timezone == '0')?"selected":"";?>>(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia</option>
		<option value="+1" <? echo ($timezone == '+1')?"selected":"";?>>(GMT +1:00) CET(Central Europe Time), Brussels, Madrid, Paris</option>
		<option value="+2" <? echo ($timezone == '+2')?"selected":"";?>>(GMT +2:00) EET(Eastern Europe Time), Kaliningrad, South Africa</option>
		<option value="+3" <? echo ($timezone == '+3')?"selected":"";?>>(GMT +3:00) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg</option>
		<option value="+3.5" <? echo ($timezone == '+3.5')?"selected":"";?>>(GMT +3:30) Tehran</option>
		<option value="+4" <? echo ($timezone == '+4')?"selected":"";?>>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
		<option value="+4.5" <? echo ($timezone == '+4.5')?"selected":"";?>>(GMT +4:30) Kabul</option>
		<option value="+5" <? echo ($timezone == '+5')?"selected":"";?>>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
		<option value="+5.5" <? echo ($timezone == '+5.5')?"selected":"";?>>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
		<option value="+6" <? echo ($timezone == '+6')?"selected":"";?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
		<option value="+7" <? echo ($timezone == '+7')?"selected":"";?>>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
		<option value="+8" <? echo ($timezone == '+8')?"selected":"";?>>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
		<option value="+9" <? echo ($timezone == '+9')?"selected":"";?>>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
		<option value="+9.5" <? echo ($timezone == '+9.5')?"selected":"";?>>(GMT +9:30) Adelaide, Darwin</option>
		<option value="+10" <? echo ($timezone == '+10')?"selected":"";?>>(GMT +10:00) EAST(East Australian Standard), Guam </option>
		<option value="+11" <? echo ($timezone == '+11')?"selected":"";?>>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
		<option value="+12" <? echo ($timezone == '+12')?"selected":"";?>>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
	</select>			 
				</td>
       </tr>
       <tr>
        <td colspan="2" align="center" class="tablec"><br />
         <input type="submit" class="button" value="Proceed >"><br /><br />
        </td>
       </tr>
      </table>
<?php
} 

function html_install_success($notes)
{
    global $DFLT;

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
            <form action="login.php" method="post">
        <td class="tableh1" colspan="2"><h2>Installation completed</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"> <a href="index.php">ExtCalendar</a> is now properly configured and ready to roll.<br /><br /><a href="login.php?">Login</a> using the information you provided for your admin account.<?php echo $notes ?>
        </td>
       </tr>
       <tr>
        <td colspan="2" align="center" class="tablec"><br />
					<input type="hidden" name="username" value="<?php echo $_POST['admin_username'] ?>">
					<input type="hidden" name="password" value="<?php //echo $_POST['admin_password'] ?>">
          <input type="submit" name="submitted" class="button" value="Proceed !"><br /><br />
        </td>
       </tr>
      </table>
<?php
} 

function html_footer()
{
	global $step;
	echo <<<EOT
				<input type='hidden' name='step' value='$step'>
      </form>
      <br />
	 </div>
 </div>
</body>
</html>
<noscript><plaintext>
EOT;
} 
// ------------------------- SQL QUERIES TO CREATE TABLES ------------------ //
function create_tables()
{
    global $errors;

		$day = gmdate("d");
		$month = gmdate("m");
		$year = gmdate("Y");
		$date = gmdate("Y-m-d H:i:s");

		$table1 = $_POST['table_prefix']."groups";
		$table2 = $_POST['table_prefix']."users";
		$table3 = $_POST['table_prefix']."categories";
		$table4 = $_POST['table_prefix']."events";
		$table5 = $_POST['table_prefix']."config";
		$table6 = $_POST['table_prefix']."templates";
		
		if (test_table_exists($_POST['dbname'], $table1)) $errors .= "<span style='color:red'>&middot;</span>&nbsp;The table <strong>'$table1'</strong> already exists! Please suggest another table prefix.<br />";
		if (test_table_exists($_POST['dbname'], $table2)) $errors .= "<span style='color:red'>&middot;</span>&nbsp;The table <strong>'$table2'</strong> already exists! Please suggest another table prefix.<br />";
		if (test_table_exists($_POST['dbname'], $table3)) $errors .= "<span style='color:red'>&middot;</span>&nbsp;The table <strong>'$table3'</strong> already exists! Please suggest another table prefix.<br />";
		if (test_table_exists($_POST['dbname'], $table4)) $errors .= "<span style='color:red'>&middot;</span>&nbsp;The table <strong>'$table4'</strong> already exists! Please suggest another table prefix.<br />";
		if (test_table_exists($_POST['dbname'], $table5)) $errors .= "<span style='color:red'>&middot;</span>&nbsp;The table <strong>'$table5'</strong> already exists! Please suggest another table prefix.<br />";
		if (test_table_exists($_POST['dbname'], $table6)) $errors .= "<span style='color:red'>&middot;</span>&nbsp;The table <strong>'$table5'</strong> already exists! Please suggest another table prefix.<br />";
		
    $db_schema = 'sql/schema.sql';
    $sql_query = fread(fopen($db_schema, 'r'), filesize($db_schema));

    $db_basic = 'sql/basic.sql';
    $sql_query .= fread(fopen($db_basic, 'r'), filesize($db_basic)); 

    // Insert the admin account
    $sql_query .= "INSERT INTO EXT_users (`user_id`, `username`, `password`, `firstname`, `lastname`, `group_id`, `email`, `lastvisit`, `user_regdate`, `user_status`) VALUES (1, '" . $_POST['admin_username'] . "', '" . md5($_POST['admin_password']) . "', 'Mr.', 'Administrator', 1, '" . $_POST['admin_email'] . "', NOW(), NOW(), 1);\n"; 

    // Insert the admin account
		$sql_query .= "INSERT INTO EXT_events VALUES (1, 'ExtCalendar Installed', 'Congratulations! ExtCalendar is installed and ready to kick !', '', '', '', '', 1, $day, $month, $year, 1, '$date', '$date', NULL, 0, 0, 0,'0000-00-00');\n"; 

    // Insert the timezone
		$timezone = str_replace('+','', $_POST['timezone']);
    $sql_query .= "INSERT INTO EXT_config VALUES ('timezone', '".$timezone."');\n"; 

    // Update the release version and table prefix
    $template_vars = array(
		'{RELEASE_NAME}' => RELEASE_NAME,
    '{RELEASE_VERSION}' => RELEASE_VERSION,
    '{RELEASE_TYPE}' => RELEASE_TYPE,
    'EXT_' => $_POST['table_prefix']
    );

		$sql_query = remove_remarks(strtr($sql_query, $template_vars));
		
    $sql_query = split_sql_file($sql_query, ';');

    foreach($sql_query as $q) {
        if (! db_query($q)) {
            $errors .= "<span style='color:red'>&middot;</span>&nbsp;Database Error: " . mysql_error() . "<br />";
            return;
        } 
    } 
} 
// ---------------------- CONFIGURATION FILE TEMPLATE ---------------------- //
function build_cfg_file()
{
    global $DFLT;

    return <<<EOT
<?php
// ExtCalendar configuration file

// DB configuration
\$CONFIG['dbsystem'] =                       "{$_POST['dbsystem']}";    // Your database system
\$CONFIG['dbserver'] =                       "{$_POST['dbserver']}";    // Your database server
\$CONFIG['dbuser'] =                         "{$_POST['dbuser']}";      // Your db username
\$CONFIG['dbpass'] =                         "{$_POST['dbpass']}";      // Your db password
\$CONFIG['dbname'] =                         "{$_POST['dbname']}";      // Your database name


// DB TABLE NAMES PREFIX
\$CONFIG['TABLE_PREFIX'] =                "{$_POST['table_prefix']}";

// FS configuration
\$CONFIG['FS_PATH'] =                         "{$_POST['fs_path']}";        // Your file system path
\$CONFIG['calendar_url'] =                    "{$_POST['url_path']}";        // Your calendar web url
?>
EOT;
} 

function write_config_file()
{
    global $errors, $DFLT;

    $config = build_cfg_file();
    @unlink($DFLT['cfg_f']);
    if ($fd = @fopen($DFLT['cfg_f'], 'wb')) {
        fwrite($fd, $config);
        fclose($fd);
    } else {
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;Unable to write config file '{$DFLT['cfg_f']}'<br />";
    } 
} 

function lock_install()
{
    global $notes, $DFLT;

    if ($fd = @fopen($DFLT['lck_f'], 'wb')) {
        fwrite($fd, 'locked');
        fclose($fd);
    } else {
        $notes .= "<br /><br /><b>Warning :</b> the installer could not create the '{$DFLT['lck_f']}' file. In order to secure your installation, you need to delete the 'install.php' file from your server !<br /><br />";
    } 
} 

function get_fspath($fs_path) {
// function to format the fs path correctly (paths end with a trail "/")
	$fs_path=preg_split("/[\/\\\]/", dirname($fs_path));

	// just in case $fs_path equals "//"
	$fs_path = ereg_replace("//","/",join('/',$fs_path)."/");
	return $fs_path;
}


// --------------------------------- MAIN CODE ----------------------------- //
// Disable magic_quotes_runtime if active to allow proper reading from .sql files.
set_magic_quotes_runtime(0);
// The defaults values
$table_prefix = $_POST['table_prefix'];
$DFLT = array('cfg_d' => 'include', // The config file dir
    'lck_f' => 'include/installed.dis', // Name of install lock file
    'cfg_f' => 'include/config.inc.php', // The config file name
    'upl_d' => 'upload' // The uploaded pic dir
    );
//$default_timezone = "-5"; // default timezone (EST) GMT - 5 hours
$default_timezone = (date("Z",time())/3600)-date("I",time()); 

$timezone = isset($_POST['timezone'])?$_POST['timezone']:$default_timezone; 
$errors = '';
$notes = '';


if ($_GET['phpinfo'] && !file_exists($DFLT['lck_f'])) {
    phpinfo();
} else { // The installer
  html_header();
  html_logo();

  if (file_exists($DFLT['lck_f'])) {
      html_installer_locked();
  } else {

		$step = isset($_POST['step'])?(int)$_POST['step']:0;
		
		// Process information
		switch($step)
		{
			case 0:
				// Display the license
				display_license();
				$step++;
				break;
			
			case 1:
				// 
		    test_fs();
		    if ($errors)
		        html_prereq_errors($errors);
		    else {
						$detect_fs = get_fspath(isset($_SERVER['PATH_TRANSLATED'])?$_SERVER['PATH_TRANSLATED']:$_SERVER['SCRIPT_FILENAME']);

						$PHP_SELF = $_SERVER['PHP_SELF'];
						$HTTPS = getenv("HTTPS");
						$protocol = (isset($HTTPS) && $HTTPS == "on") ? "https://" : "http://";
						$calendar_dir = strtr(dirname($PHP_SELF), '\\', '/');
						$calendar_url_prefix = $protocol . $_SERVER['HTTP_HOST'] . $calendar_dir . (substr($calendar_dir, -1) == '/' ? '' : '/');
						$detect_url = $calendar_url_prefix;
		        html_input_config();
						$step++;
		    } 
		
				break;
			
			case 2:
				// Determine the PHP version
		    test_sql_connection();
		    test_admin_login();
		    if (!$errors) {
		    	write_config_file();
		    	create_tables();
		      lock_install();
		      html_install_success($notes);
		   	} else html_input_config($errors);
				
				break;
			default:
				
		}
	} 
  html_footer();
} 


?>