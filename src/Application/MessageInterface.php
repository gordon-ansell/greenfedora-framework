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
namespace GreenFedora\Application;

/**
 * Base message interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface MessageInterface
{
    /**
     * Get the protocol version.
     * 
     * @return  string
     */
    public function getProtocolVersion(): string;

    /**
     * Create a clone with a new protocol version.
     * 
     * @param   string  $version    New version.
     * @return  MessageInterface
     */
    public function withProtocolVersion(string $version): MessageInterface;
}