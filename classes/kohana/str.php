<?php defined('SYSPATH') or die('No direct script access.');

/**
 * String class
 * 
 * Usage:
 * 
 * echo $string = Str::factory("This is some text and kemal.delalic@gmail.com is some email")
 * 		->chars()			// HTML::chars()
 *		->reduce_slashes()	// Text::reduce_slashes()
 * 		->plural()			// Inflector::plural()
 * 		->limit_words(4)	// Text::limit_words()
 * 		->auto_p(); 		// HTML::auto_p()
 * 
 * Now the $string contains our Str object but can still be echoed as a normal string
 * 
 * $str2 = (string) $string;
 * 
 * ====================================================
 *
 * @version 	1.0b
 * @author 		Kemal Delalic <kemal.delalic@gmail.com>
 * 
 * @todo		Cache methods
 */
abstract class Kohana_Str {

	protected $_value;
	
	public function __construct($string)
	{
		Str::init();
		
		$this->_value = $string;
	}
	
	public function __call($name, array $args)
	{
		array_unshift($args, $this->_value);
		
		if ($method = Str::find_method($name))
		{
			$this->_value = call_user_func_array($method, $args);
			
			return $this;
		}
		
		// If this method reaches end, throw an exception
		throw new Kohana_Exception('Unknown method called: :m', array(':m'=>'Str::'.$name));
	}
	
	
	public function __toString()
	{
		return $this->_value;
	}

	
	/* Class stuff */
	protected static $_cache;
	
	/**
	 * Contains the list of helpers which contain the called method, ordered by priorities
	 * Can be another object instance or classname (for static methods)
	 */
	protected static $_helpers = array
	(
		'Text',
		'HTML',
		'Inflector',
	);
	
	public static function factory($string)
	{
		return new Str($string);
	}
	
	public static function find_method($func)
	{
		// If method has already been traced
		if (isset(Str::$_cache[$func]))
		{
			return Str::$_cache[$func];
		}
		
		// Try finding the requested method in the list of helpers
		foreach (Str::$_helpers as $key => $class)
		{
			// @todo	method_exists() vs function_exists() vs is_callable() ?
			if (method_exists($class, $func))
			{
				Str::$_cache[$func] = array($class, $func);
				
				return array($class, $func);
			}
		}
		
		// If not returned by now, try finding the function with name specified
		if (function_exists($func))
		{
			Str::$_cache[$func] = $func;
			
			return $func;
		}
		
		return FALSE;
	}
	
	// Appends a helper to Str
	public static function helper_append($helper)
	{
		Str::$_helpers[] = $helper;
	}
	
	// Prepends a helper to Str
	public static function helper_prepend($helper)
	{
		arr_unshift(Str::$_helpers, $helper);
	}
	
	// Removes a helper
	public static function helper_remove($helper)
	{
		foreach (Str::$_helpers as $k => $v)
		{
			if ($v === $helper) unset(Str::$_helpers[$k]);
		}
	}
	
	// Initializes cache, if needed
	protected static function init()
	{
		if (Kohana::$caching and Str::$_cache === NULL)
		{
			Str::$_cache = (array) Kohana::cache('Str()');
		}
	}
	
	/**
	 * Add Str overriding methods below
	 */
}
// End Str
