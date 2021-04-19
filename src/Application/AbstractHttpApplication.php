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
use GreenFedora\Router\RouterInterface;
use GreenFedora\Template\TemplateInterface;
use GreenFedora\DependencyInjection\ContainerInterface;

/**
 * An HTTP application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractHttpApplication extends AbstractApplication implements HttpApplicationInterface
{
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
	 * @param 	ContainerInterface			$container	DI container.
	 * @param	string						$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	RequestInterface			$input 		Input.
	 * @param	ResponseInterface			$output 	Output.
	 *
	 * @return	void
	 */
	public function __construct(
		ContainerInterface $container, 
		string $mode = 'prod', 
		?RequestInterface $input = null, 
		?ResponseInterface $output = null
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