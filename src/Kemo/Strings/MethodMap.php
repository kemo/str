<?php namespace Kemo\Strings;

class MethodMap
{

    /**
     * Map of methods with their MethodDescription objects
     *
     * @var \Kemo\Strings\MethodDescription[]
     */
    protected $methods = array();

    /**
     * Calls a method on the passed string object and returns the resulting value
     *
     * @param  Str    $str
     * @param  string $method_name
     * @param  array  $arguments
     *
     * @return string
     * @throws \BadMethodCallException If the called method isn't mapped yet
     */
    public function call(Str $str, $method_name, array $arguments)
    {
        if (!$this->hasMethod($method_name)) {
            throw new \BadMethodCallException(sprintf('No method "%s" mapped', $method_name));
        }

        $desc = $this->methods[$method_name];

        return call_user_func_array(
            $desc->callback(),
            $this->_callArguments($str->value(), $arguments, $desc->valuePosition())
        );
    }

    /**
     * Checks if there is a method in this map by name
     *
     * @param  string $method_name
     *
     * @return boolean
     */
    public function hasMethod($method_name)
    {
        return isset($this->methods[$method_name]);
    }

    /**
     * Maps a method to the current map
     *
     * Use numeric keys for values without a default value,
     * otherwise use the array key for the value name and array value for the default value
     *
     * @param  string          $method Method name
     * @param  int             $value_position Numeric position of the value argument in callback, e.g. 0 for first
     * @param  callable|string $callback The method to call these arguments on and return the result from
     *
     * @return self             Chainable
     */
    public function map($method, $value_position = 0, $callback = null)
    {
        if ($callback === null) {
            $callback = $method;
        }

        $this->methods[$method] = new MethodDescription($value_position, $callback);

        return $this;
    }

    /**
     * Returns callback-ready list of arguments
     *
     * @param  string $value
     * @param  array  $arguments
     * @param  int    $value_position
     *
     * @return array  Callable arguments
     * @throws \InvalidArgumentException if the value position can't be reached
     */
    protected function _callArguments($value, array $arguments, $value_position)
    {
        // Are we missing some arguments?
        if ($value_position > count($arguments) + 1)
            throw new \InvalidArgumentException('Missing some arguments, at least '.($value_position - 1).' should be supplied');

        $arg_count = count($arguments);

        if ($arg_count === 0)
            return array($value);

        return $this->_createArguments($value, $arguments, $value_position, $arg_count);
    }

    protected function _createArguments($value, array $arguments, $value_position, $arg_count)
    {
        $params = array();

        for ($i = 0; $i < max($value_position, $arg_count + 1); $i++) {
            if ($i === $value_position) {
                $params[] = $value;
            }

            if (isset($arguments[$i])) {
                $params[] = $arguments[$i];
            }
        }

        return $params;
    }
}
