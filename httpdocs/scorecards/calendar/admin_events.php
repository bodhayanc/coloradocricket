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
Date Last Updated : 05/09/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : Events Administration

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('ADMIN_EVENTS_PHP', true);

require_once "config.inc.php";

require_priv('has_admin_access');

$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;

$id = isset($_GET['id'])?$_GET['id']:'';
$id = isset($_POST['id'])?$_POST['id']:$id;

if(!empty($mode)) {

	switch($mode) {
		case 'add':
			pageheader($lang_event_admin_data['add_event'] . " :: " . $lang_event_admin_data['section_title']);
			print_add_event_form($date);
			break;

		case 'edit':
			pageheader($lang_event_admin_data['edit_event'] . " :: " . $lang_event_admin_data['section_title']);
			if(!empty($id)) print_edit_event_form($id);
			else print_event_list();
		  break;

		case 'view' :
			pageheader($lang_event_admin_data['view_event'] . " :: " . $lang_event_admin_data['section_title']);
			if(!empty($id)) print_event($id);
			else print_event_list();
			break;

		case 'del' :
			pageheader($lang_event_admin_data['delete_event'] . " :: " . $lang_event_admin_data['section_title']);
			if(!empty($id)) delete_event($id);
			else print_event_list();
			break;

		case 'apr' :
			pageheader($lang_event_admin_data['approve_event'] . " :: " . $lang_event_admin_data['section_title']);
			if(!empty($id)) approve_event($id);
			else print_event_list();
			break;
	
		case 'update' :
			pageheader($lang_event_admin_data['update_event'] . " :: " . $lang_event_admin_data['section_title']);
			update_event($_POST);
			//print_event_list();
			break;

		default:
			pageheader($lang_event_admin_data['section_title']);
			print_event_list();
			break;
	}
} else {
	pageheader($lang_event_admin_data['section_title']);
	print_event_list();
}

// footer
pagefooter();

// Functions

function print_add_event_form($date = '') {
/* print a blank owner form so we can add a new owner */

	global $CONFIG, $ME, $zone_stamp, $errors, $today, $lang_event_admin_data, $lang_general;

			$form = $_POST;

			$successful = false;

			// Check date. if no date is passed as argument, then we pick today
			if (empty($date))
			{
				$day = $today['day'];
				$month = $today['month'];
				$year = $today['year'];
			}
			else
			{
				list($year, $month, $day) = split('[/.-]', $date); // split at a slash, dot, or hyphen.
				$day = (int)$day;
				$month = (int)$month;
				$year = (int)$year;
			}

			$day = isset($form['day'])?$form['day']:$day;
			$month = isset($form['month'])?$form['month']:$month;
			$year = isset($form['year'])?$form['year']:$year;

			if (isset($form['title'])) $title = addslashes($form['title']); else $title = '';
			if (isset($form['description'])) $description = addslashes($form['description']); else $description = '';
			if (isset($form['contact'])) $contact = addslashes($form['contact']); else $contact = '';
			if (isset($form['email'])) $email = addslashes($form['email']); else $email = '';
			if (isset($form['url'])) $url = addslashes($form['url']); else $url = '';
			if (isset($form['cat'])) $cat = $form['cat']; else $cat = '';
			
			if(count($_POST)) {
			// Process user submission
				// Form Validation
				$errors = '';
				if (empty($title)) $errors .=  theme_error_string($lang_event_admin_data['no_event_name']);
				if (empty($description)) $errors .= theme_error_string($lang_event_admin_data['no_event_desc']);
				if (empty($cat)) $errors .= theme_error_string($lang_event_admin_data['no_cat']);
				if (empty($day) || empty($month) || empty($year) || !checkdate($month,$day,$year)) $errors .= theme_error_string($lang_event_admin_data['non_valid_date']);

				$form['end_days'] = empty($form['end_days'])?'0':$form['end_days'];
				$form['end_hours'] = empty($form['end_hours'])?'0':$form['end_hours'];
				$form['end_minutes'] = empty($form['end_minutes'])?'0':$form['end_minutes'];
				if (!is_numeric($form['end_days'] )) { $errors .= theme_error_string($lang_event_admin_data['end_days_invalid']); }
				if (!is_numeric($form['end_hours'])) { $errors .= theme_error_string($lang_event_admin_data['end_hours_invalid']); }
				if (!is_numeric($form['end_minutes'])) { $errors .= theme_error_string($lang_event_admin_data['end_minutes_invalid']); }

				// check recurrence information
				switch((int)$form['recur_type_select']) {
					case 1:
						if (!is_numeric($form['recur_val_1']) || (int)$form['recur_val_1'] < 1) $errors .= theme_error_string($lang_event_admin_data['recur_val_1_invalid']);
						break;
					case 0:
					default:
				}
				switch((int)$form['recur_end_type']) {
					case 0:
						break;
					case 1:
						if (!is_numeric($form['recur_end_count']) || (int)$form['recur_end_count'] < 1) $errors .= theme_error_string($lang_event_admin_data['recur_end_count_invalid']);
						break;
					case 2:
						if (mktime(0,0,0,$month,$day,$year) > mktime(0,0,0,$form['recur_until_month'],$form['recur_until_day'],$form['recur_until_year'])) $errors .= theme_error_string($lang_event_admin_data['recur_end_until_invalid']);
						break;
					default:
						
				}
				
				// check if users are allowed to upload pictures
				if ($CONFIG['addevent_allow_picture'])
				{
					if (is_uploaded_file($_FILES['picture']['tmp_name']))
					{
						// check for size of picture
						$size = $_FILES['picture']['size'];
						// check for extension ! 
						$name = $_FILES['picture']['name'];
						$ext = explode(".",$name);
						$ext = array_reverse($ext);
						$ext = $ext[0];
						$valid_pic = false;
						$extensions = explode('/', $CONFIG['allowed_file_extensions']);
						for ($i=0;$i<count($extensions);$i++)
						{
							if(preg_match("/".$extensions[$i]."$/i",$ext))
							{
								$valid_pic = true;
							}
						}

		        // Get picture information
		        $dimensions  = getimagesize($_FILES['picture']['tmp_name']); 
						$filesize = $CONFIG['max_upl_size'];

						if ($size > $filesize)
						{
							$errors .= theme_error_string(sprintf($lang_event_admin_data['file_too_large'],$CONFIG['max_upl_size'] / 1000)); 
	            @unlink($_FILES['picture']['tmp_name']);
						}
						elseif (!$valid_pic)
						{
							$errors .= theme_error_string(sprintf($lang_event_admin_data['non_valid_extension'],str_replace('/',' ',$CONFIG['allowed_file_extensions']))); 
	            @unlink($_FILES['picture']['tmp_name']);
						}
						elseif (max($dimensions[0], $dimensions[1]) > $CONFIG['max_upl_dim']) {
			        // Picture dimensions (in pixels) must be is lower than the maximum allowed
							$errors .= theme_error_string(sprintf($lang_event_admin_data['non_valid_dimensions'],$CONFIG['max_upl_dim'])); 
	            @unlink($_FILES['picture']['tmp_name']);
	          }
					}
					else $picture = '';
				} 
				else $picture = '';
				if(!$errors) {
					$temp_id = '';
					if($valid_pic) {
						$temp_id = substr(md5(time()),0,4);
						$picture = "$temp_id"."_".$_FILES['picture']['name'];
						$picture = str_replace(" ","",$picture);
            // Move uploaded picture from the temporary folder
		        if(!@move_uploaded_file($_FILES['picture']['tmp_name'], $CONFIG['UPLOAD_DIR'].$picture)) {
	            theme_redirect_dialog($lang_event_admin_data['add_event'], $lang_event_admin_data['move_image_failed'], $lang_general['back'], $ME);
	            pagefooter();
							exit;
						}
		        // Change file permission
		        @chmod($CONFIG['UPLOAD_DIR'].$picture, octdec($CONFIG['picture_chmod'])); 
						
					}

					$url = str_replace("http://","",$url);
					
					$approve = (isset($form['autoapprove']))?1:0;
					
					if($CONFIG['time_format_24hours']) $start_time_hour = $form['start_time_hour']; // 24 hours mode
					else $start_time_hour = extcal_12to24hour($form['start_time_hour'], $form['start_time_ampm']); // 12 hours mode
					$start_date = date("Y-m-d H:i:s", mktime($start_time_hour, $form['start_time_minute'], 0, $month, $day, $year));					

					if($form['end_days'] > 0 && !$form['end_hours'] && !$form['end_minutes']) {
						$form['end_days']--; // to make sure not to jump to the next day, we push the time to 23:59:59
						$total_hours = 23;
						$total_minutes = 59;
						$total_seconds = 59;
					} else {
						$total_hours = $start_time_hour + $form['end_hours'];
						$total_minutes = $form['start_time_minute'] + $form['end_minutes'];
						$total_seconds = 0;
					}
					$end_date = date("Y-m-d H:i:s", mktime( $total_hours, $total_minutes, $total_seconds, $month, $day + $form['end_days'], $year));						

					// Set recurrence information
					switch((int)$form['recur_type_select']) {
						case 1:
							$recur_type = $form['recur_type_1'];
							$recur_val = $form['recur_val_1'];
							break;
						case 0:
						default:
							$recur_type = '';
							$recur_val = 0;
							break;
					}
					$recur_end_type = $form['recur_end_type'];
					$recur_count = $form['recur_end_count'];
					$recur_until = date("Y-m-d", mktime(0, 0, 0, $form['recur_until_month'], $form['recur_until_day'], $form['recur_until_year']));

					$query = "
					INSERT INTO ".$CONFIG['TABLE_EVENTS']." (
						title, description, contact, url, email, picture, cat, day, month, year, start_date, end_date, approved, recur_type, recur_val, recur_end_type, recur_count, recur_until
					) VALUES (
						'$title','$description','$contact','$url','$email','$picture','$cat','$day','$month','$year','$start_date','$end_date','$approve','$recur_type','$recur_val','$recur_end_type','$recur_count','$recur_until'
					)";

					db_query($query);

					// Successfull message
					theme_redirect_dialog($lang_event_admin_data['add_event'], $lang_event_admin_data['add_event_success'], $lang_general['continue'], $ME);
					// to remember not to display the form again
					$successful = true;
				} 
			} else {
				// Initial values. No HTTP post or get requests.
				$form['autoapprove'] = true;
				$form['end_days'] = '1';
				$form['end_hours'] = '0';
				$form['end_minutes'] = '0';
				$form['start_time_hour'] = '8';
				$form['start_time_minute'] = '0';
				$form['start_time_ampm'] = 'am';
				$form['day'] = $day;
				$form['month'] = $month;
				$form['year'] = $year;

				// initial values for recurrence
				$form['recur_type_select'] = '0';
				$form['recur_type_1'] = 'day';
				$form['recur_val_1'] = '1';
				$form['recur_end_type'] = '0';
				$form['recur_end_count'] = '1';
				$form['recur_until_day'] = $day;
				$form['recur_until_month'] = $month;
				$form['recur_until_year'] = $year;
			}
			
			// Render the form
      if(!$successful) {
				display_event_form($ME,'add',$form);
			}

}

function print_edit_event_form($eventid) {
/* print a blank owner form so we can add a new owner */

	global $CONFIG, $ME, $zone_stamp, $errors, $lang_event_admin_data, $lang_general;

			if(count($_POST)) $form = $_POST;
			else {
				$query = "
				SELECT * FROM ".$CONFIG['TABLE_EVENTS']."
				WHERE id = $eventid";
				$result = db_query($query);
				$form = db_fetch_array($result);
				$form['origpicture'] = $form['picture'];
				
				$form['autoapprove'] = $form['approved'];
				
				if($CONFIG['time_format_24hours']) {
					$form['start_time_hour'] = date("G", strtotime($form['start_date']));
					$form['start_time_ampm'] = '';
				} else {
					$form['start_time_hour'] = date("g",strtotime($form['start_date']));
					$form['start_time_ampm'] = date("a",strtotime($form['start_date']));
				}
				$form['day'] = date("d",strtotime($form['start_date']));
				$form['month'] = date("m",strtotime($form['start_date']));
				$form['year'] = date("Y",strtotime($form['start_date']));

				$duration_array = datestoduration ($form['start_date'],$form['end_date']);
				
				$form['end_days'] = $duration_array['days'];
				$form['end_hours'] = $duration_array['hours'];
				$form['end_minutes'] = $duration_array['minutes'];

				// Additional Reccurrence info processing
				$form['recur_end_count'] = $form['recur_count'];

				switch($form['recur_type']) {
					case 'day':
					case 'week':
					case 'month':
					case 'year':
						$form['recur_type_select'] = '1';
						$form['recur_type_1'] = $form['recur_type'];
						$form['recur_val_1'] = $form['recur_val'];
						break;
					case '':
					default:
						$form['recur_type_select'] = '0';
						$form['recur_type_1'] = $form['recur_type'];
						$form['recur_val_1'] = $form['recur_val'];
						break;
				}
				$recur_until_stamp = strtotime($form['recur_until']." 00:00:00");
				$form['recur_until_day'] = date("d", $recur_until_stamp);
				$form['recur_until_month'] = date("m", $recur_until_stamp);
				$form['recur_until_year'] = date("Y", $recur_until_stamp);

			}
			$successful = false;
			
			$day = $form['day'];
			$month = $form['month'];
			$year = $form['year'];

			if (isset($form['title'])) $title = addslashes($form['title']); else $title = '';
			if (isset($form['description'])) $description = addslashes($form['description']); else $description = '';
			if (isset($form['contact'])) $contact = addslashes($form['contact']); else $contact = '';
			if (isset($form['email'])) $email = addslashes($form['email']); else $email = '';
			if (isset($form['url'])) $url = addslashes($form['url']); else $url = '';
			if (isset($form['cat'])) $cat = $form['cat']; else $cat = '';

			if(count($_POST)) {
				// Process user submission
				// Form Validation
				$errors = '';
				$dateok = true;
				if (empty($title)) $errors .=  theme_error_string($lang_event_admin_data['no_event_name']);
				if (empty($description)) $errors .= theme_error_string($lang_event_admin_data['no_event_desc']);
				if (empty($cat)) $errors .= theme_error_string($lang_event_admin_data['no_cat']);
				if (empty($day) || empty($month) || empty($year) || !checkdate($month,$day,$year)) $errors .= theme_error_string($lang_event_admin_data['non_valid_date']);

				$form['end_days'] = empty($form['end_days'])?'0':$form['end_days'];
				$form['end_hours'] = empty($form['end_hours'])?'0':$form['end_hours'];
				$form['end_minutes'] = empty($form['end_minutes'])?'0':$form['end_minutes'];
				if (!is_numeric($form['end_days'] )) { $errors .= theme_error_string($lang_event_admin_data['end_days_invalid']); }
				if (!is_numeric($form['end_hours'])) { $errors .= theme_error_string($lang_event_admin_data['end_hours_invalid']); }
				if (!is_numeric($form['end_minutes'])) { $errors .= theme_error_string($lang_event_admin_data['end_minutes_invalid']); }

				// check recurrence information
				switch((int)$form['recur_type_select']) {
					case 1:
						if (!is_numeric($form['recur_val_1']) || (int)$form['recur_val_1'] < 1) $errors .= theme_error_string($lang_event_admin_data['recur_val_1_invalid']);
						break;
					case 0:
					default:
				}
				switch((int)$form['recur_end_type']) {
					case 0:
						break;
					case 1:
						if (!is_numeric($form['recur_end_count']) || (int)$form['recur_end_count'] < 1) $errors .= theme_error_string($lang_event_admin_data['recur_end_count_invalid']);
						break;
					case 2:
						if (mktime(0,0,0,$month,$day,$year) > mktime(0,0,0,$form['recur_until_month'],$form['recur_until_day'],$form['recur_until_year'])) $errors .= theme_error_string($lang_event_admin_data['recur_end_until_invalid']);
						break;
					default:
						
				}

				$valid_pic = false;
				// may someone upload picture or not ?
				if ($CONFIG['addevent_allow_picture'])
				{
					if (is_uploaded_file($_FILES['picture']['tmp_name']))
					{
						// check for size of picture
						$size = $_FILES['picture']['size'];
						// check for extension ! 
						$name = $_FILES['picture']['name'];
						$ext = explode(".",$name);
						$ext = array_reverse($ext);
						$ext = $ext[0];
						$valid_pic = false;
						$extensions = explode('/', $CONFIG['allowed_file_extensions']);
						for ($i=0;$i<count($extensions);$i++)
						{
							if(preg_match("/".$extensions[$i]."$/i",$ext))
							{
								$valid_pic = true;
							}
						}

		        // Get picture information
		        $dimensions  = getimagesize($_FILES['picture']['tmp_name']); 
						$filesize = $CONFIG['max_upl_size'];

						if ($size > $filesize)
						{
							$errors .= theme_error_string(sprintf($lang_event_admin_data['file_too_large'],$CONFIG['max_upl_size'] / 1000)); 
	            @unlink($_FILES['picture']['tmp_name']);
						}
						elseif (!$valid_pic)
						{
							$errors .= theme_error_string(sprintf($lang_event_admin_data['non_valid_extension'],str_replace('/',' ',$CONFIG['allowed_file_extensions']))); 
	            @unlink($_FILES['picture']['tmp_name']);
						}
						elseif (max($dimensions[0], $dimensions[1]) > $CONFIG['max_upl_dim']) {
			        // Picture dimensions (in pixels) must be is lower than the maximum allowed
							$errors .= theme_error_string(sprintf($lang_event_admin_data['non_valid_dimensions'],$CONFIG['max_upl_dim'])); 
	            @unlink($_FILES['picture']['tmp_name']);
	          }
					}
					else $picture = '';
				} 
				else $picture = '';
				if(!$errors) {
					$temp_id = '';

					if(isset($form['origpicture']) && (isset($form['delpicture']) || $valid) ) {
						@unlink($CONFIG['UPLOAD_DIR'].$form['origpicture']);
					} else $picture = isset($form['origpicture'])?$form['origpicture']:"";

					if($valid_pic)
					{
						$temp_id = substr(md5(time()),0,4);
						$picture = "$temp_id"."_".$_FILES['picture']['name'];
						$picture = str_replace(" ","",$picture);
            // Move uploaded picture from the temporary folder
		        if(!@move_uploaded_file($_FILES['picture']['tmp_name'], $CONFIG['UPLOAD_DIR'].$picture)) {
	            theme_redirect_dialog($lang_event_admin_data['add_event'], $lang_event_admin_data['move_image_failed'], $lang_general['back'], $ME);
	            pagefooter();
							exit;
						}
		        // Change file permission
		        @chmod($CONFIG['UPLOAD_DIR'].$picture, octdec($CONFIG['picture_chmod'])); 
						
					}

					$url = str_replace("http://","",$url);
					
					$approve = (isset($form['autoapprove']))?1:0;
					
					if($CONFIG['time_format_24hours']) $start_time_hour = $form['start_time_hour']; // 24 hours mode
					else $start_time_hour = extcal_12to24hour($form['start_time_hour'], $form['start_time_ampm']); // 12 hours mode
					$start_date = date("Y-m-d H:i:s", mktime($start_time_hour, $form['start_time_minute'], 0, $month, $day, $year));					

					if($form['end_days'] > 0 && !$form['end_hours'] && !$form['end_minutes']) {
						$form['end_days']--; // to make sure not to jump to the next day, we push the time to 23:59:59
						$total_hours = 23;
						$total_minutes = 59;
						$total_seconds = 59;
					} else {
						$total_hours = $start_time_hour + $form['end_hours'];
						$total_minutes = $form['start_time_minute'] + $form['end_minutes'];
						$total_seconds = 0;
					}
					$end_date = date("Y-m-d H:i:s", mktime( $total_hours, $total_minutes, $total_seconds, $month, $day + $form['end_days'], $year));						

					// Set recurrence information
					switch((int)$form['recur_type_select']) {
						case 1:
							$recur_type = $form['recur_type_1'];
							$recur_val = $form['recur_val_1'];
							break;
						case 0:
						default:
							$recur_type = '';
							$recur_val = 0;
							break;
					}
					$recur_end_type = $form['recur_end_type'];
					$recur_count = $form['recur_end_count'];
					$recur_until = date("Y-m-d", mktime(0, 0, 0, $form['recur_until_month'], $form['recur_until_day'], $form['recur_until_year']));

					$query = "
					UPDATE ".$CONFIG['TABLE_EVENTS']." SET 
						title = '$title',
						description = '$description',
						contact = '$contact',
						url = '$url',
						email = '$email',
						picture = '$picture',
						cat = '$cat',
						approved = '$approve',
						start_date = '$start_date',
						end_date = '$end_date',
						recur_type = '$recur_type',
						recur_val = '$recur_val',
						recur_end_type = '$recur_end_type',
						recur_count = '$recur_count',
						recur_until = '$recur_until'
					WHERE id = '{$form['id']}'";
					db_query($query);

					// Successfull message
					theme_redirect_dialog($lang_event_admin_data['edit_event'], $lang_event_admin_data['edit_event_success'], $lang_general['continue'], $ME);
					// to remember not to display the form again
					$successful = true;
				} 
			}
						
			// Render the form
      if(!$successful) {
				display_event_form($ME,'edit',$form);
			}

	
}

function approve_event($eventid) {
/* delete the owner who's identified as $ownerid */

	global $CONFIG, $ME, $lang_event_admin_data, $lang_general;
	$qid = db_query("UPDATE ".$CONFIG['TABLE_EVENTS']." SET approved = 1 WHERE id = '$eventid'");
	if(!db_affected_rows()) theme_redirect_dialog($lang_event_admin_data['approve_event'], $lang_event_admin_data['approve_event_failed'], $lang_general['back'], $ME);
	else theme_redirect_dialog($lang_event_admin_data['approve_event'], $lang_event_admin_data['approve_event_success'],  $lang_general['continue'], $ME);

}

function delete_event($eventid) {
/* delete the owner who's identified as $ownerid */

	global $CONFIG, $ME, $lang_event_admin_data, $lang_general;
	$qid = db_query("DELETE FROM ".$CONFIG['TABLE_EVENTS']." WHERE id = '$eventid'");
	if(!db_affected_rows()) theme_redirect_dialog($lang_event_admin_data['delete_event'], $lang_event_admin_data['delete_event_failed'], $lang_general['back'], $ME);
	else theme_redirect_dialog($lang_event_admin_data['delete_event'], $lang_event_admin_data['delete_event_success'], $lang_general['continue'], $ME);

}

function print_event_list() {

	global $CONFIG, $ME, $zone_stamp, $lang_event_admin_data, $lang_system, $lang_date_format;

	$filter = isset($_GET['eventfilter'])?$_GET['eventfilter']:1;
	$query = "SELECT id,title,e.description,url,e.picture,approved,cat,cat_name,day,month,year,color,start_date, end_date,recur_type,recur_val,recur_until FROM ".$CONFIG['TABLE_EVENTS']." AS e LEFT JOIN ".$CONFIG['TABLE_CATEGORIES']." AS c ON e.cat=c.cat_id ";
	$today = gmdate("Ymd",$zone_stamp);
	switch((int)$filter) {
		case 0:
			$section_title = $lang_event_admin_data['section_title'];
			break;
		case 1:
			$query .= "WHERE approved = 0 ";
			$section_title = $lang_event_admin_data['events_to_approve'];
			break;
		case 2:
			$query .= "WHERE (DATE_FORMAT(e.start_date,'%Y%m%d') >= $today) ";
			$section_title = $lang_event_admin_data['upcoming_events'];
			break;
		case 3:
			$query .= "WHERE (DATE_FORMAT(e.end_date,'%Y%m%d') < $today) ";
			$section_title = $lang_event_admin_data['past_events'];
			break;
		default:
			$section_title = $lang_event_admin_data['section_title'];
			break;
	}
		
	$query .= "ORDER BY year ASC, month ASC, day ASC";
	$result = db_query($query);
	$rows = db_num_rows($result);
	
	$num_rows = $rows;

	$count = 0;
	while ($row = db_fetch_object($result))
	{
		$event_results[$count]['event_id'] = $row->id;
		$event_results[$count]['event_title'] = format_text($row->title,false,true);
		$event_results[$count]['event_link'] = $ME."?mode=view&id=".$row->id;

		$description = format_text($row->description,true,false);

		$event_results[$count]['event_desc'] = $description;
		
		$event_results[$count]['event_status'] = (int)$row->approved;
		$event_results[$count]['event_picture'] = empty($row->picture)?false:true;
		$event_results[$count]['event_recur_type'] = empty($row->recur_type)?false:true;

		$event_results[$count]['cat_id'] = $row->cat;
		$event_results[$count]['cat_name'] = $row->cat_name;
		$event_results[$count]['color'] = $row->color;
		$event_results[$count]['date'] = strftime ($lang_date_format['day_month_year'], mktime(12,0,0,$row->month,$row->day,$row->year));
		$count++;
	}

	theme_admin_events($event_results, $num_rows, $section_title, $filter);

	db_free_result($result);  

}

function print_event($id) {

	global $CONFIG, $ME, $lang_event_admin_data, $lang_general, $lang_system;

	
  $query = "SELECT e.*,cat_name, color, c.description AS cat_desc  FROM ".$CONFIG['TABLE_EVENTS']." AS e ";
  $query .= "LEFT JOIN ".$CONFIG['TABLE_CATEGORIES']." AS c ON e.cat=c.cat_id WHERE id='$id'";
  $results = db_query($query);
	$rows = db_num_rows($results);

  if (!$rows) { 
		theme_redirect_dialog($lang_system['system_caption'], $lang_system['non_exist_event'], $lang_general['back'], $ME);
  } else 
  {
    $row = db_fetch_array($results);
		// additional field processing
		$row['title'] = format_text($row['title'],false,true);
		$row['description'] = process_content(format_text($row['description'],true,false));
		$row['link'] = eregi("/^(http[s]?:\/\/)",$row['url'])?$row['url']:"http://".$row['url'];

		theme_admin_view_event($row);
 	}
	db_free_result($results);  

}
?>
