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

use GreenFedora\Console\Exception\OutOfBoundsException;
use GreenFedora\Application\Input\ApplicationInputInterface;


/**
 * Command line options.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class CommandLineOpts implements ApplicationInputInterface
{
    /**
     * Arguments.
     * @var array
     */
    protected $args = [];
		
	/**
	 * Constructor.
	 *
     * @param   string      $mode   Execution mode.
	 * @return	void
	 */
	public function __construct(string $mode = 'prod')
	{
		$this->args = getopt($this->opts, $this->longOpts);
		$this->processArgs($mode);
	}

    /**
     * Post process args (possibly based on mode).
     * 
     * @param   string      $mode       Mode.
     * @return  mixed
     */
    protected function processArgs($mode)
    {
    }

	/**
	 * See if we have a particular argument.
	 *
	 * @param 	string 		$name 		Argument name.
	 *
	 * @return 	bool
	 */
	public function hasArg(string $name) : bool
	{
		return array_key_exists($name, $this->args);
	}	
	
	/**
	 * Get an argument.
	 *
	 * For arguments that are just present but without a value (switches) we return true.
	 * Otherwise we return the value.
	 *
	 * @param 	string 		$name		Argument name.
	 * @return	mixed
	 *
	 * @throws 	OutOfBoundsException 	If argument not found.
	 */
	public function getArg(string $name)
	{
		if ($this->hasArg($name)) {
			if (false === $this->args[$name]) {
				return true;
			} else {
				return $this->args[$name];
			}	
		}
		throw new OutOfBoundsException(sprintf("No command line argument named '%s' found", $name));
	}	

}