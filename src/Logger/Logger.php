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
namespace GreenFedora\Logger;

use GreenFedora\Logger\LoggerInterface;
use GreenFedora\Logger\Writer\LogWriterInterface;
use GreenFedora\Logger\Exception\InvalidArgumentException;
use GreenFedora\Logger\LogLevel;
use GreenFedora\Arr\Arr;

/**
 * Logger.
 *
 * Processes messages.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Logger implements LoggerInterface
{
	/**
	 * Log level numbers.
	 * @var int[]
	 */	
	const LOGLEVELNO = array(
		LogLevel::EMERGENCY	=>	0,
		LogLevel::ALERT		=>	1,
		LogLevel::CRITICAL	=>	2,
		LogLevel::ERROR 	=>	3,
		LogLevel::WARNING	=>	4,
		LogLevel::NOTICE	=>	5,
		LogLevel::INFO		=>	6,
		LogLevel::DEBUG 	=>	7,	
		LogLevel::TRACE		=>	8,		
		LogLevel::TRACE2	=>	9,		
		LogLevel::TRACE3	=>	10,		
		LogLevel::TRACE4	=>	11,		
	);
	    
    /**
	 * Error counts.
	 * @var int[]
	 */
	protected $msgCounts = array(
		LogLevel::EMERGENCY	=>	0,
		LogLevel::ALERT		=>	0,
		LogLevel::CRITICAL	=>	0,
		LogLevel::ERROR 	=>	0,
		LogLevel::WARNING	=>	0,
		LogLevel::NOTICE	=>	0,
		LogLevel::INFO		=>	0,
		LogLevel::DEBUG 	=>	0,			
		LogLevel::TRACE 	=>	0,			
		LogLevel::TRACE2 	=>	0,			
		LogLevel::TRACE3 	=>	0,			
		LogLevel::TRACE4 	=>	0,			
	);  
	
	/**
	 * Log levels allowed.
	 * @var string[]
	 */
	protected $allowedLevels = array(
		LogLevel::EMERGENCY, LogLevel::ALERT, LogLevel::CRITICAL, LogLevel::ERROR, LogLevel::WARNING,
		LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG, LogLevel::TRACE, LogLevel::TRACE2, LogLevel::TRACE3, LogLevel::TRACE4	
	);	
	
	/**
	 * Log configs.
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
	 * Log writers.
	 * LogWriterInterface[]
	 */
	protected $writers = array();	
	
	/**
	 * Constructor.
	 *
	 * @param 	iterable	$cfg 		Log configs.
	 * @param 	iterable	$writers	Log writers.
	 *
	 * @return 	void
	 *
	 * @throws	InvalidArgumentException	If a writer is incorrect.
	 * @throws	InvalidArgumentException	If the log level is not allowed.
	 */
	public function __construct(iterable $cfg, iterable $writers = array())
	{
		$this->cfg = new Arr($this->defaults);
		$this->cfg = $this->cfg->mergeReplaceRecursive($cfg);
		$this->writers = $writers;
		foreach ($this->writers as $writer) {
			if (!$writer instanceof LogWriterInterface) {
				throw new InvalidArgumentException(sprintf("Log writers passed to the Logger must implement LogWriterInterface, you passed type '%s'", gettype($write)));
			}
		}
		if (!$this->checkLevel($this->cfg->level)) {
			throw new InvalidArgumentException(sprintf("Log level '%s' is not allowed.", $this->cfg->level));
		}
	}
	
	/**
	 * Check a log level is valid.
	 *
	 * @param 	string 		$level 	Level to check.
	 *
	 * @return 	bool
	 */
	protected function checkLevel(string $level) : bool
	{
		return in_array($level, $this->allowedLevels);
	}	
	
	/**
	 * Get or set the log level.
	 *
	 * @param 	string|null		$level		Log level to set or null for a fetch.
	 *
	 * @return 	string 		The current level if we fetch, otherwise the old level.
	 *
	 * @throws	InvalidArgumentException	If the log level is not allowed.
	 */
	public function level(?string $level = null) : string
	{
		if (is_null($level)) {
			return $this->cfg->level;
		}
		
		if (!$this->checkLevel($level)) {
			throw new InvalidArgumentException(sprintf("Log level '%s' is not allowed.", $level));
		}

		$oldLevel = $this->cfg->level;
		$this->cfg->level = $level;
		return $oldLevel;
	}
	
	/**
	 * Get the message count for a level or levels.
	 *
	 * @param 	string		$level		Level or levels to get count for. Null returns all.			
	 *
	 * @return	int
	 */
	public function getMessageCount(string $level) : int
	{
		if (false !== strpos($level, '|')) {
			$levels = explode('|', $level);
			$ret = 0;
			foreach ($levels as $test) {
				if (array_key_exists(trim($test), $this->msgCounts)) {
					$ret += $this->msgCounts[trim($test)];
				}
			}			
			return $ret;
		} else {
			return $this->msgCounts[$level];
		}
	}
	
	/**
	 * Get all the message counts.
	 *
	 * @return 	int[]
	 */
	public function getMessageCounts() : array
	{
		return $this->msgCounts;
	}	
	 
    /**
     * Logs with an arbitrary level.
     *
     * @param 	mixed  	$level			Logging level.
     * @param 	string 	$message		Message string.
     * @param 	array  	$context		Additional context.
     *
     * @return 	void
     */
    public function log($level, $message, array $context = array())
    {
		if (self::LOGLEVELNO[$level] <= self::LOGLEVELNO[$this->cfg->level]) {
			foreach ($this->writers as $writer) {
				$writer->write($message, $level, $context);
			}
			$this->msgCounts[$level]++;
		}    
    }
 
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }

    /**
     * Trace information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace($message, array $context = array())
    {
        $this->log(LogLevel::TRACE, $message, $context);
    }

    /**
     * Trace information (deep).
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace2($message, array $context = array())
    {
        $this->log(LogLevel::TRACE2, $message, $context);
    }

    /**
     * Trace information (deeper).
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace3($message, array $context = array())
    {
        $this->log(LogLevel::TRACE3, $message, $context);
    }

    /**
     * Trace information (deepest).
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace4($message, array $context = array())
    {
        $this->log(LogLevel::TRACE4, $message, $context);
    }
}