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

use GreenFedora\DI\Map\ContainerMapEntry;

/**
 * Dependency injection container map entry.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ContainerMapEntryFunction extends ContainerMapEntry
{
    /**
     * Constructor.
     * 
     * @param   string      $key            Index key.
     * @param   callable    $value          Value.
     * @param   bool        $injectable     Is this injectable?
     * @return  void
     */
    public function __construct(string $key, callable $value, bool $injectable = true)
    {
        parent::__construct($key, ContainerMapEntry::TYPE_FUNCTION, $value, null, $injectable);
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
}
