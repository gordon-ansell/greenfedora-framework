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

use GreenFedora\Console\ConsoleRequestInterface;
use GreenFedora\Application\Request;

/**
 * Command line options.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleRequest extends Request implements ConsoleRequestInterface
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
	 * Constructor.
	 *
     * @param   string      $mode   	Execution mode.
	 * @param 	string 		$opts 		Options.
	 * @param 	array 		$longopts 	Long options.
	 * @return	void
	 */
	public function __construct(string $mode = 'prod', string $opts = '', array $longOpts = [])
	{
		parent::__construct();
		$this->opts = $opts;
		$this->longOpts = $longOpts;
		print_r($this->longOpts);
		$this->processArgs($mode);
	}

    /**
	 * Load command line arguments.
	 *
	 * @return 	void
	 */
	protected function loadCmdLineArgs()
	{
		echo "HERE";
		$this->args = getopt($this->opts, $this->longOpts);	
		print_r($this->opts);
		print_r($this->longOpts);
		print_r($this->args);
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
	
}