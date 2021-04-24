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
namespace GreenFedora\FileSystem\Yaml;

use GreenFedora\FileSystem\File;
use GreenFedora\FileSystem\Yaml\YamlFileInterface;
use GreenFedora\FileSystem\Yaml\Exception\RuntimeException;

use Symfony\Component\Yaml\Yaml;

/**
 * YAML file object.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class YamlFile extends File implements YamlFileInterface
{
	/**
	 * The YAML data we read.
	 * @var array
	 */
	protected $yamlData = null;
		
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
	public function __construct(string $fileName, string $openMode = 'r', bool $useIncludePath = false, resource $context = null)
	{
		parent::__construct($fileName, $openMode, $useIncludePath, $context);
		$this->read();
	}
	
	/**
	 * Read the YAML data.
	 *
	 * @return	void
	 *
	 * @throws	RuntimeException	If we can't read the YAML data.
	 */
	protected function read()
	{
		try {
			$raw = Yaml::parseFile($this->getPathname());
		} catch (\Exception $ex) {
			throw new RuntimeException(sprintf("Cannot read yaml data from file '%s': %s", $this->getPathname(), $ex->getMessage()));
		}
		
		if (false === $raw) {
			$msg = error_get_last();
			throw new RuntimeException(sprintf("Cannot read yaml data from file '%s': %s", $this->getPathname(), $msg['message']));
		}
		
		$this->yamlData = $raw;			
	}		

	/**
	 * Write the YAML data out.
	 * 
	 * @param 	array 	$yamlArr 	YAML array to write.
	 * @return 	void
	 */
	public function write(array $yamlArr)
	{
		try {
			$raw = Yaml::dump($yamlArr);
			file_put_contents($this->getPathname(), $raw);
		} catch (\Exception $ex) {
			throw new RuntimeException(sprintf("Cannot write yaml data to file '%s': %s", $this->getPathname(), $ex->getMessage()));
		}
	}

	/**
	 * Get the YAML data.
	 *
	 * @return 	array
	 */
	public function getYamlData() : array
	{
		return $this->yamlData;
	}	
}
