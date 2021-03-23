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

/**
 * Log writer interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface LogWriterInterface
{
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
	public function write(string $msg, string $level, array $context = array());
}