<?php

//------------------------------------------------------------------------------
// General.Functions.Inc v2.0
//
// (c) Andrew Collington - amnuts@talker.com
// (c) Michael Doig      - michael@mike250.com
//------------------------------------------------------------------------------


// general functions

function sqldate_to_string($t="",$short=0)
{
	if ($t=="") return "";	
	if (!$short) $months = array(
		"Jan","Feb","Mar","Apr","May","Jun","Jul",
		"Aug","Sep","Oct","Nov","Dec"
		);
	else $months = array(
		"Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"
		);
	if (ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",$t,$args))
		return sprintf("%s %d, %s",$months[$args[2]-1],$args[3],$args[1]);
	else if (ereg("^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$t,$args))
		return sprintf("%s %d, %s",$months[$args[2]-1],$args[3],$args[1]);
	else return $t;
}


function ordinal_text($num=1)
{
	$ords = array("th","st","nd","rd");
	if ((($num%=100)>9 && $num<20) || ($num%=10)>3) $num=0;
	return $ords[$num];
}


function date_format($sqltime,$lang="US")
{
	global $full_day_name,$full_month_name;
	
	ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})",$sqltime,$args);
	$wd = date("w",mktime(0,0,0,$args[2],$args[3],$args[1]));
	switch($lang) {
	case "US":
		return sprintf("%s, %s %d%s, %s",$full_day_name[$wd],$full_month_name[$args[2]-1],$args[3],ordinal_text($args[3]),$args[1]);
		break;
	default:
		return sprintf("%s, %d%s %s, %s",$full_day_name[$wd],$args[3],ordinal_text($args[3]),$full_month_name[$args[2]-1],$args[1]);
		break;
	}
}


function show_short_story($num=1,$ctry="us",$div="R",$len=210)
{
	global $pathcfg;
	
	$sroot = sprintf($pathcfg["sroot"],$ctry,$pathcfg[$div]);
	if (!file_exists("$sroot/story.$num")) return "";
	$contents = file("$sroot/story.$num");
	$header = split("\|",trim($contents[0]));

	$story = "";
	// get the line from 1-n and put it into a string
	for ($i=1; $i<count($contents); $i++) {
		$story .= $contents[$i];
	}
	// get the default size
	$story = substr($story,0,$len);
	// backtrack incase we have hit in the middle of a word
	while($story[strlen($story)-1] != " ") {
		$story = substr($story,0,-1);
	}
	// add the ... to the string
	$story = substr($story,0,-1);
	$story .= "...";
	
	// now build up for html output
	$story = eregi_replace("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}", "<a href=\"mailto:\\0\">\\0</a>",nl2br(htmlentities($story)));
	$story = "<p><span class=\"story$num\">$header[0]</span></p><div class=\"stdtext\">" . $story . "</div>";
	$story .= "<p><a href=\"stories.php?num=$num&div=$div&ctry=$ctry\">click here for full story</a></p>";

	return $story;
}


function show_full_story($num=1,$ctry="us",$div="R")
{
	global $pathcfg;
	
	$sroot = sprintf($pathcfg["sroot"],$ctry,$pathcfg[$div]);
	if (!file_exists("$sroot/story.$num")) return "";
	$contents = file("$sroot/story.$num");
	$header = split("\|",trim($contents[0]));

	$story = "";
	// get the line from 1-n and put it into a string
	for ($i=1; $i<count($contents); $i++) {
		$story .= $contents[$i];
	}
	
	// now build up for html output
	$story = eregi_replace("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}", "<a href=\"mailto:\\0\">\\0</a>",nl2br(htmlentities($story)));
	// process any special tags
	$story = preg_replace("/\[link=(\S+?)\](.*?)\[\/link\]/Uis", "<a href=\"\\1\" class=\"12px\">\\2</a>",$story);
	$story = preg_replace("/\[img=(\S+?)\](.*?)\[\/img\]/Uis", "<img src=\"\\1\" align=\"left\">",$story);
	$story = preg_replace("/\[i\](.*?)\[\/i\]/Uis", "<i>\\1</i>",$story);
	$story = preg_replace("/\[b\](.*?)\[\/b\]/Uis", "<b>\\1</b>",$story);
	$story = preg_replace("/\[u\](.*?)\[\/u\]/Uis", "<u>\\1</u>",$story);
	$story = preg_replace("/\[pre\](.*?)\[\/pre\]/Uis", "<pre>\\1</pre>",$story);
	
	if ($num == 1) {
		if (file_exists("$sroot/$header[1]")) {
			$size = GetImageSize("$sroot/$header[1]");
			$img = "<img src=\"stories/$header[1]\" $size[3] border=\"0\" alt=\"" . htmlentities($header[0]) . "\" align=\"right\">";
		} else {
			$img = "";
		}
		$story = "<p><span class=\"story$num\">" . htmlentities($header[0]) . "</span></p><div class=\"stdtext\">$img" . $story . "</div>";
	} else {
		$story = "<p><span class=\"story$num\">" . htmlentities($header[0]) . "</span></p><div class=\"stdtext\">" . $story . "</div>";
	}
	return $story;
}



function show_story_picture($num=1,$ctry="us",$div="R")
{
	global $pathcfg;
	
	$sroot = sprintf($pathcfg["sroot"],$ctry,$pathcfg[$div]);
	$contents = file("$sroot/story.$num");
	$header = split("\|",trim($contents[0]));

	if (file_exists("$sroot/$header[1]")) {
		$size = GetImageSize ("$sroot/$header[1]");
		$img = "<img src=\"stories/$header[1]\" $size[3] border=\"0\" alt=\"" . htmlentities($header[0]) . "\">";
	} else {
		$img = "";
	}
	return $img;
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

?>