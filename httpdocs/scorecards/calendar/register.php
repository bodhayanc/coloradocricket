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
Description : User Registration

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('USER_REGISTRATION_PHP', true);

require_once "config.inc.php";
require_once $CONFIG['LIB_DIR']."mail.inc.php";


pageheader($lang_user_registration_data['section_title']);

if(is_logged_in()) {
	theme_redirect_dialog($lang_user_registration_data['section_title'], $lang_user_registration_data['already_logged'], $lang_general['back'], "index.php");
	pagefooter();
	exit;
}

if(!$CONFIG['allow_user_registration']) {
	theme_redirect_dialog($lang_user_registration_data['section_title'], $lang_user_registration_data['registration_not_allowed'], $lang_general['back'], "index.php");
	pagefooter();
	exit;
}

print_add_user_form();

// footer
pagefooter();

// Functions

function print_add_user_form() {
/* print a blank category form so we can add a new one */

	global $CONFIG, $ME, $zone_stamp, $lang_user_registration_data, $lang_general, $lang_system, $errors;

	
			$successful = false;
			$form = '';
			if(isset($_GET['reg_key'])) {
				$reg_key = $_GET['reg_key'];
				$query = "UPDATE ".$CONFIG['TABLE_USERS']." SET user_status = '1', reg_key = '' WHERE user_status = '0' AND reg_key = '".addslashes($reg_key)."' LIMIT 1";
				$result = db_query($query);
				if(!db_affected_rows($result)) {
					// An error occurred while trying to activate an account
					theme_redirect_dialog($lang_user_registration_data['section_title'], $lang_user_registration_data['reg_activation_failed'], $lang_general['back'], "index.php");
					pagefooter();
					exit;
				} else {
					// succesfully activated
					theme_redirect_dialog($lang_user_registration_data['section_title'], $lang_user_registration_data['reg_activation_success'], $lang_general['continue'], "index.php");
					pagefooter();
					exit;
		
				}
			} elseif(count($_POST)) {
				// Process submitted form data
				$form = $_POST;
				$step = $form['step'];

				if($step == "regform") {
					
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
					if (empty($form['username'])) { $errors .=  theme_error_string($lang_user_registration_data['no_username']);  }
					elseif (!eregi("^[[:alnum:]]{4,30}$", $form['username'] )) { $errors .= theme_error_string($lang_user_registration_data['invalid_username']);}
					else {
						// check if there is an existing account with the same username
						$username = isset($form['username'])?addslashes($form['username']):"";
						$query = "SELECT user_id FROM ".$CONFIG['TABLE_USERS']." where username = '$username'";
						$result = db_query($query);
						if(db_num_rows($result)) $errors .= theme_error_string($lang_user_registration_data['username_exists']);
					}
					if (empty($form['password'])) { $errors .= theme_error_string($lang_user_registration_data['no_password']);  }
					elseif (!eregi("^[[:alnum:]]{4,16}$", $form['password'] )) { $errors .= theme_error_string($lang_user_registration_data['invalid_password']);  }
					elseif ($form['password'] == $form['username']) { $errors .= theme_error_string($lang_user_registration_data['password_is_username']);  }
					elseif ($form['password'] != $form['password_confirm']) { $errors .= theme_error_string($lang_user_registration_data['password_not_match']);  }

					if (empty($form['email'])) { $errors .= theme_error_string($lang_user_registration_data['no_email']);}
					elseif (!eregi("^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$", $form['email'] )) { $errors .= theme_error_string($lang_user_registration_data['invalid_email']);}
					elseif(!$CONFIG['reg_duplicate_emails']) {
						// check if there is an existing account with the same email
						$email = isset($form['email'])?addslashes($form['email']):"";
						$query = "SELECT user_id FROM ".$CONFIG['TABLE_USERS']." where email = '$email'";
						$result = db_query($query);
						if(db_num_rows($result)) $errors .= theme_error_string($lang_user_registration_data['email_exists']);
					}
					
					
					
					if(!$errors) {
						$temp_id = '';
					
						$firstname = isset($form['firstname'])?addslashes($form['firstname']):"";
						$lastname = isset($form['lastname'])?addslashes($form['lastname']):"";
						$email = isset($form['email'])?addslashes($form['email']):"";
						$user_website = isset($form['user_website'])?addslashes($form['user_website']):"";
						$user_location = isset($form['user_location'])?addslashes($form['user_location']):"";
						$user_occupation = isset($form['user_occupation'])?addslashes($form['user_occupation']):"";

						
						// Check is activation through email is required
						if($CONFIG['reg_email_verify']) {
							$user_status = 0;
							srand((double)microtime()*1000000);
							$reg_key = md5(uniqid(rand(), true));
						} else {
							$user_status = 1;
							$reg_key = "";
						}
						$query = "INSERT INTO ".$CONFIG['TABLE_USERS']." (`username`, `password`, `firstname`, `lastname`, `group_id`, `email`, `user_website`, `user_location`, `user_occupation`, `user_regdate`, `lastvisit`, `reg_key`, `user_status` ) 
											VALUES ('$username','".md5($form['password'])."','$firstname','$lastname',3,'$email','$user_website','$user_location','$user_occupation', NOW(), NOW(), '$reg_key', '$user_status')";
						db_query($query);
	
						// Check is activation through email is required
						if($CONFIG['reg_email_verify']) {
							// Activation through email required

							// create an instance of the mail class
							$mail = new extcalMailer;
							
							// Now you only need to add the necessary stuff
							$mail->AddAddress($form['email'], $form['firstname'] ." ". $form['lastname']);
							$mail->Subject = sprintf($lang_user_registration_data['reg_confirm_subject'], $CONFIG['calendar_name']);

			        $reg_link = $ME . '?reg_key=' . $reg_key;
			        $template_vars = array(
			        		'{CALENDAR_NAME}' => $CONFIG['calendar_name'],
			            '{USERNAME}' => $form['username'],
			            '{PASSWORD}' => $form['password'],
			            '{REG_LINK}' => $reg_link
			            );


							$mail->Body    = strtr($lang_user_registration_data['reg_confirm_body'], $template_vars);
							
							if(!$mail->Send())
							{
								// An error occurred while trying to send the email
								theme_redirect_dialog($lang_system['system_caption'], $lang_user_registration_data['reg_email_failed'], $lang_general['back'], "index.php");
								pagefooter();
								exit;
							}
							// Activation email sent message
							theme_redirect_dialog($lang_user_registration_data['section_title'], $lang_user_registration_data['reg_mail_success'], $lang_general['continue'], "index.php");
							pagefooter();
							exit;

						} else {
							// Activation was not needed. Registration complete message.
							theme_redirect_dialog($lang_user_registration_data['section_title'], $lang_user_registration_data['reg_nomail_success'], $lang_general['continue'], "index.php");
							pagefooter();
							exit;
						}

						// to remember not to display the form again
						$successful = true;
						
					}
				} else $step = "regform";
			} else {
				// default form values
				$step = "terms";
			}

			// Render the form
      if(!$successful) {
				theme_user_registration_form($ME, $step, $form);
			}

}

?>