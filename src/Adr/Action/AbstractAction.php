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

/**
 * The base for all actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractAction 
{
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
	 * @param 	ApplicationInputInterface	$input 		Input.
	 * @param 	ApplicationOutputInterface	$output 	Output.
	 * @param 	array						$params 	Parameters.
	 * @return	void
	 */
	public function __construct(ApplicationInputInterface $input, ApplicationOutputInterface $output, array $params = [])
	{
		$this->input = $input;
		$this->output = $output;
		$this->params = new Arr($params);
	}

	/**
	 * Dispatcher.
	 */
	abstract public function dispatch();
	
}