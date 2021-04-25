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
namespace GreenFedora\Maths;


/**
 * Maths front end.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Maths
{
    /**
     * Round a number to the nearest multiplier.
     *
     * @param   float   $num   Number to round.
     * @param   float   $mult  Multiplier to round it to.
     *
     * @return  float          Rounded number.
     */
    public static function mround(float $num, float $mult): float 
    {
        $multiplier = 1 / $mult;
        return round($num * $multiplier) / $multiplier;
    }
}