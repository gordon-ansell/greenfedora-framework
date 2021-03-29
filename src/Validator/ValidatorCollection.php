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
namespace GreenFedora\Validator;

use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;

/**
 * Validator collection.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class ValidatorCollection
{
    /**
     * Validators.
     * @var array
     */
    protected $validators = [];

    /**
     * Error.
     * @var string
     */
    protected $error = null;

    /**
     * Constructor.
     * 
     * @param   array    $validators     Validators to add.
     * @return  void 
     */
    public function __construct(?array $validators = null)
    {
        if (null !== $validators) {
            foreach ($validators as $validator) {
                $this->add($validator);
            }
        }
    }

    /**
     * Add a validator.
     * 
     * @param   ValidatorInterface  $validator  Validator to add.
     * @return  self
     */
    public function add(ValidatorInterface $validator): self
    {
        $this->validators[] = $validator;
        return $this;
    }

    /**
     * Perform the validation.
     * 
     * @param   mixed       $data       Data to validate.
     * @return  bool                    True if it's valid, else false. 
     */
    public function validate($data) : bool
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($data)) {
                $this->error = $validator->getError();
                return false;
            }
        }
        return true;
    }

    /**
     * Get the error.
     * 
     * @return string                   Error message.
     */
    public function getError(): ?string
    {
        return $this->error;
    }
}