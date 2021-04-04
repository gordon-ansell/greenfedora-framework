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
 * A console application.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractConsoleApplication extends AbstractApplication implements HttpApplicationInterface
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
	 * Dispatch.
	 *
	 * @return 	void
	 */
	protected function dispatch()
	{
	}

}