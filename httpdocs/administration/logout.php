<?php

//------------------------------------------------------------------------------
// Site Control Logout v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

require "../includes/class.mysql.inc";
require "../includes/general.config.inc";

// startup session

session_start();

// open a connection

$db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
$db->SelectDB($dbcfg['db']);
$db->Update("UPDATE $tbcfg[admin] SET ison=0 WHERE email='" . $USER['email'] . "'");

// destroy the session and log them out

session_unregister($USER);
session_destroy();
header("Location: http://$pathcfg[urlroot]/$pathcfg[adir]/index.php?again=2");

?>
