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

Date Started : 01/07/2004
Date Last Updated : 16/09/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : User Administration

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('ADMIN_USERS_PHP', true);

require_once "config.inc.php";

require_priv('can_manage_accounts');

$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;

$user_id = isset($_GET['user_id'])?$_GET['user_id']:'';
$user_id = isset($_POST['user_id'])?$_POST['user_id']:$user_id;

if(!empty($mode)) {

	switch($mode) {
		case 'add':
			pageheader($lang_user_admin_data['add_user'] . " :: " . $lang_user_admin_data['section_title']);
			print_add_user_form();
			break;

		case 'edit':
			pageheader($lang_user_admin_data['edit_user'] . " :: " . $lang_user_admin_data['section_title']);
			if(!empty($user_id)) print_edit_user_form($user_id);
			else print_user_list();
		  break;

		case 'del' :
			pageheader($lang_user_admin_data['del_user'] . " :: " . $lang_user_admin_data['section_title']);
			if(!empty($user_id)) delete_user($user_id);
			else print_user_list();
			break;

		default:
			pageheader($lang_user_admin_data['section_title']);
			print_user_list();
			break;
	}
} else {
	pageheader($lang_user_admin_data['section_title']);
	print_user_list();
}

// footer
pagefooter();

// Functions

function print_add_user_form() {
/* print a blank category form so we can add a new one */

	global $CONFIG, $ME, $zone_stamp, $lang_user_admin_data, $lang_general, $errors;

			$form = $_POST;

			$successful = false;
			
			if(count($_POST)) {
				// Process form post

				$form['username'] = trim($form['username']);
				$form['password'] = trim($form['password']);
				$form['password_confirm'] = trim($form['password_confirm']);
				$form['email'] = trim($form['email']);

				$form['firstname'] = trim($form['firstname']);
				$form['lastname'] = trim($form['lastname']);
				$form['user_website'] = trim($form['user_website']);
				$form['user_location'] = trim($form['user_location']);
				$form['user_occupation'] = trim($form['user_occupation']);

				// Form Validation
				$errors = '';
				if (empty($form['username'])) { $errors .=  theme_error_string($lang_user_admin_data['no_username']);  }
				elseif (!eregi("^[[:alnum:]]{4,30}$", $form['username'] )) { $errors .= theme_error_string($lang_user_admin_data['invalid_username']);}
				else {
					// check if there is an existing account with the same username
					$username = isset($form['username'])?addslashes($form['username']):"";
					$query = "SELECT user_id FROM ".$CONFIG['TABLE_USERS']." where username = '$username'";
					$result = db_query($query);
					if(db_num_rows($result)) $errors .= theme_error_string($lang_user_admin_data['username_exists']);
				}
				if (empty($form['password'])) { $errors .= theme_error_string($lang_user_admin_data['no_password']);  }
				elseif (!eregi("^[[:alnum:]]{4,16}$", $form['password'] )) { $errors .= theme_error_string($lang_user_admin_data['invalid_password']);  }
				elseif ($form['password'] == $form['username']) { $errors .= theme_error_string($lang_user_admin_data['password_is_username']);  }
				elseif ($form['password'] != $form['password_confirm']) { $errors .= theme_error_string($lang_user_admin_data['password_not_match']);  }

				if (empty($form['email'])) { $errors .= theme_error_string($lang_user_admin_data['no_email']);}
				elseif (!eregi("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $form['email'] )) { $errors .= theme_error_string($lang_user_admin_data['invalid_email']);}
				elseif(!$CONFIG['reg_duplicate_emails']) {
					// check if there is an existing account with the same email
					$email = isset($form['email'])?addslashes($form['email']):"";
					$query = "SELECT user_id FROM ".$CONFIG['TABLE_USERS']." where email = '$email'";
					$result = db_query($query);
					if(db_num_rows($result)) $errors .= theme_error_string($lang_user_admin_data['email_exists']);
				}

				if (empty($form['group_id'])) { $errors .= theme_error_string($lang_user_admin_data['no_group']);  }


				if(!$errors) {
					$temp_id = '';
				
					$firstname = isset($form['firstname'])?addslashes($form['firstname']):"";
					$lastname = isset($form['lastname'])?addslashes($form['lastname']):"";
					$email = isset($form['email'])?addslashes($form['email']):"";

					$user_website = isset($form['user_website'])?addslashes($form['user_website']):"";
					$user_location = isset($form['user_location'])?addslashes($form['user_location']):"";
					$user_occupation = isset($form['user_occupation'])?addslashes($form['user_occupation']):"";
		
					$user_status = isset($form['user_status'])?1:0;


					$query = "INSERT INTO ".$CONFIG['TABLE_USERS']." (`username`, `password`, `firstname`, `lastname`, `group_id`, `email`, `user_website`, `user_location`, `user_occupation`, `user_regdate`, `lastvisit`, `user_status` ) 
										VALUES ('$username','".md5($form['password'])."','$firstname','$lastname','$form[group_id]','$email','$user_website','$user_location','$user_occupation', NOW(), NOW(), '$user_status')";
					db_query($query);

					// Successfull message
					theme_redirect_dialog($lang_user_admin_data['add_user'], $lang_user_admin_data['add_user_success'], $lang_general['continue'], $ME);
					// to remember not to display the form again
					$successful = true;
				} 
			} else {
				// default form values
			}
			
			// Render the form
      if(!$successful) {
				display_user_form($ME,'add',$form);
			}

}

function print_edit_user_form($user_id) {
/* print an existing category form so we can edit it */

	global $CONFIG, $ME, $zone_stamp, $lang_user_admin_data, $lang_general, $errors;

	$successful = false;

	// Retrieve the form array
	if(count($_POST)) $form = $_POST;
	else {
		$query = "
		SELECT * FROM ".$CONFIG['TABLE_USERS']."
		WHERE user_id = '$user_id'";
		$result = db_query($query);
		$form = db_fetch_array($result);
		$form['password'] = '';
	}


	if(count($_POST)) {
		// Process form submission
		// Form Validation


		$errors = '';
		if (empty($form['email'])) { $errors .= theme_error_string($lang_user_admin_data['no_email']);}
		elseif (!preg_match( "/^[-^!#$%&'*+\/=?`{|}~.\w]+@[-a-zA-Z0-9]+(\.[-a-zA-Z0-9]+)+$/", $form['email'] )) { $errors .= theme_error_string($lang_user_admin_data['invalid_email']);}
		if (empty($form['group_id'])) { $errors .= theme_error_string($lang_user_admin_data['no_group']);  }

		if(!$errors) {

			$firstname = isset($form['firstname'])?addslashes($form['firstname']):"";
			$lastname = isset($form['lastname'])?addslashes($form['lastname']):"";
			$email = isset($form['email'])?addslashes($form['email']):"";
			$user_website = isset($form['user_website'])?addslashes($form['user_website']):"";
			$user_location = isset($form['user_location'])?addslashes($form['user_location']):"";
			$user_occupation = isset($form['user_occupation'])?addslashes($form['user_occupation']):"";

			$user_status = isset($form['user_status'])?1:0;

			$query = "
			UPDATE ".$CONFIG['TABLE_USERS']." SET 
				`firstname` = '$firstname',
				`lastname` = '$lastname',
				`email` = '$email',
				`user_status` = '$user_status' ,
				`group_id` = '$form[group_id]',
				`user_website` = '$user_website', 
				`user_location` = '$user_location', 
				`user_occupation` = '$user_occupation' 
			WHERE user_id = '{$form['user_id']}'";

			db_query($query);

			if (!empty($form['password'])) {
				$query = "
				UPDATE ".$CONFIG['TABLE_USERS']." SET 
					`password` = '".md5($form['password'])."'
				WHERE user_id = '{$form['user_id']}'";
				db_query($query);
			}
			
			// Successfull message
			theme_redirect_dialog($lang_user_admin_data['edit_user'], $lang_user_admin_data['edit_user_success'], $lang_general['continue'], $ME);
			// to remember not to display the form again
			$successful = true;
		} 
	} 
	
	// Render the form
  if(!$successful) {
		display_user_form($ME,'edit',$form);
	}

	
}

function delete_user($user_id) {
/* delete the owner who's identified as $ownerid */

	global $CONFIG, $ME, $lang_user_admin_data, $lang_general;

	$qid = db_query("DELETE FROM ".$CONFIG['TABLE_USERS']." WHERE user_id = '$user_id' AND username <> 'root'");
	if(!db_affected_rows()) theme_redirect_dialog($lang_user_admin_data['delete_user'], $lang_user_admin_data['delete_user_failed'], $lang_general['continue'], $ME);
	else theme_redirect_dialog($lang_user_admin_data['delete_user'], $lang_user_admin_data['delete_user_success'], $lang_general['continue'], $ME);
}

function print_user_list() {

	global $CONFIG, $ME, $lang_user_admin_data;

  $query = "SELECT u.*, g.* FROM ".$CONFIG['TABLE_USERS'] . " AS u, ".$CONFIG['TABLE_GROUPS']." AS g WHERE u.group_id = g.group_id ORDER BY user_id DESC";
	$result = db_query($query);
	$rows = db_num_rows($result);
	
  // if no rows
  if (!$rows) { 
		theme_redirect_dialog($lang_user_admin_data['section_title'], $lang_user_admin_data['no_users'], $lang_general['back'], "calendar.php");
  # show categorys
  } else {

		$count = 0;
		$user_rows = Array();
		$stats = Array();
    while ($row = db_fetch_object($result))
    {
			$user_rows[$count]['user_link'] = "admin_users.php?mode=view&user_id=".$row->user_id;
			$user_rows[$count]['user_id'] = $row->user_id;
			$user_rows[$count]['username'] = $row->username;
			$user_rows[$count]['firstname'] = $row->firstname;
			$user_rows[$count]['lastname'] = $row->lastname;
			$user_rows[$count]['lastvisit'] = $row->lastvisit;
			$user_rows[$count]['group_name'] = $row->group_name;
			$user_rows[$count]['user_regdate'] = $row->user_regdate;
			$user_rows[$count]['user_status'] = ($row->user_status)?true:false;
			
			$count ++;
    }
		$stats['total_users'] = $count;
		theme_admin_users($user_rows, $stats);
  }



}


?>