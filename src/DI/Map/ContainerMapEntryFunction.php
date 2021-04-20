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
     * @param   array|null  $funcparams     Function parameters.
     * @param   bool        $injectable     Is this injectable?
     * @return  void
     */
    public function __construct(string $key, callable $value, ?array $funcparams = null, bool $injectable = true)
    {
        parent::__construct($key, ContainerMapEntry::TYPE_FUNCTION, $value, null, $funcparams, $injectable);
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
        if ('value' == $key) {
            if (is_null($this->data['funcparams'])) {
                return call_user_func($this->data['value']);
            } else {
                $p = (is_array($this->data['funfparams'])) ? $this->data['funfparams'] : [$this->data['funfparams']];
                return call_user_func_array($this->data['value'], $p);
            }
        }
        return parent::get($key);
    }
}
