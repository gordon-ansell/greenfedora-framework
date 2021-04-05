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
	 * Options.
	 * @var string
	 */
	protected $opts = '';
	
	/**
	 * Long options.
	 * @var array
	 */
	protected $longOpts = array();	

	/**
     * Arguments.
     * @var array
     */
    protected $args = [];
		
	/**
	 * Constructor.
	 *
     * @param   string      $mode   	Execution mode.
	 * @param 	string 		$opts 		Options.
	 * @param 	array 		$longopts 	Long options.
	 * @return	void
	 */
	public function __construct(string $mode = 'prod', string $opts = '', array $longOpts = [])
	{
		$this->opts = $opts;
		$this->longOpts = $longOpts;
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
	 * @param 	mixed 		$default 	Default if arg not found.
	 * @return	mixed
	 *
	 * @throws 	OutOfBoundsException 	If argument not found and no default.
	 */
	public function getArg(string $name, $default = null)
	{
		if ($this->hasArg($name)) {
			if (false === $this->args[$name]) {
				return true;
			} else {
				return $this->args[$name];
			}	
		} else if (null !== $default) {
			return $default;
		}
		throw new OutOfBoundsException(sprintf("No command line argument named '%s' found and no default specified", $name));
	}	

	/**
	 * Get all arguments.
	 * 
	 * @return	array
	 */
	public function getArgs(): array
	{
		return $this->args;
	}	
}