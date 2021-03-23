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

use GreenFedora\Logger\Writer\Exception\RuntimeException;
use GreenFedora\Logger\Writer\AbstractLogWriter;
use GreenFedora\Logger\Formatter\LogFormatterInterface;
use GreenFedora\Logger\LogLevel;

/**
 * Write out to the console.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ConsoleLogWriter extends AbstractLogWriter implements LogWriterInterface
{
	/**
	 * Log level colours.
	 * @var array
	 */	
	const LOGLEVELCOLS = array(
		LogLevel::EMERGENCY	=>	"\e[1;31m",
		LogLevel::ALERT		=>	"\e[1;31m",
		LogLevel::CRITICAL	=>	"\e[1;31m",
		LogLevel::ERROR 	=>	"\e[0;31m",
		LogLevel::WARNING	=>	"\e[0;35m",
		LogLevel::NOTICE	=>	"",
		LogLevel::INFO		=>	"\e[0;32m",
		LogLevel::DEBUG 	=>	"\e[0;33m",			
		LogLevel::TRACE 	=>	"\e[0;36m",			
		LogLevel::TRACE2 	=>	"\e[0;36m",			
		LogLevel::TRACE3 	=>	"\e[0;37m",			
		LogLevel::TRACE4 	=>	"\e[0;37m",			
	);

	/**
	 * Set console colour back to normal.
	 * @var string
	 */	
    const ENDCOL = "\e[0m";

	/**
	 * Write a log message.
	 *
	 * @param 	string 				$msg 		Message to write.
	 * @param 	string 				$level 		Level of message.
	 * @param 	array 				$context	Message context.
	 *
	 * @return 	void
	 *
	 * @throws 	RuntimeException 	If we have any problems at all.
	 */
	public function write(string $msg, string $level, array $context = array())
	{
		echo self::LOGLEVELCOLS[$level] . $this->formatter->format($msg, $level, $context) . self::ENDCOL . PHP_EOL;
	}	
}