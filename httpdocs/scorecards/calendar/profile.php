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
Date Last Updated : 23/12/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : User Profile

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('PROFILE_PHP', true);

require_once "config.inc.php";

require_login(); // must be logged in to access the user's profile

$mode = isset($_GET['mode'])?$_GET['mode']:'';
$mode = isset($_POST['mode'])?$_POST['mode']:$mode;

$user_id = $Session['user']['user_id'];

switch($mode) {
	case "edit":
		pageheader($lang_user_profile_data['edit_profile'] . " :: " . $lang_user_profile_data['section_title']);
		if(!empty($user_id)) print_edit_profile_form($user_id);
		break;
	default:
		pageheader($lang_user_profile_data['section_title']);
		if(!empty($user_id)) print_user_profile($user_id);
		
}

// footer
pagefooter();

// Functions

function print_edit_profile_form($user_id) {
/* print an existing category form so we can edit it */
	global $CONFIG, $ME, $zone_stamp, $lang_user_profile_data, $lang_general, $errors;

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
		if (empty($form['email'])) { $errors .= theme_error_string($lang_user_profile_data['no_email']);}
		elseif (!preg_match( "/^[-^!#$%&'*+\/=?`{|}~.\w]+@[-a-zA-Z0-9]+(\.[-a-zA-Z0-9]+)+$/", $form['email'] )) { $errors .= theme_error_string($lang_user_profile_data['invalid_email']);}
		elseif(!$CONFIG['reg_duplicate_emails']) {
			// check if there is an existing account with the same email
			$email = isset($form['email'])?addslashes($form['email']):"";
			$query = "SELECT user_id FROM ".$CONFIG['TABLE_USERS']." where email = '$email' AND user_id <> '$user_id'";
			$result = db_query($query);
			if(db_num_rows($result)) $errors .= theme_error_string($lang_user_profile_data['email_exists']);
		}

		if (!empty($form['password'])) { 
			if (!eregi("^[[:alnum:]]{4,16}$", $form['password'] )) { $errors .= theme_error_string($lang_user_profile_data['invalid_password']);  }
			elseif ($form['password'] == $form['username']) { $errors .= theme_error_string($lang_user_profile_data['password_is_username']);  }
			elseif ($form['password'] != $form['password_confirm']) { $errors .= theme_error_string($lang_user_profile_data['password_not_match']);  }
		}
		
		if(!$errors) {

			$firstname = isset($form['firstname'])?addslashes($form['firstname']):"";
			$lastname = isset($form['lastname'])?addslashes($form['lastname']):"";
			$email = isset($form['email'])?addslashes($form['email']):"";
			$user_website = isset($form['user_website'])?addslashes($form['user_website']):"";
			$user_location = isset($form['user_location'])?addslashes($form['user_location']):"";
			$user_occupation = isset($form['user_occupation'])?addslashes($form['user_occupation']):"";

			$query = "
			UPDATE ".$CONFIG['TABLE_USERS']." SET 
				`firstname` = '$firstname',
				`lastname` = '$lastname',
				`email` = '$email',
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
			theme_redirect_dialog($lang_user_profile_data['edit_profile'], $lang_user_profile_data['edit_profile_success'], $lang_general['continue'], $ME);
			// to remember not to display the form again
			$successful = true;
		} 
	} 
	
	// Render the form
  if(!$successful) {
		theme_user_profile_form($ME,'edit',$form);
	}
}

function print_user_profile($user_id) {
/* print an existing category form so we can edit it */
	global $CONFIG, $ME, $zone_stamp, $lang_user_profile_data, $lang_general, $lang_system;

	// Retrieve the form array
	$query = "
		SELECT *, group_name FROM ".$CONFIG['TABLE_USERS']." AS e,  ".$CONFIG['TABLE_GROUPS']." AS g
		WHERE user_id = '$user_id' AND e.group_id = g.group_id" ;
	$result = db_query($query);
	$row = db_num_rows($result);
	$profile_info = db_fetch_array($result);

  // if no rows
  if (!$row) { 
		theme_redirect_dialog($lang_group_admin_data['section_title'], $lang_system['no_profile'], $lang_general['back'], "calendar.php");
  // show group and members
  } else {
		theme_user_profile($ME,$profile_info);
	} 
	
}

?>