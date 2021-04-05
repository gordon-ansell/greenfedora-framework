<?php

/**
 * This file is part of the GordyAnsell GreenFedora PHP framework.
 *
 * (c) Gordon Ansell <contact@gordonansell.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);
namespace GreenFedora\Arr;

use GreenFedora\Arr\Exception\BadMethodCallException;
use GreenFedora\Arr\Exception\InvalidArgumentException;
use GreenFedora\Arr\Exception\OutOfBoundsException;
use GreenFedora\Arr\ArrIter;
use GreenFedora\Arr\ArrInterface;

/**
 * Array object.
 *
 * Extends the PHP SPL ArrayObject class with some additional functions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Arr extends \ArrayObject implements ArrInterface
{
	/**
	 * Flag: trigger exceptions on not found.
	 * @var int
	 */
	const NOT_FOUND_TRIGGERS_EXCEPTION	=	16; 
	
	/**
	 * Flag: preserve types when setting.
	 * @var int
	 */
	const PRESERVE_TYPE_ON_SET =	32;	
	 	
	/**
	 * Do we throw an exception on not found?
	 * @var bool
	 */
	protected $notFoundTriggersException = false;
		
	/**
	 * Constructor.
	 *
	 * @param	iterable	$input 				Either an array or an object.
	 * @param 	int 		$flags 				As per \ArrayObject.
	 * @param 	string 		$iteratorClass		Class to use for iterators.
	 *
	 * @return	void
	 */
	public function __construct(iterable $input = array(), int $flags = 0, string $iteratorClass = ArrIter::class)
	{
		// Construct the parent.
		parent::__construct(array(), $flags, $iteratorClass);
		
		if (($flags & self::NOT_FOUND_TRIGGERS_EXCEPTION) == self::NOT_FOUND_TRIGGERS_EXCEPTION) {
			$this->notFoundTriggersException(true);
		}
		
		foreach ($input as $k => $v) {
			$this->offsetSet($k, $v);
		}
		
	}
	
	/**
	 * Set or get the flag that controls exceptions.
	 *
	 * @param 	bool|null	$set		Either a bool to set it or just null to get it.
	 *
	 * @return	bool	Original value if we're setting, otherwise the current value.
	 */
	public function notFoundTriggersException(?bool $set = null) : bool
	{
		if (is_null($set)) {
			return $this->notFoundTriggersException;
		}
		
		$saved = $this->notFoundTriggersException;
		$this->notFoundTriggersException = $set;
		return $saved;
	}	
	
	/**
	 * See if we contain something. Emulates in_array().
	 *
	 * @param 	mixed 		$test 		Thing to test.
	 * 
	 * @return 	bool
	 */
	public function in($test) : bool	
	{
		return in_array($test, $this->toArray());
	}
	
	/**
	 * Get the keys. Emulates array_keys().
	 *
	 * @return 	array
	 */
	public function keys() : array
	{
		return array_keys($this->toArray());
	}	
	
	/**
	 * Get the array key at a certain index.
	 *
	 * @param 	int 	$index 		Index to get.
	 *
	 * @return 	mixed
	 */	
	public function key(int $index)
	{
		$keys = $this->keys();
		if (isset($keys[$index])) {
			return $keys[$index];
		}	
		throw new OutOfBoundsException(sprintf("No %u index found", $index));
	}
	
	/**
	 * Get an element at a given index.
	 *
	 * @param 	int 	$index 		Index to get element from.
	 *
	 * @return 	mixed
	 */
	public function at(int $index)
	{
		return $this->{$this->key($index)};
	}	
	
	/**
	 * Simply return true if we have an element and it's exactly true.
	 *
	 * @param 	mixed 		$key 	Element key.
	 *
	 * @return 	bool
	 */
	public function isTrue($key) : bool
	{
		return $this->offsetExists($key) and true === $this->$key;
	}	
	
	/**
	 * Is this a sequential array?
	 *
	 * @return 	bool
	 */
	public function isSequential() : bool
	{
		return self::isArraySequential($this->toArray());
	}	
		
	/**
	 * Do a recursive merge-replace.
	 *
	 * @param	interable	$new				New data to merge in with us.
	 *
	 * @return	ArrInterface
	 *
	 * @throws	InvalidArgumentException	If something we passed in is a bit naff.
	 */
	public function mergeReplaceRecursive(iterable $new) : ArrInterface
	{
		if (!is_array($new)) {
			if (method_exists($new, 'toArray')) {
				$new = $new->toArray();
			} else {
				throw new InvalidArgumentException(sprintf("Invalid argument passed to Arr::mergeReplaceRecursive. It's of type %s and has no 'toArray' method", gettype($new)));
			}
		}	
		
		$mergeArr = array_replace_recursive($this->toArray(), $new);
		if (is_null($mergeArr)) {
			throw new InvalidArgumentException(sprintf("Invalid argument passed to Arr::mergeReplaceRecursive. It's of type %s and returned null from the merge", gettype($new)));
		}
		
		$this->exchangeArray(new static($mergeArr));
		
		return $this;
	}
	
	/**
	 * Convert ourselves to a proper array.
	 *
     * @param 	bool	$preserve 		Preserve objects?
     * @param 	bool 	$numKeys		Make numeric keys integers?
     * @param 	bool	$preserveSelf	Preserve objects of ourself?
	 *
	 * @return 	array
	 */
	public function toArray(bool $preserve = false, bool $numKeys = false, bool $preserveSelf = false) : array
	{
		$ret = array();
		foreach ($this as $key => $value) {
	        if ($numKeys and ctype_digit(strval($key))) {
		        $key = intval($key);
	        }
	        
            if (!$preserveSelf and $value instanceof static) {
                $ret[$key] = $value->toArray($preserve, $numKeys, $preserveSelf);
                //$ret[$key] = $value->getArrayCopy();
            } else if (!$preserve and is_object($value) and method_exists($value, 'toArray')) {
	            $ret[$key] = $value->toArray($preserve, $numKeys, $preserveSelf);
            } else {
                $ret[$key] = $value;
            }			
		}
		return $ret;
	} 
	
	/**
	 * Overload base set to handle creation of new objects.
	 *
	 * @param 	mixed 		$index 				Index to set.
	 * @param 	mixed 		$newVal 			New value to set.
	 *
	 * @return 	void
	 */
	public function offsetSet($index, $newVal) 
	{
		$ptos = (($this->getFlags() & self::PRESERVE_TYPE_ON_SET) == self::PRESERVE_TYPE_ON_SET) ? true : false;
		
		if ((is_array($newVal) or $newVal instanceof \Traversable) and !$ptos) {
			parent::offsetSet($index, new static($newVal, $this->getFlags(), $this->getIteratorClass()));
		} else {
			parent::offsetSet($index, $newVal);
		}
	}	
	
	/**
	 * See if we have a particular value.
	 *
	 * @param 	mixed 		$key				Key to check.
	 *
	 * @return 	bool
	 */
	public function has($key) : bool
	{
		return $this->offsetExists($key);
	}	
	
	/**
	 * Get a value or return a default.
	 *
	 * @param	mixed 		$key				Key of value to get.
	 * @param 	mixed 		$default 			Default if key does not exist.
	 * @param	bool|null	$except				Trigger exception if not found?
	 *
	 * @return	mixed
	 */
	public function get($key, $default = null, ?bool $except = null)
	{
		if ($this->has($key)) {
			return $this->offsetGet($key);
		}
		
		$except = is_null($except) ? $this->notFoundTriggersException : $except;
		
		if ($except) {
			throw new OutOfBoundsException(sprintf("Arr does not have key '%s'", $key));	
		}
		
		return $default;
	}
	
	/**
	 * Set a value,
	 *
	 * @param 	mixed 		$key 				Key of value to set.
	 * @param 	mixed 		$val 				Value to set.
	 *
	 * @return 	ArrInterface
	 */
	public function set($key, $val) : ArrInterface
	{
		$this->offsetSet($key, $val);
		return $this;
	}		
	
	/**
	 * Permit object access.
	 *
	 * @param	mixed 		$key 				Key of value to get.
	 * @param 	mixed 		$val 				Value to set.
	 *
	 * @return	void
	 */
	public function __set($key, $val)
	{
		return $this->offsetSet($key, $val);	
	}		 		

	/**
	 * Permit object access.
	 *
	 * @param	mixed 		$key 				Key of value to get.
	 *
	 * @return	mixed
	 */
	public function __get($key)
	{
		return $this->get($key);	
	}	
	
	/**
	 * Dump the object as an array.
	 *
	 * @return 	void
	 */
	public function dump()
	{
		print_r($this->toArray());
	}
	
	// =========================
	// Static helpers.
	// =========================		 		
	 
	/**
	 * Determine if an array is sequential.
	 *
	 * @param 	array 	$array		Array to check.
	 *
	 * @return 	bool
	 */
	public static function isArraySequential(array $array) : bool
	{
		return (array_keys($array) === range(0, count($array) - 1));
	}	

    /**
     * See if an array is associative (i.e. it has at least one non-numeric key).
     * 
     * @param   array       $arr            Array to test.
     * @return  bool
     */
    public static function isArrayAssociative(array $arr) : bool
    {
        return (count(array_filter(array_keys($arr), 'is_string')) > 0);
    }

    /**
     * Recursive implode.
     * 
     * @param   string          $sep            Separator.
     * @param   array           $array          Array to implode.
     * @return  string
     */
    public static function implode(string $sep, array $array) : string 
    {
        $ret = '';
        foreach ($array as $item) {
            if ('' != $ret) {
                $ret .= $sep;
            }
            if (is_array($item)) {
                $ret .= self::implode($sep, $item);
            } else {
                $ret .= $item;
            }
        }
        return $ret;
    }
}