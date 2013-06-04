<?php
require_once __DIR__ . '/../vendor/autoload.php';

if(!isset($argv[1])) {
	echo 'Required parameter missing' . PHP_EOL;
	exit(1);
}

try {
	$lexer = new \nnscr\GISL\Lexer();
	var_dump($lexer->tokenize($argv[1]));
} catch(\nnscr\GISL\Exception\SyntaxErrorException $e) {
	echo $e->getMessage() . PHP_EOL;
}
