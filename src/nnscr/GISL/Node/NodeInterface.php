<?php
namespace nnscr\GISL\Node;

use nnscr\GISL\Interpreter;

interface NodeInterface {
	/**
	 * Get all child nodes
	 *
	 * @return NodeInterface[]
	 */
	public function getNodes();

	/**
	 * Get all node attributes
	 *
	 * @return array
	 */
	public function getAttributes();

	/**
	 * Interpret this node
	 *
	 * @param Interpreter $interpreter
	 * @return mixed
	 */
	public function interpret(Interpreter $interpreter);
}
