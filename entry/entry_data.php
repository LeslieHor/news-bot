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

echo '<p>';
echo $feed->get_title();
echo '</p>';

echo '<p>';
if ($query['index'] > 0)
{
	echo '<a href="entry.php?url=' . $query["url"] . '&index=' . ((int)$query['index']-1) . '">Prev Article</a> | ';
}
if ($query['index'] < $feed->get_item_quantity() - 1)
echo '<a href="entry.php?url=' . $query["url"] . '&index=' . ((int)$query['index']+1) . '">Next Article</a>';
echo '</p>';

$item = $feed->get_item($query['index']);
$title = $item->get_title();
$link = $item->get_link();
$pubdate = $item->get_date();
$content = $item->get_content();

echo '<p>';
echo 'Published: ' . $pubdate;
echo '</p>';

echo '<p>';
echo '<a href="' . $link . '">' . $title . '</a>';
echo '</p>';

echo '<p>';
echo $content . '<br><br>';
echo '</p>';

echo '<p>';
echo '<a href="../article_extract/article.php?url=' . $link . '">Extract article</a>';
echo '</p>';
?>