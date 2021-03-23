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

use GreenFedora\DependencyInjection\Exception\InvalidArgumentException;
use GreenFedora\DependencyInjection\Exception\OutOfBoundsException;

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
	public function has($className) : bool;

	/**
	 * Get an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 * @return 	object
	 *
	 * @throws 	OutOfBoundsException If the class does not exist.
	 */
	public function get($className);
	
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
	 * Create an alias for a class.
	 *
	 * @param 	string 		$alias 		Alias to create.
	 * @param 	string		$className	Class name to create alias for.
	 *
	 * @return 	void
	 */
	public function alias($alias, $className);

	/**
	 * Create and/or get an instance of a class.
	 *
	 * @param 	string 		$className	Class name.
	 * @param 	array		$args		Arguments to pass to constructor.
	 *
	 * @return 	object
	 */
	public function createOrGet(string $className, array $args = array());
}
