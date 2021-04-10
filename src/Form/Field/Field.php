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
use GreenFedora\Form\FormInterface;
use GreenFedora\Form\Field\Label;
use GreenFedora\Form\Field\FieldsetOpen;
use GreenFedora\Form\Field\FieldsetClose;

use GreenFedora\Form\Field\Exception\InvalidArgumentException;

/**
 * Form field class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Field extends Html implements FieldInterface
{
    /**
     * Parent form.
     * @var FormInterface
     */
    protected $form = null;

    /**
     * Field value.
     * @var mixed
     */
    protected $value = null;

    /**
     * Set the autofocus.
     * @var bool
     */
    protected $autofocus = false;

    /**
     * Label for autolabel.
     * @var Label
     */
    protected $label = null;

    /**
     * Wrap the field in something?
     * @var string|null
     */
    protected $wrap = null;

    /**
     * Allow auto wrap?
     * @var bool
     */
    protected $allowAutoWrap = false;

    /**
     * Constructor.
     * 
     * @param   FormInterface       $form           Parent form.
     * @param   string              $tag            HTML tag.
     * @param   array               $params         Parameters.
     * @param   bool                $autoLabel      Automatically add a label?
     * @param   bool                $allowAutoWrap  Allow auto wrapping?
     * @return  void
     * @throws  InvalidArgumentException
     */
    public function __construct(FormInterface $form, string $tag, array $params = [],
        bool $autoLabel = false, bool $allowAutoWrap = false)
    {
        $this->form = $form;
        if (array_key_exists('name', $params) and !array_key_exists('id', $params)) {
            $params['id'] = $params['name'];
        }
        if ($autoLabel) {
            if (!$params['label']) {
                throw new InvalidArgumentException(sprintf("Autolabel form fields must have the 'label' parameter (%s)", $this->tag));
            } else {
                $this->label = new Label($form, ['for' => $params['name']]);
                $this->label->setData($params['label']);
                unset($params['label']);
            }
        }

        $this->allowAutoWrap = $allowAutoWrap;

        if ($this->allowAutoWrap) {
            if (array_key_exists('wrap', $params)) {
                $this->wrap = $params['wrap'];
                unset($params['wrap']);
            } else {
                $ar = $form->getAutoWrap();
                if (null !== $ar) {
                    $this->wrap = $ar;
                }
            }
        }

        parent::__construct($tag, $params);
    }

    /**
     * Set the name.
     * 
     * @param   string      $name   Name to set.
     * @return  FieldInterface
     */
    public function setName(string $name): FieldInterface
    {
        $this->setParam('name', $name);
        return $this;
    }

    /**
     * Get the name.
     * 
     * @return  string
     * @throws  InvalidArgumentException
     */
    public function getName(): string
    {
        if (!$this->hasParam('name')) {
            throw new InvalidArgumentException(sprintf("Field (type = %s) does not have a 'name'", $this->tag));
        }
        return $this->getParam('name');
    }

    /**
     * Set the field value.
     * 
     * @param   mixed   $value      Value to set.
     * @return  FieldInterface
     */
    public function setValue($value): FieldInterface
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Get the value.
     * 
     * @return  mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the autofocus.
     * 
     * @param   bool    $autofocus  Value to set.
     * @return  FieldInterface
     */
    public function setAutofocus(bool $autofocus = true): FieldInterface
    {
        $this->autofocus = $autofocus;
        return $this;
    }

    /**
     * Render the field.
     * 
     * @param   string  $data   data to use.
     * @return  string          Rendered form HTML.
     */
    public function render(?string $data = null): string
    {
        if ($this->autofocus) {
            $this->setParam('autofocus', true);
        }

        $ret = '';

        if ($this->allowAutoWrap and $this->wrap) {
            $w = $this->form->createField($this->wrap . 'open', $this->getName() . $this->wrap . 'open', []);
            $ret .= $w->render();
        }


        if (null !== $this->label) {
            $ret .= $this->label->render();
        }

        $ret .= parent::render($data) . PHP_EOL;

        if ($this->allowAutoWrap and $this->wrap) {
            $w = $this->form->createField($this->wrap . 'close', $this->getName() . $this->wrap . 'close', []);
            $ret .= $w->render();
        }

        return $ret;
    }

}