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
use GreenFedora\DI\Map\ContainerMapEntryClass;

/**
 * Dependency injection container map entry.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ContainerMapEntrySingleton extends ContainerMapEntryClass
{
    /**
     * Constructor.
     * 
     * @param   string      $key            Index key.
     * @param   mixed       $value          Value.
     * @param   array|null  $arguments      Arguments.
     * @param   bool        $injectable     Is this injectable?
     * @param   object|null $instance       Instance.
     * @return  void
     */
    public function __construct(string $key, $value, ?array $arguments = null, bool $injectable = true, $instance = null)
    {
        parent::__construct($key, $value, $arguments, $injectable);
        $this->data['type'] = ContainerMapEntry::TYPE_SINGLETON;
        $this->data['instance'] = $instance;
    }
}
