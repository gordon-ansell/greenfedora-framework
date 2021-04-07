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

use GreenFedora\FileSystem\Json\JsonFileInterface;

/**
 * Json file object interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */
interface JsonFileInterface
{
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
        int $maxlen = null): string;

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
        int $maxlen = null): array;

    /**
     * Write the file contents.
     * 
	 * @param	string		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function write(string $contents, int $flags = 0, resource $context = null);

    /**
     * Write the file contents from array.
     * 
	 * @param	array		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function writeArray(array $contents, int $flags = 0, resource $context = null);

    /**
     * Append the file contents.
     * 
	 * @param	string		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function append(string $contents, int $flags = 0, resource $context = null);

    /**
     * Append the file contents from array.
     * 
	 * @param	array		$contents	    Contents to write.
     * @param   int         $flags          Flags.
	 * @param 	resource	$context		Optional context.
     * @return  int|false
     */
    public function appendArray(array $contents, int $flags = 0, resource $context = null);
}