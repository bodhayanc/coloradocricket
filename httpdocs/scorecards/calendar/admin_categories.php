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
File Description: admin_categories.php - Category Admin ***incomplete
$Id: admin_categories.php,v 1.8 2005/02/12 12:50:28 simoami Exp $
**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net
**********************************************
*/

define('ADMIN_CATS_PHP', true);

require_once "config.inc.php";

require_priv('can_manage_cats');

$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;

$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:'';
$cat_id = isset($_POST['cat_id'])?$_POST['cat_id']:$cat_id;

if(!empty($mode)) {

	switch($mode) {
		case 'add':
			pageheader($lang_cat_admin_data['add_cat'] . " :: " . $lang_cat_admin_data['section_title']);
			print_add_cat_form();
			break;

		case 'edit':
			pageheader($lang_cat_admin_data['edit_cat'] . " :: " . $lang_cat_admin_data['section_title']);
			if(!empty($cat_id)) print_edit_cat_form($cat_id);
			else print_cat_list();
		  break;

		case 'del' :
			pageheader($lang_cat_admin_data['del_cat'] . " :: " . $lang_cat_admin_data['section_title']);
			if(!empty($cat_id)) delete_cat($cat_id);
			else print_cat_list();
			break;

		default:
			pageheader($lang_cat_admin_data['section_title']);
			print_cat_list();
			break;
	}
} else {
	pageheader($lang_cat_admin_data['section_title']);
	print_cat_list();
}

// footer
pagefooter();

// Functions

function print_add_cat_form() {
/* print a blank category form so we can add a new one */

	global $CONFIG, $ME, $zone_stamp, $errors, $lang_cat_admin_data, $lang_general;

			$form = $_POST;

			$successful = false;
			
			if(count($_POST)) {
				// Process user submission
				// Form Validation
				$errors = '';

				if (empty($form['cat_name'])) { $errors .=  theme_error_string($lang_cat_admin_data['no_cat_name']);  }
				if (empty($form['description'])) { $errors .= theme_error_string($lang_cat_admin_data['no_cat_desc']);}
				if (empty($form['color'])) { $errors .= theme_error_string($lang_cat_admin_data['no_color']);  }


				if(!$errors) {
					$temp_id = '';
					
					$cat_name = isset($form['cat_name'])?addslashes($form['cat_name']):"";
					$description = isset($form['description'])?addslashes(html_decode($form['description'])):"";

					$color = isset($form['color'])?addslashes($form['color']):"";

					$admin_auto_approve = (isset($form['adminapproved']))?1:0;
					$user_auto_approve = (isset($form['userapproved']))?1:0;
					$options = $user_auto_approve + $admin_auto_approve*2;
					$status = (isset($form['status']))?1:0;
					
					$query = "INSERT INTO ".$CONFIG['TABLE_CATEGORIES']." (`cat_name`, `description`, `color`, `options`, `enabled`) 
										VALUES ('$cat_name','$description','$color','$options','$status')";
					db_query($query);

					// Successfull message
					theme_redirect_dialog($lang_cat_admin_data['add_cat'], $lang_cat_admin_data['add_cat_success'], $lang_general['continue'], $ME);
					// to remember not to display the form again
					$successful = true;
				} 
			} else {
				$form['adminapproved'] = true;
				$form['color'] = "#505054";
			}
			
			// Render the form
      if(!$successful) {
				display_cat_form($ME,'add',$form);
			}

}

function print_edit_cat_form($cat_id) {
/* print an existing category form so we can edit it */

	global $CONFIG, $ME, $zone_stamp, $errors, $lang_cat_admin_data, $lang_general;

		$successful = false;

	// Retrieve the form array
	if(count($_POST)) $form = $_POST;
	else {
		$query = "
		SELECT * FROM ".$CONFIG['TABLE_CATEGORIES']."
		WHERE cat_id = $cat_id";
		$result = db_query($query);
		$form = db_fetch_array($result);
		$form['userapproved'] = $form['options'] & 1;
		$form['adminapproved'] = $form['options'] & 2;
		$form['status'] = $form['enabled'];
	}


	if(count($_POST)) {
		// Process form submission
		// Form Validation


		$errors = '';
		if (empty($form['cat_name'])) { $errors .=  theme_error_string($lang_cat_admin_data['no_cat_name']);  }
		if (empty($form['description'])) { $errors .= theme_error_string($lang_cat_admin_data['no_cat_desc']);}
		if (empty($form['color'])) { $errors .= theme_error_string($lang_cat_admin_data['no_color']);  }

		if(!$errors) {

			$cat_name = isset($form['cat_name'])?addslashes($form['cat_name']):"";
			$description = isset($form['description'])?addslashes(html_decode($form['description'])):"";
			$color = isset($form['color'])?addslashes($form['color']):"";

			$admin_auto_approve = (isset($form['adminapproved']))?1:0;
			$user_auto_approve = (isset($form['userapproved']))?1:0;
			$options = $user_auto_approve + $admin_auto_approve*2;
			$status = (isset($form['status']))?1:0;
			
			$query = "
			UPDATE ".$CONFIG['TABLE_CATEGORIES']." SET 
				`cat_name` = '$cat_name',
				`description` = '$description',
				`color` = '$color',
				`options` = '$options',
				`enabled` = '$status'
			WHERE cat_id = '{$form['cat_id']}'";

			db_query($query);

			// Successfull message
			theme_redirect_dialog($lang_cat_admin_data['edit_cat'], $lang_cat_admin_data['edit_cat_success'], $lang_general['continue'], $ME);
			// to remember not to display the form again
			$successful = true;
		} 
	} 
	
	// Render the form
  if(!$successful) {
		display_cat_form($ME,'edit',$form);
	}

	
}

function delete_cat($cat_id) {
/* delete the category identified as $cat_id */

	global $CONFIG, $ME, $lang_cat_admin_data, $lang_general;


	// Check to see if there are events under this category
	$qid = db_query("SELECT id FROM ".$CONFIG['TABLE_EVENTS']." WHERE cat = '$cat_id'");
	$num_events = db_num_rows($qid);
	if($num_events) {
		theme_redirect_dialog($lang_cat_admin_data['delete_cat'], sprintf($lang_cat_admin_data['cat_has_events'],$num_events), $lang_general['back'], $ME);
	} else {
		$qid = db_query("DELETE FROM ".$CONFIG['TABLE_CATEGORIES']." WHERE cat_id = '$cat_id'");
		if(!db_affected_rows()) theme_redirect_dialog($lang_cat_admin_data['delete_cat'], $lang_cat_admin_data['delete_cat_failed'], $lang_general['back'], $ME);
		else theme_redirect_dialog($lang_cat_admin_data['delete_cat'], $lang_cat_admin_data['delete_cat_success'], $lang_general['continue'], $ME);
	}
}

function print_cat_list() {

	global $CONFIG, $ME, $lang_cat_admin_data, $lang_general;

  $query = "SELECT cat_id, cat_name, description, color, bgcolor, enabled, options FROM ".$CONFIG['TABLE_CATEGORIES'] . " ORDER BY cat_id DESC";
	$result = db_query($query);
	$rows = db_num_rows($result);
	
  // if no rows
  if (!$rows) { 
		theme_redirect_dialog($lang_cat_admin_data['section_title'], $lang_cat_admin_data['no_cats'], $lang_general['back'], "calendar.php");
  # show categorys
  } else {

		$total_cats = 0;
		$active_cats = 0;
		$count = 0;
		$cat_rows = '';
		$stats = Array();
    while ($row = db_fetch_object($result))
    {
      $query = "SELECT * FROM ".$CONFIG['TABLE_EVENTS'] . " WHERE cat = $row->cat_id";
      $result1 = db_query($query);
      $total = db_num_rows($result1);
			$cat_rows[$count]['total_events'] = $total;
			$cat_rows[$count]['color'] = $row->color;
			$cat_rows[$count]['cat_link'] = "calendar.php?mode=cat&cat_id=".$row->cat_id;
			$cat_rows[$count]['cat_desc'] = format_text($row->description,true,false);
			$cat_rows[$count]['cat_id'] = $row->cat_id;
			$cat_rows[$count]['cat_name'] = format_text($row->cat_name,false,true);
			$cat_rows[$count]['options'] = $row->options;
			$cat_rows[$count]['status'] = $row->enabled?true:false;
			
			$active_cats += $row->enabled?1:0;
			$total_cats ++;
			$count ++;
    }
		$stats['total_cats'] = $total_cats;
		$stats['active_cats'] = $active_cats;
		theme_admin_cats($cat_rows, $stats);
  }



}


?>