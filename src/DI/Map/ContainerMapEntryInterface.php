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

use GreenFedora\DI\Map\Exception\OutOfBoundsException;

/**
 * Dependency injection container map entry interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 * 
 * @property    string      $key
 * @property    int         $type
 * @property    mixed       $value
 * @property    array|null  $arguments
 * @property    object|null $instance
 */
interface ContainerMapEntryInterface
{
    /**
     * See if the instance has been set up.
     * 
     * @return  bool
     */
    public function hasInstance(): bool;

    /**
     * See if a value matches.
     * 
     * @param   mixed   $match  Thing to match with.
     * @return  bool            
     */
    public function valueMatches($match): bool;

    /**
     * See if this is injectable.
     * 
     * @return  bool
     */
    public function isInjectable(): bool;
    
    /**
     * Setter.
     * 
     * @param   string      $key        Key to set.
     * @param   mixed       $val        Value to set.
     * @return  ContainerMapEntryInterface
     * @throws  OutOfBoundsException
     */
    public function set(string $key, $val): ContainerMapEntryInterface;

    /**
     * Getter.
     * 
     * @param   string      $key        Key to get.
     * @return  mixed
     * @throws  OutOfBoundsException
     */
    public function get(string $key);
}
