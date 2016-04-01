<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../common.php');
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

$feeds = load_json_data('../feeds.json');
foreach ($feeds as &$json_feed)
{
	if (strcmp($json_feed['link'], $query['url']) == 0)
	{
		$last_checked = $json_feed['last_checked'];
		$json_feed['last_checked'] = date('d F Y H:i:s', time());
	}
}

$counter = 0;

foreach ($feed->get_items() as $item){
	$title = $item->get_title();
	$link = $item->get_link();
	$pubdate = $item->get_date();
	#$md5 = md5($link);
	$content = $item->get_content();
	#$read_status = bool_to_str(get_read_status($md5, $feed_md5));
	
	echo date('D, d M y - H:i', strtotime($pubdate));
	if (strtotime($pubdate) > strtotime($last_checked))
	{
		echo '<div class=unread>';
	}
	else
	{
		echo '<div>';
	}
	echo '<li><a href="../entry/entry.php?url=' . $query["url"] . '&index=' . $counter . '&article_extract=0">' . $title . '</a></li>';
	echo '</div>';
	$counter++;
}

echo "</ol>";
	
save_json_data($feeds, '../feeds.json');
?>