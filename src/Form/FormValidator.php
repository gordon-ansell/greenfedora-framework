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

use GreenFedora\Validator\ValidatorCollection;
use GreenFedora\Validator\ValidatorInterface;
use GreenFedora\Filter\FilterCollection;
use GreenFedora\Filter\FilterInterface;

use GreenFedora\Form\Exception\InvalidArgumentException;

/**
 * Form validator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class FormValidator
{
    /**
     * Validators
     * @var array
     */
    protected $validators = [];

    /**
     * Filters.
     * @var array
     */
    protected $filters = [];

    /**
     * Constructor.
     * 
     * @return  void 
     */
    public function __construct()
    {
    } 

    /**
     * Add a validator.
     * 
     * @param   string              $field      Form field.
     * @param   ValidatorInterface  $validator  Validator.
     * @return  self
     */
    public function addValidator(string $field, ValidatorInterface $validator): self
    {
        if (!$this->validators[$field]) {
            $this->validators[$field] = new ValidatorCollection();
        }
        $this->validators[$field]->add($validator);
        return $this;
    }

    /**
     * Add a ilter.
     * 
     * @param   string              $field      Form field.
     * @param   FilterInterface     $filter     Filter.
     * @return  self
     */
    public function addFilter(string $field, FilterInterface $filter): self
    {
        if (!$this->filters[$field]) {
            $this->filters[$field] = new FilterCollection();
        }
        $this->filters[$field]->add($filter);
        return $this;
    }

    /**
     * Do the validation.
     * 
     * @param   iterable    $source             Source to check.
     * @param   array       $fields             Fields to check.
     * @return  string|null                     Error message or null if all is well. 
     */
    public function validate(iterable $source, array $fields): ?string
    {
        foreach ($fields as $field) {
            if (!$source[$field]) {
                throw new InvalidArgumentException(sprintf("Field '%s' not found in source for form validation", $field));
            } else {
                $f = $source[$field];

                if ($this->filters[$field]) {
                    $f = $this->filters[$field]->filter($f);
                }

                if ($this->validators[$field]) {
                    if (!$this->validators[$field]->validate($f)) {
                        return $this->validators[$field]->getError();
                    }
                }
            }
        }

        return null;
    }
}