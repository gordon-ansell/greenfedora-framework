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

use GreenFedora\Application\ApplicationInterface;
use GreenFedora\Application\Exception\InvalidArgumentException;
use GreenFedora\Application\Exception\OutOfBoundsException;
use GreenFedora\Application\Input\ApplicationInputInterface;
use GreenFedora\Application\Output\ApplicationOutputInterface;
use GreenFedora\Logger\LoggerInterface;
use GreenFedora\Logger\LoggerAwareInterface;
use GreenFedora\Logger\LoggerAwareTrait;
use GreenFedora\Logger\Formatter\StdLogFormatter;
use GreenFedora\Logger\Writer\FileLogWriter;
use GreenFedora\Logger\Writer\ConsoleLogWriter;
use GreenFedora\DependencyInjection\ContainerInterface;
use GreenFedora\DependencyInjection\ContainerAwareTrait;
use GreenFedora\DependencyInjection\ContainerAwareInterface;
use GreenFedora\Locale\LocaleInterface;
use GreenFedora\Locale\LocaleAwareInterface;
use GreenFedora\Locale\LocaleAwareTrait;
use GreenFedora\Lang\LangInterface;
use GreenFedora\Lang\LangAwareTrait;
use GreenFedora\Lang\LangAwareInterface;
use GreenFedora\Inflector\Inflector;
use GreenFedora\Inflector\InflectorInterface;
use GreenFedora\Inflector\InflectorAwareInterface;
use GreenFedora\Inflector\InflectorAwareTrait;

/**
 * The base for all applications.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractApplication implements ContainerAwareInterface, LoggerAwareInterface, 
	LocaleAwareInterface, LangAwareInterface, InflectorAwareInterface
{
	use ContainerAwareTrait;
	use LoggerAwareTrait;
	use LocaleAwareTrait;
	use LangAwareTrait;
	use InflectorAwareTrait;
	
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
	 * @param 	ContainerInterface			$container	DI container.
	 * @param	string						$mode 		The mode we're running in: 'dev', 'test' or 'prod'.
	 * @param	ApplicationInputInterface	$input 		Input.
	 * @param	ApplicationOutputInterface	$output 	Output.
	 *
	 * @return	void
	 */
	public function __construct(
		ContainerInterface $container, 
		string $mode = 'prod', 
		?ApplicationInputInterface $input = null, 
		?ApplicationOutputInterface $output = null
		)
	{
		$this->container = $container;
		$this->input = $input ?: $this->container->get('input');
		$this->output = $output ?: $this->container->get('output');
		$this->mode = $mode;

		//$this->processConfig();	
		//$this->processLocale();
		//$this->processLogger();
		//$this->processLang();
		$this->processInflector();
	}
		
	/**
	 * Process the config files.
	 *
	 * @return	void
     */
	/*
	protected function processConfig()
	{
		$this->createInstance(Config::class)->process($this->mode);
		$this->aliasInstance('config', Config::class);
	}
	*/
	
	/**
	 * Process the locale.
	 *
	 * @return	void
	 */
	/*
	protected function processLocale()
	{
		$this->createInstance(Locale::class, $this->getConfig('locale'));
		$this->aliasInstance('locale', Locale::class);
	}
	*/	
	
	/**
	 * Get the log writers.
	 *
	 * @return array
	 */
	/*
	protected function getLogWriters() : array
	{
		$formatter = $this->createInstance(StdLogFormatter::class, $this->getConfig('logger'));
		return array($this->createInstance(FileLogWriter::class, $this->getConfig('logger'), $formatter));		
	}
	*/	
	
	/**
	 * Process the logger.
	 *
	 * @return	void
     */
	/*
	protected function processLogger()
	{
		$this->createInstance(Logger::class, $this->getConfig('logger'), $this->getLogWriters());
		$this->aliasInstance('logger', Logger::class);
		if (!is_null($this->newLogLevel)) {
			$this->getInstance('logger')->level($this->newLogLevel);
		}
		$this->trace4('Logger initialised.');
	}
	*/
	
	/**
	 * Process languages.
	 *
	 * @return 	void
	 */
	/*
	protected function processLang() 
	{
		$this->createInstance(Lang::class, $this->getLocale()->getLangCode());
		$this->aliasInstance('lang', Lang::class);
		$this->trace4('Language processing initialised.');
	}
	*/	

	/**
	 * Process inflector.
	 *
	 * @return 	void
	 */
	protected function processInflector() 
	{
		$this->createInstance(Inflector::class);
		$this->aliasInstance('inflector', Inflector::class);
		$this->trace4('Inflector initialised.');
	}	

	/**
	 * See if we have a config key.
	 *
	 * @param	string			$key		Key to check.
	 * 
	 * @return 	bool
	 */
	public function hasConfig(string $key) : bool
	{
		return $this->getInstance('config')->has($key);
	}

	/**
	 * Get the config.
	 *
	 * @param	string|null		$key		Key to get or null to get them all.
	 * @param 	mixed 			$default	Default value to return if key not found.
	 * 
	 * @return 	mixed
	 */
	public function getConfig(?string $key = null, $default = array())
	{
		$instance = $this->getInstance('config');
		
		if (is_null($key)) {
			return $instance;
		}
		
		if ($instance->has($key)) {
			return $instance->$key;
		}
		return $default;
	}

	/**
	 * Get the locale.
	 *
	 * @return	LocaleInterface
	 */
	public function getLocale() : LocaleInterface
	{
		return $this->getInstance('locale');
	}			
	
	/**
	 * Get the logger.
	 *
	 * @return	LoggerInterface
	 */
	public function getLogger() : LoggerInterface
	{
		return $this->getInstance('logger');
	}			

	/**
	 * Get the language processor.
	 *
	 * @return	LangInterface
	 */
	public function getLang() : LangInterface
	{
		return $this->getInstance('lang');
	}
	
	/**
	 * Get the inflector.
	 *
	 * @return	InflectorInterface
	 */
	public function getInflector() : InflectorInterface
	{
		return $this->getInstance('inflector');
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
	public function main()
	{
		$this->trace4('Main started.');
		if ($this->preRun()) {
			$this->run();
			$this->postRun();
		}
		$this->trace4('Main ended.');
	}				
}