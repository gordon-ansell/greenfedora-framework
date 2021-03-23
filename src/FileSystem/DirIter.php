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

use GreenFedora\FileSystem\File;
use GreenFedora\FileSystem\FileInfo;
use GreenFedora\FileSystem\DirIterInterface;

/**
 * Directory iterator object.
 *
 * Extends the PHP SPL DirectoryIterator class with some additional functions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class DirIter extends \DirectoryIterator implements DirIterInterface
{
	/**
	 * Constructor.
	 *
	 * @param 	string		$fileName		Name of file or directory to process.
	 *
	 * @return	void
	 */
	public function __construct(string $fileName)
	{
		parent::__construct($fileName);
		$this->setFileClass(File::class);
		$this->setInfoClass(FileInfo::class);
	}	
}
