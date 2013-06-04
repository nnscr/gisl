<?php
namespace nnscr\GISL\Method;

interface MethodInterface {
	/**
	 * Get the callable method name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Call the method with the given arguments
	 *
	 * @param  mixed $target
	 * @param  array $arguments
	 * @return mixed
	 */
	public function call($target, array $arguments);
}
