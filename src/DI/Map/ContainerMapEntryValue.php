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

class ContainerMapEntryValue extends ContainerMapEntry
{
    /**
     * Constructor.
     * 
     * @param   string      $key        Index key.
     * @param   mixed       $value      Value.
     * @param   bool        $injectable     Is this injectable?
     * @return  void
     */
    public function __construct(string $key, $value, bool $injectable = true)
    {
        parent::__construct($key, ContainerMapEntry::TYPE_VALUE, $value, null, $injectable);
    }
}
