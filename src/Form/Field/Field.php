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
use GreenFedora\Form\Field\FieldInterface;
use GreenFedora\Form\Field\Div;
use GreenFedora\Form\Field\Fieldset;
use GreenFedora\Form\Field\Label;
use GreenFedora\Form\Field\Input;

use GreenFedora\Form\Exception\InvalidArgumentException;

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

    /**
     * Render the field.
     * 
     * @return  string      Rendered form HTML.
     */
    public function render(): string
    {
        echo "Rendering: " .$this->tag . PHP_EOL;
        $data = null;

        if (count($this->fields)) {
            $data = '';
            foreach ($this->fields as $field) {
                if ($data != '') {
                    $data .= PHP_EOL;
                }
                $data .= $field->render() . PHP_EOL;
            }
        }

        $ret = $this->build($data) . PHP_EOL;

        return $ret;
    }

    /**
     * Static field creator.
     * 
     * @param   string  $type   Type of field.
     * @param   array               $params     Parameters.
     * @param   FieldInterface[]    $fields     Subfields.
     * @return  FieldInterface
     * @throws  InvalidArgumentException
     */
    static public function createField(string $type, array $params = [], array $fields = []): FieldInterface
    {
        switch (strtolower($type)) {
            case 'div':
                return new Div($params, $fields);
                break;

            case 'fieldset':
                return new Fieldset($params, $fields);
                break;

            case 'label':
                return new Label($params, $fields);
                break;

            case 'input':
                return new Input($params, $fields);
                break;

            default:
                throw new InvalidArgumentException(sprintf("'%s' is an invalid form field type", $type));
                break;
        }
    }
}