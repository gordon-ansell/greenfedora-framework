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

class ConsoleApplication extends AbstractApplication
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
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 * @param	string						$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 *
	 * @return	void
	 */
	public function __construct(ApplicationInputInterface $input, ApplicationOutputInterface $output, string $mode = 'prod')
	{
		$this->args = getopt($this->opts, $this->longOpts);
		parent::__construct($input, $output, $mode);
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
		return $this->input->hasArg($name);
	}	
	
	/**
	 * Get an argument.
	 *
	 * For arguments that are just present but without a value (switches) we return true.
	 * Otherwise we return the value.
	 *
	 * @param 	string 		$name		Argument name.
	 * @param 	mixed 		$default 	Default if arg not found.
	 * @return	mixed
	 */
	public function getArg(string $name, $default = null)
	{
		return $this->input->getArg($name, $default);
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
	 * @return 	void
	 */
	protected function run()
	{
		$this->output->setOutput(0);
	}

}