<?php
namespace nnscr\GISL\Method;

class ReplaceMethod implements MethodInterface {
	/**
	 * Get the callable method name
	 *
	 * @return string
	 */
	public function getName() {
		return 'replace';
	}

	/**
	 * Call the method with the given arguments
	 *
	 * @param mixed $target
	 * @param array $arguments
	 * @return mixed
	 */
	public function call($target, array $arguments) {
		return str_replace($arguments[0], $arguments[1], $target);
	}
}
