<?php
namespace nnscr\GISL\Method;

class MultiplyMethod implements MethodInterface {
	/**
	 * Get the callable method name
	 *
	 * @return string
	 */
	public function getName() {
		return 'multiply';
	}

	/**
	 * Call the method with the given arguments
	 *
	 * @param  mixed $target
	 * @param  array $arguments
	 * @return mixed
	 */
	public function call($target, array $arguments) {
		return $target * $arguments[0];
	}
}
