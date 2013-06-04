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

	protected $type;
	protected $value;

	public function __construct($type, $value) {
		$this->type  = $type;
		$this->value = $value;
	}

	public function getType() {
		return $this->type;
	}

	public function getValue() {
		return $this->value;
	}

	public function test($type, $value = null) {
		if(null !== $value && !is_array($value)) {
			$value = (array)$value;
		}

		return $this->type == $type && (null === $value || in_array($this->value, $value));
	}
}
