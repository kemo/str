<?php namespace Kemo\Strings;

class MethodDescription
{

    /**
     * Creates a method description object
     *
     * @param int             $value_position Value argument position in callback
     * @param callable|string $callback Function name are callable
     */
    public function __construct($value_position, $callback)
    {
        $this->callback = $callback;
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
     * @param  array $arguments
     *
     * @return int
     */
    public function argumentsCount(array $arguments)
    {
        return max(count($arguments), $this->value_position);
    }

    /**
     * Returns the numeric position of the value parameter
     *
     * @return int
     */
    public function valuePosition()
    {
        return $this->value_position;
    }
}
