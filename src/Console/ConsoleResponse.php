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
namespace GreenFedora\Console;

use GreenFedora\Console\ConsoleResponseInterface;
use GreenFedora\Application\Response;

/**
 * Return code output.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleResponse extends Response implements ConsoleResponseInterface
{

	/**
	 * Output code.
	 * @var int
	 */
	protected $output = 0;
		
	/**
	 * Constructor.
	 *
	 * @param 	int 		$output 	The return code output.
     * @param   string|null $protocol   Protocol.
	 * @return	void
	 */
	public function __construct(int $output = 0, ?string $protocol = null)
	{
		parent::__construct($protocol);
		$this->output = $output;
	}
	
	/**
	 * Get the output.
	 *
	 * @return 	mixed
	 */
	public function getOutput()
	{
		return $this->output;
	}
	
	/**
	 * Set the output.
	 *
	 * @param 	mixed 	$output		Whatever it may be.
	 *
	 * @return 	void
	 */
	public function setOutput($output)
	{
		$this->output = $output;
	}
}
