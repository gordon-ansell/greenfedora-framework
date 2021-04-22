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

use GreenFedora\Application\MessageInterface;

/**
 * Base message.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Message implements MessageInterface
{	
    /**
     * Protocol.
     * @var string
     */
    protected $protocol = null;

    /**
     * Constructor.
     * 
     * @param   string|null     $protocol    Protocol.
     * @return  void 
     */
    public function __construct(?string $protocol = null)
    {
        if (is_null($protocol) and (array_key_exists('SERVER_PROTOCOL', $_SERVER))) {
            $this->protocol = $_SERVER['SERVER_PROTOCOL'];
        } else {
            $this->protocol = $protocol;
        }
    }

    /**
     * Split a protocol.
     * 
     * @param   string          $protocol   Protocol to split.
     * @param   string          $by         What to split by.
     * @return  array
     */
    protected function splitProtocol(string $protocol, string $by = '/'): array
    {
        return explode($by, $protocol);
    }

    /**
     * Set the protocol.
     * 
     * @param   string           $protocol   Protocol.
     * @return  MessageInterface
     */
    public function setProtocol(string $protocol): MessageInterface
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * Get the protocol version.
     * 
     * @return  string
     */
    public function getProtocolVersion(): string
    {
        $ret = null;
        if (!is_null($this->protocol)) {
            list($start, $version) = $this->splitProtocol($this->protocol);
            $ret = $version;
        }
        return $ret;
    }

    /**
     * Create a clone with a new protocol version.
     * 
     * @param   string  $newVersion    New version.
     * @return  MessageInterface
     */
    public function withProtocolVersion(string $newVersion): MessageInterface
    {
        list($start, $version) = $this->splitProtocol($this->protocol);
        $clone = clone($this);
        $this->setProtocol($start . '/' . $newVersion);
        return $clone;
    }
}
