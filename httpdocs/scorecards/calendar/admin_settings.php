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
File Description: admin_settings.php - Main Admin Settings
$Id: admin_settings.php,v 1.15 2005/07/07 08:06:11 simoami Exp $ 
**********************************************
Get the latest version of ExtCalendar at:
http://extcal.sourceforge.net//
**********************************************
*/

define('SETTINGS_PHP', true);

require_once "config.inc.php";

require_priv('can_change_settings');

if (defined('SETTINGS_PHP')) 

$lang_config_data = array(
	$lang_settings_data['general_settings_label'],
	array($lang_settings_data['calendar_name'], 'calendar_name', 0),
	array($lang_settings_data['calendar_description'], 'calendar_description', 0),
	array($lang_settings_data['calendar_admin_email'], 'calendar_admin_email', 0),
	array($lang_settings_data['cookie_name'], 'cookie_name', 0),
	array($lang_settings_data['cookie_path'], 'cookie_path', 0),
	array($lang_settings_data['debug_mode'], 'debug_mode', 1),
	array($lang_settings_data['calendar_status'], 'calendar_status', 12),

	$lang_settings_data['env_settings_label'],
	array($lang_settings_data['lang'], 'lang', 5),
	array($lang_settings_data['charset'], 'charset', 4),
	array($lang_settings_data['theme'], 'theme', 6),
	array($lang_settings_data['timezone'], 'timezone', 7),
	array($lang_settings_data['time_format'], 'time_format_24hours', 11),
	array($lang_settings_data['auto_daylight_saving'], 'auto_daylight_saving', 1),
	array($lang_settings_data['main_table_width'], 'main_table_width', 0),
	array($lang_settings_data['day_start'], 'day_start', 9),
	array($lang_settings_data['default_view'], 'default_view', 2),
	array($lang_settings_data['search_view'], 'search_view', 1),
	array($lang_settings_data['archive'], 'archive', 1),
	array($lang_settings_data['events_per_page'], 'events_per_page', 0),
	array($lang_settings_data['sort_order'], 'sort_order', 3),
	array($lang_settings_data['show_recurrent_events'], 'show_recurrent_events', 1),
	array($lang_settings_data['multi_day_events'], 'multi_day_events', 13),
	array($lang_settings_data['legend_cat_columns'], 'legend_cat_columns', 0),

	$lang_settings_data['user_settings_label'],
	array($lang_settings_data['allow_user_registration'], 'allow_user_registration', 1),
	array($lang_settings_data['reg_duplicate_emails'], 'reg_duplicate_emails', 1),
	array($lang_settings_data['reg_email_verify'], 'reg_email_verify', 1),

	$lang_settings_data['event_view_label'],
	array($lang_settings_data['popup_event_mode'], 'popup_event_mode', 1),
	array($lang_settings_data['popup_event_width'], 'popup_event_width', 0),
	array($lang_settings_data['popup_event_height'], 'popup_event_height', 0),

	$lang_settings_data['add_event_view_label'],
	array($lang_settings_data['add_event_view'], 'add_event_view', 1),
	array($lang_settings_data['addevent_allow_html'], 'addevent_allow_html', 1),
	array($lang_settings_data['addevent_allow_contact'], 'addevent_allow_contact', 1),
	array($lang_settings_data['addevent_allow_email'], 'addevent_allow_email', 1),
	array($lang_settings_data['addevent_allow_url'], 'addevent_allow_url', 1),
	array($lang_settings_data['addevent_allow_picture'], 'addevent_allow_picture', 1),
	array($lang_settings_data['new_post_notification'], 'new_post_notification', 1),

	$lang_settings_data['calendar_view_label'],
	array($lang_settings_data['monthly_view'], 'monthly_view', 1),
	array($lang_settings_data['cal_view_show_week'], 'cal_view_show_week', 1),
	array($lang_settings_data['cal_view_max_chars'], 'cal_view_max_chars', 0),

	$lang_settings_data['flyer_view_label'],
	array($lang_settings_data['flyer_view'], 'flyer_view', 1),
	array($lang_settings_data['flyer_show_picture'], 'flyer_show_picture', 1),
	array($lang_settings_data['flyer_view_max_chars'], 'flyer_view_max_chars', 0),

	$lang_settings_data['weekly_view_label'],
	array($lang_settings_data['weekly_view'], 'weekly_view', 1),
	array($lang_settings_data['weekly_view_max_chars'], 'weekly_view_max_chars', 0),

	$lang_settings_data['daily_view_label'],
	array($lang_settings_data['daily_view'], 'daily_view', 1),
	array($lang_settings_data['daily_view_max_chars'], 'daily_view_max_chars', 0),

	$lang_settings_data['categories_view_label'],
	array($lang_settings_data['cats_view'], 'cats_view', 1),
	array($lang_settings_data['cats_view_max_chars'], 'cats_view_max_chars', 0),

	$lang_settings_data['mini_cal_label'],
	array($lang_settings_data['mini_cal_def_picture'], 'mini_cal_def_picture', 0),
	array($lang_settings_data['mini_cal_display_picture'], 'mini_cal_diplay_options',10),

	$lang_settings_data['mail_settings_label'],
	array($lang_settings_data['mail_method'], 'mail_method', 14),
	array($lang_settings_data['mail_smtp_host'], 'mail_smtp_host',0),
	array($lang_settings_data['mail_smtp_auth'], 'mail_smtp_auth',1),
	array($lang_settings_data['mail_smtp_username'], 'mail_smtp_username',0),
	array($lang_settings_data['mail_smtp_password'], 'mail_smtp_password',0),

	$lang_settings_data['picture_settings_label'],
	array($lang_settings_data['max_upl_dim'], 'max_upl_dim', 0),
	array($lang_settings_data['max_upl_size'], 'max_upl_size', 0),
	array($lang_settings_data['picture_chmod'], 'picture_chmod', 0),
	array($lang_settings_data['allowed_file_extensions'], 'allowed_file_extensions',0)

);


function form_admin_sections($name, $default_value = 0)
{
   global $CONFIG, $lang_settings_data, $ME;

  $links = 	array(
  						"admin_settings.php",
  						"admin_settings_template.php",
  						"admin_settings_updates.php"
  					);
  $selected = array();
  $selected[] = ($default_value == sizeof($selected)) ? 'selected' : '';
  $selected[] = ($default_value == sizeof($selected)) ? 'selected' : '';
  $selected[] = ($default_value == sizeof($selected)) ? 'selected' : '';

	echo <<<EOT
<!-- BEGIN link_row -->
		<tr class="tablec">
			<form name="admin_links" action="$ME" method="get">
			<td height="35" colspan="2" nowrap class="tablec" align="right" valign="middle"><span class="atomic">{$lang_settings_data['admin_links_text']}:</span>&nbsp;
				<select name='admin_links' class='listbox' onChange="document.location.replace(this.value);">
            <option value="{$links[0]}" {$selected[0]} >{$lang_settings_data[$name][0]}</option>
            <option value="{$links[1]}" {$selected[1]} >{$lang_settings_data[$name][1]}</option>
            <option value="{$links[2]}" {$selected[2]} >{$lang_settings_data[$name][2]}</option>
				</select>
			</td>
			</form>
		</tr>
<!-- END link_row -->
EOT;
}

function form_label($text)
{
	echo <<<EOT
		<tr>
						<td class="tableh2" colspan="2">
										<strong>$text&nbsp;</strong>
						</td>
		</tr>
EOT;
}

function form_input($text, $name)
{
    global $CONFIG;

    $value = $CONFIG[$name];

    echo <<<EOT
        <tr>
            <td width="60%" class="tableb">
                        $text
        </td>
        <td width="40%" class="tableb" valign="top">
                <input type="text" class="textinput" style="width: 100%" name="$name" value="$value">
                </td>
        </tr>
EOT;
}

function form_yes_no($text, $name)
{
    global $CONFIG, $lang_general;

    $value = $CONFIG[$name];
    $yes_selected = $value ? 'selected' : '';
    $no_selected = !$value ? 'selected' : '';
		$yes = $lang_general['yes'];
		$no = $lang_general['no'];
		
    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="1" $yes_selected>$yes</option>
                                <option value="0" $no_selected>$no</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_start_day($text, $name)
{
    global $CONFIG, $lang_date_format;

    $value = $CONFIG[$name];
    $sunday_select = $value == 0 ? 'selected' : '';
    $monday_select = $value == 1 ? 'selected' : '';
		$sunday = $lang_date_format['day_of_week'][0];
		$monday = $lang_date_format['day_of_week'][1];
		
    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="0" $sunday_select>$sunday</option>
                                <option value="1" $monday_select>$monday</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_sort_order($text, $name)
{
    global $CONFIG, $lang_settings_data;

    $value = $CONFIG[$name];
    $ta_selected = ($value == 'ta') ? 'selected' : '';
    $td_selected = ($value == 'td') ? 'selected' : '';
    $da_selected = ($value == 'da') ? 'selected' : '';
    $dd_selected = ($value == 'dd') ? 'selected' : '';

    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="ta" $ta_selected>{$lang_settings_data['sort_order_title_a']}</option>
                                <option value="td" $td_selected>{$lang_settings_data['sort_order_title_d']}</option>
                                 <option value="da" $da_selected>{$lang_settings_data['sort_order_date_a']}</option>
                                <option value="dd" $dd_selected>{$lang_settings_data['sort_order_date_d']}</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_default_view($text, $name)
{
    global $CONFIG, $lang_settings_data;

    $value = $CONFIG[$name];
    $day_view_select = ($value == 0) ? 'selected' : '';
    $week_view_select = ($value == 1) ? 'selected' : '';
    $cal_view_select = ($value == 2) ? 'selected' : '';
    $flyer_view_select = ($value == 3) ? 'selected' : '';

    $day_view = $lang_settings_data['daily_view_label']; 
    $week_view = $lang_settings_data['weekly_view_label'];
    $cal_view = $lang_settings_data['calendar_view_label'];
    $flyer_view = $lang_settings_data['flyer_view_label']; 
    
    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="0" $day_view_select>$day_view</option>
                                <option value="1" $week_view_select>$week_view</option>
                                <option value="2" $cal_view_select>$cal_view</option>
                                <option value="3" $flyer_view_select>$flyer_view</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_timezone($text, $name)
{
    global $CONFIG;

    $timezone = $CONFIG[$name];
    echo <<<EOT
        <tr>
            <td width="60%" class="tableb">
                        $text
        </td>
        <td width="40%" class="tableb" valign="top">
EOT;
?>
					<select name="<? echo $name?>" class="listbox">
						<option value="-12" <? echo ($timezone == '-12')?"selected":"";?>>(GMT -12:00) Eniwetok, Kwajalein</option>
						<option value="-11" <? echo ($timezone == '-11')?"selected":"";?>>(GMT -11:00) Midway Island, Samoa</option>
						<option value="-10" <? echo ($timezone == '-10')?"selected":"";?>>(GMT -10:00) Hawaii</option>
						<option value="-9" <? echo ($timezone == '-9')?"selected":"";?>>(GMT -9:00) Alaska</option>
						<option value="-8" <? echo ($timezone == '-8')?"selected":"";?>>(GMT -8:00) Pacific Time (US & Canada)</option>
						<option value="-7" <? echo ($timezone == '-7')?"selected":"";?>>(GMT -7:00) Mountain Time (US & Canada)</option>
						<option value="-6" <? echo ($timezone == '-6')?"selected":"";?>>(GMT -6:00) Central Time (US & Canada)</option>
						<option value="-5" <? echo ($timezone == '-5')?"selected":"";?>>(GMT -5:00) Eastern Time (US & Canada)</option>
						<option value="-4" <? echo ($timezone == '-4')?"selected":"";?>>(GMT -4:00) Atlantic Time (Canada)</option>
						<option value="-3.5" <? echo ($timezone == '-3.5')?"selected":"";?>>(GMT -3:30) Newfoundland</option>
						<option value="-3" <? echo ($timezone == '-3')?"selected":"";?>>(GMT -3:00) Brazil, Buenos Aires</option>
						<option value="-2" <? echo ($timezone == '-2')?"selected":"";?>>(GMT -2:00) Mid-Atlantic</option>
						<option value="-1" <? echo ($timezone == '-1')?"selected":"";?>>(GMT -1:00) Azores, Cape Verde Islands</option>
						<option value="0" <? echo ($timezone == '0')?"selected":"";?>>(GMT) Western Europe Time, Casablanca</option>
						<option value="1" <? echo ($timezone == '1')?"selected":"";?>>(GMT +1:00) CET(Central Europe Time)</option>
						<option value="2" <? echo ($timezone == '2')?"selected":"";?>>(GMT +2:00) EET(Eastern Europe Time)</option>
						<option value="3" <? echo ($timezone == '3')?"selected":"";?>>(GMT +3:00) Baghdad, Kuwait, Riyadh</option>
						<option value="3.5" <? echo ($timezone == '3.5')?"selected":"";?>>(GMT +3:30) Tehran</option>
						<option value="4" <? echo ($timezone == '4')?"selected":"";?>>(GMT +4:00) Abu Dhabi, Muscat</option>
						<option value="4.5" <? echo ($timezone == '4.5')?"selected":"";?>>(GMT +4:30) Kabul</option>
						<option value="5" <? echo ($timezone == '5')?"selected":"";?>>(GMT +5:00) Ekaterinburg, Islamabad</option>
						<option value="5.5" <? echo ($timezone == '5.5')?"selected":"";?>>(GMT +5:30) Bombay, Calcutta</option>
						<option value="6" <? echo ($timezone == '6')?"selected":"";?>>(GMT +6:00) Almaty, Dhaka, Colombo</option>
						<option value="7" <? echo ($timezone == '7')?"selected":"";?>>(GMT +7:00) Bangkok, Hanoi</option>
						<option value="8" <? echo ($timezone == '8')?"selected":"";?>>(GMT +8:00) Beijing, Perth</option>
						<option value="9" <? echo ($timezone == '9')?"selected":"";?>>(GMT +9:00) Tokyo, Seoul, Osaka</option>
						<option value="9.5" <? echo ($timezone == '9.5')?"selected":"";?>>(GMT +9:30) Adelaide, Darwin</option>
						<option value="10" <? echo ($timezone == '10')?"selected":"";?>>(GMT +10:00) (East Australian Standard)</option>
						<option value="11" <? echo ($timezone == '11')?"selected":"";?>>(GMT +11:00) Magadan, Solomon Islands</option>
						<option value="12" <? echo ($timezone == '12')?"selected":"";?>>(GMT +12:00) Auckland, Wellington</option>
					</select>			 
<?
		echo <<<EOT
           </td>
        </tr>

EOT;
}

function form_charset($text, $name)
{
    global $CONFIG;

    $charsets = array('Default' => 'language-file',
        'Arabic' => 'iso-8859-6',
        'Baltic' => 'iso-8859-4',
        'Central European' => 'iso-8859-2',
        'Chinese Simplified' => 'euc-cn',
        'Chinese Traditional' => 'big5',
        'Cyrillic' => 'koi8-r',
        'Greek' => 'iso-8859-7',
        'Hebrew' => 'iso-8859-8-i',
        'Icelandic' => 'x-mac-icelandic',
        'Japanese' => 'euc-jp',
        'Korean' => 'euc-kr',
        'Maltese' => 'iso-8859-3',
        'Thai' => 'windows-874 ',
        'Turkish' => 'iso-8859-9',
        'Unicode' => 'utf-8',
        'Vietnamese' => 'windows-1258',
        'Western' => 'iso-8859-1'
        );

    $value = strtolower($CONFIG[$name]);

    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">

EOT;
    foreach ($charsets as $country => $charset) {
        echo "                                <option value=\"$charset\" " . ($value == $charset ? 'selected' : '') . ">$country ($charset)</option>\n";
    }
    echo <<<EOT
                        </select>
                </td>
        </tr>

EOT;
}

function form_language($text, $name)
{
    global $CONFIG, $lang_info, $lang_settings_data;

    $value = strtolower($CONFIG[$name]);
		$language_dir = $CONFIG['LANGUAGES_DIR'];
		
    $dir = opendir($language_dir);
    while ($dir_name = readdir($dir)) {
        if (is_dir($language_dir . $dir_name) && is_file($language_dir . $dir_name ."/index.php") && $dir_name != '.' && $dir_name != '..') {
            $lang_array[] = $dir_name;
        }
    }
    closedir($dir);

    natcasesort($lang_array);

    echo <<<EOT
        <tr>
          <td class="tableb" valign="top">
	          $text
		      </td>
		      <td class="tableb" valign="top">
            <select name="$name" class="listbox">

EOT;
    foreach ($lang_array as $language) {
        echo "                                <option value=\"$language\" " . ($value == $language ? 'selected' : '') . ">" . get_language_name($language) . "</option>\n";
    }

    echo <<<EOT
            </select>
          </td>
        </tr>
        <tr>
          <td class="tablec" colspan="2">
						<table border="0" cellspacing="6" cellpadding="0" width="98%" align="center">
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[lang_name] <b>:</b> $lang_info[name]</td>
								<td width="50%" class="atomic">$lang_settings_data[lang_author_name] <b>:</b> $lang_info[author]</td>
							</tr>
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[lang_native_name] <b>:</b> $lang_info[nativename]</td>
								<td width="50%" class="atomic">$lang_settings_data[lang_trans_date] <b>:</b> $lang_info[transdate]</td>
							</tr>
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[lang_author_email] <b>:</b> <a href="mailto:$lang_info[author_email]">$lang_info[author_email]</a></td>
								<td width="50%" class="atomic">$lang_settings_data[lang_author_url] <b>:</b> <a href="$lang_info[author_url]" target="_blank">$lang_info[author_url]</a></td>
							</tr>
						</table>
	        </td>
        </tr>

EOT;
}

function form_theme($text, $name)
{
    global $CONFIG, $theme_info, $lang_settings_data;

    $value = $CONFIG[$name];
    $theme_dir = 'themes/';

    $dir = opendir($theme_dir);
    while ($file = readdir($dir)) {
        if (is_dir($theme_dir . $file) && $file != "." && $file != "..") {
            $theme_array[] = $file;
        }
    }
    closedir($dir);

    natcasesort($theme_array);

    echo <<<EOT
        <tr>
          <td class="tableb" valign="top">
						$text
	        </td>
	        <td class="tableb" valign="top">
            <select name="$name" class="listbox" onChange="generateimage(this.options[this.selectedIndex].value)">

EOT;
    foreach ($theme_array as $theme) {
        echo "                                <option value=\"$theme\" " . ($value == $theme ? 'selected' : '') . ">" . strtr(ucfirst($theme), '_', ' ') . "</option>\n";
    }
    echo <<<EOT
                        </select>
                </td>
        </tr>
        <tr>
          <td class="tablec" colspan="2">
						<table border="0" cellspacing="6" cellpadding="0" width="98%" align="center">
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[theme_name] <b>:</b> $theme_info[name]</td>
								<td width="50%" align="center" rowspan="5">		   					
									<div id="theme"><img src="themes/$value/images/preview.gif"></div>
								</td>
							</tr>
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[theme_date_made] <b>:</b> $theme_info[datemade]</td>
							</tr>
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[theme_author_name] <b>:</b> $theme_info[author]</td>
							</tr>
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[theme_author_email] <b>:</b> <a href="mailto:$theme_info[author_email]">$theme_info[author_email]</a></td>
							</tr>
							<tr>
								<td width="50%" class="atomic">$lang_settings_data[theme_author_url] <b>:</b> <a href="$theme_info[author_url]" target="_blank">$theme_info[author_url]</a></td>
							</tr>
						</table>
	        </td>
        </tr>
EOT;
}

function form_mini_cal_display_options($text, $name)
{
    global $CONFIG, $lang_settings_data;

    $value = $CONFIG[$name];
    $none_selected = ($value == '0' || empty($value)) ? 'selected' : '';
    $def_selected = ($value == '1' || $value == 'default') ? 'selected' : '';
    $daily_selected = ($value == '2' || $value == 'daily') ? 'selected' : '';
    $weekly_selected = ($value == '3' || $value == 'weekly') ? 'selected' : '';
    $monthly_selected = ($value == '5' || $value == 'monthly') ? 'selected' : '';
    $random_selected = ($value == '4' || $value == 'random') ? 'selected' : '';
    echo <<<EOT
      <tr>
        <td class="tableb">
          $text
        </td>
        <td class="tableb" valign="top">
          <select name="$name" class="listbox">
            <option value="" $none_selected>{$lang_settings_data[$name][0]}</option>
            <option value="default" $def_selected>{$lang_settings_data[$name][1]}</option>
            <option value="daily" $daily_selected>{$lang_settings_data[$name][2]}</option>
            <option value="weekly" $weekly_selected>{$lang_settings_data[$name][3]}</option>
            <option value="monthly" $monthly_selected>{$lang_settings_data[$name][5]}</option>
            <option value="random" $random_selected>{$lang_settings_data[$name][4]}</option>
          </select>
        </td>
      </tr>

EOT;
}

function form_time_format ($text, $name)
{
    global $CONFIG, $lang_settings_data;

    $value = $CONFIG[$name];
    $sel_24hours = $value ? 'selected' : '';
    $sel_12hours = !$value ? 'selected' : '';
		$label_24hour = $lang_settings_data['24hours'];
		$label_12hour = $lang_settings_data['12hours'];

    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="1" $sel_24hours>$label_24hour</option>
                                <option value="0" $sel_12hours>$label_12hour</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_status($text, $name)
{
    global $CONFIG, $lang_general;

    $value = $CONFIG[$name];
    $yes_selected = $value ? 'selected' : '';
    $no_selected = !$value ? 'selected' : '';
		$yes = $lang_general['active'];
		$no = $lang_general['not_active'];
		
    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="1" $yes_selected>$yes</option>
                                <option value="0" $no_selected>$no</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_multi_day_events($text, $name)
{
    global $CONFIG, $lang_settings_data;

    $value = $CONFIG[$name];
    $all_selected = ($value == 'all') ? 'selected' : '';
    $bounds_selected = ($value == 'bounds') ? 'selected' : '';
    $start_selected = ($value == 'start') ? 'selected' : '';

    echo <<<EOT
        <tr>
            <td class="tableb">
                        $text
        </td>
        <td class="tableb" valign="top">
                        <select name="$name" class="listbox">
                                <option value="all" $all_selected>{$lang_settings_data['multi_day_events_all']}</option>
                                <option value="bounds" $bounds_selected>{$lang_settings_data['multi_day_events_bounds']}</option>
                                <option value="start" $start_selected>{$lang_settings_data['multi_day_events_start']}</option>
                        </select>
                </td>
        </tr>

EOT;
}

function form_mail_method($text, $name)
{
    global $CONFIG;

    $mail_methods = array(
        'SMTP' => 'smtp',
    		'PHP Mail' => 'mail',
        'Sendmail' => 'sendmail',
        'Qmail' => 'qmail'
        );

    $value = strtolower($CONFIG[$name]);

    echo <<<EOT
        <tr>
          <td class="tableb">
            $text
        	</td>
        	<td class="tableb" valign="top">
            <select name="$name" class="listbox">

EOT;
    foreach ($mail_methods as $method_name => $method) {
        echo "                                <option value=\"$method\" " . ($value == $method ? 'selected' : '') . ">$method_name</option>\n";
    }
    echo <<<EOT
          </select>
				  </td>
        </tr>

EOT;
}

function create_form(&$data)
{
    global $ME;
    foreach($data as $element) {
        if ((is_array($element))) {
            switch ($element[2]) {
                case 0 :
                    form_input($element[0].'&nbsp;', $element[1]);
                    break;
                case 1 :
                    form_yes_no($element[0].'&nbsp;', $element[1]);
                    break;
								case 2 :
										form_default_view($element[0].'&nbsp;', $element[1]);
										break;
                case 3 :
                    form_sort_order($element[0].'&nbsp;', $element[1]);
                    break;
                case 4 :
                    form_charset($element[0].'&nbsp;', $element[1]);
                    break;
                case 5 :
                    form_language($element[0].'&nbsp;', $element[1]);
                    break;
                case 6 :
                    form_theme($element[0].'&nbsp;', $element[1]);
                    break;
                case 7 :
										form_timezone($element[0].'&nbsp;', $element[1]);
                    break;
                case 8 :
                    // do nothing
                    break;
                case 9 :
                    form_start_day($element[0].'&nbsp;', $element[1]);
                    break;
                case 10 :
                    form_mini_cal_display_options($element[0].'&nbsp;', $element[1]);
                    break;
                case 11 :
                    form_time_format($element[0].'&nbsp;', $element[1]);
                    break;
                case 12 :
                    form_status($element[0].'&nbsp;', $element[1]);
                    break;
                case 13 :
 										form_multi_day_events($element[0].'&nbsp;', $element[1]);
                    break;
                case 14 :
 										form_mail_method($element[0].'&nbsp;', $element[1]);
                    break;
                default:
                    die('Invalid action');
            } // switch
        } else {
            form_label($element);
        }
    }
}

$section_index = 0; // Main Settings
$section_title = $lang_settings_data['section_title']." : ".$lang_settings_data['admin_links'][$section_index];

if (count($_POST) > 0) {
    if (isset($_POST['update_config'])) {
        /*
				$need_to_be_positive = array('events_per_page',
            'event_list_cols',
            'max_tabs');

        foreach ($need_to_be_positive as $parameter)
        $_POST[$parameter] = max(1, (int)$_POST[$parameter]);
				*/
				
        foreach($lang_config_data as $element) {
            if ((is_array($element))) {
                if ((!isset($_POST[$element[1]]))) die("Missing config value for '{$element[1]}'". __FILE__ . __LINE__);
                $value = addslashes($_POST[$element[1]]);
                db_query("UPDATE {$CONFIG['TABLE_CONFIG']} SET  value = '$value' WHERE name = '{$element[1]}'");
            }
        }
        
        pageheader($section_title);
        theme_redirect_dialog($section_title, $lang_settings_data['update_settings_success'], $lang_general['continue'], $ME);
        pagefooter();
        exit;
    }
}
	
pageheader($section_title);

$signature = $CONFIG['app_name'] ." ". CALENDAR_VERSION;

echo <<<EOT
<script>
	function generateimage(which){
	if (document.all){
		if(which!=''){
			theme.innerHTML='<center>Loading image...</center>'
			theme.innerHTML='<img src="themes/'+which+'/images/preview.gif">'
		} else {
			theme.innerHTML='&nbsp;'
		}
	}
	else if (document.layers){
	}
}

</script>
EOT;

starttable('100%', $section_title, 2);
form_admin_sections("admin_links", $section_index);
echo <<<EOT
        <form action="$ME" method="post">

EOT;
create_form($lang_config_data);
echo <<<EOT
        <tr>
            <td colspan="2" align="center" class="tablec" height="40" valign="middle">
                        <input type="submit" class="button" name="update_config" value="$lang_settings_data[update_config]">
                        &nbsp;&nbsp;
                        <input type="submit" class="button" name="restore_config" value="$lang_settings_data[restore_config]">
                </td>
        </form>
        </tr>

EOT;
endtable();

// footer
pagefooter();
?>