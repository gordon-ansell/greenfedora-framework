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
namespace GreenFedora\DI;

use GreenFedora\DI\Exception\InvalidArgumentException;
use GreenFedora\DI\Exception\OutOfBoundsException;


use Psr\Container\ContainerInterface as PsrContainerInterface;

/**
 * Dependency injection container interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ContainerInterface extends PsrContainerInterface
{
	/**
	 * Get the map.
	 * 
	 * @return	ContainerMapEntryInterface[] 
	 */
	public function getMap(): ?array;

	/**
	 * See if we have an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 *
	 * @return 	bool
	 */
	public function has(string $className) : bool;

	/**
	 * Get an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 * @return 	object
	 *
	 * @throws 	OutOfBoundsException If the class does not exist.
	 */
	public function get(string $className);
	
	/**
	 * Create an instance of a class.
	 *
	 * @param 	string 		$className	Class name.
	 * @param 	array		$args		Arguments to pass to constructor.
	 *
	 * @return 	object|null
	 *
	 * @throws 	InvalidArgumentException If the class has already been created.
	 */
	public function create(string $className, array $args = array());

	/**
	 * Set a value.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setValue(string $key, $value, ?bool $injectable = null): ContainerInterface;
	
	/**
	 * Set an injectable value.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @return 	ContainerInterface
	 */
	public function setInjectableValue(string $key, $value): ContainerInterface;

	/**
	 * Set a function.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	callable 	$val			Value.
	 * @param 	array|null	$funcparams		Function parameters.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setFunction(string $key, callable $value, ?array $funcparams = null, ?bool $injectable = null): ContainerInterface;

	/**
	 * Set an injectable function.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	callable 	$val			Value.
	 * @param 	array|null	$funcparams		Function parameters.
	 * @return 	ContainerInterface
	 */
	public function setInjectableFunction(string $key, callable $value, ?array $funcparams = null): ContainerInterface;
	/**
	 * Set a class.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setClass(string $key, $value, $args = null, bool $injectable = true): ContainerInterface;

	/**
	 * Set a class and create it.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	object
	 */
	public function setClassAndCreate(string $key, $value, $args = null, bool $injectable = true);

	/**
	 * Set a singleton.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function setSingleton(string $key, $value, $args = null, bool $injectable = true): ContainerInterface;

	/**
	 * Set a singleton and create it.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	object
	 */
	public function setSingletonAndCreate(string $key, $value, $args = null, bool $injectable = true);

	/**
	 * Add a value.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function addValue(string $key, $value, ?bool $injectable = null): ContainerInterface;
	
	/**
	 * Add an injectable value.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @return 	ContainerInterface
	 */
	public function addInjectableValue(string $key, $value): ContainerInterface;

	/**
	 * Add a function.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	callable 	$val			Value.
	 * @param 	array|null	$funcparams		Function parameters.
	 * @param 	bool|null 	$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function addFunction(string $key, callable $value, ?array $funcparams = null, 
		?bool $injectable = null): ContainerInterface;

	/**
	 * Add an injectable function.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	callable 	$val			Value.
	 * @param 	array|null	$funcparams		Function parameters.
	 * @return 	ContainerInterface
	 */
	public function addInjectableFunction(string $key, callable $value, ?array $funcparams = null): ContainerInterface;

	/**
	 * Add a class.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function addClass(string $key, $value, $args = null, bool $injectable = true): ContainerInterface;

	/**
	 * Add a class and create it.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	object
	 */
	public function addClassAndCreate(string $key, $value, $args = null, bool $injectable = true);

	/**
	 * Add a singleton.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	ContainerInterface
	 */
	public function addSingleton(string $key, $value, $args = null, bool $injectable = true): ContainerInterface;

	/**
	 * Add a singleton and create it.
	 * 
	 * @param 	string 		$key			Key.
	 * @param 	mixed 		$val			Value.
	 * @param 	array|null	$args 			Arguments.
	 * @param 	bool 		$injectable 	Is this injectable?
	 * @return 	object
	 */
	public function addSingletonAndCreate(string $key, $value, $args = null, bool $injectable = true);
}
