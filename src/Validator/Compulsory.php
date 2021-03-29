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

use GreenFedora\Validator\AbstractValidator;
use GreenFedora\Validator\ValidatorInterface;

/**
 * Compulsory validator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Compulsory extends AbstractValidator implements ValidatorInterface
{
    /**
     * Perform the validation.
     * 
     * @param   mixed       $data       Data to validate.
     * @return  bool                    True if it's valid, else false. 
     */
    public function validate($data) : bool
    {
        if ((null === $data) or (empty($data))) {
            $this->error = vsprintf("The '%s' field is compulsory.", $this->reps);
            return false;
        }
        return true;
    }
}