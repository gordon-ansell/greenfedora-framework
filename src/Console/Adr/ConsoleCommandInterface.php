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
namespace GreenFedora\Console\Adr;

use GreenFedora\Application\Adr\ActionInterface;

/**
 * The base interface for console actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ConsoleCommandInterface extends ActionInterface 
{
	/**
	 * Set the help name.
	 * 
	 * @param 	string 	$name 	Name to set.
	 * @return 	ConsoleCommandInterface
	 */
	public function setName(string $name): ConsoleCommandInterface;

	/**
	 * Set the help description.
	 * 
	 * @param 	string 	$desc 	Description to set.
	 * @return 	ConsoleCommandInterface
	 */
	public function setDescription(string $desc): ConsoleCommandInterface;
}