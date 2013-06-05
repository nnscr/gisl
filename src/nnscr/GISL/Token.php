<?php
namespace nnscr\GISL;

class Token {
	const TYPE_TEXT        = 't_text';
	const TYPE_BLOCK_START = 't_block_start';
	const TYPE_BLOCK_END   = 't_block_end';
	const TYPE_OPERATOR    = 't_operator';
	const TYPE_STRING      = 't_string';
	const TYPE_IDENTIFIER  = 't_identifier';
	const TYPE_NAME        = 't_name';
	const TYPE_NUMBER      = 't_number';
	const TYPE_PUNCTUATION = 't_punctuation';
	const TYPE_EOF         = 't_eof';

	/**
	 * The token type, one of the TYPE_* constants above
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * The value of this token
	 *
	 * @var mixed
	 */
	protected $value;

	/**
	 * @param string $type
	 * @param mixed $value
	 */
	public function __construct($type, $value) {
		$this->type  = $type;
		$this->value = $value;
	}

	/**
	 * Get the token type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Get the token value
	 *
	 * @return mixed
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Test this token for a specific type and optionally for a specific value.
	 * The value can either be a single value or an array holding several allowed values.
	 *
	 * Returns true if the token passes the test or false if it does not match.
	 *
	 * @param string $type
	 * @param mixed  $value
	 * @return bool
	 */
	public function test($type, $value = null) {
		if(null !== $value && !is_array($value)) {
			$value = (array)$value;
		}

		return $this->type == $type && (null === $value || in_array($this->value, $value));
	}
}
