<?php
namespace nnscr\GISL\Node\Expression;

use nnscr\GISL\Interpreter;

class ConstantExpressionNode extends ExpressionNode {
	public function __construct($value) {
		$this->setAttribute('value', $value);
	}

	public function interpret(Interpreter $interpreter) {
		return $this->getAttribute('value');
	}
}
