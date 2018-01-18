<?php 
/*
**********************************************
ExtCalendar v2
Copyright (c) 2003-2005 Mohamed Moujami (Simo)
v1 originally written by Kristof De Jaeger
**********************************************
This program is free software; you can redistribute it and/or modify 
it under the terms of the GNU General Public License as published by 
the Free Software Foundation; either version 2 of the License, or 
(at your option) any later version. 
**********************************************
File Description: init.inc.php - Configuration file
$Id: init.inc.php,v 1.7 2005/03/11 23:46:13 simoami Exp $ 
**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net//
**********************************************
*/

// Set initial debug level
error_reporting (E_ALL ^ E_NOTICE);
$DB_DEBUG = false;

// define application constants
define('INIT_FILE_INCLUDED', true);

define('CALENDAR_NAME', 'ExtCalendar');
define('CALENDAR_VERSION', '2.0');

define('TEMPLATE_FILE', 'template.html');

// Start buffering
ob_start();

// unescape special characters if enabled  by default.
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

$temp_path = get_fspath(__FILE__);
// Initialise the $CONFIG array and some other variables
$CONFIG = array();

// Set the local configuration parameters - mainly for developers
  if (file_exists($temp_path . "include/local/config.inc.php")) require_once $temp_path . "include/local/config.inc.php";
// Include config file
	elseif (file_exists($temp_path . "include/config.inc.php")) require_once $temp_path . "include/config.inc.php";

// Check if the calendar application has been installed before
$DFLT['lck_f'] = $CONFIG['FS_PATH'] . "include/installed.dis"; // Name of install lock file

if (!file_exists($DFLT['lck_f'])) {
	ob_end_flush();
	exit;
}

require_once $CONFIG['FS_PATH']."include/functions.inc.php";
require_once $CONFIG['FS_PATH']."include/dblib.php";
require_once $CONFIG['FS_PATH']."lib/event.inc.php";

// Web Server detection
$is_apache = strstr($_SERVER['SERVER_SOFTWARE'], 'Apache') ? true : false;
$is_IIS = strstr($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') ? true : false;
// Browser variables
$ME = qualified_me();
$REFERER = get_referer();

// File system paths
$CONFIG['UPLOAD_DIR'] = "upload/";
$CONFIG['MINI_PICS_DIR'] = $CONFIG['FS_PATH']."images/minipics/";
$CONFIG['MINI_PICS_URL'] = $CONFIG['calendar_url']."images/minipics/";
$CONFIG['LIB_DIR'] = $CONFIG['FS_PATH']."lib/";
$CONFIG['PLUGINS_DIR'] = $CONFIG['FS_PATH']."plugins/";
$CONFIG['LANGUAGES_DIR'] = $CONFIG['FS_PATH']."languages/";
$CONFIG['THEMES_DIR'] = $CONFIG['FS_PATH']."languages/";

// Database definitions
$CONFIG['TABLE_CATEGORIES'] = $CONFIG['TABLE_PREFIX'] . "categories";
$CONFIG['TABLE_GROUPS'] = $CONFIG['TABLE_PREFIX'] . "groups";
$CONFIG['TABLE_USERS'] = $CONFIG['TABLE_PREFIX'] . "users";
$CONFIG['TABLE_EVENTS'] = $CONFIG['TABLE_PREFIX'] . "events";
$CONFIG['TABLE_CONFIG'] = $CONFIG['TABLE_PREFIX'] . "config";
$CONFIG['TABLE_TEMPLATES'] = $CONFIG['TABLE_PREFIX'] . "templates";
$CONFIG['TABLE_PLUGINS'] = $CONFIG['TABLE_PREFIX'] . "plugins";

db_connect($CONFIG['dbserver'],$CONFIG['dbname'],$CONFIG['dbuser'],$CONFIG['dbpass']) or die("could not connect");

// Retrieve DB stored configuration
$results = db_query("SELECT * FROM {$CONFIG['TABLE_CONFIG']}");
while ($row = db_fetch_array($results)) {
    $CONFIG[$row['name']] = $row['value'];
} // while
db_free_result($results);

// Other $CONFIG vars
$CONFIG['app_name'] = $CONFIG['calendar_name'];
// get current version info
if(!isset($CONFIG['release_version'])) {
	$CONFIG['release_name'] = '2.0 dev';
	$CONFIG['release_version'] = "200.00";
	$CONFIG['release_type'] = 'dev';
}

// Set error logging level
if ($CONFIG['debug_mode']) {
    error_reporting (E_ALL);
		$DB_DEBUG = true;
} else {
    error_reporting (E_ALL ^ E_NOTICE);
		$DB_DEBUG = false;
} 

if (!file_exists($CONFIG['FS_PATH']."themes/{$CONFIG['theme']}/theme.php")) $CONFIG['theme'] = 'default';
require_once $CONFIG['FS_PATH']."themes/{$CONFIG['theme']}/theme.php";
$THEME_DIR = $CONFIG['calendar_url']."themes/{$CONFIG['theme']}";

if (isset($user['user_lang']) && file_exists($CONFIG['LANGUAGES_DIR']."{$user['user_lang']}/index.php") ) {
	require_once $CONFIG['LANGUAGES_DIR']."{$user['user_lang']}/index.php";
} elseif (file_exists($CONFIG['LANGUAGES_DIR']."{$CONFIG['lang']}/index.php")) {
	require_once $CONFIG['LANGUAGES_DIR']."{$CONFIG['lang']}/index.php";
} else {
	$CONFIG['lang'] = 'english';
	require_once $CONFIG['LANGUAGES_DIR']."{$CONFIG['lang']}/index.php";
}

// Localizing time
while(list(,$temp_lang_code) = each($lang_info['locale']) ) {
	setlocale (LC_TIME,$temp_lang_code);
}
//$zone_stamp = extcal_get_local_time();
//$today = ucwords(strftime ($lang_date_format['full_date'], $zone_stamp));
// e.g. Wednesday, June 05, 2002


// some settings of vars
$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;
$id = isset($_GET['id'])?$_GET['id']:'';
$id = isset($_POST['id'])?$_POST['id']:$id;
$event_id = isset($_GET['event_id'])?$_GET['event_id']:$id;
$event_id = isset($_POST['event_id'])?$_POST['event_id']:$event_id;
$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:'';
$cat_id = isset($_POST['cat_id'])?$_POST['cat_id']:$cat_id;

// Initialize time variables with today's date
$m = (int)date("n", extcal_get_local_time()); // Numeric representation of a month, without leading zeros
$y = (int)date("Y", extcal_get_local_time()); 
$d = (int)date("j", extcal_get_local_time()); // Day of the month without leading zeros

$today = array(
	'day' => $d,
	'month' => $m,
	'year' => $y
);
// initialise the date variable 
if(isset($_POST['date'])) {
	list($year, $month, $day) = split('[/.-]', $_POST['date']); // split at a slash, dot, or hyphen.
	$date = array(
		'day' => (int)$day,
		'month' => (int)$month,
		'year' => (int)$year
	);
} elseif(isset($_GET['date'])) {
	list($year, $month, $day) = split('[/.-]', $_GET['date']); // split at a slash, dot, or hyphen.
	$date = array(
		'day' => (int)$day,
		'month' => (int)$month,
		'year' => (int)$year
	);
} else {
	$date = array(
		'day' => (int)$today['day'],
		'month' => (int)$today['month'],
		'year' => (int)$today['year']
	);
} 

function get_fspath($fs_path) {
// function to format the fs path correctly (paths end with a trail "/")
	$fs_path=preg_split("/[\/\\\]/", dirname($fs_path));
	// just in case $fs_path equals "//"
	$fs_path = ereg_replace("//","/",join('/',$fs_path)."/");
	return $fs_path;
}
?>