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
 * Abstract validator.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

abstract class AbstractValidator
{
    /**
     * Options.
     * @var iterable
     */
    protected $options = null;

    /**
     * Error.
     * @var string
     */
    protected $error = null;

    /**
     * Replacements.
     * @var array
     */
    protected $reps = [];

    /**
     * Constructor.
     * 
     * @param   array       $reps       Replacements.
     * @param   iterable    $options    Validation options.
     * @return  void 
     */
    public function __construct(array $reps = [], ?iterable $options = null)
    {
        $this->options = $options;
        $this->reps = $reps;
    }

    /**
     * Perform the validation.
     * 
     * @param   mixed       $data       Data to validate.
     * @return  bool                    True if it's valid, else false. 
     */
    abstract public function validate($data) : bool;

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