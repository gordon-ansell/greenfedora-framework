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
namespace GreenFedora\Adr\Action;

use GreenFedora\Http\RequestInterface;
use GreenFedora\Http\ResponseInterface;
use GreenFedora\DI\ContainerInterface;
use GreenFedora\DI\ContainerAwareInterface;
use GreenFedora\DI\ContainerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerInterface;


/**
 * The base for all actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractAction implements ContainerAwareInterface, LoggerAwareInterface 
{
	use ContainerAwareTrait;
	use LoggerAwareTrait;

	/**
	 * Container.
	 * @var ContainerInterface
	 */
	protected $container = null;

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
	 * Parameters.
	 * @var array
	 */
	protected $params = [];

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	RequestInterface			$input 		Input.
	 * @param 	ResponseInterface			$output 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, RequestInterface $input, 
		ResponseInterface $output, array $params = [])
	{
		$this->container = $container;
		$this->input = $input;
		$this->output = $output;
		$this->params = $params;
	}

	/**
	 * Get the logger.
	 *
	 * @return	LoggerInterface
	 */
	public function getLogger() : LoggerInterface
	{
		return $this->get('logger');
	}			

	/**
	 * Dispatcher.
	 */
	abstract public function dispatch();
	
}