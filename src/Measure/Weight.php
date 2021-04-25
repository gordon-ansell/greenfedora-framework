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
namespace GreenFedora\Measure;


/**
 * Weight measure.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Weight
{
    const KGMULT = 0.45359237;

    /**
     * Value.
     * @var float
     */
    protected $value = 0;

    /**
     * Units.
     * @var string
     */
    protected $units = 'kg';

    /**
     * Constructor.
     * 
     * @param   mixed   $value  Value.
     * @param   string  $units  Units.
     * @return  void
     */
    public function __construct($value, string $units = 'kg')
    {
        if (is_array($value)) {
            $this->value = floatval($value[0]);
            $this->units = $value[1];
        } else {
            $this->value = floatval($value);
            $this->units = $units;
        }
    }

    /**
     * Get the value in particular units.
     * 
     * @param   string  $units  Units.
     * @return  float
     */
    public function asUnits(string $units): float
    {
        if ($units == $this->units) {
            return $this->value;
        } else if ('kg' == $units) {
            return $this->value * self::KGMULT;
        } else {
            return $this->value / self::KGMULT;
        }
    }

    /**
     * Get the value as kg.
     * 
     * @return  float
     */
    public function kg(): float
    {
        return $this->asUnits('kg');
    }

    /**
     * Get the value as lb.
     * 
     * @return  float
     */
    public function lb(): float
    {
        return $this->asUnits('lb');
    }
}