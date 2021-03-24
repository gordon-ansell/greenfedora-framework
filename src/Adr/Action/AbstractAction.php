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


/**
 * The base for all actions.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractAction 
{
	/**
	 * Parameters.
	 * @var Arr
	 */
	protected $params = null;

	/**
	 * Constructor.
	 *
	 * @param 	array	$params 	Parameters.
	 * @return	void
	 */
	public function __construct(array $params = [])
	{
		$this->params = new Arr($params);
	}

	/**
	 * Dispatcher.
	 */
	abstract public function dispatch();
	
}