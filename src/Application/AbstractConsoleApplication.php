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
use GreenFedora\Console\CommandLineOptsInterface;
use GreenFedora\Application\Output\ReturnCodeApplicationOutputInterface;
use GreenFedora\DI\ContainerInterface;

/**
 * A console application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractConsoleApplication extends AbstractApplication implements ConsoleApplicationInterface
{		
	/**
	 * Input.
	 * @var CommandLineOptsInterface
	 */
	protected $input = null;

	/**
	 * Output.
	 * @var ReturnCodeApplicationOutputInterface
	 */
	protected $output = null;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface						$container	DI container.
	 * @param	string									$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	CommandLineOptsInterface				$input 		Input.
	 * @param	ReturnCodeApplicationOutputInterface	$output 	Output.
	 *
	 * @return	void
	 */
	public function __construct(
		ContainerInterface $container, 
		string $mode = 'prod', 
		?CommandLineOptsInterface $input = null, 
		?ReturnCodeApplicationOutputInterface $output = null
		)
	{
		parent::__construct($container, $mode, $input, $output);
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
	 * Get all arguments.
	 * 
	 * @return	array
	 */
	public function getArgs(): array
	{
		return $this->input->getArgs();
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