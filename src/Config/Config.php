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
namespace GreenFedora\Config;

use GreenFedora\Config\ConfigInterface;
use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrIter;
use GreenFedora\FileSystem\DirIter;
use GreenFedora\Config\Reader\PhpConfigReader;

/**
 * Config processor.
 *
 * Reads configs into an array.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Config extends Arr implements ConfigInterface
{
	/**
	 * Which subdirs to process for each mode.
	 * @var array
	 */
	protected $modeSubDirs = array(
		'prod'		=>	array('.'),
		'staging'	=>	array('.', 'staging'),
		'dev'		=>	array('.', 'staging', 'dev'),	
	);
	
	/**
	 * Config file extensions we handle.
	 * @var array
	 */
	protected $configExts = array(
		'php',	
	);	
	
	/**
	 * Constructor.
	 *
	 * @param	iterable	$input 				Either an array or an object.
	 * @param 	int 		$flags 				As per \ArrayObject.
	 * @param 	string 		$iteratorClass		Class to use for iterators.
	 *
	 * @return	void
	 */
	public function __construct(iterable $input = array(), int $flags = 0, string $iteratorClass = ArrIter::class)
	{
		parent::__construct($input, $flags, $iteratorClass);
	}
	
	/**
	 * Process passed paths for configs.
	 *
	 * Any files in the path are potential config files and we determine what type by their extension:
	 *
	 *	-	.php indicates a PHP config file that will simply be 'required'.
	 *
	 * Subdirectories on each path can be named 'dev' or 'staging' and are only processed if the given mode
	 * is passed in.
	 *
	 * Paths are processed in the order they're passed in with later values overwriting older ones. However,
	 * if the mode is 'dev' or 'test' then these subdirectories are processed after the main directory.
	 *
	 * @param 	string 				$mode 		The run mode, which will be 'dev', 'test' or 'prod'.
	 * @param 	array|string|null	$paths		A single path string, an array of paths or null just to use the base app path.
	 *
	 * @return	void
	 */
	public function process(string $mode = 'prod', $paths = null)
	{
		// Turn the paths into something iterable.
		if (is_null($paths)) {
			$paths = array(APP_PATH . DIRECTORY_SEPARATOR . 'configs');
		} else if (!is_array($paths)) {
			$paths = array($paths);
		}	
		
		// Process each mode.
		foreach ($this->modeSubDirs[$mode] as $modeToProcess) {
			// Process each path for a particular mode.
			foreach ($paths as $path) {
				if ('.' == $modeToProcess) {
					$pathToProcess = $path;
				} else {
					$pathToProcess = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $modeToProcess;
				}
			}
			
			// Check if the directory exists and, if so, process it.
			if (file_exists($pathToProcess)) {
				$this->processPath($pathToProcess);
			}
		} 		
		
	}
	
	/**
	 * Process an individual path.
	 *
	 * @param 	string 			$path 		Path to be processed.
	 *
	 * @return 	void
	 */
	protected function processPath(string $path)
	{
		foreach (new DirIter($path) as $entry) {
			if ($entry->isDot() or $entry->isDir()) {
				continue;
			}
			
			if (in_array($entry->getExtension(), $this->configExts)) {
				switch ($entry->getExtension()) {
					case 'php':
						$reader = new PhpConfigReader();
				}
				
				$this->mergeReplaceRecursive($reader->read($entry->getPathname()));
			}
		}	
	}
}