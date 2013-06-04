<?php
require __DIR__ . '/_common.php';

$identifiers = [
	'foo'  => 'bar',
	'test' => 'hallo',
	'A field' => 'The field content! Wohoo!',
];

$lexer = new \nnscr\GISL\Lexer();
$parser = new \nnscr\GISL\Parser();
$interpreter = new \nnscr\GISL\Interpreter();

$interpreter->addMethod(new \nnscr\GISL\Method\ReplaceMethod());

try {
	$tokenStream = $lexer->tokenize($input);
	$parseTree   = $parser->parse($tokenStream);
	$output = $interpreter->interpret($parseTree, $identifiers);

	var_dump($output);
} catch(Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}
