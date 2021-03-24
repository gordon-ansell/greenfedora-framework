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
use GreenFedora\Logger\Formatter\StdLogFormatter;
use GreenFedora\Logger\Writer\FileLogWriter;
use GreenFedora\Router\Router;
use GreenFedora\Router\RouterInterface;

/**
 * An HTTP application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class HttpApplication extends AbstractApplication implements ApplicationInterface
{
		
	/**
	 * Constructor.
	 *
	 * @param	string	$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 *
	 * @return	void
	 */
	public function __construct(string $mode = 'prod')
	{
		parent::__construct($mode);

		$this->processRouter();
	}
	
	/**
	 * Process the router.
	 *
	 * @return	void
     */
	protected function processRouter()
	{
		$this->createInstance(Router::class, $this->getConfig('routing'));
		$this->aliasInstance('router', Router::class);
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
	 * Get the router.
	 *
	 * @return	RouterInterface
	 */
	public function getRouter() : RouterInterface
	{
		return $this->getInstance('router');
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
	}

}