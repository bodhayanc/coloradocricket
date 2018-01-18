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
Date Last Updated : 13/09/2004
Author(s) : Mohamed Moujami (Simo), Kristof De Jaeger
Description : Template configuration

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('SETTINGS_PHP', true);

require_once "config.inc.php";

require_priv('can_change_settings');

$lang_config_data = array(
	array($lang_settings_data['template_header'], 'template_header', 0),
	array($lang_settings_data['info_status'], 'template_header', 1),
	array($lang_settings_data['template_custom'], 'template_header', 2),

	array($lang_settings_data['template_footer'], 'template_footer', 0),
	array($lang_settings_data['info_status'], 'template_footer', 1),
	array($lang_settings_data['template_custom'], 'template_footer', 2),

	array($lang_settings_data['info_meta'], 'info_meta', 0),
	array($lang_settings_data['info_status'], 'info_meta', 3),
	array($lang_settings_data['info_custom'], 'info_meta', 2)
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

function form_label($text, $name)
{
  global $template_config;

	echo <<<EOT
				<tr>
					<td class="tableh2" colspan="2">
						<strong>$text</strong>
					</td>
				</tr>
EOT;

    $description = $template_config[$name]['template_description'];
    if(!empty($description))
    echo <<<EOT
				<tr>
					<td class="tablec" colspan="2"><div class="atomic">$description</div>
					</td>
				</tr>
EOT;
}

function form_description($name)
{
    global $template_config, $THEME_DIR;

    $description = $template_config[$name]['template_description'];

}

function form_text($text, $name)
{
    global $template_config, $THEME_DIR, $lang_settings_data;

    $value = $template_config[$name]['template_value'];
    $description = $template_config[$name]['template_description'];

    echo <<<EOT
       <tr>
	        <td rowspan="2" width="45%" class="tableb">
            $text
	        </td>
	        <td width="55%" class="tablec" valign="center" align="right">
	        	<a href="javascript://" class="flatButton" title="{$lang_settings_data['dynamic_tags']}">
	        		<img src="$THEME_DIR/images/icon-tag-menu.gif" border="0" vspace="2">
	        	</a>
	        </td>
	       </tr>
	       <tr>
	        <td width="55%" class="tableb" valign="top">
            <textarea name="{$name}_value" cols='60' rows='14' class='textarea'>$value</textarea>
          </td>
        </tr>
EOT;
}

function form_template_status($text, $name)
{
    global $lang_settings_data,$template_config;

    $value = $template_config[$name]['template_status'];

    $default_select = ($value == 0) ? 'checked' : '';
    $custom_select = ($value == 1) ? 'checked' : '';

    echo <<<EOT
        <tr>
          <td class="tableb">
            $text
	        </td>
	        <td class="tableb" valign="top">
            <input type="radio" name="{$name}_status" value="0" $default_select>{$lang_settings_data['template_status_default']}</input><br />
            <input type="radio" name="{$name}_status" value="1" $custom_select>{$lang_settings_data['template_status_custom']}</input>
	        </td>
        </tr>
EOT;
}

function form_info_status($text, $name)
{
    global $lang_settings_data,$template_config;

    $value = $template_config[$name]['template_status'];

    $default_select = ($value == 0) ? 'checked' : '';
    $custom_select = ($value == 1) ? 'checked' : '';

    echo <<<EOT
        <tr>
          <td class="tableb">
            $text
	        </td>
	        <td class="tableb" valign="top">
            <input type="radio" name="{$name}_status" value="0" $default_select>{$lang_settings_data['info_status_default']}</input><br />
            <input type="radio" name="{$name}_status" value="1" $custom_select>{$lang_settings_data['info_status_custom']}</input>
	        </td>
        </tr>
EOT;
}

function create_form(&$data)
{
  global $ME;
  foreach($data as $element) {
    switch ($element[2]) {
        case 0 :
            form_label($element[0], $element[1]);
            break;
        case 1 :
						form_template_status($element[0], $element[1]);
            break;
				case 2 :
            form_text($element[0], $element[1]);
						break;
        case 3 :
						form_info_status($element[0], $element[1]);
            break;
        default:
            die('Invalid action');
    } 
  }
}

$section_index = 1; // Template Configuration
$section_title = $lang_settings_data['section_title']." : ".$lang_settings_data['admin_links'][$section_index];


	
pageheader($section_title);

$signature = $CONFIG['app_name'] ." ". CALENDAR_VERSION;

$successful = false;

if (count($_POST) > 0) {
		$form = $_POST;

		$errors = '';

		if(!$errors) {

			$template_header_value = isset($form['template_header_value'])?addslashes(html_decode($form['template_header_value'])):"";
			$template_header_status = ($form['template_header_status'])?1:0;

			// Process the header update
			$query = "
				UPDATE ".$CONFIG['TABLE_TEMPLATES']." SET 
					`template_status` = '$template_header_status',
					`template_value` = '$template_header_value',
					`last_access` = NOW()
				WHERE template_type = 'header'";
			db_query($query);

			$template_footer_value = isset($form['template_footer_value'])?addslashes(html_decode($form['template_footer_value'])):"";
			$template_footer_status = ($form['template_footer_status'])?1:0;

			// Now process the footer update
			$query = "
			UPDATE ".$CONFIG['TABLE_TEMPLATES']." SET 
				`template_status` = '$template_footer_status',
				`template_value` = '$template_footer_value',
				`last_access` = NOW()
			WHERE template_type = 'footer'";
			db_query($query);

			$info_meta_value = isset($form['info_meta_value'])?addslashes(html_decode($form['info_meta_value'])):"";
			$info_meta_status = ($form['info_meta_status'])?1:0;

			// Now process the footer update
			$query = "
			UPDATE ".$CONFIG['TABLE_TEMPLATES']." SET 
				`template_status` = '$info_meta_status',
				`template_value` = '$info_meta_value',
				`last_access` = NOW()
			WHERE template_type = 'meta'";
			db_query($query);

			// Successfull message
        theme_redirect_dialog($section_title, $lang_settings_data['update_settings_success'], $lang_general['continue'], $ME);
			// to remember not to display the form again
			$successful = true;
		} 

}
	// Render the form
  if(!$successful) {

starttable('100%', $section_title, 2);

form_admin_sections("admin_links", $section_index);
echo <<<EOT
        <form action="$ME" method="post">

EOT;

// retrieve template data from db
$query = "SELECT * FROM ".$CONFIG['TABLE_TEMPLATES'];
$result = db_query($query);
while ($row = db_fetch_array($result))
{
	if($row['template_type'] == "header") $template_config['template_header'] = $row;
	elseif($row['template_type'] == "footer") $template_config['template_footer'] = $row;
	elseif($row['template_type'] == "meta") $template_config['info_meta'] = $row;
}

create_form($lang_config_data);
echo <<<EOT
        <tr>
            <td colspan="2" align="center" class="tablec" height="40" valign="middle">
              <input type="submit" class="button" name="update_config" value="$lang_settings_data[update_config]">
            </td>
        </form>
        </tr>

EOT;
endtable();
}
// footer
pagefooter();
?>