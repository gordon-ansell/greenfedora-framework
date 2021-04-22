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

use GreenFedora\FileSystem\Exception\RuntimeException;

/**
 * Stream object.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Stream
{
    /**
     * Stream resource.
     * @var resource
     */
    protected $resource = null;

	/**
	 * Filename.
	 * @var string
	 */
	protected $fileName = null;

	/**
	 * Constructor.
	 *
	 * @param 	string		$fileName		Name of file to process.
	 * @param	string		$openMode		Mode to open file with.
	 * @param	bool		$useIncludePath	Want to use the include path?
	 * @param 	resource	$context		Optional context.
	 *
	 * @return	void
	 */
	public function __construct(string $fileName, string $openMode = 'r', bool $useIncludePath = false, $context = null)
	{
		$this->fileName = $fileName;
        $this->open($fileName, $openMode, $useIncludePath, $context);
	}	

    /**
     * Open the stream.
	 *
	 * @param 	string		$fileName		Name of file to process.
	 * @param	string		$openMode		Mode to open file with.
	 * @param	bool		$useIncludePath	Want to use the include path?
	 * @param 	resource	$context		Optional context.
	 * 
	 * @return 	void
     */
    public function open(string $fileName, string $openMode = 'r', bool $useIncludePath = false, $context = null)
	{
        $this->resource = fopen($fileName, $openMode, $useIncludePath, $context);
    }

	/**
	 * Get the current position of the stream.
	 * 
	 * @return 	int
	 * @throws 	RuntimeException
	 */
	public function tell(): int
	{
		$ret = ftell($this->resource);
		if (false === $ret) {
			throw new RuntimeException(sprintf("Cannot determine position in stream: %s.", $this->fileName));
		}
		return $ret;
	}

	/**
	 * Are we at the end of thye stream?
	 * 
	 * @return 	bool 
	 */
	public function eof(): bool
	{
		return feof($this->resource);
	}

	/**
	 * Close the stream.
	 * 
	 * @return 	bool
	 */
	public function close(): bool
	{
		return fclose($this->resource);
	}

	/**
	 * Detach the stream.
	 *
	 * @return  resource
	 */
	public function detach()
	{
		return $this->resource;
	}

	/**
	 * Get the size of the stream.
	 * 
	 * @return 	int|null
	 */
	public function getSize(): ?int
	{
		return null;
	}

	/**
	 * Is the steam seekable?
	 * 
	 * @return 	bool
	 * @throws 	RuntimeException
	 */
	public function isSeekable(): bool
	{
		if (fseek($this->resource, 0, SEEK_CUR) === -1) {
			return true;
		} 		  
		return false;
	}

	/**
	 * Seek something.
	 * 
	 * @param 	int 	$offset 	Offset.
	 * @param 	int 	$flag 		Seek flag.
	 * @return 	void
	 * @throws 	RuntimeException
	 */
	public function seek(int $offset, int $flag = SEEK_SET)
	{
		if (!fseek($this->resource, $offset, $flag) === -1) {
			throw new RuntimeException(sprintf("Unable to seek in stream: %s.", $this->fileName));
		} 		  		
	}

	/**
	 * Rewind back to the start.
	 * 
	 * @return 	void
	 * @throws 	RuntimeException
	 */
	public function rewind()
	{
		if (!fseek($this->resource, 0, SEEK_SET) === -1) {
			throw new RuntimeException(sprintf("Unable to rewind stream: %s.", $this->fileName));
		} 		  		
	}

	/**
	 * See if the stream is writable.
	 * 
	 * @return 	bool
	 */
	public function isWritable(): bool
	{
		return is_writable($this->fileName);
	} 

	/**
	 * Write to the stream.
	 * 
	 * @param 	string 		$data 	Data to write.
	 * @return 	int
	 * @throws 	RuntimeException
	 */
	public function write(string $data): int
	{
		$ret = fwrite($this->handle, $data);
		if (false === $ret) {
			throw new RuntimeException(sprintf("Unable to write to stream: %s.", $this->fileName));
		}
		return $ret;
	}

	/**
	 * See if the stream is readable.
	 * 
	 * @return 	bool
	 */
	public function isReadable(): bool
	{
		return is_readable($this->fileName);
	} 

	/**
	 * Read from the stream.
	 * 
	 * @return 	int		$len 	Length to read.
	 * @return 	string
	 * @throws 	RuntimeException
	 */
	public function read(int $len): string
	{
		$ret = fread($this->handle, $len);
		if (false === $ret) {
			throw new RuntimeException(sprintf("Unable to read from stream: %s.", $this->fileName));
		}
		return $ret;
	}

	/**
	 * Get the contents of the stream.
	 * 
	 * @return 	string
	 * @throws 	RuntimeException
	 */
	public function getContents(): string
	{
		$ret = stream_get_contents($this->handle);
		if (false === $ret) {
			throw new RuntimeException(sprintf("Unable to read remaining contents of stream: %s.", $this->fileName));
		}
		return $ret;
	}

	/**
	 * Get the matadata of the stream.
	 * 
	 * @param 	string|null 		$key 	Optional metadata key.
	 * @return 	array|mixed|null
	 * @throws 	RuntimeException
	 */
	public function getMetadata(?string $key = null)
	{
		$ret = stream_get_meta_data($this->handle);
		if (false === $ret) {
			throw new RuntimeException(sprintf("Unable to read the metadata of stream: %s.", $this->fileName));
		}
		if (is_null($key)) {
			return $ret;
		} else if (array_key_exists($key, $ret)) {
			return $ret[$key];
		} else {
			throw new RuntimeException(sprintf("No '%s' key in metadata of stream: %s.", $key, $this->fileName));
		}
		return null;
	}

	/**
	 * Destructor.
	 * 
	 * @return 	void
	 */
	public function __destruct()
	{
		$this->close();
	}
}
