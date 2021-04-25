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
namespace GreenFedora\Application\Adr;

use GreenFedora\Application\RequestInterface;
use GreenFedora\Application\ResponseInterface;
use GreenFedora\DI\ContainerInterface;
use GreenFedora\DI\ContainerAwareInterface;
use GreenFedora\DI\ContainerAwareTrait;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\LoggerInterface;
use GreenFedora\Payload\Payload;
use GreenFedora\Payload\PayloadInterface;


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
	protected $request = null;

	/**
	 * Output.
	 * @var ResponseInterface
	 */
	protected $response = null;

	/**
	 * Parameters.
	 * @var array
	 */
	protected $params = [];

	/**
	 * Payload.
	 * @var PayloadInterface
	 */
	protected $payload = null;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	RequestInterface			$request 	Input.
	 * @param 	ResponseInterface			$response 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, RequestInterface $request, 
		ResponseInterface $response, array $params = [])
	{
		$this->container = $container;
		$this->request = $request;
		$this->response = $response;
		$this->params = $params;
		$this->payload = new Payload();
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