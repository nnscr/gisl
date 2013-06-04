<?php
namespace nnscr\GISL\Node;

use nnscr\GISL\Interpreter;

class Node implements NodeInterface {
	/**
	 * Child nodes
	 *
	 * @var NodeInterface[]
	 */
	private $nodes;

	/**
	 * Node attributes
	 *
	 * @var array
	 */
	private $attributes;

	/**
	 * @param array $nodes
	 */
	public function __construct(array $nodes = []) {
		$this->nodes = $nodes;
	}

	/**
	 * Get all node attributes
	 *
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * Interpret this node
	 *
	 * @param Interpreter $interpreter
	 * @return mixed
	 */
	public function interpret(Interpreter $interpreter) {
		$values = [];
		foreach($this->nodes as $node) {
			$values[] = $node->interpret($interpreter);
		}

		return implode('', $values);
	}

	/**
	 * Set an attribute
	 *
	 * @param mixed $attr
	 * @param mixed $value
	 */
	public function setAttribute($attr, $value) {
		$this->attributes[$attr] = $value;
	}

	/**
	 * Returns if the attribute exists
	 *
	 * @param  mixed $attr
	 * @return bool
	 */
	public function hasAttribute($attr) {
		return isset($this->attributes[$attr]);
	}

	/**
	 * Get an attribute
	 *
	 * @param  mixed $attr
	 * @return mixed
	 * @throws \LogicException
	 */
	public function getAttribute($attr) {
		if(!$this->hasAttribute($attr)) {
			throw new \LogicException(sprintf('Unknown attribute "%s" for node "%s"', $attr, get_class($this)));
		}

		return $this->attributes[$attr];
	}


	/**
	 * Add the given node to this node
	 *
	 * @param NodeInterface $node
	 * @param string $name
	 */
	public function pushNode(NodeInterface $node, $name = null) {
		if(null !== $name) {
			$this->nodes[$name] = $node;
		} else {
			$this->nodes[] = $node;
		}
	}

	/**
	 * Get all child nodes
	 *
	 * @return NodeInterface[]
	 */
	public function getNodes() {
		return $this->nodes;
	}

	/**
	 * Get a specific node
	 *
	 * @param  string $name
	 * @return NodeInterface
	 * @throws \LogicException
	 */
	public function getNode($name) {
		if(!$this->hasNode($name)) {
			throw new \LogicException('Unknown child node "%s"', $name);
		}

		return $this->nodes[$name];
	}

	/**
	 * Return true if the node exists
	 *
	 * @param $name
	 * @return bool
	 */
	public function hasNode($name) {
		return isset($this->nodes[$name]);
	}
}
