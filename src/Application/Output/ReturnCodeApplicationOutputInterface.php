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
namespace GreenFedora\Application\Output;

use GreenFedora\Application\Output\ApplicationOutputInterface;

/**
 * Return code output.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ReturnCodeApplicationOutputInterface extends ApplicationOutputInterface
{	
	/**
	 * Get the output.
	 *
	 * @return 	mixed
	 */
	public function getOutput();
	
	/**
	 * Set the output.
	 *
	 * @param 	mixed 	$output		Whatever it may be.
	 *
	 * @return 	void
	 */
	public function setOutput($output);
}
