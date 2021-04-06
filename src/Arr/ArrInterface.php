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

use GreenFedora\Arr\Exception\InvalidArgumentException;

/**
 * Array object interface.
 *
 * Extends the PHP SPL ArrayObject class with some additional functions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ArrInterface
{
    /**
     * Load some values.
     *
     * @param   array|Arr|Traversable       $vals           Values to load.
     * @return  void
     * @throws  InvalidArgumentException
     */
    public function loadValues($vals);

	/**
	 * Set or get the flag that controls exceptions.
	 *
	 * @param 	bool|null	$set		Either a bool to set it or just null to get it.
	 *
	 * @return	bool	Original value if we're setting, otherwise the current value.
	 */
	public function notFoundTriggersException(?bool $set = null) : bool;
	
	/**
	 * See if we contain something. Emulates in_array().
	 *
	 * @param 	mixed 		$test 		Thing to test.
	 * 
	 * @return 	bool
	 */
	public function in($test) : bool;	

	/**
	 * Get the keys. Emulates array_keys().
	 *
	 * @return 	array
	 */
	public function keys() : array;

	/**
	 * Get the array key at a certain index.
	 *
	 * @param 	int 	$index 		Index to get.
	 *
	 * @return 	mixed
	 */	
	public function key(int $index);
	
	/**
	 * Get an element at a given index.
	 *
	 * @param 	int 	$index 		Index to get element from.
	 *
	 * @return 	mixed
	 */
	public function at(int $index);

	/**
	 * Simply return true if we have an element and it's exactly true.
	 *
	 * @param 	mixed 		$key 	Element key.
	 *
	 * @return 	bool
	 */
	public function isTrue($key) : bool;

	/**
	 * Do a recursive merge-replace.
	 *
	 * @param	interable	$new				New data to merge in with us.
	 *
	 * @return	self
	 *
	 * @throws	InvalidArgumentException	If something we passed in is a bit naff.
	 */
	public function mergeReplaceRecursive(iterable $new) : self;
	
    /**
	 * Get all elements that don't have a key that begins with a certain string.   
     *
     * @param 	string|array 	$begin 		String(s) to avoid.
     * @param 	bool			$preserve 	Preserve objects?
     * @param 	bool 			$numKeys	Make numeric keys integers?
     * @return  ArrInterface
     */
    public function notBeginningWith($begin, bool $preserve = true, bool $numKeys = false) : self;

    /**
	 * Sort array by column.
	 *
	 * @param 	string 	$col 		Column to sorty by.
	 * @param 	int 	$spec 		Sort spec.	
	 * @return 	void
	 */
	public function sortByCol(string $col, int $spec = SORT_ASC);

    /**
	 * Sort array by keys.
	 *
	 * @param 	int 	$spec 		Sort spec.	
	 * @return 	void
	 */
	public function kSort(int $spec = SORT_REGULAR);

    /**
     * See if something's NOT in this array.
     *
     * @param 	mixed 	$thing 		Thing to check.
     * @param 	bool 	$strict 	Strict check?
     * @return  bool
     */
    public function notIn($thing, bool $strict = false) : bool;

    /**
     * Get the key of something in this array.
     *
     * @param 	mixed 	$thing 		Thing to check.
     * @param 	bool 	$strict 	Strict check?
     * @return  string|null
     */
    public function indexOf($thing, bool $strict = false) : ?string;

    /**
     * See if an element is (strictly) true.
     *
     * @param 	string 	$key 		Key to check.
     * @return  bool
     */
    public function isStrictlyTrue(string $key) : bool;

    /**
     * Get the (string) key for something at a given index.
     *
     * @param 	int 	$index 		Index to look up.
     * @return  string
     */
    public function keyFromIndex(int $index) : string;
    
    /**
     * Get a value by its index (or default).
     *
     * @param   int      	$index          Index.
     * @param   mixed       $default        Default.
     * @return  mixed
     */
    public function getByIndex(int $index, $default = null);
    
    /**
     * Count elements.
     *
     * @param 	string 	$key 		Key to check.
     * @return  int
     */
    public function cnt(string $key) : int;

	/**
	 * Convert ourselves to a proper array.
	 *
     * @param 	bool	$preserve 	Preserve objects?
     * @param 	bool 	$numKeys	Make numeric keys integers?
	 *
	 * @return 	array
	 */
	public function toArray(bool $preserve = true, bool $numKeys = false) : array;
	
	/**
	 * See if we have a particular value.
	 *
	 * @param 	mixed 		$key				Key to check.
	 *
	 * @return 	bool
	 */
	public function has($key) : bool;
	
	/**
	 * Get a value or return a default.
	 *
	 * @param	mixed 		$key				Key of value to get.
	 * @param 	mixed 		$default 			Default if key does not exist.
	 * @param	bool|null	$except				Trigger exception if not found?
	 *
	 * @return	mixed
	 */
	public function get($key, $default = null, ?bool $except = null);
	
	/**
	 * Set a value,
	 *
	 * @param 	mixed 		$key 				Key of value to set.
	 * @param 	mixed 		$val 				Value to set.
	 *
	 * @return 	self
	 */
	public function set($key, $val) : self;
}