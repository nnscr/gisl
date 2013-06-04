<?php
namespace nnscr\GISL\Exception;

class SyntaxErrorException extends \Exception {
	public static function unclosedBlock() {
		return new static('Unclosed block');
	}

	public static function unexpectedCharacter($c) {
		return new static(sprintf('Unexpected character "%s"', $c));
	}

	public static function wrongBracket($got, $expected) {
		return new static(sprintf('Unexpected %s, expected %s', $got, $expected));
	}

	public static function unclosedBracket($unclosed) {
		return new static(sprintf('Unclosed "%s"', $unclosed));
	}
}
