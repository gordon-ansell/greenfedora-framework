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
namespace GreenFedora\Logger\Formatter;

/**
 * Log formatter interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface LogFormatterInterface
{
	/**
	 * Format the message.
	 *
	 * @param 	string 				$msg 		Message to format.
	 * @param 	string 				$level 		Level of message.
	 * @param 	array 				$context	Message context.
	 *
	 * @return 	void
	 */	
	public function format(string $msg, string $level, array $context = array()) : string;
}