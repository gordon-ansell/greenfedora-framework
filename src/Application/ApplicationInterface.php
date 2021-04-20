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
	 * The main run call.
	 *
	 * @return 	void
	 */
	public function main();

}