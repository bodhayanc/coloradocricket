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

Date Started : 29/08/2004
Date Last Updated : 12/09/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : Group Administration

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('ADMIN_GROUPS_PHP', true);

require_once "config.inc.php";

require_priv('can_manage_accounts');

$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;

$group_id = isset($_GET['group_id'])?$_GET['group_id']:'';
$group_id = isset($_POST['group_id'])?$_POST['group_id']:$group_id;

if(!empty($mode)) {

	switch($mode) {
		case 'add':
			pageheader($lang_group_admin_data['add_group'] . " :: " . $lang_group_admin_data['section_title']);
			print_add_group_form();
			break;

		case 'edit':
			pageheader($lang_group_admin_data['edit_group'] . " :: " . $lang_group_admin_data['section_title']);
			if(!empty($group_id)) print_edit_group_form($group_id);
			else print_group_list();
		  break;

		case 'del' :
			pageheader($lang_group_admin_data['delete_group'] . " :: " . $lang_group_admin_data['section_title']);
			if(!empty($group_id)) delete_group($group_id);
			else print_group_list();
			break;

		case 'view' :
			pageheader($lang_group_admin_data['view_group'] . " :: " . $lang_group_admin_data['section_title']);
			if(!empty($group_id)) print_group_members($group_id);
			else print_group_list();
			break;

		default:
			pageheader($lang_group_admin_data['section_title']);
			print_group_list();
			break;
	}
} else {
	pageheader($lang_group_admin_data['section_title']);
	print_group_list();
}

// footer
pagefooter();

// Functions

function print_add_group_form() {
/* print a blank category form so we can add a new one */

	global $CONFIG, $ME, $zone_stamp, $errors, $lang_group_admin_data, $lang_general;

			$form = $_POST;

			$successful = false;
			
			if(count($_POST)) {
				// Process user submission
				// Form Validation
				$errors = '';

				if (empty($form['group_name'])) { $errors .=  theme_error_string($lang_group_admin_data['no_group_name']);  }
				if (empty($form['description'])) { $errors .= theme_error_string($lang_group_admin_data['no_group_desc']);}


				if(!$errors) {
					$temp_id = '';
					
					$group_name = isset($form['group_name'])?addslashes($form['group_name']):"";
					$description = isset($form['description'])?addslashes($form['description']):"";

					$has_admin_access = (isset($form['has_admin_access']))?1:0;
					$can_manage_accounts = (isset($form['can_manage_accounts']))?1:0;
					$can_change_settings = (isset($form['can_change_settings']))?1:0;
					$can_manage_cats = (isset($form['can_manage_cats']))?1:0;
					$upl_need_approval = (isset($form['upl_need_approval']))?1:0;
					
					$query = "INSERT INTO ".$CONFIG['TABLE_GROUPS']." (`group_name`, `description`, `has_admin_access`, `can_manage_accounts`, `can_change_settings`, `can_manage_cats`, `upl_need_approval`) 
										VALUES (
											'$group_name'
											,'$description'
											,'$has_admin_access'
											,'$can_manage_accounts'
											,'$can_change_settings'
											,'$can_manage_cats'
											,'$upl_need_approval'
										)";
					db_query($query);

					// Successfull message
					theme_redirect_dialog($lang_group_admin_data['add_group'], $lang_group_admin_data['add_group_success'], $lang_general['continue'], $ME);
					// to remember not to display the form again
					$successful = true;
				} 
			} else {
				// Initialisation values
				$form['upl_need_approval'] = true;
			}
			
			// Render the form
      if(!$successful) {
				display_group_form($ME,'add',$form);
			}

}

function print_edit_group_form($group_id) {
/* print an existing category form so we can edit it */

	global $CONFIG, $ME, $zone_stamp, $errors, $lang_group_admin_data, $lang_general;

		$successful = false;

	// Retrieve the form array
	if(count($_POST)) $form = $_POST;
	else {
		$query = "
		SELECT * FROM ".$CONFIG['TABLE_GROUPS']."
		WHERE group_id = $group_id";
		$result = db_query($query);
		$form = db_fetch_array($result);
	}

	if(count($_POST)) {
		// Process form submission
		// Form Validation


		$errors = '';
		if (empty($form['group_name'])) { $errors .=  theme_error_string($lang_group_admin_data['no_group_name']);  }
		if (empty($form['description'])) { $errors .= theme_error_string($lang_group_admin_data['no_group_desc']);}

		if(!$errors) {

			$group_name = isset($form['group_name'])?addslashes($form['group_name']):"";
			$description = isset($form['description'])?addslashes($form['description']):"";

			$has_admin_access = (isset($form['has_admin_access']))?1:0;
			$can_manage_accounts = (isset($form['can_manage_accounts']))?1:0;
			$can_change_settings = (isset($form['can_change_settings']))?1:0;
			$can_manage_cats = (isset($form['can_manage_cats']))?1:0;
			$upl_need_approval = (isset($form['upl_need_approval']))?1:0;
			
			$query = "
			UPDATE ".$CONFIG['TABLE_GROUPS']." SET 
				`group_name` = '$group_name',
				`description` = '$description',
				`has_admin_access` = '$has_admin_access',
				`can_manage_accounts` = '$can_manage_accounts',
				`can_change_settings` = '$can_change_settings',
				`can_manage_cats` = '$can_manage_cats',
				`upl_need_approval` = '$upl_need_approval'
			WHERE group_id = '{$form['group_id']}'";


			db_query($query);

			// Successfull message
			theme_redirect_dialog($lang_group_admin_data['edit_group'], $lang_group_admin_data['edit_group_success'], $lang_general['continue'], $ME);
			// to remember not to display the form again
			$successful = true;
		} 
	} 
	
	// Render the form
  if(!$successful) {
		display_group_form($ME,'edit',$form);
	}

	
}

function delete_group($group_id) {
/* delete the group identified as $group_id */

	global $CONFIG, $ME, $lang_group_admin_data, $lang_general;

	// Check to see if there are users under this group
	$result = db_query("SELECT user_id FROM ".$CONFIG['TABLE_USERS']." WHERE group_id = '$group_id'");
	$num_users = db_num_rows($result);
	// Check to see if this group is locked (required by the system)
  $result1 = db_query("SELECT * FROM ".$CONFIG['TABLE_GROUPS'] . " WHERE group_id = '$group_id' and locked = '1'");
	$is_locked = db_num_rows($result1);

	if($is_locked) {
		theme_redirect_dialog($lang_group_admin_data['delete_group'], $lang_group_admin_data['delete_group_failed'], $lang_general['back'], $ME);
	} elseif($num_users) {
		theme_redirect_dialog($lang_group_admin_data['delete_group'], sprintf($lang_group_admin_data['group_has_users'],$num_events), $lang_general['back'], $ME);
	} else {
		$qid = db_query("DELETE FROM ".$CONFIG['TABLE_GROUPS']." WHERE group_id = '$group_id'");
		if(!db_affected_rows()) theme_redirect_dialog($lang_group_admin_data['delete_group'], $lang_group_admin_data['delete_group_failed'], $lang_general['back'], $ME);
		else theme_redirect_dialog($lang_group_admin_data['delete_group'], $lang_group_admin_data['delete_group_success'], $lang_general['continue'], $ME);
	}
}

function print_group_list() {

	global $CONFIG, $ME, $lang_group_admin_data, $lang_general;

  $query = "SELECT * FROM ".$CONFIG['TABLE_GROUPS'] . " ORDER BY group_name";
	$result = db_query($query);
	$rows = db_num_rows($result);
	
  // if no rows
  if (!$rows) { 
		theme_redirect_dialog($lang_group_admin_data['section_title'], $lang_group_admin_data['no_groups'], $lang_general['back'], "calendar.php");
  // show groups
  } else {

		$total_groups = 0;
		$count = 0;
		$group_rows = '';
		$stats = Array();
    while ($row = db_fetch_object($result))
    {
      $query = "SELECT * FROM ".$CONFIG['TABLE_USERS'] . " WHERE group_id = $row->group_id";
      $result1 = db_query($query);
      $total = db_num_rows($result1);
			$group_rows[$count]['total_users'] = $total;
			$group_rows[$count]['group_link'] = $ME."?mode=view&group_id=".$row->group_id;
			$group_rows[$count]['group_desc'] = format_text($row->description,true,false);
			$group_rows[$count]['group_id'] = $row->group_id;
			$group_rows[$count]['group_name'] = format_text($row->group_name,false,true);
			$group_rows[$count]['locked'] = $row->locked;
			
			$total_groups ++;
			$count ++;
    }
		$stats['total_groups'] = $total_groups;
		theme_admin_groups($group_rows, $stats);
  }



}

function print_group_members($group_id) {

	global $CONFIG, $ME, $lang_group_admin_data, $lang_general;

	
    $query = "SELECT group_name, description FROM ".$CONFIG['TABLE_GROUPS'] . " WHERE group_id = $group_id";
    $result = db_query($query);
		$rows = db_num_rows($result);
		$group_row = db_fetch_object($result);
		
  // if no rows
  if (!$rows) { 
		theme_redirect_dialog($lang_group_admin_data['section_title'], $lang_group_admin_data['no_groups'], $lang_general['back'], "calendar.php");
  // show group and members
  } else {
	  $query = "SELECT * FROM ".$CONFIG['TABLE_USERS'] . " WHERE group_id = $group_id ORDER BY username";
		$result1 = db_query($query);
		//$user_rows = db_num_rows($result1);

		$total_users = 0;
		$count = 0;
		$user_rows = Array();
		$group_info = Array();
		$stats = Array();
    while ($row = db_fetch_object($result1))
    {
			$user_rows[$count]['user_link'] = "calendar.php?mode=cat&group_id=".$row->group_id;
			$user_rows[$count]['user_id'] = $row->user_id;
			$user_rows[$count]['username'] = $row->username;
			$user_rows[$count]['firstname'] = $row->firstname;
			$user_rows[$count]['lastname'] = $row->lastname;
			$user_rows[$count]['email'] = $row->email;
			$user_rows[$count]['lastvisit'] = $row->lastvisit;
			$user_rows[$count]['user_status'] = $row->user_status;
			
			$total_users ++;
			$count ++;
    }
		$group_info['total_users'] = $total_users;
		$group_info['group_name'] = format_text($group_row->group_name,false,true);
		$group_info['group_desc'] = format_text($group_row->description,false,false);
		$group_info['group_id'] = $group_id;
		$stats['total_users'] = $total_users;
		theme_view_group($user_rows,$group_info, $stats);
  }



}

?>