<?php
namespace nnscr\GISL\Exception;

use nnscr\GISL\Token;

class ParseErrorException extends \Exception {
	public static function unexpectedToken(Token $token, $expected = null) {
		if($expected) {
			return new self(sprintf('Unexpected token of type %s, expected %s.', $token->getType(), $expected));
		}

		return new self(sprintf('Unexpected token of type "%s" with value "%s"', $token->getType(), $token->getValue()));
	}

	public static function unexpectedEnd() {
		return new self('Unexpected end of script');
	}
}
