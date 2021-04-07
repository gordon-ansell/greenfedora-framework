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
namespace GreenFedora\FileSystem\Json;

use GreenFedora\FileSystem\FileInfo;
use GreenFedora\FileSystem\Json\JsonFileInterface;

/**
 * Json file object.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
class JsonFile extends FileInfo implements JsonFileInterface
{
	/**
	 * Constructor.
	 *
	 * @param 	string		$fileName		Name of file or directory to process.
	 * @return	void
	 */
	public function __construct(string $fileName)
	{
		parent::__construct($fileName);
	}	

    /**
     * Get the file contents.
     * 
	 * @param	bool		$useIncludePath	Want to use the include path?
	 * @param 	resource	$context		Optional context.
     * @param   int         $offset         Offset to start reading.
     * @param   int         $maxlen         Max length to read.
     * @return  string
     */
    public function read(bool $use_include_path = false, resource $context = null, int $offset = 0, 
        int $maxlen = null): string
    {
        if (null === $maxlen) {
            return file_get_contents($this->getPathname(), $use_include_path, $context, $offset);
        } else {
            return file_get_contents($this->getPathname(), $use_include_path, $context, $offset, $maxlen);
        }
    }

    /**
     * Get the file contents into an array.
     * 
	 * @param	bool		$useIncludePath	Want to use the include path?
	 * @param 	resource	$context		Optional context.
     * @param   int         $offset         Offset to start reading.
     * @param   int         $maxlen         Max length to read.
     * @return  array
     */
    public function readArray(bool $use_include_path = false, resource $context = null, int $offset = 0, 
        int $maxlen = null): array
    {
        return json_decode($this->read($use_include_path, $context, $offset, $maxlen), true);
    }

    /**
     * Write the file contents.
     * 
	 * @param	string		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function write(string $contents, int $flags = 0, resource $context = null)
    {
        return file_put_contents($this->getPathname(), $contents, $flags, $context);
    }

    /**
     * Write the file contents from array.
     * 
	 * @param	array		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function writeArray(array $contents, int $flags = 0, resource $context = null)
    {
        return $this->write(json_encode($contents), $flags, $context);
    }

    /**
     * Append the file contents.
     * 
	 * @param	string		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function append(string $contents, int $flags = 0, resource $context = null)
    {
        $flags |= FILE_APPEND;
        return file_put_contents($this->getPathname(), $contents, $flags, $context);
    }

    /**
     * Append the file contents from array.
     * 
	 * @param	array		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function appendArray(array $contents, int $flags = 0, resource $context = null)
    {
        return $this->append(json_encode($contents), $flags, $context);
    }
}