<?php
require __DIR__ . '/_common.php';

$lexer = new \nnscr\GISL\Lexer();
$parser = new \nnscr\GISL\Parser();

try {
	$tokenStream = $lexer->tokenize($input);
	$parseTree   = $parser->parse($tokenStream);

	var_dump($parseTree);
} catch(\Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}
