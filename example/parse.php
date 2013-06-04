<?php
require_once __DIR__ . '/../vendor/autoload.php';

if(!isset($argv[1])) {
	echo 'Required parameter missing' . PHP_EOL;
	exit(1);
}

$lexer = new \nnscr\GISL\Lexer();
$parser = new \nnscr\GISL\Parser();

try {
	$tokenStream = $lexer->tokenize($argv[1]);
	$parseTree   = $parser->parse($tokenStream);

	var_dump($parseTree);
} catch(\Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}
