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
File Description: login.php - Login script
$Id: login.php,v 1.7 2005/03/11 23:49:28 simoami Exp $ 
**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net//
**********************************************
*/

define('LOGIN_PHP', true);

require_once "config.inc.php";

$username = count($_POST)?$_POST['username']:'';
$password = count($_POST)?$_POST['password']:'';
$errors = '';


if(is_logged_in()) {
	pageheader($lang_login_data['section_title']);
	theme_redirect_dialog($lang_login_data['section_title'], $lang_login_data['already_logged'], $lang_general['back'], "index.php");
	pagefooter();
	exit;
}

if (count($_POST) && isset($_POST['submit']))
{

	if(!$username) $errors .= theme_error_string($lang_login_data['no_username']);
	else {
		$user = verify_login($_POST["username"], $_POST["password"]);
		$Session = $_SESSION["Session"];
	
		if ($user) {
			$_SESSION["Session"]["user"] = $user; 
			$_SESSION["Session"]["ip"] = getenv('REMOTE_ADDR');		

			if (isset($_POST['rememberme'])) {
					$cookie_life_time = 60*60*24*365; // Remember user for 365 days (1 year)
			} else {
					$cookie_life_time = 60*60*24; // 1 day
			} 
			setcookie($CONFIG['cookie_name'] . '_username', $user['username'], (time() + $cookie_life_time), $CONFIG['cookie_path']);
			setcookie($CONFIG['cookie_name'] . '_password', md5($_POST['password']), (time() + $cookie_life_time), $CONFIG['cookie_path']);

			header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');

			// save last access date
			$query = "UPDATE ".$CONFIG['TABLE_USERS']." SET lastvisit = '".date("Y-m-d H:i:s",$zone_stamp)."' WHERE user_id = '".$user['user_id']."'";
			$result = db_query($query);

			// redirect user to requested page
			$goto = empty($_SESSION["Session"]["wantsurl"]) ? $CONFIG['calendar_url']."index.php" : $_SESSION["Session"]["wantsurl"];
			
			if ($is_IIS)
				header("Refresh: 0;URL=$goto"); // Fixes IIS bug
			else
				header("Location: $goto");
			exit();
	
		} else {
			$errors .= theme_error_string($lang_login_data['invalid_login']);
			$username = $_POST["username"];

			header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
			header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
		}
	}
} elseif( isset($_COOKIE[$CONFIG['cookie_name'] . '_username']) ) {
		$username = $_COOKIE[$CONFIG['cookie_name'] . '_username'];
}

pageheader($lang_login_data['section_title']);

if(!$CONFIG['calendar_status']) 
	theme_calendar_locked();

theme_login_box($username, $errors);

pagefooter();

?>