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
Description : Product Updates

Get the latest version of ExtCalendar at http://extcal.sourceforge.net//
*/

define('SETTINGS_PHP', true);

require_once "config.inc.php";

require_priv('can_change_settings');

require_once $CONFIG['LIB_DIR']."xmlrpc.inc";

// XML-RPC server info
$extcal_updateServer = array(
	'host' => 'www.extcalendar.com',
	'uri' => '/webservices/extcalupdates.php',
	'port' => 80
);


// check if the config table contains release info. Insert necessary info if missing. 
if(!isset($CONFIG['release_name'])) {
	$CONFIG['release_name'] = '2.0 dev09052004';
	$CONFIG['release_version'] = '200.23';
	$CONFIG['release_type'] = 'dev';

	$query = "INSERT INTO ".$CONFIG['TABLE_CONFIG']." 
			( `name` , `value` ) 
		VALUES 
			('release_name', '".$CONFIG['release_name']."'),
			('release_version', '".$CONFIG['release_version']."'),
			('release_type', '".$CONFIG['release_type']."')
		";
	db_query($query);
		
}


$template_updates_data = <<<EOT
<!-- BEGIN info_row -->
		<tr>
			<td class='tableb' colspan='2'><br />
				<div class="atomic">{INFO_MSG}</div><br />
			</td>
		</tr>
		<tr>
			<td class='tableh2' colspan='2'>{REL_DETAILS_CAPTION}</td>
		</tr>
<!-- END info_row -->
<!-- BEGIN release_details_row -->
		<tr>
			<td class='tableb' width='140' align='center' nowrap>
				<table cellpadding='0' cellspacing='0' border='0' align='center'>
					<tr>
						<td width="40" align='right'>
							<img src='themes/default/images/{RELEASE_ICON}' align='absmiddle' border='0' alt='{RELEASE_TYPE}' hspace='6'>
						</td>
						<td align='left' class="atomic">
							<strong>{RELEASE_NAME}</strong>
						</td>
					</tr>
				</table>
			</td>
			<td class='tableb'><div class='atomic'>{DESCRIPTION}</div><br /></td>
		</tr>
		<tr>
			<td class='tablec' align='center'><div class='atomic'>{RELEASE_DATE}</div></td>
			<td class='tablec'>
				<table cellpadding='0' cellspacing='0' border='0' width='100%'>
					<tr>
						<td width='50%' align='center' class="atomic">
							<a href="{DOWNLOAD_ZIP_LINK}" target="_blank"><img src='themes/default/images/icon-archive.gif' align='absmiddle' border='0' alt='{DOWNLOAD_ZIP}'>&nbsp;{DOWNLOAD_ZIP}
						</td>
						<td width='50%' align='center' class="atomic">
							<a href="{DOWNLOAD_TGZ_LINK}" target="_blank"><img src='themes/default/images/icon-archive.gif' align='absmiddle' border='0' alt='{DOWNLOAD_TGZ}'>&nbsp;{DOWNLOAD_TGZ}
						</td>
					</tr>
				</table>
			</td>
		</tr>
<!-- END release_details_row -->
EOT;

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

function updates_check() {
	global $extcal_updateServer, $lang_settings_data, $CONFIG; 

	// Create client object
	$client = new xmlrpc_client($extcal_updateServer['uri'], $extcal_updateServer['host'], $extcal_updateServer['port']);

	$release_version = $CONFIG['release_version'];
	$release_type = $CONFIG['release_type'];

	// Create XML-RPC request message
	$xmlrpc_message = new xmlrpcmsg('extcal.getVersionUpdate',
				   array(
				   	new xmlrpcval($release_version, "string"),
				   	new xmlrpcval($release_type, "string"),
				   )
	);
	
	// Send XML-RPC request message
	if($response = $client->send($xmlrpc_message, 8))
	{

		// XML-RPC server found, now checking for errors
		if (!$response->faultCode()) {
			$result = array(0, xmlrpc_decode($response->value()));
		}else 
			$result = array($response->faultCode(), $response->faultString());

		return $result;
	}

	return array(-1, $lang_settings_data['updates_no_response']);
	
}

function updates_check_result($update_result) {
	global $template_updates_data, $lang_settings_data, $lang_general, $lang_date_format, $lang_system, $CONFIG;
	
	// Build current config report
	$current_release = CALENDAR_NAME . "&nbsp;" . $CONFIG['release_name'];
	$config_server = str_replace("/","&nbsp;", ereg_replace(" .*$",'',$_SERVER['SERVER_SOFTWARE']));
	$php_version = "PHP&nbsp;" . phpversion();
	$db_version = "MySQL";
	// try to retrieve DB version
	$query = "SELECT VERSION() AS db_version";
	$res = db_query($query);
	if($result = db_fetch_row($res)) $db_version .= "&nbsp;" . strtoupper($result[0]);
	
	$current_config = sprintf($lang_system['config_string'],
											$current_release,
											$config_server, 
											$php_version, 
											$db_version
										);
	
	if($update_result[0]> 0 && $update_result[0] != 800)
		updates_status_noresult();
	else if($update_result[0] == 800) {
		updates_status_no_update($current_config);
	} else if(is_array($update_result[1])) {
	
		$info_row = template_extract_block($template_updates_data, 'info_row');
		$result_row = template_extract_block($template_updates_data, 'release_details_row');
		//$stats_row = template_extract_block($template_updates_data, 'stats_row');
				
		
		$params = array(
			'{INFO}' => $lang_general['info'],
			'{INFO_MSG}' => $current_config,
			'{REL_DETAILS_CAPTION}' => $lang_settings_data['avail_updates']
		);
		echo template_eval($info_row, $params);
		
		$results = &$update_result;
		
		foreach($results as $result) {
			if(!is_array($result)) continue;
			switch($result['release_type']) {
				case "dev":
					 $release_icon = "icon-release-dev.gif"; // Development Build
					 $release_type = "Development Build";
					 break;
				case "alpha":
					 $release_icon = "icon-release-alpha.gif"; // Alpha Release
					 $release_type = "Alpha Release";
					 break;
				case "beta":
					 $release_icon = "icon-release-beta.gif"; // Beta Release
					 $release_type = "Beta Release";
					 break;
				case "rc":
					 $release_icon = "icon-release-rc.gif"; // Release Condidate
					 $release_type = "Release Condidate";
					 break;
				case "stable":
				default:
					 $release_icon = "icon-release-beta.gif"; // Stable Release
					 $release_type = "Stable Release";
			}			
			$params = array(
				'{RELEASE_NAME}' => CALENDAR_NAME . " " . $result['release_name'],
				'{DESCRIPTION}' => $result['description'],
				'{RELEASE_ICON}' => $release_icon,
				'{RELEASE_TYPE}' => $release_type,
				'{RELEASE_DATE}' => sprintf($lang_settings_data['updates_released_label'], strftime($lang_date_format['day_month_year'], strtotime($result['release_date']))),
				'{DOWNLOAD_ZIP}' => $lang_settings_data['updates_download_zip'],
				'{DOWNLOAD_ZIP_LINK}' => $result['download_zip'],
				'{DOWNLOAD_TGZ}' => $lang_settings_data['updates_download_tgz'],
				'{DOWNLOAD_TGZ_LINK}' => $result['download_tgz'],
			);
			echo template_eval($result_row, $params);
		}
		
	} else updates_status_noresult();
	 
}


function updates_status_wait() 
{
	global $lang_settings_data;
	echo <<<EOT
<!-- BEGIN wait_row -->
		<tr class="tableb">
			<td class="tableb" colspan="2" align="center"><br /><br /><strong>{$lang_settings_data['updates_check_text']}</strong><br /><br /><br /></td>
		</tr>
<!-- END wait_row -->
EOT;

}

function updates_status_noresult()
{
	global $lang_settings_data;
	echo <<<EOT
<!-- BEGIN wait_row -->
		<tr class="tableb">
			<td class="tableb" colspan="2" align="center"><br /><br /><strong>{$lang_settings_data['updates_no_response']}</strong><br /><br /><br /></td>
		</tr>
<!-- END wait_row -->
EOT;

}

function updates_status_no_update($current_config) {
	global $lang_settings_data;
	echo <<<EOT
<!-- BEGIN info_row -->
		<tr>
			<td class='tableb' colspan='2'><br />
				<div>$current_config</div><br />
			</td>
		</tr>
		<tr>
			<td class='tableh2' colspan='2'>{$lang_settings_data['avail_updates']}</td>
		</tr>
<!-- END info_row -->
<!-- BEGIN no_update_row -->
		<tr class="tableb">
			<td class="tableb" colspan="2" align="center"><br /><br /><strong>{$lang_settings_data['updates_no_update']}</strong><br /><br /><br /></td>
		</tr>
<!-- END no_update_row -->
EOT;

}
	
$section_index = 2; // Product Updates
$section_title = $lang_settings_data['section_title']." : ".$lang_settings_data['admin_links'][$section_index];

pageheader($section_title);

starttable('100%', $section_title, 2);
form_admin_sections("admin_links", $section_index);

if(!isset($_SESSION['update_result'])) {

	updates_status_wait();
	endtable();
	// Send output to the browser
	ob_end_flush();	


	session_register('update_result');
	// execute updates_check function
	$_SESSION['update_result'] = updates_check();
	
	echo <<<EOT
	<script language='JavaScript'>
	<!--
	document.location.replace('$ME');
	//-->
	</script>
EOT;
} else {
	updates_check_result($_SESSION['update_result']);
	endtable();

	session_unregister('update_result');
}
// footer
pagefooter();

?>