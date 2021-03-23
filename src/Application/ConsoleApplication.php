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
namespace GreenFedora\Application;

use GreenFedora\Application\AbstractApplication;
use GreenFedora\Application\ApplicationInterface;
use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\Application\Exception\OutOfBoundsException;
use GreenFedora\Logger\Formatter\StdLogFormatter;
use GreenFedora\Logger\Writer\FileLogWriter;
use GreenFedora\Logger\Writer\ConsoleLogWriter;

/**
 * A command line console application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleApplication extends AbstractApplication implements ApplicationInterface
{
	/**
	 * Options.
	 * @var string
	 */
	protected $opts = '';
	
	/**
	 * Long options.
	 * @var array
	 */
	protected $longOpts = array();	
	 	
	/**
	 * Command line arguments.
	 * @var array
	 */
	protected $args = array();
		
	/**
	 * Constructor.
	 *
	 * @param	string	$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 *
	 * @return	void
	 */
	public function __construct(string $mode = 'prod')
	{
		$this->args = getopt($this->opts, $this->longOpts);
		$mode = $this->processArgs($mode);
		parent::__construct($mode);
	}
	
	/**
	 * Process arguments.
	 *
	 * @param 	string 		$mode		Mode.
	 *
	 * @return 	string	Possibly updated mode.
	 */
	protected function processArgs(string $mode) : string
	{
		return $mode;
	}	
	
	/**
	 * See if we have a particular argument.
	 *
	 * @param 	string 		$name 		Argument name.
	 *
	 * @return 	bool
	 */
	public function hasArg(string $name) : bool
	{
		return array_key_exists($name, $this->args);
	}	
	
	/**
	 * Get an argument.
	 *
	 * For arguments that are just present but without a value (switches) we retuen true.
	 * Otherwise we return the value.
	 *
	 * @param 	string 		$name		Argument name.
	 *
	 * @return	mixed
	 *
	 * @throws 	OutOfBoundsException 	If argument not found.
	 */
	public function getArg(string $name)
	{
		if ($this->hasArg($name)) {
			if (false === $this->args[$name]) {
				return true;
			} else {
				return $this->args[$name];
			}	
		}
		throw new OutOfBoundsException(sprintf("No command line argument named '%s' found", $name));
	}	

	/**
	 * Get the log writers.
	 *
	 * @return array
	 */
	protected function getLogWriters() : array
	{
		$formatter = $this->createInstance(StdLogFormatter::class, $this->getConfig('logger'));
		return array(
			$this->createInstance(FileLogWriter::class, $this->getConfig('logger'), $formatter),
			$this->createInstance(ConsoleLogWriter::class, $this->getConfig('logger'), $formatter)
		);		
	}	

	/**
	 * Run.
	 *
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 *
	 * @return 	void
	 */
	protected function run(ApplicationInputInterface $input, ApplicationOutputInterface $output)
	{
		$output->setOutput(0);
	}

}