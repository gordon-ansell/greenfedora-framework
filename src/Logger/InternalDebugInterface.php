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
 * For internal debugging.
 *
 * Processes messages.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface InternalDebugInterface
{	
    /**
     * See if we have debugging.
     * 
     * @return  bool
     */
    public function hasDebugging(): bool;

    /**
     * Get the debug messages.
     * 
     * @return  array   
     */
    public function getDebugging(): array;

    /**
     * Output the debugging.
     * 
     * @param   LoggerInterface     $logger     Logger.
     * @return  void
     */
    public function outputDebugging(LoggerInterface $logger);
}