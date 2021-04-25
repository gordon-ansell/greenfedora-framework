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
use GreenFedora\Payload\Payload;
use GreenFedora\Payload\PayloadInterface;


/**
 * The base for all responders.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractResponder implements ContainerAwareInterface
{
	use ContainerAwareTrait;
	
	/**
	 * Output.
	 * @var RequestInterface
	 */
	protected $request = null;

	/**
	 * Output.
	 * @var ResponseInterface
	 */
	protected $response = null;

	/**
	 * Responder data.
	 * @var PayloadInterface
	 */
	protected $payload = null;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	RequestInterface			$request 	Input.
	 * @param 	ResponseInterface			$response 	Output.
	 * @param 	PayloadInterface 			$payload 	Payload of data.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, RequestInterface $request, 
		ResponseInterface $response, PayloadInterface $payload = null)
	{
		$this->container = $container;
		$this->request = $request;
		$this->response = $response;
		if (null == $payload) {
			$this->payload = new Payload();
		} else {
			$this->payload = $payload;
		}
		$this->addDefaultdata();
	}

	/**
	 * Add some default data.
	 * 
	 * @return 	void
	 */
	protected function addDefaultData()
	{
		$cfg = $this->get('config');

		if (!$cfg->has('locations') or !$cfg->get('locations')->has('webroot')) {
			$this->payload->set('webroot', '/');
		} else {
			$this->payload->set('webroot', $cfg->get('locations')->get('webroot'));
		}

		if (!$cfg->has('locations') or !$cfg->get('locations')->has('assets')) {
			$this->payload->set('assets', '/');
		} else {
			$this->payload->set('assets', $cfg->get('locations')->get('assets'));
		}
	}

	/**
	 * Dispatcher.
	 */
	abstract public function dispatch();
}