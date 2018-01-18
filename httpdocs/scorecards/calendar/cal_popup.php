<?
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
$File: cal_popup.php - Popup event file$
$Log: cal_popup.php,v $
Revision 1.7  2005/02/04 03:26:30  simoami
fixed pop up size

**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net//
**********************************************
*/

include ('config.inc.php');
$id = $_GET['id'];

$approved = has_priv('has_admin_access')?"":"AND approved = '1'";

	$event = new Event();
  if (!$event->loadEvent($id)) { 
		theme_redirect_dialog($lang_system['system_caption'], $lang_system['non_exist_event'], $lang_general['back'], $ME);
  } else 
  {
		// additional field processing
		$event->title = format_text($event->title,false,true);
		$event->description = process_content(format_text($event->description,true,false));

		popup_pageheader($row['title']);
		theme_view_event($event,true);
	}
 	
 	if(isset($_GET['print'])) { ?>

<script language="JavaScript" type="text/JavaScript">
<!--
	printDocument();
//-->
</script>

<? 
	}
	popup_pagefooter();

?>
