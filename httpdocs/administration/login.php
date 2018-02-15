<?php

//------------------------------------------------------------------------------
// Site Control Login v2.0
//
// (c) Andrew Collington - andy@amnuts.com
// (c) Michael Doig      - michael@coastart.net
// On behalf of Coastart Internet Services - all rights reserved.
//------------------------------------------------------------------------------

    require "../includes/general.config.inc";
    require "../includes/class.mysql.inc";
    require "../includes/general.functions.inc";

    // nothing given in the form

    if ($_POST['form_email']=="" || $_POST['form_pword']=="") {
        header("Location: http://$pathcfg[urlroot]/$pathcfg[adir]/index.php?again=1");
        exit();
    }

    // encrypt given password

    $encpass = crypt($_POST['form_pword'],$_POST['form_pword'][0].$_POST['form_pword'][1]);

    // open a connection

    $db = new mysql_class($dbcfg['login'],$dbcfg['pword'],$dbcfg['server']);
    $db->SelectDB($dbcfg['db']);

    // do the user/password authentication

    if (!$db->Exists("SELECT * FROM $tbcfg[admin] WHERE email='".$_POST['form_email']."'")) {
        header("Location: http://$pathcfg[urlroot]/$pathcfg[adir]/index.php?again=1");
        exit();
    }


    $db->QueryRow("SELECT * FROM $tbcfg[admin] WHERE email='".$_POST['form_email']."'");

    if (!$db->rows || ($encpass != $db->data["pword"])) {
        header("Location: http://$pathcfg[urlroot]/$pathcfg[adir]/index.php?again=1");
        exit();
    }

    // start session

    session_start();
    $SID = session_id();
	
	$USER = array();
    $USER = array(
            "fname"     => stripslashes($db->data['fname']),
            "lname"     => stripslashes($db->data['lname']),
            "email"     => $db->data['email'],
            "flags"     => explode('^',$db->data['flags']),
            "laston"    => date_format(date_create($db->data['laston'] . "MST"), "r")
        );

    $_SESSION['userdata'] = $USER;

    // everything OK

    $db->Update("UPDATE $tbcfg[admin] SET ison=1,laston=NOW() WHERE email='" . $db->data['email'] . "'");
    header("Location: http://".$pathcfg['urlroot']."/".$pathcfg['adir']."/main.php?SID=".$SID);

?>
