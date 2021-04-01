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
namespace GreenFedora\DependencyInjection;

use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\Exception\InvalidArgumentException;
use GreenFedora\DependencyInjection\Exception\OutOfBoundsException;

/**
 * Dependency injection container.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Container implements ContainerInterface
{
	/**
	 * Instances we've created.
	 * @var array
	 */
	protected $instances = array();	
	
	/**
	 * Aliases for instances.
	 *
	 * @var array
	 */
	protected $aliases = array();	

	/**
	 * See if we have an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 *
	 * @return 	bool
	 */
	public function has($className) : bool
	{
		return array_key_exists($className, $this->instances) or array_key_exists($className, $this->aliases);
	}	

	/**
	 * Get an instance of a class.
	 *
	 * @param 	string 	$className	Class name or alias.
	 * @return 	object
	 *
	 * @throws 	OutOfBoundsException If the class does not exist.
	 */
	public function get($className)
	{
		if (!array_key_exists($className, $this->instances)) {
			if (array_key_exists($className, $this->aliases)) {
				return $this->instances[$this->aliases[$className]];
			} else {
				throw new OutOfBoundsException(sprintf("Cannot get instance of '%s' as it does not exist", $className));
			}
		}
		return $this->instances[$className];
	}	
	
	/**
	 * Create an instance of a class.
	 *
	 * @param 	string 			$className	Class name.
	 * @param 	array			$args		Arguments to pass to constructor.
	 *
	 * @return 	object|null
	 *
	 * @throws 	InvalidArgumentException If the class has already been created.
	 */
	public function create(string $className, array $args = array())
	{
		echo "cc1 " . $className . PHP_EOL;
		if (array_key_exists($className, $this->instances)) {
			echo "cc1a " . $className . PHP_EOL;
			throw new InvalidArgumentException(sprintf("Cannot create instance of '%s' as it already exists", $className));
			return null;
		}
		echo "cc2" . PHP_EOL;

		$rc = new \ReflectionClass($className);
		echo "cc3" . PHP_EOL;
		$this->instances[$className] = $rc->newInstanceArgs($args);
		echo "cc4" . PHP_EOL;

		return $this->instances[$className];
	}
	
	/**
	 * Create an alias for a class.
	 *
	 * @param 	string 		$alias 		Alias to create.
	 * @param 	string		$className	Class name to create alias for.
	 *
	 * @return 	void
	 */
	public function alias($alias, $className)
	{
		$this->aliases[$alias] = $className;
	}		

	/**
	 * Create and/or get an instance of a class.
	 *
	 * @param 	string 		$className	Class name.
	 * @param 	array		$args		Arguments to pass to constructor.
	 *
	 * @return 	object
	 */
	public function createOrGet(string $className, array $args = array())
	{
		if (!array_key_exists($className, $this->instances)) {
			return $this->create($className, $args);
		}
		return $this->get($className);
	}	
}
