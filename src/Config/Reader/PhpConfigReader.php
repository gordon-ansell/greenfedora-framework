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

use GreenFedora\Config\Reader\ConfigReaderInterface;
use GreenFedora\FileSystem\File;

/**
 * Read a php config file.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class PhpConfigReader implements ConfigReaderInterface
{
	/**
	 * Read the file and return the results as an array.
	 *
	 * @param 	string 		$file		File to read.
	 *
	 * @return	array
	 */
	public function read(string $file) : array
	{
		$file = new File($file);
		return $file->getIncludedContents();	
	}	
}