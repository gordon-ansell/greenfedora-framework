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

class Select extends Field
{
    /**
     * Select options.
     * @var array
     */
    protected $options = [];

    /**
     * Constructor.
     * 
     * @param   FormInterface       $form           Parent form.
     * @param   array               $params         Parameters.
     * @param   bool                $autoLabel      Autolabel?
     * @param   bool                $allowAutoWrap  Allow auto wrapping?
     * @return  void
     */
    public function __construct(FormInterface $form, array $params = [], bool $autoLabel = true, bool $allowAutoWrap = true)
    {
        if (array_key_exists('options', $params)) {
            $this->options = $params['options'];
            unset($params['options']);
        }
        parent::__construct($form, 'input', $params, $autoLabel, $allowAutoWrap);
    }

}