<?php 
$url = "http://www.cricinfo.com/rss/content/story/feeds/0.xml";
require('./rss/rss_fetch.inc');
$rss = fetch_rss($url);
echo "Site: ", $rss->channel['title'], "<br>";
foreach ($rss->items as $item ) {
	$title = $item[title];
	$url   = $item[link];
	echo "<a href=$url>$title</a></li><br>
";
}

?>