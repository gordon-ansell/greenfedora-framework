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
namespace GreenFedora\Form\Field;

use GreenFedora\Form\Field\Field;

/**
 * Form field class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Field extends Field
{
    /**
     * Constructor.
     * @param   array               $params     Parameters.
     * @param   FieldInterface[]    $fields     Subfields.
     */
    public function __construct(array $params = [], array $fields = [])
    {
        parent::__construct('div', $params, $fields);
    }

}