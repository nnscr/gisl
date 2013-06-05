<?php
namespace nnscr\GISL\Exception;

class SyntaxErrorException extends \Exception {
	public static function unclosedBlock() {
		return new self('Unclosed block');
	}

	public static function unexpectedCharacter($c) {
		return new self(sprintf('Unexpected character "%s"', $c));
	}

	public static function wrongBracket($got, $expected) {
		return new self(sprintf('Unexpected %s, expected %s', $got, $expected));
	}

	public static function unclosedBracket($unclosed) {
		return new self(sprintf('Unclosed "%s"', $unclosed));
	}
}
