<?php
/*  PHP RSS Reader v1.1
    By Richard James Kendall 
    Bugs to richard@richardjameskendall.com 
    Free to use, please acknowledge me 
    
    Place the URL of an RSS feed in the $file variable.
   	
   	The $rss_channel array will be filled with data from the feed,
   	every RSS feed is different by by and large it should contain:
   	
   	Array {
   		['title'] = feed title
   		[DESCRIPTION] = feed description
   		[LINK] = link to their website
   		
   		[IMAGE] = Array {
   					['url'] = url of image
   					[DESCRIPTION] = alt text of image
   				}
   		
   		[ITEMS] = Array {
   					[0] = Array {
   							['title'] = item title
   							[DESCRIPTION] = item description
   							[LINK = a link to the story
   						}
   					.
   					.
   					.
   				}
   	}
   	
   	By default it retrives the Reuters Oddly Enough RSS feed. The data is put into the array
   	structure so you can format the information as you see fit.
*/
set_time_limit(0);

$file = "http://slashdot.org/index.rss";

$rss_channel = array();
$currently_writing = "";
$main = "";
$item_counter = 0;

function startElement($parser, $name, $attrs) {
   	global $rss_channel, $currently_writing, $main;
   	switch($name) {
   		case "RSS":
   		case "RDF:RDF":
   		case "ITEMS":
   			$currently_writing = "";
   			break;
   		case "CHANNEL":
   			$main = "CHANNEL";
   			break;
   		case "IMAGE":
   			$main = "IMAGE";
   			$rss_channel["IMAGE"] = array();
   			break;
   		case "ITEM":
   			$main = "ITEMS";
   			break;
   		default:
   			$currently_writing = $name;
   			break;
   	}
}

function endElement($parser, $name) {
   	global $rss_channel, $currently_writing, $item_counter;
   	$currently_writing = "";
   	if ($name == "ITEM") {
   		$item_counter++;
   	}
}

function characterData($parser, $data) {
	global $rss_channel, $currently_writing, $main, $item_counter;
	if ($currently_writing != "") {
		switch($main) {
			case "CHANNEL":
				if (isset($rss_channel[$currently_writing])) {
					$rss_channel[$currently_writing] .= $data;
				} else {
					$rss_channel[$currently_writing] = $data;
				}
				break;
			case "IMAGE":
				if (isset($rss_channel[$main][$currently_writing])) {
					$rss_channel[$main][$currently_writing] .= $data;
				} else {
					$rss_channel[$main][$currently_writing] = $data;
				}
				break;
			case "ITEMS":
				if (isset($rss_channel[$main][$item_counter][$currently_writing])) {
					$rss_channel[$main][$item_counter][$currently_writing] .= $data;
				} else {
					//print ("rss_channel[$main][$item_counter][$currently_writing] = $data<br>");
					$rss_channel[$main][$item_counter][$currently_writing] = $data;
				}
				break;
		}
	}
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
if (!($fp = fopen($file, "r"))) {
	die("could not open XML input");
}

while ($data = fread($fp, 4096)) {
	if (!xml_parse($xml_parser, $data, feof($fp))) {
		die(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
	}
}
xml_parser_free($xml_parser);

// output as HTML
print ("<html><head><title>PHP RSS Reader</title></head><body>");
if (isset($rss_channel["IMAGE"])) {
	print ("<a href=\"" . $rss_channel["LINK"] . "\" target=\"_blank\"><img border=\"0\" src=\"" . $rss_channel["IMAGE"]["URL"] . "\" align=\"middle\" alt=\"" . $rss_channel["IMAGE"]["TITLE"] . "\"></a>&nbsp;&nbsp;<font size=\"5\">" . $rss_channel["TITLE"] . "</font><br><br>");
} else {
	print ("<font size=\"5\">" . $rss_channel["TITLE"] . "</font><br><br>");
}
print ("<i>" . $rss_channel["DESCRIPTION"] . "</i><br><br>");
if (isset($rss_channel["ITEMS"])) {
	if (count($rss_channel["ITEMS"]) > 0) {
		for($i = 0;$i < count($rss_channel["ITEMS"]);$i++) {
			print ("\n<table width=\"100%\" border=\"1\"><tr><td width=\"100%\"><a href=\"" . $rss_channel["ITEMS"][$i]["LINK"] . "\" target=\"_blank\"><h2>" . $rss_channel["ITEMS"][$i]["TITLE"] . "</h2></a></b>");
			print ("<i>" . html_entity_decode($rss_channel["ITEMS"][$i]["DESCRIPTION"]) . "</i>");
			print ("</td></tr></table><br>");
		}
	} else {
		print ("<b>There are no articles in this feed.</b>");
	}
}
print ("</body></html>");
?>
