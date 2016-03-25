<?php
include 'common.php';

$files = glob('categories/*.{json,txt}', GLOB_BRACE);
foreach($files as $file) {
	$feeds = load_json_data($file);
	$title = basename($file, ".json");
	
	echo $title;
	echo '<ol>';
	foreach ($feeds as $feed){
		echo '<li><a href="feed/feed.php?url=' . $feed['link'] . '">' . $feed['title'] . '</a></li>';
	}
	echo '</ol>';
}

?>