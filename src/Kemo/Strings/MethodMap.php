<?php namespace Kemo\Strings;

class MethodMap {

	/**
	 * Map of methods with their MethodDescription objects
	 * 
	 * @var \Kemo\Strings\MethodDescription[]
	 */
	protected $method_map = array();

	/**
	 * Calls a method on the passed string object and returns the resulting value
	 * 
	 * @param  Str    $str       
	 * @param  string $method_name
	 * @param  array  $arguments 
	 * @return string
	 */
	public function call(Str $str, $method_name, array $arguments)
	{
		if ( ! isset($this->method_map[$method_name]))
			 throw new \LogicException('No method "'.$method_name.'" mapped in this map');
		
		$desc = $this->method_map[$method_name];

		return call_user_func_array($desc->callback(), $this->_call_arguments($str->value(), $arguments, $desc->value_position()));
	}

	/**
	 * Maps a method to the current map
	 * 
	 * Use numeric keys for values without a default value,
	 * otherwise use the array key for the value name and array value for the default value
	 * 
	 * @param  string           $method         Method name
	 * @param  array            $value_position Numeric position of the value argument in callback, e.g. 0 for first
	 * @param  callable|string  $callback       The method to call these arguments on and return the result from
	 * @return self             Chainable.
	 */
	public function map($method, $value_position = 0, $callback = NULL)
	{
		if ($callback === NULL)
		{
			$callback = $method;
		}

		$this->method_map[$method] = new MethodDescription($value_position, $callback);

		return $this;
	}

	/**
	 * Returns callback-ready list of arguments
	 * 
	 * @param  string $value          
	 * @param  array  $arguments      
	 * @param  int    $value_position 
	 * @return array  Callable arguments
	 */
	protected function _call_arguments($value, array $arguments, $value_position)
	{
		// Are we missing some arguments?
		if ($value_position > count($arguments) + 1)
			throw new \InvalidArgumentException('Missing some arguments, at least '.($value_position - 1).' should be supplied');

		$arg_count = count($arguments);

		if ($arg_count === 0)
			return array($value);

		return $this->_create_arguments($value, $arguments, $value_position, $arg_count);
	}

	protected function _create_arguments($value, array $arguments, $value_position, $arg_count)
	{
		$params = array();

		for ($i = 0; $i < max($value_position, $arg_count + 1); $i++)
		{
			if ($i === $value_position)
			{
				$params[] = $value;
			}
			
			if (isset($arguments[$i]))
			{
				$params[] = $arguments[$i];	
			}
		}

		return $params;
	}

}
