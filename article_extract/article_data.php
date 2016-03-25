<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('max_execution_time', 120);

include_once('../settings.php');
require_once('../libs/readability/Readability.php');

$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$parts = parse_url($url);
parse_str($parts['query'], $query);


$html = file_get_contents($query['url']);

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
		echo '<p>' . $title . '</p>';
		echo $content;
	} else {
		echo 'Looks like we couldn\'t find the content. :(';
	}
} catch (Exception $e) {
	echo "Article extraction error";
}
?>