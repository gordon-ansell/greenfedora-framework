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
namespace GreenFedora\Form;

use GreenFedora\Form\FormInterface;

use GreenFedora\Form\Field\FieldInterface;
use GreenFedora\Form\Field\Field;
use GreenFedora\Form\Field\Div;

use GreenFedora\Form\Exception\InvalidArgumentException;

/**
 * Form class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Form extends Field implements FormInterface
{
    /**
     * Form action.
     * @var string
     */
    protected $action = '';

    /**
     * Form method.
     * @var string
     */
    protected $method = 'POST';

    /**
     * Constructor.
     * 
     * @param   string              $action     Action.
     * @param   FieldInterface[]    $fields     Form fields.
     * @param   string              $method     Method.
     */
    public function __construct(string $action, array $fields = [], string $method = 'POST')
    {
        $p = array(
            'action' => $action,
            'method' => $method,
        );

        parent::__construct('form', $p, $fields);
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
            default:
                throw new InvalidArgumentException(sprintf("'%s' is an invalid form field type", $type));
                break;
        }
    }

}