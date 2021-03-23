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
use GreenFedora\Logger\LogLevel;

/**
 * For objects aware of the logger.
 *
 * Processes messages.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface LoggerAwareInterface
{	
	/**
	 * Get the logger.
	 *
	 * @return 	LoggerInterface
	 */
	public function getLogger() : LoggerInterface; 
	 	
	/**
	 * Get or set the log level.
	 *
	 * @param 	string|null		$level		Log level to set or null for a fetch.
	 *
	 * @return 	string 		The current level if we fetch, otherwise the old level.
	 *
	 * @throws	InvalidArgumentException	If the log level is not allowed.
	 */
	public function level(?string $level = null) : string;
	
	/**
	 * Get the message count for a level or levels.
	 *
	 * @param 	string		$level		Level or levels to get count for. Null returns all.			
	 *
	 * @return	int
	 */
	public function getMessageCount(string $level) : int;
	
	/**
	 * Get all the message counts.
	 *
	 * @return 	array
	 */
	public function getMessageCounts() : array;

    /**
     * Logs with an arbitrary level.
     *
     * @param 	mixed  	$level			Logging level.
     * @param 	string 	$message		Message string.
     * @param 	array  	$context		Additional context.
     * @return 	void
     */
    public function log($level, $message, array $context = array());
 
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = array());

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
    public function alert($message, array $context = array());

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
    public function critical($message, array $context = array());

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = array());

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
    public function warning($message, array $context = array());

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = array());

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
    public function info($message, array $context = array());

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = array());

    /**
     * Trace information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace($message, array $context = array());

    /**
     * Trace information (deep).
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace2($message, array $context = array());

    /**
     * Trace information (deeper).
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace3($message, array $context = array());

    /**
     * Trace information (deepest).
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function trace4($message, array $context = array());
}