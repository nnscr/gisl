<?php
require __DIR__ . '/_common.php';

try {
	$lexer = new \nnscr\GISL\Lexer();
	var_dump($lexer->tokenize($input));
} catch(\nnscr\GISL\Exception\SyntaxErrorException $e) {
	echo $e->getMessage() . PHP_EOL;
}
