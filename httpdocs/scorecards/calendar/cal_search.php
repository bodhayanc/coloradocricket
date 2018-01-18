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
$File: cal_seach.php - Calendar search$
$Log: cal_search.php,v $
Revision 1.9  2005/02/04 03:26:21  simoami
fixed pop up size

**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net//
**********************************************
*/

require "config.inc.php";

pageheader($lang_event_search_data['section_title']);

$num_rows = 0;

if (count($_POST)) { 
	
	$search = isset($_POST['search'])?$_POST['search']:'';

	if (strlen($search) >= 3) {
		// must be longer or equal to 3 characters !

		$query = "SELECT id,title,e.description,url,cat,cat_name,day,month,year, color FROM ".$CONFIG['TABLE_EVENTS']." AS e LEFT JOIN ".$CONFIG['TABLE_CATEGORIES']." AS c ON e.cat=c.cat_id ";
		$query .= "WHERE (title LIKE '%$search%' OR e.description LIKE '%$search%') AND c.enabled = '1' AND approved = '1' ";
		$query .= "ORDER BY year ASC, month ASC, day ASC";
		$result = db_query($query);
		$num_rows = db_num_rows($result);
		
		$count = 0;
		while ($row = db_fetch_object($result))
		{
			$title = format_text($row->title,false,true);
			$search_results[$count]['search_title'] = highlight($search,$title,"<span class='titlehighlight'>","</span>");

			# popup or not ?
			if ($CONFIG['popup_event_mode']){
        $search_results[$count]['search_link'] = "href=\"#\" onclick=\"MM_openBrWindow('cal_popup.php?mode=view&id=".$row->id."','Calendar','toolbar=no,location=no,";
        $search_results[$count]['search_link'] .= "status=no,menubar=no,scrollbars=yes,resizable=no',".$CONFIG['popup_event_width'].",".$CONFIG['popup_event_height'].",false)\"";
			}
			else $search_results[$count]['search_link'] = "href='calendar.php?mode=view&id=".$row->id."'";

      $description = process_content(format_text(sub_string($row->description,100,"..."),false,false));

			$search_results[$count]['search_desc'] = highlight($search,$description,"<span class='highlight'>","</span>");
			
			$search_results[$count]['cat_id'] = $row->cat;
			$search_results[$count]['cat_name'] = $row->cat_name;
			$search_results[$count]['date'] = strftime ($lang_date_format['day_month_year'], mktime(12,0,0,$row->month,$row->day,$row->year));
			$count++;
		}

	}
}

theme_search_results($search_results, $num_rows);


// footer
pagefooter();
?>
