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
use GreenFedora\Form\FormInterface;

/**
 * Form field class.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class DivClose extends Field
{
    /**
     * Value type.
     * @var int
     */
    protected $valueType = self::NO_VALUE;

    /**
     * Constructor.
     * 
     * @param   FormInterface       $form       Parent form.
     * @param   array               $params     Parameters.
     * @return  void
     */
    public function __construct(FormInterface $form, array $params = [])
    {
        parent::__construct($form, 'div', $params);
    }

    /**
     * Render the field.
     * 
     * @param   string  $data   data to use.
     * @return  string          Rendered form HTML.
     */
    public function render(?string $data = null): string
    {
        return parent::renderClose($data) . PHP_EOL;
    }
}