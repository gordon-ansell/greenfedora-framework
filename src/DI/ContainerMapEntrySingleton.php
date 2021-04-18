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
use GreenFedora\DI\Exception\OutOfBoundsException;

/**
 * Dependency injection container map entry.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ContainerMapEntrySingleton extends ContainerMapEntry
{
    /**
     * Constructor.
     * 
     * @param   string      $key        Index key.
     * @param   mixed       $value      Value.
     * @param   array|null  $arguments  Arguments.
     * @param   object|null $instance   Instance.
     * @return  void
     */
    public function __construct(string $key, $value, ?array $arguments = null, $instance = null)
    {
        parent::__construct($key, ContainerMapEntry::TYPE_SINGLETON, $value, $arguments, $instance);
    }

}
