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
namespace GreenFedora\Payload;

use GreenFedora\Payload\PayloadInterface;
use GreenFedora\Arr\Arr;
use GreenFedora\Arr\ArrInterface;

/**
 * Payload of data.
 *
 * @author Gordon Ansell <contact@gordonansell.com>
 */

class Payload extends Arr implements PayloadInterface, ArrInterface
{
}