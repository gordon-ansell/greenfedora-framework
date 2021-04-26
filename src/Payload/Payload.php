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

use GreenFedora\Payload\PayloadInterface;
use GreenFedora\Arr\Arr;

/**
 * Payload of data.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Payload extends Arr implements PayloadInterface
{
    /**
     * Payload status.
     * @var mixed
     */
    protected $_payloadStatus = null;

    /**
     * Payload status info.
     * @var mixed
     */
    protected $_payloadStatusInfo = null;

    /**
     * Get the payload status.
     * 
     * @return  mixed  
     */
    public function getStatus()
    {
        return $this->_payloadStatus;
    }

    /**
     * Get the payload status info.
     * 
     * @return  mixed  
     */
    public function getStatusInfo()
    {
        return $this->_payloadStatusInfo;
    }

    /**
     * Set the form submitted.
     * 
     * @param   string  $form   Form name.
     * @return  void
     */
    public function setFormSubmitted(string $name)
    {
        $this->setStatus(self::FORM_SUBMITTED, $name);
    }

    /**
     * Set the payload status.
     * 
     * @param   mixed  $status      Status to set.
     * @param   mixed  $statusInfo  Additional status info.
     * @return  void
     */
    public function setStatus($status, $statusInfo = null)
    {
        $this->_payloadStatus = $status;
        $this->_payloadStatusInfo = $statusInfo;
    }

    /**
     * Set the payload status info.
     * 
     * @param   mixed  $statusInfo  Additional status info.
     * @return  void
     */
    public function setStatusInfo($statusInfo = null)
    {
        $this->_payloadStatusInfo = $statusInfo;
    }

    /**
     * Set some values from an array.
     * 
     * @param   array           $source     Source array.
     * @param   array|null      $defaults   Defaults array.
     * @param   iterable|null   $keysFrom   Keys to set.
     * @return  PayloadInterface
     */
    public function setFrom(array $source, ?array $defaults = null, ?iterable $keysFrom = null): PayloadInterface
    {
        if (is_null($keysFrom)) {
            if (!is_null($defaults)) {
                $keysFrom = array_keys($defaults);
            } else {
                $keysFrom = array_keys($source);
            }
        }

        foreach ($keysFrom as $key) {
            $val = null;
            if (array_key_exists($key, $source)) {
                $val = $source[$key];
            } else if (array_key_exists($key, $defaults)) {
                $val = $defaults[$key];
            }
            $this->set($key, $val);
        }
        return $this;
    }
}