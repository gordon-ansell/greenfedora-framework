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

use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\DI\Container;

/**
 * The base for all applications.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractApplication extends Container 
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
	 * The mode we're running in.
	 * @var string
	 */	
	protected $mode = 'prod';
	
	/**
	 * Message level for logger. Null means it will remain unchanged.
	 * We just use this if we want to change the log level between start-up
	 * and the creation of the logger.
	 * @var string|null
	 */
	protected $newLogLevel = null;	
		
	/**
	 * Constructor.
	 *
	 * @param	string						$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 *
	 * @return	void
	 */
	public function __construct(
		string $mode = 'prod', 
		?ApplicationInputInterface $input = null, 
		?ApplicationOutputInterface $output = null
		)
	{
		$this->input = $input ?: $this->container->get('input');
		$this->output = $output ?: $this->container->get('output');
		$this->mode = $mode;
		self::setInstance($this);
	}
		
	/**
	 * Pre-run.
	 *
	 * @return 	bool
	 */
	protected function preRun() : bool
	{
		return true;
	}

	/**
	 * Abstract run.
	 *
	 * @return 	void
	 */
	abstract protected function run();
	
	/**
	 * Post-run.
	 *
	 * @return 	void
	 */
	protected function postRun() {}

	/**
	 * The main run call.
	 *
	 * @return 	void
	 */
	final public function main()
	{
		$this->trace4('Main started.');
		if ($this->preRun()) {
			$this->run();
			$this->postRun();
		}
		$this->trace4('Main ended.');
	}				
}