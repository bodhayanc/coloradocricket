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
File Description: logout.php - Logout script
$Id: logout.php,v 1.3 2005/03/11 23:49:28 simoami Exp $ 
**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net//
**********************************************
*/

require_once "config.inc.php";

/* unset the Session["user"] variable to log out the user */
unset($_SESSION["Session"]["user"]);
// unset the cookies
setcookie($CONFIG['cookie_name'] . '_username', '', (time() - 31536000), $CONFIG['cookie_path']); // expires 1 year ago
setcookie($CONFIG['cookie_name'] . '_password', '-', (time() - 31536000), $CONFIG['cookie_path']); // expires 1 year ago

header('Expires: Wed, 11 Jan 1984 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

if($REFERER && strpos($REFERER,'admin_') === false && strpos($REFERER,'profile.php') === false)
	$goto = $REFERER;
else $goto = $CONFIG['calendar_url'];

if ($is_IIS)
	header("Refresh: 0;URL=$goto"); // Fixes IIS bug
else
	header("Location: $goto");
exit();
?>