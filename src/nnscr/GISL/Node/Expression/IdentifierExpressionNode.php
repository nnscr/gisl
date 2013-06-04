<?php
namespace nnscr\GISL\Node\Expression;

use nnscr\GISL\Interpreter;

class IdentifierExpressionNode extends ExpressionNode {
	public function __construct($name) {
		$this->setAttribute('name', $name);
	}

	public function interpret(Interpreter $interpreter) {
		return $interpreter->getIdentifier($this->getAttribute('name'));
	}
}
