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
namespace GreenFedora\DI\Map;

use GreenFedora\DI\Map\ContainerMapEntryInterface;
use GreenFedora\DI\Map\Exception\OutOfBoundsException;

/**
 * Dependency injection container map entry.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ContainerMapEntry implements ContainerMapEntryInterface
{
    /**
     * Type constants.
     */
    const TYPE_NONE = 0;
    const TYPE_CLASS = 1;
    const TYPE_SINGLETON = 2;
    const TYPE_VALUE = 3;

    /**
     * The actual data array.
     * @var array
     */
    protected $data = [
        'key'       =>  null,
        'type'      =>  self::TYPE_NONE,
        'value'     =>  null,
        'arguments' =>  null,
        'instance'  =>  null
    ];

    /**
     * Constructor.
     * 
     * @param   string      $key            Index key.
     * @param   int         $type           Entry type.
     * @param   mixed       $value          Value.
     * @param   array|null  $arguments      Arguments.
     * @param   object|null $instance       Instance.
     * @return  void
     */
    public function __construct(string $key, int $type, $value, ?array $arguments = null, ?object $instance = null)
    {
        $this->key = $key;
        $this->type = $type;
        $this->value = $value;
        $this->arguments = $arguments;
        $this->instance = $instance;        
    }

    /**
     * See if the instance has been set up.
     * 
     * @return  bool
     */
    public function hasInstance(): bool
    {
        return (null !== $this->instance);
    }

    /**
     * Setter.
     * 
     * @param   string      $key        Key to set.
     * @param   mixed       $val        Value to set.
     * @return  ContainerMapEntryInterface
     * @throws  OutOfBoundsException
     */
    public function set(string $key, $val): ContainerMapEntryInterface
    {
        if (array_key_exists($key, $this->data)) {
            $this->data[$key] = $val;
            return $this; 
        }
        throw new OutOfBoundsException(sprintf("'%s' is an invalid container map entry field"));
    }

    /**
     * Getter.
     * 
     * @param   string      $key        Key to get.
     * @return  mixed
     * @throws  OutOfBoundsException
     */
    public function get(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key]; 
        }
        throw new OutOfBoundsException(sprintf("'%s' is an invalid container map entry field"));
    }

    /**
     * Magic setter.
     * 
     * @param   string      $key        Key to set.
     * @param   mixed       $val        Value to set.
     * @return  ContainerMapEntryInterface
     * @throws  OutOfBoundsException
     */
    public function __set(string $key, $val)
    {
        return $this->set($key, $val);
    }

    /**
     * Magic getter.
     * 
     * @param   string      $key        Key to get.
     * @return  mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }
}
