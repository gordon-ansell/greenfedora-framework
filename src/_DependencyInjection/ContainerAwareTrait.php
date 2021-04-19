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

/**
 * Trait for objects that are dependency injection container aware.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

trait ContainerAwareTrait
{
	/**
	 * Dependency injection container.
	 * @var ContainerInterface
	 */
	protected $container = null;	

	/**
	 * Get the container.
	 *
	 * @return	ContainerInterface
	 *
	 */ 
	public function getContainer() : ContainerInterface
	{
		return $this->container;
	}	
	
	/**
	 * See if we have an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 *
	 * @return 	bool
	 */
	public function hasInstance(string $className) : bool
	{
		return $this->getContainer()->has($className);
	}	

	/**
	 * Create an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 * @param 	...		$args		Arguments to pass to constructor.
	 *
	 * @return 	object|null
	 */
	public function createInstance(string $className, ...$args)
	{
		$args = func_get_args();
		array_shift($args);
		return $this->getContainer()->create($className, $args);
	}	

	/**
	 * Create and/or get an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 * @param 	...		$args		Arguments to pass to constructor.
	 *
	 * @return 	object
	 */
	public function createOrGetInstance(string $className, ...$args)
	{
		$args = func_get_args();
		array_shift($args);
		return $this->getContainer()->createOrGet($className, $args);
	}	

	/**
	 * Get an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 *
	 * @return 	object|null
	 */
	public function getInstance(string $className)
	{
		return $this->getContainer()->get($className);
	}	

	/**
	 * Create an alias for a class.
	 *
	 * @param 	string 		$alias 		Alias to create.
	 * @param 	string		$className	Class name to create alias for.
	 *
	 * @return 	void
	 */
	public function aliasInstance($alias, $className)
	{
		$this->getContainer()->alias($alias, $className);
	}		
}
