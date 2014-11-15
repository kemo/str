<?php namespace Kemo\Strings;

class MethodDescription {

	/**
	 * Creates a method description object
	 * 
	 * @param int             $value_position 
	 * @param callable|string $callback
	 */
	public function __construct($value_position, $callback)
	{
		$this->callback       = $callback;
		$this->value_position = $value_position;
	}

	/**
	 * Returns the callback
	 * 
	 * @return callable
	 */
	public function callback()
	{
		return $this->callback;
	}

	/**
	 * Returns the total count of arguments
	 * 
	 * @param  array  $arguments
	 * @return 
	 */
	public function arguments_count(array $arguments)
	{
		return max(count($arguments), $this->value_position);
	}

	/**
	 * Returns the numeric position of the value parameter
	 * 
	 * @return int
	 */
	public function value_position()
	{
		return $this->value_position;
	}

}