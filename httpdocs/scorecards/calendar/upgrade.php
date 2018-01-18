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

Date Started : 30/09/2004
Date Last Updated : 02/10/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : Upgrade Tool

Get the latest version of ExtCalendar at http://extcal.sourceforge.net
*/

// Report all errors except E_NOTICE
error_reporting (E_ALL ^ E_NOTICE);

// Process SQL files
require_once('include/sql_parse.php');
require_once "config.inc.php";

// Disable magic_quotes_runtime if active to allow proper reading from .sql files.
set_magic_quotes_runtime(0);

// Upgrade version info
define('RELEASE_NAME','2.0 Beta 2');
define('RELEASE_VERSION','200.26');
define('RELEASE_TYPE','beta');
define('VERSION_READABLE','2.0.26');

$upgrade_data = get_upgrade();

// The defaults values
$DFLT = array(
					'cfg_d' => 'include', // The config file dir
			    'cfg_f' => 'include/config.inc.php', // The config file name
				);

$errors = '';

// get current version info
if(!isset($CONFIG['release_version'])) {
	$CONFIG['release_name'] = '2.0 dev';
	$CONFIG['release_version'] = "200.00";
	$CONFIG['release_type'] = 'dev';
}

	// Only Admin users can run the upgrade tool. So, let's test if there any active admin session

// if (this file is included included
if(basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
	
  require_priv('can_change_settings');

  html_header();
  html_logo();
	
	$CONFIG['version_readable'] = get_version_readable($CONFIG['release_version']);

	if((float)$CONFIG['release_version'] >= (float)RELEASE_VERSION) {
    html_already_upgraded();
	} elseif($CONFIG['calendar_status'] && (float)$CONFIG['release_version'] >= 200.25) {
		html_calendar_unlocked(); 
  } else {
		
		$step = isset($_POST['step'])?(int)$_POST['step']:0;
		// Process information
		switch($step)
		{
			case 0:
				// Display the intro message
				html_intro_message();
				$step++;
				break;
			
			case 1:
				// Display the license
				display_license();
				$step++;
				break;

			case 2:
				// process the upgrade data
				
				$CONFIG['FS_PATH'] = get_fspath(isset($_SERVER['PATH_TRANSLATED'])?$_SERVER['PATH_TRANSLATED']:$_SERVER['SCRIPT_FILENAME']);

				$PHP_SELF = $_SERVER['PHP_SELF'];
				$HTTPS = getenv("HTTPS");
				$protocol = (isset($HTTPS) && $HTTPS == "on") ? "https://" : "http://";
				$calendar_dir = strtr(dirname($PHP_SELF), '\\', '/');
				$calendar_url_prefix = $protocol . $_SERVER['HTTP_HOST'] . $calendar_dir . (substr($calendar_dir, -1) == '/' ? '' : '/');
				$CONFIG['calendar_url'] = $calendar_url_prefix;
				test_fs();
				test_sql_connection();
				
				if (!$errors) process_upgrade();
				if (!$errors) write_config_file();
		    if (!$errors) {
					html_upgrade_success();
					$step++;
		   	} else html_upgrade_failed($errors);
		
				break;
			
			default:
				
		}
	} 
  html_footer();
}

// ---------------------------- TEST PREREQUIRED --------------------------- //
function test_fs()
{
    global $errors, $DFLT; 
    // include must be writable to create config file
    if (! is_dir($DFLT['cfg_d'])) {
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;A subdirectory called '{$DFLT['cfg_d']}' should normally exist in the directory where you uploaded ExtCalendar. The upgrade tool can't find this directory. Check that you have uploaded all ExtCalendar files to your server.<br />";
    } elseif (! is_writable($DFLT['cfg_d'])) {
        if(!@chmod($DFLT['cfg_d'], 0777)) {
	        $errors .= "<span style='color:red'>&middot;</span>&nbsp;The '{$DFLT['cfg_d']}' directory (located in the directory where you uploaded ExtCalendar) should be writable in order to save your configuration. Use your FTP program to change its mode to 777.<br />";
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
    global $CONFIG, $errors;

    if (! $connect_id = @mysql_connect($CONFIG['dbserver'], $CONFIG['dbuser'], $CONFIG['dbpass'])) {
        $errors .= "<span style='color:red'>&middot;</span> Could not create a DB connection, please check the SQL values entered<br /><br />Database error was : " . mysql_error() . "<br />";
    } elseif (! mysql_select_db($CONFIG['dbname'], $connect_id)) {
        $errors .= "<span style='color:red'>&middot;</span>&nbsp;The database system could not locate a database called '{$CONFIG['dbname']}' please check the value entered for this<br />";
    }
} 

// ------------------------- HTML OUTPUT FUNCTIONS ------------------------- //
function html_header()
{

    ?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>ExtCalendar 2 - Upgrade Tool</title>
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

		$cal_name = CALENDAR_NAME;

	  if (file_exists("LICENSE")) {
	      $license_file = "LICENSE";
	  } else echo "<strong>ExtCalendar critical error</strong>:<br />Unable to load the file '".$license_file."' !";
	  $license_txt = fread(fopen($license_file, 'r'), filesize($license_file));

		echo <<<EOT
		 <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
			<tr>
			  <form action="upgrade.php" method="post">
			  <td class="tableh1" colspan="2"><h2>Welcome to <? echo $cal_name?> Upgrade Tool</h2>
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

function html_already_upgraded()
{
    global $DFLT, $CONFIG;

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
        <form action="index.php" method="get">
        <td class="tableh1" colspan="2"><h2>Welcome to <? echo CALENDAR_NAME?> Upgrade Tool</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"><img src="images/errormessage.gif" align="absmiddle">&nbsp;<strong>Errors</strong>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2">
        	This upgrade has already been applied. For product updates, go to "Settings" in the admin section.<br />
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

function html_calendar_unlocked() {
    global $DFLT, $CONFIG;

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
        <form action="index.php" method="get">
        <td class="tableh1" colspan="2"><h2>Welcome to <? echo CALENDAR_NAME?> Upgrade Tool</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"><img src="images/errormessage.gif" align="absmiddle">&nbsp;<strong>Errors</strong>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2">
        	Before you run this upgrade, you need to disable the calendar application in the "Settings" section.<br />
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

function html_intro_message()
{
	global $CONFIG;
    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
        <form action="upgrade.php" method="post">
       <tr>
        <td class="tableh1" colspan="2"><h2>Welcome to <? echo CALENDAR_NAME?> Upgrade Tool</h2>
        </td>
       </tr>
       <tr>
				<td class='tableb' align="center" valign="middle"><img src="themes/default/images/icon-update.gif" hspace='6'></td>
        <td class="tableb" width="96%">A newer version is available:<br /><br /><strong>Current version:</strong> <? echo CALENDAR_NAME." ".$CONFIG['release_name']." (".$CONFIG['version_readable'].")"; ?><br /><strong>New version:</strong> <? echo CALENDAR_NAME." ".RELEASE_NAME." (".VERSION_READABLE.")"; ?><br /><br />Click "Proceed" to start the upgrade process.<br />
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

function html_upgrade_success()
{

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
            <form action="index.php" method="get">
        <td class="tableh1" colspan="2"><h2>Upgrade completed</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2">The upgrade was succesfully applied.<br /><br />Click "Continue" to return to the main page.
        </td>
       </tr>
       <tr>
        <td colspan="2" align="center" class="tablec"><br />
          <input type="submit" name="submitted" class="button" value="Continue"><br /><br />
        </td>
       </tr>
      </table>
<?php
} 

function html_upgrade_failed($errors) {
    global $DFLT, $CONFIG;

    ?>
      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="maintable">
       <tr>
        <form action="index.php" method="get">
        <td class="tableh1" colspan="2"><h2>Welcome to <? echo CALENDAR_NAME?> Upgrade Tool</h2>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2"><img src="images/errormessage.gif" align="absmiddle">&nbsp;<strong>Errors</strong>
        </td>
       </tr>
       <tr>
        <td class="tableb" colspan="2">
        	The following errors occurred during the upgrade process:<br />
        	<? echo $errors; ?>
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

function html_footer()
{
	global $step;
	echo <<<EOT
				<input type='hidden' name='step' value='$step'>
      </form>
	 </div>
 </div>
</body>
</html>
<noscript><plaintext>
EOT;
} 
// ------------------------- SQL QUERIES TO CREATE TABLES ------------------ //
function process_upgrade()
{
    global $CONFIG, $upgrade_data, $errors;

		$day = gmdate("d");
		$month = gmdate("m");
		$year = gmdate("Y");
		$date = gmdate("Y-m-d H:i:s");
		$sql_query = '';
		
		for($i=0; $i< count($upgrade_data);$i++) {
			$upgrade_task = &$upgrade_data[$i]; 
			if((float)$upgrade_task[0] > (float)$CONFIG['release_version']) {
				switch($upgrade_task[1]) {
					case "sqlExecute":
						$sql_query .= $upgrade_task[2];
					break;
					case "configInsert":
						$config_array = $upgrade_task[2];
						while(list($key,$value) = each($config_array)) {
							// add each variable in the config array
							add_config_var($key,$value);
						}
					break;
					
					default:
				}
			}
		}		

    // Update the release version
    $sql_query .= "UPDATE EXT_config SET value = '".RELEASE_NAME."' WHERE name = 'release_name' LIMIT 1 ;\n"; 
		$sql_query .= "UPDATE EXT_config SET value = '".RELEASE_VERSION."' WHERE name = 'release_version' LIMIT 1 ;\n"; 
		$sql_query .= "UPDATE EXT_config SET value = '".RELEASE_TYPE."' WHERE name = 'release_type' LIMIT 1 ;\n"; 

    // Update table prefix
    $sql_query = preg_replace('/EXT_/', $CONFIG['TABLE_PREFIX'], $sql_query);

    $sql_query = remove_remarks($sql_query);
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
    global $DFLT, $CONFIG;
		if(!isset($CONFIG['dbsystem'])) $CONFIG['dbsystem'] = "mysql";
    return <<<EOT
<?php
// ExtCalendar configuration file

// DB configuration
\$CONFIG['dbsystem'] =                       "{$CONFIG['dbsystem']}";    // Your database system
\$CONFIG['dbserver'] =                       "{$CONFIG['dbserver']}";    // Your database server
\$CONFIG['dbuser'] =                         "{$CONFIG['dbuser']}";      // Your db username
\$CONFIG['dbpass'] =                         "{$CONFIG['dbpass']}";      // Your db password
\$CONFIG['dbname'] =                         "{$CONFIG['dbname']}";      // Your database name


// DB TABLE NAMES PREFIX
\$CONFIG['TABLE_PREFIX'] =                "{$CONFIG['TABLE_PREFIX']}";

// FS configuration
\$CONFIG['FS_PATH'] =                         "{$CONFIG['FS_PATH']}";        // Your file system path
\$CONFIG['calendar_url'] =                    "{$CONFIG['calendar_url']}";        // Your calendar web url
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

// --------------------------------- MAIN CODE ----------------------------- //




function get_upgrade() {
	$result_data = array(
		array("200.26", 'configInsert', 
			array('legend_cat_columns' => '4')
		),
		array("200.25", 'sqlExecute', "

ALTER TABLE EXT_events 
ADD recur_end_type tinyint(1) unsigned default '0' NOT NULL AFTER recur_val ,
ADD recur_count tinyint unsigned default '0' NOT NULL AFTER recur_end_type ;

ALTER TABLE EXT_events ADD INDEX ( recur_end_type ) ;
		"),
		array("200.25", 'configInsert', 
			array(
				'calendar_status' => '1'
				,'show_recurrent_events' => '1'
				,'multi_day_events' => 'all'
				,'cal_view_show_week' => '1'
				,'mail_method' => 'mail'
				,'mail_smtp_host' => 'smtp.myhost.com'
				,'mail_smtp_auth' => '0'
				,'mail_smtp_username' => ''
				,'mail_smtp_password' => ''
			)
		),
		
		array("200.24", 'sqlExecute', "

#
# Table structure for table EXT_template
#

CREATE TABLE EXT_templates (
	template_id int(11) NOT NULL auto_increment ,
	template_type varchar(16) NOT NULL ,
  template_description varchar(255) default NULL,
	template_status tinyint(1) default '0' NOT NULL ,
	template_value text NOT NULL ,
	last_access datetime default '0000-00-00 00:00:00' NOT NULL ,
	PRIMARY KEY (template_id) ,
	INDEX (template_status) ,
	UNIQUE (template_type),
	FULLTEXT (template_value)
) TYPE = MYISAM COMMENT = 'Table for custom interface template';
# ----------------------------------------------------

ALTER TABLE EXT_categories ADD cat_parent int(11) NOT NULL default '0' AFTER cat_id;

ALTER TABLE EXT_groups ADD locked tinyint( 1 ) unsigned NOT NULL default '0';

ALTER TABLE EXT_users 
ADD user_regdate datetime  NOT NULL default '0000-00-00 00:00:00',
ADD user_lang varchar(255) NOT NULL default '',
ADD user_website varchar(255) NOT NULL default '',
ADD user_location varchar(255) NOT NULL default '',
ADD user_occupation varchar(255) NOT NULL default '',
ADD reg_key varchar(64) default NULL,
ADD user_status tinyint(1) NOT NULL default '0';

		"),

		array("200.24", 'sqlExecute', "
#
# Dumping data for table EXT_config
#
INSERT INTO EXT_config VALUES ('release_name', '');
INSERT INTO EXT_config VALUES ('release_version', '');
INSERT INTO EXT_config VALUES ('release_type', '');
INSERT INTO EXT_config VALUES ('sort_order', 'ta');
INSERT INTO EXT_config VALUES ('picture_chmod', '0644');
INSERT INTO EXT_config VALUES ('max_upl_dim', '450');
INSERT INTO EXT_config VALUES ('allow_user_registration', '1');
INSERT INTO EXT_config VALUES ('reg_email_verify', '1');
INSERT INTO EXT_config VALUES ('reg_duplicate_emails', '0');

#
# Dumping data for table `EXT_template`
#
INSERT INTO EXT_templates VALUES (1, 'header', 'Custom header structure to display on top', 0, '<body>\r\n<div align=&quot;center&quot;>\r\n  <div class=&quot;apptitle&quot;>{CAL_NAME}</div>\r\n  <div class=&quot;appdesc&quot;>{CAL_DESCRIPTION}</div>\r\n  <br>\r\n  {ADMIN_MENU}\r\n  <br>\r\n  {MAIN_MENU} \r\n  <br>\r\n  <div width=&quot;{MAIN_TABLE_WIDTH}&quot;>\r\n', NOW());
INSERT INTO EXT_templates VALUES (2, 'footer', 'Custom footer structure to display at the bottom', 0, '  </div>\r\n</div>\r\n</body>', NOW());
INSERT INTO EXT_templates VALUES (3, 'meta', 'Space to hold meta tags and other browser related information', 1, '', NOW());

#
# Update groups table
#
UPDATE EXT_groups SET locked = '1' WHERE group_id = '1' LIMIT 1 ;
UPDATE EXT_groups SET locked = '1' WHERE group_id = '3' LIMIT 1 ;
UPDATE EXT_groups SET locked = '1' WHERE group_id = '4' LIMIT 1 ;

#
# Update users table
#
UPDATE EXT_users SET user_status = '1';

		")
	);
	return $result_data;
}	
?>