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
namespace GreenFedora\FileSystem;

use GreenFedora\FileSystem\DirIterInterface;
use GreenFedora\FileSystem\Exception\RuntimeException;

/**
 * Interface for common file stuff.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface FileCommonInterface
{
	/**
	 * Return a director iterator for this object.
	 *
	 * @return	DirIterInterface
	 *
	 * @throws 	RuntimeException	If this isn't a directory.
	 */
	public function getDirectoryIterator() : DirIterInterface;
}
