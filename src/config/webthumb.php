<?php

return array(
	'local_cache_dir'	=>	public_path() . "/thumbs", //relative cache directory must exists in install directory and rwx permissions to all (777)
	'phantom_js_root'	=> __DIR__ . '/../lib/phantomjs', //Path to the root directory phantom_js

	'encoding'			=>		"png", // jpg or png
	'bwidth'			=>		"1280", // browser width
	'bheight'			=>		"1024" // browser height only for mode=screen
);