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

use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;


/**
 * The base for all responders.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractResponder
{
	/**
	 * Constructor.
	 *
	 * @param 	ContainerInterface			$container	Dependency injection container.
	 * @param 	ApplicationOutputInterface	$output 	Output.
	 * @return	void
	 */
	public function __construct(ContainerInterface $container, ApplicationOutputInterface $output)
	{
	}
	
}