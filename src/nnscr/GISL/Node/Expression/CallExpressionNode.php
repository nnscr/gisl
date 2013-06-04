<?php
namespace nnscr\GISL\Node\Expression;

use nnscr\GISL\Interpreter;
use nnscr\GISL\Node\NodeInterface;

class CallExpressionNode extends ExpressionNode {
	public function __construct(NodeInterface $node, NodeInterface $method, NodeInterface $arguments) {
		$this->pushNode($node, 'target');
		$this->pushNode($method, 'method');
		$this->pushNode($arguments, 'arguments');
	}

	public function interpret(Interpreter $interpreter) {
		$method = $this->getNode('method')->interpret($interpreter);
		$target = $this->getNode('target')->interpret($interpreter);

		$arguments = $interpreter->interpretChild($this->getNode('arguments'));

		return $interpreter->executeMethod($method, $target, $arguments);
	}
}
