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

use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;
use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;


/**
 * The base for all actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractAction implements ContainerAwareInterface 
{
	use ContainerAwareTrait;

	/**
	 * Container.
	 * @var ContainerInterface
	 */
	protected $container = null;

	/**
	 * Input.
	 * @var ApplicationInputInterface
	 */
	protected $input = null;

	/**
	 * Output.
	 * @var ApplicationOutputInterface
	 */
	protected $output = null;

	/**
	 * Parameters.
	 * @var ArrInterface
	 */
	protected $params = null;

	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	ApplicationInputInterface	$input 		Input.
	 * @param 	ApplicationOutputInterface	$output 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, ApplicationInputInterface $input, ApplicationOutputInterface $output, array $params = [])
	{
		$this->container = $container;
		$this->input = $input;
		$this->output = $output;
		$this->params = new Arr($params);
	}

	/**
	 * Dispatcher.
	 */
	abstract public function dispatch();
	
}