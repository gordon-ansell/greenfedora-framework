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

trait InternalDebugTrait
{	
    /**
     * Debug messages.
     */
    protected $debugMsgs = [];

    /**
     * Add a debug message.
     * 
     * @param   string  $msg    Message to add.
     * @param   string  $level  Message level.
     * @return  void 
     */
    protected function debug(string $msg, $level = 'debug')
    {
        $this->debugMsgs[] = array($level, $msg);
    }

    /**
     * Get the debug messages.
     * 
     * @return  array   
     */
    public function getDebugging(): array
    {
        return $this->debugMsgs;
    }

    /**
     * Output the debugging.
     * 
     * @param   LoggerInterface     $logger     Logger.
     * @return  void
     */
    public function outputDebugging(LoggerInterface $logger)
    {
        foreach ($this->debugMsgs as $msg) {
            $logger->log($msg[0], $msg[1]);
        }
    }
}