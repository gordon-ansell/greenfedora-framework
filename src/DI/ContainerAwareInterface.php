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

use GreenFedora\DI\ContainerInterface;

/**
 * Trait for objects that are dependency injection container aware.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ContainerAwareInterface
{
	/**
	 * Get the container.
	 *
	 * @return	ContainerInterface
	 *
	 */ 
	public function getContainer() : ContainerInterface;
	
	/**
	 * See if we have a key.
	 *
	 * @param 	string 	$key	Key.
	 *
	 * @return 	bool
	 */
	public function has(string $key) : bool;

	/**
	 * Create an instance of a class.
	 *
	 * @param 	string 	$className	Class name.
	 * @param 	...		$args		Arguments to pass to constructor.
	 *
	 * @return 	object|null
	 */
	public function create(string $className, ...$args);

	/**
	 * Get an instance of a class.
	 *
	 * @param 	string 	$key	Item key.
	 *
	 * @return 	object|null
	 */
	public function get(string $key);
}
