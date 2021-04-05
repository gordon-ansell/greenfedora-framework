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
namespace GreenFedora\IP;

/**
 * IP address class interface.
 */
interface IPAddressInterface
{
    /**
    * Get the client's IP address.
    * 
    * @param    bool    $allowProxy     Allow proxy IP addresses.
    * @return   string                  Client IP address.
    */
    public function getClientIp(bool $allowProxy = true) : string;
    
}
