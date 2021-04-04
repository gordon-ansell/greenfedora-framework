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
use GreenFedora\Router\RouterInterface;
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