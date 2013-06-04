<?php
namespace nnscr\GISL\Node;

use nnscr\GISL\Node\Expression\ExpressionNode;

class PrintNode extends Node {
	public function __construct(ExpressionNode $expr) {
		$this->pushNode($expr);
	}
}
