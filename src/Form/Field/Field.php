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

use GreenFedora\Html\Html;
use GreenFedora\Form\FieldInterface;

/**
 * Form field class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Field extends Html implements FieldInterface
{
    /**
     * Sub fields.
     * @var FieldInterface[]
     */
    protected $fields = [];

    /**
     * Constructor.
     * @param   string              $tag        HTML tag.
     * @param   array               $params     Parameters.
     * @param   FieldInterface[]    $fields     Subfields.
     */
    public function __construct(string $tag, array $params = [], array $fields = [])
    {
        parent::__construct($tag, $params);
        $this->addFields($fields);
    }

    /**
     * Add a field.
     * 
     * @param   FieldInterface      $field      Field to add.
     * @return  FieldInterface 
     */
    public function addField(FieldInterface $field): FieldInterface
    {
        $this->fields[] = $field;
        return $this;
    }

    /**
     * Add a bunch of fields.
     * 
     * @param   FieldInterface[]    $fields     Fields to add.
     * @return  FieldInterface 
     */
    public function addFields(array $fields = []): FieldInterface
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
    }
}