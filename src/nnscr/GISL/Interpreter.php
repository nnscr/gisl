<?php
namespace nnscr\GISL;

use nnscr\GISL\Method\DivideMethod;
use nnscr\GISL\Method\LCaseMethod;
use nnscr\GISL\Method\MethodInterface;
use nnscr\GISL\Method\MinusMethod;
use nnscr\GISL\Method\MultiplyMethod;
use nnscr\GISL\Method\PlusMethod;
use nnscr\GISL\Method\ReplaceMethod;
use nnscr\GISL\Method\UCaseMethod;
use nnscr\GISL\Node\NodeInterface;

class Interpreter {
	/**
	 * Available methods
	 *
	 * @var MethodInterface[]
	 */
	private $methods;

	/**
	 * Identifiers
	 *
	 * @var array
	 */
	private $identifiers;

	/**
	 * Interpreter
	 */
	public function __construct() {
		$this->addMethod(new ReplaceMethod());
		$this->addMethod(new UCaseMethod());
		$this->addMethod(new LCaseMethod());

		// elementary arithmetic, this is a temporary solution
		// and should be replaced with real operators (meaning that the
		// parser must be extended)
		$this->addMethod(new PlusMethod());
		$this->addMethod(new MinusMethod());
		$this->addMethod(new MultiplyMethod());
		$this->addMethod(new DivideMethod());
	}

	/**
	 * Add a method
	 *
	 * @param MethodInterface $method
	 * @throws \LogicException
	 */
	public function addMethod(MethodInterface $method) {
		if(!preg_match(Lexer::REGEX_NAME, $method->getName())) {
			throw new \LogicException(sprintf('"%s" is not a valid method name', $method->getName()));
		}

		$this->methods[$method->getName()] = $method;
	}

	/**
	 * Get a method
	 *
	 * @param  string $name
	 * @return MethodInterface
	 * @throws \LogicException
	 */
	public function getMethod($name) {
		if(!$this->hasMethod($name)) {
			throw new \LogicException(sprintf('Function "%s" does not exist', $name));
		}

		return $this->methods[$name];
	}

	/**
	 * Returns true if method exists
	 *
	 * @param  string $name
	 * @return bool
	 */
	public function hasMethod($name) {
		return isset($this->methods[$name]);
	}

	/**
	 * Execute the given method
	 *
	 * @param string $name
	 * @param mixed $target
	 * @param array $arguments
	 * @return mixed
	 */
	public function executeMethod($name, $target, array $arguments) {
		return $this->getMethod($name)->call($target, $arguments);
	}

	/**
	 * Return true if the identifier exists
	 *
	 * @param  string $name
	 * @return bool
	 */
	public function hasIdentifier($name) {
		return isset($this->identifiers[$name]);
	}

	/**
	 * Get the value for an identifier
	 *
	 * @param  string $name
	 * @throws \RuntimeException
	 * @return mixed
	 */
	public function getIdentifier($name) {
		if(!$this->hasIdentifier($name)) {
			throw new \RuntimeException(sprintf('Unknown identifier "%s"', $name));
		}

		return $this->identifiers[$name];
	}

	/**
	 * Interpret the given node.
	 *
	 * @param NodeInterface $node
	 * @param mixed $identifiers  The identifiers, can be an instance of \ArrayAccess or an array.
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	public function interpret(NodeInterface $node, $identifiers = []) {
		if(!is_array($identifiers) && !$identifiers instanceof \ArrayAccess) {
			throw new \InvalidArgumentException('$identifiers must be either an array or implement the ArrayAccess interface.');
		}

		$this->identifiers = $identifiers;
		$value = $this->interpretChild($node);
		$this->identifiers = null;

		return $value;
	}

	/**
	 * Interpret a child node
	 *
	 * @param NodeInterface $node
	 * @return mixed
	 */
	public function interpretChild(NodeInterface $node) {
		return $node->interpret($this);
	}
}
