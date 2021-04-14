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
namespace GreenFedora\Adr\Responder;

use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;
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
	 * @var ApplicationInputInterface
	 */
	protected $input = null;

	/**
	 * Output.
	 * @var ApplicationOutputInterface
	 */
	protected $output = null;

	/**
	 * Responder data.
	 * @var PayloadInterface
	 */
	protected $payload = null;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	ApplicationInputInterface	$input 		Input.
	 * @param 	ApplicationOutputInterface	$output 	Output.
	 * @param 	PayloadInterface 			$payload 	Payload of data.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, ApplicationInputInterface $input, 
		ApplicationOutputInterface $output, PayloadInterface $payload = null)
	{
		$this->container = $container;
		$this->input = $input;
		$this->output = $output;
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
		$cfg = $this->getInstance('config');

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