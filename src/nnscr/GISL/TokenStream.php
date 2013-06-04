<?php
namespace nnscr\GISL;

use nnscr\GISL\Exception\ParseErrorException;

class TokenStream implements \Countable {
	/**
	 * @var Token[]
	 */
	private $tokens;

	/**
	 * The cursor position
	 *
	 * @var int
	 */
	private $cursor;

	/**
	 * @param array $tokens
	 */
	public function __construct(array $tokens) {
		$this->tokens = $tokens;
		$this->cursor = 0;
	}

	/**
	 * Get the current token in the token stream and move the cursor forward
	 *
	 * @throws Exception\ParseErrorException
	 * @return Token
	 */
	public function next() {
		$this->cursor++;

		if(!isset($this->tokens[$this->cursor])) {
			throw ParseErrorException::unexpectedEnd();
		}

		return $this->tokens[$this->cursor - 1];
	}

	/**
	 * Get the current token
	 *
	 * @return Token
	 */
	public function current() {
		return $this->tokens[$this->cursor];
	}

	/**
	 * Get the total number of elements
	 *
	 * @return int
	 */
	public function count() {
		return count($this->tokens);
	}

	/**
	 * Has this stream reached the end?
	 *
	 * @return bool
	 */
	public function isEOF() {
		return $this->current()->getType() == Token::TYPE_EOF;
	}

	/**
	 * Test the current token
	 *
	 * @param mixed $type
	 * @param mixed $value
	 * @return bool
	 */
	public function test($type, $value = null) {
		return $this->current()->test($type, $value);
	}

	/**
	 * Expect a token. If the next token is the expected token, the cursor will move forward,
	 * else an ParseErrorException will be thrown
	 *
	 * @param  mixed $type
	 * @param  mixed $value
	 * @throws ParseErrorException
	 * @return Token
	 */
	public function expect($type, $value = null) {
		$token = $this->current();

		if(!$token->test($type, $value)) {
			throw ParseErrorException::unexpectedToken($token, $type);
		}

		// move cursor
		$this->next();

		return $token;
	}
}
