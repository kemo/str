<?php defined('SYSPATH') or die('No direct script access.');

/**
 * String class
 * 
 * Usage:
 * 
 * echo $string = Str::factory("This is some text and kemal.delalic@gmail.com is some email")
 * 		->chars()				// HTML::chars()
 *		->reduce_slashes()		// Text::reduce_slashes()
 * 		->plural()			 	// Inflector::plural()
 * 		->limit_words(4)		// Text::limit_words()
 * 		->auto_p(); 			// HTML::auto_p()
 * 
 * Now the $string contains our Str object but can still be echoed as a normal string
 * 
 * $str2 = (string) $string;
 * 
 * ====================================================
 *
 * @package 	emag
 * @version 	1.0b
 * @author 		Kemal Delalic <kemal.delalic@gmail.com>
 * 
 * @todo		Cache methods
 */
abstract class Kohana_Str {

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

	protected $_value;
	
	public function __construct($string)
	{
		$this->_value = $string;
	}
	
	public function __call($func, array $args)
	{
		array_unshift($args, $this->_value);
		
		foreach (Str::$_helpers as $key => $class)
		{
			if (method_exists($class, $func))
			{
				$this->_value = call_user_func_array(array($class, $func), $args);
				
				return $this;
			}
		}
		
		throw new Kohana_Exception('Unknown method called: :m', array(':m'=>'Str::'.$func));
	}
	
	public function __toString()
	{
		return $this->_value;
	}

	public static function factory($string)
	{
		return new Str($string);
	}
	
	/**
	 * Add Str methods here
	 */
}
// End Str
