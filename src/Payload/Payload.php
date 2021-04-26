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
use GreenFedora\Arr\ArrInterface;

/**
 * Payload of data.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Payload implements PayloadInterface
{
    /**
     * Payload status.
     * @var mixed
     */
    protected $payloadStatus = null;

    /**
     * Payload status info.
     * @var mixed
     */
    protected $payloadStatusInfo = null;

    /**
     * Payload data.
     * @var ArrInterface
     */
    protected $data = null;

    /**
     * Constructor.
     * 
     * @param   iterable    $data   Input data.
     * @return  void
     */
    public function __construct(iterable $data = [])
    {
        $this->data = new Arr($data);
    }

    /**
     * Get the payload status.
     * 
     * @return  mixed  
     */
    public function getStatus()
    {
        return $this->payloadStatus;
    }

    /**
     * Get the payload status info.
     * 
     * @return  mixed  
     */
    public function getStatusInfo()
    {
        return $this->payloadStatusInfo;
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
     * Check if a form submitted.
     * 
     * @param   string  $form   Form name.
     * @return  bool
     */
    public function isFormSubmitted(string $name): bool
    {
        return (self::FORM_SUBMITTED == $this->getStatus() and $this->getStatusInfo() == $name);
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
        $this->payloadStatus = $status;
        $this->payloadStatusInfo = $statusInfo;
    }

    /**
     * Set the payload status to null.
     * 
     * @return  void
     */
    public function setStatusNull()
    {
        $this->payloadStatus = null;
        $this->payloadStatusInfo = null;
    }

    /**
     * Set the payload status info.
     * 
     * @param   mixed  $statusInfo  Additional status info.
     * @return  void
     */
    public function setStatusInfo($statusInfo = null)
    {
        $this->payloadStatusInfo = $statusInfo;
    }

    /**
     * Get all the data.
     * 
     * @return  ArrInterface
     */
    public function getData(): ArrInterface
    {
        return $this->data;
    }

    /**
     * Set the data.
     * 
     * @param   iterable    $data   Data to set.
     * @return  void
     */
    public function setData(iterable $data)
    {
        $this->data = new Arr($data);
    }

    /**
     * Has. Passes on to the data.
     * 
     * @param   string  $key        Key to check.
     * @return  bool
     */
    public function has(string $key): bool
    {
        return $this->data->has($key);
    }

    /**
     * Get. Passes on to the data.
     * 
     * @param   string  $key        Key to get.
     * @param   mixed   $default    Default to get.
     * @return  mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data->get($key, $default);
    }

    /**
     * Set. Passes on to the data.
     * 
     * @param   string  $key        Key to get.
     * @param   mixed   $value      Value to set.
     * @return  mixed
     */
    public function set(string $key, $value)
    {
        return $this->data->set($key, $value);
    }

    /**
     * Magic getter.
     * 
     * @param   string  $key        Key to get.
     * @return  mixed
     */
    public function __get(string $key)
    {
        return $this->get($key, null);
    }

    /**
     * Magic setter.
     * 
     * @param   string  $key        Key to get.
     * @param   mixed   $value      Value to set.
     * @return  mixed
     */
    public function __set(string $key, $value)
    {
        return $this->set($key, $value);
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
            $this->data->set($key, $val);
        }
        return $this;
    }
}