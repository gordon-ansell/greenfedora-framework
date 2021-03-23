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
namespace GreenFedora\Application\Input;

use GreenFedora\Application\Input\ApplicationInputInterface;

/**
 * Simple array input.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ArrayApplicationInput implements ApplicationInputInterface
{
	/**
	 * Input array.
	 * @var array
	 */
	protected $input = array();
		
	/**
	 * Constructor.
	 *
	 * @param 	array 	$input 		The array input.
	 *
	 * @return 	void
	 */
	public function __construct(array $input = array())
	{
		$this->input = $input;	
	}	
	
	/**
	 * Get the input.
	 *
	 * @return 	mixed
	 */
	public function getInput()
	{
		return $this->input;
	}
	
}
