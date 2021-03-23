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

use GreenFedora\FileSystem\Yaml\YamlFile;
use GreenFedora\FileSystem\Yaml\FileHasYamlInterface;
use GreenFedora\FileSystem\Yaml\Exception\RuntimeException;

/**
 * For a file that has YAML but isn't entirely YAML.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class FileHasYaml extends YamlFile implements FileHasYamlInterface
{
	/**
	 * The non YAML content.
	 * @var string
	 */
	protected $content = null;
	
	/**
	 * Yaml config.
	 * @var array
	 */
	protected $yamlCfg = array(
		'start'	=>	'---',
		'end'	=>	'---',	
	);	
		
	/**
	 * Constructor.
	 *
	 * @param 	string			$fileName		Name of file or directory to process.
	 * @param 	iterable		$yamlCfg 		YAML config specs.
	 * @param	string			$openMode		Mode to open file with.
	 * @param	bool			$useIncludePath	Want to use the include path?
	 * @param 	resource		$context		Optional context.
	 *
	 * @return	void
	 */
	public function __construct(string $fileName, iterable $yamlCfg = array(), string $openMode = 'r', bool $useIncludePath = false, resource $context = null)
	{
		$this->yamlCfg = array_replace_recursive($this->yamlCfg, $yamlCfg);
		parent::__construct($fileName, $openMode, $useIncludePath, $context);
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
		// Delimiters.
		$start = $this->yamlCfg['start'];
		$end = $this->yamlCfg['end'];
		
		// Read the file into an array.
		$fileData = file($this->getPathname());
		
		// Loop through splitting the data in the YAML bits and the content bits.
		$inYaml = false;
		$doneYaml = false;
		$yamlData = '';
		$this->content = '';
		foreach ($fileData as $line) {
			if (!$doneYaml) {
				if (!$inYaml and substr($line, 0, strlen($start)) == $start) {
					$inYaml = true;				
				} else if ($inYaml and substr($line, 0, strlen($end)) == $end) {
					$inYaml = false;
					$doneYaml = true;
				} else if ($inYaml) {
					$yamlData .= $line;
				} else {
					$this->content .= $line;
				}	
			} else {
				$this->content .= $line;
			}		
		}
		
		// Now process the YAML.
		try {
			$raw = spyc_load($yamlData);
		} catch (Exception $ex) {
			throw new RuntimeException(sprintf("Cannot read YAML data from file '%s': %s", $this->getPathname(), $ex->getMessage()));
		}
		if (false === $raw) {
			$msg = error_get_last();
			throw new RuntimeException(sprintf("Cannot read YAML string data from file '%s': %s", $this->getPathname(), $msg['message']));
		}
		
		// Save the data.
		$this->yamlData = $raw;	
	}		

	/**
	 * Get the content.
	 *
	 * @return 	string
	 */
	public function getContent() : string
	{
		return $this->content;
	}	
}
