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

class ContainerMapEntryClass extends ContainerMapEntry
{
    /**
     * Constructor.
     * 
     * @param   string      $key            Index key.
     * @param   mixed       $value          Value.
     * @param   array|null  $argument       Arguments.
     * @param   bool        $injectable     Is this injectable?
     * @return  void
     */
    public function __construct(string $key, $value, ?array $arguments = null, bool $injectable = true)
    {
        parent::__construct($key, ContainerMapEntry::TYPE_CLASS, $value, $arguments, $injectable);
    }

    /**
     * See if a value matches.
     * 
     * @param   mixed   $match  Thing to match with.
     * @return  bool            
     */
    public function valueMatches($match): bool
    {
        echo "Checking " . $this->data['value'] . ' against ' . $match . '<br />' . PHP_EOL;
        if ($match instanceof $this->data['value']) {
            echo "Yup" . '<br />' . PHP_EOL;
        } else {
            echo "Nope" . '<br />' . PHP_EOL;
        }
        return ($this->data['value'] == $match or ($this->data['value'] instanceof $match));
    }
}
