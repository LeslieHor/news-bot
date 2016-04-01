<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'common.php';
include_once('settings.php');
include_once('libs/simplepie/autoloader.php');

$feeds = load_json_data($GLOBALS['root_path'] . 'feeds.json');
$title = '';
foreach ($feeds as $feed)
{
	if (strcmp($feed['category'],$title) != 0)
	{
		# Only print an end tag if the $title is not a blank string
		# IE. Don't print an end tag at the start
		if (strcmp($title, '') != 0)
		{
			echo '</ol></p>';
		}
		echo '<p>' . ucfirst($title);
		echo '<ol>';
		
		$title = $feed['category'];
	}
	
	# Parse url into feed
	$pie_feed = new SimplePie();
	$pie_feed->set_cache_location($GLOBALS['root_path'] . 'cache');
	$pie_feed->set_feed_url($feed['link']);
	$pie_feed->init();
	$pie_feed->handle_content_type();
	
	$counter = 0;
	foreach ($pie_feed->get_items() as $item)
	{
		if (strtotime($item->get_date()) > strtotime($feed['last_checked']))
		{
			$counter++;
		}
	}
	
	
	if ($counter > 0)
	{
		echo '<div class=unread>';
	}
	else
	{
		echo '<div>';
	}
	echo '<li><a href="feed/feed.php?url=' . $feed['link'] . '">(' . $counter . ') ' . $feed['title'] . '</a></li>';
	echo '</div>';
}

?>