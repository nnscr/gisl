<?php
namespace nnscr\GISL\Node;

use nnscr\GISL\Interpreter;

class ArrayNode extends Node {
	public function interpret(Interpreter $interpreter) {
		$values = [];
		foreach($this->getNodes() as $node) {
			$values[] = $node->interpret($interpreter);
		}

		return $values;
	}
}
