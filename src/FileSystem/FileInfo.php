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
use GreenFedora\FileSystem\FileInfoInterface;
use GreenFedora\FileSystem\DirIter;
use GreenFedora\FileSystem\DirIterInterface;
use GreenFedora\FileSystem\Exception\RuntimeException;
use GreenFedora\FileSystem\FileCommonTrait;

/**
 * File info object.
 *
 * Extends the PHP SPL SplFileInfo class with some additional functions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class FileInfo extends \SplFileInfo implements FileInfoInterface
{
	use FileCommonTrait;
	
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

	/**
	 * Open a file from file info.
	 */
	public function openFile(string $openMode = "r" , bool $useIncludePath = false , \resource $context = null): File
	{
		return parent::openFile($openMode, $useIncludePath, $context);
	}

}
