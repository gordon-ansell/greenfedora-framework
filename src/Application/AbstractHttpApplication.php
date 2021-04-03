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
use GreenFedora\Logger\Writer\ForcedConsoleLogWriter;
use GreenFedora\Router\Router;
use GreenFedora\Router\RouterInterface;
use GreenFedora\Template\PlatesTemplate;
use GreenFedora\Template\SmartyTemplate;
use GreenFedora\Template\TemplateInterface;
use GreenFedora\DependencyInjection\ContainerInterface;

use GreenFedora\Application\Exception\InvalidArgumentException;

/**
 * An HTTP application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractHttpApplication extends AbstractApplication implements HttpApplicationInterface
{
		
	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	DI container.
	 * @param	string						$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 *
	 * @return	void
	 */
	public function __construct(
		ContainerInterface $container, 
		string $mode = 'prod', 
		?ApplicationInputInterface $input = null, 
		?ApplicationOutputInterface $output = null
		)
	{
		parent::__construct($container, $mode, $input, $output);
		$this->processRouter();
		$this->processTemplate();
	}
	
	/**
	 * Process the router.
	 *
	 * @return	void
     */
	protected function processRouter()
	{
		$this->createInstance(Router::class, $this->getConfig('routing'), $this->container);
		$this->aliasInstance('router', Router::class);
		$this->trace4('Router initialised.');
	}

	/**
	 * Process the template processor.
	 *
	 * @return	void
     */
	protected function processTemplate()
	{
		$tplType = $this->getConfig('templateType');
		if ('plates' == $tplType) {
			$this->createInstance(PlatesTemplate::class, $this->getConfig('template'), $this->container);
			$this->aliasInstance('template', PlatesTemplate::class);
		} else if ('smarty' == $tplType) {
			$this->createInstance(SmartyTemplate::class, $this->getConfig('template'), $this->container);
			$this->aliasInstance('template', SmartyTemplate::class);
		} else {
			throw new InvalidArgumentException(sprintf("No template support for type '%s'", $tplType));
		}
		$this->trace4('Template engine initialised.');
	}

	/**
	 * Get the log writers.
	 *
	 * @return array
	 */
	protected function getLogWriters() : array
	{
		$formatter = $this->createInstance(StdLogFormatter::class, $this->getConfig('logger'));
		if ('dev' == $this->mode) {
			return array(
				$this->createInstance(FileLogWriter::class, $this->getConfig('logger'), $formatter),
				$this->createInstance(ForcedConsoleLogWriter::class, $this->getConfig('logger'), $formatter)
			);		
		} else {
			return array(
				$this->createInstance(FileLogWriter::class, $this->getConfig('logger'), $formatter)
			);		
		}
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
	 * Get the template engine.
	 *
	 * @return	TemplateInterface
	 */
	public function getTemplate() : TemplateInterface
	{
		return $this->getInstance('template');
	}			

	/**
	 * Dispatch.
	 *
	 * @return 	void
	 */
	protected function dispatch()
	{
		// Find a match for the route.
		$matched = $this->getRouter()->match($this->input->getRoute());

		// Just some debugging.
		$this->trace4(sprintf("Matched namespaced class is: %s", $matched[0]->getNamespacedClass()));

		// Create the class.
		$class = $matched[0]->getNamespacedClass();
		$dispatchable = new $class($this->container, $this->input, $this->output, $matched[1]);

		// Dispatch the class.
		$dispatchable->dispatch();
	}

}