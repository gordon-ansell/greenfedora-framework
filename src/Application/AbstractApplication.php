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
use GreenFedora\Application\ResponseInterface;
use GreenFedora\DI\Container;
use GreenFedora\Config\Config;
use GreenFedora\Locale\Locale;
use GreenFedora\Arr\Arr;

/**
 * The base for all applications.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractApplication extends Container 
{	
	/**
	 * Input.
	 * @var RequestInterface
	 */
	protected $request = null;

	/**
	 * Output.
	 * @var ResponseInterface
	 */
	protected $response = null;

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
	 * PHP environment variables.
	 * @var ArrInterface
	 */
	protected $env = null;
		
	/**
	 * Constructor.
	 *
	 * @param	string						$mode 			The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	RequestInterface			$request 		Input.
	 * @param	ResponseInterface			$response 		Output.
	 * @param 	bool 						$autoConfig		Automatically set up and process configs.
	 * @param 	bool 						$autoLocale		Automatically set up and process locale.
	 *
	 * @return	void
	 */
	public function __construct(
		string $mode = 'prod', 
		?RequestInterface $request = null, 
		?ResponseInterface $response = null,
		bool $autoConfig = true,
		bool $autoLocale = true
		)
	{
		self::setInstance($this);
		$this->mode = $mode;
		$this->request = $request;
		$this->response = $response;
		$this->env = new Arr($_ENV);
		if ($autoConfig) {
			$this->addSingletonAndCreate('config', Config::class)->process($this->mode);
		}
		if ($autoLocale) {
			$this->addSingletonAndCreate('locale', Locale::class, [$this->get('config')->locale]);
		}
	}

	/**
	 * Get an environment variable.
	 * 
	 * @param 	string|null 	$key 		Key to get.
	 * @param 	mixed 			$default	Default if not found.
	 * @return 	mixed
	 */
	public function env(?string $key = null, $default = null)
	{
        if (is_null($key)) {
            return $this->env;
        } else if ($this->env->has($key)) {
            return $this->env->get($key);
        }
        return $default;
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
	 * @return 	ResponseInterface
	 */
	protected function run(): ResponseInterface
	{
		return $this->dispatch();
	}
	
	/**
	 * Post-run.
	 *
	 * @return 	void
	 */
	protected function postRun(ResponseInterface $response) {}

	/**
	 * Dispatch.
	 *
	 * @return 	ResponseInterface
	 */
	protected function dispatch(): ResponseInterface
	{
		try {
			// Find a match for the route.
			$matched = $this->get('router')->match($this->request->getRoute());

			// Create the class.
			$class = $matched[0]->getNamespacedClass();
			$dispatchable = new $class($this, $this->request, $this->response, $matched[1]);

			// Dispatch the class.
			$response = $dispatchable->dispatch();
		} catch (\Exception $e) {
			$this->response->addException($e);
		}

		return $response;
	}

	/**
	 * The main run call.
	 *
	 * @return 	void
	 */
	final public function main(): ResponseInterface
	{
		$response = null;
		if ($this->preRun()) {
			$response = $this->run();
			$this->postRun($response);
		}
		return $response;
	}				
}