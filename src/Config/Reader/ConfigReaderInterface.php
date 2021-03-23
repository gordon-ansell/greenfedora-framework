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
namespace GreenFedora\Config\Reader;

/**
 * Config reader interface.
 *
 * This is an interface for the main config container.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ConfigReaderInterface
{
	/**
	 * Read the file and return the results as an array.
	 *
	 * @param 	string 		$file		File to read.
	 *
	 * @return	array
	 */
	public function read(string $file) : array;
}