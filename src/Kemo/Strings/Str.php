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
 * @author  Kemal Delalic <kemal.delalic@gmail.com>
 */
class Str
{

    const DEFAULT_CHUNK_END = "\r\n";
    const DEFAULT_CHUNK_LENGTH = 76;
    const DEFAULT_PAD_STRING = ' ';
    const DEFAULT_PAD_TYPE = \STR_PAD_RIGHT;
    const DEFAULT_TRIM_CHARACTER_MASK = " \t\n\r\0\x0B";

    const ENCODING_UTF8 = 'UTF-8';

    /**
     * String encoding
     *
     * @var string
     */
    protected $encoding;

    /**
     * Contains the list of values that have been changed in this object
     *
     * @var array
     */
    protected $values = array();

    /**
     * Creates a Str object
     *
     * @param string $string to represent
     */
    public function __construct($string)
    {
        $this->_set($string);
    }

    /**
     * What happens when non-existing method is called on this object
     * e.g. one that's not defined or is protected
     *
     * @param  string $method
     * @param  array  $arguments
     *
     * @return self   (chainable)
     */
    final public function __call($method, array $arguments)
    {
        throw new \BadMethodCallException(sprintf('Invalid method "%s" called', $method));
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
     * Gets the current string encoding
     *
     * @return string       Encoding
     * @throws StrException If encoding can't be detected
     */
    final public function encoding()
    {
        if (!isset($this->encoding)) {
            if (function_exists('mb_detect_encoding')) {
                return $this->encoding = \mb_detect_encoding($this->value());
            }

            if (function_exists('utf8_compliant') AND \utf8_compliant($this->value())) {
                return $this->encoding = static::ENCODING_UTF8;
            }

            throw new StrException(\sprintf('Could not detect string encoding : %s', $this->value()));
        }

        return $this->encoding;
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

    /**
     * Used for setting default parameter values.
     *
     * If the $passed value is NULL, this will return the $default
     * otherwise $passed will be returned back.
     *
     * @param  mixed $passed
     * @param  mixed $default
     *
     * @return mixed
     */
    protected function _default($passed, $default)
    {
        return $passed === null ? $default : $passed;
    }

    /**
     * Allows using array character masks in trim methods
     *
     * @param  string|array $character_mask Character mask to format (if array)
     *
     * @return string                       Character mask usable by trim functions
     */
    protected function _formatCharacterMask($character_mask)
    {
        if (is_array($character_mask)) {
            return \implode('', $character_mask);
        }

        return $character_mask;
    }

    /**
     * Sets a new value for current string
     *
     * @param  string $value String value to set
     *
     * @return self          Chainable
     */
    protected function _set($value)
    {
        $this->values[] = $value;

        // Reset encoding
        unset($this->encoding);

        return $this;
    }

    /**
     * Quote string with slashes in a C style
     *
     * @link   http://php.net/manual/en/function.addcslashes.php
     *
     * @param  string $charlist
     *
     * @return self (chainable)
     */
    public function addCslashes($charlist)
    {
        return $this->_set(
                    \addcslashes($this->value(), $charlist)
        );
    }

    /**
     * Quote string with slashes
     *
     * @link   http://php.net/manual/en/function.addslashes.php
     * @return self (chainable)
     */
    public function addSlashes()
    {
        return $this->_set(
                    \addslashes($this->value())
        );
    }

    /**
     * Splits the string into smaller chunks
     *
     * @link   http://php.net/chunk_split
     *
     * @param  int    $length The chunk length.
     * @param  string $end The line ending sequence.
     *
     * @return self             (chainable)
     */
    public function chunkSplit($length = null, $end = null)
    {
        $end = $this->_default($end, static::DEFAULT_CHUNK_END);
        $length = $this->_default($length, static::DEFAULT_CHUNK_LENGTH);

        return $this->_set(
                    \chunk_split($this->value(), $length, $end)
        );
    }

    /**
     * Binary safe string comparison
     * [!!] Not chainable
     *
     * Return values:
     *  < 0 if this string is less than target string
     *  > 0 if this string is greater than target string
     * == 0 if both strings are equal
     *
     * @link   http://php.net/manual/en/function.strcmp.php
     *
     * @param  Str|string $target to compare current string to
     *
     * @return int
     */
    public function compare($target)
    {
        return \strcmp($this->value(), (string) $target);
    }

    /**
     * Binary safe case-insensitive string comparison
     * [!!] Not chainable
     *
     * Return values:
     * < 0 if this string is less than target string
     * > 0 if this string is greater than target string
     * == 0 if strings are equal
     *
     * @link   http://php.net/manual/en/function.strcasecmp.php
     *
     * @param  string $target to compare current string to
     *
     * @return int
     */
    public function compareInsensitive($target)
    {
        return \strcasecmp($this->value(), (string) $target);
    }

    /**
     * Appends another string to this string
     *
     * @param  string $string String to concatonate
     *
     * @return self           Chainable
     */
    public function concat($string)
    {
        return $this->_set($this->value().$string);
    }

    /**
     * Does current string contain a subtring?
     * [!!] Not chainable
     *
     * @param  string $substring to search for
     *
     * @return boolean
     */
    public function contains($substring)
    {
        return $this->position((string) $substring, 0) !== false;
    }

    /**
     * Return information about characters used in a string
     * [!!] Not chainable
     *
     * @link   http://php.net/count_chars
     *
     * @param  boolean $ascii Return ASCII representations?
     *
     * @return mixed
     */
    public function countChars($ascii = FALSE)
    {
        $chars = \count_chars($this->value(), 1);

        if ($ascii === TRUE)
            return $chars;

        foreach ($chars as $ord => $count)
        {
            unset($chars[$ord]);

            $chars[chr($ord)] = $count;
        }

        return $chars;
    }

    /**
     * Splits current string by string
     * [!!] Not chainable
     *
     * @link   http://php.net/explode
     *
     * @param  string $delimiter The boundary string.
     *
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
     *
     * @param  array $pieces
     *
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
     *
     * @param  array $replacements List of search -> replace pairs
     *
     * @return self (chainable)
     * @see    \Kemo\Strings\Str::replace()
     */
    public function ireplace(array $replacements)
    {
        return $this->_set(
                    \str_ireplace(\array_keys($replacements), \array_values($replacements), $this->value())
        );
    }

    /**
     * Strip whitespace (or other characters) from the beginning of a string
     *
     * @link   http://php.net/ltrim
     *
     * @param  string $character_mask List of characters to trim (optional)
     *
     * @return self                   Chainable
     */
    public function ltrim($character_mask = null)
    {
        $character_mask = $this->_default($character_mask, static::DEFAULT_TRIM_CHARACTER_MASK);
        $character_mask = $this->_formatCharacterMask($character_mask);

        return $this->_set(
                    \ltrim($this->value(), $character_mask)
        );
    }

    /**
     * Converts new lines to <br /> elements
     *
     * @return self Chainable
     */
    public function nl2br()
    {
        return $this->_set(
                    \nl2br($this->value())
        );
    }

    /**
     * Pad the string to a certain length with another string
     *
     * @link   http://php.net/str_pad
     *
     * @param  int    $pad_length If the value of pad_length is negative, less than, or equal to the length of current string, no padding takes place
     * @param  string $pad_string String to pad the current string with
     * @param  int    $pad_type Optional, can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH. If pad_type is not specified it is assumed to be STR_PAD_RIGHT
     *
     * @return self               Chainable
     */
    public function pad($pad_length, $pad_string = null, $pad_type = null)
    {
        $pad_string = $this->_default($pad_string, static::DEFAULT_PAD_STRING);
        $pad_type = $this->_default($pad_type, static::DEFAULT_PAD_TYPE);

        return $this->_set(
                    \str_pad($this->value(), $pad_length, $pad_string, $pad_type)
        );
    }

    /**
     * Find the position of the first occurrence of a substring in a string
     * [!!] Not chainable
     *
     * @param  string  $substring
     * @param  integer $offset offset to start from
     *
     * @return mixed   Integer position on success, FALSE otherwise
     */
    public function position($substring, $offset = 0)
    {
        return \strpos($this->value(), (string) $substring, $offset);
    }

    /**
     * Repeats the string
     *
     * @link   http://php.net/str_repeat
     *
     * @param  int $multiplier How many times to repeat the string
     *
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
     *
     * @param  array $replacements List of [search => replace] pairs
     * @param  int   $count If passed, this will be set to the number of replacements performed.
     *
     * @return self                 Chainable
     */
    public function replace(array $replacements, & $count = null)
    {
        return $this->_set(
                    \str_replace(\array_keys($replacements), \array_values($replacements), $this->value(), $count)
        );
    }

    /**
     * Reverses current string
     *
     * @link   http://php.net/manual/en/function.strrev.php
     * @return self Chainable
     */
    public function reverse()
    {
        return $this->_set(
                    \strrev($this->value())
        );
    }

    /**
     * Perform the ROT13 transform on current string
     *
     * The ROT13 encoding simply shifts every letter
     * by 13 places in the alphabet while leaving non-alpha
     * characters untouched. Encoding and decoding are done by the
     * same function, passing an encoded string as argument will
     * return the original version.
     *
     * @link   http://php.net/str_rot13
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
     *
     * @return self                   Chainable
     */
    public function rtrim($character_mask = null)
    {
        $character_mask = $this->_default($character_mask, static::DEFAULT_TRIM_CHARACTER_MASK);
        $character_mask = $this->_formatCharacterMask($character_mask);

        return $this->_set(
                    \rtrim($this->value(), $character_mask)
        );
    }

    /**
     * Randomly shuffles the string
     *
     * @return self Chainable
     */
    public function shuffle()
    {
        return $this->_set(
                    \str_shuffle($this->value())
        );
    }

    /**
     * Strip HTML and PHP tags
     *
     * @link   http://php.net/manual/en/function.strip-tags.php
     *
     * @param  string $allowable_tags List of allowed tags
     *
     * @return self                    Chainable
     */
    public function stripTags($allowable_tags = NULL)
    {
        return $this->_set(
                    \strip_tags($this->value(), $allowable_tags)
        );
    }

    /**
     * Translate characters or replace substrings
     *
     * @link   http://php.net/strtr
     *
     * @param  array $replace_pairs Array in the form array('from' => 'to', ...)
     *
     * @return self                  Chainable
     */
    public function translate(array $replace_pairs)
    {
        return $this->_set(
                    \strtr($this->value(), $replace_pairs)
        );
    }

    /**
     * Strips whitespace (or other characters) from the beginning and end of the string
     *
     * @link   http://php.net/manual/en/function.trim.php
     *
     * @param  string $character_mask List of characters to trim (optional)
     *
     * @return self                     Chainable
     */
    public function trim($character_mask = null)
    {
        $character_mask = $this->_default($character_mask, static::DEFAULT_TRIM_CHARACTER_MASK);
        $character_mask = $this->_formatCharacterMask($character_mask);

        return $this->_set(
                    \trim($this->value(), $character_mask)
        );
    }

    /**
     * Undoes the last $steps operations
     *
     * @param  integer $steps How many steps to undo
     *
     * @return self           Chainable
     * @throws \InvalidArgumentException if steps isn't an integer
     */
    public function undo($steps = 1)
    {
        if (!\is_int($steps)) {
            throw new \InvalidArgumentException('Str::undo() must be given an integer');
        }

        $steps = \min($steps, \count($this->values) - 1);

        for ($i = 1; $i <= $steps; $i++) {
            \array_pop($this->values);
        }

        return $this;
    }

    /**
     * Returns a string containing all unique characters (in current string)
     * [!!] Not chainable
     *
     * @link   http://php.net/count_chars
     *
     * @return mixed
     */
    public function uniqueChars()
    {
        return \count_chars($this->value(), 3);
    }

    /**
     * Counts the number of words inside string.
     * [!!] Not chainable
     *
     * @param  string $charlist Optional list of additional characters which will be considered as 'word'
     *
     * @return int
     */
    public function wordCount($charlist = null)
    {
        return \str_word_count($this->value(), 0, $charlist);
    }

    /**
     * Returns the list of words inside string
     * [!!] Not chainable
     *
     * @param  string $charlist Optional list of additional characters which will be considered as 'word'
     *
     * @return array   Array in [position => word] format
     */
    public function words($charlist = null)
    {
        return \str_word_count($this->value(), 2, $charlist);
    }
}
