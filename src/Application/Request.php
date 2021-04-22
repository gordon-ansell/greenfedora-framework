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

use GreenFedora\Application\RequestInterface;
use GreenFedora\Application\Message;
use GreenFedora\Application\Exception\OutOfBoundsException;

/**
 * Base request.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Request extends Message implements RequestInterface
{	
	/**
     * Arguments.
     * @var array
     */
    protected $args = [];

    /**
     * Constructor.
     * 
     * @return  void 
     */
    public function __construct()
    {
        $this->loadCmdLineArgs();
    }

    /**
	 * Load command line arguments.
	 *
	 * @return 	void
	 */
	protected function loadCmdLineArgs()
	{
		echo("HEREBASE");
        if (array_key_exists('argv', $_SERVER) and count($_SERVER['argv'])) {
            $this->args = $_SERVER['argv'];
        }
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
	 * Get the command line arguments.
	 *
	 * @return	array
	 */
	public function getArgs(): array
	{
		return $this->args;
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
}
