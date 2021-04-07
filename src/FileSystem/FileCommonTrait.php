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

use GreenFedora\FileSystem\FileCommonInterface;
use GreenFedora\FileSystem\DirIter;
use GreenFedora\FileSystem\DirIterInterface;
use GreenFedora\FileSystem\Exception\RuntimeException;

/**
 * Traits that File and FileInfo share.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

trait FileCommonTrait
{
	/**
	 * Return a director iterator for this object.
	 *
	 * @return	DirIterInterface
	 *
	 * @throws 	RuntimeException	If this isn't a directory.
	 */
	public function getDirectoryIterator() : DirIterInterface
	{
		if ($this->isDir()) {
			return new DirIter($this->getPathname());
		}
		
		throw new RuntimException("Cannot return directory iterator for '%s' because it's not a directory", $this->getPathname());
	}	
	
	/**
	 * Join parts of a path.
	 * 
	 * @param 	string 	$elems	Elements to join.
	 * @return 	string
	 */
	static public function join(...$elems): string
	{
		$ret = '';
		foreach ($elems as $elem) {
			if ('' != $ret) {
				$ret .= DIRECTORY_SEPARATOR;
			}
			$ret .= trim($elem, DIRECTORY_SEPARATOR);
		}
		return DIRECTORY_SEPARATOR . $ret;
	}
}
