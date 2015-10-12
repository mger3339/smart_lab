<?php defined('BASEPATH') OR exit('No direct script access allowed');

function list_directories($path)
{
	$directories = array_filter(scandir($path), function($f) use($path) {
		return is_dir($path . DIRECTORY_SEPARATOR . $f);
	});
	
	$remove = array('.', '..');
	
	$directories = array_diff($directories, $remove);
	
	return array_values($directories);
}

/* End of file MY_directory_helper.php */
/* Location: ./application/helpers/MY_directory_helper.php */