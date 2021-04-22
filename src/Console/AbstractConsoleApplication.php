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
namespace GreenFedora\Console;

use GreenFedora\Application\AbstractApplication;
use GreenFedora\Console\ConsoleRequestInterface;
use GreenFedora\Console\ConsoleResponseInterface;
use GreenFedora\Logger\Logger;
use GreenFedora\Logger\Formatter\StdLogFormatter;
use GreenFedora\Logger\Writer\FileLogWriter;
use GreenFedora\Logger\Writer\ConsoleLogWriter;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerInterface;

/**
 * A console application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractConsoleApplication extends AbstractApplication implements ConsoleApplicationInterface, LoggerAwareInterface
{		
	use LoggerAwareTrait;

	/**
	 * Input.
	 * @var ConsoleRequestInterface
	 */
	protected $request = null;

	/**
	 * Output.
	 * @var ConsoleResponseInterface
	 */
	protected $response = null;

	/**
	 * Constructor.
	 *
	 * @param	string									$mode 			The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	ConsoleRequestInterface				    $request 		Input.
	 * @param	ConsoleResponseInterface	            $response 		Output.
	 * @param 	bool 									$autoLogger		Automatically set up logger.
	 * @param 	bool 									$autoConfig		Automatically set up and process configs.
	 * @param 	bool 									$autoLocale		Automatically set up and process locale.
	 *
	 * @return	void
	 */
	public function __construct(
		string $mode = 'prod', 
		?ConsoleRequestInterface $request = null, 
		?ConsoleResponseInterface $response = null,
		bool $autoLogger = true,
		bool $autoConfig = true,
		bool $autoLocale = true
		)
	{
		parent::__construct($mode, $request, $response, $autoConfig, $autoLocale);

		if ($autoLogger) {
			$this->configureLogger();
		}
	}

	/**
	 * Configure the logger.
	 * 
	 * @return 	void
	 */
	protected function configureLogger()
	{
		$formatter = new StdLogFormatter($this->get('config')->logger);
		$writers = array(
			new FileLogWriter($this->get('config')->logger, $formatter),
			new ConsoleLogWriter($this->get('config')->logger, $formatter));
		$this->addSingleton('logger', Logger::class, [$this->get('config')->logger, $writers]);	
	}

	/**
	 * Get the logger.
	 * 
	 * @return 	LoggerInterface
	 */
	public function getLogger(): LoggerInterface
	{
		return $this->get('logger');
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
		return $this->request->hasArg($name);
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
		return $this->request->getArg($name, $default);
	}	

	/**
	 * Get all arguments.
	 * 
	 * @return	array
	 */
	public function getArgs(): array
	{
		return $this->request->getArgs();
	}	

	/**
	 * Run.
	 *
	 * @return 	void
	 */
	protected function run()
	{
		$this->response->setOutput(0);
	}

}