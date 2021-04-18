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
namespace GreenFedora\DI;

use GreenFedora\DI\ContainerMapEntry;

/**
 * Dependency injection container map entry.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ContainerMapEntryValue extends ContainerMapEntry
{
    /**
     * Constructor.
     * 
     * @param   string      $key        Index key.
     * @param   mixed       $value      Value.
     * @return  void
     */
    public function __construct(string $key, $value)
    {
        parent::__construct($key, ContainerMapEntry::TYPE_VALUE, $value);
    }
}
