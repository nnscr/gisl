<?php
spl_autoload_register(function($class) {
	$path = __DIR__ . '/src/' . str_replace('\\', '/', $class) . '.php';
	if(file_exists($path)) {
		require $path;
	}
});

ini_set('xdebug.var_display_max_depth', -1);

if(!isset($argv[1])) {
	echo 'Required parameter missing' . PHP_EOL;
	exit(1);
}

array_shift($argv);
$input = implode(' ', $argv);
