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
namespace GreenFedora\Application;

use GreenFedora\Application\ResponseInterface;

/**
 * Application interface.
 *
 * All applications must implement this interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ApplicationInterface
{
	/**
	 * Get an environment variable.
	 * 
	 * @param 	string|null 	$key 		Key to get.
	 * @param 	mixed 			$default	Default if not found.
	 * @return 	mixed
	 */
	public function env(?string $key = null, $default = null);

	/**
	 * The main run call.
	 *
	 * @return 	ResponseInterface
	 */
	public function main(): ResponseInterface;

}