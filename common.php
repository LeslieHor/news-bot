<?php
// Loads in the JSON data
function load_json_data($local_path)
{
	$json_data = json_decode(file_get_contents($local_path), true);
	return $json_data;
}

// Saves the data as a JSON file
function save_json_data($json_data, $path)
{
	try
	{
		file_put_contents($path, json_encode($json_data));
	}
	catch (Exception $e)
	{
		echo "error";
	}
}
?>