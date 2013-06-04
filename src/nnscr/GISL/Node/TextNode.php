<?php
namespace nnscr\GISL\Node;

use nnscr\GISL\Interpreter;

class TextNode extends Node {
	/**
	 * @param string $data
	 */
	public function __construct($data) {
		$this->setAttribute('data', $data);
	}

	/**
	 * @param Interpreter $interpreter
	 * @return mixed
	 */
	public function interpret(Interpreter $interpreter) {
		return $this->getAttribute('data');
	}
}
