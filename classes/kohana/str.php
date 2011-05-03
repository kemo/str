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

	/**
	 * @var array	list of functions callable on Str objects 
	 */
	protected static $_cache;
	
	/**	 * 
	 * @var	array list of helpers ordered by priorities
	 */
	protected static $_helpers = array
	(
		'Text',
		'HTML',
		'Inflector',
	);
	
	/**
	 * Has the shutdown been registered?
	 * @var bool
	 */
	protected static $_shutdown_registered = FALSE;
	
	/**
	 * Factory method for easier chaining
	 * @param	string	$string
	 * @return	Str
	 */
	public static function factory($string)
	{
		return new Str($string);
	}
	
	/**
	 * Find a method attached to Str 
	 * 
	 * [!!] In case that the method doesn't exist,
	 * 		native functions will be used
	 * 
	 * @param 	string 	$func name of the requested method
	 * @return 	mixed	callable function or bool FALSE 
	 */
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
			// @todo method_exists() vs is_callable() ?
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
	
	/**
	 * Appends a helper to Str
	 * Can be another object instance or classname (static methods)
	 * 
	 * @param	mixed	method to append
	 * @return	void
	 */
	public static function helper_append($helper)
	{
		array_push(Str::$_helpers, $helper);
	}
	
	/**
	 * Prepends a helper to Str
	 * Can be another object instance or classname (static methods)
	 * 
	 * @param	mixed	method to prepend
	 * @return	void
	 */
	public static function helper_prepend($helper)
	{
		array_unshift(Str::$_helpers, $helper);
	}
	
	/**
	 * Remove a helper
	 * @param	string	$helper to remove
	 * @return	void
	 */
	public static function helper_remove($helper)
	{
		foreach (Str::$_helpers as $k => $v)
		{
			if ($v === $helper) unset(Str::$_helpers[$k]);
		}
	}
	
	/**
	 * Initializes cache, if needed
	 */
	protected static function init()
	{
		if (Kohana::$caching AND Str::$_cache === NULL)
		{
			Str::$_cache = (array) Kohana::cache('Str_helpers');
		}
		
		// Register shutdown handler if needed
		if (Str::$_shutdown_registered === FALSE)
		{
			register_shutdown_function('Str::__shutdown');
			
			Str::$_shutdown_registered = TRUE;
		}
	}
	
	/**
	 * Shutdown handler
	 */
	public static function __shutdown()
	{
		Kohana::cache('Str_helpers', Str::$_cache);
	}

	/**
	 * @var	string	Value of this object
	 */
	protected $_value;
	
	/**
	 * Str constructor
	 * @param string $string
	 */
	public function __construct($string)
	{
		Str::init();
		
		$this->_value = $string;
	}
	
	/**
	 * @param string $name method name
	 * @param array 	$args
	 * @throws Kohana_Exception
	 */
	public function __call($method, array $args)
	{
		array_unshift($args, $this->_value);
		
		if ($method = Str::find_method($method))
		{
			$this->_value = call_user_func_array($method, $args);
			
			return $this;
		}
		
		// If this method reaches end, throw an exception
		throw new Kohana_Exception('Unknown method called: !class::!method', array(
			'!class'	=> __CLASS__,
			'!method'	=> $method,
		));
	}
	
	/**
	 * What happens when object used as string?
	 */
	public function __toString()
	{
		return $this->_value;
	}
	
	/**
	 * Add Str overriding methods below
	 */
	
	
} // End Str
