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
		LogLevel::EMERGENCY	=>	"\033[1;31m]",
		LogLevel::ALERT		=>	"\033[1;31m]",
		LogLevel::CRITICAL	=>	"\033[1;31m]",
		LogLevel::ERROR 	=>	"\033[0;31m]",
		LogLevel::WARNING	=>	"\033[0;35m]",
		LogLevel::NOTICE	=>	"",
		LogLevel::INFO		=>	"\033[0;32m]",
		LogLevel::DEBUG 	=>	"\033[0;33m]",			
		LogLevel::TRACE 	=>	"\033[0;36m]",			
		LogLevel::TRACE2 	=>	"\033[0;36m]",			
		LogLevel::TRACE3 	=>	"\033[0;37m]",			
		LogLevel::TRACE4 	=>	"\033[0;37m]",			
	);

	/**
	 * Set console colour back to normal.
	 * @var string
	 */	
    const ENDCOL = "\033[0m]";

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