<?php
require_once __DIR__ . '/../vendor/autoload.php';

if(!isset($argv[1])) {
	echo 'Required parameter missing' . PHP_EOL;
	exit(1);
}

$identifiers = [
	'foo'  => 'bar',
	'test' => 'hallo',
	'A field' => 'The field content! Wohoo!',
];

$lexer = new \nnscr\GISL\Lexer();
$parser = new \nnscr\GISL\Parser();
$interpreter = new \nnscr\GISL\Interpreter();

$interpreter->addMethod(new \nnscr\GISL\Method\ReplaceMethod());
$interpreter->addMethod(new \nnscr\GISL\Method\LCaseMethod());
$interpreter->addMethod(new \nnscr\GISL\Method\UCaseMethod());

try {
	$tokenStream = $lexer->tokenize($argv[1]);
	$parseTree   = $parser->parse($tokenStream);
	$output = $interpreter->interpret($parseTree, $identifiers);

	var_dump($output);
} catch(Exception $e) {
	echo $e->getMessage() . PHP_EOL;
}
