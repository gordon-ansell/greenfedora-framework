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

/**
 * Validator interface.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

interface ValidatorInterface
{
    /**
     * Perform the validation.
     * 
     * @param   mixed       $data       Data to validate.
     * @return  bool                    True if it's valid, else false. 
     */
    public function validate($data) : bool;

    /**
     * Get the error.
     * 
     * @return string                   Error message.
     */
    public function getError(): ?string;
}