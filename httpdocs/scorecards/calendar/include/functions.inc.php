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
File Description: functions.inc.php - General functions
$Id: functions.inc.php,v 1.53 2005/07/07 08:04:17 simoami Exp $
**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net
**********************************************
*/


function verify_login($username, $password) {
/* verify the username and password.  if it is a valid login, return an array
 * with the username, firstname, lastname, and email address of the user */
 global $CONFIG;
 
	// SQL Injection Vulnerability protection. Thanks to Collideous
	$username = addslashes(trim($username));
	
	$query = "
	SELECT user_id, username, u.group_id AS grpid, group_name AS grpname, firstname, lastname, email, user_lang  
	FROM " . $CONFIG["TABLE_USERS"] . " AS u, ". $CONFIG["TABLE_GROUPS"] . " AS g 
	WHERE u.group_id = g.group_id AND username = '$username' AND password = '" . md5($password) . "' AND user_status = 1
	";
	$qid = db_query($query);

	return db_fetch_array($qid);
}

function is_logged_in() {
/* this function will return true if the user has logged in.  a user is logged
 * in if the $Session["user"] is set (by the login.php page) and also if the
 * remote IP address matches what we saved in the Session ($Session["ip"])
 * from login.php -- this is not a robust or secure check by any means, but it
 * will do for now */

	$Session = $_SESSION["Session"];
	$rem_ip = getenv('REMOTE_ADDR');
	return isset($Session)
		&& isset($Session["user"])
		&& isset($Session["ip"])
		&& $Session["ip"] == $rem_ip;
}

function require_login() {
/* this function checks to see if the user is logged in.  if not, it will show
 * the login screen before allowing the user to continue */
	global $CONFIG, $lang_system, $lang_general;

	$Session = $_SESSION["Session"];

	if (! is_logged_in()) {
		//$Session["wantsurl"] = qualified_me();
		$_SESSION["Session"]["wantsurl"] = qualified_me();
		pageheader($lang_system['system_caption']);
		theme_redirect_dialog($lang_system['system_caption'], $lang_system['page_requires_login'], $lang_general['continue'], "login.php");
		pagefooter();
		exit;
	}
}

function require_priv($priv) {
/* this function checks to see if the user has the privilege $priv.  if not,
 * it will display an Insufficient Privileges page and stop */
	global $CONFIG, $lang_system, $lang_general;

	if(!has_priv($priv)) {
		pageheader($lang_system['system_caption']);
		theme_redirect_dialog($lang_system['system_caption'], $lang_system['page_access_denied'], $lang_general['back'], "index.php");
		pagefooter();
		exit;
	}
}

function has_priv($priv) {
/* returns true if the user has the privilege $priv */

	global $CONFIG;
	if(is_logged_in()) {
		$Session = $_SESSION["Session"];
		$query = "SELECT * FROM " . $CONFIG['TABLE_GROUPS'] . " WHERE group_id = ".$Session["user"]["grpid"]."";
		$results = db_query($query);
		if (db_num_rows($results)) {
			$result = db_fetch_array($results);
			db_free_result($results);
			return ($result[$priv] == "1");
		}
  }
	
	$query = "SELECT * FROM {$CONFIG['TABLE_GROUPS']} WHERE group_id = 3";
	$results = db_query($query);
	if (!db_num_rows($results)) {
		pageheader($lang_system['system_caption']);
		theme_redirect_dialog($lang_system['system_caption'], sprintf($lang_system['no_anonymous_group'],$CONFIG['TABLE_GROUPS']), $lang_general['back'], "index.php");
		pagefooter();
		exit;
	}
	$result = db_fetch_array($results);
	db_free_result($results);
	return ($result[$priv] == "1");
}

// Search function 
function search()
{
	global $lang_event_search_data;
	$keyword = (isset($_POST["search"]) && !empty($_POST["search"])) ?$_POST["search"]:$lang_event_search_data['search_caption'];
	$button = (isset($_POST["search"]) && !empty($_POST["search"])) ?$lang_event_search_data['search_again']:$lang_event_search_data['search_button'];
	theme_search_form($keyword, $button);
}

// Error strings template
function theme_error_string($string) {
    global $template_error_string;

		$params = array('{MESSAGE}' => $string);
		return template_eval($template_error_string, $params);
}

function display_event_form($target, $mode, $form) {
/* Display event form */
	
	global $CONFIG, $THEME_DIR, $template_add_event_form, $errors, $today;
	global $lang_add_event_view, $lang_general, $lang_date_format;

	// building category list
	$cat_id = $form['cat'];
	$cat_filter = "";
	if(!has_priv('can_manage_cats')) $cat_filter = " WHERE enabled = '1' OR cat_id = '$cat_id'";
	$query = "SELECT * FROM ".$CONFIG['TABLE_CATEGORIES'] . $cat_filter;
	$result = db_query($query);
	$cat_list = '';
	while ($row = db_fetch_object($result))
	{
		$selected = "";
		if(isset($form['cat']))
			$selected = ($row->cat_id == $form['cat'])?"selected":"";
		$cat_list .= "\t<option value='".$row->cat_id."' style='color: " . $row->color . "' $selected>".$row->cat_name."\n";
	}

	// building day list
	$day_list = '';
	for ($i = 1;$i<=31;$i++)
	{
		$selected = ($form['day']==$i)?"selected":"";
		$day_list .= "\t<option value='$i' $selected>$i</option>\n";
	}

	// building month list
	$month_list = '';
	for($i=1;$i<=12;$i++)
	{
		$selected = ($form['month'] == $i)?"selected":"";
		$month_list .= "\t<option value='".$i."' $selected>".$lang_date_format['months'][$i-1]."</option>\n";
	}

	// building year list
	$year_list = '';
	$y = date("Y", extcal_get_local_time()) - 1;
	for ($i=0;$i<=4;$i++)
	{
		$selected = ($form['year']==$y)?"selected":"";
		$year_list .= "\t<option $selected>$y</option>\n";
		$y += 1;
	}
	
	// building time list options
	if($CONFIG['time_format_24hours']) {
		$hour_init = 0;
		$hour_limit = 23;
	} else {
		$hour_init = 1;
		$hour_limit = 12;
	}
	$start_hour_list = '';
	for ($i = $hour_init;$i<=$hour_limit;$i++)
	{
		$selected = ($form['start_time_hour'] == $i)?"selected":"";
		$start_hour_list .= "\t<option value='$i' $selected>".sprintf("%02d",$i)."</option>\n";
	}
	$start_minute_list = '';
	for ($i = 0;$i<=59;$i+=15)
	{
		$selected = ($form['start_time_minute'] == $i)?"selected":"";
		$start_minute_list .= "\t<option value='$i' $selected>".sprintf("%02d",$i)."</option>\n";
	}

	if(!$CONFIG['time_format_24hours']) {
		$selected = ($form['start_time_ampm'] == 'am')?"selected":"";
		$start_ampm_list = "\t<option value='am' $selected>AM</option>\n";
		$selected = ($form['start_time_ampm'] == 'pm')?"selected":"";
		$start_ampm_list .= "\t<option value='pm' $selected>PM</option>\n";
	} else {
		$start_ampm_list = '';
	}

	// building recurrence info
	$recur_type_1_options = '';
	$selected = ($form['recur_type_1'] == "day")?"selected":"";
	$recur_type_1_options .= "\t<option value='day' ".$selected.">".$lang_add_event_view['repeat_days']."\n";
	$selected = ($form['recur_type_1'] == "week")?"selected":"";
	$recur_type_1_options .= "\t<option value='week' ".$selected.">".$lang_add_event_view['repeat_weeks']."\n";
	$selected = ($form['recur_type_1'] == "month")?"selected":"";
	$recur_type_1_options .= "\t<option value='month' ".$selected.">".$lang_add_event_view['repeat_months']."\n";
	$selected = ($form['recur_type_1'] == "year")?"selected":"";
	$recur_type_1_options .= "\t<option value='year' ".$selected.">".$lang_add_event_view['repeat_years']."\n";

	// building day list
	$recur_until_day_list = '';
	for ($i = 1;$i<=31;$i++)
	{
		$selected = ($form['recur_until_day']==$i)?"selected":"";
		$recur_until_day_list .= "\t<option value='$i' $selected>$i</option>\n";
	}

	// building month list
	$recur_until_month_list = '';
	for($i=1;$i<=12;$i++)
	{
		$selected = ($form['recur_until_month'] == $i)?"selected":"";
		$recur_until_month_list .= "\t<option value='".$i."' $selected>".$lang_date_format['months'][$i-1]."</option>\n";
	}

	// building year list
	$recur_until_year_list = '';
	$y = date("Y", extcal_get_local_time());
	for ($i=0;$i<=4;$i++)
	{
		$selected = ($form['recur_until_year']==$y)?"selected":"";
		$recur_until_year_list .= "\t<option $selected>$y</option>\n";
		$y += 1;
	}


	$auto_approve = (isset($form['autoapprove']) && $form['autoapprove'])?"checked":"";
	$del_picture = (isset($form['delpicture']) && $form['delpicture'])?"checked":"";
		
	// building upload requirements
	$extensions = explode('/', $CONFIG['allowed_file_extensions']);
	$upload_reqs = sprintf($lang_add_event_view['file_upload_info'], ($CONFIG['max_upl_size'] / 1000), strtoupper(implode($extensions," ")) );
	
	$orig_picture = isset($form['origpicture'])?$form['origpicture']:"";
	
	if(!has_priv("has_admin_access")) template_extract_block($template_add_event_form, 'admin_row');
	if(!$errors) template_extract_block($template_add_event_form, 'errors_row');
	if(!$CONFIG['addevent_allow_contact']) template_extract_block($template_add_event_form, 'contact_row');
	if(!$CONFIG['addevent_allow_email']) template_extract_block($template_add_event_form, 'email_row');
	if(!$CONFIG['addevent_allow_url']) template_extract_block($template_add_event_form, 'url_row');
	if(!$CONFIG['addevent_allow_picture']) template_extract_block($template_add_event_form, 'picture_row');
	if(!isset($form['origpicture']) || !$form['origpicture']) template_extract_block($template_add_event_form, 'orig_picture_row');

	switch($mode) {
		case "add": 
			starttable("100%",$lang_add_event_view['section_title'],2);
			$submit = $lang_add_event_view['section_title'];
			break;
		case "edit":
			$title = format_text($form['title'],false,true);
			starttable("100%", sprintf($lang_add_event_view['edit_event'],$form['id'],$title),2);
			$submit = $lang_add_event_view['update_event_button'];
			break;
		default:
			starttable("100%",$lang_add_event_view['section_title'],2);
			$submit = $lang_add_event_view['section_title'];
	}
	if($CONFIG['time_format_24hours']) template_extract_block($template_add_event_form, '12hour_mode_row'); 
	
	$params = array(
		'{TARGET}' => $target,
		'{THEME_DIR}' => $THEME_DIR,
		'{MODE}' => $mode,
		'{EVENT_ID}' => isset($form['id'])?$form['id']:"",
		'{ERRORS}' => $lang_general['errors'],
		'{ERROR_MSG}' => $errors,
		'{EVENT_DETAILS_CAPTION}' => $lang_add_event_view['event_details_label'],
		'{TITLE_LABEL}' => $lang_add_event_view['event_title'],
		'{TITLE_VAL}' => isset($form['title'])?$form['title']:"",
		'{DESC_LABEL}' => $lang_add_event_view['event_desc'],
		'{DESC_VAL}' => isset($form['description'])?$form['description']:"",
		'{SEL_CATS_LABEL}' => $lang_add_event_view['event_cat'],
		'{SEL_CATS_DEF}' => $lang_add_event_view['choose_cat'],
		'{SEL_CATS_VAL}' => $cat_list,
		'{DATE_LABEL}' => $lang_add_event_view['event_date'],
		'{DAY_LABEL}' => $lang_add_event_view['day_label'],
		'{MONTH_LABEL}' => $lang_add_event_view['month_label'],
		'{YEAR_LABEL}' => $lang_add_event_view['year_label'],
		'{START_DATE_LABEL}' => $lang_add_event_view['start_date_label'],
		'{START_TIME_LABEL}' => $lang_add_event_view['start_time_label'],
		'{END_DATE_LABEL}' => $lang_add_event_view['end_date_label'],
		'{DAYS_LABEL}' => $lang_general['days'],
		'{HOURS_LABEL}' => $lang_general['hours'],
		'{MINUTES_LABEL}' => $lang_general['minutes'],
		'{START_DAY_VAL}' => $day_list,
		'{START_MONTH_VAL}' => $month_list,
		'{START_YEAR_VAL}' => $year_list,
		'{START_HOUR_VAL}' => $start_hour_list,
		'{START_MINUTE_VAL}' => $start_minute_list,
		'{START_AMPM_VAL}' => $start_ampm_list,
		'{DAYS_VAL}' => $form['end_days'],
		'{HOURS_VAL}' => $form['end_hours'],
		'{MINUTES_VAL}' => $form['end_minutes'],
		'{CONTACT_CAPTION}' => $lang_add_event_view['contact_details_label'],
		'{CONTACT_LABEL}' => $lang_add_event_view['contact_info'],
		'{CONTACT_VAL}' => isset($form['contact'])?$form['contact']:"",
		'{EMAIL_LABEL}' => $lang_add_event_view['contact_email'],
		'{EMAIL_VAL}' => isset($form['email'])?$form['email']:"",
		'{URL_LABEL}' => $lang_add_event_view['contact_url'],
		'{URL_VAL}' => isset($form['url'])?$form['url']:"",
		'{OTHERS_CAPTION}' => $lang_add_event_view['other_details_label'],
		'{CURRENT_PIC_LABEL}' => $lang_add_event_view['picture_file'],
		'{CURRENT_PIC}' => "upload/" . $orig_picture,
		'{DEL_PIC_LABEL}' => $lang_add_event_view['del_picture'],
		'{DEL_PIC_STATUS}' => $del_picture,
		'{ORIG_PICTURE}' => $orig_picture,
		'{UPLOAD_LABEL}' => $lang_add_event_view['picture_file'],
		'{UPLOAD_REQS}' => $upload_reqs,
		'{ADMIN_CAPTION}' => $lang_add_event_view['admin_options_label'],
		'{AUTO_APPR_LABEL}' => $lang_add_event_view['auto_appr_event'],
		'{AUTO_APPR_STATUS}' => $auto_approve,
		'{SUBMIT}' => $submit,
		'{RECUR_CAPTION}' => $lang_add_event_view['repeat_event_label'],
		'{RECUR_METHOD_CAPTION}' => $lang_add_event_view['repeat_method_label'],
		'{EXPAND}' => $lang_general['expand'],
		'{COLLAPSE}' => $lang_general['collapse'],
		'{RECURRENCE_CLOSE_SECTION}' => get_display_style('recurrence','close'),
		'{RECURRENCE_OPEN_SECTION}' => get_display_style('recurrence','open'),
		'{RECURRENCE_MESSAGE}' => !empty($form['recur_type_select'])?$lang_add_event_view['event_repeat_msg']:$lang_add_event_view['event_no_repeat_msg'],
		'{RECUR_TYPE_NONE}' => $lang_add_event_view['repeat_none'],
		'{RECUR_TYPE_NONE_CHECKED}' => !((int)$form['recur_type_select'])?"checked":"",
		'{RECUR_TYPE_1_CHECKED}' => ((int)$form['recur_type_select'] == 1)?"checked":"",
		'{RECUR_EVERY}' => $lang_add_event_view['repeat_every'],
		'{RECUR_VAL_1}' => $form['recur_val_1'],
		'{RECUR_TYPE_1_OPTIONS}' => $recur_type_1_options,
		'{RECUR_END_DATE_CAPTION}' => $lang_add_event_view['repeat_end_date_label'],
		'{RECUR_END_DATE_NONE_CHECKED}' => !((int)$form['recur_end_type'])?"checked":"",
		'{RECUR_END_DATE_NONE}' => $lang_add_event_view['repeat_end_date_none'],
		'{RECUR_END_DATE_COUNT_CHECKED}' => ((int)$form['recur_end_type'] == 1)?"checked":"",
		'{RECUR_END_DATE_COUNT}' => sprintf($lang_add_event_view['repeat_end_date_count'],'<input type="text" name="recur_end_count" value="'.$form['recur_end_count'].'" size="2" class="textinput">'),
		'{RECUR_END_DATE_UNTIL_CHECKED}' => ((int)$form['recur_end_type'] == 2)?"checked":"",
		'{RECUR_END_DATE_UNTIL}' => $lang_add_event_view['repeat_end_date_until'],
		'{RECUR_UNTIL_DAY_VAL}' => $recur_until_day_list,
		'{RECUR_UNTIL_MONTH_VAL}' => $recur_until_month_list,
		'{RECUR_UNTIL_YEAR_VAL}' => $recur_until_year_list
		
	);
	echo template_eval($template_add_event_form, $params);
	endtable();
}
	
function display_cat_form($target, $mode, $form) {
/* Display category form */
	
	global $CONFIG, $template_cat_form, $THEME_DIR, $errors, $lang_cat_admin_data, $lang_general;
	// build category list

	$admin_auto_approve = (isset($form['adminapproved']) && $form['adminapproved'])?"checked":"";
	$user_auto_approve = (isset($form['userapproved']) && $form['userapproved'])?"checked":"";
	$cat_status = (isset($form['status']) && $form['status'])?"checked":"";	

	if(!$errors) template_extract_block($template_cat_form, 'errors_row');

	switch($mode) {
		case "add": 
			starttable("100%",$lang_cat_admin_data['add_cat'],2);
			$submit = $lang_cat_admin_data['add_cat'];
			break;
		case "edit":
			starttable("100%",$lang_cat_admin_data['edit_cat']." [id{$form['cat_id']}] '{$form['cat_name']}'",2);
			$submit = $lang_cat_admin_data['update_cat'];
			break;

		default:
			starttable("100%",$lang_cat_admin_data['add_cat'],2);
			$submit = $lang_cat_admin_data['add_cat'];
	}

	$params = array(
		'{TARGET}' => $target,
		'{MODE}' => $mode,
		'{CAT_ID}' => isset($form['cat_id'])?$form['cat_id']:"",
		'{ERRORS}' => $lang_general['errors'],
		'{ERROR_MSG}' => $errors,
		'{CAT_DETAILS_CAPTION}' => $lang_cat_admin_data['general_info_label'],
		'{CAT_NAME_LABEL}' => $lang_cat_admin_data['cat_name'],
		'{CAT_NAME_VAL}' => isset($form['cat_name'])?$form['cat_name']:"",
		'{DESC_LABEL}' => $lang_cat_admin_data['cat_desc'],
		'{DESC_VAL}' => isset($form['description'])?$form['description']:"",
		'{COLOR_LABEL}' => $lang_cat_admin_data['cat_color'],
		'{COLOR}' => isset($form['color'])?$form['color']:"",
		'{PICK_COLOR_ICON}' => $THEME_DIR . "/images/icon-colorpicker.gif",
		'{PICK_COLOR_LNK}' => "include/colorpicker.php",
		'{PICK_COLOR}' => $lang_cat_admin_data['pick_color'],
		'{STATUS_LABEL}' => $lang_cat_admin_data['status_label'],
		'{STATUS_CHK}' => $cat_status,
		'{STATUS_ACTIVE_LABEL}' => $lang_cat_admin_data['active_label'],
		'{ADMIN_CAPTION}' => $lang_cat_admin_data['admin_label'],
		'{USR_APPR_LABEL}' => $lang_cat_admin_data['auto_user_appr'],
		'{ADMIN_APPR_LABEL}' => $lang_cat_admin_data['auto_admin_appr'],
		'{ADMIN_APPR_CHK}' => $admin_auto_approve,
		'{USR_APPR_CHK}' => $user_auto_approve,
		'{SUBMIT}' => $submit
	);

	echo template_eval($template_cat_form, $params);
	endtable();
}

function display_user_form($target, $mode, $form) {
/* Display user form */
	
	global $CONFIG, $template_user_form, $THEME_DIR, $errors, $lang_user_admin_data, $lang_general;

	// build group list
	$query = "SELECT group_id, group_name FROM ".$CONFIG['TABLE_GROUPS'];
	$result = db_query($query);
	$group_list = "\t<option value='' style='color: #666666'>".$lang_user_admin_data['select_group']."</option>\n";
	while ($row = db_fetch_object($result))
	{
		$selected = "";
		if(isset($form['group_id']))
			$selected = ($row->group_id == $form['group_id'])?"selected":"";
		$group_list .= "\t<option value='".$row->group_id."' $selected>".$row->group_name."</option>\n";
	}

	$user_status = (isset($form['user_status']) && $form['user_status'])?"checked":"";	
	if(!$errors) template_extract_block($template_user_form, 'errors_row');

	switch($mode) {
		case "add": 
			starttable("100%",$lang_user_admin_data['add_user'],2);
			$submit = $lang_user_admin_data['add_user'];
			template_extract_block($template_user_form, 'static_username_row');
			template_extract_block($template_user_form, 'info_row');
			break;
		case "edit":
			starttable("100%", $lang_user_admin_data['edit_user']." [id{$form['user_id']}] '{$form['username']}'",2);
			$submit = $lang_user_admin_data['update_user'];
			template_extract_block($template_user_form, 'dynamic_username_row');
			break;

		default:
			starttable("100%",$lang_user_admin_data['add_user'],2);
			$submit = $lang_user_admin_data['add_user'];
	}
	
	$info_msg = $lang_user_admin_data['update_pass_info'];
	
	$params = array(
		'{TARGET}' => $target,
		'{MODE}' => $mode,
		'{USER_ID}' => isset($form['user_id'])?$form['user_id']:"",
		'{ERRORS}' => $lang_general['errors'],
		'{ERROR_MSG}' => $errors,
		'{INFO}' => $lang_general['info'],
		'{INFO_MSG}' => $info_msg,
		'{ACCOUNT_INFO_CAPTION}' => $lang_user_admin_data['account_info_label'],
		'{USER_NAME_LABEL}' => $lang_user_admin_data['user_name'],
		'{USER_NAME_VAL}' => isset($form['username'])?$form['username']:"",
		'{USER_PASS_LABEL}' => $lang_user_admin_data['user_pass'],
		'{USER_PASS_VAL}' => isset($form['password'])?$form['password']:"",
		'{USER_PASS_CONFIRM_LABEL}' => $lang_user_admin_data['user_pass_confirm'],
		'{USER_PASS_CONFIRM_VAL}' => isset($form['password_confirm'])?$form['password_confirm']:"",
		'{USER_EMAIL_LABEL}' => $lang_user_admin_data['user_email'],
		'{USER_EMAIL_VAL}' => isset($form['email'])?$form['email']:"",
		'{GROUP_LABEL}' => $lang_user_admin_data['group_label'],
		'{GROUP_VAL}' => $group_list,
		'{STATUS_LABEL}' => $lang_user_admin_data['status_label'],
		'{STATUS_CHK}' => $user_status,
		'{STATUS_ACTIVE_LABEL}' => $lang_user_admin_data['active_label'],
		'{OTHER_DETAILS_CAPTION}' => $lang_user_admin_data['other_details_label'],
		'{USER_FNAME_LABEL}' => $lang_user_admin_data['first_name'],
		'{USER_FNAME_VAL}' => isset($form['firstname'])?$form['firstname']:"",
		'{USER_LNAME_LABEL}' => $lang_user_admin_data['last_name'],
		'{USER_LNAME_VAL}' => isset($form['lastname'])?$form['lastname']:"",
		'{USER_WEBSITE_LABEL}' => $lang_user_admin_data['user_website'],
		'{USER_WEBSITE_VAL}' => isset($form['user_website'])?$form['user_website']:"",
		'{USER_LOCATION_LABEL}' => $lang_user_admin_data['user_location'],
		'{USER_LOCATION_VAL}' => isset($form['user_location'])?$form['user_location']:"",
		'{USER_OCCUPATION_LABEL}' => $lang_user_admin_data['user_occupation'],
		'{USER_OCCUPATION_VAL}' => isset($form['user_occupation'])?$form['user_occupation']:"",
		'{SUBMIT}' => $submit
	);

	echo template_eval($template_user_form, $params);
	endtable();
}

function display_group_form($target, $mode, $form) {
/* Display group form */
	
	global $CONFIG, $template_group_form, $THEME_DIR, $errors, $lang_group_admin_data, $lang_general;

	$has_admin_access = (isset($form['has_admin_access']) && $form['has_admin_access'])?"checked":"";
	$can_manage_accounts = (isset($form['can_manage_accounts']) && $form['can_manage_accounts'])?"checked":"";
	$can_change_settings = (isset($form['can_change_settings']) && $form['can_change_settings'])?"checked":"";	
	$can_manage_cats = (isset($form['can_manage_cats']) && $form['can_manage_cats'])?"checked":"";	
	$upl_need_approval = (isset($form['upl_need_approval']) && $form['upl_need_approval'])?"checked":"";	

	if(!$errors) template_extract_block($template_group_form, 'errors_row');

	switch($mode) {
		case "add": 
			starttable("100%",$lang_group_admin_data['add_group'],2);
			$submit = $lang_group_admin_data['add_group'];
			break;
		case "edit":
			starttable("100%",$lang_group_admin_data['edit_group']." [id{$form['group_id']}] '{$form['group_name']}'",2);
			$submit = $lang_group_admin_data['update_group'];
			break;

		default:
			starttable("100%",$lang_group_admin_data['add_group'],2);
			$submit = $lang_group_admin_data['add_group'];
	}

	$params = array(
		'{TARGET}' => $target,
		'{MODE}' => $mode,
		'{GROUP_ID}' => isset($form['group_id'])?$form['group_id']:"",
		'{ERRORS}' => $lang_general['errors'],
		'{ERROR_MSG}' => $errors,
		'{GROUP_DETAILS_CAPTION}' => $lang_group_admin_data['general_info_label'],
		'{GROUP_NAME_LABEL}' => $lang_group_admin_data['group_name'],
		'{GROUP_NAME_VAL}' => isset($form['group_name'])?$form['group_name']:"",
		'{DESC_LABEL}' => $lang_group_admin_data['group_desc'],
		'{DESC_VAL}' => isset($form['description'])?$form['description']:"",
		'{ACCESS_LEVEL_CAPTION}' => $lang_group_admin_data['access_level_label'],
		'{HAS_ADMIN_ACCESS_LABEL}' => $lang_group_admin_data['has_admin_access'],
		'{HAS_ADMIN_ACCESS_CHK}' => $has_admin_access,
		'{CAN_MANAGE_ACCOUNTS_LABEL}' => $lang_group_admin_data['can_manage_accounts'],
		'{CAN_MANAGE_ACCOUNTS_CHK}' => $can_manage_accounts,
		'{CAN_CHANGE_SETTINGS_LABEL}' => $lang_group_admin_data['can_change_settings'],
		'{CAN_CHANGE_SETTINGS_CHK}' => $can_change_settings,
		'{CAN_MANAGE_CATS_LABEL}' => $lang_group_admin_data['can_manage_cats'],
		'{CAN_MANAGE_CATS_CHK}' => $can_manage_cats,
		'{UPL_NEED_APPROVAL_LABEL}' => $lang_group_admin_data['upl_need_approval'],
		'{UPL_NEED_APPROVAL_CHK}' => $upl_need_approval,
		'{SUBMIT}' => $submit
	);

	echo template_eval($template_group_form, $params);
	endtable();
}

// function to display a legend of categories
function display_cat_legend ($colspan = '', $today = false)
{
	global $CONFIG;
	
	$categories = get_active_categories();
	theme_cat_legend ($categories, $colspan, $today);
}

// HTML template for the list of categories and their corresponding colors
function theme_cat_legend ($categories, $colspan = '', $today = false)
{
	global $template_cat_legend, $CONFIG, $lang_general, $ME, $todayclr;
	if(!$colspan) $colspan = "1";
	
	$template_cat_legend1 = $template_cat_legend;
	$header_row = template_extract_block($template_cat_legend1, 'header_row');
	$start_col_row = template_extract_block($template_cat_legend1, 'start_col_row');
	$end_col_row = template_extract_block($template_cat_legend1, 'end_col_row');
	$today_row = template_extract_block($template_cat_legend1, 'today_row');
	$cats_row = template_extract_block($template_cat_legend1, 'cats_row');
	$empty_cell_row = template_extract_block($template_cat_legend1, 'empty_cell_row');
	$footer_row = template_extract_block($template_cat_legend1, 'footer_row');

	$columns = (int)$CONFIG['legend_cat_columns'];
	
	
	
	$params = array(
		'{ROWS}' => $colspan
	);
	echo template_eval($header_row, $params);

	$cat_count = count($categories); // 
	$rows = ceil(( $cat_count + 1) / $columns); // total number of rows
	$row = 0; // used to count rows in <tr> loop

	while($row < $rows) {
		echo $start_col_row;
		for($column=0;$column < $columns;$column++ ) {
			if($today && $column == 0 && $row == 0) {
				$params = array(
					'{TODAY}' => $lang_general['today'],
					'{COLOR}' => $todayclr
				);
				echo template_eval($today_row, $params);
			} elseif($cat_count) {
				list(,$category) = each($categories);
				$params = array(
					'{CAT_NAME}' => $category["cat_name"],
					'{CAT_LINK}' => 'href="'.$ME.'?mode=cat&cat_id='.$category['cat_id'].'"',
					'{COLOR}' => $category['color']
				);
				echo template_eval($cats_row, $params);
				$cat_count--;
			} else 	echo $empty_cell_row;
		}
		echo $end_col_row;
		$row++; // increase row number for next loop
	}
	echo $footer_row;
}

// Eval a template (substitute vars with values)
function template_eval(&$template, &$vars)
{
        return strtr($template, $vars);
}


// Extract and return block '$block_name' from the template, the block is replaced by $subst
function template_extract_block(&$template, $block_name, $subst='')
{
        if(!$template) return;
        $pattern = "#(<!-- BEGIN $block_name -->)(.*?)(<!-- END $block_name -->)#s";
        if ( !preg_match($pattern, $template, $matches)){
                die ('<b>Template error<b><br />Failed to find block \''.$block_name.'\' in :<br /><pre>'.htmlspecialchars($template).'</pre>');
        }
        $template = str_replace($matches[1].$matches[2].$matches[3], $subst, $template);
        return $matches[2];
}

// Highlight found keywords in a given string and return the processed string 
function highlight($keyword,$string,$startTag,$endTag)
{
	$newString = "";
	$positions = array();
	$lastPos = 0;
	$stringLength = strlen($string);
	$length = strlen($keyword);
	$start = strpos(strtolower($string), strtolower($keyword));

	if (is_integer($start)) {
		$positions[] = $start; 
	}

	while(is_integer($start = strpos(substr(strtolower($string),$start+$length), strtolower($keyword))))
	{
		if (is_integer($start)) {
			$count=count($positions) - 1;
			$start = $positions[$count]+$start+$length;
			$positions[] = $start;
		}
	}

	if(count($positions))
	{
		foreach($positions as $pos) {
			$newString .= substr($string,$lastPos,$pos - $lastPos).$startTag.substr($string,$pos,$length).$endTag;
			$lastPos = $pos +$length;
		}
	}

	$newString .= substr($string,$lastPos,$stringLength - $lastPos);
	return $newString;

}

function colorHighlight($hexColor) {
  // highlights a color by increasing it's luminosity
	$temp = hexdec(substr($hexColor, 1)) - hexdec("140A04");
	return "#".dechex($temp);
}

function extcal_get_local_time ($target_timezone = '') {
	global $CONFIG;
	if(!$target_timezone) $target_timezone = $CONFIG['timezone'];
	$zonedate = mktime(gmdate('G'), gmdate('i'), gmdate('s'), gmdate('n'),
	gmdate('j'), gmdate('Y'), 0) + ($target_timezone * 3600);

	return $zonedate;
}

function extcal_12to24hour($hour,$mode) {
	// converts 12hours format to 24hours
	if($mode == 'am') return $hour%12;
	else return $hour%12 + 12;
}

function extcal_24to12hour($hour) {
	// converts 24hours format to 12hours with am/pm flag
	$new_time[0] = ($hour%12)?$hour%12:12;
	$new_time[1] = ($hour>12)?false:true; // AM (true) / PM (false)
	return $new_time;
}

function format_text($string,$no_slashes = false,$ucwords = false) {
	// processes a given text and returns it
	$string = ($ucwords)?ucwords($string):$string;
	//$string = nl2br(html_entities($string));
	$string = nl2br($string);
	if($no_slashes)
		$string = stripslashes($string);
	return $string;
}

function sub_string($string,$max,$suffix) {
	// returns a substring that may be encoded in utf-8 or other character encodings. 
	// and adds a suffix in case the substring is smaller than the original string 
	global $CONFIG;
	if($CONFIG['charset'] == "utf-8") {
		if(preg_match('/(.{1,'.$max.'})/u', $string, $matches)) $new_string = $matches[0];
		else $new_string = $string; // this state occurs if the string contains chars with mixed encodings
	} else {
		$new_string = substr($string,0,$max);
	}
	$new_string = strlen($new_string)==strlen($string)?$new_string:$new_string.$suffix;
	return $new_string;
}

function html_entities($string) {
   // replaces all html entities except 'double' encoding of the ampersands that are already existant
   $translation_table = get_html_translation_table (HTML_ENTITIES,ENT_QUOTES);
   $translation_table[chr(38)] = '&';
   return preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,4};)/","&amp;" , strtr($string, $translation_table));
} 

function html_decode($string) 
{
   $trans_tbl = get_html_translation_table(HTML_ENTITIES);
   $trans_tbl = array_flip($trans_tbl);
   return strtr($string, $trans_tbl);
}


function strip_querystring($url) {
	// takes a URL and returns it without the querystring portion
	if ($commapos = strpos($url, '?')) {
		return substr($url, 0, $commapos);
	} else {
		return $url;
	}
}

function get_referer() {
	// returns the URL of the HTTP_REFERER without the querystring portion
	$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
	return strip_querystring($referer);
}

function me() {
	// returns the name of the current script, without the querystring portion.
	if (getenv("REQUEST_URI")) {
	$me = getenv("REQUEST_URI");
	} elseif (getenv("PATH_INFO")) {
	$me = getenv("PATH_INFO");
	} elseif ($GLOBALS["PHP_SELF"]) {
	$me = $GLOBALS["PHP_SELF"];
	}
	return strip_querystring($me);
}

function qualified_me() {
	// returns current URL
	$HTTPS = getenv("HTTPS");
	$SERVER_PROTOCOL = getenv("SERVER_PROTOCOL");
	$HTTP_HOST = getenv("HTTP_HOST");
	$protocol = (isset($HTTPS) && $HTTPS == "on") ? "https://" : "http://";
	$url_prefix = "$protocol$HTTP_HOST";
	return $url_prefix . me();
}

function match_referer($good_referer = "") {
	// returns true if the referer is the same as the good_referer. 
	// If good_refer is not specified, use qualified_me as the good_referer
	if ($good_referer == "") { $good_referer = qualified_me(); }
	return $good_referer == get_referer();
}

function extcal_dir_list($dirname)
{	
	$handle=opendir($dirname);
	while ($file = readdir($handle))
	{
			$excluded_files = array('.','..','Thumbs.db');
   		if(in_array($file,$excluded_files)) continue;
   		if(is_dir($dirname.$file)) continue;
   		$result_array[]=$file;
 	}
 	closedir($handle);
 	return $result_array;
}

function extcal_get_picture_file($file) {
	global $CONFIG;
	if($file) {
		if(file_exists($CONFIG['MINI_PICS_DIR'].$file.".jpg")) $file = $file.".jpg";
		elseif(file_exists($CONFIG['MINI_PICS_DIR'].$file.".gif")) $file = $file.".jpg";
		else $file = $CONFIG['mini_cal_def_picture'];
	} else $file = $CONFIG['mini_cal_def_picture'];
	return $file;
}

function process_content($data)
{
/* Process message data with various conversions */

	global $CONFIG, $CFG;

	if ($CONFIG['addevent_allow_html'])
	{

		$data = preg_replace("/\[B\](.*?)\[\/B\]/si", "<strong>\\1</strong>", $data);
		$data = preg_replace("/\[I\](.*?)\[\/I\]/si", "<em>\\1</em>", $data);
		$data = preg_replace("/\[U\](.*?)\[\/U\]/si", "<u>\\1</u>", $data);

		$data = preg_replace("/\[LEFT\](.*?)\[\/LEFT\]/si", "<div align='left'>\\1</div>", $data);
		$data = preg_replace("/\[CENTER\](.*?)\[\/CENTER\]/si", "<div align='center'>\\1</div>", $data);
		$data = preg_replace("/\[RIGHT\](.*?)\[\/RIGHT\]/si", "<div align='right'>\\1</div>", $data);

		$data = preg_replace("/\[URL\](http:\/\/)?(.*?)\[\/URL\]/si", "<A HREF=\"http://\\2\" target=\"_blank\">\\2</A>", $data);
		$data = preg_replace("/\[URL=(http:\/\/)?(.*?)\](.*?)\[\/URL\]/si", "<A HREF=\"http://\\2\" target=\"_blank\">\\3</A>", $data);
		$data = preg_replace("/\[EMAIL\](.*?)\[\/EMAIL\]/si", "<A HREF=\"mailto:\\1\" style=\"color:#446699\">\\1</A>", $data);
		$data = preg_replace("/\[IMG\](.*?)\[\/IMG\]/si", "<IMG border=0 SRC=\"\\1\">", $data);

		/* adding a space to beginning */
		$data = " ".$data;
		
		$data = preg_replace("#([\n ])([a-z]+?)://([^,<> \n\r]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>", $data);

		$data = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[^,<> \n\r]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $data);
	
		$data = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([^,<> \n\r]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $data);

		/* Remove space */
		$data = substr($data, 1);

	}
	return $data;

}

// Get the week number in ISO 8601:1988 format
function get_week_number($day, $month, $year) {
 global $CONFIG;
 if($CONFIG['day_start']) $week = strftime("%W", mktime(0, 0, 0, $month, $day, $year));
 else $week = strftime("%U", mktime(0, 0, 0, $month, $day, $year));
 $yearBeginWeekDay = strftime("%w", mktime(0, 0, 0, 1, 1, $year));
 $yearEndWeekDay  = strftime("%w", mktime(0, 0, 0, 12, 31, $year)); 
 // make the checks for the year beginning
 if($yearBeginWeekDay > 0 && $yearBeginWeekDay < 5) {
  // First week of the year begins during Monday-Thursday.
  // Currently first week is 0, so all weeks should be incremented by one
  $week++;
 } else if($week == 0) {
  // First week of the year begins during Friday-Sunday.
  // First week should be 53, and other weeks should remain as they are
  $week = 53;
 }
 // make the checks for the year end, these only apply to the weak 53
 if($week == 53 && $yearEndWeekDay > 0 && $yearEndWeekDay < 5) {
  // Currently the last week of the year is week 53.
  // Last week of the year begins during Friday-Sunday
  // Last week should be week 1
  $week = 1;
 }
 // return the correct ISO 8601:1988 week
 return $week;
}

// Get the week's first and last days
function get_week_bounds($day, $month, $year) {
 global $dayOfWeek, $CONFIG;
	if($CONFIG['day_start']) { // if monday is the first day
		$dayOfWeek = (strftime("%w", mktime(0,0,0,$month,$day,$year)) - 1); // weekday as a decimal number [0,6], with 0 representing Monday
  	$dayOfWeek = ($dayOfWeek == -1)?6:$dayOfWeek;
	}
	else  // if sunday is the first day
		$dayOfWeek = strftime("%w", mktime(0,0,0,$month,$day,$year)); // weekday as a decimal number [0,6], with 0 representing Sunday
	
  // first day of week
  $offset = $dayOfWeek;
  $week = Array();
  $week['first_day']['year'] = date("Y", mktime(0,0,0,$month,$day - $offset,$year) );
  $week['first_day']['month'] = date("m", mktime(0,0,0,$month,$day - $offset,$year));
  $week['first_day']['day'] = date("d", mktime(0,0,0,$month,$day - $offset,$year));
  // last day of week
  $offset=(6 - $dayOfWeek);
  $week['last_day']['year']  = date("Y", mktime(0,0,0,$month,$day + $offset,$year) );
  $week['last_day']['month']  = date("m", mktime(0,0,0,$month,$day + $offset,$year));
  $week['last_day']['day']  = date("d", mktime(0,0,0,$month,$day + $offset,$year));
  return $week;
}

function timetoduration ($seconds, $periods = null) {
  // Force the seconds to be numeric        
  $seconds = (int)$seconds;                
  // Define our periods        
  if (!is_array($periods)) {            
  	$periods = array (                    
  	//'years'     => 31556926,                    
  	//'months'    => 2629743,                    
  	//'weeks'     => 604800,                    
  	'days'      => 86400,                    
  	'hours'     => 3600,                    
  	'minutes'   => 60,                    
  	//'seconds'   => 1                    
  	);        
  }        
  // Loop through        
  foreach ($periods as $period => $value) {            
  	$count = floor($seconds / $value);            
  	$values[$period] = $count;            
  	if ($count == 0) {                
  		continue;            
  	}            
  	$seconds = $seconds % $value;        
  }        
  // Return array        
  if (empty($values)) {            
  	$values = null;        
  }
  
  return $values;    
}

function datestoduration ($start_date, $end_date, $periods = null) {

	$seconds = strtotime($end_date) - strtotime($start_date);
  // Force the seconds to be numeric        
  $seconds = (int)$seconds;                
  // Define our periods        
  if (!is_array($periods)) {            
  	$periods = array (                    
  	//'years'     => 31556926,                    
  	//'months'    => 2629743,                    
  	//'weeks'     => 604800,                    
  	'days'      => 86400,                    
  	'hours'     => 3600,                    
  	'minutes'   => 60,                    
  	//'seconds'   => 1                    
  	);        
  }        
  // Loop through        
  foreach ($periods as $period => $value) {            
  	$count = floor($seconds / $value);            
  	$values[$period] = $count;            
  	if ($count == 0) {                
  		continue;            
  	}            
  	$seconds = $seconds % $value;        
  }        
  // Return array        
  if (empty($values)) {            
  	$values = null;        
  }
  
// fix the all day value
	if(date("G:i",strtotime($end_date)) == "23:59") { 
		$values['days']++;
		$values['hours'] = 0;
		$values['minutes'] = 0;
	} 
	
  return $values;    
}

// Load and parse the template.html file
function load_template()
{
  global $THEME_DIR, $CONFIG, $template_header, $template_footer, $meta_content, $lang_general;

	$use_db_header = false;
	$use_db_footer = false;
	$use_db_meta = false;
	$header_content	= '';
	$footer_content	= '';
	$meta_content	= '';

	// retrieve template data from db
	$query = "SELECT * FROM ".$CONFIG['TABLE_TEMPLATES'];
	$result = db_query($query);
	while ($row = db_fetch_array($result))
	{
		switch($row['template_type']) {
			case "header":
				$use_db_header = $row['template_status']?true:false;
				$header_content = $row['template_value'];
				break;
			case "footer":
				$use_db_footer = $row['template_status']?true:false;
				$footer_content = $row['template_value'];
				break;
			case "meta":
				if($row['template_status']) $meta_content = $row['template_value'];
				break;
			default:
			}
	}
	
	  if (file_exists($CONFIG['FS_PATH']."themes/".$CONFIG['theme']."/" . TEMPLATE_FILE)) {
	      $template_file = $CONFIG['FS_PATH']."themes/".$CONFIG['theme']."/" . TEMPLATE_FILE;
	  } else die("<b>ExtCalendar critical error</b>:<br />Unable to load template file ".TEMPLATE_FILE."!</b>");
	
	  $template = fread(fopen($template_file, 'r'), filesize($template_file));
		
	// Header processing
	if($use_db_header) {
		$cal_pos = strpos($template, "<body ");
	 	$template_header = substr($template, 0, $cal_pos);
		$template_header .= html_decode($header_content);
	} else {
		$cal_pos = strpos($template, "{CONTENT}");
  	$template_header = substr($template, 0, $cal_pos);
  }

	$signature = '<a href="http://extcal.sourceforge.net/" target="_new">ExtCalendar <span style="color:orange">2</span></a>';
	if(strpos(" ".$lang_general['signature'],"%s"))
		$signature = sprintf($lang_general['signature'], $signature);
	else 
		$signature = $lang_general['signature'] . " " . $signature;
	$add_signature = '<div class="atomic">'.$signature.'</div><br />';

	// Footer processing
	if($use_db_footer) {
		$template_footer = $add_signature.html_decode($footer_content);
	} else {
		$cal_pos = strpos($template, "{CONTENT}");
	  $template = str_replace("{CONTENT}", $add_signature ,$template);
		$template_footer = substr($template, $cal_pos);
	}
	
	$add_version_info = '<!--ExtCalendar '.$CONFIG['release_name'].'--></body>';

  $template_footer = ereg_replace("</body[^>]*>",$add_version_info,$template_footer);
}

function get_version_readable($version = "200.00") {
	// returns a readable version (Major.Minor.CVS) out of a string version
	$matches = explode(".",$version);
	$major_version = intval((int)$matches[0]/100);
	$minor_version = $major_version * 100 - intval($matches[0]);
	$cvs_version = intval($matches[1]);
	return $major_version.".".$minor_version.".".$cvs_version;
}	

function get_events($date_stamp, $include_recurrent = false) {
	// return events that occur at a specific date
  global $CONFIG, $cat_id;
	
	if(empty($date_stamp)) return false;
	
	$cat_filter = "";
  // generate the sql query for a specific date
  $day_pattern = date("Ymd", $date_stamp); // day pattern: 20041231 for 'December 31, 2004'
  $event_condition = '';

  switch($CONFIG['multi_day_events']) {
  	case "bounds":
		  $event_condition = "(DATE_FORMAT(e.start_date,'%Y%m%d') = $day_pattern OR DATE_FORMAT(e.end_date,'%Y%m%d') = $day_pattern)";
  	
  		break;
  	case "start":
		  $event_condition = "(DATE_FORMAT(e.start_date,'%Y%m%d') = $day_pattern)";
  	
  		break;
  	case "all":
		default:  		
		  $event_condition = "(DATE_FORMAT(e.start_date,'%Y%m%d') <= $day_pattern AND DATE_FORMAT(e.end_date,'%Y%m%d') >= $day_pattern)";
  	
  		break;
  }
  
  $query = "SELECT e.id, start_date, end_date from " . $CONFIG['TABLE_EVENTS'] . " AS e LEFT JOIN " . $CONFIG['TABLE_CATEGORIES'] . " AS c ON e.cat=c.cat_id ";
  $query .= "WHERE ".$event_condition." AND c.enabled = '1' AND approved = '1' ";
	if(isset($cat_id) && is_numeric($cat_id)) $query .= "AND e.cat = '".$cat_id."' "; 
  $query .= get_event_sort_order($CONFIG['sort_order']);
  $result = db_query($query);

	$events = array();
  
  while ($row = db_fetch_row($result))
  {
  	$events[] = array($row[0],strtotime($row[1]),strtotime($row[2]));
	}
	
	if($include_recurrent) {
		// calculate recurrent events
		if(isset($cat_id) && is_numeric($cat_id)) $cat_filter .= "AND e.cat = '".$cat_id."'"; 
	  $query = "SELECT e.id, recur_type, recur_val, recur_until, start_date, end_date, recur_end_type, recur_count from " . $CONFIG['TABLE_EVENTS'] . " AS e LEFT JOIN " . $CONFIG['TABLE_CATEGORIES'] . " AS c ON e.cat=c.cat_id ";
	  $query .= "WHERE (DATE_FORMAT(e.end_date,'%Y%m%d') < $day_pattern) AND c.enabled = '1' AND approved = '1' AND recur_type <> '' $cat_filter ORDER BY start_date,title ASC";
	  $result1 = db_query($query);
		$recur_events = array();
  
	  while ($row = db_fetch_row($result1))
	  {
	  	$event = new Event();
	  	//$event->loadEvent($row[0]);
	  	$event->recType = $row[1]; // pass recur_type to event object
	  	$event->recInterval = (int)$row[2]; // pass recur_interval to event object
	  	$event->recEndDate = strtotime($row[3]." 00:00:00")-strtotime("0000-00-00 00:00:00")?strtotime($row[3]." 23:59:59"):false; // pass recur_until to event object
	  	$event->setStartDate(strtotime($row[4])); // convert start_date to timestamp and pass it to event object
	  	$event->setEndDate(strtotime($row[5])); // convert end_date to timestamp and pass it to event object
	  	$event->recEndType = (int)$row[6]; 
	  	$event->recEndCount = (int)$row[7]; 
	  	if($event->isRecurrentOn($date_stamp))
	  		$recur_events[] = array($row[0],$event->recurStartDate,$event->recurEndDate);
		}

		$events = array_merge($events,$recur_events);
	}
	return is_array($events)?$events:false;
}

function get_cat_info ($cat_id) {
// function that returns a category name if it exists, given a cat_id	
	global $CONFIG;
	
	if(!$cat_id) return false;
	$query = "SELECT cat_name, description FROM " . $CONFIG['TABLE_CATEGORIES'] . " WHERE enabled = 1 and cat_id = '$cat_id'";
	$results = db_query($query);
	if(!db_num_rows($results)) return false;
	list($cat_name,$description) = db_fetch_array($results);
	$cat_info = array('cat_name' => $cat_name, 'cat_desc' => $description );
	db_free_result($results);
	
	return $cat_info;	
}

function get_active_categories() {
// function that returns a list of categories that a user is allowed to see	
	global $CONFIG;
	
	$query = "SELECT cat_id, cat_name, description AS cat_desc, color FROM " . $CONFIG['TABLE_CATEGORIES'] . " WHERE enabled = 1";
	$results = db_query($query);
	if(!db_num_rows($results)) return false;

	$cat_list = array();
	while ($row = db_fetch_array($results))
	{
		$cat_list[] = $row;
	}
	db_free_result($results);
	
	return $cat_list;	
}

function sort_events($events, &$event_stack, $date_stamp) {

 	while (list(,$event_info) = each($events))
 	{
    $event_style = Event::get_style($date_stamp,$event_info[1],$event_info[2]);

  	if($event_style=="eventstart" && !in_array($event_info[0], $event_stack)) $event_stack[] = $event_info[0];
	}
	
	reset($events);
 	while (list(,$event_info) = each($events))
 	{
    $event_style = Event::get_style($date_stamp,$event_info[1],$event_info[2]);

  	if($event_style=="eventend" && in_array($event_info[0], $event_stack)) {
  		for($key=0;$key<count($event_stack);$key++)
  			if($event_stack[$key]==$event_info[0]) break;
  		array_splice($event_stack, $key);
  	}
	}
	reset($events);
	return $events;
}

function get_event_sort_order($sort_order) {
// create the sql portion "ORDER BY" for records sorting	

	switch($sort_order) {
		case "ta": // title ascending
			$sql_string = "ORDER BY title, start_date";
			break;
		case "td": // title ascending
			$sql_string = "ORDER BY title, start_date DESC";
			break;
		case "da": // date ascending
			$sql_string = "ORDER BY start_date, title";
			break;
		case "dd": // date descending
			$sql_string = "ORDER BY start_date, title DESC";
			break;
		default:
			$sql_string = "ORDER BY event_id";
	}
	return $sql_string;
}

function get_display_style ($name,$type) {
	global $CONFIG;
	$status = 0;
	$return_value = array("display: none","display: show");
	$cookie_name = $CONFIG['cookie_name']."_hidden_display";
	$items = explode(',',$_COOKIE[$cookie_name]);
	$status = in_array($name,$items);
	if($type == "close")
		return $status?$return_value[1]:$return_value[0]; 
	elseif($type == "open")
		return $status?$return_value[0]:$return_value[1]; 
}

function invoke_code($component,$params) {
	// function that invokes a component with and returns output for display
	global $CONFIG, $lang_system;
	
	switch($component) {
		case "minicalendar":
			$output_buffer = print_mini_cal_view($params);
			return array('status' => true, 'html' => $output_buffer);
			break;
		default:
			return array('status' => false, 'html' => $lang_system['unknown_component']);
	}
}

//function for mini calendar invocation
function print_mini_cal_view($params = '')	{
	// function to display monthly events
	global $CONFIG, $today, $lang_mini_cal, $lang_system, $THEME_DIR, $ME; 
	global $lang_general, $lang_date_format, $event_icons, $todayclr, $cat_id, $extcal_code_insert; 
	
	ob_start();    
	$navigation_controls = isset($params['navigation_controls'])?$params['navigation_controls']:true;
	$info_data['navigation_controls'] = $navigation_controls;
	$target = isset($params['target'])?$params['target']:"";
	$info_data['target'] = $target;
	$picture = isset($params['picture'])?$params['picture']:$CONFIG['mini_cal_diplay_options'];
	//$date_type = isset($params['date_type'])?$params['date_type']:'dynamic';

	// process the date parameter
	if (!isset($params['date']) || empty($params['date']))
	{
		// select today's date if no date passed as parameter
		$day = $today['day'];
		$month = $today['month'];
		$year = $today['year'];
	} elseif(is_array($params['date'])) {
		// if date is passed as array with day,month and year as indexes
		$day = (int)$params['date']['day'];
		$month = (int)$params['date']['month'];
		$year = (int)$params['date']['year'];
	} else {
		// if date is passed as url string. e.g. 2005-01-21
		list($year, $month, $day) = split('[/.-]', $params['date']); // split at a slash, dot, or hyphen.
		$day = (int)$day;
		$month = (int)$month;
		$year = (int)$year;
	}
		
	// check if "show past events" is enabled, else force the date to today's date
	if(mktime(0,0,0,$month,$day,$year) < mktime(0,0,0,$today['month'],1,$today['year']) && !$CONFIG['archive']) {
		$info_data['day_link'] = false;
	} else $info_data['day_link'] = true;

	// insert date into an array an pass it to the mini calendar theme function
	$target_date = array(
		'day' => $day,
		'month' => $month,
		'year' => $year
	);

	$pic_message = ucwords(strftime ($lang_date_format['full_date'], extcal_get_local_time()))."\n";
	
	switch($picture) {
		case '0': // Picture not displayed
		case 'none':
			$z = '';
			break;
		case '1': // Default Picture
			$z = $CONFIG['mini_cal_def_picture'];
			$pic_message .= $lang_mini_cal['def_pic'];
			break;
		case '2': // Daily Picture
		case 'daily':
			$z = (int)date("z",mktime(0,0,0,$month,$day,$year)); // 1 through 365
			$pic_message .= sprintf($lang_mini_cal['daily_pic'],$z);
			$z = extcal_get_picture_file($z);
			break;
		case '3': // Weekly Picture
		case 'weekly':
			$z = (int) get_week_number($day, $month, $year); // 1 through 53
			$pic_message .= sprintf($lang_mini_cal['weekly_pic'],$z);
			$z = extcal_get_picture_file($z);
			break;
		case '4': // Monthly Picture
		case 'monthly':
			$z = (int)date("m",mktime(0,0,0,$month,$day,$year)); // 1 through 12
			$pic_message .= sprintf($lang_mini_cal['weekly_pic'],$z);
			$z = extcal_get_picture_file($z);
			break;
		case '5': // Random Picture
		case 'random':
			$pictures = extcal_dir_list($CONFIG['MINI_PICS_DIR']);
			srand((float)microtime() * 1000000);
			shuffle($pictures);
			$z = $pictures[0];
			$pic_message .= sprintf($lang_mini_cal['rand_pic'],$z);
			break;
		default: // Default Picture by default
			$z = $CONFIG['mini_cal_def_picture'];
			$pic_message .= $lang_mini_cal['def_pic'];
	}
	if(!empty($z)) $info_data['picture_info'] = array('picture_message' => $pic_message, 'picture_url' => $z); 
	// number of days in selected month
	$nr = date("t",mktime(0,0,0,$month,1,$year));
	
	$previous_month_date = date("Y-m-d", mktime(0,0,0,$month-1,1,$year));
	$next_month_date = date("Y-m-d", mktime(0,0,0,$month+1,1,$year));

	$info_data['previous_month_url'] = $ME."?date=".$previous_month_date;
	$info_data['next_month_url'] = $ME."?date=".$next_month_date;
	$info_data['current_month_url'] = $CONFIG['calendar_url'];
	
	$info_data['current_month_color'] = ($month == $today['month'] && $year == $today['year'])?"background-color: ".$todayclr:"";
	
	if ($CONFIG['archive'] || ($month != date("n") || $year != date("Y")))
		$info_data['show_past_months'] = true;
	else $info_data['show_past_months'] = false;
	
	// get the weekdays
	for ($i=0;$i<=6;$i++)
	{
		$array_index = $CONFIG['day_start']?($i+1)%7:$i;
		if ($array_index) $css_class = "extcal_weekdays"; // weekdays
		else $css_class = "extcal_weekdays"; // sunday
		$info_data['weekdays'][$i]['name'] = sub_string($lang_date_format['day_of_week'][$array_index],2,'');
		$info_data['weekdays'][$i]['class'] = $css_class;
	}

	
	$event_stack = array();

	// 'existing' days in month
	for ($i=1;$i<=$nr;$i++)
	{
		$date_stamp = mktime(0,0,0,$month,$i,$year);
		// generate the url for each day cell
		$url_target_date = date("Y-m-d", $date_stamp);
		$event_stack[$i]['date_link'] = $info_data['day_link']?$CONFIG['calendar_url']."calendar.php?mode=day&date=".$url_target_date:'';
		// count the number of events occurring in a given date
		$events = get_events($date_stamp,$CONFIG['show_recurrent_events']);
		//$events = sort_events($events, $event_stack, $date_stamp);
		$event_stack[$i]['num_events'] = count($events);
		//$event_stack[$i]['events'] = $events;
		$event_stack[$i]['week_number'] = (int) get_week_number($i, $month, $year);

	}
	
	theme_mini_cal_view($target_date, $event_stack, $info_data);

	$output = ob_get_contents(); // read buffer
	ob_end_clean(); 
	return $output;
}

function get_language_name($language_dir) {
	// returns the name and native name of a given language
	$language_names = array(
		'arabic' => array('Arabic','&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
		'bosnian' => array('Bosnian','Bosanski'),
		'brazilian_portuguese' => array('Portuguese [Brazilian]'),
		'bulgarian' => array('Bulgarian','&#1041;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'),
		'chinese_big5' => array('Chinese-Big5','&#21488;&#28771;'),
		'chinese_gb' => array('Chinese-GB2312','&#20013;&#22269;'),
		'croatian' => array('Croatian(Hrvatski'),
		'czech' => array('Czech(&#x010C;esky'),
		'danish' => array('Danish','Dansk'),
		'dutch' => array('Dutch','Nederlands'),
		'english' => array('English','English'),
		'estonian' => array('Estonian','Eesti'),
		'finnish' => array('Finnish','Suomea'),
		'french' => array('French','Fran&ccedil;ais'),
		'german' => array('German','Deutsch','de'),
		'greek' => array('Greek','&#917;&#955;&#955;&#951;&#957;&#953;&#954;&#940;'),
		'hebrew' => array('Hebrew','&#1506;&#1489;&#1512;&#1497;&#1514;'),
		'hungarian' => array('Hungarian','Magyarul'),
		'indonesian' => array('Indonesian','Bahasa Indonesia'),
		'italian' => array('Italian','Italiano'),
		'japanese' => array('Japanese','&#26085;&#26412;&#35486;'),
		'korean' => array('Korean','&#54620;&#44397;&#50612;'),
		'latvian' => array('Latvian','Latvian'),
		'norwegian' => array('Norwegian','Norsk'),
		'polish' => array('Polish','Polski'),
		'portuguese' => array('Portuguese [Portugal]','Portugu&ecirc;s'),
		'russian' => array('Russian','&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
		'slovak' => array('Slovak','Slovensky'),
		'slovenian' => array('Slovenian','Slovensko'),
		'spanish' => array('Spanish','Espa&ntilde;ol'),
		'swedish' => array('Swedish','Svenska'),
		'thai' => array('Thai','&#3652;&#3607;&#3618;'),
		'turkish' => array('Turkish','T&uuml;rk&ccedil;e'),
		'vietnamese' => array('Vietnamese')
	);
	$name = count($language_names[$language_dir])>=2?$language_names[$language_dir][0]." (".$language_names[$language_dir][1].")":$language_names[$language_dir][0];
	return isset($language_names[$language_dir])?$name:$language_dir;	
}

function update_config_var($var_name, $new_value, $force_mode=false) {
	// Updates an existing config variable in config table
	// when $force_mode == true, the variable is inserted in case it doesn't exist
	global $CONFIG;
	if(isset($CONFIG[$var_name])) {
		$new_value = addslashes($new_value);
		db_query("UPDATE {$CONFIG['TABLE_CONFIG']} SET value = '$new_value' WHERE name = '$var_name'");
		$CONFIG[$var_name] = $new_value; // Update CONFIG array with new value
		return true;
	} elseif($force_mode) {
		// Force the update function to insert the value in case it doesn't exist
		add_config_var($var_name, $new_value);

	}
	return false;
}


function add_config_var($var_name, $value='') {
	// Adds a config variable if it doesn't already exist
	global $CONFIG;
	if(!isset($CONFIG[$var_name])) {
		$var_name = addslashes($var_name);
		$value = addslashes($value);
		db_query("INSERT INTO {$CONFIG['TABLE_CONFIG']} (name, value) VALUES ('$var_name', '$value')");

		if(db_insert_id()) {
			$CONFIG[$var_name] = $new_value; // Insert new variable in CONFIG array
		}
	}
	return;
}

function delete_config_var($var_name) {
	// Deletes an existing config variable from config table
	global $CONFIG;
	if (!isset($CONFIG[$var_name])) return false;
	db_query("DELETE FROM {$CONFIG['TABLE_CONFIG']} WHERE name = '$var_name'");
	return true;
}

?>