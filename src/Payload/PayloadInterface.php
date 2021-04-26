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
     * Some default statuses.
     */
    const FORM_SUBMITTED = "FORM_SUBMITTED";

    /**
     * Get the payload status.
     * 
     * @return  mixed  
     */
    public function getStatus();

    /**
     * Get the payload status info.
     * 
     * @return  mixed  
     */
    public function getStatusInfo();

    /**
     * Set the form submitted.
     * 
     * @param   string  $form   Form name.
     * @return  void
     */
    public function setFormSubmitted(string $name);

    /**
     * Check if a form submitted.
     * 
     * @param   string  $form   Form name.
     * @return  bool
     */
    public function isFormSubmitted(string $name): bool;

    /**
     * Set the payload status.
     * 
     * @param   mixed  $status      Status to set.
     * @param   mixed  $statusInfo  Additional status info.
     * @return  void
     */
    public function setStatus($status, $statusInfo = null);

    /**
     * Set the payload status to null.
     * 
     * @return  void
     */
    public function setStatusNull();

    /**
     * Set the payload status info.
     * 
     * @param   mixed  $statusInfo  Additional status info.
     * @return  void
     */
    public function setStatusInfo($statusInfo = null);

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