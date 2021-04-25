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
namespace GreenFedora\Payload;

use GreenFedora\Arr\ArrInterface;

/**
 * Payload interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface PayloadInterface extends ArrInterface
{
    /**
     * Get the payload status.
     * 
     * @return  mixed  
     */
    public function getStatus();

    /**
     * Set the payload status.
     * 
     * @param   mixed  $status     Status to set.
     * @return  void
     */
    public function setStatus($status);

    /**
     * Set some values from an array.
     * 
     * @param   array           $source     Source array.
     * @param   array|null      $defaults   Defaults array.
     * @param   iterable|null   $keysFrom   Keys to set.
     * @return  PayloadInterface
     */
    public function setFrom(array $source, ?array $defaults = null, ?iterable $keysFrom = null): PayloadInterface;
}