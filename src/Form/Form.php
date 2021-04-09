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

use GreenFedora\Html\Html;
use GreenFedora\Form\Field\FieldInterface;
use GreenFedora\Form\Field\Field;
use GreenFedora\Form\Field\FieldsetOpen;
use GreenFedora\Form\Field\FieldsetClose;
use GreenFedora\Form\Field\DivOpen;
use GreenFedora\Form\Field\DivClose;
use GreenFedora\Form\Field\Label;
use GreenFedora\Form\Field\Input;

use GreenFedora\Form\Exception\InvalidArgumentException;

/**
 * Form class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Form extends Html implements FormInterface
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
     * @param   string              $method     Method.
     * @return  void
     */
    public function __construct(string $action, string $method = 'POST')
    {
        $p = array(
            'action' => $action,
            'method' => $method,
        );

        parent::__construct('form', $p);
    }

    /**
     * Add a field.
     * 
     * @param   string|FieldInterface       $type       Field type ot class instance.
     * @param   string|null                 $name       Field name.
     * @param   array                       $params     Field parameters.
     * 
     * @return  FormInterface
     * @throws  InvalidArgumentException
     */
    public function addField(string $type, ?string $name = null, array $params = []): FormInterface
    {
        if ($type instanceof FieldInterface) {
            $name = $type->getName();
        } else {
            if (null === $name) {
                if (array_key_exists('name', $params)) {
                    $name = $params['name'];
                }
            }
        }

        if (null === $name) {
            throw new InvalidArgumentException(sprintf("Could not determine name for form field (type: %s)", $type));
        } else if (array_key_exists($name, $this->fields)) {
            throw new InvalidArgumentException(sprintf("A form field with the name '%s' already exists", $name));
        }

        return $this->setField($type, $name, $params);
    }

    /**
     * Set a field.
     * 
     * @param   string|FieldInterface       $type       Field type ot class instance.
     * @param   string|null                 $name       Field name.
     * @param   array                       $params     Field parameters.
     * 
     * @return  FormInterface
     */
    public function setField(string $type, ?string $name = null, array $params = []): FormInterface
    {
        $ret = null;

        if ($type instanceof FieldInterface) {
            $ret = $type;
            $name = $type->getName();
        } else {
            if (null === $name) {
                if (array_key_exists('name', $params)) {
                    $name = $params['name'];
                }
            } else {
                $params['name'] = $name;
            }

            switch (strtolower($type)) {

                case 'divopen':
                    $ret = new DivOpen($params);
                    break;

                case 'divclose':
                    $ret = new DivClose($params);
                    break;

                case 'fieldsetopen':
                    $ret = new FieldsetOpen($params);
                    break;

                case 'fieldsetclose':
                    $ret = new FieldsetClose($params);
                    break;
            
                case 'label':
                    $ret = new Label($params);
                    break;

                case 'input':
                    $ret = new Input($params);
                    break;

                default:
                    throw new InvalidArgumentException(sprintf("'%s' is an invalid form field type", $type));
                    break;
            }
        }

        $this->fields[$name] = $ret;
        return $this; 
    }


}