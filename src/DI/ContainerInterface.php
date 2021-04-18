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
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @return 	ContainerInterface
	 */
	public function setValue(string $key, $value): ContainerInterface;
	
	/**
	 * Set a class.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @param 	array|null	$args 	Arguments.
	 * @return 	ContainerInterface
	 */
	public function setClass(string $key, $value, ?array $args = null): ContainerInterface;

	/**
	 * Set a singleton.
	 * 
	 * @param 	string 		$key	Key.
	 * @param 	mixed 		$val	Value.
	 * @param 	array|null	$args 	Arguments.
	 * @return 	ContainerInterface
	 */
	public function setSingleton(string $key, $value, ?array $args = null): ContainerInterface;
}
