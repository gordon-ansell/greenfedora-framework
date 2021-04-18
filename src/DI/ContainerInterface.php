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

}
