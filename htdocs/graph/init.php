<?php
//yubing@baixing.com

if( !defined('CONFIG_DIR')) {
	define('CONFIG_DIR', __DIR__ . '/config');
}

define('LOG_DIR', '/home/logs');
define('TEMP_DIR', '/tmp');

spl_autoload_register(function($name) {
	if (file_exists(__DIR__ . "/lib/{$name}.php")) {
		require __DIR__ . "/lib/{$name}.php";
	}
});

require __DIR__ . '/lib/Graph.php';
require __DIR__ . '/lib/Query.php';
