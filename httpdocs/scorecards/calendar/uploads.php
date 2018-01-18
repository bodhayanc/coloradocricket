<?php 
/*
ExtCalendar v2
Copyright (C) 2003 Mohamed Moujami (SimoAmi)

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
Date Last Updated : 16/04/2004
Author(s) : Mohamed Moujami (SimoAmi.com), Kristof De Jaeger
Description : Events Admin

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

// Report all errors except E_NOTICE
error_reporting (E_ALL ^ E_NOTICE);

require_once "config.inc.php";

require_priv('has_admin_access');

$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;

$id = isset($_GET['id'])?$_GET['id']:'';
$id = isset($_POST['id'])?$_POST['id']:$id;

if(!empty($mode)) {

	switch($mode) {
		case 'add':
			pageheader(translate('addevent') . " :: " . $CONFIG['app_name']);
			print_add_event_form();
			break;

		case 'edit':
			pageheader(translate('edit') . " :: " . $CONFIG['app_name']);
			if(!empty($id)) print_edit_event_form($id);
			else print_event_list();
		  break;
		case 'del' :
			pageheader(translate('uploadapplnk') . " :: " . $CONFIG['app_name']);
			if(!empty($id)) delete_event($id);
			else print_event_list();
			break;

		case 'apr' :
			pageheader(translate('uploadapplnk') . " :: " . $CONFIG['app_name']);
			if(!empty($id)) approve_event($id);
			else print_event_list();
			break;
	
		case 'update' :
			pageheader(translate('uploadapplnk') . " :: " . $CONFIG['app_name']);
			update_event($_POST);
			//print_event_list();
			break;

		default:
			pageheader(translate('uploadapplnk') . " :: " . $CONFIG['app_name']);
			print_event_list();
			break;
	}
} else {
	pageheader(translate('uploadapplnk') . " :: " . $CONFIG['app_name']);
	print_event_list();
}

# footer
pagefooter();

// Functions

function print_add_event_form() {
/* print a blank owner form so we can add a new owner */

	global $CONFIG, $ME, $zoneStamp, $errors, $day, $month, $year;

			$form = $_POST;

			$successful = false;
			
			
			$day = isset($_GET['day'])?$_GET['day']:gmdate("d",$zoneStamp);
			$month = isset($_GET['month'])?$_GET['month']:gmdate("m",$zoneStamp);
			$year = isset($_GET['year'])?$_GET['year']:gmdate("Y",$zoneStamp);

			$day = isset($form['bday'])?$form['bday']:$day;
			$month = isset($form['bmonth'])?$form['bmonth']:$month;
			$year = isset($form['byear'])?$form['byear']:$year;

			if (isset($form['title'])) $title = $form['title']; else $title = '';
			if (isset($form['description'])) $description = $form['description']; else $description = '';
			if (isset($form['contact'])) $contact = $form['contact']; else $contact = '';
			if (isset($form['email'])) $email = $form['email']; else $email = '';
			if (isset($form['url'])) $url = $form['url']; else $url = '';
			if (isset($form['cat'])) $cat = $form['cat']; else $cat = '';
			
			if(count($_POST)) {
				// Process user submission
				// Form Validation
				$errors = '';
				$dateok = true;
				if (empty($title)) { $errors .=  theme_error_string(translate("notitle"));  }
				if (empty($description)) { $errors .= theme_error_string(translate("nodescription"));}
				if (empty($cat)) { $errors .= theme_error_string(translate("nocat"));  }
				if (empty($day)) { $errors .= theme_error_string(translate("noday")); $dateok = false;  }
				if (empty($month)) { $errors .= theme_error_string(translate("nomonth")); $dateok = false; }
				if (empty($year)) { $errors .= theme_error_string(translate("noyear"));  $dateok = false; }
				if ($dateok) {
					$temp_month = ($month == 'every')?gmdate("m",$zoneStamp):$month;
					$temp_year = ($year == 'every')?gmdate("Y",$zoneStamp):$year;
					if (!checkdate($temp_month,$day,$temp_year)) { $errors .= theme_error_string(translate("novaliddate")); }
				}


				# may someone upload picture or not ?
				if ($CONFIG['addevent_allow_picture'])
				{
					if (is_uploaded_file($_FILES['picture']['tmp_name']))
					{
						# check for size of picture
						$size = $_FILES['picture']['size'];
						# check for extension ! 
						$name = $_FILES['picture']['name'];
						$ext = explode(".",$name);
						$ext = array_reverse($ext);
						$ext = $ext[0];
						$valid = 0;
						$extensions = explode('/', $CONFIG['allowed_file_extensions']);
						for ($i=0;$i<count($extensions);$i++)
						{
							if(preg_match("/".$extensions[$i]."$/i",$ext))
							{
								$valid = 1;
							}
						}
						$filesize = $CONFIG['max_upl_size'];
						if ($size > $filesize)
						{
							$errors .= theme_error_string(translate("filetolarge") . " (".($CONFIG['max_upl_size'] / 1000)." ".translate("kblimit").")"); 
						}
						elseif ($valid != 1)
						{
							$errors .= theme_error_string(translate("extensionnovalid")); 
						} 
					}
					else $picture = '';
				} 
				else $picture = '';
				if(!$errors) {
					$temp_id = '';
					if($valid)
					{
						$temp_id = substr(md5(time()),0,4);
						$picture = "$temp_id"."_".$_FILES['picture']['name'];
						$picture = str_replace(" ","",$picture);
						move_uploaded_file($_FILES['picture']['tmp_name'], "upload/".$picture);
					}
					$description = nl2br($description);
					$url = str_replace("http://","",$url);
					
					$approve = (isset($form['approved']))?1:0;
					
					$temp_month = ($month == 'every')?'0':$month;
					$temp_year = ($year == 'every')?'0':$year;
					$query = "INSERT INTO ".$CONFIG['TABLE_EVENTS']." (title,description,contact,url,email,picture,cat,day,month,year,approved) VALUES ('$title','$description','$contact','$url','$email','$picture','$cat','$day',";
					$query.= "'$temp_month','$temp_year','$approve')";
					db_query($query);

					// Successfull message
					theme_redirect_dialog(translate("addevent"), translate("thankyou"), translate("continuebutton"), "uploads.php");
					// to remember not to display the form again
					$successful = true;
				} 
			}
			
			// Render the form
      if(!$successful) {
				display_event_form($ME,'add',$form);
			}

}

function print_edit_event_form($eventid) {
/* print a blank owner form so we can add a new owner */

	global $CONFIG, $ME, $zoneStamp, $errors, $day, $month, $year;

			if(count($_POST)) $form = $_POST;
			else {
				$query = "
				SELECT * FROM ".$CONFIG['TABLE_EVENTS']."
				WHERE id = $eventid";
				$result = db_query($query);
				$form = db_fetch_array($result);
				$form['origpicture'] = $form['picture'];
			}
			$successful = false;
			
			$day = $form['day'];
			$month = $form['month'];
			$year = $form['year'];

			if (isset($form['title'])) $title = $form['title']; else $title = '';
			if (isset($form['description'])) $description = $form['description']; else $description = '';
			if (isset($form['contact'])) $contact = $form['contact']; else $contact = '';
			if (isset($form['email'])) $email = $form['email']; else $email = '';
			if (isset($form['url'])) $url = $form['url']; else $url = '';
			if (isset($form['cat'])) $cat = $form['cat']; else $cat = '';
			
			if(count($_POST)) {
				// Process user submission
				// Form Validation
				$errors = '';
				$dateok = true;
				if (empty($title)) { $errors .=  theme_error_string(translate("notitle"));  }
				if (empty($description)) { $errors .= theme_error_string(translate("nodescription"));}
				if (empty($cat)) { $errors .= theme_error_string(translate("nocat"));  }
				if (empty($day)) { $errors .= theme_error_string(translate("noday")); $dateok = false;  }
				if (empty($month)) { $errors .= theme_error_string(translate("nomonth")); $dateok = false; }
				if (empty($year)) { $errors .= theme_error_string(translate("noyear"));  $dateok = false; }
				if ($dateok) {
					$temp_month = ($month == 'every')?gmdate("m",$zoneStamp):$month;
					$temp_year = ($year == 'every')?gmdate("Y",$zoneStamp):$year;
					if (!checkdate($temp_month,$day,$temp_year)) { $errors .= theme_error_string(translate("novaliddate")); }
				}


				# may someone upload picture or not ?
				if ($CONFIG['addevent_allow_picture'])
				{
					if (is_uploaded_file($_FILES['picture']['tmp_name']))
					{
						# check for size of picture
						$size = $_FILES['picture']['size'];
						# check for extension ! 
						$name = $_FILES['picture']['name'];
						$ext = explode(".",$name);
						$ext = array_reverse($ext);
						$ext = $ext[0];
						$valid = 0;
						$extensions = explode('/', $CONFIG['allowed_file_extensions']);
						for ($i=0;$i<count($extensions);$i++)
						{
							if(preg_match("/".$extensions[$i]."$/i",$ext))
							{
								$valid = 1;
							}
						}
						$filesize = $CONFIG['max_upl_size'];
						if ($size > $filesize)
						{
							$errors .= theme_error_string(translate("filetolarge") . " (".($CONFIG['max_upl_size'] / 1000)." ".translate("kblimit").")"); 
						}
						elseif ($valid != 1)
						{
							$errors .= theme_error_string(translate("extensionnovalid")); 
						} 
					}
					else $picture = '';
				} 
				else $picture = '';
				if(!$errors) {
					$temp_id = '';

					if(isset($form['origpicture']) && (isset($form['delpicture']) || $valid) ) {
						unlink("upload/".$form['origpicture']);
					} else $picture = $form['origpicture'];

					if($valid)
					{
						$temp_id = substr(md5(time()),0,4);
						$picture = "$temp_id"."_".$_FILES['picture']['name'];
						$picture = str_replace(" ","",$picture);
						move_uploaded_file($_FILES['picture']['tmp_name'], "upload/".$picture);
					}

					$description = nl2br($description);
					$url = str_replace("http://","",$url);
					
					$approve = (isset($form['approved']))?1:0;
					
					$temp_month = ($month == 'every')?'0':$month;
					$temp_year = ($year == 'every')?'0':$year;
					
					$query = "
					UPDATE ".$CONFIG['TABLE_EVENTS']." SET 
						title = '$title',
						description = '$description',
						contact = '$contact',
						url = '$url',
						email = '$email',
						picture = '$picture',
						cat = '$cat',
						day = '$day',
						month = '$temp_month',
						year = '$temp_year',
						approved = '$approve'
					WHERE id = '{$form['id']}'";
					
					db_query($query);

					// Successfull message
					theme_redirect_dialog(translate("edit"), translate("eventedited"), translate("continuebutton"), "uploads.php");
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

	global $CONFIG, $ME;
	$qid = db_query("UPDATE ".$CONFIG['TABLE_EVENTS']." SET approved = 1 WHERE id = '$eventid'");
	if(!db_affected_rows()) theme_redirect_dialog(translate("nonapproved"), translate("cantapprevent"), translate("returnbutton"), $ME);
	else theme_redirect_dialog(translate("nonapproved"), translate("appreventok"), translate("returnbutton"), $ME);

}

function delete_event($eventid) {
/* delete the owner who's identified as $ownerid */

	global $CONFIG, $ME;
	$qid = db_query("DELETE FROM ".$CONFIG['TABLE_EVENTS']." WHERE id = '$eventid'");
	if(!db_affected_rows()) theme_redirect_dialog(translate("delev"), translate("cantdelevent"), translate("returnbutton"), $ME);
	else theme_redirect_dialog(translate("delev"), translate("deleventok"), translate("returnbutton"), $ME);

}

function print_event_list() {

	global $CONFIG, $ME, $maand;

	$query = "SELECT id,title,e.description,url,cat,cat_name,day,month,year, color FROM ".$CONFIG['TABLE_EVENTS']." AS e LEFT JOIN ".$CONFIG['TABLE_CATEGORIES']." AS c ON e.cat=c.cat_id ";
	$query .= "WHERE approved = '0' ";
	$query .= "ORDER BY year ASC, month ASC, day ASC";
	$result = db_query($query);
	$rows = db_num_rows($result);
	
	if ($rows == 0)	{ 
		theme_caption_dialog(translate("nonapproved"), translate("nononapproved"));
	}	else {

		$num_rows = $rows;

		$count = 0;
		while ($row = mysql_fetch_object($result))
		{
			$event_results[$count]['event_id'] = $row->id;
			$event_results[$count]['event_title'] = htmlentities($row->title);
			$event_results[$count]['event_link'] = "href='uploads.php?op=view&id=".$row->id."'";
			
			$de = htmlentities($row->description);
			$de = stripslashes($de);
			if(strlen($de) > 100) $de = substr($de,0,100)." ...";

			$event_results[$count]['event_desc'] = $de;
			
			$event_results[$count]['cat_id'] = $row->cat;
			$event_results[$count]['cat_name'] = $row->cat_name;
			$event_results[$count]['date'] = $row->day ." ".$maand[$row->month]." ".$row->year;
			$count++;
		}

		theme_admin_events($event_results, $num_rows);
	}

}


?>
