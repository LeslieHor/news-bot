<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once('../libs/simplepie/autoloader.php');

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($url);
parse_str($parts['query'], $query);
#echo $query["url"];

# Parse url into feed
$feed = new SimplePie();
$feed->set_feed_url($query["url"]);
$feed->init();
$feed->handle_content_type();

echo $feed->get_title();


echo "<ol>";

foreach ($feed->get_items() as $item){
	$title = $item->get_title();
	$link = $item->get_link();
	#$pubdate = $item->get_date();
	#$md5 = md5($link);
	$content = $item->get_content();
	#$read_status = bool_to_str(get_read_status($md5, $feed_md5));
	
	echo '<li><a href="' . $link . '">' . $title . '</a></li>';
	echo $content . '<br><br>';
}

echo "</ol>";
?>