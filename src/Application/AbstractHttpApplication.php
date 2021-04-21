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
use GreenFedora\Application\HttpApplicationInterface;
use GreenFedora\Http\RequestInterface;
use GreenFedora\Http\ResponseInterface;
use GreenFedora\Logger\Logger;
use GreenFedora\Logger\Formatter\StdLogFormatter;
use GreenFedora\Logger\Writer\FileLogWriter;
use GreenFedora\Logger\Writer\ForcedConsoleLogWriter;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;

/**
 * An HTTP application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractHttpApplication extends AbstractApplication implements HttpApplicationInterface, LoggerAwareInterface
{
	use LoggerAwareTrait;

	/**
	 * Input.
	 * @var RequestInterface
	 */
	protected $input = null;

	/**
	 * Output.
	 * @var ResponseInterface
	 */
	protected $output = null;
		
	/**
	 * Constructor.
	 *
	 * @param	string						$mode 			The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	RequestInterface			$input 			Input.
	 * @param	ResponseInterface			$output 		Output.
	 * @param 	bool 						$autoLogger		Automatically set up logger.
	 * @param 	bool 						$autoConfig		Automatically set up and process configs.
	 * @param 	bool 						$autoLocale		Automatically set up and process locale.
	 *
	 * @return	void
	 */
	public function __construct(
		string $mode = 'prod', 
		?RequestInterface $input = null, 
		?ResponseInterface $output = null,
		bool $autoLogger = true,
		bool $autoConfig = true,
		bool $autoLocale = true
		)
	{
		parent::__construct($mode, $input, $output, $autoConfig, $autoLocale);

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
		$writers = [new FileLogWriter($this->get('config')->logger, $formatter)];
		if ('prod' != $this->mode) {
			$writers[] = new ForcedConsoleLogWriter($this->get('config')->logger, $formatter);		
		}
		$this->addSingleton('logger', Logger::class, [$this->get('config')->logger, $writers]);	
	}
	
	/**
	 * Dispatch.
	 *
	 * @return 	void
	 */
	protected function dispatch()
	{
		// Find a match for the route.
		$matched = $this->get('router')->match($this->input->getRoute());

		// Just some debugging.
		$this->trace4(sprintf("Matched namespaced class is: %s", $matched[0]->getNamespacedClass()));

		// Create the class.
		$class = $matched[0]->getNamespacedClass();
		$dispatchable = new $class($this, $this->input, $this->output, $matched[1]);

		// Dispatch the class.
		$dispatchable->dispatch();
	}

}