<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', 120);

include_once('../settings.php');
include_once('../libs/simplepie/autoloader.php');
require_once('../libs/readability/Readability.php');

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($url);
parse_str($parts['query'], $query);

if (!isset($query['article_extract']))
{
	$query['article_extract'] = 0;
}

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

if ($query['article_extract'] == 1)
{
	$html = file_get_contents($link);

	if (function_exists('tidy_parse_string')) {
		$tidy = tidy_parse_string($html, array(), 'UTF8');
		$tidy->cleanRepair();
		$html = $tidy->value;
	}

	try{
		$readability = new Readability($html, $query['url']);
		
		$result = $readability->init();
		
		if ($result) {
			$title = $readability->getTitle()->innerHTML;
			$content = $readability->getContent()->innerHTML;
			// if we've got Tidy, let's clean it up for output
			if (function_exists('tidy_parse_string')) {
				$tidy = tidy_parse_string($content, array('indent'=>true, 'show-body-only' => true), 'UTF8');
				$tidy->cleanRepair();
				$content = $tidy->value;
			}
		} else {
			echo 'Article extraction has failed.';
		}
	} catch (Exception $e) {
		echo "Article extraction error";
	}
} 

echo '<div class="main_article_content">';
echo $content . '<br><br>';
echo '</div>';

if ($query['article_extract'] == 0)
{
	echo '<p>';
	echo '<a href="./entry.php?url=' . $query["url"] . '&index=' . $query['index'] . '&article_extract=1">Extract article</a>';
	echo '</p>';
}
?>