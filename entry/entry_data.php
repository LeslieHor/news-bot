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

echo '<div class="nav_bar">';
echo '<a href="../feed/feed.php?url=' . $query['url'] . '">Back</a> | ';
if ($query['index'] > 0)
{
	echo '<a href="entry.php?url=' . $query["url"] . '&index=' . ((int)$query['index']-1) . '">Prev Article</a> | ';
}
if ($query['index'] < $feed->get_item_quantity() - 1)
{
	echo '<a href="entry.php?url=' . $query["url"] . '&index=' . ((int)$query['index']+1) . '">Next Article</a>';
}
echo '</p>';

$item = $feed->get_item($query['index']);
$title = $item->get_title();
$link = $item->get_link();
$pubdate = $item->get_date();
$content = $item->get_content();

echo '<div class="feed_title">';
echo '<a href="' . $feed->get_link() . '">' . $feed->get_title() . '</a>';
echo '</div>';

echo '<div class="article_title">';
echo '<a href="' . $link . '">' . $title . '</a>';
echo '</div>';

echo '<div class="publish_date">';
echo 'Published: ' . $pubdate;
echo '</div>';

echo '<div class="main_article_content">';
echo $content . '<br><br>';
echo '</div>';

echo '<p>';
echo '<a href="../article_extract/article.php?url=' . $link . '">Extract article</a>';
echo '</p>';
?>