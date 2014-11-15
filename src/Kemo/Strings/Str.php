<?php namespace Kemo\Strings;
/**
 * Object string class
 *
 * Allows manipulating strings as objects like in ... normal languages. Example:
 *
 * echo (new Str('bar '))
 *     ->replace(['bar' => 'foobar'])
 *     ->rtrim();
 *
 * Also allows adding custom "method maps" which can be injected via the constructor.
 * The constructor can be overriden to pull this from a DI container, obviously.
 *
 * @author  Kemal Delalic <kemal.delalic@gmail.com>
 */
class Str {

	const DEFAULT_CHUNK_END    = "\r\n";
	const DEFAULT_CHUNK_LENGTH = 76;
	const DEFAULT_PAD_STRING = ' ';
	const DEFAULT_PAD_TYPE   = \STR_PAD_RIGHT;
	const DEFAULT_TRIM_CHARACTER_MASK = " \t\n\r\0\x0B";

	/**
	 * Method map object
	 * 
	 * @var \Kemo\Strings\MethodMap
	 */
	protected $map;

	/**
	 * Contains the list of values that have been changed in this object
	 * 
	 * @var array
	 */
	protected $values = array();

	/**
	 * Creates a Str object
	 * 
	 * @param string                  $string to represent
	 * @param \Kemo\Strings\MethodMap $map of methods - optional injection
	 */
	public function __construct($string, MethodMap $map = NULL)
	{
		$this->map      = isset($map) ? $map : new MethodMap;
		$this->values[] = $string;
	}

	/**
	 * What happens when non-existing method is called on this object
	 * e.g. one that's not defined or is protected
	 * 
	 * @param  string $method    
	 * @param  array  $arguments
	 * @return self   (chainable)
	 */
	final public function __call($method, array $arguments)
	{
		return $this->map->call($this, $method, $arguments);
	}

	/**
	 * What happenes when this object is casted to string
	 * 
	 * @return string
	 */
	final public function __toString()
	{
		return $this->value();
	}

	/**
	 * Returns the current value of this string
	 * 
	 * @return string
	 */
	final public function value()
	{
		end($this->values);

		return current($this->values);
	}

	/**
	 * Returns the list of all values this object used to have
	 * 
	 * @return string[]
	 */
	final public function values()
	{
		return $this->values;
	}

	protected function _set($value)
	{
		$this->values[] = $value;

		return $this;
	}

	/**
	 * Quote string with slashes in a C style
	 *
	 * @link   http://php.net/manual/en/function.addcslashes.php
	 * @param  string $charlist
	 * @return self (chainable)
	 */
	public function add_cslashes($charlist)
	{
		return $this->_set(\addcslashes($this->value(), $charlist));
	}

	/**
	 * Quote string with slashes
	 *
	 * @link   http://php.net/manual/en/function.addslashes.php
	 * @return self (chainable)
	 */
	public function add_slashes()
	{
		return $this->_set(
			\addslashes($this->value())
		);
	}

	/**
	 * Splits the string into smaller chunks
	 *
	 * @link   http://php.net/chunk_split
	 * @param  int    $length   The chunk length.
	 * @param  string $end      The line ending sequence.
	 * @return self             (chainable)
	 */
	public function chunk_split($length = NULL, $end = NULL)
	{
		$end    = isset($end)    ? $end    : static::DEFAULT_CHUNK_END;
		$length = isset($length) ? $length : static::DEFAULT_CHUNK_LENGTH;

		return $this->_set(
			\chunk_split($this->value(), $length, $end)
		);
	}

	/**
	 * Return information about characters used in a string
	 * [!!] Not chainable
	 *
	 * Depending on mode count_chars() returns one of the following:
	 * 0 - an array with the byte-value as key and the frequency of every byte as value.
	 * 1 - same as 0 but only byte-values with a frequency greater than zero are listed.
	 * 2 - same as 0 but only byte-values with a frequency equal to zero are listed.
	 * 3 - a string containing all unique characters is returned.
	 * 4 - a string containing all not used characters is returned.
	 *
	 * @link   http://php.net/count_chars
	 * @param  integer $mode 
	 * @return mixed
	 */
	public function count_chars($mode = 0)
	{
		return \count_chars($this->value(), $mode);
	}

	/**
	 * Splits current string by string
	 * [!!] Not chainable
	 *
	 * @link   http://php.net/explode
	 * @param  string $delimiter The boundary string.
	 * @return array  Exploded string
	 */
	public function explode($delimiter)
	{
		return \explode($delimiter, $this->value());
	}

	/**
	 * Join array elements with this string
	 * [!!] Not chainable
	 * 
	 * @link   http://php.net/implode
	 * @param  array  $pieces 
	 * @return string
	 */
	public function implode(array $pieces)
	{
		return \implode($this->value(), $pieces);
	}

	/**
	 * Case-insensitive version of Str::replace()
	 * 
	 * @link   http://php.net/str_ireplace
	 * @param  array  $replacements List of search -> replace pairs
	 * @return self (chainable)
	 * @see    \Kemo\Strings\Str::replace()
	 */
	public function ireplace(array $replacements)
	{
		return $this->_set(
			\str_ireplace(array_keys($replacements), array_values($replacements), $this->value())
		);
	}

	/**
	 * Strip whitespace (or other characters) from the beginning of a string
	 *
	 * @link   http://php.net/ltrim
	 * @param  string $character_mask List of characters to trim (optional)
	 * @return self                   Chainable
	 */
	public function ltrim($character_mask = NULL)
	{
		if ($character_mask === NULL)
		{
			$character_mask = static::DEFAULT_TRIM_CHARACTER_MASK;
		}

		return $this->_set(
			\ltrim($this->value(), $character_mask)
		);
	}

	/**
	 * Pad the string to a certain length with another string
	 * 
	 * @link   http://php.net/str_pad
	 * @param  int    $pad_length If the value of pad_length is negative, less than, or equal to the length of current string, no padding takes place
	 * @param  string $pad_string String to pad the current string with
	 * @param  int    $pad_type   Optional, can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH. If pad_type is not specified it is assumed to be STR_PAD_RIGHT
	 * @return self               Chainable
	 */
	public function pad($pad_length, $pad_string = NULL, $pad_type = NULL)
	{
		if ($pad_string === NULL)
		{
			$pad_string = static::DEFAULT_PAD_STRING;
		}

		if ($pad_type === NULL)
		{
			$pad_type = static::DEFAULT_PAD_TYPE;
		}

		return $this->_set(
			\str_pad($this->value(), $pad_length, $pad_string, $pad_type)
		);
	}

	/**
	 * Repeats the string
	 *
	 * @link   http://php.net/str_repeat
	 * @param  int    $multiplier How many times to repeat the string
	 * @return self               Chainable
	 */
	public function repeat($multiplier)
	{
		return $this->_set(
			\str_repeat($this->value(), $multiplier)
		);
	}

	/**
	 * Replaces the occurences of keys with their values
	 * 
	 * @link   http://php.net/str_replace
	 * @param  array  $replacements List of [search => replace] pairs
	 * @param  int    $count        If passed, this will be set to the number of replacements performed.
	 * @return self                 Chainable
	 */
	public function replace(array $replacements, & $count = NULL)
	{
		return $this->_set(
			\str_replace(
				array_keys($replacements), 
				array_values($replacements), 
				$this->value(), 
				$count
			)
		);
	}

	/**
	 * Perform the rot13 transform on current string
	 * 
	 * @link   http://php.net/explode
	 * @return self              Chainable
	 */
	public function rot13()
	{
		return $this->_set(
			\str_rot13($this->value())
		);
	}

	/**
	 * Strip whitespace (or other characters) from the end
	 * 
	 * @param  string $character_mask List of characters to trim (optional)
	 * @return self                   Chainable
	 */
	public function rtrim($character_mask = NULL)
	{
		if ($character_mask === NULL)
		{
			$character_mask = static::DEFAULT_TRIM_CHARACTER_MASK;
		}

		return $this->_set(
			\rtrim($this->value(), $character_mask)
		);
	}

	/**
	 * Strip HTML and PHP tags
	 * 
	 * @link   http://php.net/manual/en/function.strip-tags.php
	 * @param  string  $allowable_tags List of allowed tags
	 * @return self                    Chainable
	 */
	public function strip_tags($allowable_tags)
	{
		return $this->_set(
			\strip_tags($this->value(), $allowable_tags)
		);
	}

	/**
	 *  Strip whitespace (or other characters) from the beginning and end of the string
	 *
	 * @link   http://php.net/manual/en/function.trim.php
	 * @param  string  $character_mask  List of characters to trim (optional)
	 * @return self                     Chainable
	 */
	public function trim($character_mask = NULL)
	{
		if ($character_mask === NULL)
		{
			$character_mask = static::DEFAULT_TRIM_CHARACTER_MASK;
		}

		return $this->_set(
			\trim($this->value(), $character_mask)
		);
	}
}
