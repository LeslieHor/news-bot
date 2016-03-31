<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../settings.php');
include_once('../libs/simplepie/autoloader.php');

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($url);
parse_str($parts['query'], $query);

# Parse url into feed
$feed = new SimplePie();
$feed->set_cache_location($GLOBALS['root_path'] . 'cache');
$feed->set_feed_url($query["url"]);
$feed->init();
$feed->handle_content_type();
echo $feed->get_title();

echo "<ol>";

$counter = 0;

foreach ($feed->get_items() as $item){
	$title = $item->get_title();
	$link = $item->get_link();
	$pubdate = $item->get_date();
	#$md5 = md5($link);
	$content = $item->get_content();
	#$read_status = bool_to_str(get_read_status($md5, $feed_md5));
	
	echo date('D, d M y - H:i', strtotime($pubdate));
	echo '<li><a href="../entry/entry.php?url=' . $query["url"] . '&index=' . $counter . '&article_extract=0">' . $title . '</a></li>';
	#echo '<li><a href="' . $link . '">' . $title . '</a></li>';
	#echo $content . '<br><br>';
	
	$counter++;
}

echo "</ol>";
?>