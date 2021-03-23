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

/**
 * Interface for saving log messages.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface LogMessageSaverInterface
{
	/**
	 * Save a log message.
	 *
	 * @param 	string 		$level 		Level.
	 * @param 	string 		$message	Message.
	 * @param 	array 		$context 	Context.
	 *
	 * @return	void
	 */
	public function lsave(string $level, string $message, array $context = array());
	
	/**
	 * Process the saved log messages.
	 *
	 * @param 	LoggerInterface 	$logger 	Logger.
	 *
	 * @return 	void
	 */
	public function processSavedLogMessages(LoggerInterface $logger);
}