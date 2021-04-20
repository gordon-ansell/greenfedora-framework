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
namespace GreenFedora\Logger\Writer;

use GreenFedora\Logger\Formatter\LogFormatterInterface;
use GreenFedora\Logger\LogLevel;
use GreenFedora\Arr\Arr;

use GreenFedora\Logger\Writer\Exception\InvalidArgumentException;

/**
 * Abstract log writer.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractLogWriter
{
	/**
	 * Configs from the logger.
	 * @var array
	 */
	protected $defaults = array(
		'level'			=>	LogLevel::NOTICE,
		'dtFormat'		=>	'Y-m-d H:i:s.v',
		'retainDays'	=>	30,				
	);	
	
	/**
	 * Configs.
	 * @var Arr
	 */
	protected $cfg = null;	
	
	/**
	 * Log formatter.
	 * @var LogFormatterInterface
	 */
	protected $formatter = null;	

	/**
	 * Constructor.
	 *
	 * @param 	iterable|null				$_loggerConfig 		Configs.
	 * @param 	LogFormatterInterface|null	$formatter			Log message formatter.
	 *
	 * @return	void
	 * 
	 * @throws  InvalidArgumentException
	 */
	public function __construct(?iterable $_loggerConfig = null, ?LogFormatterInterface $formatter = null)	
	{
		$this->cfg = new Arr($this->defaults);
        if (null === $_loggerConfig) {
			throw new InvalidArgumentException("Logger config is null.");
        }
		$this->cfg = $this->cfg->mergeReplaceRecursive($_loggerConfig);
        if (null === $formatter) {
			throw new InvalidArgumentException("Log formatter is null.");
        }
		$this->formatter = $formatter;
	}
}