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

use GreenFedora\FileSystem\FileInfo;
use GreenFedora\FileSystem\FileInterface;
use GreenFedora\FileSystem\FileCommonTrait;
use GreenFedora\FileSystem\Exception\RuntimeException;

/**
 * File object.
 *
 * Extends the PHP SPL SplFileObject class with some additional functions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class File extends \SplFileObject implements FileInterface
{
	use FileCommonTrait;
	
	/**
	 * Constructor.
	 *
	 * @param 	string		$fileName		Name of file or directory to process.
	 * @param	string		$openMode		Mode to open file with.
	 * @param	bool		$useIncludePath	Want to use the include path?
	 * @param 	resource	$context		Optional context.
	 *
	 * @return	void
	 */
	public function __construct(string $fileName, string $openMode = 'r', bool $useIncludePath = false, $context = null)
	{
		parent::__construct($fileName, $openMode, $useIncludePath, $context);
		$this->setFileClass(File::class);
		$this->setInfoClass(FileInfo::class);
	}	

    /**
     * Return included contents of a file.
     *
     * @return  mixed
     * @throws  RuntimeException	If the file is not readable.
     * @throws  RuntimeException	If the file isn't a file at all.
     */
    public function getIncludedContents()
    {
        if (!$this->isReadable()) {
            throw new RuntimeException(sprintf("Cannot read file '%s'", $this->getPathname()));
        } else if (!$this->isFile()) {
            throw new RuntimeException(sprintf("Cannot include '%s' - it's not a file", $this->getPathname()));
        }
        
        return include $this->getPathname();
    }
}
